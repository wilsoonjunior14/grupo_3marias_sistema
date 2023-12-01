<?php

namespace Tests\Feature\category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/categories/getByCity
 */
class GetCategoryByCityTest extends TestFramework
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
    public function negTest_get_categories_byCity_with_unauthenticated_user_non_existing_city(): void {
        $response = $this
        ->withHeaders([])
        ->get("/api/categories/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Identificador de cidade não existe."
        ]);
    }

    /**
     * @test
     */
    public function negTest_get_categories_byCity_with_authenticated_user_non_existing_city(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/categories/1");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Identificador de cidade não existe."
        ]);
    }

    /**
     * @test
     */
    public function negTest_get_categories_byCity_with_invalid_city_id(): void {
        $response = $this
        ->withHeaders([])
        ->get("/api/categories/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Identificador de cidade não existe."
        ]);
    }

    /**
     * @test
     */
    public function negTest_get_categories_byCity_with_non_existing_city_id(): void {
        parent::createCity();
        parent::createCity();
        parent::createCity();

        $response = $this
        ->withHeaders([])
        ->get("/api/categories/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Identificador de cidade não existe."
        ]);
    }

    /**
     * @test
     */
    public function posTest_get_categories_byCity_without_categories(): void {
        parent::createCity();
        parent::createCity();
        parent::createCity();

        $response = $this
        ->withHeaders([])
        ->get("/api/categories/1");

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /**
     * @test
     */
    public function posTest_get_categories_byCity_with_categories_without_enterprises(): void {
        parent::createCity();
        parent::createCity();
        parent::createCity();

        parent::createCategory();
        parent::createCategory();

        $response = $this
        ->withHeaders([])
        ->get("/api/categories/2");

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /**
     * @test
     */
    public function posTest_get_categories_byCity_with_categories_with_enterprises(): void {
        parent::createCity();
        parent::createCity();
        parent::createCity();

        $category1 = parent::createCategory();
        $category2 = parent::createCategory();

        parent::createEnterpriseWithCategoryAndCity(1, 1);
        parent::createEnterpriseWithCategoryAndCity(1, 2);
        parent::createEnterpriseWithCategoryAndCity(2, 1);

        $payloadResponse = [$category1["name"], $category2["name"]];
        sort($payloadResponse);
        $payloadResponse = [
            [
                "name" => $payloadResponse[0],
                "deleted" => false,
                "amount_enterprises" => 1
            ],
            [
                "name" => $payloadResponse[1],
                "deleted" => false,
                "amount_enterprises" => 1
            ]
        ];

        $response = $this
        ->withHeaders([])
        ->get("/api/categories/1");

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJson($payloadResponse);
    }
}
