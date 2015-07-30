<?php

namespace AppBundle\Tests\Service;

use AppBundle\Model\DestinationModel;
use AppBundle\Service\SlugService;
use Cocur\Slugify\Slugify;

/**
 * Class SlugServiceTest
 * @package AppBundle\Tests\Service
 */
class SlugServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testSlugIsNotUsed()
    {
        $name = 'ěščřžý á_í.é;';
        $expectedSlug = 'escrzy-a-i-e';
        $locale = 'en';

        $repository = $this->getRepositoryMock();
        $repository->expects($this->once())
            ->method('slugIsUsed')
            ->with($expectedSlug, $locale)
            ->willReturn(false);

        $slugify = new Slugify();

        $slugService = new SlugService($slugify);
        $slug = $slugService->getSlug($name, $repository, $locale);

        $this->assertSame($expectedSlug, $slug);
    }

    public function testSlugIsUsed()
    {
        $name = 'ěščřžý á_í.é;';
        $firstSlug = 'escrzy-a-i-e';
        $expectedSlug = 'escrzy-a-i-e-1';
        $locale = 'en';

        $repository = $this->getRepositoryMock();
        $repository->expects($this->at(0))
          ->method('slugIsUsed')
          ->with($firstSlug, $locale)
          ->willReturn(true);
        $repository->expects($this->at(1))
          ->method('slugIsUsed')
          ->with($expectedSlug, $locale)
          ->willReturn(false);

        $slugify = new Slugify();

        $slugService = new SlugService($slugify);
        $slug = $slugService->getSlug($name, $repository, $locale);

        $this->assertSame($expectedSlug, $slug);
    }

    public function testSlugIsUsed2times()
    {
        $name = 'ěščřžý á_í.é;';
        $firstSlug = 'escrzy-a-i-e';
        $secondSlug = 'escrzy-a-i-e-1';
        $expectedSlug = 'escrzy-a-i-e-2';
        $locale = 'en';

        $repository = $this->getRepositoryMock();
        $repository->expects($this->at(0))
          ->method('slugIsUsed')
          ->with($firstSlug, $locale)
          ->willReturn(true);
        $repository->expects($this->at(1))
          ->method('slugIsUsed')
          ->with($secondSlug, $locale)
          ->willReturn(true);
        $repository->expects($this->at(2))
          ->method('slugIsUsed')
          ->with($expectedSlug, $locale)
          ->willReturn(false);

        $slugify = new Slugify();

        $slugService = new SlugService($slugify);
        $slug = $slugService->getSlug($name, $repository, $locale);

        $this->assertSame($expectedSlug, $slug);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getRepositoryMock()
    {
        $mock = $this->getMockBuilder('AppBundle\Repository\Interfaces\iSlugglableRepository')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
}
