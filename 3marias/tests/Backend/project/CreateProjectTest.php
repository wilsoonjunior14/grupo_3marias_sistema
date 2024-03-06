<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/projects
 */
class CreateProjectTest extends TestFramework
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
    public function negTest_createProject_without_authorization(): void {
        $response = $this
        ->post("/api/v1/projects");

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
    public function negTest_createProject_with_empty_payload(): void {
        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Projeto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProject_with_null_payload(): void {
        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Projeto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProject_with_null_name(): void {
        $payload = [
            "name" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Projeto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProject_with_empty_name(): void {
        $payload = [
            "name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Projeto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProject_with_wrong_type_name(): void {
        $payload = [
            "name" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Projeto está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProject_with_short_name(): void {
        $payload = [
            "name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Projeto deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProject_with_long_name(): void {
        $payload = [
            "name" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome do Projeto permite no máximo 255 caracteres."
            ]
        );
    }

        /**
     * @test
     */
    public function negTest_createProject_with_null_description(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "description" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição do Projeto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProject_with_empty_description(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "description" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição do Projeto é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProject_with_wrong_type_description(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "description" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição do Projeto está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProject_with_short_description(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição do Projeto deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createProject_with_long_description(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Descrição do Projeto permite no máximo 1000 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_createProject(): void {
        $payload = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(201);
        $response->assertJson(
            [
                "name" => $payload["name"],
                "description" => $payload["description"]
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/projects/1");

        $response->assertStatus(200);
        $response->assertJson(
            [
                "name" => $payload["name"],
                "description" => $payload["description"],
                "deleted" => 0
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/projects");

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function negTest_createProject_with_same_name_existing(): void {
        $project = parent::createProject();

        $payload = [
            "name" => $project["name"],
            "description" => $project["description"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/projects", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_DUPLICATED, "Projeto", "Projetos")
            ]
        );
    }

}
