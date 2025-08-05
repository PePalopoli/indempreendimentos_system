<?php

namespace Palopoli\PaloSystem\Service;

use Symfony\Component\HttpFoundation\Request;

class DirectCsrf
{
    /**
     * Gerar token de forma extremamente simples
     */
    public function generateToken($formType = 'default')
    {
        // Garantir sessão ativa
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        $key = "direct_csrf_{$formType}";
        
        // Se já existe e é válido, reutilizar
        if (isset($_SESSION[$key])) {
            $data = $_SESSION[$key];
            if (time() < $data['expires']) {
                return $data['token'];
            }
        }
        
        // Criar novo token
        $token = bin2hex(random_bytes(16)); // Token menor para facilitar debug
        $_SESSION[$key] = [
            'token' => $token,
            'expires' => time() + 3600, // 1 hora
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        error_log("DIRECT CSRF: Token criado para '$formType': $token");
        
        return $token;
    }
    
    /**
     * Validar token de forma extremamente simples
     */
    public function validateToken($token, $formType = 'default')
    {
        if (empty($token)) {
            error_log("DIRECT CSRF: Token vazio");
            return false;
        }
        
        // Garantir sessão ativa
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        $key = "direct_csrf_{$formType}";
        
        if (!isset($_SESSION[$key])) {
            error_log("DIRECT CSRF: Chave '$key' não existe na sessão");
            error_log("DIRECT CSRF: Chaves disponíveis: " . implode(', ', array_keys($_SESSION)));
            return false;
        }
        
        $data = $_SESSION[$key];
        
        if (time() > $data['expires']) {
            error_log("DIRECT CSRF: Token expirado para '$formType'");
            unset($_SESSION[$key]);
            return false;
        }
        
        if ($token !== $data['token']) {
            error_log("DIRECT CSRF: Token não confere");
            error_log("DIRECT CSRF: Esperado: '{$data['token']}'");
            error_log("DIRECT CSRF: Recebido: '$token'");
            return false;
        }
        
        error_log("DIRECT CSRF: Token VÁLIDO para '$formType'!");
        return true;
    }
    
    /**
     * Verificar formulário
     */
    public function verifyFormSubmission(Request $request, $formType = 'default')
    {
        $token = $request->request->get('csrf_token');
        return $this->validateToken($token, $formType);
    }
}