<?php

namespace AppBundle\Model;

use AppBundle\Entity\Location;
use AppBundle\Repository\LocationRepository;
use Designeo\FrameworkBundle\Model\AbstractLoggerAwareModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DBALException;
use AppBundle\Exception\LocationException;

/**
 * Model for Location entity
 * @package AppBundle\Model
 */
class LocationModel extends AbstractLoggerAwareModel
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LocationRepository
     */
    private $locationRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param LocationRepository     $locationRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LocationRepository $locationRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->locationRepository = $locationRepository;
    }

    /**
     * @param Location $location
     * @throws \AppBundle\Exception\LocationException
     */
    public function persist(Location $location)
    {
        $this->save($location);
    }

    /**
     * @param Location $location
     * @throws \AppBundle\Exception\LocationException
     */
    public function update(Location $location)
    {
        $this->save($location);
    }

    /**
     * @param Location $location
     * @throws \AppBundle\Exception\LocationException
     */
    public function remove(Location $location)
    {
        try {
            $this->entityManager->remove($location);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new LocationException('admin.location.errors.failedToDelete', null, $e);
        }
    }

    /**
     * @param Location $location
     *
     * @throws LocationException
     */
    private function save(Location $location)
    {
        try {
            if (!$location->getId()) {
                $this->entityManager->persist($location);
            }
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new LocationException('admin.location.errors.failedToSave', null, $e);
        }
    }

    /**
     * @param int $id
     * @return null|Location
     */
    public function find($id)
    {
        return $this->locationRepository->find($id);
    }

    /**
     * @return Location[]
     */
    public function findAll()
    {
        return $this->locationRepository->findAll();
    }

    public function findAllPackageFromLocations()
    {
        $locations = $this->locationRepository->findAllPackageFromLocations();

        return $this->createIndexedArray($locations);
    }

    public function findAllPackageToLocations()
    {
        $locations = $this->locationRepository->findAllPackageToLocations();

        return $this->createIndexedArray($locations);
    }

    public function findAllRouteFromLocations()
    {
        $locations = $this->locationRepository->findAllRouteFromLocations();

        return $this->createIndexedArray($locations);
    }

    public function findAllRouteToLocations()
    {
        $locations = $this->locationRepository->findAllRouteToLocations();

        return $this->createIndexedArray($locations);
    }

    /**
     * Create array of locations indexed by location id
     * @param array $locations
     * @return array
     */
    private function createIndexedArray(array $locations)
    {
        $indexedLocations = [];

        /** @var Location $location */
        foreach ($locations as $location) {
            $indexedLocations[$location->getId()] = $location;
        }

        return $indexedLocations;
    }
}
