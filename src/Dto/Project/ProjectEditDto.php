<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\Entity\Project;
use Symfony\Component\Uid\Uuid;

final readonly class ProjectEditDto implements ProjectDtoInterface
{
    public function __construct(
        public Uuid $uuid,
        public string $name,
    ) {
    }

    /**
     * @throws \Exception
     */
    public static function fromEntity(Project $project): self
    {
        return new self(
            uuid: $project->getUuid(),
            name: $project->getName()
        );
    }
}
