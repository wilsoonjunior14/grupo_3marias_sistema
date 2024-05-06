<?php

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the PUT /api/v1/accountants
 */
class UpdateAccountantTest extends TestFramework
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
    public function negTest_updateAccountant_without_authorization(): void {
        $response = $this
        ->put("/api/v1/accountants/1", []);

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_updateAccountant_with_null_payload(): void {
        parent::createAccountant();

        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_updateAccountant_with_empty_payload(): void {
        parent::createAccountant();

        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_updateAccountant_with_null_name(): void {
        parent::createAccountant();

        $payload = [
            "name" => null,
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_updateAccountant_with_empty_name(): void {
        parent::createAccountant();

        $payload = [
            "name" => "",
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_updateAccountant_with_wrong_type_name(): void {
        parent::createAccountant();

        $payload = [
            "name" => 12345,
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador está inválido."
        ]);
    }

    #[Test]
    public function negTest_updateAccountant_with_wrong_type_object_name(): void {
        parent::createAccountant();

        $payload = [
            "name" => [],
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_updateAccountant_with_short_name(): void {
        parent::createAccountant();

        $payload = [
            "name" => parent::generateRandomString(2),
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador deve conter no mínimo 3 caracteres."
        ]);
    }

    #[Test]
    public function negTest_updateAccountant_with_long_name(): void {
        parent::createAccountant();

        $payload = [
            "name" => parent::generateRandomString(1000),
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo do Contador permite no máximo 255 caracteres."
        ]);
    }

    #[Test]
    public function negTest_updateAccountant_with_null_phone(): void {
        parent::createAccountant();

        $payload = [
            "name" => parent::generateRandomString(),
            "phone" => null,
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Telefone do Contador é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_updateAccountant_with_wrong_phone(): void {
        parent::createAccountant();

        $payload = [
            "name" => parent::generateRandomString(),
            "phone" => parent::generateRandomString(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Telefone do Contador está inválido."
        ]);
    }

    #[Test]
    public function negTest_updateAccountant_with_invalid_id(): void {
        parent::createAccountant();

        $payload = [
            "name" => parent::generateRandomString(),
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/0", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Contador")
        ]);
    }

    #[Test]
    public function negTest_updateAccountant_with_non_existing_id(): void {
        parent::createAccountant();

        $payload = [
            "name" => parent::generateRandomString(),
            "phone" => parent::generateRandomString(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 1,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/1110", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Contador")
        ]);
    }

    #[Test]
    public function posTest_updateAccountant(): void {
        parent::createAccountant();
        parent::createCity();

        $payload = [
            "name" => parent::generateRandomString(),
            "phone" => parent::generateRandomPhoneNumber(),
            "enterprise_id" => 1,
            "address" => parent::generateRandomString(),
            "city_id" => 2,
            "neighborhood" => parent::generateRandomString(),
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/accountants/1", $payload);

        $response->assertStatus(200);
        $response->assertJson([
            "name" => $payload["name"],
            "phone" => $payload["phone"],
            "enterprise_id" => $payload["enterprise_id"],
            "address" => $payload["address"],
            "neighborhood" => $payload["neighborhood"],
            "city_id" => $payload["city_id"],
            "zipcode" => $payload["zipcode"],
            "deleted" => 0
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/accountants/1", $payload);

        $response->assertStatus(200);
        $response->assertJson([
            "name" => $payload["name"],
            "phone" => $payload["phone"],
            "enterprise_id" => $payload["enterprise_id"]
        ]);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/accountants", $payload);

        $response->assertStatus(200);
        $response->assertJson([[
            "name" => $payload["name"],
            "phone" => $payload["phone"],
            "enterprise_id" => $payload["enterprise_id"]
        ]]);
    }

}
