<?php

declare(strict_types=1);

namespace App\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app', name: 'app_dashboard', methods: ['GET'])]
class DashboardController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('app/dashboard.html.twig');
    }
}
