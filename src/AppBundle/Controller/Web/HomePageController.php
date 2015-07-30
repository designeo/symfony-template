<?php

namespace AppBundle\Controller\Web;

use AppBundle\Model\DestinationModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class HomePageController
 *
 * @package AppBundle\Controller\Web
 *
 * @Route("", service="app.web.homepage_controller")
 */
class HomePageController extends AbstractWebController
{
    /**
     * @var DestinationModel
     */
    private $destinationModel;

    /**
     * @param DestinationModel $destinationModel
     * @param NewsModel        $newsModel
     * @param PostModel        $postModel
     */
    public function __construct(DestinationModel $destinationModel)
    {
        $this->destinationModel = $destinationModel;
    }

    /**
     * @Route("/", name="web_homepage_index")
     * @Template()
     * @Method({"GET"})
     * @return array
     */
    public function indexAction()
    {
        return [
            'destinations' => $this->destinationModel->findAllWithTranslation(),
        ];
    }
}
