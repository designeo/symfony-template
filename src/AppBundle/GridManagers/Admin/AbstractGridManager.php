<?php

namespace AppBundle\GridManagers\Admin;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;

/**
 * Class AbstractGridManager
 * @package AppBundle\Models\Admin
 * @author Ondřej Musil <ondrej.musil@designeo.cz>
 */
abstract class AbstractGridManager
{
    const PER_PAGE = 20;
    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';

    const EXISTS = 'exists';
    const NOT_EXISTS = 'not_exists';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * @var string
     */
    protected $sortBy;

    /**
     * @var string
     */
    protected $sortDir;

    /**
     * @var bool
     */
    protected $paginating = true;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;

        $this->page = 1;
        $this->perPage = self::PER_PAGE;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = intval($page);
    }

    /**
     * @return int
     */
    public function getPage()
    {
        if ($this->page <= 0) {
            return 1;
        }

        return $this->page;
    }

    /**
     * @param $perPage
     */
    public function setPerPage($perPage)
    {
        $this->perPage = intval($perPage);
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        if ($this->perPage <= 0) {
            return self::PER_PAGE;
        }

        return $this->perPage;
    }

    /**
     * @param null|int $count
     * @return int
     */
    public function getMaxPage($count = null)
    {
        if ($count === null) {
            $count = $this->getCount();
        }

        return intval(ceil($count / $this->getPerPage()));
    }

    /**
     * @return string
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * @return string
     */
    public function getSortDir()
    {
        return $this->sortDir;
    }

    /**
     * @return boolean
     */
    public function isPaginating() {
        return $this->paginating;
    }

    /**
     * @param boolean $paginating
     */
    public function setPaginating($paginating) {
        $this->paginating = $paginating;
    }

    /**
     * @param        $sortBy
     * @param string $sortDir
     */
    public function sortBy($sortBy, $sortDir = self::SORT_ASC)
    {
        $this->sortBy = $sortBy;
        $this->sortDir = $sortDir;
    }

    /**
     * @return string
     */
    public function getNextSortDir()
    {
        if ($this->getSortDir() == self::SORT_ASC) {
            return self::SORT_DESC;
        }
        return self::SORT_ASC;
    }

    /**
     * @param $from
     * @param $alias
     * @return QueryBuilder
     */
    protected function createBaseQueryBuilder($from, $alias)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select($alias)->from($from, $alias);

        if ($this->getSortBy()) {
            $qb->orderBy($this->getSortBy(), $this->getSortDir());
        }

        if ($this->isPaginating()) {
            $qb->setMaxResults($this->getPerPage())
                ->setFirstResult(($this->getPage() - 1) * $this->getPerPage());
        }

        return $qb;
    }

    /**
     * Add subquery as where condition as EXISTS(subquery) or NOT EXISTS(subquery) depending on selected mode
     *
     * @param QueryBuilder $qb
     * @param callable $queryCallback
     * @param string $mode self::EXISTS|self::NOT_EXISTS
     */
    protected function filter(QueryBuilder $qb, callable $queryCallback, $mode = self::EXISTS)
    {
        $subQueryBuilder = $this->em->createQueryBuilder();

        $queryCallback($subQueryBuilder);

        $subQuery = $qb->expr()->exists($subQueryBuilder->getDql());

        if ($mode == self::NOT_EXISTS) {
            $subQuery = $qb->expr()->not($subQuery);
        }

        /** @var Parameter $parameter */
        foreach ($subQueryBuilder->getParameters() as $parameter) {
            $qb->setParameter($parameter->getName(), $parameter->getValue());
        }

        $qb->andWhere($subQuery);
    }


    abstract public function getData();
    abstract public function getCount();
}