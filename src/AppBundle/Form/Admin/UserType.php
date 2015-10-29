<?php

namespace AppBundle\Form\Admin;

use DesigneoBundle\Form\Traits\EntityQueryBuilderCallbacks;
use DesigneoBundle\Service\RolesProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DesigneoBundle\Form\Traits\DateOptions;

/**
 * FormType for Users
 * @package AppBundle\Form\Admin
 */
class UserType extends AbstractType
{

    use EntityQueryBuilderCallbacks;
    use DateOptions;

    /**
     * @var RolesProvider
     */
    protected $rolesProvider;

    /**
     * @param RolesProvider $roleProvider
     */
    public function __construct(RolesProvider $roleProvider)
    {
        $this->rolesProvider = $roleProvider;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email');
        $builder->add('plainPassword', 'password', ['required' => $options['password_required']]);
        $builder->add('firstName');
        $builder->add('lastName');
        $builder->add('enabled', null, ['required' => false]);

        $builder->add('userRole', 'choice', [
            'choices' => $this->rolesProvider->getRolesForUserForm(),
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_user';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'password_required' => true,
        ));
    }

}
