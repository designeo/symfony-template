<?php

namespace Designeo\FrameworkBundle\Service\Notification;

use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Mailer
 * @package Designeo\FrameworkBundle\Service\Notifier
 * @author Ondřej Musil <ondrej.musil@designeo.cz>
 * @author Petr Fidler
 */
final class Mailer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param Swift_Mailer        $mailer
     * @param EngineInterface     $templating
     * @param TranslatorInterface $translator
     */
    public function __construct(
        Swift_Mailer $mailer,
        EngineInterface $templating,
        TranslatorInterface $translator
    )
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
    }

    /**
     * @return object
     */
    public function createMessage()
    {
        return $this->mailer->createMessage();
    }

    /**
     * @param mixed $message
     * @return int
     */
    public function send($message)
    {
        return $this->mailer->send($message);
    }

    /**
     * @return EngineInterface
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator()
    {
        return $this->translator;
    }
}