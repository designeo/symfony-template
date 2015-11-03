<?php

namespace Designeo\FrameworkBundle\Entity\Traits;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserTracking
 * @package Designeo\FrameworkBundle\Entity\Traits
 * @author  Ondřej Musil <omusil@gmail.com>
 */
trait UserTracked
{
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle:User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=false)
     */
    protected $createdBy;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle:User")
     * @ORM\JoinColumn(name="modified_by", referencedColumnName="id", nullable=true)
     */
    protected $modifiedBy;

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return User
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * @param User $modifiedBy
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }
}