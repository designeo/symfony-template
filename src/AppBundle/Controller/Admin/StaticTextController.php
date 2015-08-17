<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\StaticText;
use AppBundle\Exception\StaticTextException;
use AppBundle\Form\Admin\StaticTextType;
use AppBundle\GridDataSources\Admin\StaticTextDataSource;
use AppBundle\Model\StaticTextModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class StaticTextController
 *
 * @Route("/admin/staticText", service="app.admin.staticText_controller")
 */
class StaticTextController extends AbstractController
{
    /**
     * @var StaticTextModel
     */
    protected $staticTextModel;

    /**
     * @var StaticTextDataSource
     */
    protected $staticTextDataSource;


    /**
     * @param StaticTextDataSource $staticTextDataSource
     * @param StaticTextModel $staticTextModel
     */
    public function __construct(StaticTextDataSource $staticTextDataSource, StaticTextModel $staticTextModel)
    {
        $this->staticTextModel = $staticTextModel;
        $this->staticTextDataSource = $staticTextDataSource;
    }

    /**
     * @Route("/", name="admin_static_text_index")
     * @Method({"GET"})
     * @Template("AppBundle:Admin/StaticText:index.html.twig")
     *
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        return $this->getStaticTextGridData($request);
    }

    /**
     * Displays a form to create a new StaticText entity.
     *
     * @Route("/new", name="admin_static_text_new")
     * @Method("GET")
     * @Template("AppBundle:Admin/StaticText:edit.html.twig")
     * @return array
     */
    public function newAction()
    {
        $staticText = new StaticText();
        $form = $this->createCreateForm($staticText);

        return array(
            'title' => 'admin.staticText.actions.create',
            'entity' => $staticText,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route("/create", name="admin_static_text_create")
     * @Method({"POST"})
     * @Template("AppBundle:Admin/StaticText:edit.html.twig")
     *
     * @param Request $request
     * @return array
     */
    public function createAction(Request $request)
    {
        $staticText = new StaticText();
        $form = $this->createCreateForm($staticText);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $this->staticTextModel->persist($staticText);
                $this->addFlash('success', 'admin.staticText.messages.saved');

                return $this->redirectToRoute('admin_static_text_index');
            } catch (StaticTextException $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return [
            'form' => $form->createView(),
            'entity' => $staticText,
            'title' => 'admin.staticText.actions.create',
        ];
    }

    /**
     * @Route("/{id}/edit", name="admin_static_text_edit")
     * @Method({"GET"})
     * @ParamConverter("id", class="AppBundle:StaticText")
     * @Template("AppBundle:Admin/StaticText:edit.html.twig")
     *
     * @param StaticText $staticText
     * @return array
     */
    public function editAction(StaticText $staticText)
    {
        $form = $this->createEditForm($staticText);

        return [
            'form' => $form->createView(),
            'entity' => $staticText,
            'title' => 'admin.staticText.actions.edit',
        ];
    }

    /**
     * @Route("/{id}", name="admin_static_text_update")
     * @Method({"PUT"})
     * @ParamConverter("id", class="AppBundle:StaticText")
     * @Template("AppBundle:Admin/StaticText:edit.html.twig")
     *
     * @param Request $request
     * @param StaticText $staticText
     * @return array
     */
    public function updateAction(Request $request, StaticText $staticText)
    {
        $form = $this->createEditForm($staticText);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $this->staticTextModel->update($staticText);
                $this->addFlash('success', 'admin.staticText.messages.saved');

                return $this->redirectToRoute('admin_static_text_index');
            } catch (StaticTextException $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return [
            'form' => $form->createView(),
            'title' => 'admin.staticText.actions.edit',
        ];
    }

    /**
     * @Route("/{id}/remove", name="admin_static_text_remove")
     * @Method({"DELETE"})
     * @ParamConverter("id", class="AppBundle:StaticText")
     *
     * @param StaticText $staticText
     * @return Response
     */
    public function removeAction(StaticText $staticText)
    {
        try {
            $this->staticTextModel->remove($staticText);
            $this->addFlash('success', 'admin.staticText.messages.removed');
        } catch (StaticTextException $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('admin_static_text_index');
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getStaticTextGridData(Request $request)
    {
        $gridManager = $this->staticTextDataSource;
        $defaultSort = 'S.id';
        $gridManager->setDefaultDataFromRequest($request, $defaultSort);

        $gridManager->extractParameters($request);

        $data = $gridManager->getDataForGrid();

        return $data;
    }

    /**
     * Creates a form to create a StaticText entity.
     * @param StaticText $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(StaticText $entity)
    {
        $form = $this->createForm(new StaticTextType(), $entity, [
            'action' => $this->generateUrl('admin_static_text_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);

        return $form;
    }

    /**
     * Creates a form to edit a StaticText entity.
     * @param StaticText $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(StaticText $entity)
    {
        $form = $this->createForm(new StaticTextType(), $entity, [
            'action' => $this->generateUrl('admin_static_text_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);

        return $form;
    }
}
