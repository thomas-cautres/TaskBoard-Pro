<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Dto\Project\ProjectDto;
use App\Event\Project\ProjectRestoredEvent;
use App\Form\Project\RestoreProjectType;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/app/project/{uuid}/restore', name: 'app_project_restore', requirements: ['uuid' => Requirement::UID_RFC4122], methods: ['GET', 'POST'])]
#[IsGranted('restore', 'project')]
class RestoreProjectController extends AbstractController
{
    public function __invoke(Request $request, ProjectDto $project, EventDispatcherInterface $dispatcher): Response
    {
        $form = $this->createForm(RestoreProjectType::class, options: [
            'action' => $this->generateUrl('app_project_restore', ['uuid' => $project->getUuid()]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $dispatcher->dispatch(new ProjectRestoredEvent($project));

            $this->addFlash('success', 'project.restore.success');

            return $this->redirectToRoute('app_projects_list');
        }

        return $this->render('app/project/_modal_restore_project.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }
}
