<?php
/**
 * @copyright 2016 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class ContentfulExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration($container->getParameter('kernel.debug'));
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        if (!empty($config['delivery'])) {
            $this->loadDelivery($config['delivery'], $container);
        }
    }

    protected function loadDelivery(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('delivery.xml');

        if (empty($config['default_client'])) {
            $keys = array_keys($config['clients']);
            $config['default_client'] = reset($keys);
        }

        $container->setParameter('contentful.delivery.default_client', $config['default_client']);
        $container->setAlias('contentful.delivery', sprintf('contentful.delivery.%s_client', $config['default_client']));

        $clients = [];
        foreach ($config['clients'] as $name => $client) {
            $clients[$name] = [
                'service' => sprintf('contentful.delivery.%s_client', $name),
                'api' => $client['preview'] ? 'PREVIEW' : 'DELIVERY',
                'space' => $client['space']
            ];
            $this->loadDeliveryClient($name, $client, $container);
        }

        $container->setParameter('contentful.clients', $clients);
    }

    protected function loadDeliveryClient($name, array $client, ContainerBuilder $container)
    {
        $logger = $client['request_logging'] ? 'contentful.logger.array' : 'contentful.logger.null';
        $options = ['logger' => new Reference($logger)];

        $container
            ->setDefinition(sprintf('contentful.delivery.%s_client', $name), new DefinitionDecorator('contentful.delivery.client'))
            ->setArguments([
                $client['token'],
                $client['space'],
                $client['preview'],
                null,
                $options
            ])
        ;
    }
}
