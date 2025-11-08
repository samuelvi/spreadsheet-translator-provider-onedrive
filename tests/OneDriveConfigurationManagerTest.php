<?php

declare(strict_types=1);

/*
 * This file is part of the Atico/SpreadsheetTranslator package.
 *
 * (c) Samuel Vicent <samuelvicent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atico\SpreadsheetTranslator\Provider\OneDrive\Tests;

use Atico\SpreadsheetTranslator\Core\Configuration\Configuration;
use Atico\SpreadsheetTranslator\Core\Configuration\ProviderConfigurationInterface;
use Atico\SpreadsheetTranslator\Core\Provider\DefaultProviderManager;
use Atico\SpreadsheetTranslator\Provider\OneDrive\OneDriveConfigurationManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(OneDriveConfigurationManager::class)]
final class OneDriveConfigurationManagerTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated(): void
    {
        $configuration = $this->createMock(Configuration::class);
        $manager = new OneDriveConfigurationManager($configuration);

        $this->assertInstanceOf(OneDriveConfigurationManager::class, $manager);
    }

    #[Test]
    public function it_implements_provider_configuration_interface(): void
    {
        $configuration = $this->createMock(Configuration::class);
        $manager = new OneDriveConfigurationManager($configuration);

        $this->assertInstanceOf(ProviderConfigurationInterface::class, $manager);
    }

    #[Test]
    public function it_extends_default_provider_manager(): void
    {
        $configuration = $this->createMock(Configuration::class);
        $manager = new OneDriveConfigurationManager($configuration);

        $this->assertInstanceOf(DefaultProviderManager::class, $manager);
    }

    #[Test]
    public function it_inherits_configuration_manager_functionality(): void
    {
        $sourceResource = 'https://onedrive.live.com/embed?id=12345';
        $tempFile = '/tmp/test.xlsx';
        $format = 'xlsx';

        $configuration = $this->createMock(Configuration::class);
        $configuration->method('getOption')
            ->willReturnMap([
                ['source_resource', $sourceResource],
                ['temp_local_source_file', $tempFile],
                ['format', $format],
            ]);

        $manager = new OneDriveConfigurationManager($configuration);

        // Test inherited methods from DefaultProviderManager
        $this->assertSame($sourceResource, $manager->getSourceResource());
        $this->assertSame($tempFile, $manager->getTempLocalSourceFile());
        $this->assertSame($format, $manager->getFormat());
    }
}
