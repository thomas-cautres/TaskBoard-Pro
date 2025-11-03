<?php

declare(strict_types=1);

namespace App\AppEnum;

enum ProjectStatus: string
{
    case Active = 'active';
    case Archived = 'archived';
}
