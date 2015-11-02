<?php

namespace AppBundle\Controller\Admin;

use AppBundle\GridDataSources\Admin\UserDataSource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\Admin\UserType;
use AppBundle\Exception\UserException;
use AppBundle\Model\UserModel;

/**
 * Class UsersController
 * @package AppBundle\Controller\IS
 *
 * @Route("/admin/user", service="app.admin.user_controller")
 */
class UserController extends AbstractAdminController
{

    /** @var UserDataSource */
    private $userDataSource;

    /** @var RolesProvider */
    private $rolesProvider;

    /** @var UserModel */
    private $userModel;

    /**
     * @param UserDataSource $userDataSource
     * @param RolesProvider  $rolesProvider
     * @param UserModel      $userModel
     */
    public function __construct(UserDataSource $userDataSource, RolesProvider $rolesProvider, UserModel $userModel)
    {
        $this->userDataSource = $userDataSource;
        $this->rolesProvider = $rolesProvider;
        $this->userModel = $userModel;
    }

    /**
     * @Route("/", name="admin_user_index")
     * @Method({"GET"})
     * @Template("AppBundle:Admin/User:index.html.twig")
     *
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        return $this->getUserGridData($request);
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method("GET")
     * @Template("AppBundle:Admin/User:edit.html.twig")
     * @return array
     */
    public function newAction()
    {
        $user = new User;
        $form   = $this->createCreateForm($user);

        return array(
          'title' => 'admin.users.actions.create',
          'entity' => $user,
          'form'   => $form->createView(),
        );
    }

    /**
     * @Route("/create", name="admin_user_create")
     * @Method({"POST"})
     * @Template("AppBundle:Admin/User:edit.html.twig")
     *
     * @param Request $request
     * @return array
     */
    public function createAction(Request $request)
    {
        $user = new User;
        $form = $this->createCreateForm($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $this->userModel->persist($user);
                $this->addFlash('success', 'admin.users.messages.saved');

                return $this->redirectToRoute('admin_user_index');
            } catch (UserException $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return [
            'form' => $form->createView(),
            'entity' => $user,
            'title' => 'admin.users.actions.create',
        ];
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Method({"GET"})
     * @ParamConverter("id", class="AppBundle:User")
     * @Template("AppBundle:Admin/User:edit.html.twig")
     *
     * @param User $user
     * @return array
     */
    public function editAction(User $user)
    {
        $form = $this->createEditForm($user);

        return [
            'form' => $form->createView(),
            'entity' => $user,
            'title' => 'admin.users.actions.edit',
        ];
    }

    /**
     * @Route("/{id}", name="admin_user_update")
     * @Method({"PUT"})
     * @ParamConverter("id", class="AppBundle:User")
     * @Template("AppBundle:Admin/User:edit.html.twig")
     *
     * @param Request $request
     * @param User    $user
     * @return array
     */
    public function updateAction(Request $request, User $user)
    {
        $form = $this->createEditForm($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $this->userModel->update($user);
                $this->addFlash('success', 'admin.users.messages.saved');

                return $this->redirectToRoute('admin_user_index');
            } catch (UserException $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return [
          'form' => $form->createView(),
          'title' => 'admin.users.actions.edit',
        ];
    }

    /**
     * @Route("/{id}/remove", name="admin_user_remove")
     * @Method({"DELETE"})
     * @ParamConverter("id", class="AppBundle:User")
     *
     * @param User $user
     * @return Response
     */
    public function removeAction(User $user)
    {
        try {
            $this->userModel->remove($user);
            $this->addFlash('success', 'admin.users.messages.removed');
        } catch (UserException $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('admin_user_index');
    }

    /**
     * @Route("/{id}/detail", name="admin_user_detail")
     * @Method({"GET"})
     * @ParamConverter("id", class="AppBundle:User")
     * @Template("AppBundle:Admin/User:detail.html.twig")
     *
     * @param User $user
     * @return array
     */
    public function detailAction(User $user)
    {
        return [
          'user' => $user,
          'title' => 'admin.users.actions.detail',
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getUserGridData(Request $request)
    {
        $gridManager = $this->userDataSource;
        $defaultSort = 'U.lastName';
        $gridManager->setDefaultDataFromRequest($request, $defaultSort);

        // parameters
        $name = $request->get('name');
        $email = $request->get('email');
        $role = $request->get('role');
        $enabled = $request->get('enabled');

        $gridManager->setName($name);
        $gridManager->setEmail($email);
        $gridManager->setRole($role);
        $gridManager->setEnabled($enabled);

        $data = $gridManager->getDataForGrid();
        $data['filterData']['roles'] = $this->rolesProvider->getRoleNames();

        return $data;
    }

    /**
     * Creates a form to create a User entity.
     * @param User $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType($this->rolesProvider), $entity, [
            'action' => $this->generateUrl('admin_user_create'),
            'method' => 'POST',
            'password_required' => true,
        ]);

        $form->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);

        return $form;
    }

    /**
     * Creates a form to edit a User entity.
     * @param User $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType($this->rolesProvider), $entity, [
            'action' => $this->generateUrl('admin_user_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
            'password_required' => false,
        ]);

        $form->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);

        return $form;
    }
}
