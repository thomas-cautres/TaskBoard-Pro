<?php

declare(strict_types=1);

namespace App\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/app', name: 'app_dashboard', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('app/dashboard.html.twig');
    }
}
