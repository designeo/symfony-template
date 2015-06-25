<?php

namespace AppBundle\Service\Notification;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AMailer
 * @package AppBundle\Service\Notifier
 * @author Ondřej Musil <ondrej.musil@designeo.cz>
 */
abstract class AMailer extends ContainerAware
{
    /**
     * @var string
     */
    protected $senderMail;

    /**
     * @var string
     */
    protected $senderName;

    public function __construct(ContainerInterface $container, $senderMail, $senderName)
    {
        $this->container = $container;
        $this->senderMail = $senderMail;
        $this->senderName = $senderName;
    }

    protected function getSenderMail()
    {
        return $this->senderMail;
    }

    protected function getSenderName()
    {
        return $this->senderName;
    }

    protected function getMailer()
    {
        return $this->container->get('mailer');
    }

    protected function getTemplating()
    {
        return $this->container->get('templating');
    }

    protected function getTranslator()
    {
        return $this->container->get('translator');
    }
}