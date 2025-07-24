<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Palopoli\PaloSystem\Form\DepoimentosForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class DepoimentosController extends ContainerAware
{
    /**
     * Lista.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $depoimentos = $this->db()->fetchAll('SELECT * FROM `depoimentos` ORDER BY `order` ASC');

        $this->get('db')->close();
        $this->db()->close();

        return $this->render('list.twig', array(
            'data' => $depoimentos,
        ));
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
        $depoimento = array();

        $form = $this->createForm(new DepoimentosForm(), $depoimento, array(
            'action' => $this->get('url_generator')->generate('s_depoimentos_create'),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                // Extrair ID do YouTube
                $youtube_id = $this->extractYouTubeId($data['youtube_url']);
                $data['youtube_id'] = $youtube_id;

                // Upload da foto do autor
                if ($data['foto_autor'] instanceof UploadedFile) {
                    $image = $data['foto_autor'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/depoimentos');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $data['foto_autor'] = $image_name;
                }

                $data['order'] = (int) $this->db()->fetchAssoc('SELECT COUNT(1) as `total` FROM `depoimentos`')['total'];

                $insert_query = 'INSERT INTO `depoimentos` (`titulo`, `texto`, `youtube_url`, `youtube_id`, `autor_nome`, `autor_cargo`, `autor_empresa`, `foto_autor`, `destaque`, `order`, `enabled`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())';
                $this->db()->executeUpdate($insert_query, array($data['titulo'], $data['texto'], $data['youtube_url'], $data['youtube_id'], $data['autor_nome'], $data['autor_cargo'], $data['autor_empresa'], $data['foto_autor'], $data['destaque'], $data['order'], $data['enabled']));

                $this->flashMessage()->add('success', array('message' => 'Depoimento adicionado com sucesso.'));

                return $this->redirect('s_depoimentos');
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
        $depoimento = $this->get('db')->fetchAssoc('SELECT * FROM `depoimentos` WHERE `id` = ? LIMIT 1;', array($id));

        if ($depoimento === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas o depoimento não foi encontrado.'));

            return $this->redirect('s_depoimentos');
        }

        $image_name = $depoimento['foto_autor'];
        unset($depoimento['foto_autor']);

        $form = $this->createForm(new DepoimentosForm(), $depoimento, array(
            'action' => $this->get('url_generator')->generate('s_depoimentos_edit', array('id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                // Extrair ID do YouTube
                $youtube_id = $this->extractYouTubeId($data['youtube_url']);
                $data['youtube_id'] = $youtube_id;

                // Upload da foto do autor
                if ($data['foto_autor'] instanceof UploadedFile) {
                    $image = $data['foto_autor'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/depoimentos');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);
                }

                $data['foto_autor'] = $image_name;

                $update_query = 'UPDATE `depoimentos` SET `titulo` = ?, `texto` = ?, `youtube_url` = ?, `youtube_id` = ?, `autor_nome` = ?, `autor_cargo` = ?, `autor_empresa` = ?, `foto_autor` = ?, `destaque` = ?, `enabled` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1';
                $this->get('db')->executeUpdate($update_query, array($data['titulo'], $data['texto'], $data['youtube_url'], $data['youtube_id'], $data['autor_nome'], $data['autor_cargo'], $data['autor_empresa'], $data['foto_autor'], $data['destaque'], $data['enabled'], $data['id']));

                $this->flashMessage()->add('success', array('message' => 'Depoimento editado com sucesso.'));

                return $this->redirect('s_depoimentos');
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
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `depoimentos` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas o depoimento não foi encontrado.'));
        } else {
            $file_name = web_path('upload/depoimentos').'/'.$row_sql['foto_autor'];

            $fs = new Filesystem();
            if ($fs->exists($file_name)) {
                $fs->remove($file_name);
            }

            $this->get('db')->executeUpdate('DELETE FROM `depoimentos` WHERE `id` = ?', array($id));

            $this->flashMessage()->add('success', array('message' => 'Depoimento deletado com sucesso.'));
        }

        return $this->redirect('s_depoimentos');
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
            $this->get('db')->executeUpdate('UPDATE `depoimentos` SET `order` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1', array($order, $item));
        }

        return $this->json($orders, 201);
    }
} 