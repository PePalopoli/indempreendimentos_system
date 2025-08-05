<?php

namespace Palopoli\PaloSystem\Service;

use Symfony\Component\HttpFoundation\Request;

class UltraSimpleCsrf
{
    /**
     * Gerar token usando sessão nativa do PHP
     */
    public function generateToken($formType = 'default')
    {
        // Garantir que a sessão está iniciada
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        $sessionKey = "csrf_{$formType}";
        
        // Verificar se já existe um token válido
        if (isset($_SESSION[$sessionKey])) {
            $tokenData = $_SESSION[$sessionKey];
            
            // Verificar se ainda é válido (2 horas)
            if (time() < $tokenData['expires']) {
                error_log("ULTRA CSRF: Reutilizando token para $formType");
                return $tokenData['token'];
            }
        }
        
        // Gerar novo token
        $token = bin2hex(random_bytes(32));
        $_SESSION[$sessionKey] = [
            'token' => $token,
            'created' => time(),
            'expires' => time() + 7200 // 2 horas
        ];
        
        error_log("ULTRA CSRF: Novo token gerado para $formType - Session ID: " . session_id());
        error_log("ULTRA CSRF: Token: " . substr($token, 0, 10) . "...");
        
        return $token;
    }

    /**
     * Validar token
     */
    public function validateToken($token, $formType = 'default')
    {
        if (empty($token)) {
            error_log("ULTRA CSRF: Token vazio");
            return false;
        }

        // Garantir que a sessão está iniciada
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        error_log("ULTRA CSRF: Validando $formType - Session ID: " . session_id());
        error_log("ULTRA CSRF: Token recebido: " . substr($token, 0, 10) . "...");

        $sessionKey = "csrf_{$formType}";
        
        if (!isset($_SESSION[$sessionKey])) {
            error_log("ULTRA CSRF: Nenhum token na sessão para $formType");
            error_log("ULTRA CSRF: Chaves disponíveis: " . implode(', ', array_keys($_SESSION)));
            return false;
        }

        $tokenData = $_SESSION[$sessionKey];
        
        // Verificar expiração
        if (time() > $tokenData['expires']) {
            error_log("ULTRA CSRF: Token expirado para $formType");
            unset($_SESSION[$sessionKey]);
            return false;
        }

        // Verificar se confere
        if ($token !== $tokenData['token']) {
            error_log("ULTRA CSRF: Token não confere para $formType");
            error_log("ULTRA CSRF: Esperado: " . substr($tokenData['token'], 0, 15) . "...");
            error_log("ULTRA CSRF: Recebido: " . substr($token, 0, 15) . "...");
            return false;
        }

        error_log("ULTRA CSRF: Token VÁLIDO para $formType!");
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