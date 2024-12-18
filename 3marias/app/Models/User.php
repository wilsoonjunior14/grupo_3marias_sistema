<?php

namespace App\Models;

use App\Exceptions\InputValidationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;

use App\Models\Group;
use App\Utils\ErrorMessage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phoneNumber',
        'active',
        'deleted',
        'group_id',
        'birthdate'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    static $fieldsToBeUpdated = ["name", "email", "phoneNumber", "password", "group_id", "active", "birthdate"];

    static $rules = [
        'name' => 'bail|required|max:255|min:3',
        'email' => 'required|email:strict|max:100|min:3',
        'password' => 'required|min:3',
        'group_id' => 'required'
    ];

    static $rulesMessages = [
         'name.required' => 'Campo nome é obrigatório.',
         'name.max' => 'Campo nome permite no máximo 255 caracteres.',
         'name.min' => 'Campo nome deve conter no mínimo 3 caracteres.',
         'email.required' => 'Campo email é obrigatório.',
         'email.email' => 'Campo email está inválido.',
         'email.max' => 'Campo email permite no máximo 100 caracteres.',
         'email.min' => 'Campo email deve conter no mínimo 3 caracteres.',
         'password.required' => 'Campo senha é obrigatório.',
         'password.min' => 'Campo senha deve conter no mínimo 3 caracteres.',
         'group_id.required' => 'Campo Identificador de grupo é obrigatório.',
    ];

    public function group() {
        return $this->hasOne(Group::class, "id", "group_id")->select(["id", "description"])->where("deleted", false);
    }

    public function search($data) {
        $searchQueries = [["deleted", "=", false]];

        if (isset($data["name"]) && !empty($data["name"])) {
            $searchQueries[] = ["name", "like", "%" . $data["name"] . "%"];
        }

        if (isset($data["email"]) && !empty($data["email"])) {
            $searchQueries[] = ["email", "like", "%" . $data["email"] . "%"];
        }

        return (new User())->where($searchQueries)
        ->with("group")
        ->orderBy("name")
        ->get();
    }

    public function getUsersByPeriod($startDate, $endDate) {
        return (new User())->whereRaw(
            "(created_at >= ? AND created_at <= ?)", 
            [
                $startDate ." 00:00:00", 
                $endDate ." 23:59:59"
            ]
        )
        ->orderBy("created_at")
        ->get();
    }

    public function getUsers(){
        return (new User())->where("deleted", false)
        ->with("group")
        ->orderBy("name")
        ->get();
    }

    public function getUserLogin($email) {
        return (new User())->where("deleted", false)
        ->with("group")
        ->where("email", "like", "%" . $email . "%")
        ->take(1)
        ->get();
    }

    public function getUserById($id) {
        try{
            return (new User())->where("id", $id)
            ->with("group")
            ->get()
            ->firstOrFail();
        } catch (\Exception $e) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException($e->getMessage(), 400);
        }
    }

    public function getUserByEmail($email) {
        return (new User())->where("deleted", false)
        ->where("email", "like", "%" . $email . "%")
        ->get();
    }

    public static function validateUserData(array $data) {
        $errors = null;

        $validator = Validator::make($data, User::$rules, $messages = User::$rulesMessages);
        if ($validator->stopOnFirstFailure()->fails()){
            $errors = $validator->stopOnFirstFailure()->errors();
            return $errors->first();
        }

        // if (preg_match("/^[a-z A-Z áéíóúÁÉÍÓÚÂÊÎÔÛâêîôûãõÃÕ]+$/i", $data["name"]) !== 1) {
        //     return "Campo nome está inválido. Caracteres especiais não são suportados.";
        // }

        // if (preg_match("/^(\(\d{2}\))\d{5}\-\d{4}$/i", $data["phoneNumber"]) !== 1){
        //     $errors = "Campo número de telefone está inválido. Ex: (xx)xxxxx-xxxx.";
        // }

        if (isset($data["active"]) && empty("active")) {
            return "Campo de ativação do usuário não pode ser nulo";
        }

        if (isset($data["deleted"]) && $data["deleted"]) {
            return "Usuário não pode ser deletado por meio dessa operação.";
        }

        if (!isset($data["conf_password"]) || empty($data["conf_password"])) {
            return "Campo de confirmação de senha não informado.";
        }

        if (strcmp($data["password"], $data["conf_password"])) {
            return "Senhas estão diferentes.";
        }

        $group = new Group();
        try {
            $groupInstance = $group->getGroupById($data["group_id"]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "grupo"));
        }
        if ($groupInstance === null) {
            return "Grupo informado não existe.";
        }

        return $errors;
    }
}
