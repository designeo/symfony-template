<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;

/**
 * Class DefaultController
 * @package AppBundle\Controller\Admin
 * @author Ondřej Musil <omusil@gmail.com>
 */
class DefaultController extends AbstractAdminController
{
    /**
     * @Sensio\Route("/admin", name="admin_homepage")
     * @Sensio\Template("AppBundle:Admin/Default:index.html.twig")
     *
     * @return array
     */
    public function indexAction()
    {
        return [];
    }
}