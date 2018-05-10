<?php
/**
 * @copyright 2016 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\DataCollector;

use Contentful\Log\ArrayLogger;
use Contentful\Log\LogEntry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class ContentfulDataCollector extends DataCollector
{
    /**
     * @var ArrayLogger
     */
    private $logger;

    /**
     * @var array
     */
    private $clients = [];

    /**
     * ContentfulDataCollector constructor.
     *
     * @param ArrayLogger $logger
     * @param array       $clients
     */
    public function __construct(ArrayLogger $logger, array $clients = [])
    {
        $this->logger = $logger;
        $this->clients = $clients;
    }

    /**
     * @param  Request         $request
     * @param  Response        $response
     * @param  \Exception|null $exception
     *
     * @return void
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'logs' => $this->logger->getLogs(),
            'clients' => $this->clients
        ];
    }

    /**
     * @return LogEntry[]
     */
    public function getLogs()
    {
        return $this->data['logs'];
    }

    /**
     * @return int
     */
    public function getRequestCount()
    {
        return count($this->data['logs']);
    }

    /**
     * @return float
     */
    public function getTotalDuration()
    {
        return array_reduce($this->data['logs'], function($carry, LogEntry $item) {
            $duration = $item->getDuration();

            return $carry + $duration;
        }, 0.0);
    }

    public function getErrorCount()
    {
        return array_reduce($this->data['logs'], function($carry, LogEntry $item) {
            return $carry + ($item->isError() ? 1 : 0);
        }, 0);
    }

    /**
     * @return array
     */
    public function getClients()
    {
        return $this->data['clients'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contentful';
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->data['logs'] = [];
    }
}
