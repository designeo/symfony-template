<?php

namespace DesigneoBundle\Form\Traits;

use Doctrine\ORM\EntityRepository;

/**
 * Factory methods for requently used query builders for entity form fields
 *
 * @author Tomáš Polívka 
 */
trait EntityQueryBuilderCallbacks
{

    /**
     * Creates query_builder callback for choice ordering
     *
     * Example:
     *  use EntityQueryBuilderCallbacks;
     *
     *  ...
     *
     *  $builder->add('bank', null, [
     *     'query_builder' => $this->getOrderCallback('code')
     *  ]);
     *
     * @param string $column
     * @param string $order asc|desc, default: 'asc'
     *
     * @return callable
     */
    protected function getOrderCallback($column, $order = 'asc')
    {
        return function(EntityRepository $repo) use ($column, $order) {
            return $repo->createQueryBuilder('E')->orderBy(sprintf('E.%s', $column), $order);
        };
    }
}