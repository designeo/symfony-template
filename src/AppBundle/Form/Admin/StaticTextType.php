<?php

namespace AppBundle\Form\Admin;

use Designeo\FrameworkBundle\Form\Traits\EntityQueryBuilderCallbacks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Designeo\FrameworkBundle\Form\Traits\DateOptions;

/**
 * FormType for StaticText
 * @package AppBundle\Form\Admin
 */
class StaticTextType extends AbstractType
{

    use EntityQueryBuilderCallbacks;
    use DateOptions;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content');
        $builder->add('description', 'text');
        $builder->add('title', 'text');
        $builder->add('name', 'text');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_static_text';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\StaticText',
            'password_required' => true,
        ));
    }
}
