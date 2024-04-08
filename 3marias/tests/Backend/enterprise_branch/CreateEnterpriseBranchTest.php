<?php

use App\Models\EnterpriseBranch;
use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/enterpriseBranches
 */
class CreateEnterpriseBranchTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    private string $url = "/api/v1/enterpriseBranches";

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function negTest_createEnterprise_without_authorization(): void {
        $model = new EnterpriseBranch();
        $response = $this->sendPostRequest($this->url, $model, []);
        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_empty_payload(): void {
        $model = new EnterpriseBranch();
        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo da Empresa é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_null_name(): void {
        $model = new EnterpriseBranch();
        $model->withName(null);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo da Empresa é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_empty_name(): void {
        $model = new EnterpriseBranch();
        $model->withName("");

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo da Empresa é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_wrong_type_name(): void {
        $model = new EnterpriseBranch();
        $model->withName(12345);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo da Empresa está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_short_name(): void {
        $model = new EnterpriseBranch();
        $model->withName($this->generateRandomString(2));

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo da Empresa deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_long_name(): void {
        $model = new EnterpriseBranch();
        $model->withName($this->generateRandomString(1000));

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo da Empresa permite no máximo 255 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_without_cnpj(): void {
        $model = new EnterpriseBranch();
        $model->withName($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_null_cnpj(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj(null);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_empty_cnpj(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj("");

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_short_cnpj(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomString(2));

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_long_cnpj(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomString(255));

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_wrong_type_cnpj(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj(123456);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_without_phone(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_null_phone(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone(null);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_empty_phone(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone("");

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_wrong_type_phone(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomNumber(5));

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_invalid_phone(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomString(5));

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_invalid_phone_two(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone("0000000-0000");

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_without_enterprise_id(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_null_enterprise_id(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(null);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_empty_enterprise_id(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId("");

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_wrong_type_enterprise_id(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_invalid_enterprise_id(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(0);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_non_existing_enterprise_id(): void {
        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo de Identificador de Empresa está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_without_address(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_empty_address(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress("");

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_null_address(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress(null);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_wrong_type_address(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress(12345);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_short_address(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress($this->generateRandomString(2));

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço deve conter no mínimo 2 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_long_address(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress($this->generateRandomString(1000));

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo endereço permite no máximo 100 caracteres."
            ]
        );
    }


    #[Test]
    public function negTest_createEnterprise_without_neighborhood(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress($this->generateRandomString());

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_empty_neighborhood(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress($this->generateRandomString())
            ->withNeighborhood("");

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_null_neighborhood(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress($this->generateRandomLetters())
            ->withNeighborhood(null);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_wrong_type_neighborhood(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress($this->generateRandomString())
            ->withNeighborhood(12345);

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_short_neighborhood(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString(1));

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro deve conter no mínimo 2 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_createEnterprise_with_long_neighborhood(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString(1000));

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo bairro permite no máximo 100 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updateEnterpriseBranch_with_existing_cnpj(): void {
        $this->createEnterpriseBranch();
        $enterpriseBranch2 = $this->createEnterpriseBranch();
        $this->createEnterpriseEntity();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($enterpriseBranch2["cnpj"])
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000");

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_DUPLICATED, "CNPJ da Filial", "Filiais")
            ]
        );
    }

    #[Test]
    public function posTest_createEnterprise(): void {
        $this->createEnterprise();

        $model = new EnterpriseBranch();
        $model
            ->withName($this->generateRandomString())
            ->withCnpj($this->generateRandomCnpj())
            ->withPhone($this->generateRandomPhoneNumber())
            ->withEnterpriseId(1)
            ->withAddress($this->generateRandomString())
            ->withNeighborhood($this->generateRandomString())
            ->withCityId(1)
            ->withZipCode("00000-000");

        $response = $this->sendPostRequest($this->url, $model, $this->getHeaders());
        $response->assertStatus(201);
        $response->assertJson([
            "name" => $model->name,
            "cnpj" => $model->cnpj,
            "phone" => $model->phone,
            "enterprise_id" => $model->enterprise_id
        ]);

        $getResponse = $this->sendGetRequest($this->url . "/1", $this->getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJson(
            [
                "name" => $model->name,
                "cnpj" => $model->cnpj,
                "phone" => $model->phone,
                "enterprise_id" => $model->enterprise_id,
                "address" => $model->address,
                "neighborhood" => $model->neighborhood,
                "zipcode" => $model->zipcode,
                "city_id" => $model->city_id
            ]
        );

        $getResponse = $this->sendGetRequest($this->url, $this->getHeaders());
        $getResponse->assertStatus(200);
        $getResponse->assertJsonCount(1);
        $getResponse->assertJson([
            [
                "name" => $model->name,
                "cnpj" => $model->cnpj,
                "phone" => $model->phone,
                "enterprise_id" => $model->enterprise_id,
                "address" => [
                    "address" => $model->address,
                    "neighborhood" => $model->neighborhood,
                    "zipcode" => $model->zipcode,
                    "city_id" => $model->city_id
                ],
                "deleted" => 0
            ]
        ]);
    }
}
