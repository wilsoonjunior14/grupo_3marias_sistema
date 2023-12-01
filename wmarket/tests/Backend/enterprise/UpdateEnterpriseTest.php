<?php

namespace Tests\Feature\enterprise;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the PUT /api/v1/enterprises/1
 */
class UpdateEnterpriseTest extends TestFramework
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
    public function negTest_updateEnterprise_withoutAuthorization(): void {
        $json = [];

        $response = $this
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(401);
        $response->assertJson([
            "message" => "Unauthenticated."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_withoutValidId(): void {
        $json = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/0", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "empresa")
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_withoutAddressId(): void {
        $json = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_PROVIDED, "endereço")
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_emptyAddressId(): void {
        $json = [
            "address_id" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_PROVIDED, "endereço")
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_invalidAddressId(): void {
        $json = [
            "address_id" => 0
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_PROVIDED, "endereço")
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_without_name(): void {
        $json = [
            "address_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_emptyName(): void {
        $json = [
            "address_id" => 1,
            "name" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_shortName(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_longName(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo nome permite no máximo 100 caracteres.'
        ]);
    }

    /**
     * @test
     */
    // public function negTest_updateEnterprise_without_image(): void {
    //     $json = [
    //         "address_id" => 1,
    //         "name" => parent::generateRandomString(50),
    //         "description" => parent::generateRandomString(10)
    //     ];

    //     $response = $this
    //     ->withHeaders(parent::getHeaders())
    //     ->put("/api/v1/enterprises/1", $json);

    //     $response->assertStatus(400);
    //     $response->assertJson([
    //         "message" => 'Campo de imagem é obrigatório.'
    //     ]);
    // }

    /**
     * @test
     */
    public function negTest_updateEnterprise_without_description(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_emptyDescription(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_shortDescription(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição deve conter no mínimo 3 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_longDescription(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo descrição permite no máximo 255 caracteres.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_without_email(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo email é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_emptyEmail(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo email está inválido."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_invalidEmail(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomString(50)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo email está inválido.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_without_phone(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo telefone é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_emptyPhone(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo telefone é obrigatório.'
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_invalidPhone(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => parent::generateRandomString(10)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'O campo phone não é um celular com DDD válido.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_without_status(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo status é obrigatório.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_randomStatus(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => parent::generateRandomString(100)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo status com valor inválido.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_without_categoryId(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo identificador de categoria é obrigatório.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_emptyAddress(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo endereço é obrigatório.",
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_without_address(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo endereço é obrigatório.",
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_shortAddress(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo endereço deve conter no mínimo 3 caracteres.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_longAddress(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(255)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo endereço permite no máximo 100 caracteres.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_without_neighborhood(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(50)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo bairro é obrigatório.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_emptyNeighborhood(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(50),
            "neighborhood" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo bairro é obrigatório.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_longNeighborhood(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(500)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo bairro permite no máximo 100 caracteres.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_shortNeighborhood(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(2)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo bairro deve conter no mínimo 3 caracteres.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_without_number(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de número é obrigatório.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_emptyNumber(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10),
            "number" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de número é obrigatório.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_longComplement(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10),
            "number" => 100,
            "complement" => parent::generateRandomString(1000)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo complemento permite no máximo 255 caracteres.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_emptyCityId(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10),
            "number" => 100,
            "complement" => parent::generateRandomString(100),
            "city_id" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo identificador de cidade é obrigatório.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_without_cityId(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10),
            "number" => 100,
            "complement" => parent::generateRandomString(100)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo identificador de cidade é obrigatório.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_without_zipcode(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10),
            "number" => 100,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de cep é obrigatório.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_invalidZipCode(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 2,
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10),
            "number" => 100,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => parent::generateRandomString(5)
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => 'Campo de cep deve conter no mínimo 9 caracteres.',
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_nonExistingCityId(): void {
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10),
            "number" => 100,
            "complement" => parent::generateRandomString(100),
            "city_id" => 100,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "cidade"),
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_nonExistingCategoryId(): void {
        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 100,
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10),
            "number" => 100,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ID_NOT_EXISTS, "categoria"),
        ]);
    }

    /**
     * @test
     */
    public function posTest_updateEnterprise_without_linkedSystems(): void {
        parent::createEnterprise();
        parent::createCity();
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "password" => "12345",
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10),
            "number" => 100,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(200);
        $response->assertJson([
            "name" => $json["name"],
            "description" => $json["description"],
            "email" => $json["email"],
            "phone" => $json["phone"],
            "status" => $json["status"],
            "category_id" => $json["category_id"]
        ]);
    }

    /**
     * @test
     */
    public function posTest_updateEnterprise_addresses(): void {
        parent::createEnterprise();
        parent::createCity();
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10),
            "number" => 100,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "password" => "12345",
            "zipcode" => "00000-000"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(200);
        $response->assertJson([
            "addresses" => [
                [
                    "address" => $json["address"],
                    "neighborhood" => $json["neighborhood"],
                    "number" => $json["number"],
                    "complement" => $json["complement"],
                    "city_id" => $json["city_id"],
                    "zipcode" => $json["zipcode"]
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function posTest_updateEnterprise_linkedSystems(): void {
        parent::createEnterprise();
        parent::createCity();
        parent::createCategory();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "password" => "12345",
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10),
            "number" => 100,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "name" => "facebook",
                    "value" => "http://www.facebook.com"
                ],
                [
                    "name" => "instagram",
                    "value" => "http://www.instagram.com"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            "name" => "facebook",
            "value" => "http://www.facebook.com"
        ]);
        $response->assertJsonFragment([
            "name" => "instagram",
            "value" => "http://www.instagram.com"
        ]);
    }

    /**
     * @test
     */
    public function posTest_updateEnterprise_withExistingLinkedSystems(): void {
        parent::createEnterpriseWithLinkedSystems();
        parent::createCity();
        parent::createCategory();
        parent::createGroup();
        parent::createGroup();
        parent::createGroup();

        $json = [
            "address_id" => 1,
            "name" => parent::generateRandomString(50),
            "image" => parent::generateRandomString(10),
            "description" => parent::generateRandomString(20),
            "email" => parent::generateRandomEmail(),
            "phone" => "(88)00000-0000",
            "status" => "waiting",
            "category_id" => 1,
            "password" => "12345",
            "address" => parent::generateRandomString(50),
            "neighborhood" => parent::generateRandomString(10),
            "number" => 100,
            "complement" => parent::generateRandomString(100),
            "city_id" => 1,
            "zipcode" => "00000-000",
            "linkedSystems" => [
                [
                    "name" => "facebook",
                    "value" => "http://www.facebook.com"
                ],
                [
                    "name" => "instagram",
                    "value" => "http://www.instagram.com"
                ]
            ]
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $json);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            "name" => "facebook",
            "value" => "http://www.facebook.com"
        ]);
        $response->assertJsonFragment([
            "name" => "instagram",
            "value" => "http://www.instagram.com"
        ]);
    }
}
