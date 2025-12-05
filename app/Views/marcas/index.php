<div class="page-header">
    <div class="page-header-left">
        <h2>Marcas</h2>
        <p>Gerencie as marcas de peças</p>
    </div>
    <div class="page-header-right">
        <a href="<?= base_url('marcas/novo') ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> Nova Marca
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($marcas)): ?>
            <div class="marcas-grid">
                <?php foreach ($marcas as $marca): ?>
                    <div class="marca-card">
                        <div class="marca-header">
                            <div class="marca-icon">
                                <i class="bi bi-tag"></i>
                            </div>
                            <div class="marca-info">
                                <h4><?= e($marca->nome) ?></h4>
                                <span class="badge badge-<?= $marca->status === 'ativo' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($marca->status) ?>
                                </span>
                            </div>
                        </div>
                        <div class="marca-stats">
                            <span><i class="bi bi-box"></i> <?= $marca->total_pecas ?> peças</span>
                        </div>
                        <?php if (!empty($marca->descricao)): ?>
                            <p class="marca-desc"><?= e(substr($marca->descricao, 0, 80)) ?>...</p>
                        <?php endif; ?>
                        <div class="marca-actions">
                            <a href="<?= base_url("pecas?marca={$marca->id}") ?>" class="btn btn-sm btn-outline">
                                <i class="bi bi-eye"></i> Ver Peças
                            </a>
                            <a href="<?= base_url("marcas/{$marca->id}/editar") ?>" class="btn btn-sm btn-ghost">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?= base_url("marcas/{$marca->id}/excluir") ?>" method="POST" class="inline" 
                                  onsubmit="return confirm('Excluir esta marca?')">
                                <button type="submit" class="btn btn-sm btn-ghost text-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-tag"></i>
                <h4>Nenhuma marca cadastrada</h4>
                <p>Comece adicionando a primeira marca</p>
                <a href="<?= base_url('marcas/novo') ?>" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Nova Marca
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.marcas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: var(--spacing-lg);
}
.marca-card {
    background: var(--gray-50);
    border-radius: var(--radius-md);
    padding: var(--spacing-lg);
    transition: all var(--transition-fast);
}
.marca-card:hover {
    box-shadow: var(--shadow-md);
}
.marca-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
}
.marca-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    background: var(--primary-bg);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}
.marca-info h4 {
    font-size: 1rem;
    margin-bottom: 4px;
}
.marca-stats {
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: var(--spacing-sm);
}
.marca-desc {
    color: var(--text-secondary);
    font-size: 0.8125rem;
    margin-bottom: var(--spacing-md);
}
.marca-actions {
    display: flex;
    gap: var(--spacing-sm);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--border-color);
}
</style>

