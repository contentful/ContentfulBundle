<?php
/**
 * @copyright 2016 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProfilerController implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function detailsAction($token, $requestIndex)
    {
        /** @var $profiler \Symfony\Component\HttpKernel\Profiler\Profiler */
        $profiler = $this->container->get('profiler');
        $profiler->disable();

        $profile = $profiler->loadProfile($token);
        $logs = $profile->getCollector('contentful')->getLogs();

        $logEntry = $logs[$requestIndex];

        return $this->container->get('templating')->renderResponse('@Contentful/Collector/details.html.twig', [
            'requestIndex' => $requestIndex,
            'entry' => $logEntry
        ]);
    }
}
