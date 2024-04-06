<?php

namespace App\Models;

use App\Models\Group;
use App\Models\Role;

class GroupRole extends BaseModel
{
    protected $table = "groups_roles";
    protected $fillable = ["id", "role_id", "group_id", "deleted", "created_at", "updated_at"];

    static $rules = [
        'role_id' => 'required',
        'group_id' => 'required'
    ];

    static $rulesMessages = [
        'role_id.required' => "Identificador da permissão é obrigatório.",
        'group_id.required' => "Identificador do grupo é obrigatório."
    ];

    public function role(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Role::class, "id", "role_id");
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Group::class, "id", "group_id");
    }

    public function getGroupsRolesByGroupId($groupId) {
        return GroupRole::where("group_id", $groupId)
        ->where("deleted", false)
        ->with("role")
        ->get();
    }

    /**
     * @param null|string $endpoint
     */
    public function getGroupRolesByGroupForRequest($groupId, string $requestMethod, string|null $endpoint) {
        return GroupRole::where("deleted", false)
        ->where("group_id", $groupId)
        ->with("role")
        ->whereHas("role", function($query) use ($requestMethod, $endpoint) {
            $query->where("request_type", "like", "%" . $requestMethod . "%")
            ->where("endpoint", "like", "%" . $endpoint . "%");
        })
        ->with("group")
        ->get();
    }

    public function getGroupRole(int $group_id, int $role_id) {
        return GroupRole::where("deleted", false)
        ->where("group_id", $group_id)
        ->where("role_id", $role_id)
        ->get();
    }
}
