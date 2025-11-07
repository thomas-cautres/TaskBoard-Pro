<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\Task;

interface TaskGeneratorInterface
{
    public function generate(Task $task): mixed;
}
