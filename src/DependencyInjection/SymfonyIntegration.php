<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2024 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\ContentfulBundle\DependencyInjection;

use Contentful\Core\Api\IntegrationInterface;

class SymfonyIntegration implements IntegrationInterface
{
    public function getIntegrationPackageName(): string
    {
        return 'contentful/contentful-bundle';
    }

    public function getIntegrationName(): string
    {
        return 'contentful.symfony';
    }
}
