<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ContentfulExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('commands.xml');

        $configuration = new Configuration($container->getParameter('kernel.debug'));
        $config = $this->processConfiguration($configuration, $configs);

        if (!empty($config['delivery'])) {
            $this->configureDelivery($config['delivery'], $container);
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function configureDelivery(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('delivery.xml');

        if (empty($config['default_client'])) {
            $keys = \array_keys($config['clients']);
            $config['default_client'] = \reset($keys);
        }

        $container->setParameter(
            'contentful.delivery.default_client',
            $config['default_client']
        );
        $container->setAlias(
            'contentful.delivery',
            \sprintf('contentful.delivery.%s_client', $config['default_client'])
        )->setPublic(true);

        $clients = [];
        foreach ($config['clients'] as $name => $client) {
            $clients[$name] = $this->configureDeliveryClient($container, $name, $client);
        }

        $container->setParameter('contentful.clients', $clients);
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $name
     * @param array            $client
     *
     * @return string[]
     */
    private function configureDeliveryClient(ContainerBuilder $container, string $name, array $client): array
    {
        $options = $this->transformClientOptions($client);

        $container
            ->setDefinition(
                \sprintf('contentful.delivery.%s_client', $name),
                new ChildDefinition('contentful.delivery.client')
            )
            ->setArguments([
                $client['token'],
                $client['space'],
                $client['environment'],
                $client['preview'],
                $client['default_locale'],
                $options,
            ])
            ->addTag('contentful.client')
            ->setPublic(true)
        ;
        if (isset($options['cache'])) {
            $this->configureDeliveryCacheWarmer($container, $name, $options['cache'], $options['autoWarmup']);
        }

        return [
            'service' => \sprintf('contentful.delivery.%s_client', $name),
            'api' => $client['preview'] ? 'PREVIEW' : 'DELIVERY',
            'space' => $client['space'],
            'environment' => $client['environment'],
            'cache' => (string) ($options['cache'] ?? ''),
        ];
    }

    /**
     * @param array $client
     *
     * @return array
     */
    private function transformClientOptions(array $client): array
    {
        $options = [];
        if (isset($client['base_uri'])) {
            $options['baseUri'] = $client['base_uri'];
        }

        if (isset($client['http_client'])) {
            $options['guzzle'] = new Reference($client['http_client']);
        }

        $cache = $client['cache'] ?? false;
        if ($cache) {
            if (true === $cache) {
                $options['cache'] = new Reference('cache.app');
            } else {
                if ('@' === \mb_substr($cache, 0, 1)) {
                    $cache = \mb_substr($cache, 1);
                }
                $options['cache'] = new Reference($cache);
            }

            $options['autoWarmup'] = $client['cache_auto_warmup'];
        }

        return $options;
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $name
     * @param Reference        $cache
     * @param bool             $autoWarmup
     */
    private function configureDeliveryCacheWarmer(
        ContainerBuilder $container,
        string $name,
        Reference $cache,
        bool $autoWarmup
    ) {
        $container
            ->setDefinition(
                \sprintf('contentful.delivery.%s_client.cache_warmer', $name),
                new ChildDefinition('contentful.delivery.cache_warmer')
            )
            ->setArguments([
                new Reference(\sprintf('contentful.delivery.%s_client', $name)),
                $cache,
                $autoWarmup,
            ])
            ->addTag('kernel.cache_warmer')
        ;

        $container
            ->setDefinition(
                \sprintf('contentful.delivery.%s_client.cache_clearer', $name),
                new ChildDefinition('contentful.delivery.cache_clearer')
            )
            ->setArguments([
                new Reference(\sprintf('contentful.delivery.%s_client', $name)),
                $cache,
            ])
            ->addTag('kernel.cache_clearer')
        ;
    }
}
