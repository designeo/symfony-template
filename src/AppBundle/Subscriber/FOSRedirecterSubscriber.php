<?php

namespace AppBundle\Subscriber;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * FOS User Bundle redirects to his custom pages, which is not always desired.
 * This Subscriber handle Completed events and redirect User to desired pages.
 *
 * When user password is successfully resetted, the FOS bundle redirects user to a profile page.
 * We intercept that event and change the target url onto our homepage, as there is no profile page yet.
 *
 * Class RedirectUserAfterPasswordResettedSubscriber
 * @package AppBundle\Subscriber
 */
class FOSRedirecterSubscriber implements EventSubscriberInterface
{
    const TARGET_ROUTE_NAME_PASSWORD_CHANGE = 'homepage';
    const TARGET_ROUTE_NAME_PROFILE_CHANGE = 'web_profile_detail';

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param FilterUserResponseEvent $event
     */
    public function homepageRedirect(FilterUserResponseEvent $event)
    {
        $response = $event->getResponse();
        $targetUrl = $this->urlGenerator->generate(self::TARGET_ROUTE_NAME_PASSWORD_CHANGE);
        if ($response instanceof RedirectResponse) {
            /** @var RedirectResponse $response */
            $response->setTargetUrl($targetUrl);

        }
    }

    /**
     * @param FilterUserResponseEvent $event
     */
    public function profileRedirect(FilterUserResponseEvent $event)
    {
        $response = $event->getResponse();
        $targetUrl = $this->urlGenerator->generate(self::TARGET_ROUTE_NAME_PROFILE_CHANGE);
        if ($response instanceof RedirectResponse) {
            /** @var RedirectResponse $response */
            $response->setTargetUrl($targetUrl);

        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::RESETTING_RESET_COMPLETED => 'homepageRedirect',
            FOSUserEvents::PROFILE_EDIT_COMPLETED => 'profileRedirect',
            FOSUserEvents::CHANGE_PASSWORD_COMPLETED => 'profileRedirect',
        ];
    }

}