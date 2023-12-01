<?php

namespace Tests\Feature\group;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/v1/cities
 */
class GetCityTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function negTest_getCities_without_authentication(): void {
        $response = $this
        ->withHeaders([])
        ->get("/api/v1/cities");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function posTest_getCities_emptyResults(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/cities");

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /**
     * @test
     */
    public function posTest_getCities(): void {
        parent::createCity();
        parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/cities");

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    /**
     * @test
     */
    public function posTest_getCityById(): void {
        $city = parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/cities/1");

        $response->assertStatus(200);
    }
}
