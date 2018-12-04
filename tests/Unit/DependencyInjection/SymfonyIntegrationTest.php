<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\ContentfulBundle\Unit\DependencyInjection;

use Contentful\ContentfulBundle\DependencyInjection\SymfonyIntegration;
use Contentful\Tests\ContentfulBundle\TestCase;

class SymfonyIntegrationTest extends TestCase
{
    public function testGetData()
    {
        $integration = new SymfonyIntegration();

        $this->assertSame('contentful/contentful-bundle', $integration->getIntegrationPackageName());
        $this->assertSame('contentful.symfony', $integration->getIntegrationName());
    }
}
