<?php

namespace Tests\Feature\role;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the PUT /api/v1/roles/{id}
 */
class UpdateRoleTest extends TestFramework
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
    public function negTest_updateRole_without_authentication_before(): void {
        $json = [];
        $response = $this
        ->put("/api/v1/roles/1", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateRole_with_empty_body(): void {
        $json = [];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/roles/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateRole_with_empty_description(): void {
        $json = [
            "description" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/roles/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateRole_with_empty_endpoint(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "request_type" => parent::getRandomRequestMethod(),
            "endpoint" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/roles/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de url é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateRole_without_endpoint(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "request_type" => "post"
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/roles/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de url é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateRole_with_empty_request_type(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "endpoint" => parent::generateRandomString(),
            "request_type" => ""
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/roles/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de tipo de requisição é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateRole_without_request_type(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "endpoint" => parent::generateRandomString(),
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/roles/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de tipo de requisição é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateRole_with_invalidId(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "endpoint" => parent::generateRandomString(),
            "request_type" => "post"
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/roles/0", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_PROVIDED, "permissão")
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateRole_with_non_existingRole(): void {
        $json = [
            "description" => parent::generateRandomString(),
            "endpoint" => parent::generateRandomString(),
            "request_type" => "post"
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/roles/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => config("messages.general.entity_not_found")
        ]);
    }

    /**
     * @test
     */
    public function posTest_updateRole(): void {
        parent::createRole();
        $json = [
            "description" => parent::generateRandomString(),
            "endpoint" => parent::generateRandomString(),
            "request_type" => "post"
        ];
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/roles/1", $json);

        $response->assertStatus(200);
        $response->assertJson([
            "description" => $json["description"],
            "endpoint" => $json["endpoint"],
            "request_type" => $json["request_type"]
        ]);
    }
}
