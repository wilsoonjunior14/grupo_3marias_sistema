<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;

use App\Models\Group;

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
        'created_at',
        'updated_at',
        'birthdate',
        'recovery_password_expiration',
        'recovery_password_token'
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
        'group_id' => 'required',
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
         'group_id.required' => 'Campo de grupo é obrigatório.'
    ];

    public function group() {
        return $this->hasOne(Group::class, "id", "group_id")->where("deleted", false);
    }

    public function search($data) {
        $searchQueries = [["deleted", "=", false]];

        if (isset($data["name"]) && !empty($data["name"])) {
            $searchQueries[] = ["name", "like", "%" . $data["name"] . "%"];
        }

        if (isset($data["email"]) && !empty($data["email"])) {
            $searchQueries[] = ["email", "like", "%" . $data["email"] . "%"];
        }

        return User::where($searchQueries)
        ->with("group")
        ->orderBy("name")
        ->get();
    }

    public function getUsersByPeriod($startDate, $endDate) {
        return User::whereRaw(
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
        return User::where("deleted", false)
        ->with("group")
        ->orderBy("name")
        ->get();
    }

    public function getUserLogin($email) {
        return User::where("deleted", false)
        ->where("email", "like", "%" . $email . "%")
        ->take(1)
        ->get();
    }

    public function getUserById($id) {
        return User::where("id", $id)
        ->with("group")
        ->get()
        ->first();
    }

    public function getUserByEmail($email) {
        return User::where("deleted", false)
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

        if (preg_match("/\@|\!|\#|\$|\%|\&|\*|\(|\)|\-|\+|\/|\\|\=|\'|\<|\>|\?|\,|\`|\~|\ã/i", $data["name"]) !== 1) {
            $errors = "Campo de nome está inválido com caracteres especiais (@!#$%^&*()-='/).";
        }

        // if (preg_match("/^(\(\d{2}\))\d{5}\-\d{4}$/i", $data["phoneNumber"]) !== 1){
        //     $errors = "Campo número de telefone está inválido. Ex: (xx)xxxxx-xxxx.";
        // }

        if (isset($data["active"]) && empty("active")) {
            $errors = "Campo de ativação do usuário não pode ser nulo";
        }

        if (!isset($data["conf_password"]) || empty($data["conf_password"])) {
            $errors = "Campo de confirmação de senha não informado.";
        }

        if (strcmp($data["password"], $data["conf_password"])) {
            $errors = "Senhas estão diferentes.";
        }

        $group = new Group();
        $groupInstance = $group->getGroupById($data["group_id"]);
        if ($groupInstance === null) {
            $errors = "Grupo informado não existe.";
        }

        return $errors;
    }
}
