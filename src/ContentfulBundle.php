<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle;

use Contentful\ContentfulBundle\DependencyInjection\Compiler\ProfilerControllerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContentfulBundle extends Bundle
{
    const VERSION = '2.1.0-dev';

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ProfilerControllerPass());
    }
}
