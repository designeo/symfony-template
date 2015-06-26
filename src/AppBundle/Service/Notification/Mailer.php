<?php

namespace AppBundle\Service\Notification;

use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Mailer
 * @package AppBundle\Service\Notifier
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

    public function __construct(Swift_Mailer $mailer, EngineInterface $templating, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
    }

    public function createMessage()
    {
        return $this->mailer->createMessage();
    }

    public function send($message)
    {
        return $this->mailer->send($message);
    }

    public function getTemplating()
    {
        return $this->templating;
    }

    public function getTranslator()
    {
        return $this->translator;
    }
}