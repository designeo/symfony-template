<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\User;
use AppBundle\Service\RolesProvider;

/**
 * Class UserTest
 * @package AppBundle\Tests\Entity
 */
class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testSetRoleFromForm()
    {
        $role = 'TEST';

        $user = new User;
        $user->setUserRole($role);
        $this->assertTrue(in_array($role, $user->getRoles()));
        $this->assertTrue($user->hasRole($role));
        $this->assertSame($user->getUserRole(), $role);
    }

}
