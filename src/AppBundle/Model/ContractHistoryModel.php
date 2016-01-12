<?php

namespace AppBundle\Model;

use AppBundle\Entity\ContractHistory;
use AppBundle\Repository\ContractHistoryRepository;
use Designeo\FrameworkBundle\Model\AbstractLoggerAwareModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DBALException;
use AppBundle\Exception\ContractHistoryException;

/**
 * Model for ContractHistory entity
 * @package AppBundle\Model
 */
class ContractHistoryModel extends AbstractLoggerAwareModel
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ContractHistoryRepository
     */
    private $contractHistoryRepository;

    /**
     * @param EntityManagerInterface    $entityManager
     * @param ContractHistoryRepository $contractHistoryRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ContractHistoryRepository $contractHistoryRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->contractHistoryRepository = $contractHistoryRepository;
    }

    /**
     * @param ContractHistory $contractHistory
     * @throws \AppBundle\Exception\ContractHistoryException
     */
    public function persist(ContractHistory $contractHistory)
    {
        $this->save($contractHistory);
    }

    /**
     * @param ContractHistory $contractHistory
     * @throws \AppBundle\Exception\ContractHistoryException
     */
    public function update(ContractHistory $contractHistory)
    {
        $this->save($contractHistory);
    }

    /**
     * @param ContractHistory $contractHistory
     * @throws \AppBundle\Exception\ContractHistoryException
     */
    public function remove(ContractHistory $contractHistory)
    {
        try {
            $this->entityManager->remove($contractHistory);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new ContractHistoryException('admin.contractHistory.errors.failedToDelete', null, $e);
        }
    }

    /**
     * @param ContractHistory $contractHistory
     *
     * @throws ContractHistoryException
     */
    private function save(ContractHistory $contractHistory)
    {
        try {
            if (!$contractHistory->getId()) {
                $this->entityManager->persist($contractHistory);
            }
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new ContractHistoryException('admin.contractHistory.errors.failedToSave', null, $e);
        }
    }

    /**
     * @param int $id
     * @return null|ContractHistory
     */
    public function find($id)
    {
        return $this->contractHistoryRepository->find($id);
    }

    /**
     * @return ContractHistory[]
     */
    public function findAll()
    {
        return $this->contractHistoryRepository->findAll();
    }
}
