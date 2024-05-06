<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\File;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UploadUtils;

class FileBusiness {

    public function createByClient(int $clientId, array $data) {
        Logger::info("Iniciando a criação de arquivos.");
        if (!isset($_FILES["file"]) || empty($_FILES["file"])) {
            throw new InputValidationException("Nenhum arquivo adicionado.");
        }
        
        $files = $_FILES["file"];
        $filesSaved = [];
        for ($i = 0; $i < count($files["name"]); $i++) {
            Logger::info("Salvando novo arquivo.");
            $name = $i . time() . ".pdf";
            UploadUtils::uploadFile(tmpName: $files["tmp_name"][$i], name: $name, awsFolder: "clients");
            $newFile = new File([
                "description" => $data["descriptions"][$i], 
                "filename" => $name,
                "client_id" => $clientId
            ]);
            $newFile->save();
            $filesSaved[] = $newFile;
        }
        return $filesSaved;
    }

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de documento $id.");
        try {
            $document = (new File())->getById($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Documento"));
        }
        Logger::info("Finalizando a recuperação do documento $id.");
        return $document;
    }

    public function deleteDocument(int $id) {
        Logger::info("Iniciando a deleção de documento $id.");
        $document = $this->getById(id: $id);
        $document->deleted = true;
        $document->save();
        Logger::info("Finalizando a deleção do documento $id.");
        return $document;
    }

    public function getByClient(int $clientId) {
        Logger::info("Iniciando a busca de arquivos do cliente $clientId.");
        return File::getByClientId(clientId: $clientId);
    }

}