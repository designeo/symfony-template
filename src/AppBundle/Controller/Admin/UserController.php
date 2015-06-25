<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\Admin\UserType;
use AppBundle\GridManagers\Admin\UserManager;
use AppBundle\Model\UserException;
use AppBundle\Model\UserFacade;
use AppBundle\Service\RolesProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UsersController
 * @package AppBundle\Controller\IS
 *
 * @Sensio\Route("/admin/uzivatele-systemu", service="app.admin.user_controller")
 */
class UserController {

    private $templating;

    /** @var UserManager */
    private $userManager;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RolesProvider */
    private $rolesProvider;

    /** @var UserFacade */
    private $userFacade;

    public function __construct(EngineInterface $templating, FormFactoryInterface $formFactory, UserManager $userManager, RolesProvider $rolesProvider, UserFacade $userFacade)
    {
        $this->templating = $templating;
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
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
        return $this->templating->renderResponse(
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
        $userType = new UserType($this->rolesProvider);

        $form = $this->formFactory->create($userType, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userFacade->save($user);
                $this->addFlash('success', 'Uživatel byl úspěšně vytvořen');
                return $this->redirect($this->generateUrl('users_index'));
            }
            catch (UserException $e) {
                $form->get('email')->addError(new FormError($e->getMessage()));
            }
        }

        return $this->templating->renderResponse(
          'Admin/User/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Nový uživatel',
          ]
        );
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