<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Dto\View\Project\ProjectModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/app/project/{uuid}', name: 'app_project_show', requirements: ['uuid' => Requirement::UID_RFC4122], methods: ['GET'])]
#[IsGranted('view', 'project')]
class ShowProjectController extends AbstractController
{
    public function __invoke(ProjectModel $project): Response
    {
        return $this->render('app/project/show_project.html.twig', [
            'project' => $project,
        ]);
    }
}
