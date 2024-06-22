<?php

namespace App\Models;

class Measurement extends BaseModel
{
    protected $table = "measurements";
    protected $fillable = ["id", "number", "incidence", "measurement_item_id", "bill_receive_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["incidence", "measurement_item_id"];

    static $rules = [
        'number' => 'required|integer|gt:0',
        'incidence' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'measurement_item_id' => 'required|integer|gt:0|exists:measurement_items,id',
        'bill_receive_id' => 'required|integer|gt:0|exists:bill_receives,id'
    ];

    static $rulesMessages = [
        'number.required' => 'Campo Número da Medição é obrigatório.',
        'number.integer' => 'Campo Número da Medição está inválido.',
        'number.gt' => 'Campo Número da Medição está inválido.',
        'incidence.required' => 'Campo Incidência é obrigatório.',
        'incidence.regex' => 'Campo Incidência está inválido.',
        'measurement_item_id.required' => 'Campo Item de Medição é obrigatório.',
        'measurement_item_id.integer' => 'Campo Item de Medição está inválido.',
        'measurement_item_id.gt' => 'Campo Item de Medição está inválido.',
        'measurement_item_id.exists' => 'Campo Item de Medição está inválido.',
        'bill_receive_id.required' => 'Campo Conta a Receber é obrigatório.',
        'bill_receive_id.integer' => 'Campo Conta a Receber está inválido.',
        'bill_receive_id.gt' => 'Campo Conta a Receber está inválido.',
        'bill_receive_id.exists' => 'Campo Conta a Receber está inválido.'
    ];

    public function measurement_item() {
        return $this->hasOne(MeasurementItem::class, "id", "measurement_item_id")->where("deleted", false);
    }

    public function getByBillReceiveId(int $billReceiveId) {
        return (new Measurement())::where("deleted", false)
        ->where("bill_receive_id", $billReceiveId)
        ->with("measurement_item")
        ->orderBy("number")
        ->get();
    }

    public function getByMeasurementNumber(int $number, int $billReceiveId) {
        return (new Measurement())::where("deleted", false)
        ->where("bill_receive_id", $billReceiveId)
        ->where("number", $number)
        ->with("measurement_item")
        ->orderBy("number")
        ->get();
    }
}
