<?php

namespace AppBundle\Service\Notification;

use AppBundle\Entity\User;

/**
 * Class UserCreate
 * @package AppBundle\Service\Notification
 */
class UserCreate extends AMailer
{

    /**
     * @param User $user
     * @param $password
     */
    public function send(User $user, $password)
    {
        $message = $this->getMailer()->createMessage()
            ->setSubject('Založení účtu')
            ->setFrom($this->getSenderMail(), $this->getSenderName())
            ->setTo($user->getEmail())
            ->setBody($this->getMessage($user, $password), 'text/html');

        $this->getMailer()->send($message);
    }

    /**
     * @param User $user
     * @param $password
     * @return string|void
     */
    public function getMessage(User $user, $password)
    {
        return $this->getTemplating()->render('Admin/User/emails/create.html.twig', [
            'user' => $user,
            'password' => $password
        ]);
    }
}