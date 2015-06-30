<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package AppBundle\Controller\Admin
 * @author Ondřej Musil <omusil@gmail.com>
 */
class DefaultController extends AbstractAdminController
{
    /**
     * @Sensio\Route("/admin", name="admin_homepage")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Admin/Default:index.html.twig');
    }
}