<?php

namespace AppBundle\GridManagers\Admin;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;


class UserManager extends AbstractGridManager
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
     * @var \Datetime
     */
    protected $lastLogin;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
        $this->sortBy('U.lastName', self::SORT_ASC);
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @param $enabled
     */
    public function setEnabled($enabled)
    {
        if (! is_null($enabled)) {
            $this->enabled = (bool) $enabled;
        }
    }

    /**
     * @param $lastLogin
     */
    public function setLastLogin($lastLogin)
    {
        if ($lastLogin) {
            try {
                $this->lastLogin = new \Datetime($lastLogin);
            } catch (\Exception $e) {
                return;
            }
        }
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        $qb = $this->createBaseQueryBuilder('AppBundle:User', 'U');

        if ($this->name) {
            $qb->andWhere('lower(U.firstName) LIKE lower(:name) OR lower(U.lastName) LIKE lower(:name)');
            $qb->setParameter('name', sprintf("%%%s%%", $this->name));
        }

        if ($this->email) {
            $qb->andWhere('lower(U.email) LIKE lower(:email)');
            $qb->setParameter('email', sprintf("%%%s%%", $this->email));
        }

        if (!is_null($this->enabled)) {
            $qb->andWhere('U.enabled = :enabled');
            $qb->setParameter('enabled', $this->enabled);
        }

        if ($this->lastLogin) {
            $qb->andWhere('U.lastLogin = :lastLogin');
            $qb->setParameter('lastLogin', $this->lastLogin);
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

