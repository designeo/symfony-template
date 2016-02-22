<?php

namespace AppBundle\Service;

use AppBundle\Service\ExceptionTranslator\ExceptionTranslatorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionToJsonListener
{
    /**
     * @var array
     */
    private $apiPrefixes;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var ArrayCollection|ExceptionTranslatorInterface[]
     */
    private $translators;

    public function __construct(array $apiPrefixes, $environment)
    {
        $this->apiPrefixes = $apiPrefixes;
        $this->environment = $environment;
        $this->translators = new ArrayCollection();
    }

    public function addTranslator(ExceptionTranslatorInterface $translator)
    {
        $this->translators->add($translator);
    }


    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();
        $exception = $event->getException();

        if(!$this->shouldAct($request->getRequestUri())) {
            return;
        }

        $response = $this->translateExceptionToResponse($exception);

        if (!is_null($response)) {
            $event->setResponse($response);
        }
    }

    private function shouldAct($requestedUrl)
    {
        if ($this->environment === 'dev') {
            return false;
        }

        foreach ($this->apiPrefixes as $prefix) {
            if ($this->startsWith($requestedUrl, $prefix)) {
                return true;
            }
        }

        return false;
    }


    private function startsWith($haystack, $needle) {
        return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
    }

    private function translateExceptionToResponse(Exception $exception)
    {
        foreach ($this->translators as $translator) {
            if ($translator->supports($exception)) {
                return $translator->translate($exception);
            }
        }

        return null;
    }
}