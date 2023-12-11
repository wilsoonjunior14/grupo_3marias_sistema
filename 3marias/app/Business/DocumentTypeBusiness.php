<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\DocumentType;
use App\Models\Logger;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class DocumentTypeBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de tipos de documentos.");
        $documentTypes = (new DocumentType())->getAll(orderBy: "name");
        $amount = count($documentTypes);
        Logger::info("Foram recuperados {$amount} tipos de documentos.");
        Logger::info("Finalizando a recuperação de tipos de documentos.");
        return $documentTypes;
    }

    public function getById(int $id) {
        Logger::info("Iniciando o tipo do documento $id.");
        $documentType = (new DocumentType())->getById($id);
        Logger::info("Finalizando o tipo do documento $id.");
        return $documentType;
    }

    public function delete(int $id) {
        $documentType = $this->getById(id: $id);
        Logger::info("Deletando o tipo de documento $id.");
        $documentType->deleted = true;
        $documentType->save();
        return $documentType;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação do tipo de documento.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $documentTypeValidator = new ModelValidator(DocumentType::$rules, DocumentType::$rulesMessages);
        $hasErrors = $documentTypeValidator->validate(data: $data);
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }
        
        Logger::info("Salvando a novo tipo de documento.");
        $documentType = new DocumentType($data);
        $documentType->save();
        Logger::info("Finalizando a atualização do tipo de documento.");
        return $documentType;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do tipo de documento.");
        $documentType = (new DocumentType())->getById($id);
        $documentTypeUpdated = UpdateUtils
            ::processFieldsToBeUpdated($documentType, $request->all(), DocumentType::$fieldsToBeUpdated);
        
        Logger::info("Validando as informações do tipo de documento.");
        $documentTypeValidator = new ModelValidator(DocumentType::$rules, DocumentType::$rulesMessages);
        $documentTypeValidator->validate(data: $request->all());

        Logger::info("Atualizando as informações do tipo de documento.");
        $documentTypeUpdated->save();
        return $this->getById(id: $documentTypeUpdated->id);
    }

}