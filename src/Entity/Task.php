<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    operations: [
        new Post(),
        new GetCollection(),
        new Get(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['Read']],
    denormalizationContext: ['groups' => ['Write']],
    paginationEnabled: false,
)]
#[ApiFilter(SearchFilter::class, properties: ['status' => 'exact'])]
#[ORM\HasLifecycleCallbacks]
class Task
{
    #[Groups(['Read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['Read', 'Write'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[ORM\Column(length: 255)]
    private string $title;

    #[Groups(['Read', 'Write'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Groups(['Read', 'Write'])]
    #[ORM\Column(enumType: TaskStatus::class, options: ['default' => TaskStatus::PENDING])]
    private TaskStatus $status = TaskStatus::PENDING;

    #[Groups(['Read', 'Write'])]
    #[ApiProperty(openapiContext: ['type' => 'string', 'format' => 'date-time', 'example' => '2025-06-04 07:18:22'])]
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $due_date = null;

    #[Groups(['Read'])]
    #[ApiProperty(openapiContext: ['type' => 'string', 'format' => 'date-time', 'example' => '2025-06-04 07:18:22'])]
    #[ORM\Column]
    private ?DateTimeImmutable $created_at = null;

    #[Groups(['Read'])]
    #[ApiProperty(openapiContext: ['type' => 'string', 'format' => 'date-time', 'example' => '2025-06-04 07:18:22'])]
    #[ORM\Column]
    private ?DateTimeImmutable $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDueDate(): ?DateTimeImmutable
    {
        return $this->due_date;
    }

    public function setDueDate(?DateTimeImmutable $due_date): static
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updated_at = new DateTimeImmutable();
    }
}
