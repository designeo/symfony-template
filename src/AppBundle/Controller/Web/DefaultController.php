<?php

namespace AppBundle\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;

/**
 * Class DefaultController
 * @package AppBundle\Web\Controller
 * @author  Ondřej Musil <omusil@gmail.com>
 *
 * @Sensio\Route(service="app.controller.web.default")
 */
class DefaultController extends AbstractWebController
{
    /**
     * @Sensio\Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('Web/Default/index.html.twig');
    }
}
