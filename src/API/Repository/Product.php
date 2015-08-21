<?php

namespace API\Repository;

use Doctrine\ORM\EntityRepository;

class Product extends EntityRepository
{
    public function findByNameOrDescription($search, $firstResult = 0)
    {
        $qb = $this->createQueryBuilder('p');
        return $qb->where(
            $qb->expr()->orX(
                $qb->expr()->like('p.name', ':search'),
                $qb->expr()->like('p.description', ':search')
        ))
        ->setFirstResult($firstResult)
        ->setMaxResults(10)
        ->setParameter('search', "%{$search}%")
        ->getQuery()
        ->getResult();
    }
}
