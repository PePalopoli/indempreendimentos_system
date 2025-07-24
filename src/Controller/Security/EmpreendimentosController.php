<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Palopoli\PaloSystem\Form\EmpreendimentosForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class EmpreendimentosController extends ContainerAware
{
    /**
     * Lista.
     */
    public function indexAction()
    {
        $empreendimentos = $this->db()->fetchAll('
            SELECT e.*, oe.titulo as etapa_titulo, oe.cor_hex as etapa_cor 
            FROM `empreendimentos` e 
            INNER JOIN `obra_etapas` oe ON e.etapa_id = oe.id 
            ORDER BY e.order ASC
        ');

        $this->get('db')->close();
        $this->db()->close();

        return $this->render('list.twig', array(
            'data' => $empreendimentos,
        ));
    }

    /**
     * @return array
     */
    private function getChoiceEtapas()
    {
        $etapas = array();
        $etapas_fetch = $this->db()->fetchAll('SELECT `id`, `titulo` FROM `obra_etapas` WHERE `enabled` = 1 ORDER BY `order`');

        foreach ($etapas_fetch as $etapa) {
            $etapas[$etapa['id']] = $etapa['titulo'];
        }

        return $etapas;
    }

    /**
     * Processar múltiplas imagens da galeria
     */
    private function processarGaleriaImagens($galeria_imagens, $empreendimento_id)
    {
        error_log("DEBUG: processarGaleriaImagens iniciado");
        error_log("DEBUG: empreendimento_id: " . $empreendimento_id);
        error_log("DEBUG: galeria_imagens tipo: " . gettype($galeria_imagens));
        
        if (!$galeria_imagens) {
            error_log("DEBUG: galeria_imagens está vazio ou null");
            return array('success' => false, 'message' => 'Nenhuma imagem fornecida', 'errors' => array());
        }

        // Garantir que é array
        if (!is_array($galeria_imagens)) {
            if ($galeria_imagens instanceof UploadedFile) {
                $galeria_imagens = array($galeria_imagens);
                error_log("DEBUG: Convertido UploadedFile único para array");
            } else {
                error_log("DEBUG: galeria_imagens não é array nem UploadedFile");
                return array('success' => false, 'message' => 'Formato de imagem inválido', 'errors' => array());
            }
        }

        error_log("DEBUG: Número total de itens para processar: " . count($galeria_imagens));

        $fs = new Filesystem();
        $directory = web_path('upload/empreendimentos/galeria');
        error_log("DEBUG: Diretório de upload: " . $directory);

        if (!$fs->exists($directory)) {
            $fs->mkdir($directory, 0777);
            error_log("DEBUG: Diretório criado: " . $directory);
        }

        $uploaded_count = 0;
        $errors = array();

        // Pegar a próxima ordem disponível
        $order_base = (int) $this->db()->fetchAssoc('SELECT COALESCE(MAX(`order`), 0) as `max_order` FROM `empreendimentos_galeria` WHERE `empreendimento_id` = ?', array($empreendimento_id))['max_order'] + 1;
        error_log("DEBUG: order_base: " . $order_base);
        
        foreach ($galeria_imagens as $index => $image) {
            error_log("DEBUG: Processando item " . ($index + 1) . " de " . count($galeria_imagens));
            error_log("DEBUG: Tipo do item: " . gettype($image));
            
            if ($image instanceof UploadedFile) {
                error_log("DEBUG: Item é UploadedFile");
                error_log("DEBUG: Nome original: " . $image->getClientOriginalName());
                error_log("DEBUG: Tamanho: " . $image->getSize());
                error_log("DEBUG: É válido: " . ($image->isValid() ? 'sim' : 'não'));
                
                if ($image->isValid()) {
                    try {
                        // Validar tipo de arquivo
                        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
                        $file_extension = strtolower($image->getClientOriginalExtension());
                        error_log("DEBUG: Extensão do arquivo: " . $file_extension);
                        
                        if (!in_array($file_extension, $allowed_extensions)) {
                            $errors[] = "Arquivo {$image->getClientOriginalName()}: tipo não permitido";
                            error_log("DEBUG: Tipo de arquivo não permitido");
                            continue;
                        }

                        // Validar tamanho (máximo 10MB)
                        if ($image->getSize() > 10485760) { // 10MB em bytes
                            $errors[] = "Arquivo {$image->getClientOriginalName()}: muito grande (máximo 10MB)";
                            error_log("DEBUG: Arquivo muito grande");
                            continue;
                        }

                        $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();
                        error_log("DEBUG: Nome do arquivo gerado: " . $image_name);
                        
                        $image->move($directory, $image_name);
                        $fs->chmod($directory.'/'.$image_name, 0777);
                        error_log("DEBUG: Arquivo movido e permissões definidas");

                        // Criar título baseado no nome original do arquivo
                        $original_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                        $titulo = !empty($original_name) ? $original_name : 'Imagem ' . ($order_base + $index);
                        error_log("DEBUG: Título criado: " . $titulo);

                        // Inserir na tabela de galeria
                        $insert_query = 'INSERT INTO `empreendimentos_galeria` 
                            (`empreendimento_id`, `imagem`, `titulo`, `tipo`, `order`, `enabled`, `created_at`, `updated_at`) 
                            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())';
                        
                        $result = $this->db()->executeUpdate($insert_query, array(
                            $empreendimento_id,
                            $image_name,
                            $titulo,
                            'galeria', // Tipo fixo para galeria
                            $order_base + $index,
                            1 // Ativo por padrão
                        ));
                        
                        error_log("DEBUG: Insert executado, resultado: " . $result);
                        $uploaded_count++;
                        
                    } catch (\Exception $e) {
                        $error_msg = "Erro ao processar {$image->getClientOriginalName()}: " . $e->getMessage();
                        $errors[] = $error_msg;
                        error_log("DEBUG: ERRO: " . $error_msg);
                    }
                } else {
                    error_log("DEBUG: Arquivo não é válido - erro: " . $image->getErrorMessage());
                    $errors[] = "Arquivo {$image->getClientOriginalName()}: " . $image->getErrorMessage();
                }
            } else {
                error_log("DEBUG: Item não é UploadedFile, ignorando");
            }
        }

        $result = array(
            'success' => $uploaded_count > 0,
            'uploaded_count' => $uploaded_count,
            'errors' => $errors,
            'message' => $uploaded_count > 0 ? 
                "Processadas {$uploaded_count} imagem(ns) com sucesso" . 
                (count($errors) > 0 ? " (com alguns erros)" : "") :
                "Nenhuma imagem foi processada"
        );
        
        error_log("DEBUG: Resultado final: " . print_r($result, true));
        return $result;
    }

    /**
     * Remover imagem da galeria
     */
    public function removeGaleriaImageAction(Request $request)
    {
        $image_id = $request->request->get('image_id');
        $empreendimento_id = $request->request->get('empreendimento_id');

        if (!$image_id || !$empreendimento_id) {
            return $this->json(array('success' => false, 'message' => 'Parâmetros inválidos'), 400);
        }

        $image = $this->db()->fetchAssoc('SELECT * FROM `empreendimentos_galeria` WHERE `id` = ? AND `empreendimento_id` = ? LIMIT 1', array($image_id, $empreendimento_id));

        if (!$image) {
            return $this->json(array('success' => false, 'message' => 'Imagem não encontrada'), 404);
        }

        try {
            // Remover arquivo físico
            $fs = new Filesystem();
            $file_path = web_path('upload/empreendimentos/galeria') . '/' . $image['imagem'];
            if ($fs->exists($file_path)) {
                $fs->remove($file_path);
            }

            // Remover do banco
            $this->db()->executeUpdate('DELETE FROM `empreendimentos_galeria` WHERE `id` = ? LIMIT 1', array($image_id));

            return $this->json(array('success' => true, 'message' => 'Imagem removida com sucesso'));
        } catch (\Exception $e) {
            return $this->json(array('success' => false, 'message' => 'Erro ao remover imagem: ' . $e->getMessage()), 500);
        }
    }

    /**
     * Processar remoção de imagens marcadas no frontend
     */
    private function processarImagensParaRemover($images_to_remove, $empreendimento_id)
    {
        if (empty($images_to_remove)) {
            return array('success' => true, 'removed_count' => 0, 'message' => 'Nenhuma imagem para remover');
        }

        $image_ids = explode(',', $images_to_remove);
        $removed_count = 0;
        $errors = array();

        $fs = new Filesystem();

        foreach ($image_ids as $image_id) {
            $image_id = trim($image_id);
            if (empty($image_id)) continue;

            try {
                // Buscar a imagem no banco
                $image = $this->db()->fetchAssoc(
                    'SELECT * FROM `empreendimentos_galeria` WHERE `id` = ? AND `empreendimento_id` = ? LIMIT 1',
                    array($image_id, $empreendimento_id)
                );

                if ($image) {
                    // Remover arquivo físico
                    $file_path = web_path('upload/empreendimentos/galeria') . '/' . $image['imagem'];
                    if ($fs->exists($file_path)) {
                        $fs->remove($file_path);
                    }

                    // Remover do banco
                    $this->db()->executeUpdate(
                        'DELETE FROM `empreendimentos_galeria` WHERE `id` = ? LIMIT 1',
                        array($image_id)
                    );

                    $removed_count++;
                }
            } catch (\Exception $e) {
                $errors[] = "Erro ao remover imagem ID {$image_id}: " . $e->getMessage();
            }
        }

        return array(
            'success' => $removed_count > 0,
            'removed_count' => $removed_count,
            'errors' => $errors,
            'message' => $removed_count > 0 ? 
                "Removidas {$removed_count} imagem(ns) com sucesso" :
                "Nenhuma imagem foi removida"
        );
    }

    /**
     * Adicionar.
     */
    public function createAction(Request $request)
    {
        $empreendimento = array(
            'etapas_choices' => $this->getChoiceEtapas(),
        );

        $form = $this->createForm(new EmpreendimentosForm(), $empreendimento, array(
            'action' => $this->get('url_generator')->generate('s_empreendimentos_create'),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $data['slug'] = $this->get('slugify')->slugify($data['slug']);

                // Upload do logo
                if ($data['logo_empreendimento'] instanceof UploadedFile) {
                    $image = $data['logo_empreendimento'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/empreendimentos');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $data['logo_empreendimento'] = $image_name;
                }

                // Upload da imagem de capa
                if ($data['img_capa'] instanceof UploadedFile) {
                    $image = $data['img_capa'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/empreendimentos');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $data['img_capa'] = $image_name;
                }

                try {
                    $data['order'] = (int) $this->db()->fetchAssoc('SELECT COUNT(1) as `total` FROM `empreendimentos`')['total'];

                    $insert_query = 'INSERT INTO `empreendimentos` 
                        (`etapa_id`, `nome`, `cidade_estado`, `slug`, `descricao`, `logo_empreendimento`, `img_capa`, `destaque`, `meta_title`, `meta_description`, `meta_keywords`, `enabled`, `order`, `created_at`, `updated_at`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())';
                    $this->db()->executeUpdate($insert_query, array(
                        $data['etapa_id'], $data['nome'], $data['cidade_estado'], $data['slug'], $data['descricao'], $data['logo_empreendimento'], $data['img_capa'], $data['destaque'], $data['meta_title'], $data['meta_description'], $data['meta_keywords'], $data['enabled'], $data['order']
                    ));

                    // Pegar o ID do empreendimento recém criado
                    $empreendimento_id = $this->db()->lastInsertId();

                    // NOVO: Processar galeria de imagens
                    $galeria_imagens = $form->get('galeria_imagens')->getData();
                    
                    // DEBUG: Verificar se há imagens para processar
                    error_log("DEBUG CREATE: galeria_imagens tipo: " . gettype($galeria_imagens));
                    if (is_array($galeria_imagens) && count($galeria_imagens) > 0) {
                        error_log("DEBUG CREATE: Processando " . count($galeria_imagens) . " imagens da galeria");
                        $processamento_result = $this->processarGaleriaImagens($galeria_imagens, $empreendimento_id);
                    } elseif ($galeria_imagens !== null) {
                        error_log("DEBUG CREATE: galeria_imagens não é array ou está vazio");
                        if ($galeria_imagens instanceof UploadedFile) {
                            $galeria_imagens = array($galeria_imagens);
                            $processamento_result = $this->processarGaleriaImagens($galeria_imagens, $empreendimento_id);
                        } else {
                            $processamento_result = array('success' => false, 'message' => 'Nenhuma imagem válida encontrada', 'errors' => array());
                        }
                    } else {
                        error_log("DEBUG CREATE: galeria_imagens é null");
                        $processamento_result = array('success' => false, 'message' => 'Campo de galeria não encontrado', 'errors' => array());
                    }

                    $success_message = 'Empreendimento adicionado com sucesso.';
                    if ($processamento_result['success']) {
                        $success_message .= ' ' . $processamento_result['message'];
                    }
                    
                    $this->flashMessage()->add('success', array('message' => $success_message));

                    // Se houve erros no processamento das imagens, mostrar também
                    if (!empty($processamento_result['errors'])) {
                        foreach ($processamento_result['errors'] as $error) {
                            $this->flashMessage()->add('warning', array('message' => $error));
                        }
                    }

                    return $this->redirect('s_empreendimentos');
                } catch (UniqueConstraintViolationException $e) {
                    $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas já existe um empreendimento com este slug.'));
                }
            }
        }

        return $this->render('create.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Editar.
     */
    public function editAction(Request $request, $id)
    {
        $empreendimento = $this->get('db')->fetchAssoc('SELECT * FROM `empreendimentos` WHERE `id` = ? LIMIT 1;', array($id));

        if ($empreendimento === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas o empreendimento não foi encontrado.'));
            return $this->redirect('s_empreendimentos');
        }

        $logo_name = $empreendimento['logo_empreendimento'];
        $capa_name = $empreendimento['img_capa'];
        unset($empreendimento['logo_empreendimento']);
        unset($empreendimento['img_capa']);

        // Converter campos string para boolean
        $empreendimento['destaque'] = (bool) $empreendimento['destaque'];
        $empreendimento['enabled'] = (bool) $empreendimento['enabled'];

        $empreendimento['etapas_choices'] = $this->getChoiceEtapas();

        $form = $this->createForm(new EmpreendimentosForm(), $empreendimento, array(
            'action' => $this->get('url_generator')->generate('s_empreendimentos_edit', array('id' => $id)),
            'method' => 'POST',
        ));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $data['slug'] = $this->get('slugify')->slugify($data['slug']);

                // Upload do logo
                if ($data['logo_empreendimento'] instanceof UploadedFile) {
                    $image = $data['logo_empreendimento'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/empreendimentos');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $logo_name = $image_name;
                }

                // Upload da imagem de capa
                if ($data['img_capa'] instanceof UploadedFile) {
                    $image = $data['img_capa'];
                    $fs = new Filesystem();
                    $directory = web_path('upload/empreendimentos');

                    $image_name = sha1(uniqid(mt_rand(), true)).'.'.$image->guessExtension();

                    if (!$fs->exists($directory)) {
                        $fs->mkdir($directory, 0777);
                    }

                    $image->move($directory, $image_name);
                    $fs->chmod($directory.'/'.$image_name, 0777);

                    $capa_name = $image_name;
                }

                $data['logo_empreendimento'] = $logo_name;
                $data['img_capa'] = $capa_name;

                try {
                    $update_query = 'UPDATE `empreendimentos` SET 
                        `etapa_id` = ?, `nome` = ?, `cidade_estado` = ?, `slug` = ?, `descricao` = ?, `logo_empreendimento` = ?, `img_capa` = ?, `destaque` = ?, `meta_title` = ?, `meta_description` = ?, `meta_keywords` = ?, `enabled` = ?, `updated_at` = NOW() 
                        WHERE `id` = ? LIMIT 1';
                    $this->get('db')->executeUpdate($update_query, array(
                        $data['etapa_id'], $data['nome'], $data['cidade_estado'], $data['slug'], $data['descricao'], $data['logo_empreendimento'], $data['img_capa'], $data['destaque'], $data['meta_title'], $data['meta_description'], $data['meta_keywords'], $data['enabled'], $data['id']
                    ));

                    // NOVO: Processar imagens marcadas para remoção
                    $images_to_remove = $request->request->get('images_to_remove');
                    $remocao_result = $this->processarImagensParaRemover($images_to_remove, $id);

                    // NOVO: Processar galeria de imagens (apenas adicionar novas)
                    $galeria_imagens = $form->get('galeria_imagens')->getData();
                    
                    // DEBUG: Verificar se há imagens para processar
                    error_log("DEBUG: galeria_imagens tipo: " . gettype($galeria_imagens));
                    
                    // Verificar se houve erro de upload por limite de tamanho
                    if (empty($galeria_imagens) && !empty($_FILES['empreendimentos']['name']['galeria_imagens'])) {
                        error_log("DEBUG: Possível erro de limite de upload - FILES existe mas galeria_imagens está vazio");
                        $this->flashMessage()->add('warning', array('message' => 'Algumas imagens podem ter excedido o limite de tamanho. Tente com imagens menores ou menos arquivos.'));
                    }
                    
                    if (is_array($galeria_imagens) && count($galeria_imagens) > 0) {
                        error_log("DEBUG: Processando " . count($galeria_imagens) . " imagens da galeria");
                        $processamento_result = $this->processarGaleriaImagens($galeria_imagens, $id);
                    } elseif ($galeria_imagens !== null) {
                        error_log("DEBUG: galeria_imagens não é array ou está vazio. Tipo: " . gettype($galeria_imagens));
                        error_log("DEBUG: Conteúdo: " . print_r($galeria_imagens, true));
                        
                        // Se for um único arquivo, converter para array
                        if ($galeria_imagens instanceof UploadedFile) {
                            $galeria_imagens = array($galeria_imagens);
                            $processamento_result = $this->processarGaleriaImagens($galeria_imagens, $id);
                        } else {
                            $processamento_result = array('success' => false, 'message' => 'Nenhuma imagem válida encontrada', 'errors' => array());
                        }
                    } else {
                        error_log("DEBUG: galeria_imagens é null");
                        $processamento_result = array('success' => true, 'message' => 'Nenhuma nova imagem para processar', 'errors' => array());
                    }

                    // Construir mensagem de sucesso
                    $success_message = 'Empreendimento editado com sucesso.';
                    
                    if ($remocao_result['removed_count'] > 0) {
                        $success_message .= ' ' . $remocao_result['message'];
                    }
                    
                    if ($processamento_result['success']) {
                        $success_message .= ' ' . $processamento_result['message'];
                    }
                    
                    $this->flashMessage()->add('success', array('message' => $success_message));

                    // Mostrar erros se houver
                    if (!empty($remocao_result['errors'])) {
                        foreach ($remocao_result['errors'] as $error) {
                            $this->flashMessage()->add('warning', array('message' => $error));
                        }
                    }

                    if (!empty($processamento_result['errors'])) {
                        foreach ($processamento_result['errors'] as $error) {
                            $this->flashMessage()->add('warning', array('message' => $error));
                        }
                    }

                    return $this->redirect('s_empreendimentos');
                } catch (UniqueConstraintViolationException $e) {
                    $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas já existe um empreendimento com este slug.'));
                }
            }
        }

        // Buscar galeria existente para exibir
        $galeria_existente = $this->db()->fetchAll('SELECT * FROM `empreendimentos_galeria` WHERE `empreendimento_id` = ? ORDER BY `order` ASC', array($id));

        return $this->render('edit.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'galeria_existente' => $galeria_existente,
        ));
    }

    /**
     * Deletar.
     */
    public function deleteAction(Request $request)
    {
        $id = $request->request->get('id');
        $row_sql = $this->get('db')->fetchAssoc('SELECT * FROM `empreendimentos` WHERE `id` = ? LIMIT 1;', array($id));

        if ($row_sql === false) {
            $this->flashMessage()->add('warning', array('message' => 'Desculpe, mas o empreendimento não foi encontrado.'));
        } else {
            $fs = new Filesystem();
            
            // Remover logo
            if ($row_sql['logo_empreendimento']) {
                $file_name = web_path('upload/empreendimentos').'/'.$row_sql['logo_empreendimento'];
                if ($fs->exists($file_name)) {
                    $fs->remove($file_name);
                }
            }

            // Remover imagem de capa
            if ($row_sql['img_capa']) {
                $file_name = web_path('upload/empreendimentos').'/'.$row_sql['img_capa'];
                if ($fs->exists($file_name)) {
                    $fs->remove($file_name);
                }
            }

            // Remover galeria
            $galeria = $this->db()->fetchAll('SELECT * FROM `empreendimentos_galeria` WHERE `empreendimento_id` = ?', array($id));
            foreach ($galeria as $item) {
                $file_name = web_path('upload/empreendimentos/galeria').'/'.$item['imagem'];
                if ($fs->exists($file_name)) {
                    $fs->remove($file_name);
                }
            }
            $this->get('db')->executeUpdate('DELETE FROM `empreendimentos_galeria` WHERE `empreendimento_id` = ?', array($id));

            $this->get('db')->executeUpdate('DELETE FROM `empreendimentos` WHERE `id` = ?', array($id));

            $this->flashMessage()->add('success', array('message' => 'Empreendimento deletado com sucesso.'));
        }

        return $this->redirect('s_empreendimentos');
    }

    /**
     * Order.
     */
    public function orderAction(Request $request)
    {
        if (!$request->request->has('order')) {
            return $this->json(array(), 500);
        }

        $orders = $request->request->get('order');

        foreach ($orders as $order => $item) {
            $this->get('db')->executeUpdate('UPDATE `empreendimentos` SET `order` = ?, `updated_at` = NOW() WHERE `id` = ? LIMIT 1', array($order, $item));
        }

        return $this->json($orders, 201);
    }
}