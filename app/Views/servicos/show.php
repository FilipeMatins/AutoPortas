<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('servicos') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2><?= e($servico->nome) ?></h2>
            <p>Detalhes do serviço</p>
        </div>
    </div>
    <div class="page-header-right">
        <a href="<?= base_url("servicos/{$servico->id}/editar") ?>" class="btn btn-outline">
            <i class="bi bi-pencil"></i> Editar
        </a>
    </div>
</div>

<div class="detail-grid">
    <!-- Informações do Serviço -->
    <div class="card">
        <div class="card-header">
            <h3>Informações do Serviço</h3>
        </div>
        <div class="card-body">
            <dl class="detail-list">
                <div class="detail-item">
                    <dt><i class="bi bi-tag"></i> Nome</dt>
                    <dd><?= e($servico->nome) ?></dd>
                </div>
                
                <div class="detail-item">
                    <dt><i class="bi bi-folder"></i> Categoria</dt>
                    <dd>
                        <span class="badge badge-secondary">
                            <?= ucfirst($servico->categoria ?? 'outros') ?>
                        </span>
                    </dd>
                </div>
                
                <div class="detail-item">
                    <dt><i class="bi bi-currency-dollar"></i> Preço Base</dt>
                    <dd class="text-primary"><strong><?= money($servico->preco ?? 0) ?></strong></dd>
                </div>
                
                <?php if (!empty($servico->tempo_estimado)): ?>
                <div class="detail-item">
                    <dt><i class="bi bi-clock"></i> Tempo Estimado</dt>
                    <dd><?= e($servico->tempo_estimado) ?></dd>
                </div>
                <?php endif; ?>
                
                <div class="detail-item">
                    <dt><i class="bi bi-toggle-on"></i> Status</dt>
                    <dd>
                        <span class="badge badge-<?= status_class($servico->status ?? 'ativo') ?>">
                            <?= status_text($servico->status ?? 'ativo') ?>
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- Descrição -->
    <div class="card">
        <div class="card-header">
            <h3>Descrição</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($servico->descricao)): ?>
                <p><?= nl2br(e($servico->descricao)) ?></p>
            <?php else: ?>
                <p class="text-muted">Nenhuma descrição cadastrada.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Informações de registro -->
<div class="card">
    <div class="card-body">
        <div class="meta-info">
            <span><i class="bi bi-calendar"></i> Cadastrado em <?= format_datetime($servico->created_at) ?></span>
            <?php if (!empty($servico->updated_at)): ?>
                <span><i class="bi bi-clock"></i> Atualizado em <?= format_datetime($servico->updated_at) ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

