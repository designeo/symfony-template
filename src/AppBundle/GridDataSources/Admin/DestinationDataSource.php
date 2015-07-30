<?php

namespace AppBundle\GridDataSources\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Destination GridDataSource
 * @package AppBundle\GridDataSources\Admin
 */
class DestinationDataSource extends AbstractGridDataSource
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
        $this->sortBy('T.name', self::SORT_ASC);
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->filters['name'] = $name;
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        $qb = $this->createBaseQueryBuilder('AppBundle:Destination', 'D')
          ->leftJoin('D.translations', 't');

        if ($this->name) {
            $qb->andWhere('lower(t.name) LIKE lower(:name)');
            $qb->setParameter('name', sprintf('%%%s%%', $this->name));
        }

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

