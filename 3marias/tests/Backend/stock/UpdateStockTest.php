<?php

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the PUT /api/v1/stocks/{id}
 */
class UpdateStockTest extends TestFramework
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
    public function negTest_updateStock_cost_center_matriz(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/1");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/1");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(),
            "contract_id" => 2,
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/1", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Não é possível atualizar centro de custo da construtora."
        ]);
    }

    #[Test]
    public function negTest_updateStock_invalid_id(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/1");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/1");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(),
            "contract_id" => 2,
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/0", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de Centro de Custo foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_updateStock_with_non_existing_id(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/1");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/1");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(),
            "contract_id" => 2,
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/1000", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Centro de Custo")
        ]);
    }

    #[Test]
    public function negTest_updateStock_with_null_name(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/2");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => null,
            "contract_id" => 2,
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/2", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome do Estoque é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_updateStock_with_empty_name(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/2");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => "",
            "contract_id" => 2,
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/2", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome do Estoque é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_updateStock_with_short_name(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/2");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(2),
            "contract_id" => 2,
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/2", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome do Estoque deve conter no mínimo 3 caracteres."
        ]);
    }


    #[Test]
    public function negTest_updateStock_with_wrong_type_name(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/2");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => 12345,
            "contract_id" => 2,
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/2", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome do Estoque está inválido."
        ]);
    }

    #[Test]
    public function negTest_updateStock_with_long_name(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/2");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(10000),
            "contract_id" => 2,
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/2", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Nome do Estoque permite no máximo 255 caracteres."
        ]);
    }


    #[Test]
    public function negTest_updateStock_with_null_contract_id(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/2");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(),
            "contract_id" => null,
            "status" => "Ativo"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/2", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo identificador de contrato é obrigatório."
        ]);
    }


    #[Test]
    public function negTest_updateStock_with_empty_contract_id(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);
        
        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/2");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(),
            "contract_id" => "",
            "status" => "Ativo"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/2", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo identificador de contrato é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_updateStock_with_invalid_contract_id(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/2");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(),
            "contract_id" => 0,
            "status" => "Ativo"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/2", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Nenhum registro de Contrato foi encontrado."
        ]);
    }

    #[Test]
    public function negTest_updateStock_with_null_status(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/2");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(),
            "contract_id" => 2,
            "status" => null
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/2", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Status é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_updateStock_with_empty_status(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/2");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(),
            "contract_id" => 2,
            "status" => ""
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/2", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Status é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_updateStock_with_invalid_status(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/2");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(),
            "contract_id" => 2,
            "status" => parent::generateRandomString()
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/2", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Status contém um valor inválido."
        ]);
    }

    #[Test]
    public function posTest_updateStock(): void {
        parent::createContract();
        parent::createContract(proposalId: 2);

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/contracts/2");
        $contract = $response->decodeResponseJson();

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->get("/api/v1/stocks/2");

        $response->assertStatus(200);
        $response->assertJson([
            "name" => "Centro de Custo - " . $contract["code"],
            "contract_id" => $contract["id"],
            "status" => "Ativo",
            "deleted" => 0
        ]);

        $payload = [
            "name" => parent::generateRandomString(),
            "contract_id" => 2,
            "status" => "Desativado"
        ];

        $response = $this
        ->withHeaders(parent::getHeaders())
        ->put("/api/v1/stocks/2", $payload);

        $response->assertStatus(200);
        $response->assertJson(
            [
                "name" => $payload["name"],
                "contract_id" => 2,
                "status" => "Desativado",
                "deleted" => 0
            ]
        );
    }

}
