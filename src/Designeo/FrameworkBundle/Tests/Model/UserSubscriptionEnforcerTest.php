<?php

namespace AppBundle\Tests\Model;

use Designeo\FrameworkBundle\Service\UserSubscriptionEnforcer;

/**
 * Class UserSubscriptionEnforcerTest
 * @package AppBundle\Tests\Model
 */
class UserSubscriptionEnforcerTest extends \PHPUnit_Framework_TestCase
{

    public function testGrantAccess()
    {
        $role = 'ROLE_USER';

        $authorizationChecker = $this->getAuthorizationCheckerInterfaceMock();
        $authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->with($role)
            ->willReturn(true);

        $userSubscriptionEnforcer = new UserSubscriptionEnforcer($authorizationChecker);
        $userSubscriptionEnforcer->denyAccessUnlessGranted($role);
    }

    /**
     * @expectedException \Designeo\FrameworkBundle\Exception\DataAccessException
     */
    public function testDenyAccess()
    {
        $role = 'ROLE_USER';

        $authorizationChecker = $this->getAuthorizationCheckerInterfaceMock();
        $authorizationChecker->expects($this->once())
          ->method('isGranted')
          ->with($role)
          ->willReturn(false);

        $userSubscriptionEnforcer = new UserSubscriptionEnforcer($authorizationChecker);
        $userSubscriptionEnforcer->denyAccessUnlessGranted($role);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getAuthorizationCheckerInterfaceMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface')
          ->disableOriginalConstructor()
          ->getMock();
        return $mock;
    }
}
