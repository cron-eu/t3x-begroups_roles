<?php

use IchHabRecht\BegroupsRoles\Hook\SwitchUserRoleHook;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;

defined('TYPO3') || die();

call_user_func(function () {
    // Register hook to adjust current user group
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['begroups_roles'] =
        SwitchUserRoleHook::class . '->setUserGroup';
});
