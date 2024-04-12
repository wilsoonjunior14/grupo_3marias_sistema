<?php

namespace Tests\Feature\role;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/roles/groups/{id}
 */
class RemoveRoleGroupTest extends TestFramework
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
    public function negTest_removeRoleGroup_without_authentication_before(): void {
        $response = $this
        ->delete("/api/v1/roles/groups/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function negTest_removeRoleGroup_with_invalidId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/roles/groups/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "permissão/grupo")
        ]);
    }

    #[Test]
    public function negTest_removeRoleGroup_with_nonExistingRoleGroup(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/roles/groups/10");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Nenhum registro foi encontrado.'
        ]);
    }

    #[Test]
    public function posTest_removeRoleGroup(): void {
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

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/roles/groups/1");

        $response->assertStatus(200);
        $response->assertJson([
            "deleted" => true
        ]);
    }
}
