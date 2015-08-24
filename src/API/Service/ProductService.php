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
        
        if(!array_key_exists('tags', $data)) {
            throw new \BadMethodCallException('The key "tag was expected');
        }
        
        if(!is_array($data['tags'])) {
            throw new \InvalidArgumentException('Array was expected');
        }
        
        foreach($data['tags'] as $tag) {
            $tagEntity = $this->em->getReference('API\Entity\Tag', $tag);
            $product->addTags($tagEntity);
        }
        
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
        
        if(!array_key_exists('tags', $data)) {
            throw new \BadMethodCallException('The key "tag was expected');
        }
        
        if(!is_array($data['tags'])) {
            throw new \InvalidArgumentException('Array was expected');
        }        
        
        $conn = $this->em->getConnection();
        
        $stmt = $conn->prepare('delete from product_tag where product_id = ?');
        $stmt->bindValue(1, $product->getId());
        $stmt->execute();
        
        foreach($data['tags'] as $tag) {
            $tagEntity = $this->em->getReference('API\Entity\Tag', $tag);
            $product->addTags($tagEntity);
        }
        
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