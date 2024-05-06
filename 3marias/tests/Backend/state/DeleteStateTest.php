<?php

namespace Tests\Feature\state;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/states
 */
class DeleteStateTest extends TestFramework
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
    public function negTest_deleteStates_unauthorized(): void {
        $response = $this
        ->delete("/api/v1/states/1");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function negTest_deleteStates_invalidId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/states/0");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de Estado foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_deleteStates_nonExistingId(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/states/100");

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de Estado foi encontrado."
        ]);
    }

    #[Test]
    public function posTest_deleteStates(): void {
        parent::createState();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/states/1");

        $response->assertStatus(200);
        $response->assertJson([
            "deleted" => true
        ]);
    }
}
