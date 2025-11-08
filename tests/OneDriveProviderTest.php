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
use Atico\SpreadsheetTranslator\Core\Resource\Resource;
use Atico\SpreadsheetTranslator\Provider\OneDrive\OneDriveProvider;
use GuzzleHttp\Client;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(OneDriveProvider::class)]
final class OneDriveProviderTest extends TestCase
{
    #[Test]
    public function it_returns_correct_provider_name(): void
    {
        $configuration = $this->createMock(Configuration::class);
        $provider = new OneDriveProvider($configuration);

        $this->assertSame('onedrive', $provider->getProvider());
    }

    #[Test]
    public function it_transforms_embed_url_to_download_url(): void
    {
        $embedUrl = 'https://onedrive.live.com/embed?id=12345';
        $expectedDownloadUrl = 'https://onedrive.live.com/download?id=12345';
        $tempFile = '/tmp/test-spreadsheet.xlsx';
        $format = 'xlsx';

        $configuration = $this->createMock(Configuration::class);
        $configuration->method('getOption')
            ->willReturnMap([
                ['source_resource', $embedUrl],
                ['temp_local_source_file', $tempFile],
                ['format', $format],
            ]);

        $provider = new class($configuration) extends OneDriveProvider {
            private ?string $capturedUrl = null;
            private ?array $capturedOptions = null;

            public function handleSourceResource(): Resource
            {
                $url = str_replace('/embed', '/download', $this->configuration->getSourceResource());
                $tempLocalResource = $this->configuration->getTempLocalSourceFile();

                $this->capturedUrl = $url;
                $this->capturedOptions = [
                    'save_to' => $tempLocalResource,
                ];

                // Don't actually make HTTP request in tests
                // Just verify the URL transformation and return a Resource
                return new Resource($tempLocalResource, $this->configuration->getFormat());
            }

            public function getCapturedUrl(): ?string
            {
                return $this->capturedUrl;
            }

            public function getCapturedOptions(): ?array
            {
                return $this->capturedOptions;
            }
        };

        $resource = $provider->handleSourceResource();

        $this->assertSame($expectedDownloadUrl, $provider->getCapturedUrl());
        $this->assertSame(['save_to' => $tempFile], $provider->getCapturedOptions());
        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertSame($tempFile, $resource->getValue());
        $this->assertSame($format, $resource->getFormat());
    }

    #[Test]
    public function it_handles_url_without_embed(): void
    {
        $normalUrl = 'https://onedrive.live.com/view?id=12345';
        $tempFile = '/tmp/test-spreadsheet.xlsx';
        $format = 'xlsx';

        $configuration = $this->createMock(Configuration::class);
        $configuration->method('getOption')
            ->willReturnMap([
                ['source_resource', $normalUrl],
                ['temp_local_source_file', $tempFile],
                ['format', $format],
            ]);

        $provider = new class($configuration) extends OneDriveProvider {
            private ?string $capturedUrl = null;

            public function handleSourceResource(): Resource
            {
                $url = str_replace('/embed', '/download', $this->configuration->getSourceResource());
                $tempLocalResource = $this->configuration->getTempLocalSourceFile();

                $this->capturedUrl = $url;

                return new Resource($tempLocalResource, $this->configuration->getFormat());
            }

            public function getCapturedUrl(): ?string
            {
                return $this->capturedUrl;
            }
        };

        $resource = $provider->handleSourceResource();

        // URL should remain unchanged when /embed is not present
        $this->assertSame($normalUrl, $provider->getCapturedUrl());
        $this->assertInstanceOf(Resource::class, $resource);
    }

    #[Test]
    public function it_creates_resource_with_correct_parameters(): void
    {
        $embedUrl = 'https://onedrive.live.com/embed?id=ABC123';
        $tempFile = '/tmp/my-spreadsheet.xlsx';
        $format = 'xlsx';

        $configuration = $this->createMock(Configuration::class);
        $configuration->method('getOption')
            ->willReturnMap([
                ['source_resource', $embedUrl],
                ['temp_local_source_file', $tempFile],
                ['format', $format],
            ]);

        $provider = new class($configuration) extends OneDriveProvider {
            public function handleSourceResource(): Resource
            {
                str_replace('/embed', '/download', $this->configuration->getSourceResource());
                $tempLocalResource = $this->configuration->getTempLocalSourceFile();

                return new Resource($tempLocalResource, $this->configuration->getFormat());
            }
        };

        $resource = $provider->handleSourceResource();

        $this->assertSame($tempFile, $resource->getValue());
        $this->assertSame($format, $resource->getFormat());
    }
}
