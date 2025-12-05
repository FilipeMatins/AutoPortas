<?php

/**
 * Configurações do banco de dados
 * Auto Portas - Sistema de Gestão
 * 
 * IMPORTANTE: As credenciais são lidas do arquivo .env
 * Nunca commite o arquivo .env para o Git!
 */

return [
    'driver' => 'mysql',
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'database' => $_ENV['DB_NAME'] ?? 'autoportas_db',
    'username' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASS'] ?? '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'port' => $_ENV['DB_PORT'] ?? 3306,
    
    // Configurações do PDO
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
