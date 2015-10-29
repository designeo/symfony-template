<?php

namespace DesigneoBundle\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait FindByLocaleTrait
{
    /**
     * @param string $slug
     * @param string $locale
     * @return object
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByLocalizedSlug($slug, $locale)
    {
        $qb = $this->createQueryBuilder('E');
        $qb
          ->addSelect('E, T')
          ->join('E.translations', 'T')
          ->andWhere('T.locale = :locale')
          ->andWhere('T.slug = :slug')
          ->setParameter('locale', $locale)
          ->setParameter('slug', $slug);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @param string $alias
     *
     * @return QueryBuilder
     */
    abstract public function createQueryBuilder($alias);
}