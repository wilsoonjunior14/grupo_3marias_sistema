<?php

namespace App\Http\Controllers;

use App\Exceptions\InputValidationException;
use App\Models\Feedback;
use App\Models\Logger;
use App\Utils\ResponseUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class FeedbackController extends Controller implements APIController
{
    private $feedbackInstance;
    private $validator;

    public function __construct() {
        $this->feedbackInstance = new Feedback();
        $this->validator = new ModelValidator(Feedback::$rules, Feedback::$rulesMessages);

        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o FeedbackController em {$startTime}.");
    }

    /**
     * Gets all feedbacks.
     */
    public function index() {
        Logger::info("Iniciando a recuperação de feedbacks.");

        $feedbacks = $this->feedbackInstance->getAll("created_at");
        $count = count($feedbacks);

        Logger::info("Recuperando {$count} feedbacks.");
        return ResponseUtils::getResponse($feedbacks, 200);
    }

    /**
     * Creates a new feedback.
     */
    public function store(Request $request) {
        Logger::info("Iniciando a criação de feedback.");

        Logger::info("Iniciando a validação dos dados informados.");
        $data = $request->all();
        $validation = $this->validator->validate($data);

        if ($validation !== null){
            throw new InputValidationException($validation);
        }

        Logger::info("Criando o feedback.");
        $feedback = new Feedback($data);
        $feedback->save();

        return ResponseUtils::getResponse($feedback, 201);
    }

    /**
     * Deletes a feedback.
     */
    public function destroy($id) {}

    /**
     * Updates a feedback.
     */
    public function update(Request $request, $id) {}

    /**
     * Gets a feedback by id.
     */
    public function show($id) {}

    /**
     * Creates a feedback.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
