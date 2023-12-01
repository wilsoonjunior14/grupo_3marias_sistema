<?php

namespace Tests\Feature\role;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/roles
 */
class CreateRoleTest extends TestFramework
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
    public function negTest_createRole_without_authentication_before(): void {
        $json = [];

        $response = $this
        ->withHeaders([])
        ->post("/api/v1/roles", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRole_with_empty_data(): void {
        $json = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRole_with_empty_description(): void {
        $json = [
            "description" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRole_with_long_description(): void {
        $json = [
            "description" => parent::generateRandomString(500)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição permite no máximo 100 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRole_with_short_description(): void {
        $json = [
            "description" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRole_without_requestType(): void {
        $json = [
            "description" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de tipo de requisição é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRole_without_endpoint(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "request_type" => parent::getRandomRequestMethod()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de url é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createRole_with_short_endpoint(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "request_type" => parent::getRandomRequestMethod(),
            "endpoint" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de url deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function posTest_createRole_with_long_endpoint(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "request_type" => "post",
            "endpoint" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(201);
        $response->assertJson(["description" => $json['description']]);
        $response->assertJson(["request_type" => $json['request_type']]);
        $response->assertJson(["endpoint" => $json['endpoint']]);
    }

    /**
     * @test
     */
    public function posTest_createRole_with_put_request(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "request_type" => "put",
            "endpoint" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(201);
        $response->assertJson(["description" => $json['description']]);
        $response->assertJson(["request_type" => $json['request_type']]);
        $response->assertJson(["endpoint" => $json['endpoint']]);
    }

    /**
     * @test
     */
    public function posTest_createRole_with_get_request(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "request_type" => "get",
            "endpoint" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(201);
        $response->assertJson(["description" => $json['description']]);
        $response->assertJson(["request_type" => $json['request_type']]);
        $response->assertJson(["endpoint" => $json['endpoint']]);
    }

    /**
     * @test
     */
    public function posTest_createRole_with_delete_request(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "request_type" => "delete",
            "endpoint" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(201);
        $response->assertJson(["description" => $json['description']]);
        $response->assertJson(["request_type" => $json['request_type']]);
        $response->assertJson(["endpoint" => $json['endpoint']]);
    }

    /**
     * @test
     */
    public function posTest_createRole_with_patch_request(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "request_type" => "patch",
            "endpoint" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(201);
        $response->assertJson(["description" => $json['description']]);
        $response->assertJson(["request_type" => $json['request_type']]);
        $response->assertJson(["endpoint" => $json['endpoint']]);
    }

    /**
     * @test
     */
    public function negTest_createRole_with_invalid_request_type(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "request_type" => "postputdelete",
            "endpoint" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de tipo de requisição contém um valor inválido.'
        ]);
    }
}
