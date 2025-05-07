<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\ContentfulBundle\Unit\DependencyInjection;

use Contentful\ContentfulBundle\DependencyInjection\ClientFactory;
use Contentful\Tests\ContentfulBundle\TestCase;
use GuzzleHttp\Client as HttpClient;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class ClientFactoryTest extends TestCase
{
    public function testCreateDelivery()
    {
        $handler = new TestHandler();
        $cache = new ArrayAdapter();
        $config = $this->getConfig($cache, $handler);

        $client = ClientFactory::create($config);

        $this->assertTrue($client->isDeliveryApi());
        $this->assertSame('cfexampleapi', $client->getSpaceId());
        $this->assertSame('master', $client->getEnvironmentId());

        $client->getEntry('nyancat');

        $logs = $handler->getRecords();
        // 0 INFO nyancat      1 DEBUG nyancat
        // 2 INFO locales      3 DEBUG locales
        // 4 INFO space        5 DEBUG space
        // 6 INFO content type 7 DEBUG content type
        $this->assertCount(8, $logs);
        $this->assertSame('INFO', $logs[0]['level_name']);
        $this->assertRegExp('/GET https\:\/\/cdn\.contentful\.com\/spaces\/cfexampleapi\/environments\/master\/entries\/nyancat\?locale\=en\-US \(([0-9]{1,})\.([0-9]{3})s\)/', $logs[0]['message']);
        $this->assertSame('DEBUG', $logs[1]['level_name']);

        $this->assertTrue($cache->hasItem('contentful.DELIVERY.cfexampleapi.master.Space.cfexampleapi.__ALL__'));
        $this->assertJson($cache->getItem('contentful.DELIVERY.cfexampleapi.master.Space.cfexampleapi.__ALL__')->get());
        $this->assertTrue($cache->hasItem('contentful.DELIVERY.cfexampleapi.master.Environment.master.__ALL__'));
        $this->assertJson($cache->getItem('contentful.DELIVERY.cfexampleapi.master.Environment.master.__ALL__')->get());
        $this->assertTrue($cache->hasItem('contentful.DELIVERY.cfexampleapi.master.ContentType.cat.__ALL__'));
        $this->assertJson($cache->getItem('contentful.DELIVERY.cfexampleapi.master.ContentType.cat.__ALL__')->get());
    }

    public function testCreatePreview()
    {
        $config = [
            'token' => 'b4c0n73n7fu1',
            'space' => 'cfexampleapi',
            'environment' => 'master',
            'api' => 'preview',
            'options' => [],
        ];

        $client = ClientFactory::create($config);

        $this->assertTrue($client->isPreviewApi());
        $this->assertSame('cfexampleapi', $client->getSpaceId());
        $this->assertSame('master', $client->getEnvironmentId());
    }

    private function getConfig(CacheItemPoolInterface $cache, HandlerInterface $handler): array
    {
        return [
            'token' => 'b4c0n73n7fu1',
            'space' => 'cfexampleapi',
            'environment' => 'master',
            'api' => 'delivery',
            'options' => [
                'host' => 'https://cdn.contentful.com',
                'locale' => 'en-US',
                'logger' => new Logger('test', [$handler]),
                'client' => new HttpClient(),
                'cache' => [
                    'pool' => $cache,
                    'runtime' => true,
                    'content' => false,
                ],
            ],
        ];
    }
}
