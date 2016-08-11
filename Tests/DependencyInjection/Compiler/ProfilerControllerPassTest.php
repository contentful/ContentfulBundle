<?php
/**
 * @copyright 2016 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\Tests\DependencyInjection\Compiler;

use Contentful\ContentfulBundle\DependencyInjection\Compiler\ProfilerControllerPass;
use Contentful\ContentfulBundle\Tests\ContainerTrait;
use Symfony\Component\DependencyInjection\Definition;

class ProfilerControllerPassTest extends \PHPUnit_Framework_TestCase
{
    use ContainerTrait;

    public function testProcessWhenProfilerIsNotPresent()
    {
        $container = $this->getContainer();
        $compilerPass = new ProfilerControllerPass;

        $container->addCompilerPass($compilerPass);
        $compilerPass->process($container);

        $this->assertFalse($container->hasDefinition('contentful.profiler_controller'));
    }

    public function testProcessWhenProfilerIsPresent()
    {
        $container = $this->getContainer();
        $container->setDefinition('profiler', new Definition());
        $compilerPass = new ProfilerControllerPass;

        $container->addCompilerPass($compilerPass);
        $compilerPass->process($container);

        $this->assertTrue($container->hasDefinition('contentful.profiler_controller'));
    }
}
