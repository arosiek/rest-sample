<?php

namespace App\Tests\Functional;

use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Task;
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

    public function testSingleTaskProvidedViaGetRequest(): void
    {
        //Arrange
        $taskProxy = TaskFactory::createOne();

        //Act
        $response = $this->client->request(
            HttpOperation::METHOD_GET,
            '/tasks/' . $taskProxy->getId(),
            [
                'headers' => ['accept' => 'application/json'],
            ]
        );

        //Assert
        $this->assertJsonContains(array_filter($this->mapTaskToResponseData($taskProxy)));
    }

    /**
     * @return array<string,mixed>
     */
    private function mapTaskToResponseData(Task $task): array
    {
        return [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus()?->value,
            'due_date' => $task->getDueDate()?->format('Y-m-d H:i:s'),
            'created_at' => $task->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at' => $task->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];
    }

    public function testGetSingleTaskReturns404ForNotExisting(): void
    {
        //Act
        $response = $this->client->request(
            HttpOperation::METHOD_GET,
            '/tasks/0',
            [
                'headers' => ['accept' => 'application/json'],
            ]
        );

        //Assert
        $this->assertResponseStatusCodeSame(404);
    }

    public function testTaskSuccessfullyUpdatedViaPatchRequest(): void
    {
        //Arrange
        $taskProxy = TaskFactory::createOne([
            'title' => 'Alte Aufgabe',
            'status' => TaskStatus::PENDING,
        ]);

        $patchedData = [
            'title' => 'Neue Aufgabe',
            'status' => TaskStatus::COMPLETED->value,
        ];

        //Act
        $response = $this->client->request(
            HttpOperation::METHOD_PATCH,
            '/tasks/' . $taskProxy->getId(),
            [
                'headers' => [
                    'accept' => 'application/json',
                    'Content-Type' => 'application/merge-patch+json'
                ],
                'json' => $patchedData,
            ]
        );

        //Assert
        $responseTime = new DateTimeImmutable($response->getHeaders()['date'][0]);
        $this->assertJsonContains(
            array_filter(array_merge(
                $this->mapTaskToResponseData($taskProxy),
                $patchedData,
                ['updated_at' => $responseTime->format('Y-m-d H:i:s'),]
            ))
        );
    }

    public function testPatchSingleTaskReturns404ForNotExisting(): void
    {
        //Act
        $response = $this->client->request(
            HttpOperation::METHOD_PATCH,
            '/tasks/0',
            [
                'headers' => [
                    'accept' => 'application/json',
                    'Content-Type' => 'application/merge-patch+json'
                ],
                'json' => [],
            ]
        );

        //Assert
        $this->assertResponseStatusCodeSame(404);
    }
}
