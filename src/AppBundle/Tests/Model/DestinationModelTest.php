<?php

namespace AppBundle\Tests\Model;

use AppBundle\Model\DestinationModel;

/**
 * Class DestinationModelTest
 * @package AppBundle\Tests\Model
 */
class DestinationModelTest extends \PHPUnit_Framework_TestCase
{

    public function testPersistDestination()
    {
        $name = 'ěščřžý á_í.é;';
        $slug = 'escrzy-a-i-e';
        $locale = 'en';

        $translation = $this->getTranslationMock();
        $translation->expects($this->once())
            ->method('getName')
            ->willReturn($name);
        $translation->expects($this->once())
            ->method('setSlug')
            ->with($slug);

        $entity = $this->getEntityMock();
        $entity->expects($this->once())
            ->method('getTranslations')
            ->willReturn([$locale => $translation]);
        $entity->expects($this->once())
            ->method('translate')
            ->with($locale)
            ->willReturn($translation);

        $localeProvider = $this->getLocaleProviderMock();

        $em = $this->getEmMock();

        $destinationRepository = $this->getDestinationRepositoryMock();

        $slugService = $this->getSlugServiceMock();
        $slugService->expects($this->once())
          ->method('getSlug')
          ->with($name, $destinationRepository, $locale)
          ->willReturn($slug);

        $DestinationModel = new DestinationModel($destinationRepository, $em, $localeProvider, $slugService);
        $DestinationModel->persist($entity);
    }

    public function testUpdateDestination()
    {

        $entity = $this->getEntityMock();
        $localeProvider = $this->getLocaleProviderMock();
        $em = $this->getEmMock();
        $destinationRepository = $this->getDestinationRepositoryMock();

        $slugService = $this->getSlugServiceMock();
        $slugService->expects($this->never())
          ->method('getSlug');

        $DestinationModel = new DestinationModel($destinationRepository, $em, $localeProvider, $slugService);
        $DestinationModel->update($entity);
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
    private function getTranslationMock()
    {
        return $this->getMockBuilder('AppBundle\Entity\DestinationTranslation')
          ->disableOriginalConstructor()
          ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getEntityMock()
    {
        return $this->getMockBuilder('AppBundle\Entity\Destination')
          ->disableOriginalConstructor()
          ->getMock();
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
    private function getDestinationRepositoryMock()
    {
        $mock = $this->getMockBuilder('AppBundle\Repository\DestinationRepository')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSlugServiceMock()
    {
        $mock = $this->getMockBuilder('AppBundle\Service\SlugService')
          ->disableOriginalConstructor()
          ->getMock();

        return $mock;
    }
}
