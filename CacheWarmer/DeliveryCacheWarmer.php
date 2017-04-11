<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\CacheWarmer;

use Contentful\Delivery\Cache\CacheWarmer;
use Contentful\Delivery\Client;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class DeliveryCacheWarmer extends CacheWarmer implements CacheWarmerInterface
{
    /**
     * @var string
     */
    private $subDirectory;

    /**
     * DeliveryCacheWarmer constructor.
     *
     * @param  Client $client
     * @param  string $cacheSubDirectory
     */
    public function __construct(Client $client, $cacheSubDirectory = '')
    {
        parent::__construct($client);

        $this->subDirectory = $cacheSubDirectory;
    }

    /**
     * @param  string $cacheDir
     */
    public function warmUp($cacheDir)
    {
        if (!empty($this->subDirectory)) {
            $cacheDir .= '/' . $this->subDirectory;
        }

        parent::warmUp($cacheDir);
    }

    /**
     * @return bool
     */
    public function isOptional()
    {
        return false;
    }
}
