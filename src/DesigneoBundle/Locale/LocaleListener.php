<?php

namespace DesigneoBundle\Locale;

use AppBundle\Entity\Language;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Router;

/**
 * Class LocaleListener
 * @package AppBundle\Locale
 * @author  Adam Uhlíř <adam.uhlir@designeo.cz>
 */
class LocaleListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var string
     */
    private $environment;

    /**
     * @param string $defaultLocale
     * @param Router $router
     * @param string $environment
     */
    public function __construct($defaultLocale, Router $router, $environment)
    {
        $this->defaultLocale = $defaultLocale;
        $this->router = $router;
        $this->environment = $environment;
    }

    /**
     * Save locale to session
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        // try to see if the locale has been set as a _locale routing parameter
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);

        } else if (!$request->getSession()->has('_locale')) {
            if ($this->environment == 'test') {
                return;
            }
            // fresh person with no lang preference => redirect to default lang
            $actualRoute = $request->get('_route');
            $request->getSession()->set('_locale', $this->defaultLocale);
            $redirect = new RedirectResponse($this->router->generate($actualRoute, ['_locale' => $this->defaultLocale]));
            $event->setResponse($redirect);

            return;

        } else {
            // if no explicit locale has been set on this request, use one from the session
            $currentLocal = $request->getSession()->get('_locale');
            $actualRoute = $request->get('_route');
            if ($actualRoute == 'web_homepage_index') {
                $redirect = new RedirectResponse($this->router->generate($actualRoute, ['_locale' => $currentLocal]));
                $event->setResponse($redirect);

                return;
            } else {
                $request->setLocale($currentLocal);
            }
        }
    }

    /**
     * @return array
     */
    static public function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 17],
            ],
        ];
    }
}