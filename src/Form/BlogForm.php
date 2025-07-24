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

class BlogForm extends AbstractType
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
        $blog = $options['data'];

        $image_valid = array();
        

        $builder
            ->add('type', 'hidden', array(
                'required' => true,
                'label' => 'Type',
            ))
            ->add('title', 'text', array(
                'required' => true,
                'label' => 'Title (Max 120 caracteres)',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 2,
                        'max' => 120,
                    )),
                ),
            ))
            ->add('url', 'text', array(
                'required' => false,
                'label' => 'Url',
                'constraints' => array(
                ),
            ))
            ->add('body', 'textarea', array(
                'required' => true,
                'label' => 'Conteúdo',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 2,
                    )),
                ),
            ))
            ->add('subtitle', 'text', array(
                'required' => true,
                'label' => 'Subtitle (Max 120 caracteres)',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 2,
                        'max' => 120,
                    )),
                ),
            ))
            ->add('image', 'file', array(
                'required' => !array_key_exists('id', $blog),
                'label' => "Imagem",
                'constraints' => array(
                    new Assert\Image($image_valid),
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
        return 'blog';
    }
}
