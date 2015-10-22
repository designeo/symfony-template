<?php


namespace AppBundle\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller serving static pages
 *
 * @package AppBundle\Controller\Web
 *
 * @Route(service="app.web.static_controller")
 */
class StaticController extends AbstractWebController
{

    /**
     * @Route("/terms-of-use", name="app_static_termsofuse")
     * @Template("AppBundle:Web/Static:terms_of_use.html.twig")
     *
     * @return array
     */
    public function termsOfUseAction()
    {
        return [];
    }
}