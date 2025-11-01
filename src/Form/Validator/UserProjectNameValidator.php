<?php

declare(strict_types=1);

namespace App\Form\Validator;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

readonly class UserProjectNameValidator
{
    public function __construct(private Security $security, private ProjectRepository $projectRepository)
    {
    }

    /**
     * @param string $value
     */
    public function validate(mixed $value, ExecutionContextInterface $context): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return;
        }

        /** @var FormInterface<string> $nameForm */
        $nameForm = $context->getObject();
        /** @var FormInterface<Project> $projectForm */
        $projectForm = $nameForm->getParent();
        /** @var ?Project $project */
        $project = $projectForm->getData();

        if (0 !== $this->projectRepository->countByUserAndName($user, $value, $projectForm->getConfig()->getOption('is_edit') ? $project : null)) {
            $context->buildViolation('validator.project.name')
                ->atPath('name')
                ->addViolation();
        }
    }
}
