<?php

namespace API\Service;

class RemoveFileService
{
    public static function removeFile($fileName)
    {
        if(file_exists($fileName)) {
            return unlink($fileName);
            
        }
        
        throw new \RangeException("The file {$fileName} doesn't exist");
    }
}
