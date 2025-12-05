<?php

namespace App\Models;

use Core\Model;
use Core\Database;

/**
 * Model de Orçamento
 */
class Orcamento extends Model
{
    protected $table = 'orcamentos';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'cliente_id',
        'descricao',
        'valor_total',
        'desconto',
        'valor_final',
        'data_validade',
        'forma_pagamento',
        'observacoes',
        'status',
    ];
    
    /**
     * Status disponíveis para orçamentos
     */
    public static function getStatuses(): array
    {
        return [
            'pendente' => 'Pendente',
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            'em_execucao' => 'Em Execução',
            'concluido' => 'Concluído',
        ];
    }
    
    /**
     * Formas de pagamento disponíveis
     */
    public static function getFormasPagamento(): array
    {
        return [
            'dinheiro' => 'Dinheiro',
            'pix' => 'PIX',
            'cartao_credito' => 'Cartão de Crédito',
            'cartao_debito' => 'Cartão de Débito',
            'boleto' => 'Boleto',
            'transferencia' => 'Transferência Bancária',
            'parcelado' => 'Parcelado',
        ];
    }
    
    /**
     * Conta orçamentos por status
     */
    public function countByStatus(string $status): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = :status";
        $result = Database::getInstance()->query($sql, ['status' => $status])->fetch();
        return (int) $result->total;
    }
    
    /**
     * Retorna os orçamentos mais recentes
     */
    public function getRecent(int $limit = 5): array
    {
        $sql = "SELECT o.*, c.nome as cliente_nome 
                FROM {$this->table} o 
                LEFT JOIN clientes c ON o.cliente_id = c.id 
                ORDER BY o.created_at DESC 
                LIMIT :limit";
        return Database::getInstance()->query($sql, ['limit' => $limit])->fetchAll();
    }
    
    /**
     * Encontra um orçamento com todas as relações
     */
    public function findWithRelations(int $id): ?object
    {
        $sql = "SELECT o.*, c.nome as cliente_nome, c.email as cliente_email, 
                       c.telefone as cliente_telefone, c.endereco as cliente_endereco,
                       c.cidade as cliente_cidade, c.estado as cliente_estado
                FROM {$this->table} o 
                LEFT JOIN clientes c ON o.cliente_id = c.id 
                WHERE o.id = :id 
                LIMIT 1";
        $orcamento = Database::getInstance()->query($sql, ['id' => $id])->fetch();
        
        if ($orcamento) {
            // Carrega os serviços do orçamento
            $orcamento->servicos = $this->getServicos($id);
        }
        
        return $orcamento ?: null;
    }
    
    /**
     * Retorna orçamentos paginados com relações
     */
    public function paginateWithRelations(int $page = 1, int $perPage = 15, ?string $status = null): array
    {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = '';
        $params = ['limit' => $perPage, 'offset' => $offset];
        
        if ($status) {
            $whereClause = 'WHERE o.status = :status';
            $params['status'] = $status;
        }
        
        // Total de registros
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} o {$whereClause}";
        $countParams = $status ? ['status' => $status] : [];
        $total = (int) Database::getInstance()->query($countSql, $countParams)->fetch()->total;
        $totalPages = ceil($total / $perPage);
        
        // Dados paginados
        $sql = "SELECT o.*, c.nome as cliente_nome 
                FROM {$this->table} o 
                LEFT JOIN clientes c ON o.cliente_id = c.id 
                {$whereClause}
                ORDER BY o.created_at DESC 
                LIMIT :limit OFFSET :offset";
        $data = Database::getInstance()->query($sql, $params)->fetchAll();
        
        return [
            'data' => $data,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'has_prev' => $page > 1,
            'has_next' => $page < $totalPages,
        ];
    }
    
    /**
     * Retorna os serviços de um orçamento
     */
    public function getServicos(int $orcamentoId): array
    {
        $sql = "SELECT os.*, s.nome as servico_nome, s.preco as servico_preco 
                FROM orcamento_servicos os 
                LEFT JOIN servicos s ON os.servico_id = s.id 
                WHERE os.orcamento_id = :orcamento_id";
        return Database::getInstance()->query($sql, ['orcamento_id' => $orcamentoId])->fetchAll();
    }
    
    /**
     * Salva os serviços de um orçamento
     */
    public function saveServicos(int $orcamentoId, array $servicos): void
    {
        // Remove os serviços existentes
        $this->deleteServicos($orcamentoId);
        
        // Insere os novos serviços
        foreach ($servicos as $servicoId => $dados) {
            if (!empty($dados['selecionado'])) {
                $sql = "INSERT INTO orcamento_servicos (orcamento_id, servico_id, quantidade, valor_unitario, valor_total) 
                        VALUES (:orcamento_id, :servico_id, :quantidade, :valor_unitario, :valor_total)";
                
                $quantidade = (int) ($dados['quantidade'] ?? 1);
                $valorUnitario = (float) str_replace(['.', ','], ['', '.'], $dados['valor_unitario'] ?? '0');
                $valorTotal = $quantidade * $valorUnitario;
                
                Database::getInstance()->query($sql, [
                    'orcamento_id' => $orcamentoId,
                    'servico_id' => $servicoId,
                    'quantidade' => $quantidade,
                    'valor_unitario' => $valorUnitario,
                    'valor_total' => $valorTotal,
                ]);
            }
        }
    }
    
    /**
     * Remove os serviços de um orçamento
     */
    public function deleteServicos(int $orcamentoId): void
    {
        $sql = "DELETE FROM orcamento_servicos WHERE orcamento_id = :orcamento_id";
        Database::getInstance()->query($sql, ['orcamento_id' => $orcamentoId]);
    }
    
    /**
     * Calcula o total de vendas por período
     */
    public function getTotalByPeriodo(string $dataInicio, string $dataFim): float
    {
        $sql = "SELECT SUM(valor_final) as total FROM {$this->table} 
                WHERE status IN ('aprovado', 'em_execucao', 'concluido') 
                AND created_at BETWEEN :data_inicio AND :data_fim";
        $result = Database::getInstance()->query($sql, [
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
        ])->fetch();
        return (float) ($result->total ?? 0);
    }
    
    /**
     * Retorna estatísticas dos orçamentos
     */
    public function getEstatisticas(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pendente' THEN 1 ELSE 0 END) as pendentes,
                    SUM(CASE WHEN status = 'aprovado' THEN 1 ELSE 0 END) as aprovados,
                    SUM(CASE WHEN status = 'rejeitado' THEN 1 ELSE 0 END) as rejeitados,
                    SUM(CASE WHEN status = 'em_execucao' THEN 1 ELSE 0 END) as em_execucao,
                    SUM(CASE WHEN status = 'concluido' THEN 1 ELSE 0 END) as concluidos,
                    SUM(CASE WHEN status IN ('aprovado', 'em_execucao', 'concluido') THEN valor_final ELSE 0 END) as valor_total
                FROM {$this->table}";
        return (array) Database::getInstance()->query($sql)->fetch();
    }
    
    /**
     * Verifica se orçamento tem conta a receber vinculada
     */
    public function getContaReceber(int $orcamentoId): ?object
    {
        $sql = "SELECT * FROM contas_receber WHERE orcamento_id = :orcamento_id LIMIT 1";
        $result = Database::getInstance()->query($sql, ['orcamento_id' => $orcamentoId])->fetch();
        return $result ?: null;
    }
    
    /**
     * Retorna orçamentos disponíveis para criar cobrança
     * (aprovados/em_execucao/concluido que não tem conta vinculada)
     */
    public function getDisponiveisParaCobranca(): array
    {
        $sql = "SELECT o.*, c.nome as cliente_nome, c.telefone as cliente_telefone
                FROM {$this->table} o
                LEFT JOIN clientes c ON o.cliente_id = c.id
                LEFT JOIN contas_receber cr ON o.id = cr.orcamento_id
                WHERE o.status IN ('aprovado', 'em_execucao', 'concluido')
                AND cr.id IS NULL
                ORDER BY o.created_at DESC";
        return Database::getInstance()->query($sql)->fetchAll();
    }
    
    /**
     * Retorna as peças de um orçamento
     */
    public function getPecas(int $orcamentoId): array
    {
        $sql = "SELECT op.*, p.codigo as peca_codigo, p.nome as peca_nome, 
                       m.nome as marca_nome, p.quantidade_estoque
                FROM orcamento_pecas op 
                LEFT JOIN pecas p ON op.peca_id = p.id 
                LEFT JOIN marcas m ON p.marca_id = m.id
                WHERE op.orcamento_id = :orcamento_id";
        return Database::getInstance()->query($sql, ['orcamento_id' => $orcamentoId])->fetchAll();
    }
    
    /**
     * Salva as peças de um orçamento
     */
    public function savePecas(int $orcamentoId, array $pecas): void
    {
        // Remove as peças existentes (sem baixa de estoque)
        $this->deletePecas($orcamentoId);
        
        // Insere as novas peças
        foreach ($pecas as $pecaId => $dados) {
            if (!empty($dados['selecionado']) && $pecaId > 0) {
                $quantidade = (int) ($dados['quantidade'] ?? 1);
                $precoUnitario = (float) str_replace(['.', ','], ['', '.'], $dados['preco_unitario'] ?? '0');
                $precoTotal = $quantidade * $precoUnitario;
                
                $sql = "INSERT INTO orcamento_pecas (orcamento_id, peca_id, quantidade, preco_unitario, preco_total) 
                        VALUES (:orcamento_id, :peca_id, :quantidade, :preco_unitario, :preco_total)";
                
                Database::getInstance()->query($sql, [
                    'orcamento_id' => $orcamentoId,
                    'peca_id' => $pecaId,
                    'quantidade' => $quantidade,
                    'preco_unitario' => $precoUnitario,
                    'preco_total' => $precoTotal,
                ]);
            }
        }
    }
    
    /**
     * Remove as peças de um orçamento
     */
    public function deletePecas(int $orcamentoId): void
    {
        $sql = "DELETE FROM orcamento_pecas WHERE orcamento_id = :orcamento_id";
        Database::getInstance()->query($sql, ['orcamento_id' => $orcamentoId]);
    }
    
    /**
     * Dá baixa no estoque das peças quando orçamento é concluído
     */
    public function baixarEstoquePecas(int $orcamentoId): void
    {
        $movimentacaoModel = new MovimentacaoEstoque();
        
        // Busca peças do orçamento que ainda não tiveram baixa
        $sql = "SELECT * FROM orcamento_pecas WHERE orcamento_id = :orcamento_id AND baixa_estoque = 0";
        $pecas = Database::getInstance()->query($sql, ['orcamento_id' => $orcamentoId])->fetchAll();
        
        foreach ($pecas as $peca) {
            // Registra saída no estoque
            $movimentacaoModel->registrarSaida(
                $peca->peca_id,
                $peca->quantidade,
                'servico',
                $orcamentoId,
                "Baixa automática - Orçamento #{$orcamentoId} concluído"
            );
            
            // Marca como baixado
            $sqlUpdate = "UPDATE orcamento_pecas SET baixa_estoque = 1 WHERE id = :id";
            Database::getInstance()->query($sqlUpdate, ['id' => $peca->id]);
        }
    }
}

