<?php

namespace API\Entity;

class Product implements ProductInterface
{
    private $id;
    private $name;
    private $description;
    private $value;
    
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'value' => $this->getValue()
        ];
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setValue($value)
    {   
        $numberFormatter = new \NumberFormatter('pt_BR', \NumberFormatter::DECIMAL);
        $this->value = $numberFormatter->parse($value);
        return $this;
    }
}
