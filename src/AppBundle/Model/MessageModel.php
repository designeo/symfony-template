<?php

namespace AppBundle\Model;

use AppBundle\Entity\Message;
use AppBundle\Repository\MessageRepository;
use Designeo\FrameworkBundle\Model\AbstractLoggerAwareModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DBALException;
use AppBundle\Exception\MessageException;

/**
 * Model for Message entity
 * @package AppBundle\Model
 */
class MessageModel extends AbstractLoggerAwareModel
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param MessageRepository      $messageRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        MessageRepository $messageRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
    }

    /**
     * @param Message $message
     * @throws \AppBundle\Exception\MessageException
     */
    public function persist(Message $message)
    {
        $this->save($message);
    }

    /**
     * @param Message $message
     * @throws \AppBundle\Exception\MessageException
     */
    public function update(Message $message)
    {
        $this->save($message);
    }

    /**
     * @param Message $message
     * @throws \AppBundle\Exception\MessageException
     */
    public function remove(Message $message)
    {
        try {
            $this->entityManager->remove($message);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new MessageException('admin.message.errors.failedToDelete', null, $e);
        }
    }

    /**
     * @param Message $message
     *
     * @throws MessageException
     */
    private function save(Message $message)
    {
        try {
            if (!$message->getId()) {
                $this->entityManager->persist($message);
            }
            $this->entityManager->flush();
        } catch (DBALException $e) {
            $this->logger->error($e);
            throw new MessageException('admin.message.errors.failedToSave', null, $e);
        }
    }

    /**
     * @param int $id
     * @return null|Message
     */
    public function find($id)
    {
        return $this->messageRepository->find($id);
    }

    /**
     * @return Message[]
     */
    public function findAll()
    {
        return $this->messageRepository->findAll();
    }
}
