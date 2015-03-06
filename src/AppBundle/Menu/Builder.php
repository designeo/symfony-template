<?php

namespace AppBundle\Menu;

use AppBundle\Entity\User;
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
    public function adminMenu(FactoryInterface $factory, array $options)
    {
        $root = $factory->createItem('root');

        $user = $this->addSignedInuser($root);

        return $root;
    }

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $root = $factory->createItem('root');

        $user = $this->addSignedInuser($root);

        return $root;
    }

    private function addSignedInuser(ItemInterface $root)
    {
        $user = $this->getSignedInUser();

        if ($user) {
            $user = $root->addChild($user->getEmail());
            $user->addChild('web.menu.web', array('route' => 'homepage'));
            $user->addChild('web.menu.admin', array('route' => 'admin_homepage'));
            $user->addChild('web.menu.logout', array('route' => 'fos_user_security_logout'));
        } else {
            $root->addChild('web.menu.login', array(
                'route' => 'fos_user_security_login'
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
     * @return EntityManager
     */
    private function getEm()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }
}