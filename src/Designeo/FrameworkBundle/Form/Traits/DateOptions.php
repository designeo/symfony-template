<?php

namespace Designeo\FrameworkBundle\Form\Traits;

/**
 * Class DateOptions
 * @package Designeo\FrameworkBundle\Form\Traits
 */
trait DateOptions
{
    /**
     * @param bool $required
     * @return array
     */
    public function getDateOptions($required = false)
    {
        return [
            'required' => $required,
            'input' => 'datetime',
            'format' => 'dd.MM.yyyy',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => [
                'class' => 'datepicker'
            ]
        ];
    }
}