<?php

namespace App\Models;

use Core\Model;
use Core\Database;

/**
 * Model de Peça
 */
class Peca extends Model
{
    protected $table = 'pecas';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'marca_id',
        'codigo',
        'nome',
        'descricao',
        'preco_custo',
        'preco_venda',
        'quantidade_estoque',
        'estoque_minimo',
        'localizacao',
        'status',
    ];
    
    /**
     * Retorna peças com dados da marca
     */
    public function getAllWithMarca(): array
    {
        $sql = "SELECT p.*, m.nome as marca_nome 
                FROM {$this->table} p 
                LEFT JOIN marcas m ON p.marca_id = m.id 
                ORDER BY p.nome ASC";
        return Database::getInstance()->query($sql)->fetchAll();
    }
    
    /**
     * Alias para getAllWithMarca
     */
    public function allWithMarca(): array
    {
        return $this->getAllWithMarca();
    }
    
    /**
     * Retorna peças paginadas com marca
     */
    public function paginateWithMarca(int $page = 1, int $perPage = 15, ?int $marcaId = null): array
    {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = '';
        $params = ['limit' => $perPage, 'offset' => $offset];
        
        if ($marcaId) {
            $whereClause = 'WHERE p.marca_id = :marca_id';
            $params['marca_id'] = $marcaId;
        }
        
        // Total
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} p {$whereClause}";
        $countParams = $marcaId ? ['marca_id' => $marcaId] : [];
        $total = (int) Database::getInstance()->query($countSql, $countParams)->fetch()->total;
        $totalPages = ceil($total / $perPage);
        
        // Dados
        $sql = "SELECT p.*, m.nome as marca_nome 
                FROM {$this->table} p 
                LEFT JOIN marcas m ON p.marca_id = m.id 
                {$whereClause}
                ORDER BY p.nome ASC 
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
     * Encontra peça com dados da marca
     */
    public function findWithMarca(int $id): ?object
    {
        $sql = "SELECT p.*, m.nome as marca_nome 
                FROM {$this->table} p 
                LEFT JOIN marcas m ON p.marca_id = m.id 
                WHERE p.id = :id LIMIT 1";
        $result = Database::getInstance()->query($sql, ['id' => $id])->fetch();
        return $result ?: null;
    }
    
    /**
     * Retorna peças por marca
     */
    public function getByMarca(int $marcaId): array
    {
        return $this->where('marca_id', $marcaId);
    }
    
    /**
     * Retorna peças com estoque baixo
     */
    public function getEstoqueBaixo(): array
    {
        $sql = "SELECT p.*, m.nome as marca_nome 
                FROM {$this->table} p 
                LEFT JOIN marcas m ON p.marca_id = m.id 
                WHERE p.quantidade_estoque <= p.estoque_minimo AND p.status = 'ativo'
                ORDER BY p.quantidade_estoque ASC";
        return Database::getInstance()->query($sql)->fetchAll();
    }
    
    /**
     * Busca peças
     */
    public function search(string $term): array
    {
        $sql = "SELECT p.*, m.nome as marca_nome 
                FROM {$this->table} p 
                LEFT JOIN marcas m ON p.marca_id = m.id 
                WHERE p.nome LIKE :term OR p.codigo LIKE :term OR m.nome LIKE :term 
                ORDER BY p.nome ASC";
        return Database::getInstance()->query($sql, ['term' => "%{$term}%"])->fetchAll();
    }
    
    /**
     * Atualiza estoque
     */
    public function updateEstoque(int $id, int $quantidade, string $operacao = 'add'): bool
    {
        $peca = $this->find($id);
        if (!$peca) return false;
        
        $novoEstoque = $operacao === 'add' 
            ? $peca->quantidade_estoque + $quantidade 
            : $peca->quantidade_estoque - $quantidade;
        
        if ($novoEstoque < 0) $novoEstoque = 0;
        
        $sql = "UPDATE {$this->table} SET quantidade_estoque = :estoque, updated_at = NOW() WHERE id = :id";
        Database::getInstance()->query($sql, ['estoque' => $novoEstoque, 'id' => $id]);
        
        return true;
    }
    
    /**
     * Conta peças por status
     */
    public function countByStatus(): array
    {
        $sql = "SELECT status, COUNT(*) as total FROM {$this->table} GROUP BY status";
        return Database::getInstance()->query($sql)->fetchAll();
    }
}

