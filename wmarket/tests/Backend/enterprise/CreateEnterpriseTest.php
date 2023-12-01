<?php

namespace Tests\Feature\enterprise;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the POST /api/enterprises
 */
class CreateEnterpriseTest extends TestFramework
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
    public function negTest_createEnterprise_with_empty_data(): void {
        $json = [];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo nome é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_empty_name(): void {
        $json = [
            "name" => ""
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo nome é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_long_name(): void {
        $json = [
            "name" => parent::generateRandomString(500)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo nome permite no máximo 100 caracteres."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_short_name(): void {
        $json = [
            "name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo nome deve conter no mínimo 3 caracteres."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_without_description(): void {
        $json = [
            "name" => parent::generateRandomString()
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_long_description(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(500)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição permite no máximo 255 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_short_description(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(2)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_without_email(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString()
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo email é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_invalid_email(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomString()
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo email está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_without_phone(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail()
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo telefone é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_long_invalid_phone(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)".parent::generateRandomString(50)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "O campo phone não é um celular com DDD válido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_short_invalid_phone(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => parent::generateRandomString(5)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "O campo phone não é um celular com DDD válido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_without_status(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000"
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo status é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_without_category_id(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting"
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo identificador de categoria é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_without_address(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo endereço é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_long_address(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(500)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo endereço permite no máximo 100 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_short_address(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(2)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo endereço deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_without_neighborhood(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString()
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo bairro é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_long_neighborhood(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(500)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo bairro permite no máximo 100 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_short_neighborhood(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(2)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo bairro deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_without_number(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString()
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de número é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_number_with_invalid_type(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => parent::generateRandomString(5)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de número deve ser um número inteiro.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_long_complement(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo complemento permite no máximo 255 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_cityId_with_invalid_type(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(),
            "city_id" => parent::generateRandomString(5)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo identificador de cidade deve ser um número inteiro.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_without_cityId(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo identificador de cidade é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_without_zipcode(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de cep é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_short_zipcode(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => parent::generateRandomString(4)
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de cep deve conter no mínimo 9 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_nonExisting_category_id(): void {
        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 100,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 100,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Identificador de categoria não existe."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_nonExisting_city_id(): void {
        parent::createCategory();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 100,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Identificador de cidade não existe."
        ]);
    }

    /**
     * @test
     */
    public function posTest_createEnterprise_with_valid_data(): void {
        parent::createCategory();
        parent::createCity();
        parent::createGroup();
        parent::createGroup();
        parent::createGroup();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "password" => "12345",
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function posTest_createEnterprise_with_linkedSystems(): void {
        parent::createCategory();
        parent::createCity();
        parent::createGroup();
        parent::createGroup();
        parent::createGroup();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "password" => "12345",
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "name" => "facebook",
                    "value" => parent::generateRandomString()
                ],
                [
                    "name" => "instagram",
                    "value" => parent::generateRandomString()
                ],
                [
                    "name" => "other",
                    "value" => parent::generateRandomString()
                ]
            ]
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $json["name"],
            "email" => $json["email"],
            "phone" => $json["phone"]
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_linkedSystems_with_invalidName(): void {
        parent::createCategory();
        parent::createCity();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "name" => "facebook",
                    "value" => parent::generateRandomString()
                ],
                [
                    "name" => "microsoft",
                    "value" => parent::generateRandomString()
                ],
                [
                    "name" => "other",
                    "value" => parent::generateRandomString()
                ]
            ]
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo nome do sistema linkado contém um valor inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_linkedSystems_with_emptyName(): void {
        parent::createCategory();
        parent::createCity();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "name" => "facebook",
                    "value" => parent::generateRandomString()
                ],
                [
                    "name" => "",
                    "value" => parent::generateRandomString()
                ],
                [
                    "name" => "other",
                    "value" => parent::generateRandomString()
                ]
            ]
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo nome do sistema linkado é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_linkedSystems_withoutName(): void {
        parent::createCategory();
        parent::createCity();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "value" => parent::generateRandomString()
                ]
            ]
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo nome do sistema linkado é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_linkedSystems_with_invalidTypeInName(): void {
        parent::createCategory();
        parent::createCity();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "name" => 12345,
                    "value" => parent::generateRandomString()
                ]
            ]
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo nome do sistema linkado deve conter caracteres."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_linkedSystems_withoutValue(): void {
        parent::createCategory();
        parent::createCity();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "name" => "facebook"
                ]
            ]
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo url do sistema linkado é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_linkedSystems_withEmptyValue(): void {
        parent::createCategory();
        parent::createCity();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "name" => "facebook",
                    "value" => ""
                ]
            ]
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo url do sistema linkado é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_linkedSystems_withInvalidTypeInValue(): void {
        parent::createCategory();
        parent::createCity();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "name" => "facebook",
                    "value" => 12345
                ]
            ]
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo url do sistema linkado deve conter caracteres."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_linkedSystems_withShortValue(): void {
        parent::createCategory();
        parent::createCity();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "name" => "facebook",
                    "value" => "12"
                ]
            ]
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo url do sistema linkado deve conter no mínimo 3 caracteres."
        ]);
    }

    /**
     * @test
     */
    public function negTest_createEnterprise_with_linkedSystems_withLongValue(): void {
        parent::createCategory();
        parent::createCity();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "name" => "facebook",
                    "value" => parent::generateRandomString(1000)
                ]
            ]
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo url do sistema linkado permite no máximo 500 caracteres."
        ]);
    }

    /**
     * @test
     */
    public function posTest_createEnterprise_with_already_existingEnterprise(): void {
        parent::createCategory();
        parent::createCity();
        parent::createGroup();
        parent::createGroup();
        parent::createGroup();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "password" => "12345",
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $json["name"],
            "email" => $json["email"]
        ]);

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Empresa com nome ou email já existentes."
        ]);
    }

    /**
     * @test
     */
    public function posTest_createEnterprise_with_already_existingEnterprise_withSameEmail(): void {
        parent::createCategory();
        parent::createCity();
        parent::createGroup();
        parent::createGroup();
        parent::createGroup();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "password" => "12345",
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $json["name"],
            "email" => $json["email"]
        ]);

        $json["name"] = parent::generateRandomString();
        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Empresa com nome ou email já existentes."
        ]);
    }

    /**
     * @test
     */
    public function posTest_createEnterprise_with_already_existingEnterprise_withSameName(): void {
        parent::createCategory();
        parent::createCity();
        parent::createGroup();
        parent::createGroup();
        parent::createGroup();

        $json = [
            "name" => parent::generateRandomString(),
            "description" => parent::generateRandomString(),
            "email" => parent::generateRandomEmail(),
            "phone" => "(00)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "password" => "12345",
            "address" => parent::generateRandomString(),
            "neighborhood" => parent::generateRandomString(),
            "number" => 1000,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(201);
        $response->assertJson([
            "name" => $json["name"],
            "email" => $json["email"]
        ]);

        $json["email"] = parent::generateRandomEmail();
        $response = $this
        ->post("/api/enterprises", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Empresa com nome ou email já existentes."
        ]);
    }
}
