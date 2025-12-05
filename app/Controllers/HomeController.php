<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Cliente;
use App\Models\Servico;
use App\Models\Orcamento;
use App\Models\ContaReceber;
use App\Models\Peca;

/**
 * Controller da página inicial (Dashboard)
 */
class HomeController extends Controller
{
    /**
     * Exibe o dashboard
     */
    public function index(): void
    {
        // Requer autenticação
        $this->requireAuth();
        
        $clienteModel = new Cliente();
        $servicoModel = new Servico();
        $orcamentoModel = new Orcamento();
        $contaModel = new ContaReceber();
        $pecaModel = new Peca();
        
        // Atualiza contas vencidas
        $contaModel->atualizarVencidas();
        
        // Estatísticas para o dashboard
        $stats = [
            'total_clientes' => $clienteModel->count(),
            'total_servicos' => $servicoModel->count(),
            'orcamentos_pendentes' => $orcamentoModel->countByStatus('pendente'),
            'total_a_receber' => $contaModel->getTotalPendente(),
        ];
        
        // Estatísticas de contas
        $contasStats = $contaModel->getEstatisticas();
        
        // Últimos orçamentos
        $ultimosOrcamentos = $orcamentoModel->getRecent(5);
        
        // Últimos clientes
        $ultimosClientes = $clienteModel->getRecent(5);
        
        // Contas vencidas
        $contasVencidas = $contaModel->getVencidas();
        
        // Contas próximas do vencimento
        $contasProximas = $contaModel->getProximasVencer(7);
        
        // Peças com estoque baixo
        $pecasEstoqueBaixo = $pecaModel->getEstoqueBaixo();
        
        $this->view('home/index', [
            'title' => 'Dashboard',
            'stats' => $stats,
            'contasStats' => $contasStats,
            'ultimosOrcamentos' => $ultimosOrcamentos,
            'ultimosClientes' => $ultimosClientes,
            'contasVencidas' => $contasVencidas,
            'contasProximas' => $contasProximas,
            'pecasEstoqueBaixo' => $pecasEstoqueBaixo,
        ]);
    }
}
