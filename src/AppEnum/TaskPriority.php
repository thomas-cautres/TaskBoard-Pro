<?php

declare(strict_types=1);

namespace App\AppEnum;

enum TaskPriority: int
{
    case Low = 0;
    case Medium = 1;
    case High = 2;
}
