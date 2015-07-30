<?php

namespace AppBundle\Twig;


/**
 * Class ActionExtension
 * @package AppBundle\Twig
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

        if (!defined('\\AppBundle\\Service\\RolesProvider::' . $roleName)) {
            throw new \Exception('Required Role is not defined in RolesProvider');
        }

        return constant('\\AppBundle\\Service\\RolesProvider::' . $roleName);
    }
}