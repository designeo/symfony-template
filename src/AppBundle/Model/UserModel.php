<?php

namespace AppBundle\Model;

use AppBundle\Repository\UserRepository;
use AppBundle\Security\Token\UserFacebookToken;
use Designeo\FrameworkBundle\Service\Notification\UserCreateMail;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DBALException;
use AppBundle\Exception\UserException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Model for User entity
 * @package AppBundle\Model
 */
class UserModel
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserCreateMail
     */
    private $userCreate;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository         $userRepository
     * @param UserCreateMail         $userCreate
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserCreateMail $userCreate
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->userCreate = $userCreate;
    }

    /**
     * @param User $user
     * @throws \AppBundle\Exception\UserException
     */
    public function persist(User $user)
    {
        $generatedPassword = $user->getPlainPassword();
        $this->save($user);
        $this->userCreate->send($user, $generatedPassword);
    }

    /**
     * @param User $user
     * @throws \AppBundle\Exception\UserException
     */
    public function update(User $user)
    {
        $this->save($user);
    }

    /**
     * @param User $user
     * @throws \AppBundle\Exception\UserException
     */
    public function remove(User $user)
    {
        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            throw new UserException('admin.user.errors.failedToDelete');
        }
    }

    private function save(User $user)
    {
        try {
            if (!$user->getId()) {
                $this->entityManager->persist($user);
            }
            $this->entityManager->flush();
        } catch (DBALException $e) {
            throw new UserException('admin.users.errors.duplicateEmail');
        }
    }

    /**
     * @param int $id
     * @return null|object
     */
    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->userRepository->findAll();
    }

    public function findAllPackagesSenders()
    {
        $senders = $this->userRepository->findAllPackagesSenders();

        return $this->createIndexedArray($senders);
    }

    public function findAllPackagesCouriers()
    {
        $couriers = $this->userRepository->findAllPackagesCouriers();

        return $this->createIndexedArray($couriers);
    }

    public function findAllPaymentInstructionUsers()
    {
        $users = $this->userRepository->findAllPaymentInstructionUsers();

        return $this->createIndexedArray($users);
    }

    /**
     * Create array of users indexed by user id
     * @param array $users
     * @return array
     */
    private function createIndexedArray(array $users)
    {
        $indexedUsers = [];

        /** @var User $user */
        foreach ($users as $user) {
            $indexedUsers[$user->getId()] = $user;

        }
    }
}