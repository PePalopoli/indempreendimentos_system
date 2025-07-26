<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Palopoli\PaloSystem\Form\BeneficiosEmpreendimentosForm;
use Symfony\Component\HttpFoundation\Request;

class BeneficiosEmpreendimentosController extends ContainerAware
{
    /**
     * Lista.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $beneficios = $this->db()->fetchAll('SELECT * FROM `beneficios_empreendimentos` ORDER BY `order` ASC, `id` DESC');

        return $this->render('list.twig', array(
            'data' => $beneficios,
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
        $beneficio = array();

        $form = $this->createForm(new BeneficiosEmpreendimentosForm(), $beneficio, array(
            'action' => $this->get('url_generator')->generate('s_beneficios_empreendimentos_create'),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                // Obter o próximo número de order
                $max_order = $this->db()->fetchAssoc('SELECT MAX(`order`) as max_order FROM `beneficios_empreendimentos`');
                $next_order = ($max_order['max_order'] ? $max_order['max_order'] : 0) + 1;

                $insert_query = 'INSERT INTO `beneficios_empreendimentos` (`titulo`, `svg_code`, `enabled`, `order`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, NOW(), NOW())';
                $this->db()->executeUpdate($insert_query, array($data['titulo'], $data['svg_code'], $data['enabled'], $next_order));

                $this->flashMessage()->add('success', array('message' => 'Benefício adicionado com sucesso.'));

                return $this->redirect('s_beneficios_empreendimentos');
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
        $beneficio = $this->get('db')->fetchAssoc('SELECT * FROM `beneficios_empreendimentos` WHERE `id` = ? LIMIT 1;', array($id));

        if ($beneficio === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas o benefício não foi encontrado.'));

            return $this->redirect('s_beneficios_empreendimentos');
        }

        $form = $this->createForm(new BeneficiosEmpreendimentosForm(), $beneficio, array(
            'action' => $this->get('url_generator')->generate('s_beneficios_empreendimentos_edit', array('id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $update_query = 'UPDATE `beneficios_empreendimentos` SET `titulo` = ?, `svg_code` = ?, `enabled` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1';
                $this->get('db')->executeUpdate($update_query, array($data['titulo'], $data['svg_code'], $data['enabled'], $data['id']));

                $this->flashMessage()->add('success', array('message' => 'Benefício editado com sucesso.'));

                return $this->redirect('s_beneficios_empreendimentos');
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
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `beneficios_empreendimentos` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas o benefício não foi encontrado.'));
        } else {
            $this->get('db')->executeUpdate('DELETE FROM `beneficios_empreendimentos` WHERE `id` = ?', array($id));

            $this->flashMessage()->add('success', array('message' => 'Benefício deletado com sucesso.'));
        }

        return $this->redirect('s_beneficios_empreendimentos');
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
            $this->get('db')->executeUpdate('UPDATE `beneficios_empreendimentos` SET `order` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1', array($order, $item));
        }

        return $this->json($orders, 201);
    }
} 