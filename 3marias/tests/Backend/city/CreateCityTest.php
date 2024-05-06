<?php

namespace Tests\Feature\category;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/cities
 */
class CreateCityTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/cities";

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function negTest_createCity_without_authentication_before(): void {
        // Arrange
        $json = [];

        // Act
        $response = $this->sendRequest([], $json);

        // Assert
        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function negTest_createCity_with_empty_data(): void {
        // Arrange
        $json = [];

        // Act
        $response = $this->sendRequest(parent::getHeaders(), $json);

        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createCity_with_empty_name(): void {
        // Arrange
        $json = [
            "name" => ""
        ];

        // Act
        $response = $this->sendRequest(parent::getHeaders(), $json);

        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createCity_with_null_name(): void {
        // Arrange
        $json = [
            "name" => null
        ];

        // Act
        $response = $this->sendRequest(parent::getHeaders(), $json);

        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createCity_with_numbers_on_name(): void {
        // Arrange
        $json = [
            "name" => "12345"
        ];

        // Act
        $response = $this->sendRequest(parent::getHeaders(), $json);

        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo nome deve conter somente letras."
        ]);
    }

    #[Test]
    public function negTest_createCity_without_state_id(): void {
        // Arrange
        $json = [
            "name" => "São Paulo"
        ];

        // Act
        $response = $this->sendRequest(parent::getHeaders(), $json);

        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo identificador de país é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createCity_with_null_state_id(): void {
        // Arrange
        $json = [
            "name" => "São Paulo",
            "state_id" => null
        ];

        // Act
        $response = $this->sendRequest(parent::getHeaders(), $json);

        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo identificador de país é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createCity_with_empty_state_id(): void {
        // Arrange
        $json = [
            "name" => "São Paulo",
            "state_id" => ""
        ];

        // Act
        $response = $this->sendRequest(parent::getHeaders(), $json);

        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo identificador de país é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createCity_with_non_existing_state_id(): void {
        // Arrange
        $json = [
            "name" => "São Paulo",
            "state_id" => "1"
        ];

        // Act
        $response = $this->sendRequest(parent::getHeaders(), $json);

        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "estado")
        ]);
    }

    #[Test]
    public function posTest_createCity(): void {
        parent::createState();
        // Arrange
        $json = [
            "name" => "São Paulo",
            "state_id" => "1"
        ];

        // Act
        $response = $this->sendRequest(parent::getHeaders(), $json);

        // Assert
        $response->assertStatus(201);
        $response->assertJson([
            "name" => $json["name"]
        ]);
    }

    private function sendRequest(array $headers, array $data) {
        return $this
        ->withHeaders($headers)
        ->post($this->url, $data);
    }
}