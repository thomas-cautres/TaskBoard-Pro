<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Dto\Project\ProjectDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/app/project/{uuid}', name: 'app_project_show', methods: ['GET'])]
#[IsGranted('view', 'project')]
class ShowProjectController extends AbstractController
{
    public function __invoke(ProjectDto $project, WorkflowInterface $projectStateMachine): Response
    {
        return $this->render('app/project/show_project.html.twig', [
            'project' => $project,
        ]);
    }
}
