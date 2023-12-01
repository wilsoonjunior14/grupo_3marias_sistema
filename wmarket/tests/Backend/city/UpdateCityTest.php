<?php

namespace Tests\Feature\category;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/cities
 */
class UpdateCityTest extends TestFramework
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
    public function negTest_updateCity_without_authentication_before(): void {
        // Arrange
        $json = [];

        // Act
        $response = $this
        ->withHeaders([])
        ->put("/api/v1/cities/1", $json);

        // Assert
        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCity_with_emptyData(): void {
        parent::createCity();
        // Arrange
        $json = [];

        // Act
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/cities/1", $json);

        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "nome")
        ]);
    }

    /**
     * @test
     */
    public function posTest_updateCity(): void {
        $city = parent::createCity();
        // Arrange
        $city["name"] = "updated city";

        // Act
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/cities/1", $city);

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            "name" => $city["name"]
        ]);
    }
}