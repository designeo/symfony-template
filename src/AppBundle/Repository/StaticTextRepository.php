<?php

namespace AppBundle\Repository;

use AppBundle\Entity\StaticText;
use Doctrine\ORM\EntityRepository;

/**
 *
 */
class StaticTextRepository extends EntityRepository
{
    /**
     * @param string[] $names
     * @return StaticText[]
     */
    public function findManyByName($names)
    {
        $qb = $this->createQueryBuilder('ST');

        $qb
            ->andWhere('ST.name IN (:names)')
            ->setParameter('names', $names);

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
