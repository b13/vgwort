<?php

declare(strict_types=1);

namespace B13\Vgwort\Service;

/*
 * This file is part of TYPO3 CMS-based extension "vgwort" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;

final class VgwortSettingsService
{
    public function __construct(
        private readonly SiteFinder $siteFinder,
    ) {}

    /**
     * Get the VG Wort domain setting (vg01-vg09) for a site
     */
    public function getDomain(int $rootPageUid): string
    {
        return (string)($this->getSetting($rootPageUid, 'vgwort.domain') ?? 'vg08');
    }

    /**
     * Get the API username for a site
     */
    public function getUsername(int $rootPageUid): ?string
    {
        $username = $this->getSetting($rootPageUid, 'vgwort.api.username');

        if ($username === null || $username === '') {
            return null;
        }

        return (string)$username;
    }

    /**
     * Get the API password for a site
     */
    public function getPassword(int $rootPageUid): ?string
    {
        $password = $this->getSetting($rootPageUid, 'vgwort.api.password');

        if ($password === null || $password === '') {
            return null;
        }

        return (string)$password;
    }

    /**
     * Check if API credentials are configured for a site
     */
    public function hasCredentials(int $rootPageUid): bool
    {
        return $this->getUsername($rootPageUid) !== null
            && $this->getPassword($rootPageUid) !== null;
    }

    /**
     * Check if API is enabled for a site
     */
    public function isApiEnabled(int $rootPageUid): bool
    {
        return (bool)($this->getSetting($rootPageUid, 'vgwort.api.enabled') ?? false);
    }

    /**
     * Check if test mode is enabled for a site
     */
    public function isTestMode(int $rootPageUid): bool
    {
        return (bool)($this->getSetting($rootPageUid, 'vgwort.api.testMode') ?? false);
    }

    /**
     * Get the full VG Wort pixel URL base
     */
    public function getPixelUrlBase(int $rootPageUid): string
    {
        $domain = $this->getDomain($rootPageUid);
        return sprintf('https://%s.met.vgwort.de/na/', $domain);
    }

    /**
     * Check if this site uses a publisher account (Verlagskonto)
     * When true, publishers can participate in royalties
     * When false, submissions are always made without publisher involvement
     */
    public function isPublisherAccount(int $rootPageUid): bool
    {
        return (bool)($this->getSetting($rootPageUid, 'vgwort.publisher') ?? false);
    }

    /**
     * Get a single setting value by its dot-notation key
     */
    private function getSetting(int $rootPageUid, string $key): mixed
    {
        if ($rootPageUid <= 0) {
            return null;
        }

        try {
            $site = $this->siteFinder->getSiteByRootPageId($rootPageUid);
            return $site->getSettings()->get($key);
        } catch (SiteNotFoundException $e) {
            // Site not found or settings not available
        }

        return null;
    }
}
