<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Palopoli\PaloSystem\Form\DepoimentosTypeForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class DepoimentosTypeController extends ContainerAware
{
    /**
     * Lista.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $depoimentos_type = $this->db()->fetchAll('SELECT * FROM `depoimentos_type`');

        

        $this->get('db')->close();
        $this->db()->close();

        return $this->render('list.twig', array(
            'data' => $depoimentos_type,
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
        $depoimentos_type = array();

        $form = $this->createForm(new DepoimentosTypeForm(), $depoimentos_type, array(
            'action' => $this->get('url_generator')->generate('s_depoimentos_type_create'),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                if ($data['image'] instanceof UploadedFile) {
                    $image = $data['image'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/depoimentos');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $data['image'] = $image_name;
                }

                try {
                    $insert_query = 'INSERT INTO `depoimentos_type` (`title`, `image`,`body`, `enabled`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, NOW(), NOW())';
                    $this->db()->executeUpdate($insert_query, array($data['title'], $data['image'],$data['body'],  $data['enabled']));

                    $this->flashMessage()->add('success', array('message' => 'Adicionado com sucesso.'));

                    return $this->redirect('s_depoimentos_type');
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
        $depoimentos_type = $this->get('db')->fetchAssoc('SELECT * FROM `depoimentos_type` WHERE `id` = ? LIMIT 1;', array($id));

        if ($depoimentos_type === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais a pagina não foi encontrada.'));

            return $this->redirect('s_depoimentos_type');
        }

        $image_name = $depoimentos_type['image'];
        unset($depoimentos_type['image']);

        $form = $this->createForm(new DepoimentosTypeForm(), $depoimentos_type, array(
            'action' => $this->get('url_generator')->generate('s_depoimentos_type_edit', array('id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                if ($data['image'] instanceof UploadedFile) {
                    $image = $data['image'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/depoimentos');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                }
                $data['image'] = $image_name;

                try {
                    $update_query = 'UPDATE `depoimentos_type` SET `title` = ?, `image` = ?,`body` = ?,  `enabled` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1';
                    $this->get('db')->executeUpdate($update_query, array($data['title'], $data['image'],$data['body'], $data['enabled'], $data['id']));

                    $this->flashMessage()->add('success', array('message' => 'Editado com sucesso.'));

                    return $this->redirect('s_depoimentos_type');
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
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `depoimentos_type` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais não foi encontrado.'));
        } else {
            $this->get('db')->executeUpdate('DELETE FROM `depoimentos_type` WHERE `id` = ?', array($id));

            $this->flashMessage()->add('success', array('message' => 'Deletado com sucesso.'));
        }

        $this->get('db')->close();
        $this->db()->close();

        return $this->redirect('s_depoimentos_type');
    }
}
