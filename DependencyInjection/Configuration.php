<?php
/**
 * @copyright 2016 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpKernel\Kernel;

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
                    ->ifTrue(function ($v) { return is_array($v) && !array_key_exists('clients', $v) && !array_key_exists('client', $v); })
                    ->then(function ($v) {
                        // Key that should not be rewritten to the client config
                        $excludedKeys = array('default_client' => true);
                        $connection = array();
                        foreach ($v as $key => $value) {
                            if (isset($excludedKeys[$key])) {
                                continue;
                            }
                            $connection[$key] = $v[$key];
                            unset($v[$key]);
                        }
                        $v['default_client'] = isset($v['default_client']) ? (string) $v['default_client'] : 'default';
                        $v['clients'] = array($v['default_client'] => $connection);
                        return $v;
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

        /** @var $connectionNode ArrayNodeDefinition */
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
                ->booleanNode('preview')
                    ->defaultFalse()
                ->end()
                ->booleanNode('request_logging')
                    ->defaultValue($this->debug)
                ->end()
                ->scalarNode('default_locale')
                    ->defaultNull()
                ->end()
                ->scalarNode('uri_override')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('http_client')
                  ->info('Override the default HTTP client with a custom Guzzle instance. Service ID as string.')
                  ->cannotBeEmpty()
                ->end()
                ->scalarNode('cache')
                  ->info(Kernel::VERSION_ID >= 31000 ? 'The cache to use. Can either be true, false or the service ID of a PSR-6 compatible cache to use.' : 'The cache to use. Can either be false or the service ID of a PSR-6 compatible cache to use.')
                  ->defaultValue(Kernel::VERSION_ID >= 31000 ? !$this->debug : false)
                  ->validate()
                      ->ifTrue(function ($v) { return true === $v && Kernel::VERSION_ID < 31000; })
                      ->thenInvalid(sprintf('Cache can only be true on Symfony 3.1 or higher. You are using version %s.', Kernel::VERSION))
                  ->end()
                ->end()
                ->booleanNode('auto_warmup')
                    ->defaultValue(true)
                ->end()
            ->end()
        ;

        return $node;
    }
}
