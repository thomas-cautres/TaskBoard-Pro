<?php

declare(strict_types=1);

namespace App\Dto\Project;

class ProjectFiltersDto
{
    public const int ACTIVE_FILTER_ARCHIVED = 1;
    public const int ACTIVE_FILTER_ALL_STATUS = 2;

    public const int SORT_NAME_ASC = 1;
    public const int SORT_NAME_DESC = 2;

    private ?string $name = null;
    private ?string $type = null;
    private ?int $active = null;
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
