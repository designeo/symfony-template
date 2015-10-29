<?php

namespace DesigneoBundle\Repository\Interfaces;

interface iSlugglableRepository
{
    public function slugIsUsed($slug, $locale);
}