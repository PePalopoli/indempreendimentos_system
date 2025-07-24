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

class ObraEtapasForm extends AbstractType
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
        $etapa = $options['data'];

        $builder
            ->add('titulo', 'text', array(
                'required' => true,
                'label' => 'Título da Etapa',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 2,
                        'max' => 100,
                    )),
                ),
            ))
            ->add('cor_hex', 'text', array(
                'required' => true,
                'label' => 'Cor (Hexadecimal)',
                'attr' => array(
                    'type' => 'color',
                    'class' => 'form-control',
                ),
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Regex(array(
                        'pattern' => '/^#[0-9A-Fa-f]{6}$/',
                        'message' => 'Por favor, insira uma cor válida em formato hexadecimal (#000000).',
                    )),
                ),
            ))
            ->add('descricao', 'textarea', array(
                'required' => false,
                'label' => 'Descrição',
                'attr' => array(
                    'rows' => 4,
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
        return 'obra_etapas';
    }
} 