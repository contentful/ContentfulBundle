<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\CacheClearer;

use Contentful\Delivery\Cache\CacheClearer;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

class DeliveryCacheClearer implements CacheClearerInterface
{
    /**
     * @var CacheClearer
     */
    private $contentfulClearer;

    /**
     * DeliveryCacheClearer constructor.
     */
    public function __construct(CacheItemPoolInterface $cacheItemPool)
    {
        $this->contentfulClearer = new CacheClearer($cacheItemPool);
    }

    public function clear($cacheDir)
    {
        $this->contentfulClearer->clear();
    }
}
