<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class ProfilerController
{
    /**
     * @var Profiler
     */
    private $profiler;

    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(Profiler $profiler, EngineInterface $templating)
    {
        $this->profiler = $profiler;
        $this->templating = $templating;
    }

    public function detailsAction($token, $requestIndex)
    {
        $this->profiler->disable();

        $profile = $this->profiler->loadProfile($token);
        $logs = $profile->getCollector('contentful')->getLogs();

        $logEntry = $logs[$requestIndex];

        return $this->templating->renderResponse('@Contentful/Collector/details.html.twig', [
            'requestIndex' => $requestIndex,
            'entry' => $logEntry,
        ]);
    }
}
