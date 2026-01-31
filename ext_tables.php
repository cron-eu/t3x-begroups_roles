<?php
use IchHabRecht\BegroupsRoles\Backend\ToolbarItems\RoleSwitcher;
use IchHabRecht\BegroupsRoles\Hook\SwitchUserRoleHook;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;

defined('TYPO3') || die();

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1472569541] =
        RoleSwitcher::class;

    // Flip order of RoleSwitch and UserToolbarItem!
    // Explanation: EXT:backend uses multiple consecutive keys to register several items
    // to be rendered in the backends upper toolbar, so the only way to squeeze a new item in
    // is to change the key of one of these items.
    // 1435433111 is the UserToolbarItem (user avatar with dropdown for 'settings' and 'logout')
    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1472569542] = $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1435433111];
    unset($GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1435433111]);

    // Register hook to adjust current user group
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['begroups_roles'] =
        SwitchUserRoleHook::class . '->setUserGroup';

    GeneralUtility::makeInstance(IconRegistry::class)->registerIcon(
        'begroups-roles-switchUserGroup',
        BitmapIconProvider::class,
        [
            'source' => 'EXT:begroups_roles/Resources/Public/Icons/SwitchUserGroup.png',
        ]
    );
});
