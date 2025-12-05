<?php

namespace App\Models;

use Core\Model;
use Core\Database;

/**
 * Model de Cliente
 */
class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf_cnpj',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'observacoes',
        'status',
    ];
    
    /**
     * Retorna os clientes mais recentes
     */
    public function getRecent(int $limit = 5): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit";
        return Database::getInstance()->query($sql, ['limit' => $limit])->fetchAll();
    }
    
    /**
     * Busca clientes por nome ou email
     */
    public function search(string $term): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE nome LIKE :term OR email LIKE :term OR telefone LIKE :term 
                ORDER BY nome ASC";
        return Database::getInstance()->query($sql, ['term' => "%{$term}%"])->fetchAll();
    }
    
    /**
     * Retorna os orçamentos de um cliente
     */
    public function getOrcamentos(int $clienteId): array
    {
        $sql = "SELECT * FROM orcamentos WHERE cliente_id = :cliente_id ORDER BY created_at DESC";
        return Database::getInstance()->query($sql, ['cliente_id' => $clienteId])->fetchAll();
    }
    
    /**
     * Conta clientes ativos
     */
    public function countAtivos(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'ativo'";
        $result = Database::getInstance()->query($sql)->fetch();
        return (int) $result->total;
    }
}

