<?php

namespace Tournikoti\CrudBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tournikoti\CrudBundle\Asset\AssetManagerInterface;
use Twig\Environment;

class DashboardController
{
    public function __construct(
        private Environment           $twig,
        private AssetManagerInterface $assetManager
    )
    {
    }

    public
    function __invoke(): Response
    {
        $content = $this->twig->render('@TournikotiCrud/dashboard/index.html.twig', [
            'assetManager' => $this->assetManager,
        ]);

        return new Response($content, Response::HTTP_OK, [
            'Content-Type' => 'text/html'
        ]);
    }
}
