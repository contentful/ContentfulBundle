<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2024 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\ContentfulBundle\Unit\Cache\Delivery;

use Contentful\ContentfulBundle\Cache\Delivery\CacheWarmer;
use Contentful\Delivery\Client;
use Contentful\Tests\ContentfulBundle\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class CacheWarmerTest extends TestCase
{
    public function testWarmer()
    {
        $client = new Client('b4c0n73n7fu1', 'cfexampleapi', 'master');
        $cache = new ArrayAdapter();
        $warmer = new CacheWarmer($client, $cache, false, false);
        $this->assertFalse($warmer->isOptional());

        $warmer->warmUp('');

        $this->assertTrue($cache->hasItem('contentful.DELIVERY.cfexampleapi.master.Space.cfexampleapi.__ALL__'));
        $this->assertJson($cache->getItem('contentful.DELIVERY.cfexampleapi.master.Space.cfexampleapi.__ALL__')->get());
        $this->assertTrue($cache->hasItem('contentful.DELIVERY.cfexampleapi.master.Environment.master.__ALL__'));
        $this->assertJson($cache->getItem('contentful.DELIVERY.cfexampleapi.master.Environment.master.__ALL__')->get());
        $this->assertTrue($cache->hasItem('contentful.DELIVERY.cfexampleapi.master.ContentType.dog.__ALL__'));
        $this->assertJson($cache->getItem('contentful.DELIVERY.cfexampleapi.master.ContentType.dog.__ALL__')->get());
        $this->assertTrue($cache->hasItem('contentful.DELIVERY.cfexampleapi.master.ContentType.human.__ALL__'));
        $this->assertJson($cache->getItem('contentful.DELIVERY.cfexampleapi.master.ContentType.human.__ALL__')->get());
        $this->assertTrue($cache->hasItem('contentful.DELIVERY.cfexampleapi.master.ContentType.cat.__ALL__'));
        $this->assertJson($cache->getItem('contentful.DELIVERY.cfexampleapi.master.ContentType.cat.__ALL__')->get());
    }
}
