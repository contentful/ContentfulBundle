<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var bool
     */
    private $debug = false;

    public function __construct($debug)
    {
        $this->debug = (bool) $debug;
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('contentful');

        $this->addDeliverySection($rootNode);

        return $treeBuilder;
    }

    private function addDeliverySection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
            ->arrayNode('delivery')
                ->beforeNormalization()
                    ->ifTrue(function ($value) {
                        return \is_array($value)
                            && !\array_key_exists('clients', $value)
                            && !\array_key_exists('client', $value);
                    })
                    ->then(function ($value) {
                        // Key that should not be rewritten to the client config
                        $excludedKeys = ['default_client' => true];
                        $connection = [];

                        foreach (\array_keys($value) as $key) {
                            if (isset($excludedKeys[$key])) {
                                continue;
                            }

                            $connection[$key] = $value[$key];
                            unset($value[$key]);
                        }

                        $value['default_client'] = $value['default_client'] ?? 'default';
                        $value['clients'] = [
                            $value['default_client'] => $connection,
                        ];

                        return $value;
                    })
                ->end()
                ->children()
                    ->scalarNode('default_client')->end()
                ->end()
                ->fixXmlConfig('client')
                ->append($this->getDeliveryConnectionsNode())
            ->end()
        ;
    }

    private function getDeliveryConnectionsNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('clients');

        /** @var ArrayNodeDefinition $connectionNode */
        $connectionNode = $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
        ;

        $connectionNode
            ->children()
                ->scalarNode('space')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('token')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('environment')
                    ->defaultValue('master')
                    ->cannotBeEmpty()
                ->end()
                ->booleanNode('preview')
                    ->defaultFalse()
                ->end()
                ->scalarNode('default_locale')
                    ->defaultNull()
                ->end()
                ->scalarNode('base_uri')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('http_client')
                    ->info('Override the default HTTP client with a custom Guzzle instance. Service ID as string.')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('cache')
                    ->info('The cache to use. If set to true, the "cache.system" service will be used, otherwise specify the service ID of a custom PSR-6 compatible service')
                    ->defaultFalse()
                    ->validate()
                        ->ifTrue(function ($value) {
                            return true === $value;
                        })
                        ->then(function () {
                            return 'cache.app';
                        })
                    ->end()
                ->end()
                ->booleanNode('cache_auto_warmup')
                    ->info('If set to true, the system cache will be populated through natural use.')
                    ->defaultFalse()
                ->end()
            ->end()
        ;

        return $node;
    }
}
