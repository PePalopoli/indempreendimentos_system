<?php

namespace Palopoli\PaloSystem\Service;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;

class LeadService
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Captura um lead a partir dos dados do formulário
     */
    public function capturarLead(Request $request, array $formData, $formType = 'contato')
    {
        // Detectar página de origem
        $sourcePage = $this->detectSourcePage($request->headers->get('referer', ''));
        
        // Extrair dados UTM
        $utmData = $this->extractUtmData($request);
        
        // Preparar dados principais
        $leadData = [
            'source_url' => $request->headers->get('referer', $request->getUri()),
            'source_page' => $sourcePage,
            'form_type' => $formType,
            'user_agent' => $request->headers->get('User-Agent'),
            'ip_address' => $request->getClientIp(),
            'referrer' => $request->headers->get('referer'),
            
            // Campos principais (extraídos dos dados do form)
            'nome' => $formData['iName'] ?? $formData['nome'] ?? null,
            'email' => $formData['iEmail'] ?? $formData['email'] ?? null,
            'telefone' => $formData['iTelefone'] ?? $formData['telefone'] ?? null,
            'whatsapp' => $formData['iWhatsapp'] ?? $formData['whatsapp'] ?? null,
            'assunto' => $formData['iAssunto'] ?? $formData['assunto'] ?? null,
            'mensagem' => $formData['iMsg'] ?? $formData['mensagem'] ?? null,
            
            // Dados completos do formulário em JSON
            'form_data' => json_encode($formData, JSON_UNESCAPED_UNICODE),
            
            // UTM parameters
            'utm_source' => $utmData['utm_source'],
            'utm_medium' => $utmData['utm_medium'],
            'utm_campaign' => $utmData['utm_campaign'],
            'utm_term' => $utmData['utm_term'],
            'utm_content' => $utmData['utm_content'],
            
            'status' => 'novo',
            'email_sent' => 0
        ];

        $sql = "INSERT INTO leads (
            source_url, source_page, form_type, user_agent, ip_address, referrer,
            nome, email, telefone, whatsapp, assunto, mensagem, form_data,
            utm_source, utm_medium, utm_campaign, utm_term, utm_content,
            status, email_sent, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        try {
            $this->db->executeUpdate($sql, [
                $leadData['source_url'],
                $leadData['source_page'],
                $leadData['form_type'],
                $leadData['user_agent'],
                $leadData['ip_address'],
                $leadData['referrer'],
                $leadData['nome'],
                $leadData['email'],
                $leadData['telefone'],
                $leadData['whatsapp'],
                $leadData['assunto'],
                $leadData['mensagem'],
                $leadData['form_data'],
                $leadData['utm_source'],
                $leadData['utm_medium'],
                $leadData['utm_campaign'],
                $leadData['utm_term'],
                $leadData['utm_content'],
                $leadData['status'],
                $leadData['email_sent']
            ]);

            $leadId = $this->db->lastInsertId();
            return ['success' => true, 'lead_id' => $leadId];
            
        } catch (\Exception $e) {
            error_log("Erro ao capturar lead: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Atualizar status do email enviado
     */
    public function marcarEmailEnviado($leadId, $sucesso = true)
    {
        $sql = "UPDATE leads SET email_sent = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->executeUpdate($sql, [$sucesso ? 1 : 0, $leadId]);
    }

    /**
     * Detectar página de origem baseada na URL
     */
    private function detectSourcePage($url)
    {
        if (empty($url)) return 'unknown';
        
        $patterns = [
            'home' => ['/', '/index', '/home'],
            'contato' => ['/contato'],
            'empreendimentos' => ['/empreendimentos', '/empreendimento'],
            'sobre' => ['/sobre', '/sobre-nos'],
            'noticias' => ['/noticias', '/noticia']
        ];
        
        foreach ($patterns as $page => $urls) {
            foreach ($urls as $pattern) {
                if (strpos($url, $pattern) !== false) {
                    return $page;
                }
            }
        }
        
        return 'other';
    }

    /**
     * Extrair parâmetros UTM da requisição
     */
    private function extractUtmData(Request $request)
    {
        return [
            'utm_source' => $request->query->get('utm_source'),
            'utm_medium' => $request->query->get('utm_medium'),
            'utm_campaign' => $request->query->get('utm_campaign'),
            'utm_term' => $request->query->get('utm_term'),
            'utm_content' => $request->query->get('utm_content')
        ];
    }

    /**
     * Buscar leads com filtros
     */
    public function buscarLeads($filtros = [], $limit = 50, $offset = 0)
    {
        $sql = "SELECT * FROM leads WHERE deleted_at IS NULL";
        $params = [];

        if (!empty($filtros['source_page'])) {
            $sql .= " AND source_page = ?";
            $params[] = $filtros['source_page'];
        }

        if (!empty($filtros['form_type'])) {
            $sql .= " AND form_type = ?";
            $params[] = $filtros['form_type'];
        }

        if (!empty($filtros['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filtros['status'];
        }

        if (!empty($filtros['data_inicio'])) {
            $sql .= " AND created_at >= ?";
            $params[] = $filtros['data_inicio'];
        }

        if (!empty($filtros['data_fim'])) {
            $sql .= " AND created_at <= ?";
            $params[] = $filtros['data_fim'];
        }

        $sql .= " ORDER BY created_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Contar total de leads com filtros
     */
    public function contarLeads($filtros = [])
    {
        $sql = "SELECT COUNT(*) as total FROM leads WHERE deleted_at IS NULL";
        $params = [];

        if (!empty($filtros['source_page'])) {
            $sql .= " AND source_page = ?";
            $params[] = $filtros['source_page'];
        }

        if (!empty($filtros['form_type'])) {
            $sql .= " AND form_type = ?";
            $params[] = $filtros['form_type'];
        }

        if (!empty($filtros['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filtros['status'];
        }

        if (!empty($filtros['data_inicio'])) {
            $sql .= " AND created_at >= ?";
            $params[] = $filtros['data_inicio'];
        }

        if (!empty($filtros['data_fim'])) {
            $sql .= " AND created_at <= ?";
            $params[] = $filtros['data_fim'];
        }

        $result = $this->db->fetchAssoc($sql, $params);
        return $result['total'];
    }

    /**
     * Exportar leads para CSV
     */
    public function exportarCSV($filtros = [])
    {
        $leads = $this->buscarLeads($filtros, 10000, 0); // Limite alto para export

        $csv = [];
        $csv[] = [
            'ID', 'Data/Hora', 'Nome', 'Email', 'Telefone', 'WhatsApp', 
            'Página Origem', 'Tipo Formulário', 'Assunto', 'Mensagem', 
            'Status', 'UTM Source', 'UTM Campaign', 'IP'
        ];

        foreach ($leads as $lead) {
            $csv[] = [
                $lead['id'],
                $lead['created_at'],
                $lead['nome'],
                $lead['email'],
                $lead['telefone'],
                $lead['whatsapp'],
                $lead['source_page'],
                $lead['form_type'],
                $lead['assunto'],
                $lead['mensagem'],
                $lead['status'],
                $lead['utm_source'],
                $lead['utm_campaign'],
                $lead['ip_address']
            ];
        }

        return $csv;
    }
}