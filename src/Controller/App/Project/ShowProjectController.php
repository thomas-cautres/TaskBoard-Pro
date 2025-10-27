<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Dto\Project\ProjectViewDto;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/app/project/{uuid}', name: 'app_project_show', methods: ['GET'])]
#[IsGranted('view', 'project')]
class ShowProjectController extends AbstractController
{
    public function __invoke(Project $project): Response
    {
        return $this->render('app/project/show_project.html.twig', [
            'project' => ProjectViewDto::fromEntity($project),
        ]);
    }
}
