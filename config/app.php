<?php

/**
 * Configurações gerais da aplicação
 * Auto Portas - Sistema de Gestão
 */

return [
    // Nome da aplicação
    'name' => 'Auto Portas',
    
    // URL base da aplicação
    'base_url' => 'http://localhost/AutoPortas/public',
    
    // Ambiente: development | production
    'environment' => 'development',
    
    // Timezone
    'timezone' => 'America/Sao_Paulo',
    
    // Charset padrão
    'charset' => 'UTF-8',
    
    // Configurações de sessão
    'session' => [
        'name' => 'autoportas_session',
        'lifetime' => 7200, // 2 horas
    ],
    
    // Configurações de paginação
    'pagination' => [
        'per_page' => 15,
    ],
    
    // Versão do sistema
    'version' => '1.0.0',
];

