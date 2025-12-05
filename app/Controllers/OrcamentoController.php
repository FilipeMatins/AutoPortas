<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Orcamento;
use App\Models\Cliente;
use App\Models\Servico;
use App\Models\Peca;

/**
 * Controller de Orçamentos
 */
class OrcamentoController extends Controller
{
    protected $orcamentoModel;
    protected $clienteModel;
    protected $servicoModel;
    
    public function __construct()
    {
        // Requer autenticação em todas as ações
        $this->requireAuth();
        
        $this->orcamentoModel = new Orcamento();
        $this->clienteModel = new Cliente();
        $this->servicoModel = new Servico();
    }
    
    /**
     * Lista todos os orçamentos
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $status = $_GET['status'] ?? null;
        
        $orcamentos = $this->orcamentoModel->paginateWithRelations($page, 15, $status);
        
        $this->view('orcamentos/index', [
            'title' => 'Orçamentos',
            'orcamentos' => $orcamentos,
            'statusFilter' => $status,
        ]);
    }
    
    /**
     * Exibe o formulário de criação
     */
    public function create(): void
    {
        $clientes = $this->clienteModel->all();
        $servicos = $this->servicoModel->where('status', 'ativo');
        $pecaModel = new Peca();
        $pecas = $pecaModel->allWithMarca();
        
        $this->view('orcamentos/create', [
            'title' => 'Novo Orçamento',
            'clientes' => $clientes,
            'servicos' => $servicos,
            'pecas' => $pecas,
        ]);
    }
    
