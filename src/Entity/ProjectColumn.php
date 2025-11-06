<?php

declare(strict_types=1);

namespace App\Entity;

use App\Dto\Project\ProjectColumnDto;
use App\ObjectMapper\CollectionTransformer;
use App\ObjectMapper\ProjectTransformer;
use App\Repository\ProjectColumnRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProjectColumnRepository::class)]
#[Map(target: ProjectColumnDto::class, source: ProjectColumn::class)]
class ProjectColumn
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $uuid;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private int $position;

    #[ORM\ManyToOne(inversedBy: 'columns')]
    #[ORM\JoinColumn(nullable: false)]
    #[Map(target: 'projectUuid', source: 'project', transform: ProjectTransformer::class)]
    private Project $project;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'projectColumn', cascade: ['persist', 'remove'])]
    #[Map(target: 'tasks', source: 'tasks', transform: CollectionTransformer::class)]
    private Collection $tasks;

    public function __construct()
    {
        $this->uuid = Uuid::v7();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProjectColumn($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProjectColumn() === $this) {
                $task->setProjectColumn(null);
            }
        }

        return $this;
    }
}
