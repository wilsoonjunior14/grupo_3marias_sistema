<?php

namespace App\Models;

class LinkedSystem extends BaseModel
{
    protected $table = "linked_systems";
    protected $fillable = ["id", "name", "value", "enterprise_id", "deleted", "created_at", "updated_at"];

    static $rules = [
        'name' => 'required|string|in:facebook,instagram,other',
        'value' => 'required|string|max:500|min:3'
    ];

    static $rulesMessages = [
         'name.required' => 'Campo nome do sistema linkado é obrigatório.',
         'name.string' => 'Campo nome do sistema linkado deve conter caracteres.',
         'name.in' => 'Campo nome do sistema linkado contém um valor inválido.',
         'value.required' => 'Campo url do sistema linkado é obrigatório.',
         'value.string' => 'Campo url do sistema linkado deve conter caracteres.',
         'value.max' => 'Campo url do sistema linkado permite no máximo 500 caracteres.',
         'value.min' => 'Campo url do sistema linkado deve conter no mínimo 3 caracteres.'
    ];

    /**
     * Deletes all linked systems of the enterprise.
     */
    public function deleteAllByEnterprise(int $enterpriseId) {
        $linkedSystems = $this::where("deleted", false)
        ->where("enterprise_id", $enterpriseId)
        ->get();

        foreach ($linkedSystems as $linkedSystem) {
            $linkedSystem->deleted = true;
            $linkedSystem->save();
        }
    }

    /**
     * Creates linked systems.
     */
    public function saveLinkedSystems(array $linkedSystems, int $enterpriseId) {
        if (!isset($linkedSystems) || empty($linkedSystems)) {
            return;
        }

        foreach ($linkedSystems as $item) {
            $linkedSystem = new LinkedSystem($item);
            $linkedSystem->enterprise_id = $enterpriseId;
            $linkedSystem->save();
        }
    }
}
