<?php

namespace API\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="API\Repository\Product")
 * @ORM\Table(name="product")
 */
class Product
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
     * @ORM\ManyToOne(targetEntity="API\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @ORM\ManyToMany(targetEntity="API\Entity\Tag")
     * @ORM\JoinTable(name="product_tag", 
    *       joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
    *       inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }
    
    /**
     * 
     * @return array
     */
    public function toArray()
    {
        $data = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'value' => $this->getValue(),
            'category' => $this->getCategory()
                ->getId()            
        ];
        
        foreach($this->getTags() as $tag) {
            $data['tags'][] = $tag->getId();
        }
        
        return $data;
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

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }
    
    public function getTags()
    {
        return $this->tags;
    }

    public function addTags($tags)
    {
        $this->tags->add($tags);
        return $this;
    }
    
}
