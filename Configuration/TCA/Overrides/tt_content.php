<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        //=================================================================
        // Register Plugins
        //=================================================================
        ExtensionUtility::registerPlugin(
            $extKey,
            'Canvas',
            'RKW Canvas'
        );

        //=================================================================
        // Add Flexforms
        //=================================================================
        // plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
        /**
         * @todo not used at the moment
         */
        /*
        $pluginSignature = str_replace('_','', $extKey) . '_canvas';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        $fileName = 'FILE:EXT:rkw_canvas/Configuration/FlexForms/Canvas.xml';

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignature,
            $fileName
)       ;*/

    },
    'rkw_canvas'
);
