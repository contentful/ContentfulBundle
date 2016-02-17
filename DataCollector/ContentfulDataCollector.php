<?php
/**
 * @copyright 2016 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class ContentfulDataCollector extends DataCollector
{
    /**
     * @var array
     */
    private $clients = [];

    public function __construct(array $clients = [])
    {
        $this->clients = $clients;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'clients' => $this->clients
        ];
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
