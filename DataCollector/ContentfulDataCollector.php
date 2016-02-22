<?php
/**
 * @copyright 2016 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\DataCollector;

use Contentful\Log\ArrayLogger;
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

    public function __construct(ArrayLogger $logger, array $clients = [])
    {
        $this->logger = $logger;
        $this->clients = $clients;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'logs' => $this->logger->getLogs(),
            'clients' => $this->clients
        ];
    }

    public function getLogs()
    {
        return $this->data['logs'];
    }

    public function getRequestCount()
    {
        return count($this->data['logs']);
    }

    public function getTotalDuration()
    {
        return array_reduce($this->data['logs'], function($carry, $item) {
            $duration = $item->getDuration();

            return $carry + $duration;
        }, 0);
    }

    public function getClients()
    {
        return $this->data['clients'];
    }

    public function getName()
    {
        return 'contentful';
    }
}
