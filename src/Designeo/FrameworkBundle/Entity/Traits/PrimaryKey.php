<?php

namespace Designeo\FrameworkBundle\Entity\Traits;

/**
 * Class PrimaryKey
 * @package Designeo\FrameworkBundle\Entity\Traits
 * @author  Ondřej Musil <omusil@gmail.com>
 */
trait PrimaryKey
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}