<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Designeo\FrameworkBundle\Service\RolesProvider;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @package AppBundle\Entity
 * @author Ondřej Musil <omusil@gmail.com>
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    use Timestampable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank(message="user.firstname.notBlank")
     * @Assert\Length(max=45, maxMessage="user.firstname.tooLong")
     * @ORM\Column(name="first_name", type="string", length=45, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="user.lastname.notBlank")
     * @Assert\Length(max=45, maxMessage="user.lastname.tooLong")
     * @ORM\Column(name="last_name", type="string", length=45, nullable=true)
     */
    protected $lastName;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return sprintf('%s %s', $this->getLastName(), $this->getFirstName());
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        parent::setEmail($email);
        parent::setUsername($email);

        return $this;
    }

    /**
     * @param string $emailCanonical
     * @return $this
     */
    public function setEmailCanonical($emailCanonical)
    {
        parent::setEmailCanonical($emailCanonical);
        parent::setUsernameCanonical($emailCanonical);

        return $this;
    }

    /**
     * Avoiding baseUser methods like hasRole etc
     * @param string $role
     */
    public function setUserRole($role)
    {
        $this->addRole($role);
    }

    /**
     * @return string
     */
    public function getUserRole()
    {
        $roles = $this->getRoles();

        return $roles[0];
    }

    /**
     * @return string
     */
    public function getUserRoleName()
    {
        return RolesProvider::getRoleName($this->getUserRole());
    }

}