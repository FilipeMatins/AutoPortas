<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('pecas') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2><?= e($peca->nome) ?></h2>
            <p>Detalhes da peça</p>
        </div>
    </div>
    <div class="page-header-right">
        <a href="<?= base_url("pecas/{$peca->id}/editar") ?>" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Editar
        </a>
    </div>
</div>

<div class="detail-grid">
    <div class="card">
        <div class="card-header">
            <h3>Informações</h3>
        </div>
        <div class="card-body">
            <dl class="detail-list">
                <?php if (!empty($peca->codigo)): ?>
                <div class="detail-item">
                    <dt><i class="bi bi-upc"></i> Código</dt>
                    <dd><code><?= e($peca->codigo) ?></code></dd>
                </div>
                <?php endif; ?>
                
                <div class="detail-item">
                    <dt><i class="bi bi-box"></i> Nome</dt>
                    <dd><?= e($peca->nome) ?></dd>
                </div>
                
                <div class="detail-item">
                    <dt><i class="bi bi-tag"></i> Marca</dt>
                    <dd>
                        <?php if ($peca->marca_nome): ?>
                            <span class="badge badge-secondary"><?= e($peca->marca_nome) ?></span>
                        <?php else: ?>
                            <span class="text-muted">Sem marca</span>
                        <?php endif; ?>
                    </dd>
                </div>
                
                <div class="detail-item">
                    <dt><i class="bi bi-toggle-on"></i> Status</dt>
                    <dd>
                        <span class="badge badge-<?= $peca->status === 'ativo' ? 'success' : 'secondary' ?>">
                            <?= ucfirst($peca->status) ?>
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3>Preços e Estoque</h3>
        </div>
        <div class="card-body">
            <dl class="detail-list">
                <div class="detail-item">
                    <dt><i class="bi bi-cash"></i> Preço de Custo</dt>
                    <dd><?= money($peca->preco_custo ?? 0) ?></dd>
                </div>
                
                <div class="detail-item">
                    <dt><i class="bi bi-currency-dollar"></i> Preço de Venda</dt>
                    <dd class="text-primary"><strong><?= money($peca->preco_venda ?? 0) ?></strong></dd>
                </div>
                
                <div class="detail-item">
                    <dt><i class="bi bi-graph-up"></i> Margem</dt>
                    <dd>
                        <?php 
                        $margem = $peca->preco_custo > 0 
                            ? (($peca->preco_venda - $peca->preco_custo) / $peca->preco_custo) * 100 
                            : 0;
                        ?>
                        <span class="badge badge-<?= $margem >= 30 ? 'success' : ($margem >= 15 ? 'warning' : 'danger') ?>">
                            <?= number_format($margem, 1) ?>%
                        </span>
                    </dd>
                </div>
                
                <div class="detail-item">
                    <dt><i class="bi bi-boxes"></i> Em Estoque</dt>
                    <dd>
                        <?php if ($peca->quantidade_estoque <= $peca->estoque_minimo): ?>
                            <span class="badge badge-danger"><?= $peca->quantidade_estoque ?> unidades</span>
                            <small class="text-danger">(Estoque baixo!)</small>
                        <?php else: ?>
                            <span class="badge badge-success"><?= $peca->quantidade_estoque ?> unidades</span>
                        <?php endif; ?>
                    </dd>
                </div>
                
                <div class="detail-item">
                    <dt><i class="bi bi-exclamation-triangle"></i> Estoque Mínimo</dt>
                    <dd><?= $peca->estoque_minimo ?> unidades</dd>
                </div>
                
                <?php if (!empty($peca->localizacao)): ?>
                <div class="detail-item">
                    <dt><i class="bi bi-geo-alt"></i> Localização</dt>
                    <dd><?= e($peca->localizacao) ?></dd>
                </div>
                <?php endif; ?>
            </dl>
        </div>
    </div>
</div>

<?php if (!empty($peca->descricao)): ?>
<div class="card">
    <div class="card-header">
        <h3>Descrição</h3>
    </div>
    <div class="card-body">
        <p><?= nl2br(e($peca->descricao)) ?></p>
    </div>
</div>
<?php endif; ?>

