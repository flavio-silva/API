<?php

namespace API\Mapper;

use API\Entity\EntityInterface;

interface DAOInterface
{
    function save(EntityInterface $entity);

    function findAll();

    function findById($id);
}
