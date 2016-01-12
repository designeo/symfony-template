<?php

namespace AppBundle\Form\Admin;

use Designeo\FrameworkBundle\Form\Traits\EntityQueryBuilderCallbacks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Designeo\FrameworkBundle\Form\Traits\DateOptions;

/**
 * FormType for Package
 * @package AppBundle\Form\Admin
 */
class PackageType extends AbstractType
{

    use EntityQueryBuilderCallbacks;
    use DateOptions;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_package';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Package',
        ));
    }
}
