<?php

namespace API\Service;

class UploadService
{        
    public static function upload($fileIndex)
    {
        if(array_key_exists($fileIndex, $_FILES)) {
            $path = __DIR__.'/../../../upload/';
            
            $fileName = $_FILES[$fileIndex]['name'];
            
            $fullPath = $path . $fileName;

            if(move_uploaded_file($_FILES[$fileIndex]['tmp_name'], $fullPath)) {
               return $fullPath; 
            }

            return false;
        }
        
        throw new \OutOfRangeException;
    
    }
}
