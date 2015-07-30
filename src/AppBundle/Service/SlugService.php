<?php

namespace AppBundle\Service;

use AppBundle\Repository\Interfaces\iSlugglableRepository;
use Cocur\Slugify\Slugify;

/**
 * Class SlugService
 * @package AppBundle\Service
 * @author Petr Fidler <petr.fidler@designeo.cz>
 */
class SlugService
{

    /**
     * @var Slugify
     */
    private $slugify;

    /**
     * @param Slugify $slugify
     */
    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function getSlug($name, iSlugglableRepository $repository, $locale = NULL)
    {
        $slug = $this->slugify->slugify($name);
        $baseSlug = $slug;
        $i = 0;
        while ($repository->slugIsUsed($slug, $locale)) {
            $i++;
            $slug = $baseSlug . '-' . $i;
        }

        return $slug;
    }

}