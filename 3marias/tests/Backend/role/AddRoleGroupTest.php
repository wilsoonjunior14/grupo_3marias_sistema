<?php

namespace Tests\Feature\role;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/roles/groups
 */
class AddRoleGroupTest extends TestFramework
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
    public function negTest_createRoleGroup_without_authentication_before(): void {
        $json = [];

        $response = $this
        ->post("/api/v1/roles/groups", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function negTest_createRoleGroup_with_empty_body(): void {
        $json = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Identificador da permissão é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createRoleGroup_with_missing_groupId(): void {
        $json = [
            "role_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Identificador do grupo é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createRoleGroup_with_missing_roleId(): void {
        $json = [
            "group_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Identificador da permissão é obrigatório.'
        ]);
    }

    #[Test]
    public function negTest_createRoleGroup_with_invalid_roleId(): void {
        $json = [
            "role_id" => 0,
            "group_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Nenhum registro de Permissão foi encontrado.'
        ]);
    }

    #[Test]
    public function negTest_createRoleGroup_with_invalid_groupId(): void {
        parent::createRole();

        $json = [
            "role_id" => 1,
            "group_id" => 0
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Nenhum registro de Grupo de Usuário foi encontrado.'
        ]);
    }

    #[Test]
    public function negTest_createRoleGroup_with_invalid_ids(): void {
        $json = [
            "role_id" => 0,
            "group_id" => 0
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de Grupo de Usuário foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_createRoleGroup_with_non_existing_ids(): void {
        $json = [
            "role_id" => 1,
            "group_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de Permissão foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_createRoleGroup_with_non_existingGroup(): void {
        parent::createRole();

        $json = [
            "role_id" => 1,
            "group_id" => 2
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de Grupo de Usuário foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_createRoleGroup_with_non_existingRole(): void {
        parent::createGroup();

        $json = [
            "role_id" => 2,
            "group_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/groups", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de Permissão foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_createRoleGroup_avoidDuplicateRoles(): void {
        parent::createGroup();
        parent::createRole();

        $json = [
            "role_id" => 1,
            "group_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/groups", $json);
        $response->assertStatus(201);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/groups", $json);
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_DUPLICATED, "permissão", "grupo")
        ]);
    }

    #[Test]
    public function posTest_createRoleGroup(): void {
        parent::createGroup();
        parent::createRole();

        $json = [
            "role_id" => 1,
            "group_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/groups", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "role_id" => 1,
            "group_id" => 1
        ]);
    }
}
