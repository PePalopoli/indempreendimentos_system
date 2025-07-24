<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Palopoli\PaloSystem\Form\BlogCategoriaForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class BlogCategoriaController extends ContainerAware
{
    /**
     * Lista.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $categorias = $this->db()->fetchAll('SELECT * FROM `blog_categoria` ORDER BY `order` ASC');

        foreach ($categorias as $key => $categoria) {
            $categorias[$key]['total_posts'] = (int) $this->db()->fetchAssoc('SELECT COUNT(1) as total FROM `blog_post` WHERE `categoria_id` = ?;', array($categoria['id']))['total'];
        }

        $this->get('db')->close();
        $this->db()->close();

        return $this->render('list.twig', array(
            'data' => $categorias,
        ));
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
        $categoria = array();

        $form = $this->createForm(new BlogCategoriaForm(), $categoria, array(
            'action' => $this->get('url_generator')->generate('s_blog_categoria_create'),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $data['slug'] = $this->get('slugify')->slugify($data['slug']);

                try {
                    $data['order'] = (int) $this->db()->fetchAssoc('SELECT COUNT(1) as `total` FROM `blog_categoria`')['total'];

                    $insert_query = 'INSERT INTO `blog_categoria` (`titulo`, `slug`, `descricao`, `meta_title`, `meta_description`, `meta_keywords`, `enabled`, `order`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())';
                    $this->db()->executeUpdate($insert_query, array($data['titulo'], $data['slug'], $data['descricao'], $data['meta_title'], $data['meta_description'], $data['meta_keywords'], $data['enabled'], $data['order']));

                    $this->flashMessage()->add('success', array('message' => 'Categoria adicionada com sucesso.'));

                    return $this->redirect('s_blog_categoria');
                } catch (UniqueConstraintViolationException $e) {
                    $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas já existe uma categoria com este slug.'));
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
        $categoria = $this->get('db')->fetchAssoc('SELECT * FROM `blog_categoria` WHERE `id` = ? LIMIT 1;', array($id));

        if ($categoria === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas a categoria não foi encontrada.'));

            return $this->redirect('s_blog_categoria');
        }

        $form = $this->createForm(new BlogCategoriaForm(), $categoria, array(
            'action' => $this->get('url_generator')->generate('s_blog_categoria_edit', array('id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $data['slug'] = $this->get('slugify')->slugify($data['slug']);

                try {
                    $update_query = 'UPDATE `blog_categoria` SET `titulo` = ?, `slug` = ?, `descricao` = ?, `meta_title` = ?, `meta_description` = ?, `meta_keywords` = ?, `enabled` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1';
                    $this->get('db')->executeUpdate($update_query, array($data['titulo'], $data['slug'], $data['descricao'], $data['meta_title'], $data['meta_description'], $data['meta_keywords'], $data['enabled'], $data['id']));

                    $this->flashMessage()->add('success', array('message' => 'Categoria editada com sucesso.'));

                    return $this->redirect('s_blog_categoria');
                } catch (UniqueConstraintViolationException $e) {
                    $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas já existe uma categoria com este slug.'));
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
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `blog_categoria` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas a categoria não foi encontrada.'));
        } else {
            // Verificar se há posts vinculados
            $posts_count = (int) $this->db()->fetchAssoc('SELECT COUNT(1) as total FROM `blog_post` WHERE `categoria_id` = ?;', array($id))['total'];
            
            if ($posts_count > 0) {
                $this->flashMessage()->add('warning', array('message' => 'Não é possível excluir esta categoria pois há posts vinculados a ela.'));
            } else {
                $this->get('db')->executeUpdate('DELETE FROM `blog_categoria` WHERE `id` = ?', array($id));
                $this->flashMessage()->add('success', array('message' => 'Categoria deletada com sucesso.'));
            }
        }

        return $this->redirect('s_blog_categoria');
    }

    /**
     * Order.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function orderAction(Request $request)
    {
        if (!$request->request->has('order')) {
            return $this->json(array(), 500);
        }

        $orders = $request->request->get('order');

        foreach ($orders as $order => $item) {
            $this->get('db')->executeUpdate('UPDATE `blog_categoria` SET `order` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1', array($order, $item));
        }

        return $this->json($orders, 201);
    }
} 