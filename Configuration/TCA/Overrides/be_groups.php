<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

$tempColumns = [
    'tx_begroupsroles_isrole' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:begroups_roles/Resources/Private/Language/locallang_db.xlf:be_groups.tx_begroupsroles_isrole',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ],
    ],
];
ExtensionManagementUtility::addTCAcolumns('be_groups', $tempColumns);
ExtensionManagementUtility::addFieldsToPalette('be_groups', 'tx_begroupsroles', 'tx_begroupsroles_isrole');
ExtensionManagementUtility::addToAllTCAtypes('be_groups', '--palette--;LLL:EXT:begroups_roles/Resources/Private/Language/locallang_db.xlf:be_groups.tx_begroupsroles_title;tx_begroupsroles', '', 'after:subgroup');
