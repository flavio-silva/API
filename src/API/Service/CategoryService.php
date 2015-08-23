<?php

namespace API\Service;

use Doctrine\ORM\EntityManager;
use API\Entity\Category;

class CategoryService implements CrudServiceInterface
{
    /**
     *
     * @var EntityManager
     */
    protected $em;
    protected $entity = 'API\Entity\Category';


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function delete($id)
    {
        $entity = $this->em->getReference($this->entity, $id);
        $this->em->remove($entity);
        $this->em->flush();
        
        return $entity;
    }

    public function findAll()
    {
        $repo = $this->em->getRepository($this->entity);
        return $repo->findAll();
    }

    public function findBy($id)
    {
        return $this->em->find($this->entity, $id);
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
        if(!array_key_exists('name', $data)) {
            throw new \BadMethodCallException('Key "name" was expected');
        }
        
        $entity = new Category();
        $entity->setName($data['name']);
        
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
    
    public function update(array $data)
    {
        if(!array_key_exists('id', $data)) {
            throw new \BadMethodCallException('Key "id" was expected');
        }
        
        if(!array_key_exists('name', $data)) {
            throw new \BadMethodCallException('Key "name" was expected');
        }
        
        $entity = $this->em->getReference($this->entity, $data['id']);        
        $entity->setName($data['name']);
        
        $this->em->persist($entity);
        $this->em->flush();
        
        return $entity;
        
    }

}
