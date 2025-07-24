<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Palopoli\PaloSystem\Form\ObraEtapasForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class ObraEtapasController extends ContainerAware
{
    /**
     * Lista.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $etapas = $this->db()->fetchAll('SELECT * FROM `obra_etapas` ORDER BY `order` ASC');

        foreach ($etapas as $key => $etapa) {
            $etapas[$key]['total_empreendimentos'] = (int) $this->db()->fetchAssoc('SELECT COUNT(1) as total FROM `empreendimentos` WHERE `etapa_id` = ?;', array($etapa['id']))['total'];
        }

        $this->get('db')->close();
        $this->db()->close();

        return $this->render('list.twig', array(
            'data' => $etapas,
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
        $etapa = array();

        $form = $this->createForm(new ObraEtapasForm(), $etapa, array(
            'action' => $this->get('url_generator')->generate('s_obra_etapas_create'),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $data['order'] = (int) $this->db()->fetchAssoc('SELECT COUNT(1) as `total` FROM `obra_etapas`')['total'];

                $insert_query = 'INSERT INTO `obra_etapas` (`titulo`, `cor_hex`, `descricao`, `order`, `enabled`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, NOW(), NOW())';
                $this->db()->executeUpdate($insert_query, array($data['titulo'], $data['cor_hex'], $data['descricao'], $data['order'], $data['enabled']));

                $this->flashMessage()->add('success', array('message' => 'Etapa adicionada com sucesso.'));

                return $this->redirect('s_obra_etapas');
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
        $etapa = $this->get('db')->fetchAssoc('SELECT * FROM `obra_etapas` WHERE `id` = ? LIMIT 1;', array($id));

        if ($etapa === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas a etapa não foi encontrada.'));

            return $this->redirect('s_obra_etapas');
        }

        $form = $this->createForm(new ObraEtapasForm(), $etapa, array(
            'action' => $this->get('url_generator')->generate('s_obra_etapas_edit', array('id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $update_query = 'UPDATE `obra_etapas` SET `titulo` = ?, `cor_hex` = ?, `descricao` = ?, `enabled` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1';
                $this->get('db')->executeUpdate($update_query, array($data['titulo'], $data['cor_hex'], $data['descricao'], $data['enabled'], $data['id']));

                $this->flashMessage()->add('success', array('message' => 'Etapa editada com sucesso.'));

                return $this->redirect('s_obra_etapas');
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
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `obra_etapas` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas a etapa não foi encontrada.'));
        } else {
            // Verificar se há empreendimentos vinculados
            $empreendimentos_count = (int) $this->db()->fetchAssoc('SELECT COUNT(1) as total FROM `empreendimentos` WHERE `etapa_id` = ?;', array($id))['total'];
            
            if ($empreendimentos_count > 0) {
                $this->flashMessage()->add('warning', array('message' => 'Não é possível excluir esta etapa pois há empreendimentos vinculados a ela.'));
            } else {
                $this->get('db')->executeUpdate('DELETE FROM `obra_etapas` WHERE `id` = ?', array($id));
                $this->flashMessage()->add('success', array('message' => 'Etapa deletada com sucesso.'));
            }
        }

        return $this->redirect('s_obra_etapas');
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
            $this->get('db')->executeUpdate('UPDATE `obra_etapas` SET `order` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1', array($order, $item));
        }

        return $this->json($orders, 201);
    }
} 