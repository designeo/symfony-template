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
     * @Route("/about-us", name="app_static_aboutus")
     * @Template("AppBundle:Web/Static:about_us.html.twig")
     *
     * @return array
     */
    public function aboutUsAction()
    {
        return [];
    }

    /**
     * @Route("/how-it-works", name="app_static_howitworks")
     * @Template("AppBundle:Web/Static:how_it_works.html.twig")
     *
     * @return array
     */
    public function howItWorksAction()
    {
        return [];
    }

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