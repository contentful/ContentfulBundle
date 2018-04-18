<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\CacheWarmer;

use Contentful\Delivery\Cache\CacheWarmer;
use Contentful\Delivery\Client;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class DeliveryCacheWarmer implements CacheWarmerInterface
{
    /**
     * @var bool
     */
    private $autoWarmup;

    /**
     * @var CacheWarmer
     */
    private $contentfulWarmer;

    /**
     * DeliveryCacheWarmer constructor.
     *
     * @param Client $client
     * @param CacheItemPoolInterface $cacheItemPool
     * @param bool $autoWarmup
     */
    public function __construct(Client $client, CacheItemPoolInterface $cacheItemPool, $autoWarmup)
    {
        $this->contentfulWarmer = new CacheWarmer($client, $cacheItemPool);
        $this->autoWarmup = $autoWarmup;
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        $this->contentfulWarmer->warmUp();
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return $this->autoWarmup;
    }
}
