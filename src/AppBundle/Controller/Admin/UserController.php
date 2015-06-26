<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\Admin\UserType;
use AppBundle\GridManagers\Admin\UserManager;
use AppBundle\Model\UserException;
use AppBundle\Model\UserFacade;
use AppBundle\Service\RolesProvider;

/**
 * Class UsersController
 * @package AppBundle\Controller\IS
 *
 * @Sensio\Route("/admin/uzivatele-systemu", service="app.admin.user_controller")
 */
class UserController extends AbstractController {

    /** @var UserManager */
    private $userManager;

    /** @var RolesProvider */
    private $rolesProvider;

    /** @var UserFacade */
    private $userFacade;

    public function __construct(UserManager $userManager, RolesProvider $rolesProvider, UserFacade $userFacade)
    {
        $this->userManager = $userManager;
        $this->rolesProvider = $rolesProvider;
        $this->userFacade = $userFacade;
    }

    /**
     * @Sensio\Route("/", name="users_index")
     * @Sensio\Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render(
          'Admin/User/index.html.twig',
          $this->getUserGridData($request)
        );
    }

    /**
     * @Sensio\Route("/novy", name="users_new")
     * @Sensio\Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $user = new User;
        $form = $this->userForm($user, $request);
        if ($form instanceof Response) {
            return $form;
        }

        return $this->render(
          'Admin/User/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Nový uživatel',
          ]
        );
    }

    /**
     * @Sensio\Route("/{id}/editace", name="users_edit")
     * @Sensio\Method({"GET", "POST"})
     * @Sensio\ParamConverter("id", class="AppBundle:User")
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function editAction(Request $request, User $user)
    {
        $formOptions = ['password_required' => false];
        $form = $this->userForm($user, $request, $formOptions);
        if ($form instanceof Response) {
            return $form;
        }

        return $this->render(
          'Admin/User/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Editace uživatele',
          ]
        );
    }

    private function userForm(User $user, Request $request, array $formOptions = [])
    {
        $userType = new UserType($this->rolesProvider);

        $form = $this->createForm($userType, $user, $formOptions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userFacade->save($user);
                $this->addFlash('success', 'Uživatel byl úspěšně uložen');
                return $this->redirect($this->generateUrl('users_index'));
            }
            catch (UserException $e) {
                $form->get('email')->addError(new FormError($e->getMessage()));
            }
        }
        return $form;
    }

    /**
     * @Sensio\Route("/{id}/smazat", name="users_remove")
     * @Sensio\Method({"GET"})
     * @Sensio\ParamConverter("id", class="AppBundle:User")
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function removeAction(User $user)
    {
        try {
            $this->userFacade->remove($user);
            $this->addFlash('success', 'Uživatel byl úspěšně vymazán');
        } catch (UserException $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('users_index'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getUserGridData(Request $request)
    {
        $gridManager = $this->userManager;

        // parameters
        $page = $request->get('page', 1);

        $name = $request->get('name');
        $email = $request->get('email');
        $role = $request->get('role');
        $enabled = $request->get('enabled');
        $lastLogin = $request->get('lastLogin');

        $gridManager->setName($name);
        $gridManager->setEmail($email);
        $gridManager->setRole($role);
        $gridManager->setEnabled($enabled);
        $gridManager->setLastLogin($lastLogin);

        $gridManager->setPage($page);
        $gridManager->sortBy($request->get('sortBy', 'U.lastName'), $request->get('sortDir'));

        return [
          'data' => $gridManager->getData(),
          'page' => $gridManager->getPage(),
          'max_page' => $gridManager->getMaxPage(),
          'filter' => [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'enabled' => $enabled,
            'lastLogin' => $lastLogin,
            'sortDir' => $gridManager->getNextSortDir(),
          ],
        ];
    }

}