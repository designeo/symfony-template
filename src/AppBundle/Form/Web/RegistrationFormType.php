<?php

namespace AppBundle\Form\Web;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\EqualTo;

/**
 * Class RegistrationFormType
 * @package AppBundle\Form\Web
 */
class RegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName')
                ->add('lastName');

        // ToS == Terms of Service
        $builder->add('agreeToS', 'checkbox', [
            'mapped' => false,
            'required' => true,
            'constraints' => [
                new EqualTo(['value' => true])
            ]
        ]);

        $builder->remove('username');
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'fos_user_registration';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'web_registration';
    }
}
