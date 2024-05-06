<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the DELETE /api/v1/projects/{id}
 */
class DeleteProjectTest extends TestFramework
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
    public function negTest_deleteProject_without_authorization(): void {
        $response = $this
        ->delete("/api/v1/projects/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_deleteProject_with_invalid_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/projects/0");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Nenhum registro de Projeto foi encontrado."
            ]
        );
    }

    #[Test]
    public function negTest_deleteProject_with_non_existing_id(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/projects/1000");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Projeto")
            ]
        );
    }

    #[Test]
    public function posTest_deleteProject(): void {
        $project = parent::createProject();

        $getAllResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/projects");

        $getAllResponse->assertStatus(200);
        $getAllResponse->assertJsonCount(1);
        $getAllResponse->assertJson([
            [
                "id" => $project["id"],
                "name" => $project["name"],
                "description" => $project["description"],
                "deleted" => 0
            ]
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->delete("/api/v1/projects/" . $project["id"]);

        $response->assertStatus(200);
        $response->assertJson(
            [
                "deleted" => true
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/projects/" . $project["id"]);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Projeto")
        ]);

        $getAllResponse = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/projects");

        $getAllResponse->assertStatus(200);
        $getAllResponse->assertJsonCount(0);
    }
}
