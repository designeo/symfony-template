<?php

namespace AppBundle\Form\Admin;

use AppBundle\Form\Traits\EntityQueryBuilderCallbacks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DestinationType
 * @package AppBundle\Form\Admin
 */
class DestinationType extends AbstractType
{

    use EntityQueryBuilderCallbacks;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations', [
                'fields' => [
                    'name' => [
                      'label' => 'admin.destination.entity.name',
                    ],
                    'slug' => [
                      'display' => false,
                    ],
                ]
            ])
            ->add('lat', 'number')
            ->add('lng', 'number')
            ->add('code', 'text')
            ->add('photoFile', 'vich_image', [
                'required' => $options['image_required'],
                'allow_delete' => false,
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_destination';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Destination',
            'image_required' => true,
            'cascade_validation' => true,
        ));
    }

}
