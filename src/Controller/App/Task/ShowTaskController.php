<?php

declare(strict_types=1);

namespace App\Controller\App\Task;

use App\Dto\View\Task\TaskModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/app/task/{uuid}', name: 'app_task_show', requirements: ['uuid' => Requirement::UID_RFC4122], methods: 'GET')]
#[IsGranted('view', 'task')]
class ShowTaskController extends AbstractController
{
    public function __invoke(TaskModel $task): Response
    {
        return $this->render('app/task/_modal_show_task.html.twig', [
            'task' => $task,
        ]);
    }
}
