<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\ContentfulBundle;

use Contentful\Tests\TestCase as BaseTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class TestCase extends BaseTestCase
{
    /**
     * @param string $environment
     */
    protected function getContainer($environment = 'test'): ContainerBuilder
    {
        return new ContainerBuilder(new ParameterBag([
            'kernel.debug' => \false,
            'kernel.bundles' => [],
            'kernel.cache_dir' => \sys_get_temp_dir(),
            'kernel.environment' => $environment,
            'kernel.root_dir' => __DIR__.'/../src/',
        ]));
    }
}
