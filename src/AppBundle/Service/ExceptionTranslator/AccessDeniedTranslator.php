<?php


namespace AppBundle\Service\ExceptionTranslator;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessDeniedTranslator implements ExceptionTranslatorInterface
{
    const CODE = 'ERROR_ACCESS_DENIED';
    /**
     * @param Exception $exception
     * @return boolean
     */
    public function supports(Exception $exception)
    {
        return $exception instanceof AccessDeniedHttpException;
    }

    /**
     * @param Exception $exception
     * @return Response
     */
    public function translate(Exception $exception)
    {
        /** @var $exception AccessDeniedHttpException */
        $data = [
            self::KEY_CODE => self::CODE,
            self::KEY_MESSAGE => "You don't have permission to do this action",
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }
}