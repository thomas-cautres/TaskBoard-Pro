<?php

declare(strict_types=1);

namespace App\Form\Security;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @extends AbstractType<User>
 */
class ConfirmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('confirmationCode', TextType::class, [
            'label' => 'Confirmation code',
            'constraints' => new Callback([$this, 'validateConfirmationCode']),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'confirmation_code' => null,
        ]);
    }

    public static function validateConfirmationCode(mixed $value, ExecutionContextInterface $context): void
    {
        /** @var FormInterface<mixed> $root */
        $root = $context->getRoot();
        $confirmationCode = $root->getConfig()->getOption('confirmation_code');

        if ($value !== $confirmationCode) {
            $context->buildViolation('This code is not valid.')->addViolation();
        }
    }
}
