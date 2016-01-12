<?php

namespace AppBundle\Model;

use AppBundle\Entity\PaymentInstruction;
use AppBundle\Repository\PaymentInstructionRepository;
use Designeo\FrameworkBundle\Model\AbstractLoggerAwareModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DBALException;
use AppBundle\Exception\PaymentInstructionException;

/**
 * Model for PaymentInstruction entity
 * @package AppBundle\Model
 */
class PaymentInstructionModel extends AbstractLoggerAwareModel
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PaymentInstructionRepository
     */
    private $paymentInstructionRepository;

    /**
     * @param EntityManagerInterface       $entityManager
     * @param PaymentInstructionRepository $paymentInstructionRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PaymentInstructionRepository $paymentInstructionRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->paymentInstructionRepository = $paymentInstructionRepository;
    }

    /**
     * @param PaymentInstruction $paymentInstruction
     * @throws \AppBundle\Exception\PaymentInstructionException
     */
    public function persist(PaymentInstruction $paymentInstruction)
    {
        $this->save($paymentInstruction);
    }

    /**
     * @param PaymentInstruction $paymentInstruction
     * @throws \AppBundle\Exception\PaymentInstructionException
     */
    public function update(PaymentInstruction $paymentInstruction)
    {
        $this->save($paymentInstruction);
    }

    /**
     * @param PaymentInstruction $paymentInstruction
     * @throws \AppBundle\Exception\PaymentInstructionException
     */
    public function remove(PaymentInstruction $paymentInstruction)
    {
        try {
            $this->entityManager->remove($paymentInstruction);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new PaymentInstructionException('admin.paymentInstruction.errors.failedToDelete', null, $e);
        }
    }

    public function confirmPayment(PaymentInstruction $paymentInstruction)
    {
        if (!$paymentInstruction->isProcessed() && !$paymentInstruction->getPaymentDatetime()) {
            $paymentInstruction->setProcessed(true);
            $paymentInstruction->setPaymentDatetime(new \DateTime());
            $this->save($paymentInstruction);
            return true;
        }

        return false;
    }

    /**
     * @param PaymentInstruction $paymentInstruction
     *
     * @throws PaymentInstructionException
     */
    private function save(PaymentInstruction $paymentInstruction)
    {
        try {
            if (!$paymentInstruction->getId()) {
                $this->entityManager->persist($paymentInstruction);
            }
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new PaymentInstructionException('admin.paymentInstruction.errors.failedToSave', null, $e);
        }
    }

    /**
     * @param int $id
     * @return null|PaymentInstruction
     */
    public function find($id)
    {
        return $this->paymentInstructionRepository->find($id);
    }

    /**
     * @return PaymentInstruction[]
     */
    public function findAll()
    {
        return $this->paymentInstructionRepository->findAll();
    }
}
