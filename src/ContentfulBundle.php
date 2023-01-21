<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2023 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\ContentfulBundle;

use Contentful\ContentfulBundle\DependencyInjection\Compiler\ProfilerControllerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContentfulBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ProfilerControllerPass());
    }
}
