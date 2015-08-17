<?php

namespace AppBundle\Model;

use AppBundle\Entity\StaticText;
use AppBundle\Repository\StaticTextRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DBALException;
use AppBundle\Exception\StaticTextException;

/**
 * Model for StaticText entity
 * @package AppBundle\Model
 */
class StaticTextModel extends AbstractLoggerAwareModel
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var StaticTextRepository
     */
    private $staticTextRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param StaticTextRepository      $staticTextRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        StaticTextRepository $staticTextRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->staticTextRepository = $staticTextRepository;
    }

    /**
     * @param StaticText $staticText
     * @throws \AppBundle\Exception\StaticTextException
     */
    public function persist(StaticText $staticText)
    {
        $this->save($staticText);
    }

    /**
     * @param StaticText $staticText
     * @throws \AppBundle\Exception\StaticTextException
     */
    public function update(StaticText $staticText)
    {
        $this->save($staticText);
    }

    /**
     * @param StaticText $staticText
     * @throws \AppBundle\Exception\StaticTextException
     */
    public function remove(StaticText $staticText)
    {
        try {
            $this->entityManager->remove($staticText);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new StaticTextException('admin.staticText.errors.failedToDelete', null, $e);
        }
    }

    /**
     * @param StaticText $staticText
     *
     * @throws StaticTextException
     */
    private function save(StaticText $staticText)
    {
        try {
            if (!$staticText->getId()) {
                $this->entityManager->persist($staticText);
            }
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new StaticTextException('admin.staticText.errors.failedToSave', null, $e);
        }
    }

    /**
     * @param int $id
     * @return null|StaticText
     */
    public function find($id)
    {
        return $this->staticTextRepository->find($id);
    }

    /**
     * @param string $name
     * @return StaticText|null
     */
    public function findOneByName($name)
    {
        return $this->staticTextRepository->findOneByName($name);
    }

    /**
     * @param $names
     * @return StaticText[]
     */
    public function findManyByName($names)
    {
        return $this->staticTextRepository->findManyByName($names);
    }

    /**
     * @return StaticText[]
     */
    public function findAll()
    {
        return $this->staticTextRepository->findAll();
    }
}
