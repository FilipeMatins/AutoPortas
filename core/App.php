<?php

namespace Core;

/**
 * Classe principal da aplicação
 * Responsável por inicializar e gerenciar o ciclo de vida da aplicação
 */
class App
{
    protected static $instance = null;
    protected $config = [];
    
    /**
     * Construtor privado (Singleton)
     */
    private function __construct()
    {
        $this->loadConfig();
        $this->setTimezone();
        $this->startSession();
    }
    
    /**
     * Retorna a instância única da aplicação
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Carrega as configurações
     */
    protected function loadConfig(): void
    {
        $this->config['app'] = require CONFIG_PATH . '/app.php';
        $this->config['database'] = require CONFIG_PATH . '/database.php';
    }
    
    /**
     * Define o timezone
     */
    protected function setTimezone(): void
    {
        date_default_timezone_set($this->config['app']['timezone']);
    }
    
    /**
     * Inicia a sessão
     */
    protected function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name($this->config['app']['session']['name']);
            session_start();
        }
    }
    
    /**
     * Retorna uma configuração específica
     */
    public function config(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    /**
     * Executa a aplicação
     */
    public function run(): void
    {
        try {
            $router = new Router();
            require ROUTES_PATH . '/web.php';
            $router->dispatch();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * Trata exceções
     */
    protected function handleException(\Exception $e): void
    {
        if ($this->config('app.environment') === 'development') {
            echo "<h1>Erro</h1>";
            echo "<p><strong>Mensagem:</strong> " . $e->getMessage() . "</p>";
            echo "<p><strong>Arquivo:</strong> " . $e->getFile() . "</p>";
            echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        } else {
            // Em produção, redireciona para página de erro
            header('Location: ' . $this->config('app.base_url') . '/erro');
        }
    }
}

