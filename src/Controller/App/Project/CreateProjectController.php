<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/project/create', name: 'app_project_create', methods: ['GET', 'POST'])]
class CreateProjectController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return $this->render('app/project/create_project.html.twig');
    }
}
