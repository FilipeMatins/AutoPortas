<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Peca;
use App\Models\MovimentacaoEstoque;

class EstoqueController extends Controller
{
    private Peca $pecaModel;
    private MovimentacaoEstoque $movimentacaoModel;
    
    public function __construct()
    {
        $this->pecaModel = new Peca();
        $this->movimentacaoModel = new MovimentacaoEstoque();
    }
    
    /**
     * Lista movimentações de estoque
     */
    public function index(): void
    {
        $movimentacoes = $this->movimentacaoModel->allWithPeca();
        
        $this->view('estoque/index', [
            'title' => 'Movimentações de Estoque',
            'movimentacoes' => $movimentacoes,
        ]);
    }
    
    /**
     * Formulário de saída manual
     */
    public function saida(): void
    {
        $pecas = $this->pecaModel->allWithMarca();
        
        $this->view('estoque/saida', [
            'title' => 'Saída de Peças',
            'pecas' => $pecas,
            'motivos' => MovimentacaoEstoque::getMotivos(),
        ]);
    }
    
    /**
     * Processa saída manual
     */
    public function registrarSaida(): void
    {
        $pecaId = (int) ($_POST['peca_id'] ?? 0);
        $quantidade = (int) ($_POST['quantidade'] ?? 0);
        $motivo = $_POST['motivo'] ?? '';
        $observacoes = $_POST['observacoes'] ?? '';
        
        // Validações
        $errors = [];
        
        if (!$pecaId) {
            $errors['peca_id'] = 'Selecione uma peça';
        }
        
        if ($quantidade <= 0) {
            $errors['quantidade'] = 'Quantidade deve ser maior que zero';
        }
        
        if (!$motivo) {
            $errors['motivo'] = 'Selecione um motivo';
        }
        
        // Verifica estoque disponível
        if ($pecaId && $quantidade > 0) {
            $peca = $this->pecaModel->find($pecaId);
            if ($peca && $quantidade > $peca->quantidade_estoque) {
                $errors['quantidade'] = "Estoque insuficiente. Disponível: {$peca->quantidade_estoque}";
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect(base_url('estoque/saida'));
            return;
        }
        
        // Registra a saída
        $this->movimentacaoModel->registrarSaida($pecaId, $quantidade, $motivo, null, $observacoes);
        
        $this->flash('success', 'Saída registrada com sucesso!');
        $this->redirect(base_url('estoque'));
    }
    
    /**
     * Formulário de entrada manual
     */
    public function entrada(): void
    {
        $pecas = $this->pecaModel->allWithMarca();
        
        $this->view('estoque/entrada', [
            'title' => 'Entrada de Peças',
            'pecas' => $pecas,
            'motivos' => [
                'compra' => 'Compra/Reposição',
                'devolucao' => 'Devolução',
                'ajuste' => 'Ajuste de Estoque',
            ],
        ]);
    }
    
    /**
     * Processa entrada manual
     */
    public function registrarEntrada(): void
    {
        $pecaId = (int) ($_POST['peca_id'] ?? 0);
        $quantidade = (int) ($_POST['quantidade'] ?? 0);
        $motivo = $_POST['motivo'] ?? '';
        $observacoes = $_POST['observacoes'] ?? '';
        
        // Validações
        $errors = [];
        
        if (!$pecaId) {
            $errors['peca_id'] = 'Selecione uma peça';
        }
        
        if ($quantidade <= 0) {
            $errors['quantidade'] = 'Quantidade deve ser maior que zero';
        }
        
        if (!$motivo) {
            $errors['motivo'] = 'Selecione um motivo';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect(base_url('estoque/entrada'));
            return;
        }
        
        // Registra a entrada
        $this->movimentacaoModel->registrarEntrada($pecaId, $quantidade, $motivo, $observacoes);
        
        $this->flash('success', 'Entrada registrada com sucesso!');
        $this->redirect(base_url('estoque'));
    }
    
    /**
     * API: Busca peças para select (com estoque)
     */
    public function buscarPecas(): void
    {
        header('Content-Type: application/json');
        
        $termo = $_GET['q'] ?? '';
        $pecas = $this->pecaModel->search($termo);
        
        $result = array_map(function($p) {
            return [
                'id' => $p->id,
                'codigo' => $p->codigo,
                'nome' => $p->nome,
                'marca' => $p->marca_nome ?? '',
                'estoque' => $p->quantidade_estoque,
                'preco' => $p->preco_venda,
            ];
        }, $pecas);
        
        echo json_encode($result);
        exit;
    }
}

