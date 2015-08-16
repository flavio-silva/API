<?php

namespace API\Mapper;

use API\Entity\ProductInterface;

class ProductDAO implements ProductDAOInterface
{
    protected $pdo;
    
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    protected function insert(ProductInterface $product)
    {
        $query = "INSERT INTO product (name, description, value) "
                . "values (:name, :description, :value)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':name', $product->getName(), \PDO::PARAM_STR);
        $stmt->bindValue(':description', $product->getDescription(), \PDO::PARAM_STR);
        $stmt->bindValue(':value', $product->getValue());
        
        if($stmt->execute()) {
           
           $product->setId($this->pdo->lastInsertId());
           return $product; 
        }
        
        throw new \UnexpectedValueException;
    
    }
    
    protected function update(ProductInterface $product)
    {
        $query = "UPDATE product SET name = :name, description = :description, value =:value "
                . "WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':name', $product->getName(), \PDO::PARAM_STR);
        $stmt->bindValue(':description', $product->getDescription(), \PDO::PARAM_STR);
        $stmt->bindValue(':value', $product->getValue());
        $stmt->bindValue(':id', $product->getId(),\PDO::PARAM_INT);
        
        if($stmt->execute()) {
            return $product;
        }
        
        throw new \UnexpectedValueException;
    
    }
    
    public function save(ProductInterface $product)
    {
        if(is_null($product->getId())) {
            return $this->insert($product);
        }
        return $this->update($product);
    }

    public function findAll()
    {
        $query = 'SELECT * FROM product';
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
        $query = 'SELECT * FROM product WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject('API\Entity\Product');
    }
    
    public function delete($id) 
    {
        $query = 'DELETE FROM product WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
