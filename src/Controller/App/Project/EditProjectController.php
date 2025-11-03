<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Dto\Project\ProjectDto;
use App\Event\Project\ProjectEditedEvent;
use App\Form\Project\EditProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/app/project/{uuid}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
#[IsGranted('edit', 'project')]
class EditProjectController extends AbstractController
{
    public function __invoke(Request $request, ProjectDto $project, EventDispatcherInterface $dispatcher): Response
    {
        $form = $this->createForm(EditProjectType::class, $project, [
            'is_edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dispatcher->dispatch(new ProjectEditedEvent($project));

            $this->addFlash('success', 'project.edit.flash.message.success');

            return $this->redirectToRoute('app_project_show', ['uuid' => $project->getUuid()]);
        }

        return $this->render('app/project/edit_project.html.twig', [
            'form' => $form,
            'project' => $project,
        ]);
    }
}
