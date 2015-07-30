<?php

namespace AppBundle\Service;

use AppBundle\Exception\DataAccessException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class UserSubscriptionEnforcer
 * @package AppBundle\Service
 */
class UserSubscriptionEnforcer
{

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Throws DataAccessException unless user has given role.
     *
     * @param string $role
     * @throws DataAccessException
     */
    public function denyAccessUnlessGranted($role = RolesProvider::ROLE_SUBSCRIPTION)
    {
        if (!$this->authorizationChecker->isGranted($role)) {
            throw new DataAccessException('User does not have role ' . $role);
        }
    }
}