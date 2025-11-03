<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Dto\Project\ProjectViewDto;
use App\Entity\Project;
use App\Event\Project\ProjectArchivedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/app/project/{uuid}/archive', name: 'app_project_archive', methods: ['POST'])]
#[IsGranted('archive', 'project')]
class ArchiveProjectController extends AbstractController
{
    public function __invoke(Project $project, EventDispatcherInterface $dispatcher): Response
    {
        $dispatcher->dispatch(new ProjectArchivedEvent($project));

        return $this->render('app/project/show_project.html.twig', [
            'project' => ProjectViewDto::fromEntity($project),
        ]);
    }
}
