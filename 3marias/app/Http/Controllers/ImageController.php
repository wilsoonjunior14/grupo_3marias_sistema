<?php

namespace App\Http\Controllers;

use App\Exceptions\EntityNotFoundException;
use App\Utils\PathUtils;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function getImage(Request $request, $folder, $filename): void {
        $filename = PathUtils::getPathByFolder($folder) . "/" . $filename; 

        if (!file_exists($filename)) {
            throw new EntityNotFoundException("Imagem não encontrada.");
        }

        $handle = fopen($filename, "rb"); 
        $contents = fread($handle, filesize($filename)); 
        fclose($handle); 
        
        header("content-type: image/png"); 
        echo $contents;
    }
}
