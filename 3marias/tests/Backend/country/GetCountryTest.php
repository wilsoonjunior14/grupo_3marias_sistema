<?php

namespace Tests\Feature\category;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the GET /api/v1/countries
 */
class GetCountryTest extends TestFramework
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
    public function negTest_getCountries_without_authentication_before(): void {
        $response = $this
        ->withHeaders([])
        ->get("/api/v1/countries");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function posTest_getCountries_with_non_existing_data(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/countries");

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    #[Test]
    public function posTest_getCountries_with_data(): void {
        $json = parent::createCountry();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/countries");

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson([[
            "name" => $json["name"]
        ]]);
    }

    #[Test]
    public function posTest_getCountry_byId_withInvalidId(): void {
        $json = parent::createCountry();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/countries/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "país")
        ]);
    }

    #[Test]
    public function posTest_getCountry_byId_withNonExistingId(): void {
        $json = parent::createCountry();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/countries/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => ErrorMessage::$ENTITY_NOT_FOUND
        ]);
    }

    #[Test]
    public function posTest_getCountry_byId_withExistingId(): void {
        $json = parent::createCountry();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/countries/1");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => $json["name"],
            "deleted" => false
        ]);
    }
}
