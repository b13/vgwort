<?php

/*
 * This file is part of TYPO3 CMS-based extension "vgwort" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

declare(strict_types=1);

use TYPO3\CMS\Core\Domain\Repository\PageRepository;

defined('TYPO3') or die();

// Define which page doktypes should have VG Wort tracking fields.
// By default, only standard pages (doktype 1) are included.
// Integrators can add custom doktypes in their ext_localconf.php:
//   $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['vgwort']['contentDoktypes'][] = 116;
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['vgwort']['contentDoktypes'] ??= [
    PageRepository::DOKTYPE_DEFAULT, // 1 - Standard page
];

// Exclude extraction parameter from cHash calculation
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'vgwort-markers';
