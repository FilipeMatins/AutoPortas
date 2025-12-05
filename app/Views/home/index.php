<div class="dashboard">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value"><?= $stats['total_clientes'] ?? 0 ?></span>
                <span class="stat-label">Clientes</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-wrench-adjustable"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value"><?= $stats['total_servicos'] ?? 0 ?></span>
                <span class="stat-label">Serviços</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value"><?= $stats['orcamentos_pendentes'] ?? 0 ?></span>
                <span class="stat-label">Orçamentos Pendentes</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value"><?= money($stats['total_a_receber'] ?? 0) ?></span>
                <span class="stat-label">A Receber</span>
            </div>
        </div>
    </div>
    
    <!-- Alertas de Cobrança -->
    <?php if (!empty($contasVencidas) || !empty($contasProximas)): ?>
    <div class="alert-section">
        <?php if (!empty($contasVencidas)): ?>
        <div class="alert alert-error">
            <i class="bi bi-exclamation-triangle"></i>
            <div>
                <strong><?= count($contasVencidas) ?> conta(s) vencida(s)!</strong>
                <span>Você tem cobranças em atraso que precisam de atenção.</span>
            </div>
            <a href="<?= base_url('contas?status=vencido') ?>" class="btn btn-sm btn-danger">Ver contas</a>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($contasProximas)): ?>
        <div class="alert alert-warning">
            <i class="bi bi-clock"></i>
            <div>
                <strong><?= count($contasProximas) ?> conta(s) próxima(s) do vencimento</strong>
                <span>Vencendo nos próximos 7 dias.</span>
            </div>
            <a href="<?= base_url('contas?status=pendente') ?>" class="btn btn-sm btn-outline">Ver contas</a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- Content Grid -->
    <div class="dashboard-grid">
        <!-- Últimos Orçamentos -->
        <div class="card">
            <div class="card-header">
                <h3>Últimos Orçamentos</h3>
                <a href="<?= base_url('orcamentos') ?>" class="btn btn-sm btn-outline">Ver todos</a>
            </div>
            <div class="card-body">
                <?php if (!empty($ultimosOrcamentos)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ultimosOrcamentos as $orcamento): ?>
                                    <tr>
                                        <td><strong>#<?= $orcamento->id ?></strong></td>
                                        <td><?= e($orcamento->cliente_nome ?? 'N/A') ?></td>
                                        <td><?= money($orcamento->valor_final ?? 0) ?></td>
                                        <td>
                                            <span class="badge badge-<?= status_class($orcamento->status) ?>">
                                                <?= status_text($orcamento->status) ?>
                                            </span>
                                        </td>
                                        <td><?= format_date($orcamento->created_at) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-file-earmark-text"></i>
                        <p>Nenhum orçamento cadastrado</p>
                        <a href="<?= base_url('orcamentos/novo') ?>" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Criar Orçamento
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Últimos Clientes -->
        <div class="card">
            <div class="card-header">
                <h3>Últimos Clientes</h3>
                <a href="<?= base_url('clientes') ?>" class="btn btn-sm btn-outline">Ver todos</a>
            </div>
            <div class="card-body">
                <?php if (!empty($ultimosClientes)): ?>
                    <ul class="client-list">
                        <?php foreach ($ultimosClientes as $cliente): ?>
                            <li class="client-item">
                                <div class="client-avatar">
                                    <?= strtoupper(substr($cliente->nome, 0, 1)) ?>
                                </div>
                                <div class="client-info">
                                    <span class="client-name"><?= e($cliente->nome) ?></span>
                                    <span class="client-contact"><?= format_phone($cliente->telefone) ?></span>
                                </div>
                                <a href="<?= base_url("clientes/{$cliente->id}") ?>" class="btn btn-icon btn-ghost">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-people"></i>
                        <p>Nenhum cliente cadastrado</p>
                        <a href="<?= base_url('clientes/novo') ?>" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Adicionar Cliente
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Peças com Estoque Baixo -->
    <?php if (!empty($pecasEstoqueBaixo)): ?>
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-exclamation-triangle text-warning"></i> Peças com Estoque Baixo</h3>
            <a href="<?= base_url('pecas') ?>" class="btn btn-sm btn-outline">Ver todas</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Peça</th>
                            <th>Marca</th>
                            <th class="text-center">Em Estoque</th>
                            <th class="text-center">Mínimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($pecasEstoqueBaixo, 0, 5) as $peca): ?>
                            <tr>
                                <td><strong><?= e($peca->nome) ?></strong></td>
                                <td><span class="badge badge-secondary"><?= e($peca->marca_nome ?? '-') ?></span></td>
                                <td class="text-center">
                                    <span class="badge badge-danger"><?= $peca->quantidade_estoque ?></span>
                                </td>
                                <td class="text-center"><?= $peca->estoque_minimo ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>Ações Rápidas</h3>
        <div class="actions-grid">
            <a href="<?= base_url('clientes/novo') ?>" class="action-card">
                <i class="bi bi-person-plus"></i>
                <span>Novo Cliente</span>
            </a>
            <a href="<?= base_url('orcamentos/novo') ?>" class="action-card">
                <i class="bi bi-file-earmark-plus"></i>
                <span>Novo Orçamento</span>
            </a>
            <a href="<?= base_url('contas/novo') ?>" class="action-card">
                <i class="bi bi-cash-stack"></i>
                <span>Nova Cobrança</span>
            </a>
        </div>
    </div>
</div>

