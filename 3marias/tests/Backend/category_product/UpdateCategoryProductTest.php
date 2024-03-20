<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the PUT /api/v1/categoryProducts/{id}
 */
class UpdateCategoryProductTest extends TestFramework
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
    public function negTest_updateCategoryProducts_without_authorization(): void {
        $response = $this
        ->put("/api/v1/categoryProducts/1", []);

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateCategoryProduct_with_empty_payload(): void {
        parent::createCategoryProduct();
        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categoryProducts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateCategoryProduct_with_null_payload(): void {
        parent::createCategoryProduct();
        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categoryProducts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateCategoryProduct_with_null_name(): void {
        parent::createCategoryProduct();
        $payload = [
            "name" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categoryProducts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateCategoryProduct_with_empty_name(): void {
        parent::createCategoryProduct();
        $payload = [
            "name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categoryProducts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateCategoryProduct_with_wrong_type_name(): void {
        parent::createCategoryProduct();
        $payload = [
            "name" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categoryProducts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateCategoryProduct_with_short_name(): void {
        parent::createCategoryProduct();
        $payload = [
            "name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categoryProducts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateCategoryProduct_with_long_name(): void {
        parent::createCategoryProduct();
        $payload = [
            "name" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categoryProducts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto permite no máximo 255 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateCategoryProduct_with_existing_name(): void {
        $category1 = parent::createCategoryProduct();
        $category2 = parent::createCategoryProduct();

        $payload = [
            "name" => $category2["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categoryProducts/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_DUPLICATED, "Nome da Categoria", "Categorias de Produtos")
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_updateCategoryProduct(): void {
        $category1 = parent::createCategoryProduct();
        $category2 = parent::createCategoryProduct();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/categoryProducts/1");

        $response->assertStatus(200);
        $response->assertJson([
            "id" => 1,
            "name" => $category1["name"],
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(),
            "category_products_father_id" => $category2["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/categoryProducts/1", $payload);

        $response->assertStatus(200);
        $response->assertJson([
            "name" => $payload["name"],
            "category_products_father_id" => 2
        ]);
    }

}
