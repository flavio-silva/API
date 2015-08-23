<?php

namespace API\Service;

use Doctrine\ORM\EntityManager;
use API\Hydrator;
use Doctrine\ORM\AbstractQuery;

class AbstractCrudService implements CrudServiceInterface
{
    
    /**
     * @var EntityManager
     */
    
    protected $em;
    
    /**
     * @var string
     */
    protected $entity;
    
    public function __construct(EntityManager $em, $entity)
    {
        $this->em = $em;
        $this->entity = $entity;
    }
    
    public function delete($id)
    {
        $product = $this->em->getReference($this->entity, $id);
        $this->em->remove($product);
        $this->em->flush();
        return $product;
    }

    public function findAll()
    {
        $repo = $this->em->getRepository($this->entity);        
        return $repo->findAll();
    }

    public function findOneById($id)
    {
        $repo = $this->em->getRepository($this->entity);
        return $repo->find($id);
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
        $entity = new $this->entity;        
        $entity = Hydrator::configure($entity, $data);        
        
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
    
    protected function update(array $data)
    {        
        $entity = $this->em->getReference($this->entity, $data['id']);
        $entity = Hydrator::configure($entity, $data);
        
        $this->em->persist($entity);
        $this->em->flush();
        
        return $entity;
        
    }

}
