<?php

declare(strict_types=1);

namespace B13\Vgwort\Configuration;

/*
 * This file is part of TYPO3 CMS-based extension "vgwort" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Core\Domain\Repository\PageRepository;

/**
 * Provides access to extension configuration values.
 */
final class ExtensionConfiguration
{
    /**
     * Get the list of page doktypes that should have VG Wort tracking.
     *
     * By default, only standard pages (doktype 1) are included.
     * Integrators can extend this in their ext_localconf.php:
     *
     *   $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['vgwort']['contentDoktypes'][] = 116;
     *
     * @return int[]
     */
    public static function getContentDoktypes(): array
    {
        $doktypes = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['vgwort']['contentDoktypes']
            ?? [PageRepository::DOKTYPE_DEFAULT];

        return array_map('intval', $doktypes);
    }
}
