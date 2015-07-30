<?php

namespace AppBundle\Form\Web;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


/**
 * Class ProfileFormType
 * @package AppBundle\Form\Web
 * @author  Adam Uhlíř <adam.uhlir@designeo.cz>
 */
class ProfileFormType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('firstName')
                ->add('lastName');

        $builder->remove('current_password');
        $builder->remove('username');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'web_profile';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'fos_user_profile';
    }
}