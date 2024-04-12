<?php

use App\Models\EnterpriseOwner;
use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/enterpriseOwners
 */
class CreateEnterpriseOwnerTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/enterpriseOwners";

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function negTest_createEnterpriseOwner_without_authorization(): void {
        $model = new EnterpriseOwner();
        $response = $this->sendPostRequest($this->url, $model);
        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_without_name(): void {
        $model = new EnterpriseOwner();
        $model
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_null_name(): void {
        $model = new EnterpriseOwner();
        $model
            ->withName(null)
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_empty_name(): void {
        $model = new EnterpriseOwner();
        $model
            ->withName("")
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_short_name(): void {
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString(2))
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Representante Legal deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_long_name(): void {
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString(1000))
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Representante Legal permite no máximo 255 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_wrong_type_name(): void {
        $model = new EnterpriseOwner();
        $model
            ->withName(12345)
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Representante Legal está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_without_phone(): void {
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_null_phone(): void {
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone(null)
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_empty_phone(): void {
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone("")
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_invalid_phone(): void {
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomString(200))
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Representante Legal está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_without_enterprise_id(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_null_enterprise_id(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(null)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_empty_enterprise_id(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId("")
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_zero_enterprise_id(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(0)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_non_existing_enterprise_id(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(10)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa não existe."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_wrong_type_enterprise_id(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId($this->generateRandomLetters(2))
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_without_state(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Estado Civil do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_null_state(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState(null)
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Estado Civil do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_empty_state(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Estado Civil do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_invalid_state(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState($this->generateRandomString())
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Estado Civil do Representante Legal é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_wrong_type_state(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState(12345)
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Estado Civil do Representante Legal é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_without_ocupation(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_null_ocupation(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation(null)
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_empty_ocupation(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation("")
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_short_ocupation(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString(2))
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Representante Legal deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_long_ocupation(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString(1000))
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Representante Legal permite no máximo 255 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_wrong_type_ocupation(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation(12345)
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Representante Legal está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_without_email(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_null_email(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail(null)
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_empty_email(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail("")
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_invalid_email(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomString())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Representante Legal está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_without_cpf(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_null_cpf(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withCPF(null)
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_empty_cpf(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withCPF("")
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF do Representante Legal é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_wrong_type_cpf(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withCPF(12345)
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_invalid_cpf(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withCPF($this->generateRandomNumber(11))
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CPF é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_null_rg(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withCPF($this->generateRandomCpf())
            ->withRg(null)
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo RG do Representante Legal deve conter no mínimo 13 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_invalid_rg(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withCPF($this->generateRandomCpf())
            ->withRg($this->generateRandomLetters(14))
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo RG do Representante Legal está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_with_empty_rg(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withCPF($this->generateRandomCpf())
            ->withRg("")
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo RG do Representante Legal deve conter no mínimo 13 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterpriseOwner_repeatedCPF(): void {
        $enterpriseOwner = $this->createEnterpriseOwner();

        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withCPF($enterpriseOwner["cpf"])
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_DUPLICATED, "CPF do Representante Legal", "Representantes Legais")
            ]
        );
    }

    #[Test]
    public function posTest_createEnterpriseOwner_required_fields(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withCPF($this->generateRandomCpf())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(201);
        $response->assertJson(
            [
                "name" => $model->name,
                "phone" => $model->phone,
                "enterprise_id" => $model->enterprise_id,
                "state" => $model->state,
                "ocupation" => $model->ocupation,
                "email" => $model->email,
                "cpf" => $model->cpf
            ]
        );

        $response = $this->sendGetRequest($this->url, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson([
            [
                "name" => $model->name,
                "phone" => $model->phone,
                "enterprise_id" => $model->enterprise_id,
                "state" => $model->state,
                "ocupation" => $model->ocupation,
                "email" => $model->email,
                "cpf" => $model->cpf,
                "address" => [
                    "address" => $model->address,
                    "neighborhood" => $model->neighborhood,
                    "city_id" => $model->city_id,
                    "zipcode" => $model->zipcode,
                    "number" => $model->number,
                    "complement" => $model->complement
                ]
            ]
        ]);

        $response = $this->sendGetRequest($this->url . "/1", $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "name" => $model->name,
                "phone" => $model->phone,
                "enterprise_id" => $model->enterprise_id,
                "state" => $model->state,
                "ocupation" => $model->ocupation,
                "email" => $model->email,
                "address" => $model->address,
                "neighborhood" => $model->neighborhood,
                "city_id" => $model->city_id,
                "zipcode" => $model->zipcode,
                "number" => $model->number,
                "complement" => $model->complement
            ]
        );
    }

    #[Test]
    public function posTest_createEnterpriseOwner_all_fields(): void {
        $this->createEnterpriseEntity();
        $model = new EnterpriseOwner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Casado")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withCPF($this->generateRandomCpf())
            ->withNaturality($this->generateRandomLetters())
            ->withNationality($this->generateRandomLetters())
            ->withRg($this->generateRandomNumber(14))
            ->withRgOrgan("ssp/ce")
            ->withRgDate(date('Y-m-d'))
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(1)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(201);
        $response->assertJson(
            [
                "name" => $model->name,
                "phone" => $model->phone,
                "enterprise_id" => $model->enterprise_id,
                "state" => $model->state,
                "ocupation" => $model->ocupation,
                "email" => $model->email,
                "cpf" => $model->cpf,
                "naturality" => $model->naturality,
                "nationality" => $model->nationality,
                "rg" => $model->rg,
                "rg_date" => $model->rg_date,
                "rg_organ" => $model->rg_organ
            ]
        );

        $response = $this->sendGetRequest($this->url, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson([
            [
                "name" => $model->name,
                "phone" => $model->phone,
                "enterprise_id" => $model->enterprise_id,
                "state" => $model->state,
                "ocupation" => $model->ocupation,
                "email" => $model->email,
                "cpf" => $model->cpf,
                "naturality" => $model->naturality,
                "nationality" => $model->nationality,
                "rg" => $model->rg,
                "rg_date" => $model->rg_date,
                "rg_organ" => $model->rg_organ,
                "address" => [
                    "address" => $model->address,
                    "neighborhood" => $model->neighborhood,
                    "city_id" => $model->city_id,
                    "zipcode" => $model->zipcode,
                    "number" => $model->number,
                    "complement" => $model->complement
                ]
            ]
        ]);

        $response = $this->sendGetRequest($this->url . "/1", $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson(
            [
                "name" => $model->name,
                "phone" => $model->phone,
                "enterprise_id" => $model->enterprise_id,
                "state" => $model->state,
                "ocupation" => $model->ocupation,
                "email" => $model->email,
                "cpf" => $model->cpf,
                "naturality" => $model->naturality,
                "nationality" => $model->nationality,
                "rg" => $model->rg,
                "rg_date" => $model->rg_date,
                "rg_organ" => $model->rg_organ,
                "address" => $model->address,
                "neighborhood" => $model->neighborhood,
                "city_id" => $model->city_id,
                "zipcode" => $model->zipcode,
                "number" => $model->number,
                "complement" => $model->complement
            ]
        );
    }
}
