<?php

namespace API\Mapper;

use API\Entity\ProductInterface;

interface ProductDAOInterface
{
    function save(ProductInterface $entity);

    function findAll();

    function findById($id);
    
    function delete($id);
}
