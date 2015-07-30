<?php

namespace AppBundle\Controller\Web;

use AppBundle\Entity\Destination;
use AppBundle\Model\DestinationModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Class DestinationController
 * @package AppBundle\Controller\Web
 * @author  Adam Uhlíř <adam.uhlir@designeo.cz>
 *
 * @Route("/destination", service="app.web.destination_controller")
 */
class DestinationController extends AbstractWebController
{

    /**
     * @var DestinationModel
     */
    private $destinationModel;

    /**
     * DestinationController constructor.
     *
     * @param DestinationModel $destinationModel
     * @param ExpertModel      $expertModel
     * @param NewsModel        $newsModel
     */
    public function __construct(
        DestinationModel $destinationModel
    )
    {
        $this->destinationModel = $destinationModel;
    }

    /**
     * @Route("/{slug}", name="web_destination_detail")
     * @Method({"GET"})
     * @ParamConverter(
     *     "destination",
     *     class="AppBundle:Destination",
     *     options={
     *         "useTranslatable": true,
     *         "repository_method" = "findByLocalizedSlug",
     *     }
     * )
     * @Template("")
     *
     * @param Destination $destination
     *
     * @return array
     */
    public function detailAction(Destination $destination)
    {
        return [
            'destination' => $destination,
        ];
    }
}