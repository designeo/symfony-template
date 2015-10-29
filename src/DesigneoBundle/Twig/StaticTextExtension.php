<?php

namespace DesigneoBundle\Twig;

use AppBundle\Entity\StaticText;
use AppBundle\Exception\StaticTextException;
use AppBundle\Model\StaticTextModel;
use Doctrine\Common\Collections\ArrayCollection;

class StaticTextExtension extends \Twig_Extension
{

    /**
     * @var ArrayCollection
     */
    private $staticTextCache;

    /**
     * @var StaticTextModel
     */
    private $staticTextModel;

    /**
     * @param StaticTextModel $staticTextModel
     */
    public function __construct(StaticTextModel $staticTextModel)
    {
        $this->staticTextCache = new ArrayCollection();
        $this->staticTextModel = $staticTextModel;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('preloadStaticTexts', [$this, 'preloadStaticTexts']),
            new \Twig_SimpleFunction('getStaticText', [$this, 'getStaticText']),
        ];
    }

    /**
     * @param string[] $names
     */
    public function preloadStaticTexts($names)
    {
        $texts = $this->staticTextModel->findManyByName($names);

        foreach ($texts as $text) {
            $this->staticTextCache->set($text->getName(), $text);
        }
    }

    /**
     * @param string $name
     * @return StaticText
     */
    public function getStaticText($name)
    {
        if (!$text = $this->staticTextCache->get($name)) {
            return $this->staticTextModel->createDummyStaticText($name);
        }

        return $text;
    }

    public function getName()
    {
        return 'app__static_text_extension';
    }
}
