<?php
use App\Models\Orcamento;
$statuses = Orcamento::getStatuses();
?>

<div class="page-header">
    <div class="page-header-left">
        <h2>Lista de Orçamentos</h2>
        <p>Gerencie todos os orçamentos</p>
    </div>
    <div class="page-header-right">
        <a href="<?= base_url('orcamentos/novo') ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> Novo Orçamento
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form class="filter-form" method="GET">
            <div class="filter-group">
                <label>Filtrar por status:</label>
                <div class="filter-buttons">
                    <a href="<?= base_url('orcamentos') ?>" class="btn btn-sm <?= empty($statusFilter) ? 'btn-primary' : 'btn-outline' ?>">
                        Todos
                    </a>
                    <?php foreach ($statuses as $value => $label): ?>
                        <a href="?status=<?= $value ?>" class="btn btn-sm <?= $statusFilter === $value ? 'btn-primary' : 'btn-outline' ?>">
                            <?= $label ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($orcamentos['data'])): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orcamentos['data'] as $orcamento): ?>
                            <tr>
                                <td><strong>#<?= $orcamento->id ?></strong></td>
                                <td><?= e($orcamento->cliente_nome ?? 'N/A') ?></td>
                                <td><?= e(substr($orcamento->descricao, 0, 40)) ?>...</td>
                                <td><strong><?= money($orcamento->valor_final ?? 0) ?></strong></td>
                                <td>
                                    <span class="badge badge-<?= status_class($orcamento->status) ?>">
                                        <?= status_text($orcamento->status) ?>
                                    </span>
                                </td>
                                <td><?= format_date($orcamento->created_at) ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="<?= base_url("orcamentos/{$orcamento->id}") ?>" class="btn btn-icon btn-ghost" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url("orcamentos/{$orcamento->id}/editar") ?>" class="btn btn-icon btn-ghost" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= base_url("orcamentos/{$orcamento->id}/pdf") ?>" class="btn btn-icon btn-ghost" title="PDF" target="_blank">
                                            <i class="bi bi-file-pdf"></i>
                                        </a>
                                        <form action="<?= base_url("orcamentos/{$orcamento->id}/excluir") ?>" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este orçamento?')">
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
            
            <?php if ($orcamentos['total_pages'] > 1): ?>
                <div class="pagination">
                    <?php if ($orcamentos['has_prev']): ?>
                        <a href="?page=<?= $orcamentos['current_page'] - 1 ?><?= $statusFilter ? '&status=' . $statusFilter : '' ?>" class="btn btn-sm btn-outline">
                            <i class="bi bi-chevron-left"></i> Anterior
                        </a>
                    <?php endif; ?>
                    
                    <span class="pagination-info">
                        Página <?= $orcamentos['current_page'] ?> de <?= $orcamentos['total_pages'] ?>
                    </span>
                    
                    <?php if ($orcamentos['has_next']): ?>
                        <a href="?page=<?= $orcamentos['current_page'] + 1 ?><?= $statusFilter ? '&status=' . $statusFilter : '' ?>" class="btn btn-sm btn-outline">
                            Próxima <i class="bi bi-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-file-earmark-text"></i>
                <h4>Nenhum orçamento encontrado</h4>
                <p>Comece criando seu primeiro orçamento</p>
                <a href="<?= base_url('orcamentos/novo') ?>" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Novo Orçamento
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

