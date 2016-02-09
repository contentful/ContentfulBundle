<?php
/**
 * @copyright 2016 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class ContentfulExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

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

        foreach ($config['clients'] as $name => $connection) {
            $this->loadDeliveryClient($name, $connection, $container);
        }
    }

    protected function loadDeliveryClient($name, array $client, ContainerBuilder $container)
    {
        $def = $container
            ->setDefinition(sprintf('contentful.delivery.%s_client', $name), new DefinitionDecorator('contentful.delivery.client'))
            ->setArguments([
                $client['token'],
                $client['space'],
                $client['preview'],
            ])
        ;
    }
}
