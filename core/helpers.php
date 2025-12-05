<?php

/**
 * Funções auxiliares globais
 * Auto Portas - Sistema de Gestão
 */

use Core\Router;

/**
 * Retorna a URL base da aplicação
 */
function base_url(string $path = ''): string
{
    $config = require CONFIG_PATH . '/app.php';
    return rtrim($config['base_url'], '/') . '/' . ltrim($path, '/');
}

/**
 * Retorna a URL de um asset
 */
function asset(string $path): string
{
    return base_url($path);
}

/**
 * Retorna a URL de uma rota nomeada
 */
function route(string $name, array $params = []): string
{
    return Router::route($name, $params);
}

/**
 * Escapa HTML para prevenir XSS
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Formata um valor para moeda brasileira
 */
function money(float $value): string
{
    return 'R$ ' . number_format($value, 2, ',', '.');
}

/**
 * Formata uma data
 */
function format_date(?string $date, string $format = 'd/m/Y'): string
{
    if (empty($date)) {
        return '';
    }
    return date($format, strtotime($date));
}

/**
 * Formata data e hora
 */
function format_datetime(?string $datetime, string $format = 'd/m/Y H:i'): string
{
    if (empty($datetime)) {
        return '';
    }
    return date($format, strtotime($datetime));
}

/**
 * Formata um telefone
 */
function format_phone(?string $phone): string
{
    if (empty($phone)) {
        return '';
    }
    
    $phone = preg_replace('/\D/', '', $phone);
    
    if (strlen($phone) === 11) {
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
    } elseif (strlen($phone) === 10) {
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6);
    }
    
    return $phone;
}

/**
 * Formata um CPF
 */
function format_cpf(?string $cpf): string
{
    if (empty($cpf)) {
        return '';
    }
    
    $cpf = preg_replace('/\D/', '', $cpf);
    
    if (strlen($cpf) === 11) {
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
    }
    
    return $cpf;
}

/**
 * Formata um CNPJ
 */
function format_cnpj(?string $cnpj): string
{
    if (empty($cnpj)) {
        return '';
    }
    
    $cnpj = preg_replace('/\D/', '', $cnpj);
    
    if (strlen($cnpj) === 14) {
        return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12);
    }
    
    return $cnpj;
}

/**
 * Formata CPF ou CNPJ automaticamente
 */
function format_document(?string $doc): string
{
    if (empty($doc)) {
        return '';
    }
    
    $doc = preg_replace('/\D/', '', $doc);
    
    if (strlen($doc) === 11) {
        return format_cpf($doc);
    } elseif (strlen($doc) === 14) {
        return format_cnpj($doc);
    }
    
    return $doc;
}

/**
 * Verifica se é uma requisição AJAX
 */
function is_ajax(): bool
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Retorna o valor de um campo POST com escape
 */
function old(string $field, string $default = ''): string
{
    return e($_POST[$field] ?? $default);
}

/**
 * Debug formatado
 */
function dd($var): void
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}

/**
 * Gera um token CSRF
 */
function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Campo hidden com token CSRF
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Verifica o token CSRF
 */
function verify_csrf(string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Retorna classe CSS para status
 */
function status_class(string $status): string
{
    $classes = [
        'pendente' => 'warning',
        'em_andamento' => 'info',
        'concluido' => 'success',
        'cancelado' => 'danger',
        'ativo' => 'success',
        'inativo' => 'secondary',
    ];
    
    return $classes[$status] ?? 'secondary';
}

/**
 * Retorna texto formatado para status
 */
function status_text(string $status): string
{
    $texts = [
        'pendente' => 'Pendente',
        'em_andamento' => 'Em Andamento',
        'concluido' => 'Concluído',
        'cancelado' => 'Cancelado',
        'ativo' => 'Ativo',
        'inativo' => 'Inativo',
    ];
    
    return $texts[$status] ?? ucfirst($status);
}

