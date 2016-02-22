<?php

namespace AppBundle\Security\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Class ApiToken
 * @package AppBundle\Security\Authentication
 */
class ApiToken extends AbstractToken
{

    private $user;

    /**
     * @var string
     */
    private $authorizationString;

    /**
     * Returns the user credentials.
     *
     * @return mixed The user credentials
     */
    public function getCredentials()
    {
        return '';
    }

    /**
     * @param mixed $user
     * @return ApiToken
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorizationString()
    {
        return $this->authorizationString;
    }

    /**
     * @param string $authorizationString
     * @return ApiToken
     */
    public function setAuthorizationString($authorizationString)
    {
        $this->authorizationString = $authorizationString;

        return $this;
    }
}