<?php

namespace API\Service;

interface CrudServiceInterface 
{
    function save(array $data);
    
    function findAll();
    
    function findOneById($id);
    
    function delete($id);
}
