<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('clientes') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2><?= e($cliente->nome) ?></h2>
            <p>Detalhes do cliente</p>
        </div>
    </div>
    <div class="page-header-right">
        <a href="<?= base_url("clientes/{$cliente->id}/editar") ?>" class="btn btn-outline">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="<?= base_url('orcamentos/novo?cliente_id=' . $cliente->id) ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> Novo Orçamento
        </a>
    </div>
</div>

<div class="detail-grid">
    <!-- Informações do Cliente -->
    <div class="card">
        <div class="card-header">
            <h3>Informações Pessoais</h3>
        </div>
        <div class="card-body">
            <div class="detail-avatar">
                <?= strtoupper(substr($cliente->nome, 0, 2)) ?>
            </div>
            
            <dl class="detail-list">
                <div class="detail-item">
                    <dt><i class="bi bi-person"></i> Nome</dt>
                    <dd><?= e($cliente->nome) ?></dd>
                </div>
                
                <?php if (!empty($cliente->email)): ?>
                <div class="detail-item">
                    <dt><i class="bi bi-envelope"></i> Email</dt>
                    <dd><a href="mailto:<?= e($cliente->email) ?>"><?= e($cliente->email) ?></a></dd>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($cliente->telefone)): ?>
                <div class="detail-item">
                    <dt><i class="bi bi-telephone"></i> Telefone</dt>
                    <dd><a href="tel:<?= preg_replace('/\D/', '', $cliente->telefone) ?>"><?= format_phone($cliente->telefone) ?></a></dd>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($cliente->cpf_cnpj)): ?>
                <div class="detail-item">
                    <dt><i class="bi bi-card-text"></i> CPF/CNPJ</dt>
                    <dd><?= format_document($cliente->cpf_cnpj) ?></dd>
                </div>
                <?php endif; ?>
            </dl>
        </div>
    </div>
    
    <!-- Endereço -->
    <div class="card">
        <div class="card-header">
            <h3>Endereço</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($cliente->endereco) || !empty($cliente->cidade)): ?>
                <dl class="detail-list">
                    <?php if (!empty($cliente->endereco)): ?>
                    <div class="detail-item">
                        <dt><i class="bi bi-geo-alt"></i> Endereço</dt>
                        <dd><?= e($cliente->endereco) ?></dd>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($cliente->cidade) || !empty($cliente->estado)): ?>
                    <div class="detail-item">
                        <dt><i class="bi bi-building"></i> Cidade/Estado</dt>
                        <dd><?= e($cliente->cidade ?? '') ?><?= !empty($cliente->estado) ? ' - ' . e($cliente->estado) : '' ?></dd>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($cliente->cep)): ?>
                    <div class="detail-item">
                        <dt><i class="bi bi-mailbox"></i> CEP</dt>
                        <dd><?= e($cliente->cep) ?></dd>
                    </div>
                    <?php endif; ?>
                </dl>
            <?php else: ?>
                <div class="empty-state small">
                    <i class="bi bi-geo-alt"></i>
                    <p>Endereço não cadastrado</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Observações -->
    <div class="card full-width">
        <div class="card-header">
            <h3>Observações</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($cliente->observacoes)): ?>
                <p><?= nl2br(e($cliente->observacoes)) ?></p>
            <?php else: ?>
                <p class="text-muted">Nenhuma observação cadastrada.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Informações de registro -->
<div class="card">
    <div class="card-body">
        <div class="meta-info">
            <span><i class="bi bi-calendar"></i> Cadastrado em <?= format_datetime($cliente->created_at) ?></span>
            <?php if (!empty($cliente->updated_at)): ?>
                <span><i class="bi bi-clock"></i> Atualizado em <?= format_datetime($cliente->updated_at) ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

