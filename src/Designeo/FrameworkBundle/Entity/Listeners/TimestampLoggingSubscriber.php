<?php

namespace Designeo\FrameworkBundle\Entity\Listeners;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class TimestampLoggingSubscriber
 * @package Designeo\FrameworkBundle\Entity\Listeners
 * @author Marek Makovec <marek.makovec@designeo.cz>
 */
class TimestampLoggingSubscriber implements EventSubscriber
{

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

        if (method_exists($entity, 'setCreatedAt')) {
            $entity->setCreatedAt();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (method_exists($entity, 'touchModifiedAt')) {
            $entity->touchModifiedAt();
        }
    }
}
