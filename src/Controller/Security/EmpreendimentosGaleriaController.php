<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Palopoli\PaloSystem\Form\EmpreendimentosGaleriaForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class EmpreendimentosGaleriaController extends ContainerAware
{
    /**
     * Lista galeria de um empreendimento.
     *
     * @param mixed $empreendimento_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($empreendimento_id)
    {
        // Verificar se empreendimento existe
        $empreendimento = $this->db()->fetchAssoc('SELECT * FROM `empreendimentos` WHERE `id` = ? LIMIT 1', array($empreendimento_id));
        
        if ($empreendimento === false) {
            $this->flashMessage()->add('warning', array('message' => 'Empreendimento não encontrado.'));
            return $this->redirect('s_empreendimentos');
        }

        $galeria = $this->db()->fetchAll('SELECT * FROM `empreendimentos_galeria` WHERE `empreendimento_id` = ? ORDER BY `order` ASC', array($empreendimento_id));

        return $this->render('list.twig', array(
            'data' => $galeria,
            'empreendimento' => $empreendimento,
            'empreendimento_id' => $empreendimento_id,
        ));
    }

    /**
     * Adicionar imagem à galeria.
     *
     * @param Request $request
     * @param mixed   $empreendimento_id
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request, $empreendimento_id)
    {
        // Verificar se empreendimento existe
        $empreendimento = $this->db()->fetchAssoc('SELECT * FROM `empreendimentos` WHERE `id` = ? LIMIT 1', array($empreendimento_id));
        
        if ($empreendimento === false) {
            $this->flashMessage()->add('warning', array('message' => 'Empreendimento não encontrado.'));
            return $this->redirect('s_empreendimentos');
        }

        $galeria = array(
            'empreendimento_id' => $empreendimento_id,
        );

        $form = $this->createForm(new EmpreendimentosGaleriaForm(), $galeria, array(
            'action' => $this->get('url_generator')->generate('s_empreendimentos_galeria_create', array('empreendimento_id' => $empreendimento_id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                // Upload da imagem
                if ($data['imagem'] instanceof UploadedFile) {
                    $image = $data['imagem'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/empreendimentos/galeria');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $data['imagem'] = $image_name;
                }

                $data['order'] = (int) $this->db()->fetchAssoc('SELECT COUNT(1) as `total` FROM `empreendimentos_galeria` WHERE `empreendimento_id` = ?', array($empreendimento_id))['total'];

                $insert_query = 'INSERT INTO `empreendimentos_galeria` 
                    (`empreendimento_id`, `imagem`, `titulo`, `legenda`, `alt_text`, `tipo`, `order`, `enabled`, `created_at`, `updated_at`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())';
                $this->db()->executeUpdate($insert_query, array(
                    $data['empreendimento_id'], $data['imagem'], $data['titulo'], $data['legenda'], $data['alt_text'], $data['tipo'], $data['order'], $data['enabled']
                ));

                $this->flashMessage()->add('success', array('message' => 'Imagem adicionada à galeria com sucesso.'));

                return $this->redirect('s_empreendimentos_galeria', array('empreendimento_id' => $empreendimento_id));
            }
        }

        return $this->render('create.twig', array(
            'form' => $form->createView(),
            'empreendimento' => $empreendimento,
            'empreendimento_id' => $empreendimento_id,
        ));
    }

    /**
     * Editar imagem da galeria.
     *
     * @param Request $request
     * @param mixed   $empreendimento_id
     * @param mixed   $id
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, $empreendimento_id, $id)
    {
        $galeria = $this->get('db')->fetchAssoc('SELECT * FROM `empreendimentos_galeria` WHERE `id` = ? AND `empreendimento_id` = ? LIMIT 1;', array($id, $empreendimento_id));

        if ($galeria === false) {
            $this->flashMessage()->add('warning', array('message' => 'Imagem não encontrada.'));
            return $this->redirect('s_empreendimentos_galeria', array('empreendimento_id' => $empreendimento_id));
        }

        $empreendimento = $this->db()->fetchAssoc('SELECT * FROM `empreendimentos` WHERE `id` = ? LIMIT 1', array($empreendimento_id));

        $image_name = $galeria['imagem'];
        unset($galeria['imagem']);

        // Converter campo enabled para boolean
        $galeria['enabled'] = (bool) $galeria['enabled'];

        $form = $this->createForm(new EmpreendimentosGaleriaForm(), $galeria, array(
            'action' => $this->get('url_generator')->generate('s_empreendimentos_galeria_edit', array('empreendimento_id' => $empreendimento_id, 'id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                // Upload da imagem se houver nova
                if ($data['imagem'] instanceof UploadedFile) {
                    $image = $data['imagem'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/empreendimentos/galeria');

                    $new_image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $new_image_name);
                    $fs->chmod($directory.'/'.$new_image_name, 0777);

                    $image_name = $new_image_name;
                }

                $data['imagem'] = $image_name;

                $update_query = 'UPDATE `empreendimentos_galeria` SET 
                    `imagem` = ?, `titulo` = ?, `legenda` = ?, `alt_text` = ?, `tipo` = ?, `enabled` = ?, `updated_at` = NOW() 
                    WHERE `id` = ? AND `empreendimento_id` = ? LIMIT 1';
                $this->get('db')->executeUpdate($update_query, array(
                    $data['imagem'], $data['titulo'], $data['legenda'], $data['alt_text'], $data['tipo'], $data['enabled'], $data['id'], $data['empreendimento_id']
                ));

                $this->flashMessage()->add('success', array('message' => 'Imagem editada com sucesso.'));

                return $this->redirect('s_empreendimentos_galeria', array('empreendimento_id' => $empreendimento_id));
            }
        }

        return $this->render('edit.twig', array(
            'form' => $form->createView(),
            'empreendimento' => $empreendimento,
            'empreendimento_id' => $empreendimento_id,
            'id' => $id,
        ));
    }

    /**
     * Deletar imagem da galeria.
     *
     * @param Request $request
     * @param mixed   $empreendimento_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $empreendimento_id)
    {
        $id = $request->request->get('id');
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `empreendimentos_galeria` WHERE `id` = ? AND `empreendimento_id` = ? LIMIT 1;', array($id, $empreendimento_id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Imagem não encontrada.'));
        } else {
            $fs = new Filesystem();
            
            $file_name = web_path('upload/empreendimentos/galeria').'/'.$row_sql['imagem'];
            if ($fs->exists($file_name)) {
                $fs->remove($file_name);
            }

            $this->get('db')->executeUpdate('DELETE FROM `empreendimentos_galeria` WHERE `id` = ? AND `empreendimento_id` = ?', array($id, $empreendimento_id));

            $this->flashMessage()->add('success', array('message' => 'Imagem deletada com sucesso.'));
        }

        return $this->redirect('s_empreendimentos_galeria', array('empreendimento_id' => $empreendimento_id));
    }

    /**
     * Ordenar imagens da galeria.
     *
     * @param Request $request
     * @param mixed   $empreendimento_id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function orderAction(Request $request, $empreendimento_id)
    {
        if (!$request->request->has('order')) {
            return $this->json(array(), 500);
        }

        $orders = $request->request->get('order');

        foreach ($orders as $order => $item) {
            $this->get('db')->executeUpdate('UPDATE `empreendimentos_galeria` SET `order` = ?, `updated_at` = NOW() WHERE `id` = ? AND `empreendimento_id` = ? LIMIT 1', array($order, $item, $empreendimento_id));
        }

        return $this->json($orders, 201);
    }
} 