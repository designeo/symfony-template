<?php

namespace AppBundle\Controller\Api\V1;

use Designeo\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class TestStaticController
 * @package AppBundle\Controller\Api\V1
 *
 * @Route("/api/v1/test-controller", service="app.api.test_static_controller")
 */
class TestStaticController extends AbstractController
{
    private $tokenStorage;

    /**
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/something")
     * @return Response
     */
    public function somethingAction()
    {
        dump($this->tokenStorage->getToken());

        return new Response('Hello world');
    }
}
