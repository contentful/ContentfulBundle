<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\CacheClearer;

use Contentful\Delivery\Cache\CacheClearer;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

class DeliveryCacheClearer extends CacheClearer implements CacheClearerInterface
{
    /**
     * @var string
     */
    private $subDirectory;

    /**
     * DeliveryCacheClearer constructor.
     *
     * @param string $spaceId
     * @param string $cacheSubDirectory
     */
    public function __construct($spaceId, $cacheSubDirectory = '')
    {
        parent::__construct($spaceId);

        $this->subDirectory = $cacheSubDirectory;
    }

    /**
     * @param string $cacheDir
     */
    public function clear($cacheDir)
    {
        if (!empty($this->subDirectory)) {
            $cacheDir .= '/'.$this->subDirectory;
        }

        parent::clear($cacheDir);
    }
}
