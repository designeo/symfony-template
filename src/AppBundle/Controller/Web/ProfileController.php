<?php

namespace AppBundle\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class ProfileController
 * @package AppBundle\Controller\Web
 * @author  Adam Uhlíř <adam.uhlir@designeo.cz>
 *
 * @Route("/profile", service="app.web.profile_controller")
 */
class ProfileController extends AbstractWebController
{

    /**
     * @Route("/", name="web_profile_detail")
     * @Security("is_granted('ROLE_USER')")
     * @Template()
     *
     * @return array
     */
    public function detailAction()
    {
        return [
            'user' => $this->getUser()
        ];
    }
}