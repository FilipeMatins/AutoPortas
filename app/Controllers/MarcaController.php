<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Marca;

/**
 * Controller de Marcas
 */
class MarcaController extends Controller
{
    protected $marcaModel;
    
    public function __construct()
    {
        $this->requireAuth();
        $this->marcaModel = new Marca();
    }
    
    /**
     * Lista todas as marcas
     */
    public function index(): void
    {
        $marcas = $this->marcaModel->getAllWithPecasCount();
        
        $this->view('marcas/index', [
            'title' => 'Marcas',
            'marcas' => $marcas,
        ]);
    }
    
    /**
     * Formulário de criação
     */
    public function create(): void
    {
        $this->view('marcas/create', [
            'title' => 'Nova Marca',
        ]);
    }
    
    /**
     * Salva nova marca
     */
    public function store(): void
    {
        $data = [
            'nome' => $_POST['nome'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'status' => $_POST['status'] ?? 'ativo',
        ];
        
        $errors = $this->validate($data, [
            'nome' => 'required|min:2',
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->redirect(base_url('marcas/novo'));
            return;
        }
        
        $this->marcaModel->create($data);
        
        $this->flash('success', 'Marca cadastrada com sucesso!');
        $this->redirect(base_url('marcas'));
    }
    
    /**
     * Formulário de edição
     */
    public function edit(int $id): void
    {
        $marca = $this->marcaModel->find($id);
        
        if (!$marca) {
            $this->flash('error', 'Marca não encontrada.');
            $this->redirect(base_url('marcas'));
            return;
        }
        
        $this->view('marcas/edit', [
            'title' => 'Editar Marca',
            'marca' => $marca,
        ]);
    }
    
    /**
     * Atualiza marca
     */
    public function update(int $id): void
    {
        $marca = $this->marcaModel->find($id);
        
        if (!$marca) {
            $this->flash('error', 'Marca não encontrada.');
            $this->redirect(base_url('marcas'));
            return;
        }
        
        $data = [
            'nome' => $_POST['nome'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'status' => $_POST['status'] ?? 'ativo',
        ];
        
        $this->marcaModel->update($id, $data);
        
        $this->flash('success', 'Marca atualizada com sucesso!');
        $this->redirect(base_url('marcas'));
    }
    
    /**
     * Exclui marca
     */
    public function destroy(int $id): void
    {
        $marca = $this->marcaModel->find($id);
        
        if (!$marca) {
            $this->flash('error', 'Marca não encontrada.');
            $this->redirect(base_url('marcas'));
            return;
        }
        
        $this->marcaModel->delete($id);
        
        $this->flash('success', 'Marca excluída com sucesso!');
        $this->redirect(base_url('marcas'));
    }
}

