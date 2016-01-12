<?php

namespace AppBundle\Model;

use AppBundle\Entity\Contract;
use AppBundle\Repository\ContractRepository;
use Designeo\FrameworkBundle\Model\AbstractLoggerAwareModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DBALException;
use AppBundle\Exception\ContractException;

/**
 * Model for Contract entity
 * @package AppBundle\Model
 */
class ContractModel extends AbstractLoggerAwareModel
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ContractRepository
     */
    private $contractRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ContractRepository     $contractRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ContractRepository $contractRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->contractRepository = $contractRepository;
    }

    /**
     * @param Contract $contract
     * @throws \AppBundle\Exception\ContractException
     */
    public function persist(Contract $contract)
    {
        $this->save($contract);
    }

    /**
     * @param Contract $contract
     * @throws \AppBundle\Exception\ContractException
     */
    public function update(Contract $contract)
    {
        $this->save($contract);
    }

    /**
     * @param Contract $contract
     * @throws \AppBundle\Exception\ContractException
     */
    public function remove(Contract $contract)
    {
        try {
            $this->entityManager->remove($contract);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new ContractException('admin.contract.errors.failedToDelete', null, $e);
        }
    }

    /**
     * @param Contract $contract
     *
     * @throws ContractException
     */
    private function save(Contract $contract)
    {
        try {
            if (!$contract->getId()) {
                $this->entityManager->persist($contract);
            }
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new ContractException('admin.contract.errors.failedToSave', null, $e);
        }
    }

    /**
     * @param int $id
     * @return null|Contract
     */
    public function find($id)
    {
        return $this->contractRepository->find($id);
    }

    /**
     * @return Contract[]
     */
    public function findAll()
    {
        return $this->contractRepository->findAll();
    }

    public function findAllContractIds()
    {
        $contracts = $this->contractRepository->findAll();

        $contractIds = [];

        /** @var Contract $contract */
        foreach ($contracts as $contract) {
            $contractIds[$contract->getId()] = $contract->getId();
        }

        return $contractIds;
    }
}
