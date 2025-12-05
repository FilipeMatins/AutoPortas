<?php

/**
 * Auto Portas - Sistema de Gestão
 * Arquivo de entrada principal
 */

// Define constantes de caminho
define('ROOT_PATH', dirname(__DIR__));

// Carrega variáveis de ambiente do arquivo .env
$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignora comentários
        if (strpos(trim($line), '#') === 0) continue;
        
        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove aspas se houver
            $value = trim($value, '"\'');
            
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CORE_PATH', ROOT_PATH . '/core');
define('VIEWS_PATH', APP_PATH . '/Views');
define('ROUTES_PATH', ROOT_PATH . '/routes');
define('PUBLIC_PATH', __DIR__);

// Autoloader simples
spl_autoload_register(function ($class) {
    // Converte namespace para caminho de arquivo
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    
    // Mapeia namespaces para diretórios
    $paths = [
        'Core' => CORE_PATH,
        'App' => APP_PATH,
    ];
    
    foreach ($paths as $namespace => $path) {
        if (strpos($class, $namespace) === 0) {
            $file = str_replace($namespace, $path, $class) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Carrega helpers globais
require_once CORE_PATH . '/helpers.php';

// Inicializa e executa a aplicação
use Core\App;

$app = App::getInstance();
$app->run();

