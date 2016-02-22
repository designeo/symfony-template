<?php


namespace AppBundle\Security\Provider;


use AppBundle\Repository\UserRepository;
use AppBundle\Security\Token\UserFacebookToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserFacebookProvider implements AuthenticationProviderInterface
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @param UserFacebookToken|TokenInterface $token The TokenInterface instance to authenticate
     * @return TokenInterface An authenticated TokenInterface instance, never null
     *
     */
    public function authenticate(TokenInterface $token)
    {
        $username = $token->getUsername();
        $facebook = $token->getFacebookCode();

        $user = $this->userRepository->findUserByCanonicalUsername($username);
        if (!is_null($user) || $user->getFacebookId() !== $facebook) {
            throw new AuthenticationException();
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
        return $token instanceof UserFacebookToken;
    }
}