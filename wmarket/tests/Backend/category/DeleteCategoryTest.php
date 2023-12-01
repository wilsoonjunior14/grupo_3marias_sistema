<?php

namespace Tests\Feature\category;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the DELETE /api/v1/categories/{id}
 */
class DeleteCategoryTest extends TestFramework
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
    public function negTest_deleteCategory_without_authentication_before(): void {
        $json = [];

        $response = $this
        ->withHeaders([])
        ->delete("/api/v1/categories/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_deleteCategory_with_invalidId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/categories/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "categoria")
        ]);
    }

    /**
     * @test
     */
    public function negTest_deleteCategory_without_existsCategory(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/categories/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "categoria")
        ]);
    }

    /**
     * @test
     */
    public function posTest_deleteCategory_with_existingCategory(): void {
        parent::createCategory();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/categories/1");

        $response->assertStatus(200);
        $response->assertJson(
            [
                "deleted" => false
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/categories/1");

        $response->assertStatus(200);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/categories/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "categoria")
        ]);
    }
}
