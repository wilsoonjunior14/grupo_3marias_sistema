<?php

namespace Tests\Feature\group;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the PUT /api/v1/groups/{id}
 */
class UpdateGroupTest extends TestFramework
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
    public function negTest_updateGroup_without_authentication_before(): void {
        $json = [];

        $response = $this
        ->withHeaders([])
        ->put("/api/v1/groups/1", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function negTest_updateGroup_with_empty_body(): void {
        $json = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/groups/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_updateGroup_with_empty_description(): void {
        $json = [
            "description" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/groups/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_updateGroup_with_description_with_invalid_type(): void {
        $json = [
            "description" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/groups/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição deve conter caracteres.'
        ]);
    }

    #[Test]
    public function negTest_updateGroup_with_long_description(): void {
        $json = [
            "description" => parent::generateRandomString(500)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/groups/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição permite no máximo 100 caracteres.'
        ]);
    }

    #[Test]
    public function negTest_updateGroup_with_short_description(): void {
        $json = [
            "description" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/groups/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição deve conter no mínimo 3 caracteres.'
        ]);
    }

    #[Test]
    public function negTest_updateGroup_with_invalidId(): void {
        $json = [
            "description" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/groups/0", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_PROVIDED, "grupo")
        ]);
    }

    #[Test]
    public function negTest_updateGroup_with_non_existing_group(): void {
        $json = [
            "description" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/groups/2", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Nenhum registro foi encontrado.'
        ]);
    }

    #[Test]
    public function posTest_updateGroup(): void {
        parent::createGroup();

        $json = [
            "description" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/groups/2", $json);

        $response->assertStatus(200);
        $response->assertJson([
            "description" => $json["description"]
        ]);
    }
}
