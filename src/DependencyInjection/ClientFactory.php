<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2024 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\ContentfulBundle\DependencyInjection;

use Contentful\Delivery\Client;
use Contentful\Delivery\ClientOptions;

class ClientFactory
{
    public static function create(array $config): Client
    {
        $client = new Client(
            $config['token'],
            $config['space'],
            $config['environment'],
            self::createClientOptions($config)
        );
        $client->useIntegration(new SymfonyIntegration());

        return $client;
    }

    private static function createClientOptions(array $config): ClientOptions
    {
        $options = new ClientOptions();

        if ('preview' === $config['api']) {
            $options->usingPreviewApi();
        }

        if (isset($config['options']['host'])) {
            $options->withHost($config['options']['host']);
        }

        if (isset($config['options']['locale'])) {
            $options->withDefaultLocale($config['options']['locale']);
        }

        if (isset($config['options']['logger'])) {
            $options->withLogger($config['options']['logger']);
        }

        if (isset($config['options']['client'])) {
            $options->withHttpClient($config['options']['client']);
        }

        if (isset($config['options']['cache']['pool'])) {
            $options->withCache(
                $config['options']['cache']['pool'],
                $config['options']['cache']['runtime'],
                $config['options']['cache']['content']
            );
        }

        if (isset($config['options']['query_cache']['pool'])) {
            $options->withQueryCache(
                $config['options']['query_cache']['pool'],
                $config['options']['query_cache']['lifetime']
            );
        }

        return $options;
    }
}
