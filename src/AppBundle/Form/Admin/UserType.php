<?php

namespace AppBundle\Form\Admin;

use AppBundle\Form\Traits\EntityQueryBuilderCallbacks;
use AppBundle\Service\RolesProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{

    use EntityQueryBuilderCallbacks;

    /**
     * @var RolesProvider
     */
    protected $rolesProvider;

    function __construct(RolesProvider $roleProvider) {
        $this->rolesProvider = $roleProvider;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', ['label' => 'Email']);
        $builder->add('plainPassword', 'password', ['label' => 'Heslo', 'required' => $options['password_required']]);
        $builder->add('firstName', NULL, ['label' => 'Křestní jméno']);
        $builder->add('lastName', NULL, ['label' => 'Příjmení']);
        $builder->add('enabled', null, ['label' => 'Aktivní', 'required' => false]);
        $builder->add('save', 'submit', ['label' => 'Odeslat', 'attr' => ['class' => 'btn btn-primary']]);

        $builder->add('roles', 'choice', [
          'choices' => $this->rolesProvider->getRolesForUserForm(),
          'mapped' => false,
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
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'password_required' => true,
            'roles' => []
        ));
    }

}
