<?php

namespace AppBundle\Security\Provider;

use AppBundle\Model\UserModel;
use AppBundle\Repository\UserRepository;
use AppBundle\Security\Token\ApiToken;
use AppBundle\Security\Token\DebugApiToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class TokenAuthenticator
 * @package Photobooth\ApiBundle\Security\Authentication
 * @author Ondřej Musil <ondrej.musil@designeo.cz>
 * @author Marek Makovec <marek.makovec@designeo.cz>
 */
class RestProvider implements AuthenticationProviderInterface
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ApiToken $token
     * @return TokenInterface
     */
    public function authenticate(TokenInterface $token)
    {

        $user = $this->userRepository->findByAuthorizationString($token->getAuthorizationString());

        if (!$user) {
            throw new AuthenticationException('Invalid token');
        }

        $token->setUser($user);

        return $token;
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return bool true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof ApiToken;
    }
}