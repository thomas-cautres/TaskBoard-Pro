<?php

declare(strict_types=1);

namespace App\Form\Project;

use App\Dto\Request\Project\CreateProjectFormData;
use App\Form\Type\ProjectTypeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<CreateProjectFormData>
 */
class CreateProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'project.form.name.label',
                'attr' => [
                    'placeholder' => 'project.form.name.placeholder',
                ],
                'label_attr' => [
                    'class' => 'fw-semibold',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'project.form.description.label',
                'attr' => [
                    'placeholder' => 'project.form.description.placeholder',
                    'rows' => 4,
                ],
                'label_attr' => [
                    'class' => 'fw-semibold',
                ],
            ])
            ->add('type', ProjectTypeType::class, [
                // TODO When project has tasks, true === $options['is_edit'] && $project->getTasks()->isEmpty(),
                'disabled' => true === $options['is_edit'],
            ])
            ->add('startDate', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'project.form.start_date.label',
                'label_attr' => [
                    'class' => 'fw-semibold',
                ],
            ])
            ->add('endDate', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'project.form.end_date.label',
                'label_attr' => [
                    'class' => 'fw-semibold',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateProjectFormData::class,
            'is_edit' => false,
        ]);
    }
}
