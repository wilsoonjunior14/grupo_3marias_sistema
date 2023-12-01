<?php

namespace App\Http\Controllers;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\Rating;
use App\Utils\ResponseUtils;
use App\Validation\RatingValidator;
use Illuminate\Http\Request;

class RatingController extends Controller implements APIController
{
    private $ratingInstance;
    private $validator;

    public function __construct() {
        $this->ratingInstance = new Rating();
        $this->validator = new RatingValidator(Rating::$rules, Rating::$rulesMessages);

        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o RatingController em {$startTime}.");
    }

    /**
     * Creates a new rating.
     */
    public function store(Request $request) {
        Logger::info("Validando os dados informados.");

        $data = $request->all();
        $validation = $this->validator->validate($data);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }

        Logger::info("Salvando a avaliação.");
        $rating = new Rating($data);
        $rating->save();

        Logger::info("Encerrando processo de criação de avaliação.");
        return ResponseUtils::getResponse($rating, 201);
    }

    /**
     * Gets all ratings.
     */
    public function index() {}

    /**
     * Gets rating by id.
     */
    public function show($id) {}

    /**
     * Deletes a rating.
     */
    public function destroy($id) {}

    /**
     * Updates a rating.
     */
    public function update(Request $request, $id) {}

    /**
     * Creates a rating.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
