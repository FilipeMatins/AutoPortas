<?php

namespace Core;

/**
 * Classe Router
 * Responsável pelo roteamento da aplicação
 */
class Router
{
    protected static $routes = [];
    protected static $namedRoutes = [];
    
    /**
     * Registra uma rota GET
     */
    public static function get(string $uri, $action, ?string $name = null): void
    {
        self::addRoute('GET', $uri, $action, $name);
    }
    
    /**
     * Registra uma rota POST
     */
    public static function post(string $uri, $action, ?string $name = null): void
    {
        self::addRoute('POST', $uri, $action, $name);
    }
    
    /**
     * Registra uma rota PUT
     */
    public static function put(string $uri, $action, ?string $name = null): void
    {
        self::addRoute('PUT', $uri, $action, $name);
    }
    
    /**
     * Registra uma rota DELETE
     */
    public static function delete(string $uri, $action, ?string $name = null): void
    {
        self::addRoute('DELETE', $uri, $action, $name);
    }
    
    /**
     * Adiciona uma rota à lista
     */
    protected static function addRoute(string $method, string $uri, $action, ?string $name): void
    {
        $uri = '/' . trim($uri, '/');
        
        self::$routes[$method][$uri] = $action;
        
        if ($name) {
            self::$namedRoutes[$name] = $uri;
        }
    }
    
    /**
     * Retorna a URL de uma rota nomeada
     */
    public static function route(string $name, array $params = []): string
    {
        $config = require CONFIG_PATH . '/app.php';
        $baseUrl = $config['base_url'];
        
        if (!isset(self::$namedRoutes[$name])) {
            return $baseUrl;
        }
        
        $uri = self::$namedRoutes[$name];
        
        foreach ($params as $key => $value) {
            $uri = str_replace('{' . $key . '}', $value, $uri);
        }
        
        return $baseUrl . $uri;
    }
    
    /**
     * Despacha a requisição para o controller apropriado
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();
        
        // Suporte para _method em formulários (PUT, DELETE)
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        
        if (!isset(self::$routes[$method])) {
            $this->notFound();
            return;
        }
        
        foreach (self::$routes[$method] as $route => $action) {
            $pattern = $this->convertToRegex($route);
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $this->executeAction($action, $matches);
                return;
            }
        }
        
        $this->notFound();
    }
    
    /**
     * Obtém a URI atual
     */
    protected function getUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        
        // Remove o caminho base da aplicação
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/' && $basePath !== '\\') {
            $uri = str_replace($basePath, '', $uri);
        }
        
        return '/' . trim($uri, '/');
    }
    
    /**
     * Converte uma rota para expressão regular
     */
    protected function convertToRegex(string $route): string
    {
        $route = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $route);
        return '#^' . $route . '$#';
    }
    
    /**
     * Executa a ação do controller
     */
    protected function executeAction($action, array $params = []): void
    {
        if (is_callable($action)) {
            call_user_func_array($action, $params);
            return;
        }
        
        if (is_string($action)) {
            list($controller, $method) = explode('@', $action);
            $controllerClass = "App\\Controllers\\{$controller}";
            
            if (!class_exists($controllerClass)) {
                throw new \Exception("Controller {$controllerClass} não encontrado.");
            }
            
            $controllerInstance = new $controllerClass();
            
            if (!method_exists($controllerInstance, $method)) {
                throw new \Exception("Método {$method} não encontrado em {$controllerClass}.");
            }
            
            call_user_func_array([$controllerInstance, $method], $params);
        }
    }
    
    /**
     * Página não encontrada
     */
    protected function notFound(): void
    {
        http_response_code(404);
        $viewPath = VIEWS_PATH . '/errors/404.php';
        
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "<h1>404 - Página não encontrada</h1>";
        }
    }
}

