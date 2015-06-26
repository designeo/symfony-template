<?php

namespace AppBundle\Tests\Model;

use AppBundle\Model\UserFacade;
use Doctrine\DBAL\DBALException;

class UserFacadeTest extends \PHPUnit_Framework_TestCase {

    public function testCreateUser()
    {
        $password = 'secretPasswd';

        $user = $this->getUserMock();
        $user->expects($this->once())
          ->method('getId')
          ->will($this->returnValue(FALSE));
        $user->expects($this->once())
            ->method('getPlainPassword')
            ->will($this->returnValue($password));

        $em = $this->getEmMock();
        $em->expects($this->once())
          ->method('persist')
            ->with($user);

        $userCreateMail = $this->getUserCreateMailMock();
        $userCreateMail->expects($this->once())
            ->method('send')
            ->with($user, $password);

        $userFacade = new UserFacade($em, $userCreateMail);
        $userFacade->save($user);
    }

    /**
     * @expectedException \AppBundle\Model\UserException
     */
    public function testCreateUserDuplicateEmail()
    {
        $password = 'secretPasswd';

        $user = $this->getUserMock();
        $user->expects($this->once())
          ->method('getId')
          ->will($this->returnValue(FALSE));
        $user->expects($this->once())
          ->method('getPlainPassword')
          ->will($this->returnValue($password));

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
          ->getMock();
        $em->expects($this->once())
          ->method('persist')
          ->with($user)
          ->willThrowException(new DBALException);

        $userCreateMail = $this->getUserCreateMailMock();
        $userCreateMail->expects($this->never())
          ->method('send')
          ->with($user, $password);

        $userFacade = new UserFacade($em, $userCreateMail);
        $userFacade->save($user);
    }

    public function testEditUser()
    {
        $password = 'secretPasswd';

        $user = $this->getUserMock();
        $user->expects($this->once())
          ->method('getId')
          ->will($this->returnValue(TRUE));
        $user->expects($this->once())
          ->method('getPlainPassword')
          ->will($this->returnValue($password));

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
          ->getMock();
        $em->expects($this->once())
          ->method('persist')
          ->with($user);

        $userCreateMail = $this->getUserCreateMailMock();
        $userCreateMail->expects($this->never())
          ->method('send')
          ->with($user, $password);

        $userFacade = new UserFacade($em, $userCreateMail);
        $userFacade->save($user);
    }

    public function testRemoveUser()
    {
        $user = $this->getUserMock();

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
          ->getMock();
        $em->expects($this->once())
          ->method('remove')
          ->with($user);

        $userCreateMail = $this->getUserCreateMailMock();

        $userFacade = new UserFacade($em, $userCreateMail);
        $userFacade->remove($user);
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
     * @param bool $expectFlush
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getEmMock($expectFlush = TRUE)
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
          ->getMock();

        if ($expectFlush) {
            $mock->expects($this->once())
              ->method('flush');
        }

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getUserCreateMailMock()
    {
        return $this->getMockBuilder('AppBundle\Service\Notification\UserCreateMail')
          ->disableOriginalConstructor()
          ->getMock();
    }

}
