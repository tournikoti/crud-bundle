<?php

namespace Tournikoti\CrudBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class DashboardController
{
    public function __construct(
        private Environment $twig
    )
    {
    }

    public function __invoke(): Response
    {
        return new Response($this->twig->render('@TournikotiCrud/dashboard/index.html.twig', []), Response::HTTP_OK, [
            'Content-Type' => 'text/html'
        ]);
    }
}
