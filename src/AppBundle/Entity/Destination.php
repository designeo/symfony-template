<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\Photo;
use AppBundle\Entity\Traits\PrimaryKey;
use AppBundle\Entity\Traits\Timestamps;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Destination
 *
 * @ORM\Table(name="destinations")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DestinationRepository")
 * @Vich\Uploadable
 */
class Destination
{
    use PrimaryKey;
    use Translatable;
    use Photo;
    use Timestamps;

    /**
     * @var float
     *
     * @Assert\NotBlank(message="destination.lat.notBlank")
     * @Assert\Type(type="float", message="destination.lat.mustBeDecimal")
     * @ORM\Column(name="lat", type="float")
     */
    protected $lat;

    /**
     * @var float
     * @Assert\NotBlank(message="destination.lng.notBlank")
     * @Assert\Type(type="float", message="destination.lng.mustBeDecimal")
     * @ORM\Column(name="lng", type="float")
     */
    protected $lng;

    /**
     * @var string
     *
     *
     * @Assert\NotBlank(message="destination.code.notBlank")
     * @Assert\Length(max=255, maxMessage="destination.code.tooLong")
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=false)
     * @Assert\NotNull(message="destination.imageNotNull")
     *
     * @Assert\Length(max=255, maxMessage="destination.photo.tooLong")
     */
    protected $photoName;

    /**
     * @var File $imageFile
     * @Vich\UploadableField(mapping="destination_image", fileNameProperty="photoName")
     */
    protected $photoFile;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="favoriteDestinations")
     */
    protected $users;

    /**
     * Set lat
     *
     * @param float $lat
     * @return Destination
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return Destination
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Destination
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addUser(User $user)
    {
        if (!$this->posts->contains($user)) {
            $this->posts->add($user);
            $user->addFavoriteDestination($this);
        }

        return $this;
    }

    /**
     * Return label for administration purposes.
     * Usable for selectboxes etc., when there is chance, that translation for current user language
     * is not present.
     *
     * @return string
     */
    public function getLabelForAdmin()
    {
        if ($translation = $this->translate()) {
            return sprintf('%s (%s)', $translation->getName(), $this->getCode());
        } else {
            return sprintf('(%s)', $this->getCode());
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->translate()->getName();
    }
}
