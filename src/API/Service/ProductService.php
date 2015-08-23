<?php

namespace API\Service;

use API\Entity\Product as ProductEntity;

class ProductService extends AbstractCrudService
{
    
    protected function insert(array $data)
    {
        $product = new ProductEntity();
        
        $product->setName($data['name'])
            ->setDescription($data['description'])
            ->setValue($data['value']);        
        
        if(!array_key_exists('category', $data)) {
            throw new \BadMethodCallException('The key "category" was expected');
        }
        
        
        $category = $this->em->getReference('API\Entity\Category', $data['category']);
        
        $product->setCategory($category);
        
        $this->em->persist($product);
        $this->em->flush();
        
        return $product;
    }
    
    protected function update(array $data)
    {
        $product = $this->em->getReference('API\Entity\Product', $data['id']);
        $product->setName($data['name'])
            ->setDescription($data['description'])
            ->setValue($data['value']);
        
        if(!array_key_exists('category', $data)) {
            throw new \BadMethodCallException('The key "category" was expected');
        }
        
        $category = $this->em->getReference('API\Entity\Category', $data['category']);
        
        $product->setCategory($category);
        
        $this->em->persist($product);
        $this->em->flush();
        
        return $product;
    }
    
    public function findByNameOrDescription($search)
    {
        $repo = $this->em->getRepository('API\Entity\Product');
        return new \ArrayIterator($repo->findByNameOrDescription($search));
    }

}