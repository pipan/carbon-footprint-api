<?php

namespace Tests\Feature\Unit;

use App\Repository\UnitRepository;
use Tests\Mock\UnitRepositoryMock;
use Tests\TestCase;

class IndexTest extends TestCase
{
    private $unitRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->unitRepository = new UnitRepositoryMock();

        $this->app->instance(UnitRepository::class, $this->unitRepository);
    }

    public function testResponseEmpty()
    {
        $response = $this->get('/api/unit');

        $response->assertStatus(200)
            ->assertJson([]);
    }

    public function testResponse()
    {
        $this->unitRepository->add([
            'id' => 1,
            'name' => 'Length',
            'scales' => [
                [
                    'id' => 1,
                    'unit_id' => 1,
                    'label' => 'mm',
                    'multiplier' => 1,
                    'devider' => 1
                ]
            ]
        ]);

        $response = $this->get('/api/unit');

        $response->assertJson([
            [
                'id' => 1,
                'name' => 'Length',
                'scales' => [
                    [
                        'id' => 1,
                        'unit_id' => 1,
                        'label' => 'mm',
                        'multiplier' => 1,
                        'devider' => 1
                    ]
                ]
            ]
        ]);
    }
}
