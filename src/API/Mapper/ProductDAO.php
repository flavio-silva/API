<?php

namespace API\Mapper;

use API\Entity\ProductInterface;
use Doctrine\ORM\EntityManager;

class ProductDAO implements ProductDAOInterface
{
    protected $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function insert(ProductInterface $product)
    {
        $this->em->persist($product);
        $this->em->flush();
    }
    
    public function update(array $data)
    {
        $entity = $this->em->getReference('API\Entity\Product', $data['id']);
        
        $entity->setName($data['name'])
                ->setDescription($data['description'])
                ->setValue($data['value']);
        
        $this->em->persist($entity);
        $this->em->flush();
    }
    
    public function findAll()
    {
        $repository = $this->em->getRepository('\API\Entity\Product');        
        return $repository->findAll();
    }

    public function findById($id)
    {
        return $this->em->find('API\Entity\Product', $id);
    }
    
    public function delete($id) 
    {
        $product = $this->em->getReference('API\Entity\Product', $id);
        $this->em->remove($product);
        $this->em->flush();
        return $product;
    }
}
