<?php

namespace AppBundle\Repository\Interfaces;

interface iSlugglableRepository
{
    public function slugIsUsed($slug, $locale);
}