<?php

namespace AppBundle\Twig;

class BoolExtension extends \Twig_Extension
{
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
     * @param $value
     * @return string
     */
    public function renderBool($value)
    {
        if ((bool) $value) {
            return 'Ano';
        }
        return 'Ne';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mbool_extension';
    }
}