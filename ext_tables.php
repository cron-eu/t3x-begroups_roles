<?php
defined('TYPO3_MODE') || die();

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1472569541] =
        \IchHabRecht\BegroupsRoles\Backend\ToolbarItems\RoleSwitcher::class;

    // Flip order of RoleSwitch and UserToolbarItem!
    // Explanation: EXT:backend uses multiple consecutive keys to register several items
    // to be rendered in the backends upper toolbar, so the only way to squeeze a new item in
    // is to change the key of one of these items.
    // 1435433111 is the UserToolbarItem (user avatar with dropdown for 'settings' and 'logout')
    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1472569542] = $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1435433111];
    unset($GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1435433111]);

    // Register hook to adjust current user group
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['begroups_roles'] =
        \IchHabRecht\BegroupsRoles\Hook\SwitchUserRoleHook::class . '->setUserGroup';

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('be_groups', 'EXT:begroups_roles/Resources/Private/Language/locallang_csh.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('be_users', 'EXT:begroups_roles/Resources/Private/Language/locallang_csh.xlf');

    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Imaging\IconRegistry::class)->registerIcon(
        'begroups-roles-switchUserGroup',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        [
            'source' => 'EXT:begroups_roles/Resources/Public/Icons/SwitchUserGroup.png',
        ]
    );
});
