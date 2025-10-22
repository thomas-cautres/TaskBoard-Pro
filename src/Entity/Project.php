<?php

declare(strict_types=1);

namespace App\Entity;

use App\AppEnum\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $uuid;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::STRING, enumType: ProjectType::class)]
    private ProjectType $type;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private User $createdBy;

    #[ORM\OneToMany(targetEntity: ProjectColumn::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $columns;

    public function __construct()
    {
        $this->uuid = Uuid::v7();
        $this->createdAt = new \DateTimeImmutable('now');
        $this->columns = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Project
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Project
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Project
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ProjectType
    {
        return $this->type;
    }

    public function setType(ProjectType $type): Project
    {
        $this->type = $type;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): Project
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): Project
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): Project
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): Project
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): Project
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getColumns(): Collection
    {
        return $this->columns;
    }

    public function addColumn(ProjectColumn $projectColumn): static
    {
        if (!$this->columns->contains($projectColumn)) {
            $this->columns->add($projectColumn);
            $projectColumn->setProject($this);
        }

        return $this;
    }

    public function removeColumn(ProjectColumn $projectColumn): static
    {
        $this->columns->removeElement($projectColumn);

        return $this;
    }
}
