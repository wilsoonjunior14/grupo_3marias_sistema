<?php

namespace Tests\Feature\group;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function negTest_getCities_without_authentication(): void {
        $response = $this
        ->withHeaders([])
        ->get("/api/v1/cities");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function posTest_getCities_emptyResults(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/cities");

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    #[Test]
    public function posTest_getCities(): void {
        parent::createCity();
        parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/cities");

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    #[Test]
    public function posTest_getCityById(): void {
        $city = parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/cities/1");

        $response->assertStatus(200);
    }

    #[Test]
    public function negTest_getCity_with_invalid_id(): void {
        $city = parent::createCity();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/cities/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Cidade")
        ]);
    }
}
