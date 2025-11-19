<?php

declare(strict_types=1);

namespace App\Form\Project;

use App\Dto\Request\Project\EditProjectFormData;
use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<Project>
 */
class EditProjectType extends AbstractType
{
    public function getParent(): string
    {
        return CreateProjectType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditProjectFormData::class,
            'is_edit' => false,
        ]);
    }
}
