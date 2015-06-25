<?php

namespace AppBundle\GridManagers\Web;

use AppBundle\Enum\Entities;
use AppBundle\GridManagers\IS\AbstractGridManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;


class AssociationManager extends AbstractGridManager
{

    /**
     * @var int
     */
    private $sport;

    /**
     * @var string
     */
    private $name;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
        $this->sortBy('A.name', self::SORT_ASC);
    }

    /**
     * @param int $sport
     */
    public function setSport($sport) {
        $this->sport = intval($sport);
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        $qb = $this->createBaseQueryBuilder(Entities::ASSOCIATION, 'A')
                    ->leftJoin('A.parent', 'PA')
                    ->where('A.inCus = TRUE OR PA.inCus = TRUE');

        $qb->andWhere('A.parent IS NULL');

        if($this->name){
            $qb->andWhere('LOWER(A.name) LIKE LOWER(:name)');
            $qb->setParameter('name', sprintf('%%%s%%', $this->name));
        }

        if($this->sport){
            $qb->join('A.sports', 'SP');
            $qb->andWhere('SP.id = :sport');
            $qb->setParameter('sport', $this->sport);
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

        $qb->select('COUNT(A)');

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