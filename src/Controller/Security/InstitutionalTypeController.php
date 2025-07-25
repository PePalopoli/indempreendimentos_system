<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Palopoli\PaloSystem\Form\InstitutionalTypeForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class InstitutionalTypeController extends ContainerAware
{
    /**
     * Lista.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $institutional_type = $this->db()->fetchAll('SELECT * FROM `institutional_type`');

        foreach ($institutional_type as $key => $bnty) {
            $institutional_type[$key]['total_institucional'] = (int) $this->db()->fetchAssoc('SELECT COUNT(1) as total FROM `institutional` WHERE `type` = ?;', array($bnty['id']))['total'];
        }

        $this->get('db')->close();
        $this->db()->close();

        return $this->render('list.twig', array(
            'data' => $institutional_type,
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
        $institutional_type = array();

        $form = $this->createForm(new InstitutionalTypeForm(), $institutional_type, array(
            'action' => $this->get('url_generator')->generate('s_institutional_type_create'),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $data['url'] = $this->get('slugify')->slugify($data['url']);

                try {
                    $insert_query = 'INSERT INTO `institutional_type` (`title`, `url`, `body`, `enabled`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, NOW(), NOW())';
                    $this->db()->executeUpdate($insert_query, array($data['title'], $data['url'], $data['body'], $data['enabled']));

                    $this->flashMessage()->add('success', array('message' => 'Adicionado com sucesso.'));

                    return $this->redirect('s_institutional_type');
                } catch (UniqueConstraintViolationException $e) {
                    $this->flashMessage()->add('danger', array('message' => 'Url já está em uso.'));
                }
            }
        }

        $this->get('db')->close();
        $this->db()->close();

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
        $institutional_type = $this->get('db')->fetchAssoc('SELECT * FROM `institutional_type` WHERE `id` = ? LIMIT 1;', array($id));

        if ($institutional_type === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais a pagina não foi encontrada.'));

            return $this->redirect('s_institutional_type');
        }

        $form = $this->createForm(new InstitutionalTypeForm(), $institutional_type, array(
            'action' => $this->get('url_generator')->generate('s_institutional_type_edit', array('id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $data['url'] = $this->get('slugify')->slugify($data['url']);

                try {
                    $update_query = 'UPDATE `institutional_type` SET `title` = ?, `url` = ?, `body` = ?, `enabled` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1';
                    $this->get('db')->executeUpdate($update_query, array($data['title'], $data['url'], $data['body'], $data['enabled'], $data['id']));

                    $this->flashMessage()->add('success', array('message' => 'Editado com sucesso.'));

                    return $this->redirect('s_institutional_type');
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
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `institutional_type` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais não foi encontrado.'));
        } else {
            $this->get('db')->executeUpdate('DELETE FROM `institutional_type` WHERE `id` = ?', array($id));

            $this->flashMessage()->add('success', array('message' => 'Deletado com sucesso.'));
        }

        $this->get('db')->close();
        $this->db()->close();

        return $this->redirect('s_institutional_type');
    }
}
