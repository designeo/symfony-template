<?php

namespace DesigneoBundle\Helper;

/**
 * String helpers
 * @package AppBundle\Helper
 */
class String
{

    /**
     * @param string $string
     * @return string
     */
    public static function webalize($string)
    {
        $url = $string;
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
        $url = trim($url, '-');
        $url = iconv('utf-8', 'us-ascii//TRANSLIT', $url);
        $url = strtolower($url);
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url);

        return $url;
    }
}
