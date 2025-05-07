<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\ContentfulBundle\DependencyInjection;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @var NodeBuilder
     */
    private $builder;

    public function __construct($debug)
    {
        $this->debug = (bool) $debug;
        $this->builder = new NodeBuilder();
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('contentful', 'array', $this->builder);
        /**
         * @var ArrayNodeDefinition
         */
        $root = $treeBuilder->getRootNode();

        $root
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('delivery')
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->arrayPrototype()
            ->children()
            ->append($this->createDefaultNode())
            ->append($this->createTokenNode())
            ->append($this->createSpaceNode())
            ->append($this->createEnvironmentNode())
            ->append($this->createApiNode())
            ->append($this->createOptionsNode())
        ;

        return $treeBuilder;
    }

    private function createDefaultNode(): NodeDefinition
    {
        return $this->builder
            ->booleanNode('default')
            ->info('Which client to configure as default for autowiring')
            ->defaultFalse()
        ;
    }

    private function createTokenNode(): NodeDefinition
    {
        return $this->builder
            ->scalarNode('token')
            ->info('The Contentful access token for the given space')
            ->isRequired()
            ->cannotBeEmpty()
        ;
    }

    private function createSpaceNode(): NodeDefinition
    {
        return $this->builder
            ->scalarNode('space')
            ->info('The space ID')
            ->isRequired()
            ->cannotBeEmpty()
        ;
    }

    private function createEnvironmentNode(): NodeDefinition
    {
        return $this->builder
            ->scalarNode('environment')
            ->info('The environment ID')
            ->defaultValue('master')
            ->cannotBeEmpty()
        ;
    }

    private function createApiNode(): NodeDefinition
    {
        return (new NodeBuilder())
            ->enumNode('api')
            ->info('The name of the API to use, either "delivery" (default value) or "preview"')
            ->defaultValue('delivery')
            ->cannotBeEmpty()
            ->values(['delivery', 'preview'])
        ;
    }

    private function createOptionsNode(): NodeDefinition
    {
        return $this->builder
            ->arrayNode('options')
            ->addDefaultsIfNotSet()
            ->children()
            ->append($this->createLocaleNode())
            ->append($this->createHostNode())
            ->append($this->createLoggerNode())
            ->append($this->createClientNode())
            ->append($this->createCacheNode())
            ->append($this->createQueryCacheNode())
            ->end()
        ;
    }

    private function createLocaleNode(): NodeDefinition
    {
        return $this->builder
            ->scalarNode('locale')
            ->info('If set, it will be used as the locale on all API calls')
            ->defaultNull()
            ->cannotBeEmpty()
        ;
    }

    private function createHostNode(): NodeDefinition
    {
        return $this->builder
            ->scalarNode('host')
            ->info('A URL of a host to use instead of cdn.contentful.com (useful with proxies)')
            ->defaultNull()
            ->cannotBeEmpty()
            ->validate()
            ->ifTrue(function (string $url): bool {
                return false === \filter_var($url, \FILTER_VALIDATE_URL);
            })
            ->thenInvalid('Parameter "host" in client configuration must be a valid URL.')
            ->end()
        ;
    }

    private function createLoggerNode(): NodeDefinition
    {
        return $this->builder
            ->scalarNode('logger')
            ->info('A PSR-3 logger implementation, will default to the system logger')
            ->defaultValue(LoggerInterface::class)
        ;
    }

    private function createClientNode(): NodeDefinition
    {
        return $this->builder
            ->scalarNode('client')
            ->info('A Guzzle client instance')
            ->defaultNull()
            ->cannotBeEmpty()
        ;
    }

    private function createCacheNode(): NodeDefinition
    {
        // PHPStan does not know this is guaranteed to return a NodeBuilder on line 192.
        // Therefore, we need to ignore this.
        return $this->builder /* @phpstan-ignore-line */
            ->arrayNode('cache')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('pool')
            ->info('A PSR-6 cache item pool implementation, will default to the system cache')
            ->defaultValue(CacheItemPoolInterface::class)
            ->end()
            ->booleanNode('runtime')
            ->info('If true, content type and locale data will be cached during runtime and not on warmup')
            ->defaultFalse()
            ->end()
            ->booleanNode('content')
            ->info('If true, entry and asset data will be cached during runtime')
            ->defaultFalse()
            ->end()
            ->end()
        ;
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }

    private function createQueryCacheNode(): NodeDefinition
    {
        return $this->builder /* @phpstan-ignore-line */
            ->arrayNode('query_cache')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('pool')
            ->info(
                'A PSR-6 cache item pool implementation, will default to the system cache. This will be used to '.
                'cache items retrieved with queries when calling `$client->getEntries($query)`'
            )
            ->defaultNull()
            ->end()
            ->integerNode('lifetime')
            ->info(
                'The number of seconds of lifetime for `$client->getEntries($query)` cache items. '.
                'There\'s no invalidation mechanism on these cache items so consider to set a low lifetime '.
                '(for example 60 seconds).'
            )
            ->defaultValue(60)
            ->end()
            ->end()
        ;
    }
}
