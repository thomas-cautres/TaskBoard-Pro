<?php

namespace App\Form\Validator;

use App\Entity\User;
use App\Repository\ProjectRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

readonly class UserProjectNameValidator
{
    public function __construct(private Security $security, private ProjectRepository $projectRepository)
    {
    }

    public function validate(mixed $value, ExecutionContextInterface $context, mixed $payload): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return;
        }

        if (0 !== $this->projectRepository->countByUserAndName($user, $value)) {
            $context->buildViolation('You have already created a project with that name')
                ->atPath('name')
                ->addViolation();
        }
    }
}
