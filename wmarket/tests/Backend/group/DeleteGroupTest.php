<?php

namespace Tests\Feature\group;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the DELETE /api/v1/groups/{$id}
 */
class DeleteGroupTest extends TestFramework
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
    public function negTest_deleteGroup_without_authentication(): void {
        $response = $this
        ->delete("/api/v1/groups/2");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_deleteGroup_with_invalidId(): void {
        parent::createGroup();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/groups/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "grupo")
        ]);
    }

    /**
     * @test
     */
    public function negTest_deleteGroup_non_existing_group(): void {
        parent::createGroup();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/groups/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Nenhum registro foi encontrado.'
        ]);
    }

    /**
     * @test
     */
    public function posTest_deleteGroup(): void {
        parent::createGroup();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/groups/2");

        $response->assertStatus(200);
    }
}
