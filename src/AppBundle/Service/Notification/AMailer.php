<?php

namespace AppBundle\Service\Notification;

use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AMailer
 * @package AppBundle\Service\Notifier
 * @author Ondřej Musil <ondrej.musil@designeo.cz>
 */
abstract class AMailer
{
    /**
     * @var string
     */
    protected $senderMail;

    /**
     * @var string
     */
    protected $senderName;

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

    public function __construct(Swift_Mailer $mailer, EngineInterface $templating, TranslatorInterface $translator, $senderMail, $senderName)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
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
        return $this->mailer;
    }

    protected function getTemplating()
    {
        return $this->templating;
    }

    protected function getTranslator()
    {
        return $this->translator;
    }
}