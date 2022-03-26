<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2022 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\ContentfulBundle\Unit\Cache\Delivery;

use Contentful\ContentfulBundle\Cache\Delivery\CacheClearer;
use Contentful\Delivery\Client;
use Contentful\Tests\ContentfulBundle\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class CacheClearerTest extends TestCase
{
    public function testClearer()
    {
        $cachePool = new ArrayAdapter();
        $this->prefillCache($cachePool);

        $this->assertTrue($cachePool->hasItem('contentful.DELIVERY.cfexampleapi.master.Space.cfexampleapi.__ALL__'));
        $this->assertTrue($cachePool->hasItem('contentful.DELIVERY.cfexampleapi.master.Environment.master.__ALL__'));
        $this->assertTrue($cachePool->hasItem('contentful.DELIVERY.cfexampleapi.master.ContentType.dog.__ALL__'));
        $this->assertTrue($cachePool->hasItem('contentful.DELIVERY.cfexampleapi.master.ContentType.human.__ALL__'));
        $this->assertTrue($cachePool->hasItem('contentful.DELIVERY.cfexampleapi.master.ContentType.cat.__ALL__'));

        $clearer = new CacheClearer(
            new Client('b4c0n73n7fu1', 'cfexampleapi', 'master'),
            $cachePool
        );
        $clearer->clear('');

        $this->assertFalse($cachePool->hasItem('contentful.DELIVERY.cfexampleapi.master.Space.cfexampleapi.__ALL__'));
        $this->assertFalse($cachePool->hasItem('contentful.DELIVERY.cfexampleapi.master.Environment.master.__ALL__'));
        $this->assertFalse($cachePool->hasItem('contentful.DELIVERY.cfexampleapi.master.ContentType.dog.__ALL__'));
        $this->assertFalse($cachePool->hasItem('contentful.DELIVERY.cfexampleapi.master.ContentType.human.__ALL__'));
        $this->assertFalse($cachePool->hasItem('contentful.DELIVERY.cfexampleapi.master.ContentType.cat.__ALL__'));
    }

    private function prefillCache(CacheItemPoolInterface $cache): void
    {
        $this->createCacheItem(
            $cache,
            'contentful.DELIVERY.cfexampleapi.master.Space.cfexampleapi.__ALL__',
            '{"sys":{"id":"cfexampleapi","type":"Space"},"name":"Contentful Example API"}',
        );
        $this->createCacheItem(
            $cache,
            'contentful.DELIVERY.cfexampleapi.master.Environment.master.__ALL__',
            '{"sys":{"id":"master","type":"Environment"},"locales":[{"sys":{"id":"2oQPjMCL9bQkylziydLh57","type":"Locale","version":1},"code":"en-US","default":true,"name":"English","fallbackCode":null},{"sys":{"id":"3zpZmkZrHTIekHmXgflXaV","type":"Locale","version":0},"code":"tlh","default":false,"name":"Klingon","fallbackCode":"en-US"}]}',
        );
        $this->createCacheItem(
            $cache,
            'contentful.DELIVERY.cfexampleapi.master.ContentType.dog.__ALL__',
            '{"sys":{"id":"dog","type":"ContentType","revision":2,"createdAt":"2013-06-27T22:46:13.498Z","updatedAt":"2013-09-02T14:32:11.837Z","environment":{"sys":{"type":"Link","id":"master","linkType":"Environment"}},"space":{"sys":{"type":"Link","id":"cfexampleapi","linkType":"Space"}}},"name":"Dog","description":"Bark!","displayField":"name","fields":[{"id":"name","name":"Name","type":"Text","required":true,"localized":false},{"id":"description","name":"Description","type":"Text","required":false,"localized":false},{"id":"image","name":"Image","type":"Link","required":false,"localized":false,"linkType":"Asset"}]}',
        );
        $this->createCacheItem(
            $cache,
            'contentful.DELIVERY.cfexampleapi.master.ContentType.human.__ALL__',
            '{"sys":{"id":"human","type":"ContentType","revision":3,"createdAt":"2013-06-27T22:46:14.133Z","updatedAt":"2013-09-02T15:10:26.818Z","environment":{"sys":{"type":"Link","id":"master","linkType":"Environment"}},"space":{"sys":{"type":"Link","id":"cfexampleapi","linkType":"Space"}}},"name":"Human","description":null,"displayField":"name","fields":[{"id":"name","name":"Name","type":"Text","required":true,"localized":false},{"id":"description","name":"Description","type":"Text","required":false,"localized":false},{"id":"likes","name":"Likes","type":"Array","required":false,"localized":false,"items":{"type":"Symbol"}},{"id":"image","name":"Image","type":"Array","required":false,"localized":false,"disabled":true,"items":{"type":"Link","linkType":"Asset"}}]}',
        );
        $this->createCacheItem(
            $cache,
            'contentful.DELIVERY.cfexampleapi.master.ContentType.cat.__ALL__',
            '{"sys":{"id":"cat","type":"ContentType","revision":8,"createdAt":"2013-06-27T22:46:12.852Z","updatedAt":"2017-07-06T09:58:52.691Z","environment":{"sys":{"type":"Link","id":"master","linkType":"Environment"}},"space":{"sys":{"type":"Link","id":"cfexampleapi","linkType":"Space"}}},"name":"Cat","description":"Meow.","displayField":"name","fields":[{"id":"name","name":"Name","type":"Text","required":true,"localized":true},{"id":"likes","name":"Likes","type":"Array","required":false,"localized":false,"items":{"type":"Symbol"}},{"id":"color","name":"Color","type":"Symbol","required":false,"localized":false},{"id":"bestFriend","name":"Best Friend","type":"Link","required":false,"localized":false,"linkType":"Entry"},{"id":"birthday","name":"Birthday","type":"Date","required":false,"localized":false},{"id":"lifes","name":"Lifes left","type":"Integer","required":false,"localized":false,"disabled":true},{"id":"lives","name":"Lives left","type":"Integer","required":false,"localized":false},{"id":"image","name":"Image","type":"Link","required":false,"localized":false,"linkType":"Asset"}]}',
        );
    }

    private function createCacheItem(CacheItemPoolInterface $cache, string $key, string $value): void
    {
        $cacheItem = $cache->getItem($key);
        $cacheItem->set($value);
        $cache->save($cacheItem);
    }
}
