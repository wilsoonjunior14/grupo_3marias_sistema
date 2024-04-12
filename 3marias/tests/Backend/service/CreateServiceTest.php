<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/services
 */
class CreateServiceTest extends TestFramework
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
    public function negTest_createService_without_authorization(): void {
        $response = $this
        ->post("/api/v1/services");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_createService_with_null_payload(): void {
        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria de Serviço é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createService_with_empty_payload(): void {
        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome da Categoria de Serviço é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createService_with_null_service(): void {
        $category = parent::createCategoryService();

        $payload = [
            "service" => null,
            "category_service_name" => $category["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Serviço é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createService_with_empty_service(): void {
        $category = parent::createCategoryService();

        $payload = [
            "service" => "",
            "category_service_name" => $category["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Serviço é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createService_with_wrong_type_service(): void {
        $category = parent::createCategoryService();

        $payload = [
            "service" => 12345,
            "category_service_name" => $category["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Serviço está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createService_with_short_service(): void {
        $category = parent::createCategoryService();

        $payload = [
            "service" => parent::generateRandomString(2),
            "category_service_name" => $category["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Serviço deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createService_with_long_service(): void {
        $category = parent::createCategoryService();

        $payload = [
            "service" => parent::generateRandomString(1000),
            "category_service_name" => $category["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Serviço permite no máximo 255 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createService_with_wrong_type_object_service(): void {
        $category = parent::createCategoryService();

        $payload = [
            "service" => [],
            "category_service_name" => $category["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Serviço é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createService_without_category_service_name(): void {
        $payload = [
            "service" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome da Categoria de Serviço")
            ]
        );
    }

    #[Test]
    public function negTest_createService_with_null_category_service_name(): void {
        $payload = [
            "service" => parent::generateRandomString(),
            "category_service_name" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome da Categoria de Serviço")
            ]
        );
    }

    #[Test]
    public function negTest_createService_with_empty_category_service_name(): void {
        $payload = [
            "service" => parent::generateRandomString(),
            "category_service_name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome da Categoria de Serviço")
            ]
        );
    }

    #[Test]
    public function negTest_createService_with_wrong_type_category_service_name(): void {
        $payload = [
            "service" => parent::generateRandomString(),
            "category_service_name" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Categoria do Serviço")
            ]
        );
    }

    #[Test]
    public function negTest_createService_with_non_existing_service_name(): void {
        $payload = [
            "service" => parent::generateRandomString(),
            "category_service_name" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Categoria do Serviço")
            ]
        );
    }

    #[Test]
    public function posTest_createService(): void {
        $categoryService = parent::createCategoryService();

        $payload = [
            "service" => parent::generateRandomString(),
            "category_service_name" => $categoryService["name"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/services", $payload);

        $response->assertStatus(201);
        $response->assertJson(
            [
                "service" => $payload["service"],
                "category_service_id" => 1
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/services/1");

        $response->assertStatus(200);
        $response->assertJson([
        
            "service" => $payload["service"],
            "category_service_id" => 1,
            "deleted" => 0
        
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/services");

        $response->assertStatus(200);
        $response->assertJson([
            [
                "service" => $payload["service"],
                "category_service_id" => 1,
                "deleted" => 0
            ]
        ]);
    }
}
