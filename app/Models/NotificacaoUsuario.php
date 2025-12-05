<?php

namespace App\Models;

use Core\Model;
use Core\Database;

/**
 * Model para gerenciar notificações lidas/excluídas
 */
class NotificacaoUsuario extends Model
{
    protected $table = 'notificacoes_usuario';
    protected $timestamps = false;
    
    protected $fillable = [
        'hash_notificacao',
        'status',
    ];
    
    /**
     * Gera hash único para uma notificação
     */
    public static function gerarHash(array $notificacao): string
    {
        return md5($notificacao['tipo'] . $notificacao['titulo'] . $notificacao['link']);
    }
    
    /**
     * Verifica se notificação foi lida
     */
    public function foiLida(string $hash): bool
    {
        $sql = "SELECT id FROM {$this->table} WHERE hash_notificacao = :hash AND status = 'lida' LIMIT 1";
        $result = Database::getInstance()->query($sql, ['hash' => $hash])->fetch();
        return (bool) $result;
    }
    
    /**
     * Verifica se notificação foi excluída
     */
    public function foiExcluida(string $hash): bool
    {
        $sql = "SELECT id FROM {$this->table} WHERE hash_notificacao = :hash AND status = 'excluida' LIMIT 1";
        $result = Database::getInstance()->query($sql, ['hash' => $hash])->fetch();
        return (bool) $result;
    }
    
    /**
     * Retorna todos os hashes de notificações excluídas
     */
    public function getExcluidas(): array
    {
        $sql = "SELECT hash_notificacao FROM {$this->table} WHERE status = 'excluida'";
        $results = Database::getInstance()->query($sql)->fetchAll();
        return array_column($results, 'hash_notificacao');
    }
    
    /**
     * Retorna todos os hashes de notificações lidas
     */
    public function getLidas(): array
    {
        $sql = "SELECT hash_notificacao FROM {$this->table} WHERE status = 'lida'";
        $results = Database::getInstance()->query($sql)->fetchAll();
        return array_column($results, 'hash_notificacao');
    }
    
    /**
     * Marca notificação como lida
     */
    public function marcarComoLida(string $hash): bool
    {
        // Verifica se já existe
        $sql = "SELECT id FROM {$this->table} WHERE hash_notificacao = :hash LIMIT 1";
        $existing = Database::getInstance()->query($sql, ['hash' => $hash])->fetch();
        
        if ($existing) {
            $sql = "UPDATE {$this->table} SET status = 'lida' WHERE hash_notificacao = :hash";
        } else {
            $sql = "INSERT INTO {$this->table} (hash_notificacao, status) VALUES (:hash, 'lida')";
        }
        
        Database::getInstance()->query($sql, ['hash' => $hash]);
        return true;
    }
    
    /**
     * Marca notificação como excluída
     */
    public function excluir(string $hash): bool
    {
        // Verifica se já existe
        $sql = "SELECT id FROM {$this->table} WHERE hash_notificacao = :hash LIMIT 1";
        $existing = Database::getInstance()->query($sql, ['hash' => $hash])->fetch();
        
        if ($existing) {
            $sql = "UPDATE {$this->table} SET status = 'excluida' WHERE hash_notificacao = :hash";
        } else {
            $sql = "INSERT INTO {$this->table} (hash_notificacao, status) VALUES (:hash, 'excluida')";
        }
        
        Database::getInstance()->query($sql, ['hash' => $hash]);
        return true;
    }
    
    /**
     * Marca todas como lidas
     */
    public function marcarTodasComoLidas(array $hashes): bool
    {
        foreach ($hashes as $hash) {
            $this->marcarComoLida($hash);
        }
        return true;
    }
    
    /**
     * Limpa notificações antigas (mais de 30 dias)
     */
    public function limparAntigas(): int
    {
        $sql = "DELETE FROM {$this->table} WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)";
        Database::getInstance()->query($sql);
        return Database::getInstance()->rowCount();
    }
}

