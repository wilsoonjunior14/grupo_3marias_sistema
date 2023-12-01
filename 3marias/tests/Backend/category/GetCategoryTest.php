<?php

namespace Tests\Feature\category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/v1/categories
 */
class GetCategoryTest extends TestFramework
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
    public function negTest_get_all_categories_with_unauthenticated_user(): void {
        $json = [];

        $response = $this
        ->withHeaders([])
        ->get("/api/v1/categories");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function posTest_get_all_categories_without_categories_created(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/categories");

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function posTest_get_all_categories(): void {
        parent::createCategory();
        parent::createCategory();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/categories");

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    /**
     * @test
     */
    public function negTest_categories_getByCity_non_existing_city(): void {
        $cityId = 100;

        $response = $this
        ->get("/api/categories/{$cityId}");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Identificador de cidade não existe."
        ]);
    }

    /**
     * @test
     */
    public function posTest_categories_getByCity_existing_city(): void {
        parent::createCity();
        $cityId = 1;

        $response = $this
        ->get("/api/categories/{$cityId}");

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function posTest_categories_getByCity_existing_enterprises_and_categories(): void {
        parent::createCategory();
        parent::createCategory();
        parent::createCategory();

        parent::createCity();
        parent::createCity();

        parent::createEnterpriseWithCategoryAndCity(1, 1);
        parent::createEnterpriseWithCategoryAndCity(1, 2);
        parent::createEnterpriseWithCategoryAndCity(2, 3);

        $response = $this
        ->get("/api/categories/1");

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

}
