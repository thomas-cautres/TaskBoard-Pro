<?php

declare(strict_types=1);

namespace App\Form\Project;

use App\Entity\Project;
use App\Form\Type\ProjectTypeType;
use App\Form\Validator\UserProjectNameValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @extends AbstractType<Project>
 */
class CreateProjectType extends AbstractType
{
    public function __construct(
        private readonly UserProjectNameValidator $nameValidator,
    ) {
    }

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
                'constraints' => [
                    new NotBlank(),
                    new Length(min: 3, max: 100),
                    new Callback([$this->nameValidator, 'validate']),
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
                'constraints' => [
                    new NotBlank(message: 'validator.project.type.empty'),
                ],
            ])
            ->add('startDate', null, [
                'widget' => 'single_text',
                'label' => 'project.form.start_date.label',
                'label_attr' => [
                    'class' => 'fw-semibold',
                ],
            ])
            ->add('endDate', null, [
                'widget' => 'single_text',
                'label' => 'project.form.end_date.label',
                'label_attr' => [
                    'class' => 'fw-semibold',
                ],
                'constraints' => [
                    new Callback([
                        'callback' => function (?\DateTimeImmutable $value, ExecutionContextInterface $context) {
                            if (null === $value) {
                                return;
                            }
                            /** @var FormInterface $projectRoot */
                            $projectRoot = $context->getRoot();
                            /** @var Project $project */
                            $project = $projectRoot->getData();

                            if ($value < $project->getStartDate()) {
                                $context->addViolation('validator.project.end.date');
                            }
                        },
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
