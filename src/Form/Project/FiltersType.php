<?php

declare(strict_types=1);

namespace App\Form\Project;

use App\AppEnum\ProjectType;
use App\Dto\Request\Project\ProjectFiltersRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<ProjectFiltersRequest>
 */
class FiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search by name or description...',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'All Types',
                'choices' => [
                    strtoupper(ProjectType::Scrum->value) => ProjectType::Scrum->value,
                    strtoupper(ProjectType::Kanban->value) => ProjectType::Kanban->value,
                    strtoupper(ProjectType::Basic->value) => ProjectType::Basic->value,
                ],
            ])
            ->add('active', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Active Only',
                'choices' => [
                    'Archived' => ProjectFiltersRequest::ACTIVE_FILTER_ARCHIVED,
                    'All status' => ProjectFiltersRequest::ACTIVE_FILTER_ALL_STATUS,
                ],
            ])
            ->add('sort', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Creation Date',
                'choices' => [
                    'Name (A-Z)' => ProjectFiltersRequest::SORT_NAME_ASC,
                    'Name (Z-A)' => ProjectFiltersRequest::SORT_NAME_DESC,
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filter',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectFiltersRequest::class,
            'csrf_protection' => false,
        ]);
    }
}
