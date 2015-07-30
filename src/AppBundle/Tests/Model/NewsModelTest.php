<?php

namespace AppBundle\Tests\Model;

use AppBundle\Entity\Destination;
use AppBundle\Exception\DataAccessException;
use AppBundle\Model\NewsModel;

/**
 * Class NewsModelTest
 * @package AppBundle\Tests\Model
 */
class NewsModelTest extends \PHPUnit_Framework_TestCase
{

    public function testFind()
    {
        $id = 1;

        $localeProvider = $this->getLocaleProviderMock();
        $em = $this->getEmMock();
        $newsRepository = $this->getNewsRepositoryMock();
        $newsRepository->expects($this->once())
            ->method('find')
            ->with($id);

        $userSubscriptionEnforcer = $this->getUserSubscriptionEnforcer();
        $userSubscriptionEnforcer->expects($this->once())
            ->method('denyAccessUnlessGranted')
            ->with();

        $NewsModel = new NewsModel($em, $localeProvider, $newsRepository, $userSubscriptionEnforcer);
        $NewsModel->find($id);
    }

    /**
     * @expectedException \AppBundle\Exception\DataAccessException
     */
    public function testFindNotGranted()
    {
        $id = 1;

        $localeProvider = $this->getLocaleProviderMock();
        $em = $this->getEmMock();
        $newsRepository = $this->getNewsRepositoryMock();
        $newsRepository->expects($this->never())
            ->method('find');

        $userSubscriptionEnforcer = $this->getUserSubscriptionEnforcer();
        $userSubscriptionEnforcer->expects($this->once())
            ->method('denyAccessUnlessGranted')
            ->with()
            ->willThrowException(new DataAccessException);

        $NewsModel = new NewsModel($em, $localeProvider, $newsRepository, $userSubscriptionEnforcer);
        $NewsModel->find($id);
    }

    public function testFindLastNewsForDestination()
    {
        $destination = new Destination;
        $count = 4;

        $localeProvider = $this->getLocaleProviderMock();
        $em = $this->getEmMock();
        $newsRepository = $this->getNewsRepositoryMock();
        $newsRepository->expects($this->once())
          ->method('findLastNewsForDestination')
          ->with($destination, $count);

        $userSubscriptionEnforcer = $this->getUserSubscriptionEnforcer();
        $userSubscriptionEnforcer->expects($this->once())
          ->method('denyAccessUnlessGranted')
          ->with();

        $NewsModel = new NewsModel($em, $localeProvider, $newsRepository, $userSubscriptionEnforcer);
        $NewsModel->findLastNewsForDestination($destination, $count);
    }

    public function testFindLastNews()
    {
        $count = 4;
        $locale = 'en';

        $localeProvider = $this->getLocaleProviderMock();
        $localeProvider->expects($this->once())
            ->method('getLocale')
            ->with()
            ->willReturn($locale);
        $em = $this->getEmMock();
        $newsRepository = $this->getNewsRepositoryMock();
        $newsRepository->expects($this->once())
          ->method('findLastNews')
          ->with($locale, $count);

        $userSubscriptionEnforcer = $this->getUserSubscriptionEnforcer();
        $userSubscriptionEnforcer->expects($this->once())
          ->method('denyAccessUnlessGranted')
          ->with();

        $NewsModel = new NewsModel($em, $localeProvider, $newsRepository, $userSubscriptionEnforcer);
        $NewsModel->findLastNews($count);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getEmMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
          ->disableOriginalConstructor()
          ->getMock();
        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getLocaleProviderMock()
    {
        return $this->getMockBuilder('AppBundle\Locale\LocaleProvider')
          ->disableOriginalConstructor()
          ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getNewsRepositoryMock()
    {
        $mock = $this->getMockBuilder('AppBundle\Repository\NewsRepository')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getUserSubscriptionEnforcer()
    {
        $mock = $this->getMockBuilder('AppBundle\Service\UserSubscriptionEnforcer')
          ->disableOriginalConstructor()
          ->getMock();

        return $mock;
    }
}
