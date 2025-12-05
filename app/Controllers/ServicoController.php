<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Servico;

/**
 * Controller de Serviços
 */
class ServicoController extends Controller
{
    protected $servicoModel;
    
    public function __construct()
    {
        // Requer autenticação em todas as ações
        $this->requireAuth();
        
        $this->servicoModel = new Servico();
    }
    
    /**
     * Lista todos os serviços
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $servicos = $this->servicoModel->paginate($page);
        
        $this->view('servicos/index', [
            'title' => 'Serviços',
            'servicos' => $servicos,
        ]);
    }
    
    /**
     * Exibe o formulário de criação
     */
    public function create(): void
    {
        $this->view('servicos/create', [
            'title' => 'Novo Serviço',
        ]);
    }
    
    /**
     * Salva um novo serviço
     */
    public function store(): void
    {
        $data = [
            'nome' => $_POST['nome'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'preco' => str_replace(['.', ','], ['', '.'], $_POST['preco'] ?? '0'),
            'categoria' => $_POST['categoria'] ?? '',
            'tempo_estimado' => $_POST['tempo_estimado'] ?? '',
            'status' => $_POST['status'] ?? 'ativo',
        ];
        
        $errors = $this->validate($data, [
            'nome' => 'required|min:3',
            'preco' => 'required|numeric',
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->redirect(base_url('servicos/novo'));
            return;
        }
        
        $this->servicoModel->create($data);
        
        $this->flash('success', 'Serviço cadastrado com sucesso!');
        $this->redirect(base_url('servicos'));
    }
    
    /**
     * Exibe os detalhes de um serviço
     */
    public function show(int $id): void
    {
        $servico = $this->servicoModel->find($id);
        
        if (!$servico) {
            $this->flash('error', 'Serviço não encontrado.');
            $this->redirect(base_url('servicos'));
            return;
        }
        
        $this->view('servicos/show', [
            'title' => 'Detalhes do Serviço',
            'servico' => $servico,
        ]);
    }
    
    /**
     * Exibe o formulário de edição
     */
    public function edit(int $id): void
    {
        $servico = $this->servicoModel->find($id);
        
        if (!$servico) {
            $this->flash('error', 'Serviço não encontrado.');
            $this->redirect(base_url('servicos'));
            return;
        }
        
        $this->view('servicos/edit', [
            'title' => 'Editar Serviço',
            'servico' => $servico,
        ]);
    }
    
    /**
     * Atualiza um serviço
     */
    public function update(int $id): void
    {
        $servico = $this->servicoModel->find($id);
        
        if (!$servico) {
            $this->flash('error', 'Serviço não encontrado.');
            $this->redirect(base_url('servicos'));
            return;
        }
        
        $data = [
            'nome' => $_POST['nome'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'preco' => str_replace(['.', ','], ['', '.'], $_POST['preco'] ?? '0'),
            'categoria' => $_POST['categoria'] ?? '',
            'tempo_estimado' => $_POST['tempo_estimado'] ?? '',
            'status' => $_POST['status'] ?? 'ativo',
        ];
        
        $errors = $this->validate($data, [
            'nome' => 'required|min:3',
            'preco' => 'required|numeric',
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->redirect(base_url("servicos/{$id}/editar"));
            return;
        }
        
        $this->servicoModel->update($id, $data);
        
        $this->flash('success', 'Serviço atualizado com sucesso!');
        $this->redirect(base_url('servicos'));
    }
    
    /**
     * Deleta um serviço
     */
    public function destroy(int $id): void
    {
        $servico = $this->servicoModel->find($id);
        
        if (!$servico) {
            $this->flash('error', 'Serviço não encontrado.');
            $this->redirect(base_url('servicos'));
            return;
        }
        
        $this->servicoModel->delete($id);
        
        $this->flash('success', 'Serviço excluído com sucesso!');
        $this->redirect(base_url('servicos'));
    }
}

