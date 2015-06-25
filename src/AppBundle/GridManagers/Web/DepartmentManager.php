<?php

namespace AppBundle\GridManagers\Web;

use AppBundle\Enum\Entities;
use AppBundle\GridManagers\IS\AbstractGridManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;


class DepartmentManager extends AbstractGridManager
{

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
        $this->sortBy('D.name', self::SORT_ASC);
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select([
            'D'
        ]);

        $qb
            ->from(Entities::DEPARTMENT, 'D');

        return $qb;
    }

    /**
     * @return QueryBuilder
     */
    protected function getCountQueryBuilder()
    {
        $qb = $this->createQueryBuilder();

        $qb->resetDQLParts(array(
            'select',
            'orderBy'
        ));

        $qb->setMaxResults(null);
        $qb->setFirstResult(null);

        $qb->select('COUNT(D)');

        return $qb;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $qb = $this->createQueryBuilder();
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * @return int
     */
    public function getCount()
    {
        $qb = $this->getCountQueryBuilder();
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }
}