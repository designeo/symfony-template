<?php

namespace Designeo\FrameworkBundle\Repository\Interfaces;

interface iSlugglableRepository
{
    public function slugIsUsed($slug, $locale);
}