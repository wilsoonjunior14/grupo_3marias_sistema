<?php

namespace Tests\Feature\role;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/v1/roles | /api/v1/roles/{id}
 */
class GetRoleTest extends TestFramework
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
    public function negTest_getRoles_without_authentication_before(): void {
        $response = $this
        ->get("/api/v1/roles");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_getRoleById_without_authentication_before(): void {
        $response = $this
        ->get("/api/v1/roles/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function posTest_getRoles_without_existing_roles(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/roles");

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function posTest_getRoles_with_existing_roles(): void {
        $role1 = parent::createRole();
        $role2 = parent::createRole();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/roles");

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    /**
     * @test
     */
    public function negTest_getRoleById_with_invalidId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/roles/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "permissão")
        ]);
    }

    /**
     * @test
     */
    public function negTest_getRoleById_with_notExistingRole(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/roles/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => config("messages.general.entity_not_found")
        ]);
    }

    /**
     * @test
     */
    public function posTest_getRoleById(): void {
        $role1 = parent::createRole();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/roles/1");

        $response->assertStatus(200);
        $response->assertJson([
            "description" => $role1["description"]
        ]);
    }
}
