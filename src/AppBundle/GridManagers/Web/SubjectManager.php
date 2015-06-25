<?php

namespace AppBundle\GridManagers\Web;

use AppBundle\Entity\Club;
use AppBundle\Enum\Entities;
use AppBundle\GridManagers\IS\AbstractGridManager;
use AppBundle\Repository\MemberRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;


class SubjectManager extends AbstractGridManager
{
    const CLUB_TYPE_CUS = -1;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $ic;

    /**
     * @var int
     */
    protected $cus;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var int
     */
    protected $region;

    /**
     * @var int
     */
    protected $sport;

    /**
     * @var int
     */
    protected $association;

    /**
     * @var int
     */
    protected $role;

    /**
     * @var int
     */
    protected $ageFrom;

    /**
     * @var int
     */
    protected $ageTo;

    /**
     * @var
     */
    protected $sex;

    /**
     * @var bool
     */
    protected $isCoach;

    /**
     * @var bool
     */
    protected $isReferee;

    /**
     * @var int
     */
    protected $count = null;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
        $this->sortBy('CLUB.name', self::SORT_ASC);
    }

    /**
     * @param mixed $region
     *
     * @return SubjectManager
     */
    public function setRegion( $region ) {
        $this->region = $region;

        return $this;
    }

    /**
     * @param mixed $sport
     *
     * @return SubjectManager
     */
    public function setSport( $sport ) {
        $this->sport = $sport;

        return $this;
    }

    /**
     * @param mixed $association
     */
    public function setAssociation($association) {
        $this->association = $association;
    }


    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $ic
     */
    public function setIc($ic)
    {
        $this->ic = $ic;
    }

    /**
     * @param $cus
     */
    public function setCus($cus)
    {
        $this->cus = intval($cus);
    }

    /**
     * @param string $city
     *
     * @return SubjectManager
     */
    public function setCity( $city ) {
        $this->city = $city;

        return $this;
    }

    /**
     * @param mixed $role
     *
     * @return SubjectManager
     */
    public function setRole( $role ) {
        $this->role = $role;

        return $this;
    }

    /**
     * @param mixed $ageFrom
     *
     * @return SubjectManager
     */
    public function setAgeFrom( $ageFrom ) {
        $this->ageFrom = $ageFrom;

        return $this;
    }

    /**
     * @param mixed $ageTo
     *
     * @return SubjectManager
     */
    public function setAgeTo( $ageTo ) {
        $this->ageTo = $ageTo;

        return $this;
    }

    /**
     * @param mixed $sex
     *
     * @return SubjectManager
     */
    public function setSex( $sex ) {
        $this->sex = $sex;

        return $this;
    }

    /**
     * @param boolean $isCoach
     *
     * @return SubjectManager
     */
    public function setIsCoach( $isCoach ) {
        $this->isCoach = $isCoach;

        return $this;
    }

    /**
     * @param boolean $isReferee
     *
     * @return SubjectManager
     */
    public function setIsReferee( $isReferee ) {
        $this->isReferee = $isReferee;

        return $this;
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('CLUB');

        $sport = $this->sport;

        $qb->from(Entities::CLUB, 'CLUB')
            ->leftJoin('CLUB.clubType', 'CT')
            ->leftJoin('CLUB.cus', 'CUS');

        $qb->andWhere('CLUB.clubType IS NULL');

        if ($this->getSortBy()) {
            $qb->orderBy($this->getSortBy(), $this->getSortDir());
        }

        $qb->setMaxResults($this->getPerPage())
           ->setFirstResult(($this->getPage() - 1) * $this->getPerPage());

        if ($this->name) {
            $qb->andWhere('lower(CLUB.name) LIKE lower(:name)');
            $qb->setParameter('name', sprintf("%%%s%%", $this->name));
        }

        if ($this->city) {
            $qb->andWhere('lower(CLUB.city) LIKE lower(:city)');
            $qb->setParameter('city', sprintf("%%%s%%", $this->city));
        }

        if ($this->ic) {
            $qb->andWhere('CLUB.ic LIKE :ic');
            $qb->setParameter('ic', sprintf("%%%s%%", $this->ic));
        }

        if ($this->region) {
            $qb
                ->andWhere('CUS.id = :region')
                ->setParameter('region', $this->region);
        }

        if ($this->association) {
            $qb
                ->leftJoin('CUS.associations', 'ASSOC')
                ->andWhere('ASSOC.id = :assoc')
                ->setParameter('assoc', $this->association);
        }

        if ($sport) {
            $this->filter(
                $qb,
                function(QueryBuilder $qb) use ($sport) {
                    $qb
                        ->select('S_CLUB.id')
                        ->from(Entities::CLUB, 'S_CLUB')
                        ->leftJoin('S_CLUB.departments', 'S_DEP')
                        ->andWhere('S_DEP.sport = :sport')
                        ->andWhere('S_CLUB.id = CLUB.id')
                        ->setParameter('sport', $sport);
                }
            );
        }

        return $qb;
    }

    /**
     * @return int
     */
    protected function getCountQueryResult()
    {
        return $this->filteredScalarQuery(function(QueryBuilder $qb){
            $qb->select('COUNT(CLUB)');
        });
    }

    /**
     * @return array
     */
    public function getData()
    {
        $qb = $this->createQueryBuilder();

        $results = $qb->getQuery()->getResult();

        /** @var Club $result */
        foreach ($results as $result) {
            $result->prepareLazyCalculatedValues(
                $this->em,
                $this->exportMemberFilter()
            );
        }

        return $results;
    }

    /**
     * @return array
     */
    protected function exportMemberFilter()
    {
        return [
            'role' => $this->role,
            'age_from' => $this->ageFrom,
            'age_to' => $this->ageTo,
            'sex' => $this->sex,
            'is_coach' => $this->isCoach,
            'is_referee' => $this->isReferee,
        ];
    }

    /**
     * @return int
     */
    public function getCount()
    {
        if (is_null($this->count)) {
            $this->count = $this
                ->getCountQueryResult();
        }

        return $this->count;
    }

    /*
    * Additional API
    */
    public function getFullMapData()
    {
        $this->setPaginating(false);
        $qb = $this->createQueryBuilder();

        $qb->select('CLUB.lat', 'CLUB.lng', 'CLUB.name');

        $qb
            ->andWhere('CLUB.lat IS NOT NULL')
            ->andWhere('CLUB.lng IS NOT NULL');

        $query = $qb->getQuery();

        return $query->getResult();
    }

    /**
     * @return int
     */
    public function getMembersCount()
    {
        /** @var MemberRepository $memberRepo */
        $memberRepo = $this->em->getRepository(Entities::MEMBER);

        return $memberRepo->getUniqueCountByFilter(
            $this->exportMemberFilter(),
            [
                'name' => $this->name,
                'city' => $this->city,
                'ic' => $this->ic,
                'region' => $this->region,
                'association' => $this->association,
                'sport' => $this->sport
            ]
        );
    }

    /**
     * @return int
     */
    public function getDepartmentsCount() 
    {
        return $this->filteredScalarQuery(function (QueryBuilder $qb) {

            $qb
                ->leftJoin('CLUB.departments','X_DEP');

            if ($this->sport) {
                $qb
                    ->andWhere('X_DEP.sport = :sport')
                    ->setParameter('sport', $this->sport);
            }

            return $qb->select('COUNT(X_DEP)');
        });
    }

    /**
     * @return int
     */
    public function getSubjectsCount()
    {
        return $this->getCount();
    }

    /**
     * @return QueryBuilder
     */
    protected function getRawFilterQuery()
    {
        $qb = $this->createQueryBuilder();

        $qb->resetDQLParts(array(
            'select',
            'orderBy'
        ));

        $qb->setMaxResults(null);
        $qb->setFirstResult(null);

        return $qb;
    }

    /**
     * @param callable $scalarQuery
     *
     * @return mixed
     */
    protected function filteredScalarQuery(callable $scalarQuery)
    {
        $qb = $this->getRawFilterQuery();

        $scalarQuery($qb);

        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }
}