<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\Cache;

use Contentful\Delivery\Cache\CacheClearer;
use Contentful\Delivery\Client;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

class DeliveryCacheClearer implements CacheClearerInterface
{
    /**
     * @var CacheClearer
     */
    private $clearer;

    /**
     * DeliveryCacheClearer constructor.
     *
     * @param Client                 $client
     * @param CacheItemPoolInterface $cacheItemPool
     */
    public function __construct(Client $client, CacheItemPoolInterface $cacheItemPool)
    {
        $this->clearer = new CacheClearer($client, $cacheItemPool);
    }

    /**
     * {@inheritdoc}
     */
    public function clear($cacheDir)
    {
        $this->clearer->clear();
    }
}
