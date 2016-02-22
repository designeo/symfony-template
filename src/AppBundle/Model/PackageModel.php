<?php

namespace AppBundle\Model;

use AppBundle\Entity\Package;
use AppBundle\Repository\PackageRepository;
use Designeo\FrameworkBundle\Model\AbstractLoggerAwareModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DBALException;
use AppBundle\Exception\PackageException;

/**
 * Model for Package entity
 * @package AppBundle\Model
 */
class PackageModel extends AbstractLoggerAwareModel
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PackageRepository
     */
    private $packageRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param PackageRepository      $packageRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PackageRepository $packageRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->packageRepository = $packageRepository;
    }

    /**
     * @param Package $package
     * @throws \AppBundle\Exception\PackageException
     */
    public function persist(Package $package)
    {
        $this->save($package);
    }

    /**
     * @param Package $package
     * @throws \AppBundle\Exception\PackageException
     */
    public function update(Package $package)
    {
        $this->save($package);
    }

    /**
     * @param Package $package
     * @throws \AppBundle\Exception\PackageException
     */
    public function remove(Package $package)
    {
        try {
            $this->entityManager->remove($package);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new PackageException('admin.package.errors.failedToDelete', null, $e);
        }
    }

    /**
     * @param Package $package
     *
     * @throws PackageException
     */
    private function save(Package $package)
    {
        try {
            if (!$package->getId()) {
                $this->entityManager->persist($package);
            }
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new PackageException('admin.package.errors.failedToSave', null, $e);
        }
    }

    /**
     * @param int $id
     * @return null|Package
     */
    public function find($id)
    {
        return $this->packageRepository->find($id);
    }

    /**
     * @return Package[]
     */
    public function findAll()
    {
        return $this->packageRepository->findAll();
    }
}
