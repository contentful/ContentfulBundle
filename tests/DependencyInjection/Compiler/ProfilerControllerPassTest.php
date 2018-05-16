<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\ContentfulBundle\DependencyInjection\Compiler;

use Contentful\ContentfulBundle\DependencyInjection\Compiler\ProfilerControllerPass;
use Contentful\Tests\ContentfulBundle\ContainerTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Definition;

class ProfilerControllerPassTest extends TestCase
{
    use ContainerTrait;

    public function testProcessWhenProfilerIsNotPresent()
    {
        $container = $this->getContainer();
        $compilerPass = new ProfilerControllerPass();

        $container->addCompilerPass($compilerPass);
        $compilerPass->process($container);

        $this->assertFalse($container->hasDefinition('contentful.profiler_controller'));
    }

    public function testProcessWhenProfilerIsPresent()
    {
        $container = $this->getContainer();
        $container->setDefinition('profiler', new Definition());
        $compilerPass = new ProfilerControllerPass();

        $container->addCompilerPass($compilerPass);
        $compilerPass->process($container);

        $this->assertTrue($container->hasDefinition('contentful.profiler_controller'));
    }
}
