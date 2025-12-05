<?php

namespace App\Models;

use Core\Model;
use Core\Database;

class MovimentacaoEstoque extends Model
{
    protected $table = 'movimentacoes_estoque';
    protected $timestamps = false;
    
    protected $fillable = [
        'peca_id',
        'tipo',
        'quantidade',
        'motivo',
        'orcamento_id',
        'observacoes',
    ];
    
    /**
     * Tipos de movimentação
     */
    public static function getTipos(): array
    {
        return [
            'entrada' => 'Entrada',
            'saida' => 'Saída',
        ];
    }
    
    /**
     * Motivos de movimentação
     */
    public static function getMotivos(): array
    {
        return [
            'compra' => 'Compra/Reposição',
            'servico' => 'Serviço/Orçamento',
            'ajuste' => 'Ajuste de Estoque',
            'perda' => 'Perda/Avaria',
            'devolucao' => 'Devolução',
        ];
    }
    
    /**
     * Busca todas as movimentações com dados da peça
     */
    public function allWithPeca(): array
    {
        $sql = "SELECT m.*, p.nome as peca_nome, p.codigo as peca_codigo, 
                       ma.nome as marca_nome, o.id as orcamento_numero
                FROM {$this->table} m
                LEFT JOIN pecas p ON m.peca_id = p.id
                LEFT JOIN marcas ma ON p.marca_id = ma.id
                LEFT JOIN orcamentos o ON m.orcamento_id = o.id
                ORDER BY m.created_at DESC";
        return Database::getInstance()->query($sql)->fetchAll();
    }
    
    /**
     * Busca movimentações de uma peça específica
     */
    public function getByPeca(int $pecaId): array
    {
        $sql = "SELECT m.*, o.id as orcamento_numero
                FROM {$this->table} m
                LEFT JOIN orcamentos o ON m.orcamento_id = o.id
                WHERE m.peca_id = :peca_id
                ORDER BY m.created_at DESC";
        return Database::getInstance()->query($sql, ['peca_id' => $pecaId])->fetchAll();
    }
    
    /**
     * Registra uma saída de estoque
     */
    public function registrarSaida(int $pecaId, int $quantidade, string $motivo, ?int $orcamentoId = null, ?string $observacoes = null): int
    {
        $movId = $this->create([
            'peca_id' => $pecaId,
            'tipo' => 'saida',
            'quantidade' => $quantidade,
            'motivo' => $motivo,
            'orcamento_id' => $orcamentoId,
            'observacoes' => $observacoes,
        ]);
        
        // Atualiza estoque da peça
        $this->atualizarEstoquePeca($pecaId, -$quantidade);
        
        return $movId;
    }
    
    /**
     * Registra uma entrada de estoque
     */
    public function registrarEntrada(int $pecaId, int $quantidade, string $motivo, ?string $observacoes = null): int
    {
        $movId = $this->create([
            'peca_id' => $pecaId,
            'tipo' => 'entrada',
            'quantidade' => $quantidade,
            'motivo' => $motivo,
            'observacoes' => $observacoes,
        ]);
        
        // Atualiza estoque da peça
        $this->atualizarEstoquePeca($pecaId, $quantidade);
        
        return $movId;
    }
    
    /**
     * Atualiza o estoque de uma peça
     */
    private function atualizarEstoquePeca(int $pecaId, int $quantidade): void
    {
        $sql = "UPDATE pecas SET quantidade_estoque = quantidade_estoque + :quantidade WHERE id = :id";
        Database::getInstance()->query($sql, [
            'quantidade' => $quantidade,
            'id' => $pecaId,
        ]);
    }
    
    /**
     * Busca movimentações recentes (para dashboard)
     */
    public function getRecentes(int $limit = 10): array
    {
        $sql = "SELECT m.*, p.nome as peca_nome, p.codigo as peca_codigo
                FROM {$this->table} m
                LEFT JOIN pecas p ON m.peca_id = p.id
                ORDER BY m.created_at DESC
                LIMIT :limit";
        return Database::getInstance()->query($sql, ['limit' => $limit])->fetchAll();
    }
}

