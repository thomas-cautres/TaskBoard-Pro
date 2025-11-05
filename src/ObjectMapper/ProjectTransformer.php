<?php

declare(strict_types=1);

namespace App\ObjectMapper;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

/**
 * @implements TransformCallableInterface<object, object>
 */
final readonly class ProjectTransformer implements TransformCallableInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
    ) {
    }

    public function __invoke(mixed $value, object $source, ?object $target): mixed
    {
        if ($value instanceof Project) {
            return $this->toUuid($value);
        }

        if (is_string($value)) {
            return $this->toEntity($value);
        }

        if (null === $value) {
            return null;
        }

        throw new \InvalidArgumentException(sprintf('Expected User or string, got %s', get_debug_type($value)));
    }

    private function toUuid(mixed $value): string
    {
        if (!$value instanceof Project) {
            throw new \InvalidArgumentException('Expected Project instance');
        }

        return $value->getUuid()->toRfc4122();
    }

    private function toEntity(mixed $value): Project
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('Expected string (email)');
        }

        $project = $this->projectRepository->findOneBy(['uuid' => $value]);

        if (!$project) {
            throw new \RuntimeException(sprintf('Project with uuid "%s" not found', $value));
        }

        return $project;
    }
}
