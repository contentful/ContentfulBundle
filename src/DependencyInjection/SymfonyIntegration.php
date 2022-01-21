<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2022 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\ContentfulBundle\DependencyInjection;

use Contentful\Core\Api\IntegrationInterface;

class SymfonyIntegration implements IntegrationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getIntegrationPackageName(): string
    {
        return 'contentful/contentful-bundle';
    }

    /**
     * {@inheritdoc}
     */
    public function getIntegrationName(): string
    {
        return 'contentful.symfony';
    }
}
