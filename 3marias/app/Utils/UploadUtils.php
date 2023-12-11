<?php

namespace App\Utils;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use Illuminate\Http\Request;

class UploadUtils
{

    public static function uploadImage(Request $request, string $folder, string $filePath, string $field = "image") {
        Logger::info("Realizando upload para o local storage.");
        move_uploaded_file($request->file($field)->getRealPath(), $filePath);

        if (!(strcmp(env('APP_ENV'), "testing") === 0)) {
            Self::upload(folder: $folder, filePath: $filePath);
        }
    }

    private static function upload(string $folder, string $filePath) {
        Logger::info("Realizando upload para o bucket s3");
        $bucketName = "3marias-terraform-dev";
        $awsAccessId = "AKIAXAMRWEJY57CN5PUY";
        $awsSecretKey = "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";
        $output = shell_exec("AWS_ACCESS_KEY_ID=$awsAccessId AWS_SECRET_ACCESS_KEY=$awsSecretKey aws s3 mv $filePath s3://$bucketName/$folder/");
        if (!$output) {
            throw new InputValidationException("Não foi possível realizar o upload do arquivo. Error: "+$output);
        }
    }
}
