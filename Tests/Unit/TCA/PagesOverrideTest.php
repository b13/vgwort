<?php

declare(strict_types=1);

namespace B13\Vgwort\Tests\Unit\TCA;

/*
 * This file is part of TYPO3 CMS-based extension "vgwort" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Loads the TCA override the way TYPO3 does and looks at what it leaves in $GLOBALS['TCA'].
 */
final class PagesOverrideTest extends UnitTestCase
{
    protected bool $backupGlobals = true;

    protected function setUp(): void
    {
        parent::setUp();
        unset($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['vgwort']);
        $GLOBALS['TCA']['pages'] = [
            'columns' => [],
            'types' => [
                '1' => ['showitem' => 'title,lastUpdated'],
                '116' => ['showitem' => 'title,lastUpdated'],
                '254' => ['showitem' => 'title,lastUpdated'],
            ],
        ];
    }

    private function loadOverride(): void
    {
        require __DIR__ . '/../../../Configuration/TCA/Overrides/pages.php';
    }

    #[Test]
    public function tabIsAddedToStandardPagesByDefault(): void
    {
        $this->loadOverride();

        self::assertStringContainsString('vgwort_tracking', $GLOBALS['TCA']['pages']['types']['1']['showitem']);
        self::assertStringNotContainsString('vgwort_tracking', $GLOBALS['TCA']['pages']['types']['116']['showitem']);
        self::assertStringNotContainsString('vgwort_tracking', $GLOBALS['TCA']['pages']['types']['254']['showitem']);
    }

    #[Test]
    public function tabIsAddedToConfiguredDoktypes(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['vgwort']['contentDoktypes'] = [1, 116];

        $this->loadOverride();

        self::assertStringContainsString('vgwort_tracking', $GLOBALS['TCA']['pages']['types']['1']['showitem']);
        self::assertStringContainsString('vgwort_tracking', $GLOBALS['TCA']['pages']['types']['116']['showitem']);
        self::assertStringNotContainsString('vgwort_tracking', $GLOBALS['TCA']['pages']['types']['254']['showitem']);
    }

    #[Test]
    public function pixelFieldFitsItsDatabaseColumnAndIsTrimmed(): void
    {
        $this->loadOverride();

        $config = $GLOBALS['TCA']['pages']['columns']['tx_vgwort_pixel']['config'];
        self::assertSame(50, $config['max']);
        self::assertSame('trim', $config['eval']);
    }

    #[Test]
    public function pixelFieldIsHiddenWhilePageIsIgnored(): void
    {
        $this->loadOverride();

        self::assertSame(
            'FIELD:tx_vgwort_ignore:=:0',
            $GLOBALS['TCA']['pages']['columns']['tx_vgwort_pixel']['displayCond']
        );
    }
}
