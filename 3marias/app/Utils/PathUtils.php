<?php

namespace App\Utils;

class PathUtils
{
    public static function getPathByFolder(string $folder): string {
        return str_replace("/3marias/app/Utils", "/files/".$folder, __DIR__);
    }
}
