<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\ContaReceber;
use App\Models\Orcamento;
use App\Models\Peca;
use App\Models\NotificacaoUsuario;

/**
 * Controller de Notificações
 */
class NotificacaoController extends Controller
{
    private NotificacaoUsuario $notificacaoUsuarioModel;
    
    public function __construct()
    {
        $this->requireAuth();
        $this->notificacaoUsuarioModel = new NotificacaoUsuario();
    }
    
    /**
     * Retorna todas as notificações (JSON)
     */
    public function index(): void
    {
        $notificacoes = $this->getNotificacoes();
        $this->json($notificacoes);
    }
    
    /**
     * Exibe página de notificações
     */
    public function page(): void
    {
        $notificacoes = $this->getNotificacoes(false); // Inclui lidas na página completa
        $lidas = $this->notificacaoUsuarioModel->getLidas();
        
        $this->view('notificacoes/index', [
            'title' => 'Notificações',
            'notificacoes' => $notificacoes,
            'lidas' => $lidas,
        ]);
    }
    
    /**
     * Marca notificação como lida
     */
    public function marcarLida(): void
    {
        $hash = $_POST['hash'] ?? '';
        
        if (empty($hash)) {
            $this->json(['error' => 'Hash inválido'], 400);
            return;
        }
        
        $this->notificacaoUsuarioModel->marcarComoLida($hash);
        $this->json(['success' => true, 'message' => 'Notificação marcada como lida']);
    }
    
    /**
     * Marca todas as notificações como lidas
     */
    public function marcarTodasLidas(): void
    {
        $notificacoes = $this->getNotificacoesBase();
        $hashes = array_map(fn($n) => $n['hash'], $notificacoes);
        
        $this->notificacaoUsuarioModel->marcarTodasComoLidas($hashes);
        
        if (is_ajax()) {
            $this->json(['success' => true, 'message' => 'Todas as notificações foram marcadas como lidas']);
        } else {
            $this->flash('success', 'Todas as notificações foram marcadas como lidas');
            $this->redirect(base_url('notificacoes'));
        }
    }
    
    /**
     * Exclui notificação (não mostra mais)
     */
    public function excluir(): void
    {
        $hash = $_POST['hash'] ?? '';
        
        if (empty($hash)) {
            $this->json(['error' => 'Hash inválido'], 400);
            return;
        }
        
        $this->notificacaoUsuarioModel->excluir($hash);
        $this->json(['success' => true, 'message' => 'Notificação excluída']);
    }
    
    /**
     * Coleta todas as notificações do sistema (base)
     */
    private function getNotificacoesBase(): array
    {
        $notificacoes = [];
        
        // Contas vencidas
        $contaModel = new ContaReceber();
        $contaModel->atualizarVencidas();
        $contasVencidas = $contaModel->getVencidas();
        
        foreach ($contasVencidas as $conta) {
            $diasVencido = floor((time() - strtotime($conta->data_vencimento)) / 86400);
            $notif = [
                'tipo' => 'danger',
                'icone' => 'exclamation-triangle',
                'titulo' => 'Conta vencida há ' . $diasVencido . ' dias',
                'mensagem' => $conta->cliente_nome . ' - ' . money($conta->valor_pendente),
                'link' => base_url("contas/{$conta->id}"),
                'data' => $conta->data_vencimento,
            ];
            // Hash único por conta e status
            $notif['hash'] = md5('conta_vencida_' . $conta->id);
            $notificacoes[] = $notif;
        }
        
        // Contas próximas do vencimento (7 dias)
        $contasProximas = $contaModel->getProximasVencer(7);
        
        foreach ($contasProximas as $conta) {
            $diasRestantes = floor((strtotime($conta->data_vencimento) - time()) / 86400);
            $notif = [
                'tipo' => 'warning',
                'icone' => 'clock',
                'titulo' => $diasRestantes == 0 ? 'Vence hoje!' : "Vence em {$diasRestantes} dias",
                'mensagem' => $conta->cliente_nome . ' - ' . money($conta->valor_pendente),
                'link' => base_url("contas/{$conta->id}"),
                'data' => $conta->data_vencimento,
            ];
            // Hash único por conta
            $notif['hash'] = md5('conta_proxima_' . $conta->id);
            $notificacoes[] = $notif;
        }
        
        // Orçamentos pendentes
        $orcamentoModel = new Orcamento();
        $orcamentosPendentes = $orcamentoModel->countByStatus('pendente');
        
        if ($orcamentosPendentes > 0) {
            $notif = [
                'tipo' => 'info',
                'icone' => 'file-earmark-text',
                'titulo' => $orcamentosPendentes . ' orçamento(s) pendente(s)',
                'mensagem' => 'Aguardando aprovação do cliente',
                'link' => base_url('orcamentos?status=pendente'),
                'data' => date('Y-m-d'),
            ];
            // Hash inclui quantidade para mudar quando número de pendentes mudar
            $notif['hash'] = md5('orcamentos_pendentes_' . $orcamentosPendentes);
            $notificacoes[] = $notif;
        }
        
        // Peças com estoque baixo (mostra cada uma)
        $pecaModel = new Peca();
        $pecasEstoqueBaixo = $pecaModel->getEstoqueBaixo();
        
        foreach ($pecasEstoqueBaixo as $peca) {
            $notif = [
                'tipo' => 'warning',
                'icone' => 'box-seam',
                'titulo' => 'Estoque baixo: ' . $peca->nome,
                'mensagem' => "Restam apenas {$peca->quantidade_estoque} unidades (mínimo: {$peca->estoque_minimo})",
                'link' => base_url("pecas/{$peca->id}"),
                'data' => date('Y-m-d'),
            ];
            // Hash inclui quantidade para que mude quando estoque mudar
            $notif['hash'] = md5('estoque_baixo_' . $peca->id . '_' . $peca->quantidade_estoque);
            $notificacoes[] = $notif;
        }
        
        // Ordena por tipo (danger primeiro) e data
        usort($notificacoes, function($a, $b) {
            $prioridade = ['danger' => 1, 'warning' => 2, 'info' => 3, 'success' => 4];
            $pA = $prioridade[$a['tipo']] ?? 5;
            $pB = $prioridade[$b['tipo']] ?? 5;
            
            if ($pA === $pB) {
                return strtotime($a['data']) - strtotime($b['data']);
            }
            return $pA - $pB;
        });
        
        return $notificacoes;
    }
    
    /**
     * Coleta notificações filtradas (sem excluídas e sem lidas por padrão)
     */
    public function getNotificacoes(bool $ocultarLidas = true): array
    {
        $notificacoes = $this->getNotificacoesBase();
        
        // Pega notificações excluídas
        $excluidas = $this->notificacaoUsuarioModel->getExcluidas();
        $lidas = $ocultarLidas ? $this->notificacaoUsuarioModel->getLidas() : [];
        
        // Filtra notificações excluídas
        $notificacoes = array_filter($notificacoes, function($n) use ($excluidas, $lidas, $ocultarLidas) {
            // Remove excluídas
            if (in_array($n['hash'], $excluidas)) {
                return false;
            }
            // Remove lidas se solicitado
            if ($ocultarLidas && in_array($n['hash'], $lidas)) {
                return false;
            }
            return true;
        });
        
        return array_values($notificacoes);
    }
    
    /**
     * Conta total de notificações não lidas
     */
    public static function count(): int
    {
        $controller = new self();
        return count($controller->getNotificacoes());
    }
}
