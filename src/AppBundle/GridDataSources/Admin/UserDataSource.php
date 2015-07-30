<?php

namespace AppBundle\GridDataSources\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use FOS\UserBundle\Model\UserInterface;

/**
 * Class UserDataSource
 * @package AppBundle\GridDataSources\Admin
 */
class UserDataSource extends AbstractGridDataSource
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $role;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
        $this->sortBy('U.lastName', self::SORT_ASC);
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
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->filters['email'] = $email;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
        $this->filters['role'] = $role;
    }

    /**
     * @param integer $enabled
     */
    public function setEnabled($enabled)
    {
        if (is_numeric($enabled)) {
            $this->enabled = (bool) $enabled;
        }
        $this->filters['enabled'] = $enabled;
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        $qb = $this->createBaseQueryBuilder('AppBundle:User', 'U');

        if ($this->name) {
            $qb->andWhere('lower(U.firstName) LIKE lower(:name) OR lower(U.lastName) LIKE lower(:name)');
            $qb->setParameter('name', sprintf('%%%s%%', $this->name));
        }

        if ($this->email) {
            $qb->andWhere('lower(U.email) LIKE lower(:email)');
            $qb->setParameter('email', sprintf('%%%s%%', $this->email));
        }

        if ($this->role) {
            $qb->andWhere('lower(U.roles) LIKE lower(:role)');
            if ($this->role != UserInterface::ROLE_DEFAULT) {
                $qb->setParameter('role', sprintf('%%%s%%', $this->role));
            } else {
                $qb->setParameter('role', sprintf('%%%s%%', '{}'));
            }
        }

        if (!is_null($this->enabled)) {
            $qb->andWhere('U.enabled = :enabled');
            $qb->setParameter('enabled', $this->enabled);
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

        $qb->select('COUNT(U)');

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

