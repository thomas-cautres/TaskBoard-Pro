<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Dto\Project\ProjectDto;
use App\Event\Project\ProjectArchivedEvent;
use App\Form\Project\ArchiveProjectType;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/app/project/{uuid}/archive', name: 'app_project_archive', methods: ['GET', 'POST'])]
#[IsGranted('archive', 'project')]
class ArchiveProjectController extends AbstractController
{
    public function __invoke(Request $request, ProjectDto $project, EventDispatcherInterface $dispatcher): Response
    {
        $form = $this->createForm(ArchiveProjectType::class, options: [
            'action' => $this->generateUrl('app_project_archive', ['uuid' => $project->getUuid()]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $dispatcher->dispatch(new ProjectArchivedEvent($project));

            $this->addFlash('success', 'project.archive.success');

            return $this->redirectToRoute('app_projects_list');
        }

        return $this->render('app/project/_modal_archive_project.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }
}
