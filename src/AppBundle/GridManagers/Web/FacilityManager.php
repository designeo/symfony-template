<?php

namespace AppBundle\GridManagers\Web;

use AppBundle\Entity\Cus;
use AppBundle\Entity\Facility;
use AppBundle\Enum\Entities;
use AppBundle\GridManagers\IS\AbstractGridManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;


class FacilityManager extends AbstractGridManager
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var int
     */
    protected $objectType;

    /**
     * @var int
     */
    protected $ownershipType;

    /**
     * @var string
     */
    protected $arealName;

    /**
     * @var int
     */
    protected $sport;

    /**
     * @var int
     */
    protected $sportFieldType;

    /**
     * @var int
     */
    protected $surfaceType;

    /**
     * @var int
     */
    protected $region;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $club;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
        $this->sortBy('F.name', self::SORT_ASC);
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @param $objectType
     */
    public function setObjectType($objectType)
    {
        $this->objectType = intval($objectType);
    }

    /**
     * @param $ownershipType
     */
    public function setOwnershipType($ownershipType)
    {
        $this->ownershipType = intval($ownershipType);
    }

    /**
     * @param $arealName
     */
    public function setArealName($arealName)
    {
        $this->arealName = $arealName;
    }

    /**
     * @param int $sport
     *
     * @return FacilityManager
     */
    public function setSport( $sport )
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * @return int
     */
    public function getSportFieldType() {
        return $this->sportFieldType;
    }

    /**
     * @param int $sportFieldType
     *
     * @return FacilityManager
     */
    public function setSportFieldType( $sportFieldType ) {
        $this->sportFieldType = $sportFieldType;

        return $this;
    }

    /**
     * @return int
     */
    public function getSurfaceType() {
        return $this->surfaceType;
    }

    /**
     * @param int $surfaceType
     * @return $this
     */
    public function setSurfaceType($surfaceType) {
        $this->surfaceType = intval($surfaceType);

        return $this;
    }

    /**
     * @return int
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * @param int $region
     * @return $this
     */
    public function setRegion($region) {
        $this->region = intval($region);

        return $this;
    }

    /**
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getClub() {
        return $this->club;
    }

    /**
     * @param string $club
     * @return $this
     */
    public function setClub($club) {
        $this->club = $club;

        return $this;
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        $qb = $this->createBaseQueryBuilder('AppBundle:Facility', 'F');

        $qb
            ->join('F.objectType', 'OT')
            ->join('F.ownershipType', 'OST');

        // Facility filters

        if ($this->name) {
            $qb
                ->andWhere('lower(F.name) LIKE lower(:name)')
                ->setParameter('name', sprintf('%%%s%%', $this->name));
        }

        if ($this->address) {
            $qb
                ->andWhere('
                    lower(F.street) LIKE lower(:address)
                    OR F.orientationNumber LIKE :address
                    OR F.houseNumber LIKE :address
                    OR lower(F.city) LIKE lower(:address)
                    OR lower(F.zip) LIKE lower(:address)
                ')
                ->setParameter('address', sprintf('%%%s%%', $this->address));
        }

        if ($this->objectType) {
            $qb
                ->andWhere('F.objectType = :objectType')
                ->setParameter('objectType', $this->objectType);
        }

        if ($this->ownershipType) {
            $qb
                ->andWhere('F.ownershipType = :ownershipType')
                ->setParameter('ownershipType', $this->ownershipType);
        }

        if ($this->arealName) {
            $qb
                ->andWhere('lower(F.arealName) LIKE lower(:arealName)')
                ->setParameter('arealName', sprintf('%%%s%%', $this->arealName));
        }

        if ($this->city) {
            $qb
                ->andWhere('lower(F.city) LIKE lower(:city)')
                ->setParameter('city', sprintf('%%%s%%', $this->city));
        }

        // Facility singular relation filters
        if ($this->region || $this->club) {
            $qb
                ->join('F.club', 'CL');

            if ($this->region) {
                $qb
                    ->join('CL.cus', 'CUS')
                    ->andWhere('CUS.id = :region')
                    ->setParameter('region', $this->region);
            }

            if ($this->club) {
                $qb
                    ->andWhere('lower(CL.name) LIKE lower(:club)')
                    ->setParameter('club', sprintf('%%%s%%', $this->club));
            }
        }

        // Facility multi relation filters

        $sport = $this->sport;
        $sportFieldType = $this->sportFieldType;
        $surfaceType = $this->surfaceType;

        if ($sport) {
            $this->filter(
                $qb,
                function (QueryBuilder $qb) use ($sport) {
                    $qb
                        ->select('S_F')
                        ->from(Entities::FACILITY, 'S_F')
                        ->leftJoin('S_F.facilityParts', 'S_FP')
                        ->leftJoin('S_FP.sportFields', 'S_SF')
                        ->leftjoin('S_SF.sports', 'S_S')
                        ->andWhere('S_S.id = :sport')
                        ->andWhere('S_F.id = F.id')
                        ->setParameter('sport', $sport);
                }
            );
        }

        if ($sportFieldType || $surfaceType) {
            $this->filter(
                $qb,
                function (QueryBuilder $qb) use ($sportFieldType, $surfaceType) {
                    $qb
                        ->select('SF_F')
                        ->from(Entities::FACILITY, 'SF_F')
                        ->leftJoin('SF_F.facilityParts', 'SF_FP')
                        ->leftJoin('SF_FP.sportFields', 'SF_SF')
                        ->andWhere('SF_F.id = F.id');

                    // SPORT FIELD TYPE
                    if ($sportFieldType) {
                        $qb
                            ->andWhere('SF_SF.sportFieldType = :sportFieldType')
                            ->setParameter('sportFieldType', $sportFieldType);
                    }

                    // SURFACE TYPE
                    if ($surfaceType) {
                        $qb
                            ->andWhere('SF_SF.surfaceType = :surfaceType')
                            ->setParameter('surfaceType', $surfaceType);
                    }
                }
            );
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

        $qb->select('COUNT(F)');

        return $qb;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $qb = $this->createQueryBuilder();
        $query = $qb->getQuery();
        $results = $query->getResult();

        /** @var Facility $result */
        foreach ($results as $result) {
            $result->prepareLazyCalculatedValues($this->em);
        }

        return $results;
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

    /*
     * Additional API
     */
    public function getFullMapData()
    {
        $this->setPaginating(false);
        $qb = $this->createQueryBuilder();

        $qb->select('F.lat', 'F.lng', 'F.name');

        $qb
            ->andWhere('F.lat IS NOT NULL')
            ->andWhere('F.lng IS NOT NULL');

        $query = $qb->getQuery();

        return $query->getResult();
    }
}