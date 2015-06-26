<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AbstractController
 * @package AppBundle\Controller
 * @author Ondřej Musil <omusil@gmail.com>
 */
abstract class AbstractController extends Controller
{

    final public function getRequest()
    {
        throw new \Exception('Deprecated method call');
    }

    final public function getDoctrine()
    {
        throw new \Exception('Use model instead '.__METHOD__.' method.');
    }

    final public function has($id)
    {
        throw new \Exception('Use DI instead '.__METHOD__.' method.');
    }

    final public function get($id)
    {
        throw new \Exception('Use DI instead '.__METHOD__.' method.');
    }

}