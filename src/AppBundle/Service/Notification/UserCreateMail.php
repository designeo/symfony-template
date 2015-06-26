<?php

namespace AppBundle\Service\Notification;

use AppBundle\Entity\User;

/**
 * Class UserCreateMail
 * @package AppBundle\Service\Notification
 */
class UserCreateMail
{
    /**
     * @var string
     */
    private $senderMail;

    /**
     * @var string
     */
    private $senderName;

    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer, $senderMail, $senderName)
    {
        $this->mailer = $mailer;
        $this->senderMail = $senderMail;
        $this->senderName = $senderName;
    }

    /**
     * @param User $user
     * @param $password
     */
    public function send(User $user, $password)
    {
        $message = $this->mailer->createMessage()
            ->setSubject('Založení účtu')
            ->setFrom($this->senderMail, $this->senderName)
            ->setTo($user->getEmail())
            ->setBody($this->getMessage($user, $password), 'text/html');

        $this->mailer->send($message);
    }

    /**
     * @param User $user
     * @param $password
     * @return string|void
     */
    public function getMessage(User $user, $password)
    {
        return $this->mailer->getTemplating()->render('Admin/User/emails/create.html.twig', [
            'user' => $user,
            'password' => $password
        ]);
    }
}