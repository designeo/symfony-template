<?php

namespace AppBundle\Entity;

use DesigneoBundle\Entity\Traits\PrimaryKey;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *      indexes={
             @ORM\Index(name="name_idx", columns={"name"})
 *      },
 *      name="static_texts",
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StaticTextRepository")
 */
class StaticText
{
    use PrimaryKey;

    /**
     * Content of the static text - HTML & etc
     *
     * @var string
     * @ORM\Column(name="content", type="text", nullable=false)
     *
     * @Assert\NotBlank(message="staticText.content.notBlank")
     */
    private $content;

    /**
     * Human readable description of where is this text displayed and what it is good for.
     *
     * @var string
     * @ORM\Column(name="description", type="text", length=255, nullable=false)
     *
     * @Assert\Length(max=255, maxMessage="staticText.description.tooLong")
     * @Assert\NotBlank(message="staticText.description.notBlank")
     */
    private $description;

    /**
     * System name of this static text - used for fetching from template.
     *
     * @var string
     *
     * @ORM\Column(name="name", type="text", length=255, nullable=false)
     *
     * @Assert\Length(max=255, maxMessage="staticText.name.tooLong")
     * @Assert\NotBlank(message="staticText.name.notBlank")
     */
    private $name;

    /**
     * Title of this static text block
     *
     * @var string
     *
     * @ORM\Column(name="title", type="text", length=255, nullable=false)
     *
     * @Assert\Length(max=255, maxMessage="staticText.title.tooLong")
     * @Assert\NotBlank(message="staticText.title.notBlank")
     */
    private $title;

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return StaticText
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $name
     * @return StaticText
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $description
     * @return StaticText
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $content
     * @return StaticText
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
