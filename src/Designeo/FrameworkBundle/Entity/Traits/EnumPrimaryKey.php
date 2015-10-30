<?php

namespace Designeo\FrameworkBundle\Entity\Traits;

/**
 * Class PrimaryKey
 * @package Designeo\FrameworkBundle\Entity\Traits
 * @author  Tomáš Polívka <tomas.polivka@designeo.cz>
 */
trait EnumPrimaryKey
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}