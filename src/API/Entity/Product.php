<?php

namespace API\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */

class Product implements ProductInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * @var string
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string 
     */
    private $description;
    /**
     * @ORM\Column(type="decimal", nullable=false, precision=11, scale=2)
     * @var string 
     */
    private $value;
    
    /**
     * 
     * @return array
     */
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
