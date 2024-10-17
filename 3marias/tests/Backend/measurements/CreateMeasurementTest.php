<?php

namespace Tests\Backend\measurements;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestFramework;
use PHPUnit\Framework\Attributes\Test;

/**
 * This suite tests the POST /api/v1/measurements
 */
class CreateMeasurementTest extends TestFramework
{

    use RefreshDatabase;
    use CreatesApplication;

    public function setUp() : void {
        parent::setUp();
        parent::refreshToken();

        $this->createMeasurementItems();
    }

    protected function tearDown(): void {
        parent::tearDown();
    }

    #[Test]
    public function posTest_createMeasurement_doubleValues(): void {
        $this->createMeasurementConfiguration();

        $payload = [
            "measurements" => [
                [
                    "incidence" => 1.00,
                    "measurement_item_id" => 1,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 1.00,
                    "measurement_item_id" => 2,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 1.00,
                    "measurement_item_id" => 3,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 1.00,
                    "measurement_item_id" => 4,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 1.00,
                    "measurement_item_id" => 5,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 1.00,
                    "measurement_item_id" => 6,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 1.00,
                    "measurement_item_id" => 7,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 1.00,
                    "measurement_item_id" => 8,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 1.00,
                    "measurement_item_id" => 9,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 10,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 11,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 1.00,
                    "measurement_item_id" => 12,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 13,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 14,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 15,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 16,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 17,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 18,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 1.00,
                    "measurement_item_id" => 19,
                    "bill_receive_id" => 3,
                    "number" => 1
                ],
                [
                    "incidence" => 0.00,
                    "measurement_item_id" => 20,
                    "bill_receive_id" => 3,
                    "number" => 1
                ]
            ]
        ];

        $response = $this
        ->withHeaders($this->getHeaders())
        ->post("/api/v1/measurements", $payload);

        $response->assertStatus(201);
        $response->assertJsonCount(20);
    }

    private function createMeasurementConfiguration() : void {
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
