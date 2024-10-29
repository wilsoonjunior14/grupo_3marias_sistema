<?php

namespace Tests\Feature\middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the userIsAllowed middleware
 */
class UserIsAllowedTest extends TestFramework
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

    // #[Test]
    // public function negTest_usingMiddleware_userIsAllowed_withoutExistingRoles(): void {
    //     $this->withMiddleware(\App\Http\Middleware\UserIsAllowed::class);

    //     $_SERVER["REQUEST_URI"] = "http://www.wmarketproject.com/api/v1/roles";
    //     $_SERVER["REQUEST_METHOD"] = "post";

    //     $body = [
    //         "description" => parent::generateRandomString()
    //     ];

    //     $response = $this
    //     ->withHeaders(parent::getHeaders())
    //     ->post("/api/v1/groups", $body);

    //     $response->assertStatus(404);
    //     $response->assertJson([
    //         "errors" => [
    //             "request" => "Operação desconhecida não pode ser realizada."
    //         ]
    //         ]);
    // }

    // #[Test]
    // public function negTest_usingMiddleware_userIsAllowed_withoutPermissions(): void {
    //     $json = [
    //         "description" => "POST /api/v1/groups",
    //         "request_type" => "post",
    //         "endpoint" => "/api/v1/groups"
    //     ];

    //     $response = $this
    //     ->withHeaders($this->getHeaders())
    //     ->post("/api/v1/roles", $json);
    //     $response->assertStatus(201);

    //     $this->withMiddleware(\App\Http\Middleware\UserIsAllowed::class);

    //     $_SERVER["REQUEST_URI"] = "/api/v1/groups";
    //     $_SERVER["REQUEST_METHOD"] = "post";

    //     $body = [
    //         "description" => parent::generateRandomString()
    //     ];

    //     $response = $this
    //     ->withHeaders(parent::getHeaders())
    //     ->post("/api/v1/groups", $body);

    //     $response->assertStatus(401);
    //     $response->assertJson([
    //         "errors" => [
    //             "request" => "Você não tem permissão para realizar essa operação."
    //         ]
    //         ]);
    // }
}
