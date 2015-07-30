<?php

namespace AppBundle\Tests\Model;

use AppBundle\Model\SubscriptionModel;

/**
 * Class SubscriptionModelTest
 * @package AppBundle\Tests\Model
 */
class SubscriptionModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testCreateSubscription()
    {
        $dateTo = new \DateTime();
        $dateFrom = new \DateTime();

        $user = $this->getUserMock();

        $em = $this->getEmMock();
        $em->expects($this->once())
          ->method('persist');

        $subscriptionRepository = $this->getSubscriptionRepositoryMock();
        $subscriptionModel = new SubscriptionModel($em, $subscriptionRepository);
        $subscriptionModel->newSubscription($user, $dateTo, $dateFrom);
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
    private function getUserMock()
    {
        return $this->getMockBuilder('AppBundle\Entity\User')
          ->getMock();
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSubscriptionRepositoryMock()
    {
        $mock = $this->getMockBuilder('AppBundle\Repository\SubscriptionRepository')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
}
