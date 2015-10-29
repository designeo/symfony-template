<?php

namespace DesigneoBundle\Entity\Traits;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Sluggable
 * @package AppBundle\Entity\Traits
 * @author  Adam Uhlíř <adam.uhlir@designeo.cz>
 */
trait Sluggable
{
    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", nullable=false, length=255)
     */
    protected $slug;

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return Sluggable
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

}