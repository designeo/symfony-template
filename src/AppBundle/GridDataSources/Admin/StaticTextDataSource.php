<?php

namespace AppBundle\GridDataSources\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class StaticTextDataSource
 * @package AppBundle\GridDataSources\Admin
 */
class StaticTextDataSource extends AbstractGridDataSource
{

    const FILTER_KEY_DESCRIPTION = 'description';

    const FILTER_KEY_NAME = 'name';

    const FILTER_KEY_TITLE = 'title';

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);

        $this->filters = [
            self::FILTER_KEY_DESCRIPTION => null,
            self::FILTER_KEY_NAME => null,
            self::FILTER_KEY_TITLE => null,
        ];
    }

    /**
     * Extract mandatory parameters from the Request object
     *
     * @param Request $request
     * @throws Exception
     */
    public function extractParameters(Request $request)
    {
        foreach ($this->filters as $key => $foo) {
            $value = $request->get($key);
            $methodName = sprintf('set%s', ucfirst($key));
            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            } else {
                throw new Exception(sprintf('Method %s::%s is missing.', get_class($this), $methodName));
            }

        }
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        if (!is_null($description)) {
            $this->filters[self::FILTER_KEY_DESCRIPTION] = $description;
        }
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        if (!is_null($name)) {
            $this->filters[self::FILTER_KEY_NAME] = $name;
        }
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        if (!is_null($title)) {
            $this->filters[self::FILTER_KEY_TITLE] = $title;
        }
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        $qb = $this->createBaseQueryBuilder('AppBundle:StaticText', 'S');

        if (!is_null($this->filters[self::FILTER_KEY_DESCRIPTION])) {
            $qb
                ->andWhere('LOWER(S.description) LIKE LOWER(:description)')
                ->setParameter('description', '%'.$this->filters[self::FILTER_KEY_DESCRIPTION].'%');
        }

        if (!is_null($this->filters[self::FILTER_KEY_NAME])) {
            $qb
                ->andWhere('LOWER(S.name) LIKE LOWER(:name)')
                ->setParameter('name', '%'.$this->filters[self::FILTER_KEY_NAME].'%');
        }

        if (!is_null($this->filters[self::FILTER_KEY_TITLE])) {
            $qb
                ->andWhere('LOWER(S.title) LIKE LOWER(:title)')
                ->setParameter('title', '%'.$this->filters[self::FILTER_KEY_TITLE].'%');
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

        $qb->select('COUNT(S)');

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

