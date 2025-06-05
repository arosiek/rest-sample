<?php

namespace App\Tests\Functional;

use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Enum\TaskStatus;
use App\Factory\TaskFactory;
use DateTimeImmutable;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class TaskTest extends ApiTestCase
{
    use ResetDatabase;
    use Factories;
    use DatabasePrimer;

    protected static ?bool $alwaysBootKernel = false;
    private Client $client;

    protected function setUp(): void
    {
        $kernel = static::bootKernel();
        $this->prime($kernel);

        $this->client = self::createClient();
    }

    public function testTaskSuccessfullyCreatedViaPostRequest(): void
    {
        //Arrange
        $taskData = [
            'title' => 'Neue Aufgabe',
            'description' => 'Beschreibung der Aufgabe',
            'status' => TaskStatus::PENDING->value,
            'due_date' => '2024-12-31 12:00:00',
        ];

        //Act
        $response = $this->client->request(
            HttpOperation::METHOD_POST,
            '/tasks',
            [
                'headers' => ['accept' => 'application/json'],
                'json' => $taskData,
            ]
        );

        //Assert
        $responseTime = new DateTimeImmutable($response->getHeaders()['date'][0]);
        $this->assertJsonContains(
            array_merge($taskData, [
                'created_at' => $responseTime->format('Y-m-d H:i:s'),
                'updated_at' => $responseTime->format('Y-m-d H:i:s'),
            ])
        );
    }

    public function testFullTaskCollectionProvidedViaGetRequest(): void
    {
        //Arrange
        TaskFactory::createMany(100);

        //Act
        $response = $this->client->request(
            HttpOperation::METHOD_GET,
            '/tasks',
            [
                'headers' => ['accept' => 'application/json'],
            ]
        );

        //Assert
        $this->assertCount(100, $response->toArray());
    }
}
