<?php

namespace Tests\Feature\role;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/roles/{id}
 */
class DeleteRoleTest extends TestFramework
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
    public function negTest_deleteRole_without_authentication_before(): void {
        $response = $this
        ->withHeaders([])
        ->delete("/api/v1/roles/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_deleteRole_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/roles/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "permissão")
        ]);
    }

    /**
     * @test
     */
    public function negTest_deleteRole_non_existingRole(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/roles/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => config("messages.general.entity_not_found")
        ]);
    }

    /**
     * @test
     */
    public function posTest_deleteRole_existingRole(): void {
        parent::createRole();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/roles/1");

        $response->assertStatus(200);
        $response->assertJson([
            "deleted" => true
        ]);
    }
}
