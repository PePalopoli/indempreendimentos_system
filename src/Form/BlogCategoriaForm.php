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

class BlogCategoriaForm extends AbstractType
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
        $categoria = $options['data'];

        $builder
            ->add('titulo', 'text', array(
                'required' => true,
                'label' => 'Título',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 2,
                        'max' => 120,
                    )),
                ),
            ))
            ->add('slug', 'text', array(
                'required' => true,
                'label' => 'Slug/URL',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 2,
                        'max' => 120,
                    )),
                ),
            ))
            ->add('descricao', 'textarea', array(
                'required' => false,
                'label' => 'Descrição',
                'attr' => array(
                    'rows' => 3,
                ),
            ))
            ->add('meta_title', 'text', array(
                'required' => false,
                'label' => 'Meta Title (SEO)',
                'attr' => array(
                    'maxlength' => 160,
                    'placeholder' => 'Máximo 160 caracteres',
                ),
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => 160,
                    )),
                ),
            ))
            ->add('meta_description', 'textarea', array(
                'required' => false,
                'label' => 'Meta Description (SEO)',
                'attr' => array(
                    'maxlength' => 255,
                    'rows' => 3,
                    'placeholder' => 'Máximo 255 caracteres',
                ),
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => 255,
                    )),
                ),
            ))
            ->add('meta_keywords', 'text', array(
                'required' => false,
                'label' => 'Meta Keywords (SEO)',
                'attr' => array(
                    'placeholder' => 'Palavras-chave separadas por vírgula',
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
        return 'blog_categoria';
    }
} 