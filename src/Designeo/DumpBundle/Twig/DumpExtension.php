<?php

namespace Designeo\DumpBundle\Twig;

/**
 * Class DumpExtension
 * @package Designeo\DumpBundle\Twig
 * @author  Petr Fidler
 */
class DumpExtension extends \Twig_Extension
{

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'designeo__dump_extension';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('dump', [$this, 'dumpFunction']),
        ];
    }

    /**
     * @param mixed $var
     */
    public function dumpFunction($var)
    {

    }
}