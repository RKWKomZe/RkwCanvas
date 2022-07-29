<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3_MODE') || die('Access denied.');

$extKey = 'rkw_canvas';

//=================================================================
// Register Plugins
//=================================================================
ExtensionUtility::registerPlugin(
    $extKey,
    'Canvas',
    'RKW Canvas'
);

