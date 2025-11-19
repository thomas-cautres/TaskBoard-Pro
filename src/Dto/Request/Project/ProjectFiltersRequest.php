<?php

declare(strict_types=1);

namespace App\Dto\Request\Project;

use App\AppEnum\ProjectType;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectFiltersRequest
{
    public const int ACTIVE_FILTER_ARCHIVED = 1;
    public const int ACTIVE_FILTER_ALL_STATUS = 2;

    public const int SORT_NAME_ASC = 1;
    public const int SORT_NAME_DESC = 2;

    private ?string $name = null;

    #[Assert\Choice(callback: [ProjectType::class, 'values'])]
    private ?string $type = null;

    #[Assert\Choice(choices: [self::ACTIVE_FILTER_ARCHIVED, self::ACTIVE_FILTER_ALL_STATUS])]
    private ?int $active = null;

    #[Assert\Choice(choices: [self::SORT_NAME_ASC, self::SORT_NAME_DESC])]
    private ?int $sort = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function setActive(?int $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(?int $sort): static
    {
        $this->sort = $sort;

        return $this;
    }
}
