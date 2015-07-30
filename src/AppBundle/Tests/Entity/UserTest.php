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

    /**
     *
     */
    public function testPaidToday()
    {
        $date = $this->todayDate();

        $user = $this->getUser($date);
        $this->assertTrue($user->isPaid());
    }

    /**
     *
     */
    public function testPaidTomorrow()
    {
        $date = $this->todayDate();
        $date->modify('+1 day');

        $user = $this->getUser($date);
        $this->assertTrue($user->isPaid());
    }

    /**
     *
     */
    public function testUnPaidYesterday()
    {
        $date = $this->todayDate();
        $date->modify('-1 day');

        $user = $this->getUser($date);
        $this->assertFalse($user->isPaid());
    }

    /**
     *
     */
    public function testRoleSubscribed()
    {
        $role = RolesProvider::ROLE_SUBSCRIPTION;

        $date = $this->todayDate();

        $user = $this->getUser($date);

        $this->assertTrue(in_array($role, $user->getRoles()));
    }

    /**
     *
     */
    public function testRoleNoSubscribed()
    {
        $role = RolesProvider::ROLE_SUBSCRIPTION;

        $date = $this->todayDate();
        $date->modify('-1 day');

        $user = $this->getUser($date);

        $this->assertFalse(in_array($role, $user->getRoles()));
    }

    /**
     * @return \DateTime
     */
    private function todayDate()
    {
        $date = new \DateTime;
        $date->setTime(0, 0);

        return $date;
    }

    /**
     * @param \DateTime $date
     * @return User
     */
    private function getUser(\DateTime $date)
    {
        $user = new User;
        $user->setSubscribedToDate($date);

        return $user;
    }

}
