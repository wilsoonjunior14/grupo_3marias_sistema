<?php

namespace App\Models;

class MeasurementConfiguration extends BaseModel
{
    protected $table = "measurement_configurations";
    protected $fillable = ["id", "incidence", "measurement_item_id", "bill_receive_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["incidence", "measurement_item_id"];

    static $rules = [
        'incidence' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'measurement_item_id' => 'required|integer|gt:0|exists:measurement_items,id',
        'bill_receive_id' => 'required|integer|gt:0|exists:bill_receives,id'
    ];

    static $rulesMessages = [
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

    public function getByBillReceiveId(int $billReceiveId) {
        return (new MeasurementConfiguration())::where("deleted", false)
        ->where("bill_receive_id", $billReceiveId)
        ->orderBy("id")
        ->get();
    }
}
