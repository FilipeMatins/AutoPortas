<?php

namespace Core;

/**
 * Classe base Controller
 * Todos os controllers devem estender esta classe
 */
abstract class Controller
{
    protected $data = [];
    
    /**
     * Verifica se o usuário está autenticado
     * Redireciona para login se não estiver
     */
    protected function requireAuth(): void
    {
        Auth::require();
    }
    
    /**
     * Renderiza uma view
     */
    protected function view(string $view, array $data = [], ?string $layout = 'main'): void
    {
        // Mescla os dados
        $this->data = array_merge($this->data, $data);
        
        // Extrai as variáveis para uso na view
        extract($this->data);
        
        // Caminho da view
        $viewPath = VIEWS_PATH . '/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$view} não encontrada.");
        }
        
        // Se não houver layout, renderiza apenas a view
        if ($layout === null) {
            require $viewPath;
            return;
        }
        
        // Captura o conteúdo da view
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        
        // Renderiza o layout com o conteúdo
        $layoutPath = VIEWS_PATH . '/layouts/' . $layout . '.php';
        
        if (!file_exists($layoutPath)) {
            throw new \Exception("Layout {$layout} não encontrado.");
        }
        
        require $layoutPath;
    }
    
    /**
     * Renderiza uma view parcial
     */
    protected function partial(string $partial, array $data = []): void
    {
        extract(array_merge($this->data, $data));
        
        $partialPath = VIEWS_PATH . '/partials/' . str_replace('.', '/', $partial) . '.php';
        
        if (file_exists($partialPath)) {
            require $partialPath;
        }
    }
    
    /**
     * Redireciona para outra URL
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Redireciona para uma rota nomeada
     */
    protected function redirectToRoute(string $name, array $params = []): void
    {
        $url = Router::route($name, $params);
        $this->redirect($url);
    }
    
    /**
     * Retorna uma resposta JSON
     */
    protected function json($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Define uma mensagem flash na sessão
     */
    protected function flash(string $key, string $message): void
    {
        $_SESSION['flash'][$key] = $message;
    }
    
    /**
     * Obtém e remove uma mensagem flash
     */
    protected function getFlash(string $key): ?string
    {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
    
    /**
     * Valida dados de entrada
     */
    protected function validate(array $data, array $rules): array
    {
        $errors = [];
        
        foreach ($rules as $field => $ruleString) {
            $fieldRules = explode('|', $ruleString);
            $value = $data[$field] ?? null;
            
            foreach ($fieldRules as $rule) {
                $error = $this->applyRule($field, $value, $rule);
                if ($error) {
                    $errors[$field][] = $error;
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Aplica uma regra de validação
     */
    protected function applyRule(string $field, $value, string $rule): ?string
    {
        $params = [];
        
        if (strpos($rule, ':') !== false) {
            list($rule, $paramString) = explode(':', $rule);
            $params = explode(',', $paramString);
        }
        
        switch ($rule) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    return "O campo {$field} é obrigatório.";
                }
                break;
                
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return "O campo {$field} deve ser um email válido.";
                }
                break;
                
            case 'min':
                if (strlen($value) < $params[0]) {
                    return "O campo {$field} deve ter no mínimo {$params[0]} caracteres.";
                }
                break;
                
            case 'max':
                if (strlen($value) > $params[0]) {
                    return "O campo {$field} deve ter no máximo {$params[0]} caracteres.";
                }
                break;
                
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    return "O campo {$field} deve ser numérico.";
                }
                break;
        }
        
        return null;
    }
}

