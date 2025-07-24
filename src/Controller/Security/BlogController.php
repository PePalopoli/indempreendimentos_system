<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Palopoli\PaloSystem\Form\BlogForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class BlogController extends ContainerAware
{
    /**
     * Lista.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(array $blog_type)
    {
        $blog = $this->db()->fetchAll('SELECT * FROM `blog` WHERE `type` = ?', array($blog_type['id']));

        $this->get('db')->close();
        $this->db()->close();

        return $this->render('list.twig', array(
            'data' => $blog,
            'blog_type' => $blog_type,
        ));
    }

    /**
     * Adicionar.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request, array $blog_type)
    {
        $blog = array(
            'blog_type' => $blog_type,
            'type' => $blog_type['id'],
        );

        $form = $this->createForm(new BlogForm(), $blog, array(
            'action' => $this->get('url_generator')->generate('s_blog_create', array('blog_type' => $blog_type['id'])),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                if ($data['image'] instanceof UploadedFile) {
                    $image = $data['image'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/blog');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $data['image'] = $image_name;
                }

                $data['url'] = $this->get('slugify')->slugify($data['url']);

                $data['subtitle'] = substr($data['subtitle'], 0, 120);

                try {
                    $insert_query = 'INSERT INTO `blog` (`title`,`subtitle`, `url`, `body`,`image`, `enabled`, `type`, `created_at`, `updated_at`) VALUES (?,?,?, ?, ?, ?, ?, NOW(), NOW())';
                    $this->db()->executeUpdate($insert_query, array($data['title'],$data['subtitle'], $data['url'], $data['body'],$data['image'], $data['enabled'], $data['type']));

                    $this->flashMessage()->add('success', array('message' => 'Adicionado com sucesso.'));

                    return $this->redirect('s_blog', array('blog_type' => $blog_type['id']));
                } catch (UniqueConstraintViolationException $e) {
                    $this->flashMessage()->add('danger', array('message' => 'Url já está em uso.'));
                }
            }
        }

        $this->get('db')->close();
        $this->db()->close();

        return $this->render('create.twig', array(
            'form' => $form->createView(),
            'blog_type' => $blog_type,
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
    public function editAction(Request $request, array $blog_type, $id)
    {
        $blog = $this->get('db')->fetchAssoc('SELECT * FROM `blog` WHERE `id` = ? LIMIT 1;', array($id));

        if ($blog === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais a pagina não foi encontrada.'));

            return $this->redirect('s_blog', array('blog_type' => $blog_type['id']));
        }

        $image_name = $blog['image'];
        unset($blog['image']);

        $form = $this->createForm(new BlogForm(), $blog, array(
            'action' => $this->get('url_generator')->generate('s_blog_edit', array('blog_type' => $blog_type['id'], 'id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                if ($data['image'] instanceof UploadedFile) {
                    $image = $data['image'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/blog');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                }
                $data['image'] = $image_name;
                $data['subtitle'] = substr($data['subtitle'], 0, 120);

                $data['url'] = $this->get('slugify')->slugify($data['url']);
                try {
                    $update_query = 'UPDATE `blog` SET `title` = ?,`subtitle` = ?, `url` = ?, `body` = ?, `image` = ?, `enabled` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1';
                    $this->get('db')->executeUpdate($update_query, array($data['title'],$data['subtitle'], $data['url'], $data['body'],$data['image'], $data['enabled'], $data['id']));

                    $this->flashMessage()->add('success', array('message' => 'Editado com sucesso.'));

                    return $this->redirect('s_blog', array('blog_type' => $blog_type['id']));
                } catch (UniqueConstraintViolationException $e) {
                    $this->flashMessage()->add('danger', array('message' => 'Url já está em uso.'));
                }
            }
        }

        $this->get('db')->close();
        $this->db()->close();

        return $this->render('edit.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'blog_type' => $blog_type,
        ));
    }

    /**
     * Deletar.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, array $blog_type)
    {
        $id = $request->request->get('id');
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `blog` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais não foi encontrado.'));
        } else {
            $this->get('db')->executeUpdate('DELETE FROM `blog` WHERE `id` = ?', array($id));

            $this->flashMessage()->add('success', array('message' => 'Deletado com sucesso.'));
        }

        $this->get('db')->close();
        $this->db()->close();

        return $this->redirect('s_blog', array('blog_type' => $blog_type['id']));
    }
}
