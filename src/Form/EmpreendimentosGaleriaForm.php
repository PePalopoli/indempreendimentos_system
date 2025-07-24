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

class EmpreendimentosGaleriaForm extends AbstractType
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
        $galeria = $options['data'];
        
        // Configurações para validação de imagem
        $image_valid = array(
            'mimeTypes' => array(
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp'
            ),
            'mimeTypesMessage' => 'Por favor, envie uma imagem válida (JPEG, PNG, GIF ou WebP)',
        );

        $builder
            ->add('empreendimento_id', 'hidden')
            ->add('imagem', 'file', array(
                'required' => !array_key_exists('id', $galeria),
                'label' => 'Imagem',
                'constraints' => array(
                    new Assert\Image($image_valid),
                ),
            ))
            ->add('titulo', 'text', array(
                'required' => false,
                'label' => 'Título da Imagem',
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => 100,
                    )),
                ),
            ))
            
            ->add('legenda', 'textarea', array(
                'required' => false,
                'label' => 'Legenda',
                'attr' => array(
                    'rows' => 3,
                ),
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => 255,
                    )),
                ),
            ))
            ->add('alt_text', 'text', array(
                'required' => false,
                'label' => 'Texto Alternativo (Alt)',
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => 120,
                    )),
                ),
            ))
            ->add('tipo', 'choice', array(
                'required' => true,
                'label' => 'Tipo da Imagem',
                'choices' => array(
                    'fachada' => 'Fachada',
                    'planta' => 'Planta',
                    'ambiente' => 'Ambiente',
                    'obra' => 'Obra',
                    'localizacao' => 'Localização',
                    'outros' => 'Outros',
                ),
                'constraints' => array(
                    new Assert\NotBlank(),
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
        return 'empreendimentos_galeria';
    }
} 