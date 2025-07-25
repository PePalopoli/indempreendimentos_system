<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Palopoli\PaloSystem\Form\BannerTypeForm;
use Symfony\Component\HttpFoundation\Request;

class BannerTypeController extends ContainerAware
{
    /**
     * Lista.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $banner_type = $this->db()->fetchAll('SELECT * FROM `banner_type`');

        foreach ($banner_type as $key => $bnty) {
            $banner_type[$key]['total_banner'] = (int) $this->db()->fetchAssoc('SELECT COUNT(1) as total FROM `banner` WHERE `type` = ?;', array($bnty['id']))['total'];
        }

        return $this->render('list.twig', array(
            'data' => $banner_type,
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
        $banner_type = array();

        $form = $this->createForm(new BannerTypeForm(), $banner_type, array(
            'action' => $this->get('url_generator')->generate('s_banner_type_create'),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $insert_query = 'INSERT INTO `banner_type` (`title`, `width`, `height`, `enabled`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, NOW(), NOW())';
                $this->db()->executeUpdate($insert_query, array($data['title'], $data['width'], $data['height'], $data['enabled']));

                $this->flashMessage()->add('success', array('message' => 'Adicionado com sucesso.'));

                return $this->redirect('s_banner_type');
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
        $banner_type = $this->get('db')->fetchAssoc('SELECT * FROM `banner_type` WHERE `id` = ? LIMIT 1;', array($id));

        if ($banner_type === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais a pagina não foi encontrada.'));

            return $this->redirect('s_banner_type');
        }

        $form = $this->createForm(new BannerTypeForm(), $banner_type, array(
            'action' => $this->get('url_generator')->generate('s_banner_type_edit', array('id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $update_query = 'UPDATE `banner_type` SET `title` = ?, `width` = ?, `height` = ?, `enabled` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1';
                $this->get('db')->executeUpdate($update_query, array($data['title'], $data['width'], $data['height'], $data['enabled'], $data['id']));

                $this->flashMessage()->add('success', array('message' => 'Editado com sucesso.'));

                return $this->redirect('s_banner_type');
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
     * @param int     $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $id = $request->request->get('id');
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `banner_type` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais não foi encontrado.'));
        } else {
            $banners = $this->db()->fetchAll('SELECT * FROM `banner` WHERE `type` = ?', array($banner_type['id']));
            foreach ($banners as $banner) {
                $file_name = __DIR__.'/../../../web/upload/banner/'.$banner['image'];

                $fs = new Filesystem();
                if ($fs->exists($file_name)) {
                    $fs->remove($file_name);
                }

                $this->get('db')->executeUpdate('DELETE FROM `banner` WHERE `id` = ?', array($banner['id']));
            }

            $this->get('db')->executeUpdate('DELETE FROM `banner_type` WHERE `id` = ?', array($id));

            $this->flashMessage()->add('success', array('message' => 'Deletado com sucesso.'));
        }

        return $this->redirect('s_banner_type');
    }
}
