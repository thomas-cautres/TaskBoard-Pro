<?php

declare(strict_types=1);

namespace App\Controller\App\Task;

use App\Dto\Project\ProjectColumnDto;
use App\Dto\Task\TaskDto;
use App\Event\Task\TaskCreatedEvent;
use App\Form\Task\CreateTaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/app/project/{uuid}/task/create', name: 'app_task_create', requirements: ['uuid' => Requirement::UID_RFC4122], methods: ['GET', 'POST'])]
class CreateTaskController extends AbstractController
{
    public function __invoke(Request $request, ProjectColumnDto $projectColumn, EventDispatcherInterface $dispatcher): Response
    {
        $task = new TaskDto();
        $form = $this->createForm(CreateTaskType::class, $task, [
            'action' => $this->generateUrl('app_task_create', ['uuid' => $projectColumn->getUuid()]),
            'attr' => ['data-controller' => 'task', 'data-action' => 'task#submitFormModalCreate']
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dispatcher->dispatch(new TaskCreatedEvent($task, $projectColumn->getUuid()->toRfc4122()));

            $this->addFlash('success', 'task.create.flash.message.success');

            return $this->json([
                'success' => true,
                'redirect' => $this->redirectToRoute('app_project_show', ['uuid' => $projectColumn->getProjectUuid()])
            ]);
        }

        return $this->render('app/task/_modal_create_task.html.twig', [
            'form' => $form,
            'column_name' => $projectColumn->getName(),
        ]);
    }
}
