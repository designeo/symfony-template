<?php


namespace AppBundle\Security\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class UserFacebookToken extends AbstractToken
{

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $facebookCode;

    /**
     * @param string $username
     * @param string $facebookCode
     */
    public function __construct($username, $facebookCode, $roles)
    {
        parent::__construct($roles);
        $this->facebookCode = $facebookCode;
        $this->username = $username;
    }

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
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getFacebookCode()
    {
        return $this->facebookCode;
    }
}