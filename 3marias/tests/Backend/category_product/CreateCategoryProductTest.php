<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/categoryProducts
 */
class CreateCategoryProductTest extends TestFramework
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
    public function negTest_createCategoryProducts_without_authorization(): void {
        $response = $this
        ->post("/api/v1/categoryProducts", []);

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_createCategoryProduct_with_empty_payload(): void {
        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createCategoryProduct_with_null_payload(): void {
        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createCategoryProduct_with_null_name(): void {
        $payload = [
            "name" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createCategoryProduct_with_empty_name(): void {
        $payload = [
            "name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createCategoryProduct_with_wrong_type_name(): void {
        $payload = [
            "name" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createCategoryProduct_with_short_name(): void {
        $payload = [
            "name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createCategoryProduct_with_long_name(): void {
        $payload = [
            "name" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria do Produto permite no máximo 255 caracteres."
            ]
        );
    }

    #[Test]
    public function posTest_createCategoryProduct_with_null_father_id(): void {
        parent::createCategoryProduct();

        $payload = [
            "name" => parent::generateRandomString(),
            "category_products_father_id" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador da Categoria de Produto está inválido."
        ]);
    }

    #[Test]
    public function posTest_createCategoryProduct_with_empty_father_id(): void {
        parent::createCategoryProduct();

        $payload = [
            "name" => parent::generateRandomString(),
            "category_products_father_id" => "   "
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Identificador da Categoria de Produto está inválido."
        ]);
    }

    #[Test]
    public function posTest_createCategoryProduct_with_non_existing_father_id(): void {
        parent::createCategoryProduct();

        $payload = [
            "name" => parent::generateRandomString(),
            "category_products_father_id" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Categoria Associada")
        ]);
    }

    #[Test]
    public function posTest_createCategoryProduct_with_existing_name(): void {
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
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_DUPLICATED, "Nome da Categoria", "Categorias de Produtos")
            ]
        );
    }

    #[Test]
    public function posTest_createCategoryProduct_with_father_id(): void {
        $categoryProduct = parent::createCategoryProduct();

        $payload = [
            "name" => parent::generateRandomString(),
            "category_products_father_id" => $categoryProduct["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/categoryProducts", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $payload["name"],
            "category_products_father_id" => 1
        ]);
    }

}
