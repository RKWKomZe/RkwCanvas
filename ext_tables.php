<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'RKW.RkwCanvas',
            'Canvas',
            'RKW Canvas: Canvas'
        );

    },
    $_EXTKEY
);
