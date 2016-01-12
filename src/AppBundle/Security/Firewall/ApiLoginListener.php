<?php

namespace AppBundle\Security\Firewall;

use AppBundle\Security\Provider\ApiLoginProvider;
use AppBundle\Security\Provider\RestProvider;
use AppBundle\Security\Token\ApiToken;
use AppBundle\Security\Token\DebugApiToken;
use AppBundle\Security\Token\UserFacebookToken;
use Designeo\FrameworkBundle\Service\RolesProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\ChainUserProvider;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * Class RestListener
 * @package AppBundle\Security\Firewall
 */
class ApiLoginListener implements ListenerInterface
{

    const KEY_USERNAME = 'username';
    const KEY_PASSWORD = 'password';
    const KEY_TYPE = 'type';

    const LOGIN_TYPE_FACEBOOK = 'facebook';
    const LOGIN_TYPE_PASSWORD = 'password';

    /**
     * @var ApiLoginProvider
     */
    private $apiLoginProvider;

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
     * @param ApiLoginProvider $apiLoginProvider
     * @param TokenStorage     $tokenStorage
     * @param array            $debugAddresses
     */
    public function __construct(
        ApiLoginProvider $apiLoginProvider,
        TokenStorage $tokenStorage,
        array $debugAddresses
    )
    {
        $this->apiLoginProvider = $apiLoginProvider;
        $this->tokenStorage = $tokenStorage;
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

        try {
            $token = $this->createTokenFromRequest($request);
            $this->apiLoginProvider->authenticate($token);
            $this->tokenStorage->setToken($token);
        } catch (\InvalidArgumentException $exception) {
            $response = new JsonResponse(
                [
                    'error' =>
                        'Malformed data sent. Check if you have required keys present (username, password and type).'
                ],
                Response::HTTP_BAD_REQUEST
            );
            $event->setResponse($response);
        } catch (AuthenticationException $exception) {
            $response = new JsonResponse(['error' => 'Credentials mismatch'], Response::HTTP_FORBIDDEN);
            $event->setResponse($response);
        }
    }

    private function createTokenFromRequest(Request $request)
    {
        $roles = [RolesProvider::ROLE_USER];

        $bag = $this->extractPostedJsonData($request);



        $username = $this->normalize($bag->get(self::KEY_USERNAME));
        $password = $this->normalize($bag->get(self::KEY_PASSWORD));
        $loginType = $this->normalize($bag->get(self::KEY_TYPE));

        switch ($loginType) {
            case self::LOGIN_TYPE_FACEBOOK:
                $token = new UserFacebookToken($username, $password, $roles);
                break;
            case self::LOGIN_TYPE_PASSWORD:
                $token =  new UsernamePasswordToken($username, $password, 'api_v1_login', $roles);
                break;
            default:
                throw new \InvalidArgumentException('Invalid login type method');
        }

        return $token;
    }

    /**
     * @param Request $request
     * @return ParameterBag
     */
    private function extractPostedJsonData(Request $request)
    {
        $requestBody = $request->getContent();
        $requestData = json_decode($requestBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Malformed JSON given');
        }

        return new ParameterBag($requestData);
    }

    /**
     * @param $string
     * @return string
     */
    private function normalize($string)
    {
        return strtolower(trim($string));
    }
}