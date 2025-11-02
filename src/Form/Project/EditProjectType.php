<?php

declare(strict_types=1);

namespace App\Form\Project;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;

/**
 * @extends AbstractType<Project>
 */
class EditProjectType extends AbstractType
{
    public function getParent(): string
    {
        return CreateProjectType::class;
    }
}
