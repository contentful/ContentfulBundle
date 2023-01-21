<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2023 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\ContentfulBundle\Unit;

use Contentful\ContentfulBundle\ContentfulBundle;
use Contentful\ContentfulBundle\DependencyInjection\Compiler\ProfilerControllerPass;
use Contentful\Tests\ContentfulBundle\TestCase;

class ContentfulBundleTest extends TestCase
{
    public function testCompilerPassIsAdded()
    {
        $bundle = new ContentfulBundle();
        $container = $this->getContainer();

        $bundle->build($container);

        foreach ($container->getCompilerPassConfig()->getPasses() as $pass) {
            if ($pass instanceof ProfilerControllerPass) {
                $this->markTestAsPassed('Profiler compiler pass was successfully added');

                return;
            }
        }

        $this->fail('Profiler compiler pass was not successfully added');
    }
}
