<?php

/**
 * Configurações de autenticação
 * Auto Portas - Sistema de Gestão
 * 
 * IMPORTANTE: As credenciais são lidas do arquivo .env
 * Nunca commite o arquivo .env para o Git!
 */

return [
    // Credenciais do administrador (lidas do .env)
    'admin' => [
        'usuario' => $_ENV['ADMIN_USER'] ?? 'admin',
        'senha' => $_ENV['ADMIN_PASS'] ?? 'admin123',
        'nome' => $_ENV['ADMIN_NAME'] ?? 'Administrador',
    ],
    
    // Configurações de sessão
    'session_key' => 'autoportas_user',
    
    // Tempo de expiração da sessão (em segundos)
    // 0 = expira ao fechar o navegador
    'session_lifetime' => 0,
];
