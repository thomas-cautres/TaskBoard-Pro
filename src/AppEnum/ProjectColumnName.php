<?php

declare(strict_types=1);

namespace App\AppEnum;

enum ProjectColumnName: string
{
    case BackLog = 'Backlog';
    case ToDo = 'To do';
    case InProgress = 'In progress';
    case Review = 'Review';
    case Done = 'Done';
    case Open = 'Open';
    case Closed = 'Closed';
}
