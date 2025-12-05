<?php

namespace App\Models;

use Core\Model;
use Core\Database;

/**
 * Model de Contas a Receber
 */
class ContaReceber extends Model
{
    protected $table = 'contas_receber';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'orcamento_id',
        'cliente_id',
        'descricao',
        'valor_total',
        'valor_pago',
        'valor_pendente',
        'data_vencimento',
        'data_pagamento',
        'forma_pagamento',
        'observacoes',
        'status',
    ];
    
    /**
     * Status disponíveis
     */
    public static function getStatuses(): array
    {
        return [
            'pendente' => 'Pendente',
            'parcial' => 'Pago Parcial',
            'pago' => 'Pago',
            'vencido' => 'Vencido',
            'cancelado' => 'Cancelado',
        ];
    }
    
    /**
     * Retorna contas com dados do cliente
     */
    public function getAllWithCliente(): array
    {
        $sql = "SELECT c.*, cl.nome as cliente_nome, cl.telefone as cliente_telefone 
                FROM {$this->table} c 
                LEFT JOIN clientes cl ON c.cliente_id = cl.id 
                ORDER BY c.data_vencimento ASC";
        return Database::getInstance()->query($sql)->fetchAll();
    }
    
    /**
     * Retorna contas paginadas
     */
    public function paginateWithCliente(int $page = 1, int $perPage = 15, ?string $status = null): array
    {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = '';
        $params = ['limit' => $perPage, 'offset' => $offset];
        
        if ($status) {
            $whereClause = 'WHERE c.status = :status';
            $params['status'] = $status;
        }
        
        // Total
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} c {$whereClause}";
        $countParams = $status ? ['status' => $status] : [];
        $total = (int) Database::getInstance()->query($countSql, $countParams)->fetch()->total;
        $totalPages = ceil($total / $perPage);
        
        // Dados
        $sql = "SELECT c.*, cl.nome as cliente_nome, cl.telefone as cliente_telefone 
                FROM {$this->table} c 
                LEFT JOIN clientes cl ON c.cliente_id = cl.id 
                {$whereClause}
                ORDER BY 
                    CASE WHEN c.status = 'vencido' THEN 1 
                         WHEN c.status = 'pendente' THEN 2 
                         WHEN c.status = 'parcial' THEN 3 
                         ELSE 4 END,
                    c.data_vencimento ASC 
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
     * Encontra conta com cliente e pagamentos
     */
    public function findWithRelations(int $id): ?object
    {
        $sql = "SELECT c.*, cl.nome as cliente_nome, cl.telefone as cliente_telefone, cl.email as cliente_email 
                FROM {$this->table} c 
                LEFT JOIN clientes cl ON c.cliente_id = cl.id 
                WHERE c.id = :id LIMIT 1";
        $conta = Database::getInstance()->query($sql, ['id' => $id])->fetch();
        
        if ($conta) {
            $conta->pagamentos = $this->getPagamentos($id);
        }
        
        return $conta ?: null;
    }
    
    /**
     * Retorna pagamentos de uma conta
     */
    public function getPagamentos(int $contaId): array
    {
        $sql = "SELECT * FROM pagamentos WHERE conta_id = :conta_id ORDER BY data_pagamento DESC";
        return Database::getInstance()->query($sql, ['conta_id' => $contaId])->fetchAll();
    }
    
    /**
     * Registra um pagamento
     */
    public function registrarPagamento(int $contaId, float $valor, string $dataPagamento, ?string $formaPagamento = null, ?string $observacoes = null): bool
    {
        $conta = $this->find($contaId);
        if (!$conta) return false;
        
        // Insere o pagamento
        $sql = "INSERT INTO pagamentos (conta_id, valor, data_pagamento, forma_pagamento, observacoes) 
                VALUES (:conta_id, :valor, :data_pagamento, :forma_pagamento, :observacoes)";
        Database::getInstance()->query($sql, [
            'conta_id' => $contaId,
            'valor' => $valor,
            'data_pagamento' => $dataPagamento,
            'forma_pagamento' => $formaPagamento,
            'observacoes' => $observacoes,
        ]);
        
        // Atualiza a conta
        $novoValorPago = $conta->valor_pago + $valor;
        $novoValorPendente = $conta->valor_total - $novoValorPago;
        
        $novoStatus = 'parcial';
        if ($novoValorPendente <= 0) {
            $novoStatus = 'pago';
            $novoValorPendente = 0;
        }
        
        $this->update($contaId, [
            'valor_pago' => $novoValorPago,
            'valor_pendente' => $novoValorPendente,
            'status' => $novoStatus,
            'data_pagamento' => $novoStatus === 'pago' ? $dataPagamento : null,
        ]);
        
        return true;
    }
    
    /**
     * Retorna contas vencidas
     */
    public function getVencidas(): array
    {
        $sql = "SELECT c.*, cl.nome as cliente_nome, cl.telefone as cliente_telefone 
                FROM {$this->table} c 
                LEFT JOIN clientes cl ON c.cliente_id = cl.id 
                WHERE c.data_vencimento < CURDATE() 
                AND c.status IN ('pendente', 'parcial')
                ORDER BY c.data_vencimento ASC";
        return Database::getInstance()->query($sql)->fetchAll();
    }
    
    /**
     * Retorna contas próximas do vencimento (7 dias)
     */
    public function getProximasVencer(int $dias = 7): array
    {
        $sql = "SELECT c.*, cl.nome as cliente_nome, cl.telefone as cliente_telefone 
                FROM {$this->table} c 
                LEFT JOIN clientes cl ON c.cliente_id = cl.id 
                WHERE c.data_vencimento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :dias DAY)
                AND c.status IN ('pendente', 'parcial')
                ORDER BY c.data_vencimento ASC";
        return Database::getInstance()->query($sql, ['dias' => $dias])->fetchAll();
    }
    
    /**
     * Retorna contas de um cliente
     */
    public function getByCliente(int $clienteId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE cliente_id = :cliente_id ORDER BY data_vencimento DESC";
        return Database::getInstance()->query($sql, ['cliente_id' => $clienteId])->fetchAll();
    }
    
    /**
     * Atualiza status de contas vencidas
     */
    public function atualizarVencidas(): int
    {
        $sql = "UPDATE {$this->table} 
                SET status = 'vencido', updated_at = NOW() 
                WHERE data_vencimento < CURDATE() 
                AND status IN ('pendente', 'parcial')";
        Database::getInstance()->query($sql);
        return Database::getInstance()->rowCount();
    }
    
    /**
     * Conta por status
     */
    public function countByStatus(string $status): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = :status";
        $result = Database::getInstance()->query($sql, ['status' => $status])->fetch();
        return (int) $result->total;
    }
    
    /**
     * Total a receber
     */
    public function getTotalPendente(): float
    {
        $sql = "SELECT SUM(valor_pendente) as total FROM {$this->table} WHERE status IN ('pendente', 'parcial', 'vencido')";
        $result = Database::getInstance()->query($sql)->fetch();
        return (float) ($result->total ?? 0);
    }
    
    /**
     * Estatísticas para o dashboard
     */
    public function getEstatisticas(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'vencido' THEN 1 ELSE 0 END) as vencidas,
                    SUM(CASE WHEN status = 'pendente' THEN 1 ELSE 0 END) as pendentes,
                    SUM(CASE WHEN status = 'parcial' THEN 1 ELSE 0 END) as parciais,
                    SUM(CASE WHEN status IN ('pendente', 'parcial', 'vencido') THEN valor_pendente ELSE 0 END) as total_pendente,
                    SUM(CASE WHEN status = 'vencido' THEN valor_pendente ELSE 0 END) as total_vencido
                FROM {$this->table}";
        return (array) Database::getInstance()->query($sql)->fetch();
    }
}

