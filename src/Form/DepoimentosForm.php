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

class DepoimentosForm extends AbstractType
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
        $depoimento = $options['data'];
        $image_valid = array();

        $builder
            ->add('titulo', 'text', array(
                'required' => true,
                'label' => 'Título',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 2,
                        'max' => 200,
                    )),
                ),
            ))
            ->add('texto', 'textarea', array(
                'required' => true,
                'label' => 'Texto do Depoimento',
                'attr' => array(
                    'rows' => 6,
                ),
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
            ->add('youtube_url', 'url', array(
                'required' => true,
                'label' => 'URL do YouTube',
                'attr' => array(
                    'placeholder' => 'https://www.youtube.com/watch?v=...',
                ),
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Url(),
                    new Assert\Regex(array(
                        'pattern' => '/youtube\.com\/watch\?v=|youtu\.be\//',
                        'message' => 'Por favor, insira uma URL válida do YouTube.',
                    )),
                ),
            ))
            ->add('autor_nome', 'text', array(
                'required' => false,
                'label' => 'Nome do Autor',
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => 100,
                    )),
                ),
            ))
            ->add('autor_cargo', 'text', array(
                'required' => false,
                'label' => 'Cargo do Autor',
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => 100,
                    )),
                ),
            ))
            ->add('autor_empresa', 'text', array(
                'required' => false,
                'label' => 'Empresa do Autor',
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => 100,
                    )),
                ),
            ))
            ->add('foto_autor', 'file', array(
                'required' => false,
                'label' => 'Foto do Autor',
                'constraints' => array(
                    new Assert\Image($image_valid),
                ),
            ))            
            ->add('destaque', new StatusType())
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
        return 'depoimentos';
    }
} 