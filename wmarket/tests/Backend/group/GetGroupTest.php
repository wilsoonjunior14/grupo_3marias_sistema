<?php

namespace Tests\Feature\group;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/v1/groups
 */
class GetGroupTest extends TestFramework
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
    public function negTest_getGroup_without_authentication(): void {
        $response = $this
        ->withHeaders([])
        ->get("/api/v1/groups");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function posTest_getGroup_with_onlyOne_existing_groups(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/groups");

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function posTest_getGroup_with_existing_groups(): void {
        parent::createGroup();
        parent::createGroup();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/groups");

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /**
     * @test
     */
    public function negTest_getGroupById_without_authentication(): void {
        $response = $this
        ->get("/api/v1/groups/1");

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
    public function negTest_getGroupById_with_invalidId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/groups/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "grupo")
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_getGroupById_with_notExisting_group(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/groups/100");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Nenhum registro foi encontrado."
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_getGroupById(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/groups/1");

        $response->assertStatus(200);
    }
}
