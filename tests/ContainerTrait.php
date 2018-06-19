<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\ContentfulBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

trait ContainerTrait
{
    /**
     * @param string $environment
     *
     * @return ContainerBuilder
     */
    protected function getContainer($environment = 'test'): ContainerBuilder
    {
        return new ContainerBuilder(new ParameterBag([
            'kernel.debug' => false,
            'kernel.bundles' => [],
            'kernel.cache_dir' => \sys_get_temp_dir(),
            'kernel.environment' => $environment,
            'kernel.root_dir' => __DIR__.'/../src/',
        ]));
    }
}
