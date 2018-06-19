<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\ContentfulBundle\DataCollector;

use Contentful\ContentfulBundle\DataCollector\ContentfulDataCollector;
use Contentful\Delivery\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentfulDataCollectorTest extends TestCase
{
    public function testGetClients()
    {
        $client = new Client('b4c0n73n7fu1', 'cfexampleapi', 'master');
        $configurations = [
            ['service' => 'default.client', 'cache' => false],
        ];

        $client->getSpace();
        $client->getEnvironment();
        $client->getContentTypes();

        $dataCollector = new ContentfulDataCollector([$client], $configurations);
        $dataCollector->collect(new Request(), new Response());

        $this->assertSame('contentful', $dataCollector->getName());

        $expected = [
            [
                'api' => 'DELIVERY',
                'space' => 'cfexampleapi',
                'environment' => 'master',
                'service' => 'default.client',
                'cache' => false,
            ],
        ];
        $this->assertSame($expected, $dataCollector->getClients());
        $this->assertCount(3, $dataCollector->getMessages());
        $this->assertSame(3, $dataCollector->getRequestCount());
        $this->assertGreaterThan(0, $dataCollector->getTotalDuration());
        $this->assertSame(0, $dataCollector->getErrorCount());
    }
}
