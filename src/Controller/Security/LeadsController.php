<?php

namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class LeadsController extends ContainerAware
{
    /**
     * Listar leads com filtros
     */
    public function indexAction(Request $request)
    {
        $page = (int) $request->query->get('page', 1);
        $limit = 25;
        $offset = ($page - 1) * $limit;

        // Filtros
        $filtros = [
            'source_page' => $request->query->get('source_page'),
            'form_type' => $request->query->get('form_type'),
            'status' => $request->query->get('status'),
            'data_inicio' => $request->query->get('data_inicio'),
            'data_fim' => $request->query->get('data_fim')
        ];

        // Remover filtros vazios
        $filtros = array_filter($filtros, function($value) {
            return !empty($value);
        });

        $leadService = $this->get('lead.service');
        $leads = $leadService->buscarLeads($filtros, $limit, $offset);
        $total = $leadService->contarLeads($filtros);
        $totalPages = ceil($total / $limit);

        // Estatísticas básicas
        $stats = $this->getLeadStats();
        //dd($filtros);
        return $this->render('index.twig', [
            'leads' => $leads,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'stats' => $stats,
            'pages_options' => $this->getPagesOptions(),
            'form_types_options' => $this->getFormTypesOptions(),
            'status_options' => $this->getStatusOptions()
        ]);
    }

    /**
     * Visualizar lead específico
     */
    public function viewAction($id)
    {
        $lead = $this->db()->fetchAssoc("SELECT * FROM leads WHERE id = ?", [$id]);
        
        if (!$lead) {
            $this->flashMessage()->add('error', ['message' => 'Lead não encontrado.']);
            return $this->redirect('s_leads');
        }

        // Decodificar form_data JSON
        $formData = json_decode($lead['form_data'], true);

        return $this->render('security/leads/view.twig', [
            'lead' => $lead,
            'form_data' => $formData
        ]);
    }

    /**
     * Atualizar status do lead
     */
    public function updateStatusAction(Request $request, $id)
    {
        if ('POST' !== $request->getMethod()) {
            return $this->json(['success' => false, 'message' => 'Método não permitido'], 405);
        }

        $newStatus = $request->request->get('status');
        $notes = $request->request->get('notes');

        $validStatuses = ['novo', 'contatado', 'convertido', 'perdido'];
        if (!in_array($newStatus, $validStatuses)) {
            return $this->json(['success' => false, 'message' => 'Status inválido'], 400);
        }

        try {
            $sql = "UPDATE leads SET status = ?, notes = ?, updated_at = NOW() WHERE id = ?";
            $this->db()->executeUpdate($sql, [$newStatus, $notes, $id]);

            $this->flashMessage()->add('success', ['message' => 'Status do lead atualizado com sucesso.']);
            return $this->json(['success' => true, 'message' => 'Status atualizado com sucesso']);
        } catch (\Exception $e) {
            error_log("Erro ao atualizar status do lead: " . $e->getMessage());
            return $this->json(['success' => false, 'message' => 'Erro ao atualizar status'], 500);
        }
    }

    /**
     * Exportar leads para CSV
     */
    public function exportAction(Request $request)
    {
        // Mesmos filtros da listagem
        $filtros = [
            'source_page' => $request->query->get('source_page'),
            'form_type' => $request->query->get('form_type'),
            'status' => $request->query->get('status'),
            'data_inicio' => $request->query->get('data_inicio'),
            'data_fim' => $request->query->get('data_fim')
        ];

        $filtros = array_filter($filtros, function($value) {
            return !empty($value);
        });

        $leadService = $this->get('lead.service');
        $csvData = $leadService->exportarCSV($filtros);

        // Criar arquivo CSV
        $filename = 'leads_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 
            $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename));

        // Converter array para CSV
        $output = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($output, $row, ';', '"'); // Usando ponto e vírgula para compatibilidade com Excel
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        // Adicionar BOM para UTF-8 (Excel compatibility)
        $csvContent = "\xEF\xBB\xBF" . $csvContent;

        $response->setContent($csvContent);
        return $response;
    }

    /**
     * Dashboard com métricas de leads
     */
    public function dashboardAction()
    {
        $stats = $this->getDetailedStats();
        
        return $this->render('security/leads/dashboard.twig', [
            'stats' => $stats
        ]);
    }

    /**
     * Deletar lead
     */
    public function deleteAction($id)
    {
        try {
            $sql = "UPDATE leads SET deleted_at = NOW() WHERE id = ?";
            $this->db()->executeUpdate($sql, [$id]);

            $this->flashMessage()->add('success', ['message' => 'Lead removido com sucesso.']);
        } catch (\Exception $e) {
            error_log("Erro ao deletar lead: " . $e->getMessage());
            $this->flashMessage()->add('error', ['message' => 'Erro ao remover lead.']);
        }

        return $this->redirect('s_leads');
    }

    /**
     * Obter estatísticas básicas
     */
    private function getLeadStats()
    {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');

        return [
            'total' => $this->db()->fetchColumn("SELECT COUNT(*) FROM leads WHERE deleted_at IS NULL"),
            'hoje' => $this->db()->fetchColumn("SELECT COUNT(*) FROM leads WHERE DATE(created_at) = ? AND deleted_at IS NULL", [$today]),
            'mes' => $this->db()->fetchColumn("SELECT COUNT(*) FROM leads WHERE DATE_FORMAT(created_at, '%Y-%m') = ? AND deleted_at IS NULL", [$thisMonth]),
            'novos' => $this->db()->fetchColumn("SELECT COUNT(*) FROM leads WHERE status = 'novo' AND deleted_at IS NULL"),
            'convertidos' => $this->db()->fetchColumn("SELECT COUNT(*) FROM leads WHERE status = 'convertido' AND deleted_at IS NULL")
        ];
    }

    /**
     * Obter estatísticas detalhadas
     */
    private function getDetailedStats()
    {
        $stats = $this->getLeadStats();
        
        // Leads por página
        $leadsPerPage = $this->db()->fetchAll("
            SELECT source_page, COUNT(*) as total 
            FROM leads 
            WHERE deleted_at IS NULL 
            GROUP BY source_page 
            ORDER BY total DESC
        ");

        // Leads por tipo de formulário
        $leadsPerForm = $this->db()->fetchAll("
            SELECT form_type, COUNT(*) as total 
            FROM leads 
            WHERE deleted_at IS NULL 
            GROUP BY form_type 
            ORDER BY total DESC
        ");

        // Leads dos últimos 30 dias
        $leadsLast30Days = $this->db()->fetchAll("
            SELECT DATE(created_at) as data, COUNT(*) as total 
            FROM leads 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
            AND deleted_at IS NULL 
            GROUP BY DATE(created_at) 
            ORDER BY data ASC
        ");

        $stats['por_pagina'] = $leadsPerPage;
        $stats['por_formulario'] = $leadsPerForm;
        $stats['ultimos_30_dias'] = $leadsLast30Days;

        return $stats;
    }

    /**
     * Opções para filtro de páginas
     */
    private function getPagesOptions()
    {
        return [
            '' => 'Todas as páginas',
            'home' => 'Home',
            'contato' => 'Contato',
            'empreendimentos' => 'Empreendimentos',
            'sobre' => 'Sobre Nós',
            'noticias' => 'Notícias',
            'other' => 'Outras'
        ];
    }

    /**
     * Opções para filtro de tipos de formulário
     */
    private function getFormTypesOptions()
    {
        return [
            '' => 'Todos os tipos',
            'home' => 'Home',
            'contato' => 'Contato', 
            'empreendimento' => 'Empreendimento',
            'newsletter' => 'Newsletter'
        ];
    }

    /**
     * Opções para filtro de status
     */
    private function getStatusOptions()
    {
        return [
            '' => 'Todos os status',
            'novo' => 'Novo',
            'contatado' => 'Contatado',
            'convertido' => 'Convertido',
            'perdido' => 'Perdido'
        ];
    }
}