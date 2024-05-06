<?php

namespace App\Models;

use App\Models\GroupRole;

class Group extends BaseModel
{
    protected $table = "groups";
    protected $fillable = ["id", "description", "deleted", "created_at", "updated_at"];

    static $rules = [
        'description' => 'required|string|max:100|min:3'
    ];

    static $rulesMessages = [
         'description.required' => 'Campo descrição é obrigatório.',
         'description.string' => 'Campo descrição deve conter caracteres.',
         'description.max' => 'Campo descrição permite no máximo 100 caracteres.',
         'description.min' => 'Campo descrição deve conter no mínimo 3 caracteres.'
    ];

    public function roles(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(GroupRole::class, "group_id", "id")->where("deleted", false);
    }

    /**
     * Gets a group with roles based on group id.
     */
    public function getGroupById($id) {
        try{
            return (new Group())->where("id", $id)
            ->with("roles.role")
            ->get()
            ->firstOrFail(); 
        } catch (\Exception $e) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException($e->getMessage(), 400);
        }
    }

    public function getGroupByName(string $groupName){
        return (new Group())->where("description", "like", '%' . $groupName . '%')
               ->where("deleted", false)
               ->get();
    }
}
