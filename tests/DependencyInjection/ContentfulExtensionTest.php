<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\ContentfulBundle\DependencyInjection;

use Contentful\ContentfulBundle\DependencyInjection\ContentfulExtension;
use Contentful\Tests\ContentfulBundle\ContainerTrait;

class ContentfulExtensionTest extends \PHPUnit_Framework_TestCase
{
    use ContainerTrait;

    public function testLoadEmpty()
    {
        $container = $this->getContainer();
        $extension = new ContentfulExtension();

        $container->registerExtension($extension);

        $extension->load([[], [], []], $container);

        // The parameter 'contentful.clients' has to be defined for the InfoCommand to work.
        $this->assertSame([], $container->getParameter('contentful.clients'));
    }

    public function testLoadDeliveryDefault()
    {
        $container = $this->getContainer();
        $extension = new ContentfulExtension();

        $container->registerExtension($extension);

        $extension->load([
            [],
            [
                'delivery' => [
                    'space' => 'abc',
                    'token' => '123',
                ],
            ],
            [],
        ], $container);

        $definition = $container->getDefinition('contentful.delivery.default_client');

        $this->assertSame('123', $definition->getArgument(0));
        $this->assertSame('abc', $definition->getArgument(1));
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
                    'clients' => ['default' => ['space' => 'abc', 'token' => '123']],
                ],
            ],
            [],
        ], $container);

        $definition = $container->getDefinition('contentful.delivery.default_client');

        $this->assertSame('123', $definition->getArgument(0));
        $this->assertSame('abc', $definition->getArgument(1));

        $this->assertSame([
            'default' => [
                'service' => 'contentful.delivery.default_client',
                'api' => 'DELIVERY',
                'space' => 'abc',
            ],
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
                    'clients' => ['foo' => ['space' => 'abc', 'token' => '123']],
                ],
            ],
            [],
        ], $container);

        $this->assertSame('contentful.delivery.foo_client', (string) $container->getAlias('contentful.delivery'));
    }

    public function testLoadDeliveryAlternateDefault()
    {
        $container = $this->getContainer();
        $extension = new ContentfulExtension();

        $container->registerExtension($extension);

        $extension->load([
            [],
            [
                'delivery' => [
                    'default_client' => 'foo',
                    'clients' => ['foo' => ['space' => 'abc', 'token' => '123']],
                ],
            ],
            [],
        ], $container);

        $this->assertSame('foo', $container->getParameter('contentful.delivery.default_client'));

        $definition = $container->getDefinition('contentful.delivery.foo_client');

        $this->assertSame('123', $definition->getArgument(0));
        $this->assertSame('abc', $definition->getArgument(1));

        $this->assertSame('contentful.delivery.foo_client', (string) $container->getAlias('contentful.delivery'));
    }

    public function testLoadDeliveryMultiple()
    {
        $container = $this->getContainer();
        $extension = new ContentfulExtension();

        $container->registerExtension($extension);

        $extension->load([
            [],
            [
                'delivery' => [
                    'default_client' => 'foo',
                    'clients' => [
                        'foo' => ['space' => 'abc', 'token' => '123'],
                        'bar' => ['space' => 'def', 'token' => '456', 'preview' => true],
                    ],
                ],
            ],
            [],
        ], $container);

        $definition = $container->getDefinition('contentful.delivery.foo_client');

        $this->assertSame('123', $definition->getArgument(0));
        $this->assertSame('abc', $definition->getArgument(1));

        $definition = $container->getDefinition('contentful.delivery.bar_client');

        $this->assertSame('456', $definition->getArgument(0));
        $this->assertSame('def', $definition->getArgument(1));

        $this->assertSame([
            'foo' => [
                'service' => 'contentful.delivery.foo_client',
                'api' => 'DELIVERY',
                'space' => 'abc',
            ],
            'bar' => [
                'service' => 'contentful.delivery.bar_client',
                'api' => 'PREVIEW',
                'space' => 'def',
            ],
        ], $container->getParameter('contentful.clients'));
    }
}
