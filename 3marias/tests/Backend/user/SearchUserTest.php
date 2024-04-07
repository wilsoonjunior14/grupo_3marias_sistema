<?php

namespace Tests\Feature\user;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/users/search
 */
class SearchUserTest extends TestFramework
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
    public function negTest_searchUsers_without_authorization(): void {
        $data = [];
        $response = $this
        ->post("/api/v1/users/search", $data);

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function posTest_searchUsers_with_empty_data(): void {
        $data = [];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/users/search", $data);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    #[Test]
    public function posTest_searchUsers_using_name(): void {
        $user1 = parent::createUser();

        $data = [
            "name" => $user1["name"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/users/search", $data);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    #[Test]
    public function posTest_searchUsers_using_email(): void {
        $user1 = parent::createUser();

        $data = [
            "email" => $user1["email"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/users/search", $data);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    #[Test]
    public function posTest_searchUsers_all_fields(): void {
        $user1 = parent::createUser();

        $data = [
            "email" => $user1["email"],
            "name" => $user1["name"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/users/search", $data);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }
}
