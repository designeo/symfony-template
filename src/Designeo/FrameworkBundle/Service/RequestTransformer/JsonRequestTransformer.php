<?php

namespace Designeo\FrameworkBundle\Service\RequestTransformer;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class JsonRequestTransformer
 * @package Designeo\FrameworkBundle\RequestTransformer
 * @author Ondřej Musil <omusil@gmail.com>
 */
class JsonRequestTransformer
{
    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$this->isJsonRequest($request)) {
            return;
        }

        if (!$this->transformJsonBody($request)) {
            $response = Response::create('Unable to parse json content.', Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
    }

    protected function isJsonRequest(Request $request)
    {
        return $request->getContentType() === 'json';
    }

    protected function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        if (is_null($data)) {
            return true;
        }

        $request->request->replace($data);

        return true;
    }
}
