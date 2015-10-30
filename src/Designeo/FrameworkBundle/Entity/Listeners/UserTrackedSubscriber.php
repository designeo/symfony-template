<?php

namespace Designeo\FrameworkBundle\Entity\Listeners;

use AppBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserTrackedListener
 * @package Designeo\FrameworkBundle\Entity\Listeners
 * @author Ondřej Musil <omusil@gmail.com>
 */
class UserTrackedSubscriber implements EventSubscriber
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array('prePersist', 'preUpdate');
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (method_exists($entity, 'setCreatedBy')) {
            $entity->setCreatedBy($this->getSignedInUser());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (method_exists($entity, 'setModifiedBy')) {
            $entity->setModifiedBy($this->getSignedInUser());
        }
    }

    /**
     * @return User
     */
    private function getSignedInUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}