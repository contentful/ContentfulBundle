<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\ContentfulBundle\Cache\Delivery;

use Contentful\Delivery\Cache\CacheClearer as SdkCacheClearer;
use Contentful\Delivery\Client;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

class CacheClearer implements CacheClearerInterface
{
    /**
     * @var SdkCacheClearer
     */
    private $clearer;

    /**
     * DeliveryCacheClearer constructor.
     */
    public function __construct(Client $client, CacheItemPoolInterface $cacheItemPool)
    {
        $this->clearer = new SdkCacheClearer($client, $client->getResourcePool(), $cacheItemPool);
    }

    public function clear($cacheDir): void
    {
        $this->clearer->clear();
    }
}
