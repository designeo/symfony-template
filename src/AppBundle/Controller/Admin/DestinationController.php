<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Destination;
use AppBundle\Form\Admin\DestinationType;
use AppBundle\GridDataSources\Admin\DestinationDataSource;
use AppBundle\Exception\DestinationException;
use AppBundle\Model\DestinationModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DestinationController
 *
 * @Route("/admin/destinations", service="app.admin.destination_controller")
 */
class DestinationController extends AbstractController
{

    /**
     * @var DestinationModel
     */
    private $destinationModel;

    /**
     * @var DestinationDataSource
     */
    private $destinationDataSource;

    /**
     * @param DestinationDataSource $destinationDataSource
     * @param DestinationModel      $destinationModel
     */
    public function __construct(
        DestinationDataSource $destinationDataSource,
        DestinationModel $destinationModel)
    {
        $this->destinationModel = $destinationModel;
        $this->destinationDataSource = $destinationDataSource;
    }

    /**
     * @Route("/", name="admin_destination_index")
     * @Method({"GET"})
     * @Template("AppBundle:Admin/Destination:index.html.twig")
     *
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        return $this->getDestinationGridData($request);
    }

    /**
     * @Route("/new", name="admin_destination_new")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:Admin/Destination:edit.html.twig")
     *
     * @return Response|array
     */
    public function newAction()
    {
        $destination = new Destination();
        $form = $this->createCreateForm($destination);

        return [
            'form' => $form->createView(),
            'title' => 'admin.destination.actions.create',
        ];
    }

    /**
     * @Route("/create", name="admin_destination_create")
     * @Method({"POST"})
     * @Template("AppBundle:Admin/Destination:edit.html.twig")
     *
     * @param Request $request
     * @return Response|array
     */
    public function createAction(Request $request)
    {
        $destination = new Destination();
        $form = $this->createCreateForm($destination);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $this->destinationModel->persist($destination);
                $this->addFlash('success', 'admin.destination.messages.recordCreated');

                return $this->redirectToRoute('admin_destination_index');
            } catch (DestinationException $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return [
          'form' => $form->createView(),
          'title' => 'admin.destination.actions.create',
        ];
    }

    /**
     * @Route("/{id}/edit", name="admin_destination_edit")
     * @Method({"GET", "POST"})
     * @ParamConverter("id", class="AppBundle:Destination")
     * @Template("AppBundle:Admin/Destination:edit.html.twig")
     *
     * @param Destination $destination
     * @return Response
     */
    public function editAction(Destination $destination)
    {
        $form = $this->createEditForm($destination);

        return [
          'form' => $form->createView(),
          'title' => 'admin.destination.actions.edit',
        ];
    }

    /**
     * @Route("/{id}/update", name="admin_destination_update")
     * @Method({"PUT"})
     * @ParamConverter("id", class="AppBundle:Destination")
     * @Template("AppBundle:Admin/Destination:edit.html.twig")
     *
     * @param Request     $request
     * @param Destination $destination
     * @return Response
     */
    public function updateAction(Request $request, Destination $destination)
    {
        $form = $this->createEditForm($destination);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $this->destinationModel->update($destination);
                $this->addFlash('success', 'admin.destination.messages.recordUpdated');

                return $this->redirectToRoute('admin_destination_index');
            } catch (DestinationException $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return [
          'form' => $form->createView(),
          'title' => 'admin.destination.actions.edit',
        ];
    }

    /**
     * @Route("/{id}/remove", name="admin_destination_remove")
     * @Method({"DELETE"})
     * @ParamConverter("id", class="AppBundle:Destination")
     *
     * @param Destination $destination
     * @return Response
     */
    public function removeAction(Destination $destination)
    {
        try {
            $this->destinationModel->remove($destination);
            $this->addFlash('success', 'admin.destination.messages.recordDeleted');
        } catch (DestinationException $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('admin_destination_index');
    }

    /**
     * @param Destination $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Destination $entity)
    {
        $form = $this->createForm(new DestinationType(), $entity, [
            'action' => $this->generateUrl('admin_destination_create'),
            'method' => 'POST',
            'image_required' => true,
        ]);

        $form->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);

        return $form;
    }

    /**
     * @param Destination $entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Destination $entity)
    {
        $form = $this->createForm(new DestinationType(), $entity, [
            'action' => $this->generateUrl('admin_destination_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
            'image_required' => false,
        ]);

        $form->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);

        return $form;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getDestinationGridData(Request $request)
    {
        $gridManager = $this->destinationDataSource;
        $defaultSort = 't.name';
        $gridManager->setDefaultDataFromRequest($request, $defaultSort);

        // parameters
        $name = $request->get('name');
        $gridManager->setName($name);

        return $gridManager->getDataForGrid();
    }
}
