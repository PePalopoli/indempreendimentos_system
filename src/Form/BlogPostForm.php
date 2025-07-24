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

class BlogPostForm extends AbstractType
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
        $post = $options['data'];
        $image_valid = array();

        $builder
            ->add('categoria_id', 'choice', array(
                'required' => true,
                'label' => 'Categoria',
                'choices' => array_key_exists('categorias_choices', $post) ? $post['categorias_choices'] : array(),
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
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
            ->add('slug', 'text', array(
                'required' => true,
                'label' => 'Slug/URL',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 2,
                        'max' => 200,
                    )),
                ),
            ))
            ->add('subtitulo', 'text', array(
                'required' => false,
                'label' => 'Subtítulo',
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => 300,
                    )),
                ),
            ))
            ->add('resumo', 'textarea', array(
                'required' => false,
                'label' => 'Resumo',
                'attr' => array(
                    'rows' => 4,
                ),
            ))
            ->add('conteudo', 'textarea', array(
                'required' => true,
                'label' => 'Conteúdo',
                'attr' => array(
                    'class' => 'ckeditor',
                    'rows' => 10,
                ),
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
            ->add('imagem_capa', 'file', array(
                'required' => !array_key_exists('id', $post),
                'label' => 'Imagem de Capa',
                'constraints' => array(
                    new Assert\Image($image_valid),
                ),
            ))
            ->add('autor', 'text', array(
                'required' => false,
                'label' => 'Autor',
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => 100,
                    )),
                ),
            ))
            ->add('data_publicacao', 'datetime', array(
                'required' => false,
                'label' => 'Data de Publicação',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('destaque', 'checkbox', array(
                'required' => false,
                'label' => 'Post em Destaque',
            ))
            ->add('permitir_comentarios', 'checkbox', array(
                'required' => false,
                'label' => 'Permitir Comentários',
            ))
            // Campos SEO
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
            ->add('og_title', 'text', array(
                'required' => false,
                'label' => 'Open Graph Title',
                'attr' => array(
                    'maxlength' => 100,
                ),
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => 100,
                    )),
                ),
            ))
            ->add('og_description', 'textarea', array(
                'required' => false,
                'label' => 'Open Graph Description',
                'attr' => array(
                    'maxlength' => 200,
                    'rows' => 3,
                ),
                'constraints' => array(
                    new Assert\Length(array(
                        'max' => 200,
                    )),
                ),
            ))
            ->add('og_image', 'file', array(
                'required' => false,
                'label' => 'Open Graph Image',
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
        return 'blog_post';
    }
} 