<?php

namespace AppBundle\Model;

use AppBundle\Entity\Route;
use AppBundle\Repository\RouteRepository;
use Designeo\FrameworkBundle\Model\AbstractLoggerAwareModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DBALException;
use AppBundle\Exception\RouteException;

/**
 * Model for Route entity
 * @package AppBundle\Model
 */
class RouteModel extends AbstractLoggerAwareModel
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RouteRepository
     */
    private $routeRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RouteRepository        $routeRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RouteRepository $routeRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->routeRepository = $routeRepository;
    }

    /**
     * @param Route $route
     * @throws \AppBundle\Exception\RouteException
     */
    public function persist(Route $route)
    {
        $this->save($route);
    }

    /**
     * @param Route $route
     * @throws \AppBundle\Exception\RouteException
     */
    public function update(Route $route)
    {
        $this->save($route);
    }

    /**
     * @param Route $route
     * @throws \AppBundle\Exception\RouteException
     */
    public function remove(Route $route)
    {
        try {
            $this->entityManager->remove($route);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new RouteException('admin.route.errors.failedToDelete', null, $e);
        }
    }

    /**
     * @param Route $route
     *
     * @throws RouteException
     */
    private function save(Route $route)
    {
        try {
            if (!$route->getId()) {
                $this->entityManager->persist($route);
            }
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new RouteException('admin.route.errors.failedToSave', null, $e);
        }
    }

    /**
     * @param int $id
     * @return null|Route
     */
    public function find($id)
    {
        return $this->routeRepository->find($id);
    }

    /**
     * @return Route[]
     */
    public function findAll()
    {
        return $this->routeRepository->findAll();
    }
}
