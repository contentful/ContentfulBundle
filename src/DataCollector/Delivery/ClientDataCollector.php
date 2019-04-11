<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\ContentfulBundle\DataCollector\Delivery;

use Contentful\Core\Api\Message;
use Contentful\Delivery\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class ClientDataCollector extends DataCollector
{
    /**
     * @var Client[]
     */
    private $clients = [];

    /**
     * @param Client[] $clients        This is actually a Generator, but it behaves as an array of Client objects
     * @param array    $configurations
     */
    public function __construct($clients = [], $configurations = [])
    {
        $this->reset();

        foreach ($clients as $index => $client) {
            $config = \array_shift($configurations);
            $this->data['clients'][] = [
                'api' => $client->getApi(),
                'space' => $client->getSpaceId(),
                'environment' => $client->getEnvironmentId(),
                'service' => $config['service'],
                'cache' => $config['cache'],
            ];

            $this->clients[] = $client;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = \null)
    {
        $messages = [];
        foreach ($this->clients as $client) {
            $messages = \array_merge($messages, $client->getMessages());
        }

        $this->data['messages'] = $messages;
    }

    /**
     * @return array
     */
    public function getClients(): array
    {
        return $this->data['clients'];
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        return $this->data['messages'];
    }

    /**
     * @return int
     */
    public function getRequestCount(): int
    {
        return \count($this->data['messages']);
    }

    /**
     * @return float
     */
    public function getTotalDuration(): float
    {
        return \array_reduce($this->data['messages'], function (float $carry, Message $message) {
            return $carry + $message->getDuration();
        }, 0.0);
    }

    /**
     * @return int
     */
    public function getErrorCount(): int
    {
        return \array_reduce($this->data['messages'], function (int $carry, Message $message) {
            return $carry + ($message->isError() ? 1 : 0);
        }, 0);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'contentful';
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->data = [
            'messages' => [],
            'clients' => [],
        ];
    }
}
