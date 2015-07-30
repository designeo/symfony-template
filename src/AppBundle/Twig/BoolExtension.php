<?php

namespace AppBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Twig extension providing filters like |renderBool
 * @package AppBundle\Twig
 */
class BoolExtension extends \Twig_Extension
{

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('renderBool', [$this, 'renderBool']),
        );
    }

    /**
     * @param string $value
     * @return string
     */
    public function renderBool($value)
    {
        if ((bool) $value) {
            return $this->translator->trans('general.boolean.yes');
        }

        return $this->translator->trans('general.boolean.no');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mbool_extension';
    }
}