<?php

namespace Palopoli\PaloSystem\Service;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;

class CsrfService
{
    private $db;
    private $tokenExpiration = 7200; // 2 horas (aumentando o tempo)

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Gerar token CSRF
     */
    public function generateToken($formType = 'default', Request $request = null)
    {
        // Verificar se já existe um token válido para este tipo de formulário
        $existingToken = $this->getValidToken($formType);
        if ($existingToken) {
            error_log("CSRF: Reutilizando token existente para FormType: $formType");
            return $existingToken;
        }
        
        // Limpar tokens expirados apenas ocasionalmente (10% das vezes)
        if (rand(1, 10) === 1) {
            $this->cleanExpiredTokens();
        }
        
        $token = bin2hex(random_bytes(32));
        $ipAddress = $request ? $request->getClientIp() : null;
        $expiresAt = date('Y-m-d H:i:s', time() + $this->tokenExpiration);

        $sql = "INSERT INTO csrf_tokens (token, form_type, ip_address, expires_at) VALUES (?, ?, ?, ?)";
        
        try {
            $this->db->executeUpdate($sql, [$token, $formType, $ipAddress, $expiresAt]);
            error_log("CSRF: Novo token gerado - FormType: $formType, Expira: $expiresAt");
            return $token;
        } catch (\Exception $e) {
            error_log("CSRF: Erro ao gerar token: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Validar token CSRF
     */
    public function validateToken($token, $formType = 'default', Request $request = null)
    {
        if (empty($token)) {
            error_log("CSRF: Token vazio");
            return false;
        }

        // Primeiro vamos verificar se o token existe e está válido
        $sql = "SELECT id, expires_at, created_at FROM csrf_tokens WHERE token = ? AND form_type = ?";
        $params = [$token, $formType];

        try {
            $result = $this->db->fetchAssoc($sql, $params);
            
            if (!$result) {
                error_log("CSRF: Token não encontrado - Token: $token, FormType: $formType");
                return false;
            }

            // Verificar se o token não expirou
            $now = new \DateTime();
            $expiresAt = new \DateTime($result['expires_at']);
            
            if ($now > $expiresAt) {
                error_log("CSRF: Token expirado - Criado: {$result['created_at']}, Expira: {$result['expires_at']}, Agora: " . $now->format('Y-m-d H:i:s'));
                return false;
            }

            // Token válido - NÃO marcar como usado para permitir reenvios
            error_log("CSRF: Token válido - FormType: $formType");
            return true;
            
        } catch (\Exception $e) {
            error_log("CSRF: Erro ao validar token: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Marcar token como usado
     */
    private function markTokenAsUsed($tokenId)
    {
        $sql = "UPDATE csrf_tokens SET used = 1 WHERE id = ?";
        $this->db->executeUpdate($sql, [$tokenId]);
    }

    /**
     * Obter token válido existente
     */
    private function getValidToken($formType)
    {
        $sql = "SELECT token FROM csrf_tokens WHERE form_type = ? AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1";
        
        try {
            $result = $this->db->fetchAssoc($sql, [$formType]);
            return $result ? $result['token'] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Limpar tokens expirados
     */
    public function cleanExpiredTokens()
    {
        $sql = "DELETE FROM csrf_tokens WHERE expires_at < NOW()";
        $deleted = $this->db->executeUpdate($sql);
        if ($deleted > 0) {
            error_log("CSRF: Removidos $deleted tokens expirados");
        }
    }

    /**
     * Verificar se formulário tem proteção CSRF válida
     */
    public function verifyFormSubmission(Request $request, $formType = 'default')
    {
        $token = $request->request->get('csrf_token');
        
        error_log("CSRF: Verificando submissão - FormType: $formType, Token: " . substr($token, 0, 10) . "...");
        
        if (!$this->validateToken($token, $formType, $request)) {
            error_log("CSRF: Token inválido para formulário: $formType. Token recebido: " . substr($token, 0, 10) . "...");
            return false;
        }
        
        error_log("CSRF: Submissão válida para FormType: $formType");
        return true;
    }

    /**
     * Debug: Verificar status de todos os tokens
     */
    public function debugTokens()
    {
        $sql = "SELECT form_type, token, created_at, expires_at, used FROM csrf_tokens ORDER BY created_at DESC LIMIT 10";
        $tokens = $this->db->fetchAll($sql);
        
        error_log("CSRF DEBUG: Tokens atuais:");
        foreach ($tokens as $token) {
            $status = (strtotime($token['expires_at']) > time()) ? 'VÁLIDO' : 'EXPIRADO';
            $used = $token['used'] ? 'USADO' : 'DISPONÍVEL';
            error_log("  - FormType: {$token['form_type']}, Token: " . substr($token['token'], 0, 10) . "..., Status: $status, $used, Criado: {$token['created_at']}, Expira: {$token['expires_at']}");
        }
    }
}