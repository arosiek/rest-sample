<?php

namespace App\Factory;

use App\Entity\Task;
use App\Enum\TaskStatus;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Task>
 */
final class TaskFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Task::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'status' => self::faker()->randomElement(TaskStatus::cases()),
            'title' => self::faker()->text(255),
            'description' => self::faker()->boolean(10) ? self::faker()->text() : null,
            'due_date' => self::faker()->boolean(5) ? self::faker()->dateTime() : null,
        ];
    }
}
