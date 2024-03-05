<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/v1/partners
 */
class CreatePartnerTest extends TestFramework
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
    public function negTest_createPartner_without_authorization(): void {
        $response = $this
        ->post("/api/v1/partners");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_empty_payload(): void {
        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_null_payload(): void {
        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_null_fantasy_name_required_field(): void {
        $payload = [
            "fantasy_name" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_empty_fantasy_name_required_field(): void {
        $payload = [
            "fantasy_name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_wrong_type_fantasy_name_required_field(): void {
        $payload = [
            "fantasy_name" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_short_fantasy_name_required_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_long_fantasy_name_required_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor permite no máximo 255 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_without_partner_type_required_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Parceiro/Fornecedor é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_null_partner_type_required_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Parceiro/Fornecedor é obrigatório."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_empty_type_partner_type_required_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Parceiro/Fornecedor é obrigatório."
            ]
        );
    }
    
    /**
     * @test
     */
    public function negTest_createPartner_with_wrong_type_partner_type_required_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_short_partner_type_required_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Parceiro/Fornecedor é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_long_partner_type_required_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Parceiro/Fornecedor é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_null_email_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_empty_email_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_wrong_type_email_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_invalid_email_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_null_phone_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => parent::generateRandomEmail(),
            "phone" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_empty_phone_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => parent::generateRandomEmail(),
            "phone" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_invalid_phone_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => parent::generateRandomEmail(),
            "phone" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Parceiro/Fornecedor está inválido."
            ]
        );
    }


    /**
     * @test
     */
    public function negTest_createPartner_with_wrong_type_phone_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => parent::generateRandomEmail(),
            "phone" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_wrong_type_url_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "website" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Website é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_null_url_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "website" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Website é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_empty_url_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "website" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Website é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_short_url_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "website" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Website é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_long_url_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "website" => "http://" . parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Website permite no máximo 255 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_null_social_reason_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "social_reason" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Razão Social do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_empty_social_reason_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "social_reason" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Razão Social do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_wrong_type_social_reason_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "social_reason" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Razão Social do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_short_social_reason_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "social_reason" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Razão Social do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_long_social_reason_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "social_reason" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Razão Social do Parceiro/Fornecedor permite no máximo 255 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_null_observation_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "observation" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_empty_observation_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "observation" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_wrong_type_observation_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "observation" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_short_observation_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "observation" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_long_observation_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "observation" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Parceiro/Fornecedor permite no máximo 500 caracteres."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_null_cnpj_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ do Parceiro/Fornecedor é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_empty_cnpj_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ do Parceiro/Fornecedor é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_wrong_type_cnpj_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ do Parceiro/Fornecedor é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_with_invalid_cnpj_optional_field(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ do Parceiro/Fornecedor é inválido."
            ]
        );
    }

    /**
     * @test
     */
    public function negTest_createPartner_duplicated(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(201);
        $response->assertJson(
            [
                "fantasy_name" => $payload["fantasy_name"],
                "partner_type" => $payload["partner_type"],
                "cnpj" => $payload["cnpj"]
            ]
        );

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Registro de CNPJ do Parceiro/Fornecedor já registrado em Parceiro/Fornecedores."
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_createPartner_with_required_fields_and_optional_fields(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => parent::generateRandomEmail(),
            "phone" => parent::generateRandomPhoneNumber(),
            "website" => parent::generateURL(),
            "social_reason" => parent::generateRandomString(),
            "observation" => parent::generateRandomString(),
            "cnpj" => "60.725.781/0001-03"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(201);
        $response->assertJson(
            [
                "fantasy_name" => $payload["fantasy_name"],
                "partner_type" => $payload["partner_type"],
                "email" => $payload["email"],
                "phone" => $payload["phone"],
                "website" => $payload["website"],
                "social_reason" => $payload["social_reason"],
                "observation" => $payload["observation"],
                "cnpj" => $payload["cnpj"]
            ]
        );
    }

    /**
     * @test
     */
    public function posTest_createPartner_with_required_fields_and_url(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "website" => parent::generateURL()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(201);
        $response->assertJson(
            [
                "fantasy_name" => $payload["fantasy_name"],
                "partner_type" => $payload["partner_type"],
                "cnpj" => $payload["cnpj"],
                "website" => $payload["website"]
            ]
        );
        $response->assertJsonMissing([
            "social_reason" => null,
            "observation" => null,
        ]);
    }

    /**
     * @test
     */
    public function posTest_createPartner_with_required_fields_and_social_reason(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "social_reason" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(201);
        $response->assertJson(
            [
                "fantasy_name" => $payload["fantasy_name"],
                "partner_type" => $payload["partner_type"],
                "social_reason" => $payload["social_reason"]
            ]
        );
        $response->assertJsonMissing([
            "website" => null,
            "observation" => null
        ]);
    }

    /**
     * @test
     */
    public function posTest_createPartner_with_required_fields_and_observation(): void {
        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "observation" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->post("/api/v1/partners", $payload);

        $response->assertStatus(201);
        $response->assertJson(
            [
                "fantasy_name" => $payload["fantasy_name"],
                "partner_type" => $payload["partner_type"],
                "cnpj" => $payload["cnpj"],
                "observation" => $payload["observation"]
            ]
        );
        $response->assertJsonMissing([
            "website" => null,
            "social_reason" => null
        ]);
    }

}
