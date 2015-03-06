<?php

namespace AppBundle\Entity\Traits;

/**
 * Class Timestamps
 * @package AppBundle\Entity\Traits
 * @author  Ondřej Musil <omusil@gmail.com>
 */
trait Timestamps
{
    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     */
    protected $modifiedAt;

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function touchModifiedAt()
    {
        $this->modifiedAt = new \DateTime();
    }

}