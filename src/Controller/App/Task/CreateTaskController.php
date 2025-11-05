<?php

declare(strict_types=1);

namespace App\Controller\App\Task;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/task/create', name: 'app_task_create', methods: ['GET', 'POST'])]
class CreateTaskController extends AbstractController
{
    public function __invoke(): Response
    {
    }
}
