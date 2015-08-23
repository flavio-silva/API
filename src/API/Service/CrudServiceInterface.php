<?php

namespace API\Service;

interface CrudServiceInterface 
{
    function save(array $data);
    
    function findAll();
    
    function findBy($id);
    
    function delete($id);
}
