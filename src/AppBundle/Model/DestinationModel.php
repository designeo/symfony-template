<?php

namespace AppBundle\Model;

use AppBundle\Entity\Destination;
use AppBundle\Exception\DestinationException;
use AppBundle\Locale\LocaleProvider;
use AppBundle\Repository\DestinationRepository;
use AppBundle\Service\SlugService;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Model for manipulating Destination entity
 *
 * @package AppBundle\Model
 */
class DestinationModel
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LocaleProvider
     */
    private $localeProvider;

    /**
     * @var DestinationRepository
     */
    private $repository;

    /**
     * @var SlugService
     */
    private $slugService;

    /**
     * @param DestinationRepository  $destinationRepository
     * @param EntityManagerInterface $entityManager
     * @param LocaleProvider         $localeProvider
     * @param SlugService            $slugService
     */
    public function __construct(
        DestinationRepository $destinationRepository,
        EntityManagerInterface $entityManager,
        LocaleProvider $localeProvider,
        SlugService $slugService
    )
    {
        $this->entityManager = $entityManager;
        $this->localeProvider = $localeProvider;
        $this->repository = $destinationRepository;
        $this->slugService = $slugService;
    }

    /**
     * @param Destination $destination
     * @throws \Exception
     * @throws DestinationException
     */
    public function persist(Destination $destination)
    {
        foreach ($destination->getTranslations() as $locale => $translation) {
            $slug = $this->slugService->getSlug($translation->getName(), $this->repository, $locale);
            $destination->translate($locale)->setSlug($slug);
        }
        $this->save($destination);
    }

    /**
     * @param Destination $destination
     * @throws DestinationException
     */
    public function update(Destination $destination)
    {
        $this->save($destination);
    }

    /**
     * @param Destination $user
     * @throws DestinationException
     */
    public function remove(Destination $user)
    {
        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        } catch (DBALException $e) {
            throw new DestinationException('admin.destination.messagesFailedToDeleteRecord');
        }
    }

    /**
     * @param Destination $destination
     * @throws DestinationException
     */
    private function save(Destination $destination)
    {
        try {
            $this->entityManager->persist($destination);
            $destination->mergeNewTranslations();
            $this->entityManager->flush();
        } catch (DBALException $e) {
            throw new DestinationException('admin.destination.messages.failedToCreateRecord');
        }
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @return array
     */
    public function findAllWithTranslation()
    {
        return $this->repository->findAllWithTranslation($this->localeProvider->getLocale());
    }

    /**
     * @param string $locale
     * @param string $direction ASC|DESC
     * @return array
     */
    public function findAllOrderedByLocale($locale, $direction = 'ASC')
    {
        return $this->repository->findAllOrderedByLocale($locale, $direction);
    }
}
