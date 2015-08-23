<?php

namespace API\Entity;

interface ProductInterface
{
        
    function setId($id);
    
    function getId();
    
    function setName($name);
    
    function getName();
    
    function setDescription($description);
    
    function getDescription();
    
    function setValue($value);
    
    function getValue();
    
    function toArray();
}
