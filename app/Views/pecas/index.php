<div class="page-header">
    <div class="page-header-left">
        <h2>Peças</h2>
        <p>Gerencie o estoque de peças</p>
    </div>
    <div class="page-header-right">
        <a href="<?= base_url('marcas') ?>" class="btn btn-outline">
            <i class="bi bi-tag"></i> Marcas
        </a>
        <a href="<?= base_url('pecas/novo') ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> Nova Peça
        </a>
    </div>
</div>

<!-- Filtro por Marca -->
<div class="card mb-4">
    <div class="card-body">
        <div class="filter-form">
            <div class="filter-group">
                <label>Filtrar por marca:</label>
                <div class="filter-buttons">
                    <a href="<?= base_url('pecas') ?>" class="btn btn-sm <?= !$marcaFilter ? 'btn-primary' : 'btn-outline' ?>">
                        Todas
                    </a>
                    <?php foreach ($marcas as $marca): ?>
                        <a href="?marca=<?= $marca->id ?>" class="btn btn-sm <?= $marcaFilter == $marca->id ? 'btn-primary' : 'btn-outline' ?>">
                            <?= e($marca->nome) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($pecas['data'])): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Peça</th>
                            <th>Marca</th>
                            <th class="text-right">Preço Venda</th>
                            <th class="text-center">Estoque</th>
                            <th>Status</th>
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pecas['data'] as $peca): ?>
                            <tr>
                                <td><code><?= e($peca->codigo ?: '-') ?></code></td>
                                <td>
                                    <strong><?= e($peca->nome) ?></strong>
                                    <?php if (!empty($peca->descricao)): ?>
                                        <br><small class="text-muted"><?= e(substr($peca->descricao, 0, 50)) ?>...</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($peca->marca_nome): ?>
                                        <span class="badge badge-secondary"><?= e($peca->marca_nome) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right"><strong><?= money($peca->preco_venda) ?></strong></td>
                                <td class="text-center">
                                    <?php if ($peca->quantidade_estoque <= $peca->estoque_minimo): ?>
                                        <span class="badge badge-danger"><?= $peca->quantidade_estoque ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><?= $peca->quantidade_estoque ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $peca->status === 'ativo' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($peca->status) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="<?= base_url("pecas/{$peca->id}") ?>" class="btn btn-icon btn-ghost" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url("pecas/{$peca->id}/editar") ?>" class="btn btn-icon btn-ghost" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= base_url("pecas/{$peca->id}/excluir") ?>" method="POST" class="inline" 
                                              onsubmit="return confirm('Excluir esta peça?')">
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
            
            <?php if ($pecas['total_pages'] > 1): ?>
                <div class="pagination">
                    <?php if ($pecas['has_prev']): ?>
                        <a href="?page=<?= $pecas['current_page'] - 1 ?><?= $marcaFilter ? "&marca={$marcaFilter}" : '' ?>" class="btn btn-sm btn-outline">
                            <i class="bi bi-chevron-left"></i> Anterior
                        </a>
                    <?php endif; ?>
                    <span class="pagination-info">Página <?= $pecas['current_page'] ?> de <?= $pecas['total_pages'] ?></span>
                    <?php if ($pecas['has_next']): ?>
                        <a href="?page=<?= $pecas['current_page'] + 1 ?><?= $marcaFilter ? "&marca={$marcaFilter}" : '' ?>" class="btn btn-sm btn-outline">
                            Próxima <i class="bi bi-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-box"></i>
                <h4>Nenhuma peça encontrada</h4>
                <p>Comece adicionando a primeira peça</p>
                <a href="<?= base_url('pecas/novo') ?>" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Nova Peça
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

