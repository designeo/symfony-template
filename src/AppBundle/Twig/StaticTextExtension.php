<?php

namespace AppBundle\Twig;

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
        $this->assertAllTextsWereFound($names, $texts);

        foreach ($texts as $text) {
            $this->staticTextCache->set($text->getName(), $text);
        }
    }

    /**
     * @param string $name
     * @return StaticText
     * @throws StaticTextException
     */
    public function getStaticText($name)
    {
        if (!$text = $this->staticTextCache->get($name)) {
            throw new StaticTextException(sprintf('Static text called "%s" was not preloaded.', $name));
        }

        return $text;
    }

    public function getName()
    {
        return 'app__static_text_extension';
    }

    /**
     * @param string[]     $lookedFor
     * @param StaticText[] $result
     * @throws StaticTextException
     */
    private function assertAllTextsWereFound($lookedFor, $result)
    {
        if (count($lookedFor) === count($result)) {
            return;
        }

        foreach ($lookedFor as $needle) {
            $found = false;

            foreach ($result as $recordFound) {
                if ($recordFound->getName() === $needle) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                throw new StaticTextException(sprintf('Static text called "%s" was not found.', $needle));
            }
        }
    }
}
