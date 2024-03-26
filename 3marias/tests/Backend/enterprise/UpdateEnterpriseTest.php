<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;

/**
 * This suite tests the PUT /api/v1/enterprises/{id}
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
    public function negTest_updateEnterprise_without_authorization(): void {
        $response = $this
        ->put("/api/v1/enterprises/1", []);

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
    public function negTest_updateEnterprise_with_null_payload(): void {
        $payload = [null];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo da Empresa é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_empty_payload(): void {
        $payload = [];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/enterprises/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome Completo da Empresa é obrigatório."
        ]);
    }

    /**
     * @test
     */
    public function negTest_updateEnterprise_with_existing_cnpj(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_name(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_cnpj(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_bank(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_bank_agency(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_bank_account(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_creci(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_email(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_fantasy_name(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_municipal_registration(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_state_registration(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_pix(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_only_social_reason(): void { }

    /**
     * @test
     */
    public function posTest_updateEnterprise_all_fields(): void { }
}
