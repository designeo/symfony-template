<?php
/**
 * User: Jiri Fajman
 * Date: 18.1.16
 */

namespace AppBundle\Enum;


class PackageStates
{
    const ACTIVE = 'active';
    const ASSIGNED = 'assigned';
    const COMPLETED = 'completed';
    const DELETED = 'deleted';

    public static function toSelectBox()
    {
        return [
            static::ACTIVE => 'Active',
            static::ASSIGNED => 'Assigned',
            static::COMPLETED => 'Completed',
            static::DELETED => 'Deleted',
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