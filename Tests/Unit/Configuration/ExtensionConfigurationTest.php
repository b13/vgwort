<?php

declare(strict_types=1);

namespace B13\Vgwort\Tests\Unit\Configuration;

/*
 * This file is part of TYPO3 CMS-based extension "vgwort" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use B13\Vgwort\Configuration\ExtensionConfiguration;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class ExtensionConfigurationTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        unset($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['vgwort']);
    }

    #[Test]
    public function standardPagesAreTheDefault(): void
    {
        self::assertSame([1], ExtensionConfiguration::getContentDoktypes());
    }

    #[Test]
    public function configuredDoktypesAreReturned(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['vgwort']['contentDoktypes'] = [1, 116];

        self::assertSame([1, 116], ExtensionConfiguration::getContentDoktypes());
    }

    #[Test]
    public function doktypesAreCastToIntegers(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['vgwort']['contentDoktypes'] = ['1', '116'];

        self::assertSame([1, 116], ExtensionConfiguration::getContentDoktypes());
    }
}
