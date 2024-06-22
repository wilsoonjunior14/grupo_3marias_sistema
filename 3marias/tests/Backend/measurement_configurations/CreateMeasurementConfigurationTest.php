<?php

namespace Tests\Feature\group;

use App\Utils\ErrorMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/measurementConfigurations
 */
class CreateMeasurementConfigurationTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();

        for ($i = 0; $i < 20; $i ++) {
            $json = [
                "service" => $this->generateRandomLetters(15)
            ];
            $response = $this
            ->withHeaders($this->getHeaders())
            ->post("/api/v1/measurementItems", $json);

            $response->assertStatus(201);
            $response->assertJson([
                "service" => $json["service"]
            ]);
        }
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function negTest_createMeasurementConfiguration_without_measurements(): void {
        $payload = [];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/measurementConfigurations", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Medições é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createMeasurementConfiguration_with_null_measurements(): void {
        $payload = [
            "measurements" => null
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/measurementConfigurations", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Medições é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createMeasurementConfiguration_with_empty_measurements(): void {
        $payload = [
            "measurements" => ""
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/measurementConfigurations", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Medições é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createMeasurementConfiguration_with_empty_array_measurements(): void {
        $payload = [
            "measurements" => []
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/measurementConfigurations", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Medições é obrigatório."
        ]);
    }

    #[Test]
    public function negTest_createMeasurementConfiguration_with_two_items_on_array_measurements(): void {
        $payload = [
            "measurements" => [
                [
                    "incidence" => 00.00,
                    "measurement_item_id" => 1,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 50.00,
                    "measurement_item_id" => 2,
                    "bill_receive_id" => 3
                ]
            ]
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/measurementConfigurations", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Medições deve conter no mínimo 20 itens de medição."
        ]);
    }

    #[Test]
    public function negTest_createMeasurementConfiguration_with_repeated_items_on_array_measurements(): void {

        $payload = [
            "measurements" => [
                [
                    "incidence" => 00.00,
                    "measurement_item_id" => 1,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 50.00,
                    "measurement_item_id" => 2,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 00.00,
                    "measurement_item_id" => 1,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 00.00,
                    "measurement_item_id" => 1,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 5,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 6,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 7,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 8,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 9,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 10,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 11,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 12,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 13,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 14,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 15,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 16,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 17,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 18,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 19,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 20,
                    "bill_receive_id" => 3
                ]
            ]
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/measurementConfigurations", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Item de Medição está duplicado."
        ]);
    }

    #[Test]
    public function negTest_createMeasurementConfiguration_with_invalid_measuremnent_items(): void {
        $this->createContract();

        $payload = [
            "measurements" => [
                [
                    "incidence" => 3.78,
                    "measurement_item_id" => 100,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 1.49,
                    "measurement_item_id" => 2,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 8.51,
                    "measurement_item_id" => 3,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 1.22,
                    "measurement_item_id" => 4,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 30.00,
                    "measurement_item_id" => 5,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 5.89,
                    "measurement_item_id" => 6,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 4.11,
                    "measurement_item_id" => 7,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 2.22,
                    "measurement_item_id" => 8,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 7.78,
                    "measurement_item_id" => 9,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 10,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 11,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 30.00,
                    "measurement_item_id" => 12,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 13,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 14,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 15,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 16,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 17,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 18,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 5.00,
                    "measurement_item_id" => 19,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 20,
                    "bill_receive_id" => 3
                ]
            ]
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/measurementConfigurations", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Item de Medição está inválido."
        ]);
    }

    #[Test]
    public function negTest_createMeasurementConfiguration_with_invalid_bill_receive_id(): void {
        $this->createContract();

        $payload = [
            "measurements" => [
                [
                    "incidence" => 3.78,
                    "measurement_item_id" => 1,
                    "bill_receive_id" => 10000
                ],
                [
                    "incidence" => 1.49,
                    "measurement_item_id" => 2,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 8.51,
                    "measurement_item_id" => 3,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 1.22,
                    "measurement_item_id" => 4,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 30.00,
                    "measurement_item_id" => 5,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 5.89,
                    "measurement_item_id" => 6,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 4.11,
                    "measurement_item_id" => 7,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 2.22,
                    "measurement_item_id" => 8,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 7.78,
                    "measurement_item_id" => 9,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 10,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 11,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 30.00,
                    "measurement_item_id" => 12,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 13,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 14,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 15,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 16,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 17,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 18,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 5.00,
                    "measurement_item_id" => 19,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 20,
                    "bill_receive_id" => 3
                ]
            ]
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/measurementConfigurations", $payload);

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Campo Conta a Receber está inválido."
        ]);
    }

    #[Test]
    public function posTest_createMeasurementConfiguration(): void {
        $this->createContract();

        $payload = [
            "measurements" => [
                [
                    "incidence" => 00.00,
                    "measurement_item_id" => 1,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 50.00,
                    "measurement_item_id" => 2,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 50.00,
                    "measurement_item_id" => 3,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 4,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 5,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 6,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 7,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 8,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 9,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 10,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 11,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 12,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 13,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 14,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 15,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 16,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 17,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 18,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 19,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 20,
                    "bill_receive_id" => 3
                ]
            ]
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/measurementConfigurations", $payload);

        $response->assertStatus(201);
    }

    #[Test]
    public function posTest_createMeasurementConfiguration_doubleValues(): void {
        $this->createContract();

        $payload = [
            "measurements" => [
                [
                    "incidence" => 3.78,
                    "measurement_item_id" => 1,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 1.49,
                    "measurement_item_id" => 2,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 8.51,
                    "measurement_item_id" => 3,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 1.22,
                    "measurement_item_id" => 4,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 30.00,
                    "measurement_item_id" => 5,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 5.89,
                    "measurement_item_id" => 6,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 4.11,
                    "measurement_item_id" => 7,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 2.22,
                    "measurement_item_id" => 8,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 7.78,
                    "measurement_item_id" => 9,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 10,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 11,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 30.00,
                    "measurement_item_id" => 12,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 13,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 14,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 15,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 16,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 17,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 18,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 5.00,
                    "measurement_item_id" => 19,
                    "bill_receive_id" => 3
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 20,
                    "bill_receive_id" => 3
                ]
            ]
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/measurementConfigurations", $payload);

        $response->assertStatus(201);
        $response->assertJsonCount(20);
        $response->assertJson($payload["measurements"]);
    }

}
