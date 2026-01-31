<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

$tempColumns = [
    'tx_begroupsroles_enabled' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:begroups_roles/Resources/Private/Language/locallang_db.xlf:be_users.tx_begroupsroles_enabled',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ],
    ],
    'tx_begroupsroles_limit' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:begroups_roles/Resources/Private/Language/locallang_db.xlf:be_users.tx_begroupsroles_limit',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ],
    ],
];
ExtensionManagementUtility::addTCAcolumns('be_users', $tempColumns);
ExtensionManagementUtility::addFieldsToPalette('be_users', 'tx_begroupsroles', 'tx_begroupsroles_enabled, tx_begroupsroles_limit');
ExtensionManagementUtility::addToAllTCAtypes('be_users', '--palette--;LLL:EXT:begroups_roles/Resources/Private/Language/locallang_db.xlf:be_users.tx_begroupsroles_title;tx_begroupsroles', '', 'after:usergroup');
