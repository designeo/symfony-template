<?php

namespace AppBundle\Service\ExceptionTranslator;

use Exception;
use Symfony\Component\HttpFoundation\Response;

interface ExceptionTranslatorInterface
{
    const KEY_CODE = 'error_code';
    const KEY_MESSAGE = 'error_message';

    /**
     * @param Exception $exception
     * @return boolean
     */
    public function supports(Exception $exception);

    /**
     * @param Exception $exception
     * @return Response
     */
    public function translate(Exception $exception);
}