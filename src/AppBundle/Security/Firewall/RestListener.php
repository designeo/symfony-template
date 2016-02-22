<?php

namespace AppBundle\Security\Firewall;

use AppBundle\Security\Provider\RestProvider;
use AppBundle\Security\Token\ApiToken;
use AppBundle\Security\Token\DebugApiToken;
use Designeo\FrameworkBundle\Service\RolesProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * Class RestListener
 * @package AppBundle\Security\Firewall
 */
class RestListener implements ListenerInterface
{

    /**
     * @var array
     */
    private $secret;

    /**
     * @var RestProvider
     */
    private $restProvider;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * Array of addresses from which are request considered as debug.
     * @var array
     */
    private $debugAddresses = [];

    /**
     * @param RestProvider $restProvider
     * @param TokenStorage $tokenStorage
     * @param array        $secret
     * @param array        $debugAddresses
     */
    public function __construct(
      RestProvider $restProvider,
      TokenStorage $tokenStorage,
      array $secret,
      array $debugAddresses
    )
    {
        $this->restProvider = $restProvider;
        $this->tokenStorage = $tokenStorage;
        $this->secret = $secret;
        $this->debugAddresses = $debugAddresses;
    }

    /**
     * This interface must be implemented by firewall listeners.
     *
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $token = $this->createTokenFromRequest($request);

        try {
            $this->restProvider->authenticate($token);
            $this->tokenStorage->setToken($token);
        } catch (AuthenticationException $exception) {
            $response = new Response('', Response::HTTP_FORBIDDEN);
            $event->setResponse($response);
        }
    }

    private function createTokenFromRequest(Request $request)
    {
        $roles = [RolesProvider::ROLE_USER];

        if (in_array($request->getClientIp(), $this->debugAddresses)) {
            return new DebugApiToken($roles);
        }

        $token = new ApiToken($roles);

        $apiKey = trim($request->headers->get('X-Api-Key'));
        $authorization = trim($request->headers->get('Authorization'));
        $appVersion = trim($request->headers->get('X-App-Version'));

        if (!isset($apiKey) || in_array($apiKey, $this->secret)) {
            throw new UnauthorizedHttpException('Replay attack occured');
        }

        $token->setToken($authorization);

        return $token;
    }
}