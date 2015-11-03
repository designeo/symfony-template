<?php

namespace Designeo\FrameworkBundle\Entity\Traits;

use Designeo\FrameworkBundle\Helper\String;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Photo
 *
 * Serves as support for Photo attribute.
 * For better flexibility the property has to be defined directly
 * on the entity. This trait is composed only from setters&getters.
 * Trait require Timestamp trait.
 *
 * @package Designeo\FrameworkBundle\Entity\Traits
 * @author  Adam Uhlíř <uhlir.a@gmail.com>
 */
trait Photo
{
    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    protected $photoName;

    /**
     * @var File $imageFile
     * @Vich\UploadableField(mapping="photo_image", fileNameProperty="photoName")
     */
    protected $photoFile;

    /**
     * Set photo
     *
     * @param string $photoName
     *
     * @return $this
     */
    public function setPhotoName($photoName)
    {
        $this->photoName = $photoName;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhotoName()
    {
        return $this->photoName;
    }

    /**
     * @return File
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $photoFile
     *
     * @return $this
     */
    public function setPhotoFile(File $photoFile)
    {
        $this->photoFile = $photoFile;
        if ($photoFile) {
            $this->touchModifiedAt();
        }

        if ($photoFile instanceof UploadedFile) {
            $originalName = $photoFile->getClientOriginalName();
        } else {
            $originalName = $photoFile->getFilename();
        }

        $this->setPhotoName($this->generatePhotoFileName($originalName, $photoFile));

        return $this;
    }

    /**
     * Method called when photoFile was set-up.
     *
     * @return void
     */
    public abstract function touchModifiedAt();

    /**
     *
     * @param string $originalFileName
     * @param File   $photoFile
     *
     * @return string
     */
    protected function generatePhotoFileName($originalFileName, File $photoFile)
    {
        $fileNameParts = explode('.', $originalFileName);
        $extension = array_pop($fileNameParts);
        $filename = String::webalize(implode('.', $fileNameParts));

        return sprintf('%s.%s', $filename, $extension);
    }
}