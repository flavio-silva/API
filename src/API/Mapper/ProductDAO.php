<?php

namespace API\Mapper;

use API\Entity\Product as ProductEntity;

class ProductDAO implements DAOInterface
{
    protected $pdo;
    
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    protected function insert(ProductEntity $product)
    {
        $query = "INSERT INTO product (name, description, value) "
                . "values (:name, :description, :value)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':name', $product->getName(), \PDO::PARAM_STR);
        $stmt->bindValue(':description', $product->getDescription(), \PDO::PARAM_STR);
        $stmt->bindValue(':value', $product->getValue());
        
        if($stmt->execute()) {
           return $product; 
        }
        
        throw new \UnexpectedValueException;
    
    }
    
    protected function update(ProductEntity $product)
    {
        $query = "UPDATE product SET name = :name, description = :description, value =:value "
                . "WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':name', $product->getName(), \PDO::PARAM_STR);
        $stmt->bindValue(':descripton', $product->getDescription(), \PDO::PARAM_STR);
        $stmt->bindValue(':value', $product->getValue());
        $stmt->bindValue(':id', $product->getId());
        
        if($stmt->execute()) {
            return $product;
        }
        
        throw new \UnexpectedValueException;
    
    }
    
    public function save(ProductEntity $product)
    {
        if(is_null($product->getId())) {
            $this->insert($product);
        }
        $this->update($product);
    }

    public function findAll()
    {
        $query = 'SELECT name, description, value FROM product';
        $stmt = $this->pdo->query($query);
        
        
        $products = [];
        
        while($product = $stmt->fetchObject('API\Entity\Product'))
        {
        	$products[$product->getId()] = $product;
        }
        
        return $products;
        
    }

    public function findById($id)
    {
        $query = 'SELECT name, description, value FROM product WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject('API\Entity\Product');
    }
}
