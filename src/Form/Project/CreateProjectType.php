<?php

declare(strict_types=1);

namespace App\Form\Project;

use App\Entity\Project;
use App\Form\Type\ProjectTypeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom du projet *',
                'attr' => [
                    'placeholder' => 'Ex: Refonte Site Web E-commerce',
                ],
                'label_attr' => [
                    'class' => 'fw-semibold',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décrivez les objectifs et le contexte du projet...',
                    'rows' => 4,
                ],
                'label_attr' => [
                    'class' => 'fw-semibold',
                ],
            ])
            ->add('type', ProjectTypeType::class)
            ->add('startDate', null, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'label_attr' => [
                    'class' => 'fw-semibold',
                ],
            ])
            ->add('endDate', null, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'label_attr' => [
                    'class' => 'fw-semibold',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
