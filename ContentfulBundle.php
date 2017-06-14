<?php
/**
 * @copyright 2016 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle;

use Contentful\ContentfulBundle\DependencyInjection\Compiler\ProfilerControllerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContentfulBundle extends Bundle
{
    const VERSION = '2.0.0-dev';

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ProfilerControllerPass());
    }
}
