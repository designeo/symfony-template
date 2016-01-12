<?php
/**
 * User: Jiri Fajman
 * Date: 18.1.16
 */

namespace AppBundle\Enum;


class PackageSizes
{
    const SMALL = 'small';
    const MEDIUM = 'medium';
    const LARGE = 'large';

    public static function toSelectBox()
    {
        return [
            static::SMALL => 'Small',
            static::MEDIUM => 'Medium',
            static::LARGE => 'Large',
        ];
    }

    /**
     * @param $index
     * @return string label for grid
     */
    public static function toLabel($index)
    {
        return static::toSelectBox()[$index];
    }

}