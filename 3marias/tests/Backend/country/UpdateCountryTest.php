<?php

namespace Tests\Feature\category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the PUT /api/v1/countries
 */
class UpdateCountryTest extends TestFramework
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
    public function negTest_updateCountry_without_authentication_before(): void {
        $json = [];

        $response = $this
        ->withHeaders([])
        ->put("/api/v1/countries/1", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCountry_with_empty_data(): void {
        parent::createState();
        $json = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/countries/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }
    
    /**
     * @test
     */
    public function posTest_updateCountry(): void {
        $json = parent::createState();
        $json["name"] = "state updated";
        $json["acronym"] = "sta";

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/countries/1", $json);

        $response->assertStatus(200);
        $response->assertJson([
            "name" => $json["name"]
        ]);
    }
}
