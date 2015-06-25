<?php

namespace AppBundle\Service;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\Club;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;


/**
 * Class RoleProvider
 * @package AppBundle\Service
 * @author Adam Uhlíř <adam.uhlir@designeo.cz>
 */
class RolesProvider {

    /**
     * @var User
     */
    protected $user;

    /**
     * @var AuthorizationChecker
     */
    protected $checker;

    public function __construct(TokenStorage $storage, AuthorizationChecker $checker) {
        $this->checker = $checker;
        $this->user = $storage->getToken()->getUser();
    }

    public function getRolesForUserForm() {
        return [
          'ROLE_SUPER_ADMIN' => 'Admin',
        ];
    }

}