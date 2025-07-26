<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Palopoli\PaloSystem\Form\Type\StatusType;

class BeneficiosEmpreendimentosForm extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo', 'text', array(
                'required' => true,
                'label' => 'Título',
                'constraints' => array(
                    new Assert\NotBlank(array('message' => 'O título é obrigatório.')),
                    new Assert\Length(array(
                        'max' => 255,
                        'maxMessage' => 'O título não pode ter mais de 255 caracteres.'
                    ))
                ),
            ))
            ->add('svg_code', 'textarea', array(
                'required' => true,
                'label' => 'Código SVG',
                'attr' => array(
                    'rows' => 6,
                    'placeholder' => 'Cole aqui o código SVG do ícone do benefício'
                ),
                'constraints' => array(
                    new Assert\NotBlank(array('message' => 'O código SVG é obrigatório.')),
                ),
            ))
            ->add('enabled', new StatusType())
        ;
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        //
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'beneficios_empreendimentos';
    }
} 