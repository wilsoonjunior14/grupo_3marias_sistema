<?php

namespace Tests\Feature\category;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the PUT /api/v1/categories/{id}
 */
class UpdateCategoryTest extends TestFramework
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
    public function negTest_updateCategory_without_authentication_before(): void {
        $response = $this
        ->withHeaders([])
        ->put("/api/v1/categories/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategory_with_empty_payload(): void {
        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categories/0", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategory_with_empty_name(): void {
        $payload = [
            "name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categories/0", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategory_with_short_name(): void {
        $payload = [
            "name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categories/0", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategory_with_long_name(): void {
        $payload = [
            "name" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categories/0", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome permite no máximo 100 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategory_with_invalidId(): void {
        $payload = [
            "name" => parent::generateRandomString(100)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categories/0", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "categoria")
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategory_with_nonExistingCategory(): void {
        $payload = [
            "name" => parent::generateRandomString(100)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categories/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "categoria")
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateCategory_with_nonExistingName(): void {
        parent::createCategory();
        $category2 = parent::createCategory();
        parent::createCategory();

        $payload = [
            "name" => $category2["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categories/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => ErrorMessage::$ENTITY_EXISTS
        ]);
    }
}
