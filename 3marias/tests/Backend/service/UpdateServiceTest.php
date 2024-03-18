<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the PUT /api/v1/services/{id}
 */
class UpdateServiceTest extends TestFramework
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
    public function negTest_updateService_without_authorization(): void {
        $response = $this
        ->put("/api/v1/services/1");

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
    public function negTest_updateService_with_null_payload(): void {
        parent::createService();

        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria de Serviço é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateService_with_empty_payload(): void {
        parent::createService();

        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria de Serviço é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateService_with_null_service(): void {
        parent::createService();

        $category = parent::createCategoryService();

        $payload = [
            "service" => null,
            "category_service_name" => $category["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Serviço é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateService_with_empty_service(): void {
        parent::createService();

        $category = parent::createCategoryService();

        $payload = [
            "service" => "",
            "category_service_name" => $category["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Serviço é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateService_with_wrong_type_service(): void {
        parent::createService();

        $category = parent::createCategoryService();

        $payload = [
            "service" => 12345,
            "category_service_name" => $category["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Serviço está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateService_with_short_service(): void {
        parent::createService();

        $category = parent::createCategoryService();

        $payload = [
            "service" => parent::generateRandomString(2),
            "category_service_name" => $category["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Serviço deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateService_with_long_service(): void {
        parent::createService();

        $category = parent::createCategoryService();

        $payload = [
            "service" => parent::generateRandomString(1000),
            "category_service_name" => $category["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Serviço permite no máximo 255 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateService_with_wrong_type_object_service(): void {
        parent::createService();

        $category = parent::createCategoryService();

        $payload = [
            "service" => [],
            "category_service_name" => $category["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Serviço é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateService_without_category_service_name(): void {
        parent::createService();

        $payload = [
            "service" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome da Categoria de Serviço")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateService_with_null_category_service_name(): void {
        parent::createService();

        $payload = [
            "service" => parent::generateRandomString(),
            "category_service_name" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome da Categoria de Serviço")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateService_with_empty_category_service_name(): void {
        parent::createService();

        $payload = [
            "service" => parent::generateRandomString(),
            "category_service_name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome da Categoria de Serviço")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateService_with_wrong_type_category_service_name(): void {
        parent::createService();

        $payload = [
            "service" => parent::generateRandomString(),
            "category_service_name" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Categoria do Serviço")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_updateService_with_non_existing_service_name(): void {
        parent::createService();

        $payload = [
            "service" => parent::generateRandomString(),
            "category_service_name" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Categoria do Serviço")
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_updateService(): void {
        $service = parent::createService();

        $categoryService = parent::createCategoryService();

        $payload = [
            "service" => parent::generateRandomString(),
            "category_service_name" => $categoryService["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/services/1", $payload);

        $response->assertStatus(200);
        $response->assertJson(
            [
                "service" => $payload["service"],
                "category_service_id" => 2
            ]
        );
        $response->assertJsonMissing(
            [
                "service" => $service["service"],
                "category_service_id" => 1
            ]
        );
    }
}
