<?php

namespace Tests\Feature\enterprise;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the GET /api/v1/enterprises
 */
class GetEnterpriseTest extends TestFramework
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
    public function negTest_get_enterprises_without_authentication(): void {
        $response = $this
        ->get("/api/v1/enterprises");

        $response->assertStatus(401);
        $response->assertJson([
            "message" => "Unauthenticated."
        ]);
    }

    /**
     * @test
     */
    public function posTest_get_enterprises(): void {
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/enterprises");

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function posTest_get_enterprises_non_empty(): void {
        parent::createEnterprise();
        parent::createEnterprise();
        parent::createEnterprise();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/enterprises");

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /**
     * @test
     */
    public function posTest_get_enterpriseById_with_nonExistingId(): void {
        parent::createEnterprise();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/enterprises/100");

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => config("messages.general.entity_not_found")
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_get_enterpriseById(): void {
        $json = parent::createEnterprise();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/enterprises/1");

        $response->assertStatus(200);
        $response->assertJson(
            [
                "name" => $json["name"],
                "email" => $json["email"],
                "description" => $json["description"]
            ]
        );
    }
}
