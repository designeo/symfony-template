<?php

namespace DesigneoBundle\Entity\Traits;

/**
 * Class PrimaryKey
 * @package AppBundle\Entity\Traits
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