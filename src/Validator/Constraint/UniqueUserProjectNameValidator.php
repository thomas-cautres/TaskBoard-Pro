<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use App\Dto\Request\Project\CreateProjectInterface;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserProjectNameValidator extends ConstraintValidator
{
    public function __construct(
        private readonly Security $security,
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    /**
     * @param ?string $value
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueUserProjectName) {
            throw new \InvalidArgumentException('Invalid constraint');
        }

        if (null === $value || '' === $value) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return;
        }

        $root = $this->context->getRoot();

        /** @var CreateProjectInterface|null $existingProject */
        $existingProject = match (true) {
            $root instanceof CreateProjectInterface => $root,
            $root instanceof FormInterface => $root->getData(),
            default => throw new \LogicException(sprintf('Root should be of type %s or %s', CreateProjectInterface::class, FormInterface::class)),
        };

        if ($this->projectRepository->countByUserAndName($user, $value, $existingProject) > 0) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
