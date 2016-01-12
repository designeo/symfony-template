<?php

namespace AppBundle\Security\Provider;

use AppBundle\Repository\UserRepository;
use AppBundle\Security\Token\ApiToken;
use AppBundle\Security\Token\UserFacebookToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class TokenAuthenticator
 * @author Ondřej Musil <ondrej.musil@designeo.cz>
 * @author Marek Makovec <marek.makovec@designeo.cz>
 */
class ApiLoginProvider implements AuthenticationProviderInterface
{
    private $passwordEncoder;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param UserRepository      $userRepository
     * @param UserPasswordEncoder $passwordEncoder
     */
    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoder $passwordEncoder
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param UsernamePasswordToken|UserFacebookToken $token
     * @return TokenInterface
     */
    public function authenticate(TokenInterface $token)
    {
        if ($token instanceof UserFacebookToken) {
            return $this->verifyFacebookUser($token);
        } else if ($token instanceof UsernamePasswordToken) {
            return $this->verifyUserPasswordToken($token);
        } else {
            throw new \InvalidArgumentException('Given token class is not UsernamePasswordToken neither UserFacebookToken');
        }
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
        return $token instanceof UsernamePasswordToken || $token instanceof UserFacebookToken;
    }

    private function findUserByEmail($email)
    {
        $user = $this->userRepository->findUserByCanonicalUsername($email);

        if (!$user) {
            throw new AuthenticationException('Invalid username');
        }

        return $user;
    }

    private function verifyFacebookUser(UserFacebookToken $token)
    {
        $user = $this->findUserByEmail($token->getUsername());

        if ($user->getFacebookId() !== $token->getFacebookCode()) {
            throw new AuthenticationException('Facebook token mismatch.');
        }

        $token->setUser($user);

        return $token;
    }

    private function verifyUserPasswordToken(UsernamePasswordToken $token)
    {
        $user = $this->findUserByEmail($token->getUsername());

        if (!$this->passwordEncoder->isPasswordValid($user, $token->getCredentials())) {
            throw new AuthenticationException('Password mismatch.');
        }

        $token->setUser($user);

        return $token;
    }
}