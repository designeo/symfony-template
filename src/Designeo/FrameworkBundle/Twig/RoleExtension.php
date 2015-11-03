<?php

namespace Designeo\FrameworkBundle\Twig;

/**
 * Class ActionExtension
 * @package Designeo\FrameworkBundle\Twig
 * @author  Adam Uhlíř <adam.uhlir@designeo.cz>
 */
class RoleExtension extends \Twig_Extension
{

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'app__role_extension';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('role', [$this, 'roleFunction']),
        ];
    }

    /**
     * @param string $roleName
     *
     * @return mixed
     * @throws \Exception
     */
    public function roleFunction($roleName)
    {

        if (!defined('\\Designeo\\FrameworkBundle\\Service\\RolesProvider::' . $roleName)) {
            throw new \Exception('Required Role is not defined in RolesProvider');
        }

        return constant('\\Designeo\\FrameworkBundle\\Service\\RolesProvider::' . $roleName);
    }
}