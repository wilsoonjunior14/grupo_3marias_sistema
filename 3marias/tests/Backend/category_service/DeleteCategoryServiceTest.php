<?php

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/categoryServices/{id}
 */
class DeleteCategoryServiceTest extends TestFramework
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
    public function negTest_deleteCategoryService_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/categoryServices/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_deleteCategoryService_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/categoryServices/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "Categoria de Serviço")
            ]
        );
    }

    #[Test]
    public function negTest_deleteCategoryService_with_non_existing_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/categoryServices/1000");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Identificador de Categoria de Serviço não existe."
            ]
        );
    }

    #[Test]
    public function posTest_deleteCategoryService(): void {
        $category = parent::createCategoryService();

        $getAllResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/categoryServices");

        $getAllResponse->assertStatus(200);
        $getAllResponse->assertJsonCount(1);
        $getAllResponse->assertJson([
            [
                "id" => 1,
                "name" => $category["name"],
                "deleted" => 0
            ]
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/categoryServices/1");

        $response->assertStatus(200);
        $response->assertJson(
            [
                "deleted" => true
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/categoryServices/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Identificador de Categoria de Serviço não existe."
        ]);

        $getAllResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/categoryServices");

        $getAllResponse->assertStatus(200);
        $getAllResponse->assertJsonCount(0);
    }
}
