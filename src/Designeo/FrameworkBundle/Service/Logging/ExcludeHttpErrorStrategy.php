<?php
/**
 * @author: Stanislav Fifik <stanislav.fifik@designeo.cz>
 */

namespace Designeo\FrameworkBundle\Service\Logging;

use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExcludeHttpErrorStrategy extends ErrorLevelActivationStrategy
{


    public function __construct($actionLevel)
    {
        parent::__construct($actionLevel);
    }

    public function isHandlerActivated(array $record)
    {
        $isActivated = parent::isHandlerActivated($record);
        if ($isActivated &&
            isset($record['context']['exception']) &&
            $record['context']['exception'] instanceof HttpException
        ) {
            /** @var HttpException $exception */
            $exception = $record['context']['exception'];
            return !($exception->getStatusCode() >= 400 && $exception->getStatusCode() < 500);
        }
        return $isActivated;
    }


}