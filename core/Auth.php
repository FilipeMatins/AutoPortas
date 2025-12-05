<?php

namespace Core;

/**
 * Classe de Autenticação
 * Gerencia verificação de login e proteção de rotas
 */
class Auth
{
    protected static $config = null;
    
    /**
     * Carrega as configurações de autenticação
     */
    protected static function loadConfig(): array
    {
        if (self::$config === null) {
            self::$config = require CONFIG_PATH . '/auth.php';
        }
        return self::$config;
    }
    
    /**
     * Verifica se o usuário está logado
     */
    public static function check(): bool
    {
        $config = self::loadConfig();
        return isset($_SESSION[$config['session_key']]);
    }
    
    /**
     * Retorna os dados do usuário logado
     */
    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        
        $config = self::loadConfig();
        return $_SESSION[$config['session_key']];
    }
    
    /**
     * Retorna o nome do usuário logado
     */
    public static function userName(): string
    {
        $user = self::user();
        return $user['nome'] ?? 'Usuário';
    }
    
    /**
     * Requer autenticação - redireciona se não logado
     */
    public static function require(): void
    {
        if (!self::check()) {
            header('Location: ' . base_url('login'));
            exit;
        }
    }
    
    /**
     * Faz logout do usuário
     */
    public static function logout(): void
    {
        $config = self::loadConfig();
        unset($_SESSION[$config['session_key']]);
    }
}

