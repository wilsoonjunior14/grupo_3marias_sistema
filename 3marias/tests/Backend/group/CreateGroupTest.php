<?php

namespace Tests\Feature\group;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/groups
 */
class CreateGroupTest extends TestFramework
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
    public function negTest_createGroup_without_authentication_before(): void {
        $json = [];

        $response = $this
        ->withHeaders([])
        ->post("/api/v1/groups", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function negTest_createGroup_with_random_authorization_token(): void {
        $json = [];

        parent::setToken(parent::generateRandomString());

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/groups", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function negTest_createGroup_with_empty_data(): void {
        $json = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createGroup_with_empty_description(): void {
        $json = [
            "description" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createGroup_with_long_description(): void {
        $json = [
            "description" => parent::generateRandomString(500)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição permite no máximo 100 caracteres.'
        ]);
    }

    #[Test]
    public function negTest_createGroup_with_short_description(): void {
        $json = [
            "description" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição deve conter no mínimo 3 caracteres.'
        ]);
    }

    #[Test]
    public function posTest_createGroup_valid_data(): void {
        $json = [
            "description" => parent::generateRandomString(10)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/groups", $json);

        $response->assertStatus(201);
    }

    #[Test]
    public function negTest_create_duplicated_groups(): void {
        $group = parent::createGroup();

        $json = [
            "description" => $group["description"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Registro com informações já existentes.'
        ]);
    }

    #[Test]
    public function negTest_create_group_with_integer_description(): void {
        $json = [
            "description" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição deve conter caracteres.'
        ]);
    }

    #[Test]
    public function negTest_create_group_with_boolean_description(): void {
        $json = [
            "description" => false
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição deve conter caracteres.'
        ]);
    }

    #[Test]
    public function posTest_createGroup(): void {
        $json = [
            "description" => parent::generateRandomString(10)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/groups", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "description" => $json["description"],
            "deleted" => false
        ]);
    }
}
