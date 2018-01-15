<?php
/**
 * @copyright 2016 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\Tests\DataCollector;

use Contentful\ContentfulBundle\DataCollector\ContentfulDataCollector;
use Contentful\Log\ArrayLogger;
use Contentful\Log\LogEntry;
use Prophecy\Prophecy\ObjectProphecy;
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
                    'bar' => ['space' => 'def', 'token' => '456', 'preview' => true]
                ]
            ]
        ];

        $dataCollector = new ContentfulDataCollector($arrayLogger, $clients);

        $dataCollector->collect(new Request, new Response);

        $this->assertEquals($clients, $dataCollector->getClients());
    }

    public function testReset()
    {
        $logEntry = $this->prophesize(LogEntry::class);
        $expectedLogs = [$logEntry->reveal()];

        /** @var ArrayLogger|ObjectProphecy $logger */
        $logger = $this->prophesize(ArrayLogger::class);
        $logger
            ->getLogs()
            ->willReturn($expectedLogs);

        /** @var Request|ObjectProphecy $request */
        $request = $this->prophesize(Request::class);
        /** @var Response|ObjectProphecy $response */
        $response = $this->prophesize(Response::class);

        // initialize collector
        $collector = new ContentfulDataCollector($logger->reveal());

        // check whether collector retrieves log entries from logger
        $collector->collect($request->reveal(), $response->reveal());
        $this->assertSame($expectedLogs, $collector->getLogs());

        // check whether collectors log entries are empty after resetting
        $collector->reset();
        $this->assertSame([], $collector->getLogs());
    }
}
