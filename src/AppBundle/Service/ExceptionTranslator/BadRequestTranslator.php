<?php


namespace AppBundle\Service\ExceptionTranslator;


use AppBundle\Exception\ShippanseeException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BadRequestTranslator implements ExceptionTranslatorInterface
{

    /**
     * @param Exception $exception
     * @return boolean
     */
    public function supports(Exception $exception)
    {
        return $exception instanceof BadRequestHttpException;
    }

    /**
     * @param Exception $exception
     * @return Response
     */
    public function translate(Exception $exception)
    {
        $httpCode = Response::HTTP_BAD_REQUEST;

        if ($exception->getPrevious()) {
            $exception = $exception->getPrevious();
        }

        if ($exception instanceof ShippanseeException) {
            $data = $this->translateShippanseeException($exception);
        } else {
            $data = [
                self::KEY_CODE => ShippanseeException::MESSAGE_CODE_UNDEFINED,
                self::KEY_MESSAGE => $exception->getMessage(),
            ];
        }


        return new JsonResponse($data, $httpCode);
    }

    private function translateShippanseeException(ShippanseeException $e) {
        return [
            self::KEY_CODE => $e->getCodeString(),
            self::KEY_MESSAGE => $e->getMessage(),
        ];
    }
}