<?php
namespace IchHabRecht\BegroupsRoles\Backend\ToolbarItems;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Nicole Cordes <cordes@cps-it.de>, CPS-IT GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Toolbar\RequestAwareToolbarItemInterface;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Renders roles switcher to toolbar
 */
class RoleSwitcher implements ToolbarItemInterface, RequestAwareToolbarItemInterface
{
    /**
     * @var BackendUserAuthentication
     */
    protected $backendUser;

    /**
     * @var LanguageService
     */
    protected $languageService;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var array
     */
    protected $groups = [];

    private ServerRequestInterface $request;

    /**
     * @var int
     */
    protected $role = 0;

    public function __construct(
        private readonly BackendViewFactory $backendViewFactory,
        private readonly IconFactory $iconFactory,
        private readonly UriBuilder $uriBuilder
    ) {
        $this->backendUser = $GLOBALS['BE_USER'];
        $this->languageService = $GLOBALS['LANG'];
        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->backendUser->user_table);
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * Checks whether the user has access to this toolbar item
     *
     * @return  bool
     */
    public function checkAccess()
    {
        if (empty($this->backendUser->user['tx_begroupsroles_enabled'])) {
            return false;
        }

        $this->role = (int)$this->backendUser->getSessionData('tx_begroupsroles_role');

        $queryBuilder = $this->connection->createQueryBuilder();
        $expressionBuilder = $queryBuilder->expr();
        $rows = $queryBuilder->select('uid', 'title')
            ->from($this->backendUser->usergroup_table)
            ->where(
                $expressionBuilder->in(
                    'uid',
                    $queryBuilder->createNamedParameter(
                        GeneralUtility::intExplode(',', $this->backendUser->user['tx_begroupsroles_groups'] ?? '', true),
                        Connection::PARAM_INT_ARRAY
                    )
                ),
                $expressionBuilder->eq(
                    'tx_begroupsroles_isrole',
                    $queryBuilder->createNamedParameter(1, \TYPO3\CMS\Core\Database\Connection::PARAM_INT)
                )
            )
            ->orderBy('title')
            ->executeQuery()
            ->fetchAllAssociative();

        $this->groups = array_combine(array_map(intval(...), array_column($rows, 'uid')), $rows);

        return !empty($this->groups);
    }

    /**
     * Render "item" part of this toolbar
     *
     * @return string
     */
    public function getItem()
    {
        $view = $this->backendViewFactory->create($this->request);
        $view->assign('group', $this->groups[$this->role] ?? null);
        return $view->render('ToolbarItems/RoleSwitcher');
    }

    /**
     * TRUE if this toolbar item has a collapsible drop down
     *
     * @return bool
     */
    public function hasDropDown()
    {
        return true;
    }

    /**
     * Render "drop down" part of this toolbar
     *
     * @return string Drop down HTML
     */
    public function getDropDown()
    {
        $view = $this->backendViewFactory->create($this->request);
        $view->assignMultiple([
            'user' => $this->backendUser,
            'role' => $this->role,
            'groups' => $this->groups,
        ]);
        return $view->render('ToolbarItems/RoleSwitcherDropdown');
    }

    /**
     * Returns an array with additional attributes added to containing <li> tag of the item.
     *
     * @return array
     */
    public function getAdditionalAttributes()
    {
        return [];
    }

    /**
     * Returns an integer between 0 and 100 to determine the position of this item relative to others
     *
     * @return int
     */
    public function getIndex()
    {
        return 80;
    }

    /**
     * This is being called via AJAX when user choose a role to switch to.
     */
    public function switchRoleAction(ServerRequestInterface $request): ResponseInterface
    {
        $newRole = (int)($GLOBALS['TYPO3_REQUEST']->getParsedBody()['role'] ?? null);
        if ($newRole <= 0 || !GeneralUtility::inList($this->backendUser->user['tx_begroupsroles_groups'], $newRole)) {
            $newRole = 0;
        }

        $this->backendUser->setAndSaveSessionData('tx_begroupsroles_role', $newRole);

        return new JsonResponse([
            'redirectUrl' => (string)$this->uriBuilder->buildUriFromRoute('main'),
        ]);
    }
}
