<?php

declare(strict_types=1);

namespace B13\Vgwort\Tests\Functional\Frontend;

/*
 * This file is part of TYPO3 CMS-based extension "vgwort" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Renders a real frontend page and looks at the HTML that comes out: through the
 * `page.1` object of the site set, and through the Fluid partial.
 */
final class PixelRenderingTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['b13/vgwort'];

    protected function setUp(): void
    {
        parent::setUp();
        // The site depends on the extension's site set, which is what puts `page.1` in place.
        $siteDirectory = Environment::getConfigPath() . '/sites/test';
        GeneralUtility::mkdir_deep($siteDirectory);
        file_put_contents($siteDirectory . '/config.yaml', Yaml::dump([
            'rootPageId' => 1,
            'base' => '/',
            'dependencies' => ['b13/vgwort'],
            'languages' => [
                [
                    'languageId' => 0,
                    'title' => 'English',
                    'locale' => 'en_US.UTF-8',
                    'base' => '/',
                ],
            ],
        ], 6));
    }

    public static function pixelCases(): array
    {
        return [
            'pixel set' => ['abc123', 0, 'https://vg08.met.vgwort.de/na/abc123'],
            'no pixel' => ['', 0, null],
        ];
    }

    #[Test]
    #[DataProvider('pixelCases')]
    public function typoScriptOutputFollowsPixelAndIgnoreFlag(string $pixel, int $ignore, ?string $expectedSrc): void
    {
        $html = $this->render($pixel, $ignore);
        $typoScriptOutput = $this->between($html, '<body', '<!--partial-->');

        $this->assertPixel($expectedSrc, $typoScriptOutput);
    }

    #[Test]
    #[DataProvider('pixelCases')]
    public function partialOutputFollowsPixelAndIgnoreFlag(string $pixel, int $ignore, ?string $expectedSrc): void
    {
        $html = $this->render($pixel, $ignore);

        $this->assertPixel($expectedSrc, $this->between($html, '<!--partial-->', '<!--/partial-->'));
    }

    private function render(string $pixel, int $ignore): string
    {
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('pages')
            ->insert('pages', [
                'uid' => 1,
                'pid' => 0,
                'title' => 'Root',
                'slug' => '/',
                'doktype' => 1,
                'is_siteroot' => 1,
                'tx_vgwort_pixel' => $pixel,
                'tx_vgwort_ignore' => $ignore,
            ]);
        $this->setUpFrontendRootPage(1, ['EXT:vgwort/Tests/Functional/Fixtures/Frontend.typoscript']);

        $response = $this->executeFrontendSubRequest(new InternalRequest('http://localhost/'));
        self::assertSame(200, $response->getStatusCode(), substr((string)$response->getBody(), 0, 1500));

        return (string)$response->getBody();
    }

    private function assertPixel(?string $expectedSrc, string $html): void
    {
        if ($expectedSrc === null) {
            self::assertStringNotContainsString('<img', $html);
            return;
        }
        self::assertStringContainsString('src="' . $expectedSrc . '"', $html);
    }

    private function between(string $html, string $start, string $end): string
    {
        $from = strpos($html, $start);
        $to = strpos($html, $end);
        self::assertNotFalse($from, 'Marker not found: ' . $start);
        self::assertNotFalse($to, 'Marker not found: ' . $end);

        return substr($html, $from, $to - $from);
    }
}
