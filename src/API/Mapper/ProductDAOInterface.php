<?php

namespace API\Mapper;

interface ProductDAOInterface
{
    function findAll();

    function findById($id);
    
    function delete($id);
}
