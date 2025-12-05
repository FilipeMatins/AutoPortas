<?php

namespace App\Models;

use Core\Model;
use Core\Database;

/**
 * Model de Serviço
 */
class Servico extends Model
{
    protected $table = 'servicos';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'categoria',
        'tempo_estimado',
        'status',
    ];
    
    /**
     * Categorias de serviços disponíveis
     */
    public static function getCategorias(): array
    {
        return [
            'instalacao' => 'Instalação',
            'manutencao' => 'Manutenção',
            'reparo' => 'Reparo',
            'automatizacao' => 'Automatização',
            'vistoria' => 'Vistoria',
            'outros' => 'Outros',
        ];
    }
    
    /**
     * Retorna os serviços mais recentes
     */
    public function getRecent(int $limit = 5): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit";
        return Database::getInstance()->query($sql, ['limit' => $limit])->fetchAll();
    }
    
    /**
     * Busca serviços por nome ou categoria
     */
    public function search(string $term): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE nome LIKE :term OR categoria LIKE :term 
                ORDER BY nome ASC";
        return Database::getInstance()->query($sql, ['term' => "%{$term}%"])->fetchAll();
    }
    
    /**
     * Retorna serviços por categoria
     */
    public function getByCategoria(string $categoria): array
    {
        return $this->where('categoria', $categoria);
    }
    
    /**
     * Retorna serviços ativos
     */
    public function getAtivos(): array
    {
        return $this->where('status', 'ativo');
    }
    
    /**
     * Conta serviços por categoria
     */
    public function countByCategoria(): array
    {
        $sql = "SELECT categoria, COUNT(*) as total FROM {$this->table} 
                WHERE status = 'ativo' 
                GROUP BY categoria";
        return Database::getInstance()->query($sql)->fetchAll();
    }
}

