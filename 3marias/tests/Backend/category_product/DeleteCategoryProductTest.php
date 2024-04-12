<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/categoryProducts/{id}
 */
class DeleteCategoryProductTest extends TestFramework
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
    public function negTest_deleteCategoryProducts_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/categoryProducts/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_deleteCategoryProducts_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/categoryProducts/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "Categoria de Produto")
            ]
        );
    }

    #[Test]
    public function negTest_deleteCategoryProducts_with_non_existing_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/categoryProducts/1000");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Identificador de Categoria de Produto não existe."
            ]
        );
    }

    #[Test]
    public function posTest_deleteCategoryProducts(): void {
        $payload = [
            "name" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $payload["name"]
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/categoryProducts");

        $response->assertStatus(200);
        $response->assertJsonCount(1);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/categoryProducts/1");

        $response->assertStatus(200);
        $response->assertJson(
            [
                "deleted" => true
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/categoryProducts/1");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Identificador de Categoria de Produto não existe."
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/categoryProducts");

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }
}
