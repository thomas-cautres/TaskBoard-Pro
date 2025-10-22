<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/{uuid}/project', name: 'app_project_show', methods: ['GET'])]
class ShowProjectController extends AbstractController
{
    public function __invoke(Project $project): Response
    {
        return $this->render('app/project/show_project.html.twig', [
            'project' => $project
        ]);
    }
}