    /**
     * Salva um novo orçamento
     */
    public function store(): void
    {
        $data = [
            'cliente_id' => $_POST['cliente_id'] ?? null,
            'descricao' => $_POST['descricao'] ?? '',
            'valor_total' => str_replace(['.', ','], ['', '.'], $_POST['valor_total'] ?? '0'),
            'desconto' => str_replace(['.', ','], ['', '.'], $_POST['desconto'] ?? '0'),
            'data_validade' => $_POST['data_validade'] ?? null,
            'forma_pagamento' => $_POST['forma_pagamento'] ?? '',
            'observacoes' => $_POST['observacoes'] ?? '',
            'status' => 'pendente',
        ];
        
        $errors = $this->validate($data, [
            'cliente_id' => 'required',
            'descricao' => 'required|min:10',
            'valor_total' => 'required|numeric',
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->redirect(base_url('orcamentos/novo'));
            return;
        }
        
        // Calcula o valor final
        $data['valor_final'] = $data['valor_total'] - $data['desconto'];
        
        $orcamentoId = $this->orcamentoModel->create($data);
        
        // Salva os serviços do orçamento
        if (!empty($_POST['servicos'])) {
            $this->orcamentoModel->saveServicos($orcamentoId, $_POST['servicos']);
        }
        
        // Salva as peças do orçamento
        if (!empty($_POST['pecas'])) {
            $this->orcamentoModel->savePecas($orcamentoId, $_POST['pecas']);
        }
        
        $this->flash('success', 'Orçamento cadastrado com sucesso!');
        $this->redirect(base_url('orcamentos'));
    }
    
    /**
     * Exibe os detalhes de um orçamento
     */
    public function show(int $id): void
    {
        $orcamento = $this->orcamentoModel->findWithRelations($id);
        
        if (!$orcamento) {
            $this->flash('error', 'Orçamento não encontrado.');
            $this->redirect(base_url('orcamentos'));
            return;
        }
        
        // Carrega as peças do orçamento
        $orcamento->pecas = $this->orcamentoModel->getPecas($id);
        
        $this->view('orcamentos/show', [
            'title' => 'Detalhes do Orçamento #' . $id,
            'orcamento' => $orcamento,
        ]);
    }
    
    /**
     * Exibe o formulário de edição
     */
    public function edit(int $id): void
    {
        $orcamento = $this->orcamentoModel->findWithRelations($id);
        
        if (!$orcamento) {
            $this->flash('error', 'Orçamento não encontrado.');
            $this->redirect(base_url('orcamentos'));
            return;
        }
        
        $clientes = $this->clienteModel->all();
        $servicos = $this->servicoModel->where('status', 'ativo');
        $pecaModel = new Peca();
        $pecas = $pecaModel->allWithMarca();
        
        // Carrega as peças do orçamento
        $orcamento->pecas = $this->orcamentoModel->getPecas($id);
        
        $this->view('orcamentos/edit', [
            'title' => 'Editar Orçamento',
            'orcamento' => $orcamento,
            'clientes' => $clientes,
            'servicos' => $servicos,
            'pecas' => $pecas,
        ]);
    }
    
    /**
     * Atualiza um orçamento
     */
    public function update(int $id): void
    {
        $orcamento = $this->orcamentoModel->find($id);
        
        if (!$orcamento) {
            $this->flash('error', 'Orçamento não encontrado.');
            $this->redirect(base_url('orcamentos'));
            return;
        }
        
        $data = [
            'cliente_id' => $_POST['cliente_id'] ?? null,
            'descricao' => $_POST['descricao'] ?? '',
            'valor_total' => str_replace(['.', ','], ['', '.'], $_POST['valor_total'] ?? '0'),
            'desconto' => str_replace(['.', ','], ['', '.'], $_POST['desconto'] ?? '0'),
            'data_validade' => $_POST['data_validade'] ?? null,
            'forma_pagamento' => $_POST['forma_pagamento'] ?? '',
            'observacoes' => $_POST['observacoes'] ?? '',
            'status' => $_POST['status'] ?? $orcamento->status,
        ];
        
        $errors = $this->validate($data, [
            'cliente_id' => 'required',
            'descricao' => 'required|min:10',
            'valor_total' => 'required|numeric',
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->redirect(base_url("orcamentos/{$id}/editar"));
            return;
        }
        
        // Calcula o valor final
        $data['valor_final'] = $data['valor_total'] - $data['desconto'];
        
        $this->orcamentoModel->update($id, $data);
        
        // Atualiza os serviços do orçamento
        if (isset($_POST['servicos'])) {
            $this->orcamentoModel->saveServicos($id, $_POST['servicos']);
        }
        
        // Atualiza as peças do orçamento
        if (isset($_POST['pecas'])) {
            $this->orcamentoModel->savePecas($id, $_POST['pecas']);
        }
        
        $this->flash('success', 'Orçamento atualizado com sucesso!');
        $this->redirect(base_url('orcamentos'));
    }
    
    /**
     * Atualiza o status de um orçamento
     */
    public function updateStatus(int $id): void
    {
        $orcamento = $this->orcamentoModel->find($id);
        
        if (!$orcamento) {
            if (is_ajax()) {
                $this->json(['error' => 'Orçamento não encontrado.'], 404);
            }
            $this->flash('error', 'Orçamento não encontrado.');
            $this->redirect(base_url('orcamentos'));
            return;
        }
        
        $status = $_POST['status'] ?? '';
        
        if (!in_array($status, ['pendente', 'aprovado', 'rejeitado', 'em_execucao', 'concluido'])) {
            if (is_ajax()) {
                $this->json(['error' => 'Status inválido.'], 400);
            }
            $this->flash('error', 'Status inválido.');
            $this->redirect(base_url('orcamentos'));
            return;
        }
        
        $this->orcamentoModel->update($id, ['status' => $status]);
        
        // Se status for "concluído", dá baixa no estoque das peças
        if ($status === 'concluido') {
            $this->orcamentoModel->baixarEstoquePecas($id);
        }
        
        if (is_ajax()) {
            $this->json(['success' => true, 'message' => 'Status atualizado com sucesso!']);
        }
        
        $this->flash('success', 'Status do orçamento atualizado com sucesso!');
        $this->redirect(base_url('orcamentos'));
    }
    
    /**
     * Deleta um orçamento
     */
    public function destroy(int $id): void
    {
        $orcamento = $this->orcamentoModel->find($id);
        
        if (!$orcamento) {
            $this->flash('error', 'Orçamento não encontrado.');
            $this->redirect(base_url('orcamentos'));
            return;
        }
        
        // Remove os serviços e peças relacionados primeiro
        $this->orcamentoModel->deleteServicos($id);
        $this->orcamentoModel->deletePecas($id);
        $this->orcamentoModel->delete($id);
        
        $this->flash('success', 'Orçamento excluído com sucesso!');
        $this->redirect(base_url('orcamentos'));
    }
    
    /**
     * Gera PDF do orçamento
     */
    public function pdf(int $id): void
    {
        $orcamento = $this->orcamentoModel->findWithRelations($id);
        
        if (!$orcamento) {
            $this->flash('error', 'Orçamento não encontrado.');
            $this->redirect(base_url('orcamentos'));
            return;
        }
        
        $this->view('orcamentos/pdf', [
            'title' => 'Orçamento #' . $id,
            'orcamento' => $orcamento,
        ], null); // Sem layout
    }
    
    /**
     * Gera conta a receber a partir do orçamento
     */
    public function gerarCobranca(int $id): void
    {
        $orcamento = $this->orcamentoModel->findWithRelations($id);
        
        if (!$orcamento) {
            $this->flash('error', 'Orçamento não encontrado.');
            $this->redirect(base_url('orcamentos'));
            return;
        }
        
        // Verifica se já existe conta vinculada
        $contaExistente = $this->orcamentoModel->getContaReceber($id);
        if ($contaExistente) {
            $this->flash('warning', 'Este orçamento já possui uma cobrança vinculada.');
            $this->redirect(base_url("contas/{$contaExistente->id}"));
            return;
        }
        
        // Pega os valores do formulário
        $valorPago = $this->parseDecimal($_POST['valor_pago'] ?? '0');
        $dataVencimento = $_POST['data_vencimento'] ?? date('Y-m-d', strtotime('+7 days'));
        $formaPagamento = $_POST['forma_pagamento'] ?? null;
        
        $valorTotal = $orcamento->valor_final;
        $valorPendente = $valorTotal - $valorPago;
        
        // Define o status
        $status = 'pendente';
        if ($valorPago > 0 && $valorPendente > 0) {
            $status = 'parcial';
        } elseif ($valorPendente <= 0) {
            $status = 'pago';
            $valorPendente = 0;
        }
        
        // Cria a conta a receber
        $contaModel = new \App\Models\ContaReceber();
        $contaId = $contaModel->create([
            'orcamento_id' => $id,
            'cliente_id' => $orcamento->cliente_id,
            'descricao' => "Orçamento #{$id} - " . substr($orcamento->descricao, 0, 100),
            'valor_total' => $valorTotal,
            'valor_pago' => $valorPago,
            'valor_pendente' => $valorPendente,
            'data_vencimento' => $dataVencimento,
            'forma_pagamento' => $formaPagamento,
            'status' => $status,
        ]);
        
        // Se já pagou algo, registra o pagamento
        if ($valorPago > 0) {
            $contaModel->registrarPagamento(
                $contaId,
                $valorPago,
                date('Y-m-d'),
                $formaPagamento,
                'Pagamento inicial - Orçamento #' . $id
            );
        }
        
        $this->flash('success', 'Cobrança gerada com sucesso!');
        $this->redirect(base_url("contas/{$contaId}"));
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

