<?php

namespace Tests\Feature\user;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the PUT /api/v1/partners/{id}
 */
class UpdatePartnerTest extends TestFramework
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
    public function negTest_updatePartner_without_authorization(): void {
        $response = $this
        ->put("/api/v1/partners/1");

        $response->assertStatus(401);
        $response->assertJson(
            [
                "message" => "Unauthenticated."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_empty_payload(): void {
        $partner = parent::createPartner();

        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_null_payload(): void {
        $partner = parent::createPartner();

        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_null_fantasy_name_required_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_empty_fantasy_name_required_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_wrong_type_fantasy_name_required_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_short_fantasy_name_required_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_long_fantasy_name_required_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Fantasia do Parceiro/Fornecedor permite no máximo 255 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_without_partner_type_required_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Parceiro/Fornecedor é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_null_partner_type_required_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Parceiro/Fornecedor é obrigatório."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_empty_type_partner_type_required_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Parceiro/Fornecedor é obrigatório."
            ]
        );
    }
    
    #[Test]
    public function negTest_updatePartner_with_wrong_type_partner_type_required_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_short_partner_type_required_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Parceiro/Fornecedor é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_long_partner_type_required_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Tipo de Parceiro/Fornecedor é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_null_email_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_empty_email_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_wrong_type_email_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_invalid_email_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Email do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_null_phone_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => parent::generateRandomEmail(),
            "phone" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_empty_phone_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => parent::generateRandomEmail(),
            "phone" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_invalid_phone_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => parent::generateRandomEmail(),
            "phone" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Parceiro/Fornecedor está inválido."
            ]
        );
    }


    #[Test]
    public function negTest_updatePartner_with_wrong_type_phone_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "email" => parent::generateRandomEmail(),
            "phone" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Telefone do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_wrong_type_url_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "website" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Website é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_null_url_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "website" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Website é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_empty_url_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "website" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Website é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_short_url_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "website" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Website é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_long_url_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "website" => "http://" . parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Website permite no máximo 255 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_null_social_reason_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "social_reason" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Razão Social do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_empty_social_reason_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "social_reason" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Razão Social do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_wrong_type_social_reason_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "social_reason" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Razão Social do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_short_social_reason_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "social_reason" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Razão Social do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_long_social_reason_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "social_reason" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Razão Social do Parceiro/Fornecedor permite no máximo 255 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_null_observation_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "observation" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_empty_observation_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "observation" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_wrong_type_observation_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "observation" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Parceiro/Fornecedor está inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_short_observation_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "observation" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Parceiro/Fornecedor deve conter no mínimo 3 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_long_observation_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"],
            "observation" => parent::generateRandomString(10000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo Nome Completo do Parceiro/Fornecedor permite no máximo 500 caracteres."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_null_cnpj_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ do Parceiro/Fornecedor é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_empty_cnpj_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ do Parceiro/Fornecedor é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_wrong_type_cnpj_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => 12345
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ do Parceiro/Fornecedor é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_invalid_cnpj_optional_field(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Campo CNPJ do Parceiro/Fornecedor é inválido."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_invalid_id(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/0", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => "Nenhum registro de Parceiro/Fornecedor foi encontrado."
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_with_non_existing_id(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => $partner["cnpj"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/100", $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Parceiro/Fornecedor")
            ]
        );
    }

    #[Test]
    public function negTest_updatePartner_duplicated(): void {
        $partner = parent::createPartner();
        $partner2 = parent::createPartner(cnpj: "14.395.688/0001-60");

        $payload = [
            "fantasy_name" => $partner["fantasy_name"],
            "partner_type" => $partner["partner_type"],
            "cnpj" => $partner2["cnpj"]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(400);
        $response->assertJson(
            [
                "message" => sprintf(ErrorMessage::$ENTITY_DUPLICATED, "CNPJ do Parceiro/Fornecedor", "Parceiro/Fornecedores")
            ]
        );
    }

    #[Test]
    public function posTest_updatePartner_with_required_fields_and_optional_fields(): void {
        $partner = parent::createPartner();

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
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(200);
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

    #[Test]
    public function posTest_updatePartner_with_required_fields_and_url(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "website" => parent::generateURL()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(200);
        $response->assertJson(
            [
                "fantasy_name" => $payload["fantasy_name"],
                "partner_type" => $payload["partner_type"],
                "cnpj" => $payload["cnpj"],
                "website" => $payload["website"]
            ]
        );
    }

    #[Test]
    public function posTest_updatePartner_with_required_fields_and_social_reason(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "social_reason" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(200);
        $response->assertJson(
            [
                "fantasy_name" => $payload["fantasy_name"],
                "partner_type" => $payload["partner_type"],
                "social_reason" => $payload["social_reason"]
            ]
        );
    }

    #[Test]
    public function posTest_updatePartner_with_required_fields_and_observation(): void {
        $partner = parent::createPartner();

        $payload = [
            "fantasy_name" => parent::generateRandomString(),
            "partner_type" => parent::generateRandomPeopleType(),
            "cnpj" => "60.725.781/0001-03",
            "observation" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/partners/" . $partner["id"], $payload);

        $response->assertStatus(200);
        $response->assertJson(
            [
                "fantasy_name" => $payload["fantasy_name"],
                "partner_type" => $payload["partner_type"],
                "cnpj" => $payload["cnpj"],
                "observation" => $payload["observation"]
            ]
        );
    }

}
