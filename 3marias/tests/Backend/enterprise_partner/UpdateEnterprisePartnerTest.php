<?php

use App\Models\EnterprisePartner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the PUT /api/v1/enterprisePartners/{id}
 */
class UpdateEnterprisePartnerTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/enterprisePartners";

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function negTest_updateEnterprisePartner_without_authorization(): void {
        $model = new EnterprisePartner();
        $response = $this->sendPutRequest($this->url . "/1", $model, []);
        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_empty_payload(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_null_name(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName(null);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_empty_name(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName(null);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_wrong_type_name(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName(12345);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Sócio está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_short_name(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString(2));

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Sócio deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_long_name(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString(1000));

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Sócio permite no máximo 255 caracteres."
            ]
        );
    }

    // #[Test] TODO: THIS TEST CAN BE ENABLED
    // public function negTest_updateEnterprisePartner_with_special_chars_name(): void {
    //     $model = new EnterprisePartner();
    //     $model
    //         ->withName($this->generateRandomString(10) . "#$%ˆ@");

    //     $response = $this->sendPutRequest($this->url, $model, $this->getHeaders());
    //     $response->assertStatus(400);
    //     $response->assertJson(
    //         [
    //             "message" => "Campo Nome Completo do Sócio está inválido."
    //         ]
    //     );
    // }


    #[Test]
    public function negTest_updateEnterprisePartner_without_phone(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString());

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_null_phone(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone(null);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_empty_phone(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone("");

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_wrong_type_phone(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomNumber(5));

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Sócio está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_invalid_phone(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomString(5));

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Sócio está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_invalid_phone_two(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone("0000000-0000");

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Sócio está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_without_enterprise_id(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber());

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_null_enterprise_id(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(null);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_empty_enterprise_id(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId("");

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_non_existing_enterprise_id(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(100);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa não existe."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_invalid_enterprise_id(): void {
        $this->createEnterprisePartner();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(0);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_without_state(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Estado Civil do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_null_state(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState(null);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Estado Civil do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_empty_state(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("");

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Estado Civil do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_invalid_state(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState($this->generateRandomString());

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Estado Civil do Sócio é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_wrong_type_state(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState(12345);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Estado Civil do Sócio é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_without_ocupation(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Solteiro");

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_null_ocupation(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Solteiro")
            ->withOcupation(null);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_empty_ocupation(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Solteiro")
            ->withOcupation("");

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_wrong_type_ocupation(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Solteiro")
            ->withOcupation(12345);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Sócio está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_short_ocupation(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Solteiro")
            ->withOcupation($this->generateRandomString(2));

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Sócio deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_long_ocupation(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Solteiro")
            ->withOcupation($this->generateRandomString(1000));

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Profissão do Sócio permite no máximo 255 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_without_email(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Solteiro")
            ->withOcupation($this->generateRandomString());

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_null_email(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Solteiro")
            ->withOcupation($this->generateRandomString())
            ->withEmail(null);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_empty_email(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Solteiro")
            ->withOcupation($this->generateRandomString())
            ->withEmail("");

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Sócio é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_wrong_email(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Solteiro")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomString());

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Sócio está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterprisePartner_with_wrong_type_email(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Solteiro")
            ->withOcupation($this->generateRandomString())
            ->withEmail(12345);

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Sócio está inválido."
            ]
        );
    }

    #[Test]
    public function posTest_updateEnterprisePartner(): void {
        $this->createEnterprisePartner();
        $this->createEnterprise();

        $model = new EnterprisePartner();
        $model
            ->withName($this->generateRandomString())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withState("Solteiro")
            ->withOcupation($this->generateRandomString())
            ->withEmail($this->generateRandomEmail())
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000")
            ->withNumber(10)
            ->withComplement($this->generateRandomString());

        $response = $this->sendPutRequest($this->url . "/1", $model, $this->getHeaders());
        $response->assertStatus(200);
        $response->assertJson([
            "name" => $model->name,
            "phone" => $model->phone,
            "enterprise_id" => $model->enterprise_id,
            "state" => $model->state,
            "ocupation" => $model->ocupation,
            "email" => $model->email
        ]);

        $getResponse = $this->sendGetRequest($this->url . "/1", $this->getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJson(
            [
                "name" => $model->name,
                "phone" => $model->phone,
                "enterprise_id" => $model->enterprise_id,
                "state" => $model->state,
                "ocupation" => $model->ocupation,
                "email" => $model->email,
                "address" => $model->address,
                "neighborhood" => $model->neighborhood,
                "zipcode" => $model->zipcode,
                "city_id" => $model->city_id,
                "number" => $model->number,
                "complement" => $model->complement
            ]
        );

        $getResponse = $this->sendGetRequest($this->url, $this->getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJsonCount(1);
        $getResponse->assertJson([
            [
                "name" => $model->name,
                "phone" => $model->phone,
                "enterprise_id" => $model->enterprise_id,
                "state" => $model->state,
                "ocupation" => $model->ocupation,
                "email" => $model->email,
                "address" => [
                    "address" => $model->address,
                    "neighborhood" => $model->neighborhood,
                    "zipcode" => $model->zipcode,
                    "city_id" => $model->city_id,
                    "number" => $model->number,
                    "complement" => $model->complement
                ],
                "deleted" => 0
            ]
        ]);
    }

}
