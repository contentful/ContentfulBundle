<?php
/**
 * @copyright 2016 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\Tests\DependencyInjection;

use Contentful\ContentfulBundle\DependencyInjection\ContentfulExtension;
use Contentful\ContentfulBundle\Tests\ContainerTrait;

class ContentfulExtensionTest extends \PHPUnit_Framework_TestCase
{
    use ContainerTrait;

    public function testLoadEmpty()
    {
        $container = $this->getContainer();
        $extension = new ContentfulExtension;

        $container->registerExtension($extension);

        $extension->load([[], [], []], $container);

        // The parameter 'contentful.clients' has to be defined for the InfoCommand to work.
        $this->assertEquals([], $container->getParameter('contentful.clients'));
    }

    public function testLoadDeliveryDefault()
    {
        $container = $this->getContainer();
        $extension = new ContentfulExtension;

        $container->registerExtension($extension);

        $extension->load([
            [],
            [
                'delivery' => [
                    'space' => 'abc',
                    'token' => '123'
                ]
            ],
            []
        ], $container);

        $definition = $container->getDefinition('contentful.delivery.default_client');

        $this->assertEquals('123', $definition->getArgument(0));
        $this->assertEquals('abc', $definition->getArgument(1));
    }

    public function testLoadDeliveryOne()
    {
        $container = $this->getContainer();
        $extension = new ContentfulExtension();

        $container->registerExtension($extension);

        $extension->load([
            [],
            [
                'delivery' => [
                    'clients' => ['default' => ['space' => 'abc', 'token' => '123']]
                ]
            ],
            []
        ], $container);

        $definition = $container->getDefinition('contentful.delivery.default_client');

        $this->assertEquals('123', $definition->getArgument(0));
        $this->assertEquals('abc', $definition->getArgument(1));

        $this->assertEquals([
            'default' => [
                'service' => 'contentful.delivery.default_client',
                'api' => 'DELIVERY',
                'space' => 'abc'
            ]
        ], $container->getParameter('contentful.clients'));
    }

    public function testLoadDeliveryImplicitDefault()
    {
        $container = $this->getContainer();
        $extension = new ContentfulExtension();

        $container->registerExtension($extension);

        $extension->load([
            [],
            [
                'delivery' => [
                    'clients' => ['foo' => ['space' => 'abc', 'token' => '123']]
                ]
            ],
            []
        ], $container);

        $this->assertEquals('contentful.delivery.foo_client', $container->getAlias('contentful.delivery'));
    }

    public function testLoadDeliveryAlternateDefault()
    {
        $container = $this->getContainer();
        $extension = new ContentfulExtension;

        $container->registerExtension($extension);

        $extension->load([
            [],
            [
                'delivery' => [
                    'default_client' => 'foo',
                    'clients' => ['foo' => ['space' => 'abc', 'token' => '123']]
                ]
            ],
            []
        ], $container);

        $this->assertEquals('foo', $container->getParameter('contentful.delivery.default_client'));

        $definition = $container->getDefinition('contentful.delivery.foo_client');

        $this->assertEquals('123', $definition->getArgument(0));
        $this->assertEquals('abc', $definition->getArgument(1));

        $this->assertEquals('contentful.delivery.foo_client', $container->getAlias('contentful.delivery'));
    }

    public function testLoadDeliveryMultiple()
    {
        $container = $this->getContainer();
        $extension = new ContentfulExtension;

        $container->registerExtension($extension);

        $extension->load([
            [],
            [
                'delivery' => [
                    'default_client' => 'foo',
                    'clients' => [
                        'foo' => ['space' => 'abc', 'token' => '123'],
                        'bar' => ['space' => 'def', 'token' => '456', 'preview' => true]
                    ]
                ]
            ],
            []
        ], $container);

        $definition = $container->getDefinition('contentful.delivery.foo_client');

        $this->assertEquals('123', $definition->getArgument(0));
        $this->assertEquals('abc', $definition->getArgument(1));

        $definition = $container->getDefinition('contentful.delivery.bar_client');

        $this->assertEquals('456', $definition->getArgument(0));
        $this->assertEquals('def', $definition->getArgument(1));

        $this->assertEquals([
            'foo' => [
                'service' => 'contentful.delivery.foo_client',
                'api' => 'DELIVERY',
                'space' => 'abc'
            ],
            'bar' => [
                'service' => 'contentful.delivery.bar_client',
                'api' => 'PREVIEW',
                'space' => 'def'
            ]
        ], $container->getParameter('contentful.clients'));
    }
}
