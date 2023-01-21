<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2023 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\ContentfulBundle\DependencyInjection;

use Contentful\ContentfulBundle\Cache\Delivery\CacheClearer;
use Contentful\ContentfulBundle\Cache\Delivery\CacheWarmer;
use Contentful\ContentfulBundle\Command\Delivery\DebugCommand;
use Contentful\ContentfulBundle\Command\Delivery\InfoCommand;
use Contentful\ContentfulBundle\DataCollector\Delivery\ClientDataCollector;
use Contentful\Delivery\Client;
use Contentful\Delivery\Client\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ContentfulExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configs = $this->processConfiguration(
            new Configuration($container->getParameter('kernel.debug')),
            $configs
        );

        $this->registerCache($container)
            ->registerCommand($container)
            ->registerDataCollector($container)
            ->registerDeliveryClient($container, $configs['delivery'] ?? [])
        ;
    }

    /**
     * Registers two base cache handlers, one for warming up and one for clearing.
     * They are defined as abstract as a "concrete" implementation will be defined
     * for every configured client.
     */
    private function registerCache(ContainerBuilder $container): self
    {
        $container->register('contentful.delivery.cache_clearer', CacheClearer::class)
            ->setAbstract(true)
        ;

        $container->register('contentful.delivery.cache_warmer', CacheWarmer::class)
            ->setAbstract(true)
        ;

        return $this;
    }

    /**
     * Registers the CLI command which is in charge of extracting the configuration info,
     * and displaying it for debugging purposes.
     */
    private function registerCommand(ContainerBuilder $container): self
    {
        $container->register('contentful.delivery.command.info', InfoCommand::class)
            ->addArgument(new Parameter('contentful.delivery.clients.info'))
            ->addTag('console.command', [
                'command' => 'contentful:delivery:info',
            ])
        ;

        $container->register('contentful.delivery.command.debug', DebugCommand::class)
            ->addArgument(new TaggedIteratorArgument('contentful.delivery.client'))
            ->addArgument(new Parameter('contentful.delivery.clients.info'))
            ->addTag('console.command', [
                'command' => 'contentful:delivery:debug',
            ])
        ;

        return $this;
    }

    /**
     * Registers the data collector, which will display info about the configured clients
     * and the queries being made to the API.
     */
    private function registerDataCollector(ContainerBuilder $container): self
    {
        $container->register('contentful.delivery.data_collector', ClientDataCollector::class)
            ->addArgument(new TaggedIteratorArgument('contentful.delivery.client'))
            ->addArgument(new Parameter('contentful.delivery.clients.info'))
            ->addTag('data_collector', [
                'id' => 'contentful',
                'template' => '@Contentful/Collector/contentful.html.twig',
                'priority' => '250',
            ])
        ;

        return $this;
    }

    private function registerDeliveryClient(ContainerBuilder $container, array $configs)
    {
        if (!$configs) {
            $container->setParameter('contentful.delivery.clients.info', []);

            return;
        }

        // When only one client is configured,
        // it will automatically be the default one
        if (1 === \count($configs)) {
            $name = \array_keys($configs)[0];
            $configs[$name]['default'] = true;
        }

        $defaults = \array_reduce($configs, function (int $carry, array $config) {
            return $carry + (int) (true === $config['default']);
        }, 0);
        if (1 !== $defaults) {
            throw new LogicException(\sprintf('Contentful client configuration requires exactly one client defined with "default: true" key, %d found.', $defaults));
        }

        $clientsInfo = [];
        foreach ($configs as $name => $config) {
            $options = $this->configureDeliveryOptions($config);
            $serviceId = \sprintf('contentful.delivery.%s_client', $name);
            $container->register($serviceId, Client::class)
                ->addArgument($options)
                ->setFactory([ClientFactory::class, 'create'])
                ->addTag('contentful.delivery.client')
                ->setAutowired(true)
            ;

            $this->configureDeliveryCache($container, $name, $options['options']['cache']);

            if (true === $config['default']) {
                $container->setAlias(ClientInterface::class, $serviceId);
                $container->setAlias(Client::class, $serviceId);
                $container->setAlias('contentful.delivery.client', $serviceId)
                    ->setPublic(true)
                ;
            }

            $clientsInfo[$name] = [
                'service' => $serviceId,
                'api' => 'delivery' === $config['api'] ? Client::API_DELIVERY : Client::API_PREVIEW,
                'space' => $config['space'],
                'environment' => $config['environment'],
                'cache' => (string) ($config['options']['cache']['pool'] ?? ''),
            ];
        }

        $container->setParameter('contentful.delivery.clients.info', $clientsInfo);
    }

    /**
     * Converts the references in the configuration into actual Reference objects.
     */
    private function configureDeliveryOptions(array $options): array
    {
        $logger = $options['options']['logger'];
        if (null !== $logger) {
            $options['options']['logger'] = true === $logger
                ? new Reference(LoggerInterface::class)
                : new Reference($options['options']['logger']);
        }

        if (null !== $options['options']['client']) {
            $options['options']['client'] = new Reference($options['options']['client']);
        }

        $pool = $options['options']['cache']['pool'];
        if (null !== $pool) {
            $options['options']['cache']['pool'] = true === $pool
                ? new Reference(CacheItemPoolInterface::class)
                : new Reference($pool);
        }

        return $options;
    }

    private function configureDeliveryCache(ContainerBuilder $container, string $name, array $cache)
    {
        if (null === $cache['pool']) {
            return;
        }

        $client = new Reference(\sprintf('contentful.delivery.%s_client', $name));

        $clearerDefinition = (new ChildDefinition('contentful.delivery.cache_clearer'))
            ->setArguments([$client, $cache['pool']])
            ->addTag('kernel.cache_clearer')
        ;
        $warmerDefinition = (new ChildDefinition('contentful.delivery.cache_warmer'))
            ->setArguments([$client, $cache['pool'], $cache['runtime'], $cache['content']])
            ->addTag('kernel.cache_warmer')
        ;

        $container->addDefinitions([
            \sprintf('contentful.delivery.%s_client.cache_clearer', $name) => $clearerDefinition,
            \sprintf('contentful.delivery.%s_client.cache_warmer', $name) => $warmerDefinition,
        ]);
    }
}
