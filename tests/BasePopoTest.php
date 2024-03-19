<?php

declare(strict_types=1);

namespace Tests;

use Tests\Mock\ArrayPopo;
use Tests\Mock\SamplePopo;
use Tests\Mock\CollectionPopo;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Route;

/**
 * @internal
 */
class BasePopoTest extends TestCase
{
    /**
     * @var CollectionPopo
     */
    private CollectionPopo $collectionPopo;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $samplePopo = new SamplePopo('test');
        $this->collectionPopo = new CollectionPopo('private', collect([$samplePopo]), 4);

        Route::get('/popo/test', fn () => $this->collectionPopo);
    }

    /** @test */
    public function can_return_from_route()
    {
        $response = $this->get('/popo/test');
        $response->assertStatus(200);

        $this->assertEquals([
            'samples' => [
                [
                    'name' => 'test',
                ],
            ],
            'number' => 4,
            'nullable' => null,
            'array' => [],
        ], $response->json());
    }

    /** @test */
    public function private_attributes_are_hidden()
    {
        $response = $this->get('/popo/test');
        $response->assertStatus(200);

        $this->assertFalse(isset($response->json()['thisIsPrivate']));
    }

    /** @test */
    public function can_to_array_a_popo()
    {
        $arrayablePopo = new ArrayPopo('Bertrand');

        $array = $arrayablePopo->toArray();

        $this->assertEquals(['firstName' => 'Bertrand'], $array);
    }

    /** @test */
    public function can_to_test_array_a_popo()
    {
        $arrayablePopo = new ArrayPopo('Bertrand');

        $array = $arrayablePopo->toTestArray();

        $this->assertEquals(['first_name' => 'Bertrand'], $array);
    }

    /** @test */
    public function can_to_array_colletion_popo()
    {
        $array = $this->collectionPopo->toArray();

        $this->assertEquals([
            'samples' => [
                [
                    'name' => 'test',
                ],
            ],
            'number' => 4,
            'nullable' => null,
            'array' => [],
        ], $array);
    }
}
