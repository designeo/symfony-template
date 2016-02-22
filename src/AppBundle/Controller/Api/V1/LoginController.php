<?php

namespace AppBundle\Controller\Api\V1;

use AppBundle\Exception\UserException;
use AppBundle\Model\UserModel;
use Designeo\FrameworkBundle\Controller\AbstractController;
use Designeo\FrameworkBundle\Service\UserAuthenticatorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class LoginController
 * @package AppBundle\Controller\Api\V1
 *
 * @Route("/api/v1/login", service="app.api.login_controller")
 */
class LoginController extends AbstractController
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        TokenStorageInterface $tokenStorage
    )
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function loginAction(Request $request)
    {
        $token = $this->tokenStorage->getToken();
        $user = $token->getUser();

        return new JsonResponse([
            'data' => [
            'token' => 'abcd',
            'user' => [
            'name' => $user->getFirstName(),
            ]
            ]
        ]);
    }
}
