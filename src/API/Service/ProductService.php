<?php

namespace API\Service;

use API\Entity\Product as ProductEntity;
use Doctrine\ORM\EntityManager;

class ProductService implements CrudServiceInterface
{
    
    protected $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function save(array $data)
    {
        if(array_key_exists('id', $data)) {
            return $this->update($data);
        }        
        
        return $this->insert($data);
    }

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
    public function findAll()
    {
        $repo = $this->em->getRepository('API\Entity\Product');
        return new \ArrayIterator($repo->findAll());
    }
    
    public function delete($id) 
    {
        $product = $this->em->getReference('API\Entity\Product', $id);        
        $this->em->remove($product);
        $this->em->flush();
        return $product;
    }

    public function findBy($id) 
    {
        $repo = $this->em->getRepository('API\Entity\Product');
        return $repo->find($id);
    }
    
    public function findByNameOrDescription($search)
    {
        $repo = $this->em->getRepository('API\Entity\Product');
        return new \ArrayIterator($repo->findByNameOrDescription($search));
    }

}