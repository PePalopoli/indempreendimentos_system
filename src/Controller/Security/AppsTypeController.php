<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Palopoli\PaloSystem\Form\AppsTypeForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class appsTypeController extends ContainerAware
{
    /**
     * Lista.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $apps_type = $this->db()->fetchAll('SELECT * FROM `apps_type`');

        

        $this->get('db')->close();
        $this->db()->close();

        return $this->render('list.twig', array(
            'data' => $apps_type,
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
        $apps_type = array();

        $form = $this->createForm(new AppsTypeForm(), $apps_type, array(
            'action' => $this->get('url_generator')->generate('s_apps_type_create'),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                if ($data['image'] instanceof UploadedFile) {
                    $image = $data['image'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/apps');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $data['image'] = $image_name;
                }

                try {
                    $insert_query = 'INSERT INTO `apps_type` (`title`, `image`, `enabled`, `created_at`, `updated_at`) VALUES ( ?, ?, ?, NOW(), NOW())';
                    $this->db()->executeUpdate($insert_query, array($data['title'], $data['image'],  $data['enabled']));

                    $this->flashMessage()->add('success', array('message' => 'Adicionado com sucesso.'));

                    return $this->redirect('s_apps_type');
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
        $apps_type = $this->get('db')->fetchAssoc('SELECT * FROM `apps_type` WHERE `id` = ? LIMIT 1;', array($id));

        if ($apps_type === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais a pagina não foi encontrada.'));

            return $this->redirect('s_apps_type');
        }

        $image_name = $apps_type['image'];
        unset($apps_type['image']);

        $form = $this->createForm(new AppsTypeForm(), $apps_type, array(
            'action' => $this->get('url_generator')->generate('s_apps_type_edit', array('id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                if ($data['image'] instanceof UploadedFile) {
                    $image = $data['image'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/apps');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                }
                $data['image'] = $image_name;

                try {
                    $update_query = 'UPDATE `apps_type` SET `title` = ?, `image` = ?,  `enabled` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1';
                    $this->get('db')->executeUpdate($update_query, array($data['title'], $data['image'], $data['enabled'], $data['id']));

                    $this->flashMessage()->add('success', array('message' => 'Editado com sucesso.'));

                    return $this->redirect('s_apps_type');
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
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `apps_type` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mais não foi encontrado.'));
        } else {
            $this->get('db')->executeUpdate('DELETE FROM `apps_type` WHERE `id` = ?', array($id));

            $this->flashMessage()->add('success', array('message' => 'Deletado com sucesso.'));
        }

        $this->get('db')->close();
        $this->db()->close();

        return $this->redirect('s_apps_type');
    }
}
