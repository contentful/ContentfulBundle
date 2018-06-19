<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\Controller;

use Contentful\ContentfulBundle\DataCollector\ContentfulDataCollector;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Twig\Environment;

class ProfilerController
{
    /**
     * @var Profiler
     */
    private $profiler;

    /**
     * @var Environment
     */
    private $templating;

    /**
     * ProfilerController constructor.
     *
     * @param Profiler    $profiler
     * @param Environment $templating
     */
    public function __construct(Profiler $profiler, Environment $twig)
    {
        $this->profiler = $profiler;
        $this->twig = $twig;
    }

    /**
     * @param string $token
     * @param int    $requestIndex
     *
     * @return Response
     */
    public function detailsAction(string $token, int $requestIndex): Response
    {
        $this->profiler->disable();

        $profile = $this->profiler->loadProfile($token);
        /** @var ContentfulDataCollector $collector */
        $collector = $profile->getCollector('contentful');
        $messages = $collector->getMessages();

        $message = $messages[$requestIndex];

        $body = $this->twig->render('@Contentful/Collector/details.html.twig', [
            'requestIndex' => $requestIndex,
            'message' => $message,
        ]);

        return new Response($body);
    }
}
