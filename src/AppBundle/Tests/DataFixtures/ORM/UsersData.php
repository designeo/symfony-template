<?php
namespace AppBundle\Tests\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Users data for tests
 * @package AppBundle\DataFixtures
 * @author Ondřej Musil <omusil@gmail.com>
 */
class UsersData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    protected $superAdminMail = 'admin@designeo.cz';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userRepo = $manager->getRepository('AppBundle:User');
        $superAdmin = $userRepo->findOneBy(array('email' => $this->superAdminMail));

        if (!$superAdmin) {
            $this->addSuperAdmin($manager);
        }
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }

    private function addSuperAdmin(ObjectManager $manager)
    {
        $user = new User();

        $user->setUsername($this->superAdminMail);
        $user->setEmail($this->superAdminMail);
        $user->setSuperAdmin(true);
        $user->setEnabled(true);

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user);

        $user->setPassword($encoder->encodePassword('secret', $user->getSalt()));
        $user->setFirstName('Petr');

        $manager->persist($user);
        $manager->flush();
    }
}
