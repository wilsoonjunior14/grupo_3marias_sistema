<?php

namespace Tests\Feature\group;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function negTest_deleteGroup_without_authentication(): void {
        $response = $this
        ->delete("/api/v1/groups/2");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function negTest_deleteGroup_with_invalidId(): void {
        parent::createGroup();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/groups/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de grupo foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_deleteGroup_non_existing_group(): void {
        parent::createGroup();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/groups/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de grupo foi encontrado."
        ]);
    }

    #[Test]
    public function posTest_deleteGroup(): void {
        parent::createGroup();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/groups/2");

        $response->assertStatus(200);
    }
}
