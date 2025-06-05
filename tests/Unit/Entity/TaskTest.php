<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Enum\TaskStatus;
use PHPUnit\Framework\TestCase;

final class TaskTest extends TestCase
{
    private Task $task;

    protected function setUp(): void
    {
        $this->task = new Task();
    }

    public function testTaskGettersAndSettersWorks(): void
    {
        $this->assertNull($this->task->getId());

        $this->task->setTitle('Test Task');
        $this->assertSame('Test Task', $this->task->getTitle());

        $this->assertNull($this->task->getDescription());
        $this->task->setDescription('Detailed description');
        $this->assertSame('Detailed description', $this->task->getDescription());

        $this->assertSame(TaskStatus::PENDING, $this->task->getStatus());
        $this->task->setStatus(TaskStatus::COMPLETED);
        $this->assertSame(TaskStatus::COMPLETED, $this->task->getStatus());

        $this->assertNull($this->task->getDueDate());
        $dueDate = new \DateTimeImmutable('2025-06-01');
        $this->task->setDueDate($dueDate);
        $this->assertSame($dueDate, $this->task->getDueDate());

        $this->assertNull($this->task->getCreatedAt());
        $this->assertNull($this->task->getUpdatedAt());

        $this->task->onPrePersist();
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->task->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->task->getUpdatedAt());

        $createdAt = $this->task->getCreatedAt();

        sleep(1);
        $this->task->onPreUpdate();
        $this->assertNotEquals($createdAt, $this->task->getUpdatedAt());
    }
}
