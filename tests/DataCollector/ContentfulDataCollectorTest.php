<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\ContentfulBundle\DataCollector;

use Contentful\ContentfulBundle\DataCollector\ContentfulDataCollector;
use Contentful\Log\ArrayLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentfulDataCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetClients()
    {
        $arrayLogger = new ArrayLogger();
        $clients = [
            'delivery' => [
                'default_client' => 'foo',
                'clients' => [
                    'foo' => ['space' => 'abc', 'token' => '123'],
                    'bar' => ['space' => 'def', 'token' => '456', 'preview' => true],
                ],
            ],
        ];

        $dataCollector = new ContentfulDataCollector($arrayLogger, $clients);

        $dataCollector->collect(new Request(), new Response());

        $this->assertSame($clients, $dataCollector->getClients());
    }
}
