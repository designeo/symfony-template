<?php

namespace AppBundle\Exception;

use Exception;

/**
 * Exception thrown when there is attempt to retrieve that current logged user has no access to
 * @package AppBundle\Exception
 */
class DataAccessException extends Exception
{

}
