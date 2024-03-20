<?php

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the PUT /api/v1/products
 */
class UpdateProductTest extends TestFramework
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
    public function negTest_updateProduct_without_authorization(): void {
        $response = $this
        ->put("/api/v1/products/1");

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
    public function negTest_updateProduct_with_null_payload(): void {
        parent::createProduct();

        $payload = [null];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome da Categoria de Produto")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateProduct_with_empty_payload(): void {
        parent::createProduct();

        $payload = [];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome da Categoria de Produto")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateProduct_with_null_product(): void {
        $category = parent::createCategoryProduct();
        parent::createProduct();

        $payload = [
            "product" => null,
            "category_product_name" => $category["name"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Produto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateProduct_with_empty_product(): void {
        $category = parent::createCategoryProduct();
        parent::createProduct();

        $payload = [
            "product" => "",
            "category_product_name" => $category["name"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Produto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateProduct_with_wrong_type_product(): void {
        $category = parent::createCategoryProduct();
        parent::createProduct();

        $payload = [
            "product" => 12345,
            "category_product_name" => $category["name"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Produto está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateProduct_with_short_product(): void {
        $category = parent::createCategoryProduct();
        parent::createProduct();

        $payload = [
            "product" => parent::generateRandomString(2),
            "category_product_name" => $category["name"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Produto deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateProduct_with_long_product(): void {
        $category = parent::createCategoryProduct();
        parent::createProduct();

        $payload = [
            "product" => parent::generateRandomString(1000),
            "category_product_name" => $category["name"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Produto permite no máximo 255 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateProduct_without_category_product_name(): void {
        parent::createProduct();
        $payload = [
            "product" => parent::generateRandomString()
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome da Categoria de Produto")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateProduct_with_null_category_product_name(): void {
        parent::createProduct();
        $payload = [
            "product" => parent::generateRandomString(),
            "category_product_name" => null
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria de Produto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateProduct_with_empty_category_product_name(): void {
        parent::createProduct();
        $payload = [
            "product" => parent::generateRandomString(),
            "category_product_name" => ""
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome da Categoria de Produto")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateProduct_with_wrong_type_category_product_name(): void {
        parent::createProduct();
        $payload = [
            "product" => parent::generateRandomString(),
            "category_product_name" => 12345
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Categoria Associada")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateProduct_with_invalid_category_product_name(): void {
        parent::createProduct();
        $payload = [
            "product" => parent::generateRandomString(),
            "category_product_name" => parent::generateRandomString()
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Categoria Associada")
            ]
        );
    }


    /**
     * @test
     */
    public function negTest_updateProduct_with_invalid_product_id(): void {
        $category = parent::createCategoryProduct();
        parent::createProduct();
        $payload = [
            "product" => parent::generateRandomString(),
            "category_product_name" => $category["name"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/0", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "Produto")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateProduct_with_non_existing_product_id(): void {
        $category = parent::createCategoryProduct();
        parent::createProduct();
        $payload = [
            "product" => parent::generateRandomString(),
            "category_product_name" => $category["name"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/100", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Produto")
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_updateProduct(): void {
        $category = parent::createCategoryProduct();
        parent::createProduct();
        $payload = [
            "product" => parent::generateRandomString(),
            "category_product_name" => $category["name"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(200);
        $response->assertJson(
            [
                "product" => $payload["product"],
                "category_product_id" => 2
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_updateProduct_without_change_fields(): void {
        $category = parent::createCategoryProduct();
        $payload = [
            "product" => parent::generateRandomString(),
            "category_product_name" => $category["name"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/products", $payload);

        $response->assertStatus(201);
        $response->assertJson(
            [
                "product" => $payload["product"],
                "category_product_id" => 1
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/products/1", $payload);

        $response->assertStatus(200);
        $response->assertJson(
            [
                "product" => $payload["product"],
                "category_product_id" => 1
            ]
        );
    }
}
