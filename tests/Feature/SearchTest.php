<?php

namespace Tests\Feature;

use App\Repository\ModelRepository;
use Tests\Mock\ModelRepositoryMock;
use Tests\TestCase;

class SearchTest extends TestCase
{
    private $modelRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->modelRepository = new ModelRepositoryMock();
        $this->modelRepository->withSearch('one', [
            [
                'id' => 1,
                'name' => 'one',
                'inputs' => [],
                'components' => [
                    "type" => 'stack',
                    "items" => []
                ]
            ]
        ]);

        $this->instance(ModelRepository::class, $this->modelRepository);
    }

    public function testResponseMissingQuery()
    {
        $response = $this->get('/api/search');

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Invalid request',
                'errors' => [
                    'query' => [ 'The query field is required.']
                ]
            ]);
    }

    public function testResponseEmptyQuery()
    {
        $response = $this->get('/api/search?query=');

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Invalid request',
                'errors' => [
                    'query' => [ 'The query field is required.' ]
                ]
            ]);
    }

    public function testResponseEmpty()
    {
        $response = $this->get('/api/search?query=test');

        $response->assertStatus(200)
            ->assertJson([
                'items' => [],
                'page' => 1,
                'limit' => 12,
                'total' => 0
            ]);
    }

    public function testResponse()
    {
        $response = $this->get('/api/search?query=one');

        $response->assertStatus(200)
            ->assertJson([
                'items' => [
                    [
                        'id' => 1,
                        'name' => 'one'
                    ]
                ],
                'page' => 1,
                'limit' => 12,
                'total' => 1
            ]);
    }

    public function testResponseSetLimit()
    {
        $response = $this->get('/api/search?query=none&limit=8');

        $response->assertStatus(200)
            ->assertJson([
                'items' => [],
                'page' => 1,
                'limit' => 8,
                'total' => 0
            ]);
    }

    public function testResponseSetPage()
    {
        $response = $this->get('/api/search?query=none&limit=8&page=2');

        $response->assertStatus(200)
            ->assertJson([
                'items' => [],
                'page' => 2,
                'limit' => 8,
                'total' => 0
            ]);
    }
}
