<?php

/*
 * This file is part of the Atico/SpreadsheetTranslator package.
 *
 * (c) Samuel Vicent <samuelvicent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atico\SpreadsheetTranslator\Provider\OneDrive;

use GuzzleHttp\Client;
use Atico\SpreadsheetTranslator\Core\Configuration\Configuration;
use Atico\SpreadsheetTranslator\Core\Resource\Resource;

use Atico\SpreadsheetTranslator\Core\Provider\ProviderInterface;

class OneDriveProvider implements ProviderInterface
{
    protected OneDriveConfigurationManager $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = new OneDriveConfigurationManager($configuration);
    }

    public function getProvider(): string
    {
        return 'onedrive';
    }

   public function handleSourceResource(): Resource
    {
        $url = str_replace('/embed', '/download', $this->configuration->getSourceResource());
        $tempLocalResource = $this->configuration->getTempLocalSourceFile();
        $options = [
            'save_to' => $tempLocalResource,
        ];

        $guzzleHttpClient = new Client();
        $guzzleHttpClient->get($url, $options);
        return new Resource($tempLocalResource, $this->configuration->getFormat());
    }
}