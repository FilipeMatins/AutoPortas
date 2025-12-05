<div class="page-header">
    <div class="page-header-left">
        <h2>Lista de Serviços</h2>
        <p>Gerencie todos os serviços oferecidos</p>
    </div>
    <div class="page-header-right">
        <a href="<?= base_url('servicos/novo') ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> Novo Serviço
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($servicos['data'])): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Serviço</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>Status</th>
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servicos['data'] as $servico): ?>
                            <tr>
                                <td>
                                    <div>
                                        <strong><?= e($servico->nome) ?></strong>
                                        <?php if (!empty($servico->descricao)): ?>
                                            <br><small class="text-muted"><?= e(substr($servico->descricao, 0, 60)) ?>...</small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <?= ucfirst($servico->categoria ?? 'outros') ?>
                                    </span>
                                </td>
                                <td><strong><?= money($servico->preco ?? 0) ?></strong></td>
                                <td>
                                    <span class="badge badge-<?= status_class($servico->status ?? 'ativo') ?>">
                                        <?= status_text($servico->status ?? 'ativo') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="<?= base_url("servicos/{$servico->id}") ?>" class="btn btn-icon btn-ghost" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url("servicos/{$servico->id}/editar") ?>" class="btn btn-icon btn-ghost" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= base_url("servicos/{$servico->id}/excluir") ?>" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este serviço?')">
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
            
            <?php if ($servicos['total_pages'] > 1): ?>
                <div class="pagination">
                    <?php if ($servicos['has_prev']): ?>
                        <a href="?page=<?= $servicos['current_page'] - 1 ?>" class="btn btn-sm btn-outline">
                            <i class="bi bi-chevron-left"></i> Anterior
                        </a>
                    <?php endif; ?>
                    
                    <span class="pagination-info">
                        Página <?= $servicos['current_page'] ?> de <?= $servicos['total_pages'] ?>
                    </span>
                    
                    <?php if ($servicos['has_next']): ?>
                        <a href="?page=<?= $servicos['current_page'] + 1 ?>" class="btn btn-sm btn-outline">
                            Próxima <i class="bi bi-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-wrench-adjustable"></i>
                <h4>Nenhum serviço cadastrado</h4>
                <p>Comece adicionando seu primeiro serviço</p>
                <a href="<?= base_url('servicos/novo') ?>" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Novo Serviço
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

