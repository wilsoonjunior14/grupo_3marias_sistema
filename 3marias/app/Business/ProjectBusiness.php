<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Project;
use App\Models\Logger;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use App\Validation\projectValidator;
use Illuminate\Http\Request;

class ProjectBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de projetos.");
        $projects = (new Project())->getAll("name");
        $amount = count($projects);
        Logger::info("Foram recuperados {$amount} projetos.");
        Logger::info("Finalizando a recuperação de projetos.");
        return $projects;
    }

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de projeto $id.");
        $project = (new Project())->getById($id);
        Logger::info("Finalizando a recuperação de projeto $id.");
        return $project;
    }

    public function delete(int $id) {
        $project = $this->getById(id: $id);
        Logger::info("Deletando o de projeto $id.");
        $project->deleted = true;
        $project->save();
        return $project;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de projeto.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $projectValidator = new ModelValidator(Project::$rules, Project::$rulesMessages);
        $validation = $projectValidator->validate($data);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }
        
        Logger::info("Salvando a nova projeto.");
        $project = new project($data);
        $project->save();
        Logger::info("Finalizando a atualização de projeto.");
        return $project;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do projeto.");
        $project = (new Project())->getById($id);
        $projectUpdated = UpdateUtils::processFieldsToBeUpdated($project, $request->all(), Project::$fieldsToBeUpdated);
        
        Logger::info("Validando as informações do projeto.");
        $projectValidator = new ModelValidator(Project::$rules, Project::$rulesMessages);
        $validation = $projectValidator->validate($projectUpdated);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }

        Logger::info("Atualizando as informações do projeto.");
        $projectUpdated->save();
        return $this->getById(id: $projectUpdated->id);
    }

}