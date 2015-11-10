<?php

namespace Designeo\FrameworkBundle\Entity\Traits;

use Designeo\FrameworkBundle\Helper\Strings;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Image
 *
 * Serves as support for Image attribute.
 * For better flexibility the property has to be defined directly
 * on the entity. This trait is composed only from setters&getters.
 * Trait require Timestamp trait.
 *
 * @package Designeo\FrameworkBundle\Entity\Traits
 * @author  Adam Uhlíř <uhlir.a@gmail.com>
 */
trait Image
{
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    protected $imageName;

    /**
     * @var File $imageFile
     * @Vich\UploadableField(mapping="image_image", fileNameProperty="imageName")
     */
    protected $imageFile;

    /**
     * Set image
     *
     * @param string $imageName
     *
     * @return $this
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     *
     * @return $this
     */
    public function setImageFile(File $imageFile)
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updateTimestamps();
        }

        return $this;
    }

    /**
     * Method called when imageFile was set-up.
     *
     * @return void
     */
    public abstract function updateTimestamps();

}