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
        $user->expects($this->once())
          ->method('getSubscribedToDate')
          ->willReturn(null);

        $em = $this->getEmMock();
        $em->expects($this->once())
          ->method('persist')
          ->with($user);

        $userRepository = $this->getUserRepositoryMock();

        $userCreateMail = $this->getUserCreateMailMock();
        $userCreateMail->expects($this->once())
            ->method('send')
            ->with($user, $password);

        $subscriptionModel = $this->getSubscriptionModelMock();
        $subscriptionModel->expects($this->never())
          ->method('newSubscription');

        $userModel = new UserModel($em, $userRepository, $userCreateMail, $subscriptionModel);
        $userModel->persist($user);
    }

    /**
     *
     */
    public function testCreateUserWithSubscription()
    {
        $password = 'secretPasswd';

        $user = $this->getUserMock();
        $user->expects($this->once())
          ->method('getPlainPassword')
          ->will($this->returnValue($password));
        $user->expects($this->exactly(2))
          ->method('getSubscribedToDate')
          ->willReturn(new \DateTime());

        $em = $this->getEmMock();
        $em->expects($this->once())
          ->method('persist')
          ->with($user);

        $userRepository = $this->getUserRepositoryMock();

        $userCreateMail = $this->getUserCreateMailMock();
        $userCreateMail->expects($this->once())
          ->method('send')
          ->with($user, $password);

        $subscriptionModel = $this->getSubscriptionModelMock();
        $subscriptionModel->expects($this->once())
          ->method('newSubscription');

        $userModel = new UserModel($em, $userRepository, $userCreateMail, $subscriptionModel);
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

        $subscriptionModel = $this->getSubscriptionModelMock();
        $subscriptionModel->expects($this->never())
          ->method('newSubscription');

        $userModel = new UserModel($em, $userRepository, $userCreateMail, $subscriptionModel);
        $userModel->persist($user);
    }

    /**
     *
     */
    public function testEditUser()
    {
        $date = new \DateTime();
        $oldUser = ['subscribedToDate' => $date];

        $user = $this->getUserMock();
        $user->expects($this->once())
          ->method('getSubscribedToDate')
          ->willReturn($date);

        $uow = $this->getUOWMock();
        $uow->expects($this->once())
          ->method('getOriginalEntityData')
          ->willReturn($oldUser);

        $em = $this->getEmMock();
        $em->expects($this->once())
          ->method('persist')
          ->with($user);
        $em->expects($this->once())
          ->method('getUnitOfWork')
          ->willReturn($uow);

        $userRepository = $this->getUserRepositoryMock();

        $userCreateMail = $this->getUserCreateMailMock();
        $userCreateMail->expects($this->never())
          ->method('send');

        $subscriptionModel = $this->getSubscriptionModelMock();
        $subscriptionModel->expects($this->never())
          ->method('newSubscription');

        $userModel = new UserModel($em, $userRepository, $userCreateMail, $subscriptionModel);
        $userModel->update($user);
    }

    /**
     *
     */
    public function testEditUserWithSubscriptionUpdate()
    {
        $date = new \DateTime();
        $date->modify('-1 day');
        $oldUser = ['subscribedToDate' => $date];

        $user = $this->getUserMock();
        $user->expects($this->exactly(2))
          ->method('getSubscribedToDate')
          ->willReturn(new \DateTime());

        $uow = $this->getUOWMock();
        $uow->expects($this->once())
          ->method('getOriginalEntityData')
          ->willReturn($oldUser);

        $em = $this->getEmMock();
        $em->expects($this->once())
          ->method('persist')
          ->with($user);
        $em->expects($this->once())
          ->method('getUnitOfWork')
          ->willReturn($uow);

        $userRepository = $this->getUserRepositoryMock();

        $userCreateMail = $this->getUserCreateMailMock();
        $userCreateMail->expects($this->never())
          ->method('send');

        $subscriptionModel = $this->getSubscriptionModelMock();
        $subscriptionModel->expects($this->once())
          ->method('newSubscription');

        $userModel = new UserModel($em, $userRepository, $userCreateMail, $subscriptionModel);
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

        $subscriptionModel = $this->getSubscriptionModelMock();

        $userModel = new UserModel($em, $userRepository, $userCreateMail, $subscriptionModel);
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
        return $this->getMockBuilder('AppBundle\Service\Notification\UserCreateMail')
          ->disableOriginalConstructor()
          ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSubscriptionModelMock()
    {
        return $this->getMockBuilder('AppBundle\Model\SubscriptionModel')
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getUOWMock()
    {
        return $this->getMockBuilder('Doctrine\ORM\UnitOfWork')
          ->disableOriginalConstructor()
          ->getMock();
    }

}
