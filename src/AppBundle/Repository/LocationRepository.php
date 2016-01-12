<?php
/**
 * User: Jiri Fajman
 * Date: 14.1.16
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Location;
use Doctrine\ORM\EntityRepository;

class LocationRepository extends EntityRepository
{

    /**
     * @return Location[]
     */
    public function findAllPackageFromLocations()
    {
        $qb = $this->createQueryBuilder('L');

        $qb
            ->join('L.packagesFromLocation', 'P')
            ->distinct()
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }

    /**
     * Find all target locations
     * @return Location[]
     */
    public function findAllPackageToLocations()
    {
        $qb = $this->createQueryBuilder('L');

        $qb
            ->join('L.packagesToLocation', 'P')
            ->distinct()
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function findAllRouteFromLocations()
    {
        $qb = $this->createQueryBuilder('L');

        $qb
            ->join('L.routesFromLocation', 'P')
            ->distinct()
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function findAllRouteToLocations()
    {
        $qb = $this->createQueryBuilder('L');

        $qb
            ->join('L.routesToLocation', 'P')
            ->distinct()
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }
}