<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\EnterpriseFile;
use App\Models\Logger;
use App\Utils\PathUtils;
use App\Utils\ErrorMessage;
use App\Utils\UploadUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class EnterpriseFileBusiness {

    public function get(int $enterpriseId) {
        Logger::info("Iniciando a recuperação de arquivos.");
        $entepriseFiles = (new EnterpriseFile())->getByEnterprise(enterpriseId: $enterpriseId);
        $amount = count($entepriseFiles);
        Logger::info("Foram recuperados {$amount} arquivos.");
        Logger::info("Finalizando a recuperação de arquivos.");
        return $entepriseFiles;
    }

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de arquivo $id.");
        $entepriseFile = (new EnterpriseFile())->getById($id);
        Logger::info("Finalizando a recuperação de arquivo $id.");
        return $entepriseFile;
    }

    public function delete(int $id) {
        $entepriseFile = $this->getById(id: $id);
        Logger::info("Deletando o arquivo $id.");
        $entepriseFile->deleted = true;
        $entepriseFile->save();
        return $entepriseFile;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de arquivo.");
        Logger::info("Validando as informações fornecidas.");
        if (!$request->hasFile("file")) {
            throw new InputValidationException("Nenhum documento foi anexado.");
        }
        $data = $request->all();
        if (!isset($data["enterprise_id"]) || empty($data["enterprise_id"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_PROVIDED, "empresa"));
        }

        $file = $request->file("file");
        $fileData = [
            "name" => $file->getClientOriginalName(),
            "description" => $file->getClientOriginalName(),
            "url" => "https://3marias-terraform-dev.s3.amazonaws.com/enterprise/",
            "enterprise_id" => $data["enterprise_id"]
        ];

        $entepriseFileValidator = new ModelValidator(EnterpriseFile::$rules, EnterpriseFile::$rulesMessages);
        $hasErrors = $entepriseFileValidator->validate(data: $fileData);
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }

        $filename = time() . ".pdf";
        $filePath = PathUtils::getPathByFolder("enterprise") . "/" . $filename;
        UploadUtils::uploadImage(request: $request, field: "file",
            folder: "enterprise", filePath: $filePath);
        
        Logger::info("Salvando a nova arquivo.");
        $entepriseFile = new EnterpriseFile($fileData);
        $entepriseFile->url = $entepriseFile->url . $filename;
        $entepriseFile->save();
        Logger::info("Finalizando a atualização de arquivo.");
        return $fileData;
    }
}