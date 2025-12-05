<?php use App\Models\ContaReceber; $statuses = ContaReceber::getStatuses(); ?>

<div class="page-header">
    <div class="page-header-left">
        <h2>Contas a Receber</h2>
        <p>Gerencie pagamentos e cobranças</p>
    </div>
    <div class="page-header-right">
        <a href="<?= base_url('contas/novo') ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> Nova Conta
        </a>
    </div>
</div>

<!-- Resumo -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-info">
            <span class="stat-value"><?= $estatisticas['pendentes'] ?? 0 ?></span>
            <span class="stat-label">Pendentes</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
            <i class="bi bi-pie-chart"></i>
        </div>
        <div class="stat-info">
            <span class="stat-value"><?= $estatisticas['parciais'] ?? 0 ?></span>
            <span class="stat-label">Pagamento Parcial</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="stat-info">
            <span class="stat-value"><?= $estatisticas['vencidas'] ?? 0 ?></span>
            <span class="stat-label">Vencidas</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-currency-dollar"></i>
        </div>
        <div class="stat-info">
            <span class="stat-value"><?= money($estatisticas['total_pendente'] ?? 0) ?></span>
            <span class="stat-label">Total a Receber</span>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <div class="filter-form">
            <div class="filter-group">
                <label>Filtrar por status:</label>
                <div class="filter-buttons">
                    <a href="<?= base_url('contas') ?>" class="btn btn-sm <?= !$statusFilter ? 'btn-primary' : 'btn-outline' ?>">
                        Todos
                    </a>
                    <?php foreach ($statuses as $value => $label): ?>
                        <a href="?status=<?= $value ?>" class="btn btn-sm <?= $statusFilter === $value ? 'btn-primary' : 'btn-outline' ?>">
                            <?= $label ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($contas['data'])): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Descrição</th>
                            <th class="text-right">Valor Total</th>
                            <th class="text-right">Pendente</th>
                            <th>Vencimento</th>
                            <th>Status</th>
                            <th width="140">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contas['data'] as $conta): ?>
                            <tr class="<?= $conta->status === 'vencido' ? 'row-danger' : '' ?>">
                                <td>
                                    <strong><?= e($conta->cliente_nome) ?></strong>
                                    <br><small class="text-muted"><?= format_phone($conta->cliente_telefone) ?></small>
                                </td>
                                <td><?= e(substr($conta->descricao, 0, 40)) ?>...</td>
                                <td class="text-right"><?= money($conta->valor_total) ?></td>
                                <td class="text-right">
                                    <strong class="<?= $conta->valor_pendente > 0 ? 'text-danger' : 'text-success' ?>">
                                        <?= money($conta->valor_pendente) ?>
                                    </strong>
                                </td>
                                <td>
                                    <?php 
                                    $vencimento = strtotime($conta->data_vencimento);
                                    $hoje = strtotime('today');
                                    $diasRestantes = floor(($vencimento - $hoje) / 86400);
                                    ?>
                                    <span class="<?= $diasRestantes < 0 ? 'text-danger' : ($diasRestantes <= 3 ? 'text-warning' : '') ?>">
                                        <?= format_date($conta->data_vencimento) ?>
                                        <?php if ($diasRestantes < 0): ?>
                                            <br><small>(<?= abs($diasRestantes) ?> dias atrás)</small>
                                        <?php elseif ($diasRestantes == 0): ?>
                                            <br><small class="text-warning">(Hoje!)</small>
                                        <?php elseif ($diasRestantes <= 7): ?>
                                            <br><small>(em <?= $diasRestantes ?> dias)</small>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= 
                                        $conta->status === 'pago' ? 'success' : 
                                        ($conta->status === 'vencido' ? 'danger' : 
                                        ($conta->status === 'parcial' ? 'warning' : 'info')) 
                                    ?>">
                                        <?= $statuses[$conta->status] ?? ucfirst($conta->status) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="<?= base_url("contas/{$conta->id}") ?>" class="btn btn-icon btn-ghost" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if ($conta->status !== 'pago'): ?>
                                            <a href="<?= base_url("contas/{$conta->id}") ?>" class="btn btn-icon btn-ghost text-success" title="Registrar Pagamento">
                                                <i class="bi bi-cash"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= base_url("contas/{$conta->id}/editar") ?>" class="btn btn-icon btn-ghost" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= base_url("contas/{$conta->id}/excluir") ?>" method="POST" class="inline" 
                                              onsubmit="return confirm('Excluir esta conta?')">
                                            <button type="submit" class="btn btn-icon btn-ghost text-danger" title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($contas['total_pages'] > 1): ?>
                <div class="pagination">
                    <?php if ($contas['has_prev']): ?>
                        <a href="?page=<?= $contas['current_page'] - 1 ?><?= $statusFilter ? "&status={$statusFilter}" : '' ?>" class="btn btn-sm btn-outline">
                            <i class="bi bi-chevron-left"></i> Anterior
                        </a>
                    <?php endif; ?>
                    <span class="pagination-info">Página <?= $contas['current_page'] ?> de <?= $contas['total_pages'] ?></span>
                    <?php if ($contas['has_next']): ?>
                        <a href="?page=<?= $contas['current_page'] + 1 ?><?= $statusFilter ? "&status={$statusFilter}" : '' ?>" class="btn btn-sm btn-outline">
                            Próxima <i class="bi bi-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-cash-stack"></i>
                <h4>Nenhuma conta encontrada</h4>
                <p>Não há contas a receber cadastradas</p>
                <a href="<?= base_url('contas/novo') ?>" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Nova Conta
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.row-danger {
    background: #fef2f2 !important;
}
.row-danger:hover {
    background: #fee2e2 !important;
}
</style>

