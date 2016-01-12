<?php

namespace AppBundle\Model;

use AppBundle\Entity\Rating;
use AppBundle\Repository\RatingRepository;
use Designeo\FrameworkBundle\Model\AbstractLoggerAwareModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DBALException;
use AppBundle\Exception\RatingException;

/**
 * Model for Rating entity
 * @package AppBundle\Model
 */
class RatingModel extends AbstractLoggerAwareModel
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RatingRepository
     */
    private $ratingRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RatingRepository       $ratingRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RatingRepository $ratingRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->ratingRepository = $ratingRepository;
    }

    /**
     * @param Rating $rating
     * @throws \AppBundle\Exception\RatingException
     */
    public function persist(Rating $rating)
    {
        $this->save($rating);
    }

    /**
     * @param Rating $rating
     * @throws \AppBundle\Exception\RatingException
     */
    public function update(Rating $rating)
    {
        $this->save($rating);
    }

    /**
     * @param Rating $rating
     * @throws \AppBundle\Exception\RatingException
     */
    public function remove(Rating $rating)
    {
        try {
            $this->entityManager->remove($rating);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new RatingException('admin.rating.errors.failedToDelete', null, $e);
        }
    }

    /**
     * @param Rating $rating
     *
     * @throws RatingException
     */
    private function save(Rating $rating)
    {
        try {
            if (!$rating->getId()) {
                $this->entityManager->persist($rating);
            }
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new RatingException('admin.rating.errors.failedToSave', null, $e);
        }
    }

    /**
     * @param int $id
     * @return null|Rating
     */
    public function find($id)
    {
        return $this->ratingRepository->find($id);
    }

    /**
     * @return Rating[]
     */
    public function findAll()
    {
        return $this->ratingRepository->findAll();
    }
}
