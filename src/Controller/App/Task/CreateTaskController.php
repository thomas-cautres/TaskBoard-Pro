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

#[Route('/app/project/{uuid}/task/create', name: 'app_task_create', methods: ['GET', 'POST'])]
class CreateTaskController extends AbstractController
{
    public function __invoke(Request $request, ProjectColumnDto $projectColumn, EventDispatcherInterface $dispatcher): Response
    {
        $task = new TaskDto();
        $form = $this->createForm(CreateTaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dispatcher->dispatch(new TaskCreatedEvent($task));

            $this->addFlash('success', 'task.create.flash.message.success');

            return $this->redirectToRoute('app_project_show', ['uuid' => $projectColumn->getProjectUuid()]);
        }

        return $this->render('app/task/_modal_create_task.html.twig', [
            'form' => $form,
        ]);
    }
}
