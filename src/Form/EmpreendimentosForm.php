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

class EmpreendimentosForm extends AbstractType
{
    /**
     * Builds the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $empreendimento = $options['data'];
        
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
            ->add('etapa_id', 'choice', array(
                'required' => true,
                'label' => 'Etapa da Obra',
                'choices' => array_key_exists('etapas_choices', $empreendimento) ? $empreendimento['etapas_choices'] : array(),
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
            ->add('nome', 'text', array(
                'required' => true,
                'label' => 'Nome do Empreendimento',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 2,
                        'max' => 200,
                    )),
                ),
            ))
            ->add('cidade_estado', 'text', array(
                'required' => true,
                'label' => 'Cidade/Estado',
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
            ->add('descricao', 'textarea', array(
                'required' => false,
                'label' => 'Descrição',
                'attr' => array(
                    'rows' => 4,
                ),
            ))
            ->add('logo_empreendimento', 'file', array(
                'required' => !array_key_exists('id', $empreendimento),
                'label' => 'Logo do Empreendimento',
                'constraints' => array(
                    new Assert\Image($image_valid),
                ),
            ))
            ->add('img_capa', 'file', array(
                'required' => !array_key_exists('id', $empreendimento),
                'label' => 'Imagem de Capa',
                'constraints' => array(
                    new Assert\Image($image_valid),
                ),
            ))
                        // NOVO: Campo para múltiplas imagens da galeria
                        ->add('galeria_imagens', 'file', array(
                            'required' => false,
                            'label' => 'Galeria de Imagens',
                            'mapped' => false, // Não mapeia para o objeto, processaremos manualmente
                            'multiple' => true, // Permitir múltiplos arquivos
                            'attr' => array(
                                'multiple' => true,
                                'accept' => 'image/*',
                                'class' => 'form-control-file'
                            ),
                            'constraints' => array(
                                new Assert\All(array(
                                    new Assert\Image(array(
                                        'maxSize' => '10M',
                                        'mimeTypes' => array('image/jpeg', 'image/png', 'image/gif', 'image/webp'),
                                        'mimeTypesMessage' => 'Por favor envie apenas imagens válidas (JPEG, PNG, GIF, WebP)'
                                    ))
                                ))
                            ),
                        ))
            ->add('destaque', 'checkbox', array(
                'required' => false,
                'label' => 'Empreendimento em Destaque',
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
            ->add('enabled', new StatusType())
        ;
    }

    /**
     * Configures the options for this type.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        //
    }

    /**
     * Returns the name of this type.
     */
    public function getName()
    {
        return 'empreendimentos';
    }
} 