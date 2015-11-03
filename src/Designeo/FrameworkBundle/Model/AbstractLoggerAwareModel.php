<?php

namespace Designeo\FrameworkBundle\Model;

use Psr\Log\LoggerInterface;

/**
 * Class AbstractLoggerAwareModel
 * @package AppBundle\Model
 */
abstract class AbstractLoggerAwareModel
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param mixed $logger
     * @return AbstractLoggerAwareModel
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }
}