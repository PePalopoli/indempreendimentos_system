<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                1 => 'Habilitado',
                0 => 'Desabilitado',
            ),
            'required' => true,
            'label' => 'Status',
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'status';
    }
}
