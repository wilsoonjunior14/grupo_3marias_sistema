<?php

namespace Tests\Backend\broker;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

class CreateBrokerTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/brokers";

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function negTest_createBroker_without_authorization(): void {
        $response = $this
        ->post("/api/v1/brokers", []);

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_createBroker_withoutName(): void {
        $this->createCity();

        $model = [
                "cpf" => $this->generateRandomCpf(),
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome Completo do Corretor")
            ]
        );
    }

    #[Test]
    public function negTest_createBroker_withEmptyName(): void {
        $this->createCity();

        $model = [
                "name" => "",
                "cpf" => $this->generateRandomCpf(),
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_INVALID, "Nome Completo do Corretor")
            ]
        );
    }

    #[Test]
    public function negTest_createBroker_withNullName(): void {
        $this->createCity();

        $model = [
                "name" => null,
                "cpf" => $this->generateRandomCpf(),
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_INVALID, "Nome Completo do Corretor")
            ]
        );
    }

    #[Test]
    public function negTest_createBroker_withShortName(): void {
        $this->createCity();

        $model = [
                "name" => $this->generateRandomLetters(2),
                "cpf" => $this->generateRandomCpf(),
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_SHORT, "Nome Completo do Corretor", "3")
            ]
        );
    }

    #[Test]
    public function negTest_createBroker_withLongName(): void {
        $this->createCity();

        $model = [
                "name" => $this->generateRandomLetters(1000),
                "cpf" => $this->generateRandomCpf(),
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$FIELD_LONG, "Nome Completo do Corretor", "255")
            ]
        );
    }

    #[Test]
    public function posTest_createBroker_withSpecialCharsName(): void {
        $this->createCity();

        $model = [
                "name" => $this->generateRandomLetters(10) . "#$%",
                "cpf" => $this->generateRandomCpf(),
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(201);
    }

    #[Test]
    public function negTest_createBroker_withInvalidTypeName(): void {
        $this->createCity();

        $model = [
                "name" => 12345,
                "cpf" => $this->generateRandomCpf(),
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_INVALID, "Nome Completo do Corretor")
        ]);
    }

    #[Test]
    public function negTest_createBroker_withNullCPF(): void {
        $this->createCity();

        $model = [
                "name" => $this->generateRandomString(),
                "cpf" => null,
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_INVALID, "CPF")
        ]);
    }

    #[Test]
    public function negTest_createBroker_withEmptyCPF(): void {
        $this->createCity();

        $model = [
                "name" => $this->generateRandomString(),
                "cpf" => "",
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_INVALID, "CPF")
        ]);
    }

    #[Test]
    public function negTest_createBroker_withoutCPF(): void {
        $this->createCity();

        $model = [
                "name" => $this->generateRandomString(),
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(201);
    }

    #[Test]
    public function negTest_createBroker_withInvalidTypeCPF(): void {
        $this->createCity();

        $model = [
                "name" => $this->generateRandomString(),
                "cpf" => 12345,
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_INVALID, "CPF")
        ]);
    }

    #[Test]
    public function negTest_createBroker_withRandomStringCPF(): void {
        $this->createCity();

        $model = [
                "name" => $this->generateRandomString(),
                "cpf" => $this->generateRandomString(),
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_INVALID, "CPF")
        ]);
    }

    #[Test]
    public function negTest_createBroker_withShortCPF(): void {
        $this->createCity();

        $model = [
                "name" => $this->generateRandomString(),
                "cpf" => $this->generateRandomString(3),
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_INVALID, "CPF")
        ]);
    }

    #[Test]
    public function negTest_createBroker_withLongCPF(): void {
        $this->createCity();

        $model = [
                "name" => $this->generateRandomString(),
                "cpf" => $this->generateRandomString(100),
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$FIELD_INVALID, "CPF")
        ]);
    }

    #[Test]
    public function negTest_createBroker_withoutCreci(): void {

    }

    #[Test]
    public function negTest_createBroker_withShortCreci(): void {

    }

    #[Test]
    public function negTest_createBroker_withNullCreci(): void {

    }

    #[Test]
    public function negTest_createBroker_withEmptyCreci(): void {

    }

    #[Test]
    public function negTest_createBroker_withLongCreci(): void {

    }

    #[Test]
    public function negTest_createBroker_withInvalidTypeCreci(): void {

    }

    #[Test]
    public function posTest_createBroker(): void {
        $this->createCity();

        $model = [
                "name" => $this->generateRandomString(50),
                "cpf" => $this->generateRandomCpf(),
                "creci" => $this->generateRandomString(15),
                "phone" => $this->generateRandomPhoneNumber(),
                "email" => $this->generateRandomEmail(),
                "address" => $this->generateRandomString(),
                "neighborhood" => $this->generateRandomString(),
                "zipcode" => $this->generateRandomNumber(5) . "-000",
                "number" => "S/N",
                "complement" => "",
                "city_id" => 1
        ];
        $response = $this->post($this->url, $model, headers: $this->getHeaders());
        $response->assertStatus(201);
        $response->assertJson(
            [
                "name" => $model["name"],
                "cpf" => $model["cpf"],
                "creci" => $model["creci"],
                "email" => $model["email"],
                "phone" => $model["phone"],
                "address_id" => 1
            ]
        );
    }
}
