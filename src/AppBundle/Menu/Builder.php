<?php

namespace AppBundle\Menu;

use AppBundle\Entity\User;
use AppBundle\Service\RolesProvider;
use Doctrine\ORM\EntityManager;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Knp\Menu\FactoryInterface;

/**
 * Class AdminBuilder
 * @package AppBundle\Menu
 * @author Ondřej Musil <omusil@gmail.com>
 */
class Builder extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array            $options
     * @return ItemInterface
     */
    public function adminMenu(FactoryInterface $factory, array $options)
    {
        $root = $factory->createItem('root');

        $user = $this->addSignedInuser($root);

        return $root;
    }

    /**
     * @param FactoryInterface $factory
     * @param array            $options
     * @return ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $root = $factory->createItem('root');

        $user = $this->addSignedInuser($root);

        return $root;
    }

    /**
     * @param ItemInterface $root
     * @return User|ItemInterface|null
     */
    private function addSignedInuser(ItemInterface $root)
    {
        $user = $this->getSignedInUser();

        if ($user) {
            $user = $root->addChild($user->getFullName());
            $user->addChild('web.menu.web', array('route' => 'web_homepage_index'));
            $user->addChild('web.menu.profile', array('route' => 'web_profile_detail'));
            if ($this->getAuthorizationChecker()->isGranted([RolesProvider::ROLE_SUPER_ADMIN], $user)) {
                $user->addChild('web.menu.admin', array('route' => 'admin_homepage'));
            }
            $user->addChild('web.menu.logout', array('route' => 'fos_user_security_logout'));
        } else {
            $root->addChild('web.menu.login', array(
                'route' => 'fos_user_security_login'
            ));
            $root->addChild('web.menu.register', array(
                'route' => 'fos_user_registration_register'
            ));
        }

        return $user;
    }

    /**
     * @return null|User
     */
    private function getSignedInUser()
    {
        $token = $this->container->get('security.token_storage')->getToken();
        $user = $token->getUser();

        if ($user instanceof User) {
            return $user;
        }

        return null;
    }

    /**
     * @return \Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    private function getAuthorizationChecker()
    {
        return $this->container->get('security.authorization_checker');
    }

    /**
     * @return EntityManager
     */
    private function getEm()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }
}