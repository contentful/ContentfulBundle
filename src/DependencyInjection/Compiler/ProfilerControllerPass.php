<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2024 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\ContentfulBundle\DependencyInjection\Compiler;

use Contentful\ContentfulBundle\Controller\Delivery\ProfilerController;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ProfilerControllerPass implements CompilerPassInterface
{
    /**
     * Loads the definition for the ProfilerController when the profiler and twig are present.
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('profiler') || !$container->hasDefinition('twig')) {
            return;
        }

        $container->register('contentful.profiler_controller', ProfilerController::class)
            ->setArguments([
                new Reference('profiler'),
                new Reference('twig'),
            ])
            ->setPublic(true)
            ->addTag('controller.service_arguments')
        ;
    }
}
