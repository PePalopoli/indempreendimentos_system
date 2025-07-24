<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Palopoli\PaloSystem\Form\BlogPostForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class BlogPostController extends ContainerAware
{
    /**
     * Lista.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $posts = $this->db()->fetchAll('
            SELECT bp.*, bc.titulo as categoria_titulo 
            FROM `blog_post` bp 
            INNER JOIN `blog_categoria` bc ON bp.categoria_id = bc.id 
            ORDER BY bp.data_publicacao DESC, bp.created_at DESC
        ');

        $this->get('db')->close();
        $this->db()->close();

        return $this->render('list.twig', array(
            'data' => $posts,
        ));
    }

    /**
     * @return array
     */
    private function getChoiceCategorias()
    {
        $categorias = array();

        $categorias_fetch = $this->db()->fetchAll('SELECT `id`, `titulo` FROM `blog_categoria` WHERE `enabled` = 1 ORDER BY `titulo`');

        foreach ($categorias_fetch as $categoria) {
            $categorias[$categoria['id']] = $categoria['titulo'];
        }

        return $categorias;
    }

    /**
     * Extrair ID do YouTube da URL.
     */
    private function extractYouTubeId($url)
    {
        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            return $matches[1];
        } elseif (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Adicionar.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $post = array(
            'categorias_choices' => $this->getChoiceCategorias(),
        );

        $form = $this->createForm(new BlogPostForm(), $post, array(
            'action' => $this->get('url_generator')->generate('s_blog_post_create'),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $data['slug'] = $this->get('slugify')->slugify($data['slug']);

                // Upload da imagem de capa
                if ($data['imagem_capa'] instanceof UploadedFile) {
                    $image = $data['imagem_capa'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/blog');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $data['imagem_capa'] = $image_name;
                }

                // Upload da imagem Open Graph
                if ($data['og_image'] instanceof UploadedFile) {
                    $image = $data['og_image'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/blog/og');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $data['og_image'] = $image_name;
                }

                try {
                    $insert_query = 'INSERT INTO `blog_post` 
                        (`categoria_id`, `titulo`, `slug`, `subtitulo`, `resumo`, `conteudo`, `imagem_capa`, `autor`, `data_publicacao`, `destaque`, `permitir_comentarios`, `meta_title`, `meta_description`, `meta_keywords`, `og_title`, `og_description`, `og_image`, `enabled`, `created_at`, `updated_at`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())';
                    $this->db()->executeUpdate($insert_query, array(
                        $data['categoria_id'], $data['titulo'], $data['slug'], $data['subtitulo'], $data['resumo'], $data['conteudo'], $data['imagem_capa'], $data['autor'], $data['data_publicacao'], $data['destaque'], $data['permitir_comentarios'], $data['meta_title'], $data['meta_description'], $data['meta_keywords'], $data['og_title'], $data['og_description'], $data['og_image'], $data['enabled']
                    ));

                    $this->flashMessage()->add('success', array('message' => 'Post adicionado com sucesso.'));

                    return $this->redirect('s_blog_post');
                } catch (UniqueConstraintViolationException $e) {
                    $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas já existe um post com este slug.'));
                }
            }
        }

        return $this->render('create.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Editar.
     *
     * @param Request $request
     * @param mixed   $id
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, $id)
    {
        $post = $this->get('db')->fetchAssoc('SELECT * FROM `blog_post` WHERE `id` = ? LIMIT 1;', array($id));

        if ($post === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas o post não foi encontrado.'));

            return $this->redirect('s_blog_post');
        }

        $image_capa_name = $post['imagem_capa'];
        $og_image_name = $post['og_image'];
        unset($post['imagem_capa'], $post['og_image']);

        $post['categorias_choices'] = $this->getChoiceCategorias();

        $form = $this->createForm(new BlogPostForm(), $post, array(
            'action' => $this->get('url_generator')->generate('s_blog_post_edit', array('id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $data['slug'] = $this->get('slugify')->slugify($data['slug']);

                // Upload da imagem de capa
                if ($data['imagem_capa'] instanceof UploadedFile) {
                    $image = $data['imagem_capa'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/blog');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $image_capa_name = $image_name;
                }

                // Upload da imagem Open Graph
                if ($data['og_image'] instanceof UploadedFile) {
                    $image = $data['og_image'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/blog/og');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $og_image_name = $image_name;
                }

                $data['imagem_capa'] = $image_capa_name;
                $data['og_image'] = $og_image_name;

                try {
                    $update_query = 'UPDATE `blog_post` SET 
                        `categoria_id` = ?, `titulo` = ?, `slug` = ?, `subtitulo` = ?, `resumo` = ?, `conteudo` = ?, `imagem_capa` = ?, `autor` = ?, `data_publicacao` = ?, `destaque` = ?, `permitir_comentarios` = ?, `meta_title` = ?, `meta_description` = ?, `meta_keywords` = ?, `og_title` = ?, `og_description` = ?, `og_image` = ?, `enabled` = ?, `updated_at` = NOW() 
                        WHERE `id` = ? LIMIT 1';
                    $this->get('db')->executeUpdate($update_query, array(
                        $data['categoria_id'], $data['titulo'], $data['slug'], $data['subtitulo'], $data['resumo'], $data['conteudo'], $data['imagem_capa'], $data['autor'], $data['data_publicacao'], $data['destaque'], $data['permitir_comentarios'], $data['meta_title'], $data['meta_description'], $data['meta_keywords'], $data['og_title'], $data['og_description'], $data['og_image'], $data['enabled'], $data['id']
                    ));

                    $this->flashMessage()->add('success', array('message' => 'Post editado com sucesso.'));

                    return $this->redirect('s_blog_post');
                } catch (UniqueConstraintViolationException $e) {
                    $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas já existe um post com este slug.'));
                }
            }
        }

        return $this->render('edit.twig', array(
            'form' => $form->createView(),
            'id' => $id,
        ));
    }

    /**
     * Deletar.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $id = $request->request->get('id');
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `blog_post` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas o post não foi encontrado.'));
        } else {
            // Remover imagens do filesystem
            $fs = new Filesystem();
            
            if ($row_sql['imagem_capa']) {
                $file_name = web_path('upload/blog').'/'.$row_sql['imagem_capa'];
                if ($fs->exists($file_name)) {
                    $fs->remove($file_name);
                }
            }

            if ($row_sql['og_image']) {
                $file_name = web_path('upload/blog/og').'/'.$row_sql['og_image'];
                if ($fs->exists($file_name)) {
                    $fs->remove($file_name);
                }
            }

            // Deletar galeria associada
            $galeria = $this->db()->fetchAll('SELECT * FROM `blog_galeria` WHERE `post_id` = ?', array($id));
            foreach ($galeria as $item) {
                $file_name = web_path('upload/blog/galeria').'/'.$item['imagem'];
                if ($fs->exists($file_name)) {
                    $fs->remove($file_name);
                }
            }
            $this->get('db')->executeUpdate('DELETE FROM `blog_galeria` WHERE `post_id` = ?', array($id));

            $this->get('db')->executeUpdate('DELETE FROM `blog_post` WHERE `id` = ?', array($id));

            $this->flashMessage()->add('success', array('message' => 'Post deletado com sucesso.'));
        }

        return $this->redirect('s_blog_post');
    }
} 