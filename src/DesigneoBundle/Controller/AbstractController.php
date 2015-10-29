<?php

namespace DesigneoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AbstractController
 * @package AppBundle\Controller
 * @author Petr Fidler
 */
abstract class AbstractController extends Controller
{

    /**
     * @throws \Exception
     */
    final public function getRequest()
    {
        throw new \Exception('Deprecated method call');
    }

    /**
     * @throws \Exception
     */
    final public function getDoctrine()
    {
        throw new \Exception('Use model instead '.__METHOD__.' method.');
    }

    /**
     * @param string $id
     * @throws \Exception
     * @return null
     */
    final public function has($id)
    {
        throw new \Exception('Use DI instead '.__METHOD__.' method.');
    }

    /**
     * @param string $id
     * @throws \Exception
     * @return null
     */
    final public function get($id)
    {
        throw new \Exception('Use DI instead '.__METHOD__.' method.');
    }

}