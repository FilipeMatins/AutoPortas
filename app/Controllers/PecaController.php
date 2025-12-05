<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Peca;
use App\Models\Marca;

/**
 * Controller de Peças
 */
class PecaController extends Controller
{
    protected $pecaModel;
    protected $marcaModel;
    
    public function __construct()
    {
        $this->requireAuth();
        $this->pecaModel = new Peca();
        $this->marcaModel = new Marca();
    }
    
    /**
     * Lista todas as peças
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $marcaId = !empty($_GET['marca']) ? (int) $_GET['marca'] : null;
        
        $pecas = $this->pecaModel->paginateWithMarca($page, 15, $marcaId);
        $marcas = $this->marcaModel->getAtivas();
        
        $this->view('pecas/index', [
            'title' => 'Peças',
            'pecas' => $pecas,
            'marcas' => $marcas,
            'marcaFilter' => $marcaId,
        ]);
    }
    
    /**
     * Formulário de criação
     */
    public function create(): void
    {
        $marcas = $this->marcaModel->getAtivas();
        
        $this->view('pecas/create', [
            'title' => 'Nova Peça',
            'marcas' => $marcas,
        ]);
    }
    
    /**
     * Salva nova peça
     */
    public function store(): void
    {
        $data = [
            'marca_id' => !empty($_POST['marca_id']) ? (int) $_POST['marca_id'] : null,
            'codigo' => $_POST['codigo'] ?? '',
            'nome' => $_POST['nome'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'preco_custo' => $this->parseDecimal($_POST['preco_custo'] ?? '0'),
            'preco_venda' => $this->parseDecimal($_POST['preco_venda'] ?? '0'),
            'quantidade_estoque' => (int) ($_POST['quantidade_estoque'] ?? 0),
            'estoque_minimo' => (int) ($_POST['estoque_minimo'] ?? 5),
            'localizacao' => $_POST['localizacao'] ?? '',
            'status' => $_POST['status'] ?? 'ativo',
        ];
        
        $errors = $this->validate($data, [
            'nome' => 'required|min:3',
            'preco_venda' => 'required|numeric',
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect(base_url('pecas/novo'));
            return;
        }
        
        $this->pecaModel->create($data);
        
        $this->flash('success', 'Peça cadastrada com sucesso!');
        $this->redirect(base_url('pecas'));
    }
    
    /**
     * Exibe detalhes de uma peça
     */
    public function show(int $id): void
    {
        $peca = $this->pecaModel->findWithMarca($id);
        
        if (!$peca) {
            $this->flash('error', 'Peça não encontrada.');
            $this->redirect(base_url('pecas'));
            return;
        }
        
        $this->view('pecas/show', [
            'title' => 'Detalhes da Peça',
            'peca' => $peca,
        ]);
    }
    
    /**
     * Formulário de edição
     */
    public function edit(int $id): void
    {
        $peca = $this->pecaModel->find($id);
        
        if (!$peca) {
            $this->flash('error', 'Peça não encontrada.');
            $this->redirect(base_url('pecas'));
            return;
        }
        
        $marcas = $this->marcaModel->getAtivas();
        
        $this->view('pecas/edit', [
            'title' => 'Editar Peça',
            'peca' => $peca,
            'marcas' => $marcas,
        ]);
    }
    
    /**
     * Atualiza peça
     */
    public function update(int $id): void
    {
        $peca = $this->pecaModel->find($id);
        
        if (!$peca) {
            $this->flash('error', 'Peça não encontrada.');
            $this->redirect(base_url('pecas'));
            return;
        }
        
        $data = [
            'marca_id' => !empty($_POST['marca_id']) ? (int) $_POST['marca_id'] : null,
            'codigo' => $_POST['codigo'] ?? '',
            'nome' => $_POST['nome'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'preco_custo' => $this->parseDecimal($_POST['preco_custo'] ?? '0'),
            'preco_venda' => $this->parseDecimal($_POST['preco_venda'] ?? '0'),
            'quantidade_estoque' => (int) ($_POST['quantidade_estoque'] ?? 0),
            'estoque_minimo' => (int) ($_POST['estoque_minimo'] ?? 5),
            'localizacao' => $_POST['localizacao'] ?? '',
            'status' => $_POST['status'] ?? 'ativo',
        ];
        
        $this->pecaModel->update($id, $data);
        
        $this->flash('success', 'Peça atualizada com sucesso!');
        $this->redirect(base_url('pecas'));
    }
    
    /**
     * Exclui peça
     */
    public function destroy(int $id): void
    {
        $peca = $this->pecaModel->find($id);
        
        if (!$peca) {
            $this->flash('error', 'Peça não encontrada.');
            $this->redirect(base_url('pecas'));
            return;
        }
        
        $this->pecaModel->delete($id);
        
        $this->flash('success', 'Peça excluída com sucesso!');
        $this->redirect(base_url('pecas'));
    }
    
    /**
     * Converte string de moeda para decimal
     */
    protected function parseDecimal(string $value): float
    {
        $value = str_replace(['.', ','], ['', '.'], $value);
        return (float) $value;
    }
}

