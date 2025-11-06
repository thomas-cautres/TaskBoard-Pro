<?php

declare(strict_types=1);

namespace App\Entity;

use App\AppEnum\ProjectStatus;
use App\AppEnum\ProjectType;
use App\Dto\Project\ProjectDto;
use App\ObjectMapper\CollectionTransformer;
use App\ObjectMapper\UserTransformer;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Index(name: 'name_idx', columns: ['name'])]
#[Map(target: ProjectDto::class, source: ProjectDto::class)]
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
    private ?User $createdBy = null;

    /**
     * @var Collection<int, ProjectColumn>
     */
    #[ORM\OneToMany(targetEntity: ProjectColumn::class, mappedBy: 'project', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Map(target: 'columns', source: 'columns', transform: CollectionTransformer::class)]
    private Collection $columns;

    #[ORM\Column(type: Types::STRING, enumType: ProjectStatus::class, options: ['default' => ProjectStatus::Active->value])]
    private ProjectStatus $status = ProjectStatus::Active;

    public function __construct()
    {
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

    /**
     * @return Collection<int, ProjectColumn>
     */
    public function getColumns(): Collection
    {
        return $this->columns;
    }

    /**
     * @return Collection<int, ProjectColumn>
     *
     * @throws \Exception
     */
    public function getColumnsSortedByPosition(): Collection
    {
        $columns = $this->columns->toArray();

        uasort($columns, fn (ProjectColumn $projectColumnA, ProjectColumn $projectColumnB) => $projectColumnA->getPosition() <=> $projectColumnB->getPosition());

        return new ArrayCollection($columns);
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

    public function getStatus(): ProjectStatus
    {
        return $this->status;
    }

    public function setStatus(ProjectStatus $status): Project
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusAsString(): string
    {
        return $this->status->value;
    }

    public function setStatusAsString(string $status): static
    {
        $this->status = ProjectStatus::from($status);

        return $this;
    }

    public function getColumnsCount(): int
    {
        return $this->columns->count();
    }
}
