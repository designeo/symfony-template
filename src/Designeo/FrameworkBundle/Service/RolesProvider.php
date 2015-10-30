<?php

namespace Designeo\FrameworkBundle\Service;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class RoleProvider
 * @package Designeo\FrameworkBundle\Service
 * @author Adam Uhlíř <adam.uhlir@designeo.cz>
 */
class RolesProvider
{

    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    /**
     * @return array
     */
    public static function getRoleNames()
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Admin',
            self::ROLE_USER => 'User',
        ];
    }

    /**
     * @param string $role
     * @return null
     */
    public static function getRoleName($role)
    {
        $roles = self::getRoleNames();
        if (!isset($roles[$role])) {
            return null;
        }

        return $roles[$role];
    }

    /**
     * @var User
     */
    protected $user;

    /**
     * @var AuthorizationChecker
     */
    protected $checker;

    /**
     * @param TokenStorage         $storage
     * @param AuthorizationChecker $checker
     */
    public function __construct(TokenStorage $storage, AuthorizationChecker $checker)
    {
        $this->checker = $checker;
        $this->user = $storage->getToken()->getUser();
    }

    /**
     * @return array
     */
    public function getRolesForUserForm()
    {
        return $this->getRoleNames();
    }

}