<?php

namespace Tests\Feature\role;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/roles/search
 */
class SearchRoleTest extends TestFramework
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
    public function negTest_searchRoles_without_authentication_before(): void {
        $json = [];
        $response = $this
        ->post("/api/v1/roles/search", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => 'Unauthenticated.'
        ]);
    }

    #[Test]
    public function posTest_searchRoles_empty_payload_and_empty_results(): void {
        $payload = [];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/search", $payload);

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    #[Test]
    public function posTest_searchRoles_empty_payload(): void {
        $role1 = parent::createRole();

        $payload = [];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/search", $payload);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson(
            [[
                "description" => $role1["description"],
                "request_type" => $role1["request_type"],
                "endpoint" => $role1["endpoint"]
            ]]
        );
    }

    #[Test]
    public function posTest_searchRoles_search_by_wrong_description(): void {
        $role1 = parent::createRole();

        $payload = [
            "description" => parent::generateRandomString()
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/search", $payload);

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    #[Test]
    public function posTest_searchRoles_search_by_wrong_request_type(): void {
        $role1 = parent::createRole();

        $payload = [
            "request_type" => parent::generateRandomString()
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/search", $payload);

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    #[Test]
    public function posTest_searchRoles_search_by_wrong_endpoint(): void {
        $role1 = parent::createRole();

        $payload = [
            "endpoint" => parent::generateRandomString()
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/search", $payload);

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    #[Test]
    public function posTest_searchRoles_search_using_endpoint_field(): void {
        $role1 = parent::createRole();

        $payload = [
            "endpoint" => $role1["endpoint"]
        ];
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/roles/search", $payload);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson([
            [
                "description" => $role1["description"],
                "request_type" => $role1["request_type"],
                "endpoint" => $role1["endpoint"]
            ]
            ]);
    }
}
