<?php

namespace App\Controllers;

use Core\Controller;

/**
 * Controller de Autenticação
 */
class AuthController extends Controller
{
    /**
     * Exibe a tela de login
     */
    public function login(): void
    {
        // Se já estiver logado, redireciona para o dashboard
        if ($this->isLoggedIn()) {
            $this->redirect(base_url('/'));
            return;
        }
        
        $this->view('auth/login', [
            'title' => 'Login',
        ], null); // Sem layout
    }
    
    /**
     * Processa o login
     */
    public function authenticate(): void
    {
        $usuario = $_POST['usuario'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $lembrar = isset($_POST['lembrar']);
        
        // Carrega as configurações de autenticação
        $authConfig = require CONFIG_PATH . '/auth.php';
        $admin = $authConfig['admin'];
        
        // Verifica as credenciais
        if ($usuario === $admin['usuario'] && $senha === $admin['senha']) {
            // Login bem-sucedido
            $_SESSION[$authConfig['session_key']] = [
                'usuario' => $admin['usuario'],
                'nome' => $admin['nome'],
                'logged_at' => time(),
            ];
            
            // Se marcou "lembrar-me", estende a sessão
            if ($lembrar) {
                $lifetime = 60 * 60 * 24 * 30; // 30 dias
                setcookie(session_name(), session_id(), time() + $lifetime, '/');
            }
            
            $this->redirect(base_url('/'));
        } else {
            // Login falhou
            $_SESSION['login_error'] = 'Usuário ou senha incorretos.';
            $_SESSION['login_usuario'] = $usuario;
            $this->redirect(base_url('login'));
        }
    }
    
    /**
     * Faz logout
     */
    public function logout(): void
    {
        $authConfig = require CONFIG_PATH . '/auth.php';
        
        // Remove os dados da sessão
        unset($_SESSION[$authConfig['session_key']]);
        
        // Destrói a sessão completamente
        session_destroy();
        
        // Redireciona para o login
        $this->redirect(base_url('login'));
    }
    
    /**
     * Verifica se o usuário está logado
     */
    protected function isLoggedIn(): bool
    {
        $authConfig = require CONFIG_PATH . '/auth.php';
        return isset($_SESSION[$authConfig['session_key']]);
    }
}

