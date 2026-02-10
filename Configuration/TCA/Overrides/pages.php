<?php

/*
 * This file is part of TYPO3 CMS-based extension "vgwort" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

call_user_func(function () {
    ExtensionManagementUtility::addTCAcolumns('pages', [
        'tx_vgwort_ignore' => [
            'label' => 'vgwort.db:pages.tx_vgwort_ignore',
            'description' => 'vgwort.db:pages.tx_vgwort_ignore.description',
            'l10n_mode' => 'exclude',
            'onChange' => 'reload',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
        ],
        'tx_vgwort_pixel' => [
            'label' => 'vgwort.db:pages.tx_vgwort_pixel',
            'description' => 'vgwort.db:pages.tx_vgwort_pixel.description',
            'l10n_mode' => 'exclude',
            'displayCond' => 'FIELD:tx_vgwort_ignore:=:0',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 50,
            ],
        ],
    ]);

    // Add VG Wort tab only to configured content doktypes
    // Default is doktype 1 (standard page), integrators can extend via ext_localconf.php
    $contentDoktypes = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['vgwort']['contentDoktypes'] ?? [1];

    foreach ($contentDoktypes as $doktype) {
        ExtensionManagementUtility::addToAllTCAtypes(
            'pages',
            '--div--;vgwort.db:pages.tabs.vgwort,--palette--;;vgwort_tracking',
            (string)$doktype,
            'after:lastUpdated',
        );
    }

    $GLOBALS['TCA']['pages']['palettes']['vgwort_tracking'] = [
        'label' => 'vgwort.db:pages.palettes.vgwort_tracking',
        'showitem' => 'tx_vgwort_ignore,--linebreak--,tx_vgwort_pixel',
    ];
});
