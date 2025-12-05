<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\ContaReceber;
use App\Models\Cliente;

/**
 * Controller de Contas a Receber
 */
class ContaReceberController extends Controller
{
    protected $contaModel;
    protected $clienteModel;
    
    public function __construct()
    {
        $this->requireAuth();
        $this->contaModel = new ContaReceber();
        $this->clienteModel = new Cliente();
        
        // Atualiza contas vencidas
        $this->contaModel->atualizarVencidas();
    }
    
    /**
     * Lista todas as contas
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $status = $_GET['status'] ?? null;
        
        $contas = $this->contaModel->paginateWithCliente($page, 15, $status);
        $estatisticas = $this->contaModel->getEstatisticas();
        
        $this->view('contas/index', [
            'title' => 'Contas a Receber',
            'contas' => $contas,
            'estatisticas' => $estatisticas,
            'statusFilter' => $status,
        ]);
    }
    
    /**
     * Formulário de criação
     */
    public function create(): void
    {
        $clientes = $this->clienteModel->all();
        
        // Busca orçamentos aprovados/em execução que não tem conta vinculada
        $orcamentoModel = new \App\Models\Orcamento();
        $orcamentosDisponiveis = $orcamentoModel->getDisponiveisParaCobranca();
        
        $this->view('contas/create', [
            'title' => 'Nova Conta a Receber',
            'clientes' => $clientes,
            'orcamentos' => $orcamentosDisponiveis,
        ]);
    }
    
    /**
     * Salva nova conta
     */
    public function store(): void
    {
        $valorTotal = $this->parseDecimal($_POST['valor_total'] ?? '0');
        $valorPago = $this->parseDecimal($_POST['valor_pago'] ?? '0');
        $valorPendente = $valorTotal - $valorPago;
        
        $status = 'pendente';
        if ($valorPago > 0 && $valorPendente > 0) {
            $status = 'parcial';
        } elseif ($valorPendente <= 0) {
            $status = 'pago';
            $valorPendente = 0;
        }
        
        // Verifica se o orçamento já tem conta vinculada
        $orcamentoId = !empty($_POST['orcamento_id']) ? (int) $_POST['orcamento_id'] : null;
        if ($orcamentoId) {
            $orcamentoModel = new \App\Models\Orcamento();
            $contaExistente = $orcamentoModel->getContaReceber($orcamentoId);
            if ($contaExistente) {
                $this->flash('error', 'Este orçamento já possui uma cobrança vinculada.');
                $this->redirect(base_url('contas/novo'));
                return;
            }
        }
        
        $data = [
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'orcamento_id' => $orcamentoId,
            'descricao' => $_POST['descricao'] ?? '',
            'valor_total' => $valorTotal,
            'valor_pago' => $valorPago,
            'valor_pendente' => $valorPendente,
            'data_vencimento' => $_POST['data_vencimento'] ?? date('Y-m-d'),
            'forma_pagamento' => $_POST['forma_pagamento'] ?? null,
            'observacoes' => $_POST['observacoes'] ?? '',
            'status' => $status,
        ];
        
        $errors = $this->validate($data, [
            'cliente_id' => 'required',
            'descricao' => 'required|min:5',
            'valor_total' => 'required|numeric',
            'data_vencimento' => 'required',
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect(base_url('contas/novo'));
            return;
        }
        
        $contaId = $this->contaModel->create($data);
        
        // Se foi pago algo, registra o pagamento
        if ($valorPago > 0) {
            $this->contaModel->registrarPagamento(
                $contaId, 
                $valorPago, 
                date('Y-m-d'),
                $_POST['forma_pagamento'] ?? null,
                'Pagamento inicial'
            );
        }
        
        $this->flash('success', 'Conta cadastrada com sucesso!');
        $this->redirect(base_url('contas'));
    }
    
    /**
     * Exibe detalhes da conta
     */
    public function show(int $id): void
    {
        $conta = $this->contaModel->findWithRelations($id);
        
        if (!$conta) {
            $this->flash('error', 'Conta não encontrada.');
            $this->redirect(base_url('contas'));
            return;
        }
        
        $this->view('contas/show', [
            'title' => 'Detalhes da Conta #' . $id,
            'conta' => $conta,
        ]);
    }
    
    /**
     * Formulário de edição
     */
    public function edit(int $id): void
    {
        $conta = $this->contaModel->findWithRelations($id);
        
        if (!$conta) {
            $this->flash('error', 'Conta não encontrada.');
            $this->redirect(base_url('contas'));
            return;
        }
        
        $clientes = $this->clienteModel->all();
        
        $this->view('contas/edit', [
            'title' => 'Editar Conta',
            'conta' => $conta,
            'clientes' => $clientes,
        ]);
    }
    
    /**
     * Atualiza conta
     */
    public function update(int $id): void
    {
        $conta = $this->contaModel->find($id);
        
        if (!$conta) {
            $this->flash('error', 'Conta não encontrada.');
            $this->redirect(base_url('contas'));
            return;
        }
        
        $data = [
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'descricao' => $_POST['descricao'] ?? '',
            'data_vencimento' => $_POST['data_vencimento'] ?? $conta->data_vencimento,
            'forma_pagamento' => $_POST['forma_pagamento'] ?? null,
            'observacoes' => $_POST['observacoes'] ?? '',
        ];
        
        $this->contaModel->update($id, $data);
        
        $this->flash('success', 'Conta atualizada com sucesso!');
        $this->redirect(base_url('contas'));
    }
    
    /**
     * Registra pagamento
     */
    public function registrarPagamento(int $id): void
    {
        $conta = $this->contaModel->find($id);
        
        if (!$conta) {
            $this->flash('error', 'Conta não encontrada.');
            $this->redirect(base_url('contas'));
            return;
        }
        
        $valor = $this->parseDecimal($_POST['valor'] ?? '0');
        $dataPagamento = $_POST['data_pagamento'] ?? date('Y-m-d');
        $formaPagamento = $_POST['forma_pagamento'] ?? null;
        $observacoes = $_POST['observacoes'] ?? null;
        
        if ($valor <= 0) {
            $this->flash('error', 'Valor do pagamento deve ser maior que zero.');
            $this->redirect(base_url("contas/{$id}"));
            return;
        }
        
        $this->contaModel->registrarPagamento($id, $valor, $dataPagamento, $formaPagamento, $observacoes);
        
        $this->flash('success', 'Pagamento registrado com sucesso!');
        $this->redirect(base_url("contas/{$id}"));
    }
    
    /**
     * Exclui conta
     */
    public function destroy(int $id): void
    {
        $conta = $this->contaModel->find($id);
        
        if (!$conta) {
            $this->flash('error', 'Conta não encontrada.');
            $this->redirect(base_url('contas'));
            return;
        }
        
        $this->contaModel->delete($id);
        
        $this->flash('success', 'Conta excluída com sucesso!');
        $this->redirect(base_url('contas'));
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

