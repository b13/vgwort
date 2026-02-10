<?php

declare(strict_types=1);

namespace B13\Vgwort\Tests\Unit\Service;

/*
 * This file is part of TYPO3 CMS-based extension "vgwort" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use B13\Vgwort\Service\VgwortSettingsService;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class VgwortSettingsServiceTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    private function createSiteWithSettings(array $settings, int $rootPageId = 1): Site
    {
        return new Site(
            'test-site',
            $rootPageId,
            [
                'base' => 'https://example.com/',
                'settings' => $settings,
                'languages' => [
                    [
                        'languageId' => 0,
                        'title' => 'English',
                        'locale' => 'en_US.UTF-8',
                        'base' => '/',
                    ],
                ],
            ]
        );
    }

    #[Test]
    public function getDomainReturnsDefaultWhenSiteNotFound(): void
    {
        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->willThrowException(new SiteNotFoundException('Site not found'));

        $service = new VgwortSettingsService($siteFinder);

        self::assertSame('vg08', $service->getDomain(1));
    }

    #[Test]
    public function getDomainReturnsDefaultWhenNotConfigured(): void
    {
        $site = $this->createSiteWithSettings([]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertSame('vg08', $service->getDomain(1));
    }

    #[Test]
    public function getDomainReturnsConfiguredValue(): void
    {
        $site = $this->createSiteWithSettings([
            'vgwort' => [
                'domain' => 'vg05',
            ],
        ]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertSame('vg05', $service->getDomain(1));
    }

    #[Test]
    public function isApiEnabledReturnsFalseWhenSiteNotFound(): void
    {
        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->willThrowException(new SiteNotFoundException('Site not found'));

        $service = new VgwortSettingsService($siteFinder);

        self::assertFalse($service->isApiEnabled(1));
    }

    #[Test]
    public function isApiEnabledReturnsFalseWhenNotConfigured(): void
    {
        $site = $this->createSiteWithSettings([]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertFalse($service->isApiEnabled(1));
    }

    #[Test]
    public function isApiEnabledReturnsTrueWhenEnabled(): void
    {
        $site = $this->createSiteWithSettings([
            'vgwort' => [
                'api' => [
                    'enabled' => true,
                ],
            ],
        ]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertTrue($service->isApiEnabled(1));
    }

    #[Test]
    public function isTestModeReturnsFalseByDefault(): void
    {
        $site = $this->createSiteWithSettings([]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertFalse($service->isTestMode(1));
    }

    #[Test]
    public function isTestModeReturnsTrueWhenEnabled(): void
    {
        $site = $this->createSiteWithSettings([
            'vgwort' => [
                'api' => [
                    'testMode' => true,
                ],
            ],
        ]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertTrue($service->isTestMode(1));
    }

    #[Test]
    public function getPixelUrlBaseReturnsCorrectUrl(): void
    {
        $site = $this->createSiteWithSettings([
            'vgwort' => [
                'domain' => 'vg03',
            ],
        ]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertSame('https://vg03.met.vgwort.de/na/', $service->getPixelUrlBase(1));
    }

    #[Test]
    public function getPixelUrlBaseReturnsDefaultDomain(): void
    {
        $site = $this->createSiteWithSettings([]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertSame('https://vg08.met.vgwort.de/na/', $service->getPixelUrlBase(1));
    }

    #[Test]
    public function getDomainReturnsDefaultForZeroPageUid(): void
    {
        $siteFinder = $this->createMock(SiteFinder::class);

        $service = new VgwortSettingsService($siteFinder);

        self::assertSame('vg08', $service->getDomain(0));
    }

    #[Test]
    public function getUsernameReturnsConfiguredValue(): void
    {
        $site = $this->createSiteWithSettings([
            'vgwort' => [
                'api' => [
                    'username' => 'test-user',
                ],
            ],
        ]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertSame('test-user', $service->getUsername(1));
    }

    #[Test]
    public function getUsernameReturnsNullWhenNotConfigured(): void
    {
        $site = $this->createSiteWithSettings([]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertNull($service->getUsername(1));
    }

    #[Test]
    public function getPasswordReturnsConfiguredValue(): void
    {
        $site = $this->createSiteWithSettings([
            'vgwort' => [
                'api' => [
                    'password' => 'test-pass',
                ],
            ],
        ]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertSame('test-pass', $service->getPassword(1));
    }

    #[Test]
    public function hasCredentialsReturnsTrueWhenBothConfigured(): void
    {
        $site = $this->createSiteWithSettings([
            'vgwort' => [
                'api' => [
                    'username' => 'test-user',
                    'password' => 'test-pass',
                ],
            ],
        ]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertTrue($service->hasCredentials(1));
    }

    #[Test]
    public function hasCredentialsReturnsFalseWhenOnlyUsernameConfigured(): void
    {
        $site = $this->createSiteWithSettings([
            'vgwort' => [
                'api' => [
                    'username' => 'test-user',
                ],
            ],
        ]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertFalse($service->hasCredentials(1));
    }

    #[Test]
    public function hasCredentialsReturnsFalseWhenNothingConfigured(): void
    {
        $site = $this->createSiteWithSettings([]);

        $siteFinder = $this->createMock(SiteFinder::class);
        $siteFinder->method('getSiteByRootPageId')->with(1)->willReturn($site);

        $service = new VgwortSettingsService($siteFinder);

        self::assertFalse($service->hasCredentials(1));
    }
}
