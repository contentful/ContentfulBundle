<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2020 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\ContentfulBundle\Cache\Delivery;

use Contentful\Delivery\Cache\CacheWarmer as SdkCacheWarmer;
use Contentful\Delivery\Client;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class CacheWarmer implements CacheWarmerInterface
{
    /**
     * @var SdkCacheWarmer
     */
    private $warmer;

    /**
     * @var bool
     */
    private $autoWarmup;

    /**
     * @var bool
     */
    private $cacheContent;

    /**
     * DeliveryCacheWarmer constructor.
     */
    public function __construct(Client $client, CacheItemPoolInterface $cacheItemPool, bool $autoWarmup, bool $cacheContent)
    {
        $this->warmer = new SdkCacheWarmer($client, $client->getResourcePool(), $cacheItemPool);
        $this->autoWarmup = $autoWarmup;
        $this->cacheContent = $cacheContent;
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        $this->warmer->warmUp($this->cacheContent);
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional(): bool
    {
        // If the cache can be filled at runtime,
        // it means that the warmer is optional.

        return $this->autoWarmup;
    }
}
