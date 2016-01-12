<?php


namespace AppBundle\Tests\Helpers;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Swift_Message;
use Swift_Mime_Message;
use Swift_Transport;

class TestSpool implements \Swift_Spool
{

    private static $messages = [];

    /**
     * Starts this Spool mechanism.
     */
    public function start()
    {
        // nop
    }

    /**
     * Stops this Spool mechanism.
     */
    public function stop()
    {
        // nop
    }

    public function resetQueue()
    {
        self::$messages = [];
    }

    /**
     * Tests if this Spool mechanism has started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * Queues a message.
     *
     * @param Swift_Mime_Message $message The message to store
     *
     * @return bool Whether the operation has succeeded
     */
    public function queueMessage(Swift_Mime_Message $message)
    {
        self::$messages[] = $message;
    }

    /**
     * Sends messages using the given transport instance.
     *
     * @param Swift_Transport $transport A transport instance
     * @param string[] $failedRecipients An array of failures by-reference
     *
     * @return int The number of sent emails
     */
    public function flushQueue(Swift_Transport $transport, &$failedRecipients = null)
    {
        // nop
    }

    /**
     * @return Swift_Message[]|Collection
     */
    public function getMessages()
    {
        return new ArrayCollection(self::$messages);
    }
}