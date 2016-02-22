<?php

namespace AppBundle\Service\ExceptionTranslator;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundTranslator implements ExceptionTranslatorInterface
{
    const CODE = 'ERROR_ENTITY_NOT_FOUND';

    /**
     * @param Exception $exception
     * @return boolean
     */
    public function supports(Exception $exception)
    {
        return $exception instanceof NotFoundHttpException;
    }

    /**
     * @param Exception $exception
     * @return Response
     */
    public function translate(Exception $exception)
    {
        /** @var $exception NotFoundHttpException */
        $data = [
            self::KEY_MESSAGE => $this->filterMessage($exception->getMessage()),
            self::KEY_CODE => self::CODE,
        ];


        return new JsonResponse($data, Response::HTTP_NOT_FOUND);
    }

    private function filterMessage($message) {
        return str_replace('AppBundle:', '', $message);
    }
}