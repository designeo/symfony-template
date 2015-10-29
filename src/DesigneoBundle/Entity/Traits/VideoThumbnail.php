<?php

namespace DesigneoBundle\Entity\Traits;

use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class VideoThumbnail
 *
 * Require VichUploader with declared mapping "video_thumbnails".
 * Also require Timestamp trait.
 *
 * @package AppBundle\Entity\Traits
 * @author  Adam Uhlíř <uhlir.a@gmail.com>
 */
trait VideoThumbnail
{
    /**
     * @var File $imageFile
     * @Vich\UploadableField(mapping="video_thumbnails", fileNameProperty="videoThumbnailName")
     */
    protected $videoThumbnailFile;

    /**
     * @var string
     *
     * @ORM\Column(name="video_thumbnail", type="string", length=255, nullable=true)
     */
    protected $videoThumbnailName;

    /**
     * Set videoThumbnail
     *
     * @param string $videoThumbnailName
     * @return $this
     */
    public function setVideoThumbnailName($videoThumbnailName)
    {
        $this->videoThumbnailName = $videoThumbnailName;

        return $this;
    }

    /**
     * Get videoThumbnail
     *
     * @return string
     */
    public function getVideoThumbnailName()
    {
        return $this->videoThumbnailName;
    }

    /**
     * @return File
     */
    public function getVideoThumbnailFile()
    {
        return $this->videoThumbnailFile;
    }

    /**
     * @param File $videoThumbnailFile
     * @return $this
     */
    public function setVideoThumbnailFile($videoThumbnailFile)
    {
        $this->videoThumbnailFile = $videoThumbnailFile;

        if ($videoThumbnailFile) {
            $this->touchModifiedAt();
        }

        return $this;
    }

    /**
     * Method called when photoFile was set-up.
     *
     * @return void
     */
    public abstract function touchModifiedAt();
}