<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\AppEnum\ProjectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<ProjectType>
 */
class ProjectTypeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => ProjectType::class,
            'multiple' => false,
            'expanded' => true,
            'choice_attr' => function (ProjectType $choice, $key, $value) {
                return [
                    'data-icon' => match ($choice) {
                        ProjectType::Scrum => 'bi-calendar-week',
                        ProjectType::Kanban => 'bi-kanban',
                        ProjectType::Basic => 'bi-list-check',
                    },
                    'data-icon-color' => match ($choice) {
                        ProjectType::Scrum => 'text-success',
                        ProjectType::Kanban => 'text-warning',
                        ProjectType::Basic => 'text-secondary',
                    },
                    'data-emoji' => match ($choice) {
                        ProjectType::Scrum => '🏃',
                        ProjectType::Kanban => '📋',
                        ProjectType::Basic => '📝',
                    },
                    'data-description' => match ($choice) {
                        ProjectType::Scrum => 'project.form.type.scrum.description',
                        ProjectType::Kanban => 'project.form.type.kanban.description',
                        ProjectType::Basic => 'project.form.type.basic.description',
                    },
                ];
            },
        ]);
    }

    public function getParent(): string
    {
        return EnumType::class;
    }
}
