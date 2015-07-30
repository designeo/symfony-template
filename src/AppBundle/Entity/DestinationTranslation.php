<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\Sluggable;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * DestinationTranslation
 *
 * @ORM\Table(
 *     name="destination_translations",
 *     uniqueConstraints={
 *         @UniqueConstraint(
 *             name="destination_unique_locale_slug",
 *             columns={
 *                 "slug",
 *                 "locale"
 *             }
 *         )
 *      }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DestinationTranslationRepository")
 */
class DestinationTranslation
{
    use Translation;
    use Sluggable;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="destinationtranslation.name.notBlank"
     * )
     * @Assert\Length(
     *     min=1,
     *     max=255,
     *     minMessage="destinationtranslation.name.tooShort",
     *     maxMessage="destinationtranslation.name.tooLong"
     * )
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * Set name
     *
     * @param string $name
     * @return DestinationTranslation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
