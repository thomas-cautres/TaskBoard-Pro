<?php

declare(strict_types=1);

namespace App\AppEnum;

enum ProjectType: string
{
    case Scrum = 'scrum';
    case Kanban = 'kanban';
    case Basic = 'basic';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
