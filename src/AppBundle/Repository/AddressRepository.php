<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AddressRepository extends EntityRepository
{
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.firstname', 'ASC')
            ->getQuery()->getResult();
    }
}
