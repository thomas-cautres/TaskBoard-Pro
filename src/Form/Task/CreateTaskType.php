<?php

declare(strict_types=1);

namespace App\Form\Task;

use App\AppEnum\TaskPriority;
use App\Dto\Task\TaskDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'task.create.title_placeholder',
                ],
                'help' => 'task.create.title_help',
                'constraints' => [
                    new NotBlank(),
                    new Length(min: 2, max: 255)
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'task.create.description_placeholder',
                ],
                'help' => 'task.create.description_help',
            ])
            ->add('priority', EnumType::class, [
                'class' => TaskPriority::class,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('endDate', DateType::class, [
                'required' => false,
                'help' => 'task.create.due_date_help',
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaskDto::class,
        ]);
    }
}
