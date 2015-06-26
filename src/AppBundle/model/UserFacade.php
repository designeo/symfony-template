<?php

namespace AppBundle\Model;

use AppBundle\Service\Notification\UserCreate;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\User;

class UserFacade {

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /** @var UserCreate */
    private $userCreate;

    public function __construct(EntityManagerInterface $entityManager, UserCreate $userCreate)
    {

        $this->entityManager = $entityManager;
        $this->userCreate = $userCreate;
    }

    public function save(User $user)
    {
        $new = !$user->getId();
        $generatedPassword = $user->getPlainPassword();
        
        //TODO - permission?

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        catch (DBALException $e) {
            throw new UserException('Uživatel s tímto e-mailem již existuje');
        }

        if ($new) {
            $this->userCreate->send($user, $generatedPassword);
        }
    }

    public function remove(User $user)
    {
        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
        catch (DBALException $e) {
            throw new UserException('Uživatel nebyl vymazán');
        }
    }
}

Class UserException extends \Exception {

}