<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Entity\Project;
use App\Event\Project\ProjectCreatedEvent;
use App\Form\Project\CreateProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/project/create', name: 'app_project_create', methods: ['GET', 'POST'])]
class CreateProjectController extends AbstractController
{
    public function __invoke(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $project = new Project();
        $form = $this->createForm(CreateProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dispatcher->dispatch(new ProjectCreatedEvent($project));

            $this->addFlash('success', 'project.create.flash.message.success');

            return $this->redirectToRoute('app_project_show', ['uuid' => $project->getUuid()]);
        }

        return $this->render('app/project/create_project.html.twig', [
            'form' => $form,
        ]);
    }
}
