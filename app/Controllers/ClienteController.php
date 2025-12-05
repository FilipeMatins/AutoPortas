<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Cliente;

/**
 * Controller de Clientes
 */
class ClienteController extends Controller
{
    protected $clienteModel;
    
    public function __construct()
    {
        // Requer autenticação em todas as ações
        $this->requireAuth();
        
        $this->clienteModel = new Cliente();
    }
    
    /**
     * Lista todos os clientes
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $clientes = $this->clienteModel->paginate($page);
        
        $this->view('clientes/index', [
            'title' => 'Clientes',
            'clientes' => $clientes,
        ]);
    }
    
    /**
     * Exibe o formulário de criação
     */
    public function create(): void
    {
        $this->view('clientes/create', [
            'title' => 'Novo Cliente',
        ]);
    }
    
    /**
     * Salva um novo cliente
     */
    public function store(): void
    {
        $data = [
            'nome' => $_POST['nome'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telefone' => $_POST['telefone'] ?? '',
            'cpf_cnpj' => $_POST['cpf_cnpj'] ?? '',
            'endereco' => $_POST['endereco'] ?? '',
            'cidade' => $_POST['cidade'] ?? '',
            'estado' => $_POST['estado'] ?? '',
            'cep' => $_POST['cep'] ?? '',
            'observacoes' => $_POST['observacoes'] ?? '',
        ];
        
        $errors = $this->validate($data, [
            'nome' => 'required|min:3',
            'email' => 'email',
            'telefone' => 'required',
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->redirect(base_url('clientes/novo'));
            return;
        }
        
        $this->clienteModel->create($data);
        
        $this->flash('success', 'Cliente cadastrado com sucesso!');
        $this->redirect(base_url('clientes'));
    }
    
    /**
     * Exibe os detalhes de um cliente
     */
    public function show(int $id): void
    {
        $cliente = $this->clienteModel->find($id);
        
        if (!$cliente) {
            $this->flash('error', 'Cliente não encontrado.');
            $this->redirect(base_url('clientes'));
            return;
        }
        
        $this->view('clientes/show', [
            'title' => 'Detalhes do Cliente',
            'cliente' => $cliente,
        ]);
    }
    
    /**
     * Exibe o formulário de edição
     */
    public function edit(int $id): void
    {
        $cliente = $this->clienteModel->find($id);
        
        if (!$cliente) {
            $this->flash('error', 'Cliente não encontrado.');
            $this->redirect(base_url('clientes'));
            return;
        }
        
        $this->view('clientes/edit', [
            'title' => 'Editar Cliente',
            'cliente' => $cliente,
        ]);
    }
    
    /**
     * Atualiza um cliente
     */
    public function update(int $id): void
    {
        $cliente = $this->clienteModel->find($id);
        
        if (!$cliente) {
            $this->flash('error', 'Cliente não encontrado.');
            $this->redirect(base_url('clientes'));
            return;
        }
        
        $data = [
            'nome' => $_POST['nome'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telefone' => $_POST['telefone'] ?? '',
            'cpf_cnpj' => $_POST['cpf_cnpj'] ?? '',
            'endereco' => $_POST['endereco'] ?? '',
            'cidade' => $_POST['cidade'] ?? '',
            'estado' => $_POST['estado'] ?? '',
            'cep' => $_POST['cep'] ?? '',
            'observacoes' => $_POST['observacoes'] ?? '',
        ];
        
        $errors = $this->validate($data, [
            'nome' => 'required|min:3',
            'email' => 'email',
            'telefone' => 'required',
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->redirect(base_url("clientes/{$id}/editar"));
            return;
        }
        
        $this->clienteModel->update($id, $data);
        
        $this->flash('success', 'Cliente atualizado com sucesso!');
        $this->redirect(base_url('clientes'));
    }
    
    /**
     * Deleta um cliente
     */
    public function destroy(int $id): void
    {
        $cliente = $this->clienteModel->find($id);
        
        if (!$cliente) {
            $this->flash('error', 'Cliente não encontrado.');
            $this->redirect(base_url('clientes'));
            return;
        }
        
        $this->clienteModel->delete($id);
        
        $this->flash('success', 'Cliente excluído com sucesso!');
        $this->redirect(base_url('clientes'));
    }
}

