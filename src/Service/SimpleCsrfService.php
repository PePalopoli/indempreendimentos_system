<?php

namespace Palopoli\PaloSystem\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SimpleCsrfService
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Gerar token CSRF simples usando sessão
     */
    public function generateToken($formType = 'default')
    {
        // Verificar se já existe um token válido para este tipo
        $existingToken = $this->getValidToken($formType);
        if ($existingToken) {
            error_log("SIMPLE CSRF: Reutilizando token existente para $formType: " . substr($existingToken, 0, 10) . "...");
            return $existingToken;
        }
        
        $token = bin2hex(random_bytes(32));
        
        // Armazenar na sessão com timestamp
        $tokenData = [
            'token' => $token,
            'created_at' => time(),
            'expires_at' => time() + 7200 // 2 horas (aumentando tempo)
        ];
        
        $this->session->set("csrf_token_{$formType}", $tokenData);
        
        error_log("SIMPLE CSRF: Novo token gerado para $formType: " . substr($token, 0, 10) . "...");
        error_log("SIMPLE CSRF: Session ID: " . $this->session->getId());
        
        return $token;
    }

    /**
     * Obter token válido existente
     */
    private function getValidToken($formType)
    {
        $sessionKey = "csrf_token_{$formType}";
        $tokenData = $this->session->get($sessionKey);

        if (!$tokenData) {
            return null;
        }

        // Verificar se expirou
        if (time() > $tokenData['expires_at']) {
            $this->session->remove($sessionKey);
            return null;
        }

        return $tokenData['token'];
    }

    /**
     * Validar token CSRF
     */
    public function validateToken($token, $formType = 'default')
    {
        if (empty($token)) {
            error_log("SIMPLE CSRF: Token vazio para $formType");
            return false;
        }

        error_log("SIMPLE CSRF: Validando token para $formType: " . substr($token, 0, 10) . "...");
        error_log("SIMPLE CSRF: Session ID na validação: " . $this->session->getId());

        $sessionKey = "csrf_token_{$formType}";
        $tokenData = $this->session->get($sessionKey);

        if (!$tokenData) {
            error_log("SIMPLE CSRF: Nenhum token na sessão para $formType");
            // Debug: mostrar todas as chaves da sessão
            $allKeys = array_keys($this->session->all());
            error_log("SIMPLE CSRF: Chaves na sessão: " . implode(', ', $allKeys));
            return false;
        }

        error_log("SIMPLE CSRF: Token encontrado na sessão, expira em: " . date('Y-m-d H:i:s', $tokenData['expires_at']));

        // Verificar se expirou
        if (time() > $tokenData['expires_at']) {
            error_log("SIMPLE CSRF: Token expirado para $formType");
            $this->session->remove($sessionKey);
            return false;
        }

        // Verificar se o token confere
        if ($token !== $tokenData['token']) {
            error_log("SIMPLE CSRF: Token não confere para $formType");
            error_log("SIMPLE CSRF: Token recebido: " . substr($token, 0, 20) . "...");
            error_log("SIMPLE CSRF: Token esperado: " . substr($tokenData['token'], 0, 20) . "...");
            return false;
        }

        error_log("SIMPLE CSRF: Token válido para $formType!");
        return true;
    }

    /**
     * Verificar submissão de formulário
     */
    public function verifyFormSubmission(Request $request, $formType = 'default')
    {
        $token = $request->request->get('csrf_token');
        
        error_log("SIMPLE CSRF: Verificando submissão - FormType: $formType, Token: " . substr($token, 0, 10) . "...");
        
        return $this->validateToken($token, $formType);
    }
}