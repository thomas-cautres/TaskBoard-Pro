<?php

declare(strict_types=1);

namespace App\AppEnum;

enum ProjectType: string
{
    case Scrum = 'scrum';
    case Kanban = 'kanban';
    case Basic = 'basic';
}
