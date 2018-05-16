<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\Cache;

use Contentful\Delivery\Cache\CacheWarmer;
use Contentful\Delivery\Client;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class DeliveryCacheWarmer implements CacheWarmerInterface
{
    /**
     * @var bool
     */
    private $autoWarmup = false;

    /**
     * @var CacheWarmer
     */
    private $warmer;

    /**
     * DeliveryCacheWarmer constructor.
     *
     * @param Client                 $client
     * @param CacheItemPoolInterface $cacheItemPool
     * @param bool                   $autoWarmup
     */
    public function __construct(Client $client, CacheItemPoolInterface $cacheItemPool, $autoWarmup)
    {
        $this->warmer = new CacheWarmer($client, $cacheItemPool);
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        return $this->warmer->warmUp();
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return $this->autoWarmup;
    }
}
