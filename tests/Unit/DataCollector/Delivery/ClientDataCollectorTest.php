<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\ContentfulBundle\Unit\DataCollector\Delivery;

use Contentful\ContentfulBundle\DataCollector\Delivery\ClientDataCollector;
use Contentful\Delivery\Client;
use Contentful\Tests\ContentfulBundle\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientDataCollectorTest extends TestCase
{
    public function testGetData()
    {
        $client = new Client('b4c0n73n7fu1', 'cfexampleapi', 'master');
        $configurations = [
            ['service' => 'contentful.delivery.default_client', 'cache' => \false],
        ];

        $client->getSpace();
        $client->getEnvironment();
        $client->getContentTypes();

        $dataCollector = new ClientDataCollector([$client], $configurations);
        $dataCollector->collect(new Request(), new Response());

        $this->assertSame('contentful', $dataCollector->getName());

        $expected = [
            [
                'api' => 'DELIVERY',
                'space' => 'cfexampleapi',
                'environment' => 'master',
                'service' => 'contentful.delivery.default_client',
                'cache' => \false,
            ],
        ];
        $this->assertSame($expected, $dataCollector->getClients());
        $this->assertCount(3, $dataCollector->getMessages());
        $this->assertSame(3, $dataCollector->getRequestCount());
        $this->assertGreaterThan(0, $dataCollector->getTotalDuration());
        $this->assertSame(0, $dataCollector->getErrorCount());
    }
}
