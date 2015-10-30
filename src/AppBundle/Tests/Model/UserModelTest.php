<?php

namespace AppBundle\Tests\Model;

use AppBundle\Model\UserModel;
use Doctrine\DBAL\DBALException;

/**
 * Class UserModelTest
 * @package AppBundle\Tests\Model
 */
class UserModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testCreateUser()
    {
        $password = 'secretPasswd';

        $user = $this->getUserMock();
        $user->expects($this->once())
            ->method('getPlainPassword')
            ->will($this->returnValue($password));

        $em = $this->getEmMock();
        $em->expects($this->once())
          ->method('persist')
          ->with($user);

        $userRepository = $this->getUserRepositoryMock();

        $userCreateMail = $this->getUserCreateMailMock();
        $userCreateMail->expects($this->once())
            ->method('send')
            ->with($user, $password);

        $userModel = new UserModel($em, $userRepository, $userCreateMail);
        $userModel->persist($user);
    }

    /**
     * @expectedException \AppBundle\Exception\UserException
     */
    public function testCreateUserDuplicateEmail()
    {
        $user = $this->getUserMock();

        $em = $this->getEmMock(false);
        $em->expects($this->once())
          ->method('persist')
          ->with($user)
          ->willThrowException(new DBALException);

        $userRepository = $this->getUserRepositoryMock();

        $userCreateMail = $this->getUserCreateMailMock();
        $userCreateMail->expects($this->never())
          ->method('send');

        $userModel = new UserModel($em, $userRepository, $userCreateMail);
        $userModel->persist($user);
    }

    /**
     *
     */
    public function testEditUser()
    {

        $user = $this->getUserMock();

        $em = $this->getEmMock();
        $em->expects($this->once())
          ->method('persist')
          ->with($user);

        $userRepository = $this->getUserRepositoryMock();

        $userCreateMail = $this->getUserCreateMailMock();
        $userCreateMail->expects($this->never())
          ->method('send');

        $userModel = new UserModel($em, $userRepository, $userCreateMail);
        $userModel->update($user);
    }

    /**
     * @throws \AppBundle\Exception\UserException
     */
    public function testRemoveUser()
    {
        $user = $this->getUserMock();

        $em = $this->getEmMock();
        $em->expects($this->once())
          ->method('remove')
          ->with($user);

        $userRepository = $this->getUserRepositoryMock();

        $userCreateMail = $this->getUserCreateMailMock();

        $userModel = new UserModel($em, $userRepository, $userCreateMail);
        $userModel->remove($user);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getUserMock()
    {
        return $this->getMockBuilder('AppBundle\Entity\User')
          ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getUserRepositoryMock()
    {
        $mock = $this->getMockBuilder('AppBundle\Repository\UserRepository')
          ->disableOriginalConstructor()
          ->getMock();

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getUserCreateMailMock()
    {
        return $this->getMockBuilder('Designeo\FrameworkBundle\Service\Notification\UserCreateMail')
          ->disableOriginalConstructor()
          ->getMock();
    }

    /**
     * @param bool $expectFlush
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getEmMock($expectFlush = true)
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
          ->getMock();

        if ($expectFlush) {
            $mock->expects($this->once())
              ->method('flush');
        }

        return $mock;
    }

}
