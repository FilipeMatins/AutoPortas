<?php 
use App\Models\ContaReceber;
use App\Models\Orcamento;
$statuses = ContaReceber::getStatuses();
$formasPagamento = Orcamento::getFormasPagamento();
?>

<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('contas') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2>Conta #<?= $conta->id ?></h2>
            <p>Detalhes da conta a receber</p>
        </div>
    </div>
    <div class="page-header-right">
        <a href="<?= base_url("contas/{$conta->id}/editar") ?>" class="btn btn-outline">
            <i class="bi bi-pencil"></i> Editar
        </a>
    </div>
</div>

<!-- Status Banner -->
<div class="status-banner status-<?= $conta->status ?>">
    <div class="status-info">
        <span class="status-label">Status:</span>
        <span class="status-value"><?= $statuses[$conta->status] ?? ucfirst($conta->status) ?></span>
    </div>
    <?php if ($conta->status === 'vencido'): ?>
        <div class="status-alert">
            <i class="bi bi-exclamation-triangle"></i>
            Vencida há <?= floor((time() - strtotime($conta->data_vencimento)) / 86400) ?> dias
        </div>
    <?php endif; ?>
</div>

<div class="detail-grid">
    <!-- Cliente -->
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-person"></i> Cliente</h3>
        </div>
        <div class="card-body">
            <dl class="detail-list">
                <div class="detail-item">
                    <dt>Nome</dt>
                    <dd><strong><?= e($conta->cliente_nome) ?></strong></dd>
                </div>
                <?php if (!empty($conta->cliente_telefone)): ?>
                <div class="detail-item">
                    <dt>Telefone</dt>
                    <dd>
                        <a href="https://wa.me/55<?= preg_replace('/\D/', '', $conta->cliente_telefone) ?>" target="_blank" class="btn btn-sm btn-outline" style="gap: 4px;">
                            <i class="bi bi-whatsapp"></i> <?= format_phone($conta->cliente_telefone) ?>
                        </a>
                    </dd>
                </div>
                <?php endif; ?>
                <?php if (!empty($conta->cliente_email)): ?>
                <div class="detail-item">
                    <dt>Email</dt>
                    <dd><a href="mailto:<?= e($conta->cliente_email) ?>"><?= e($conta->cliente_email) ?></a></dd>
                </div>
                <?php endif; ?>
            </dl>
        </div>
    </div>
    
    <!-- Valores -->
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-currency-dollar"></i> Valores</h3>
        </div>
        <div class="card-body">
            <dl class="detail-list">
                <div class="detail-item">
                    <dt>Valor Total</dt>
                    <dd><?= money($conta->valor_total) ?></dd>
                </div>
                <div class="detail-item">
                    <dt>Valor Pago</dt>
                    <dd class="text-success"><?= money($conta->valor_pago) ?></dd>
                </div>
                <div class="detail-item total">
                    <dt>Valor Pendente</dt>
                    <dd class="<?= $conta->valor_pendente > 0 ? 'text-danger' : 'text-success' ?>">
                        <strong><?= money($conta->valor_pendente) ?></strong>
                    </dd>
                </div>
                <div class="detail-item">
                    <dt>Vencimento</dt>
                    <dd><?= format_date($conta->data_vencimento) ?></dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- Registrar Pagamento -->
<?php if ($conta->status !== 'pago' && $conta->status !== 'cancelado'): ?>
<div class="card">
    <div class="card-header">
        <h3><i class="bi bi-cash"></i> Registrar Pagamento</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url("contas/{$conta->id}/pagamento") ?>" method="POST" class="form">
            <?= csrf_field() ?>
            
            <div class="form-grid" style="grid-template-columns: repeat(4, 1fr);">
                <div class="form-group">
                    <label for="valor">Valor do Pagamento <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-prefix">R$</span>
                        <input type="text" id="valor" name="valor" class="form-control" 
                               value="<?= number_format($conta->valor_pendente, 2, ',', '.') ?>" data-mask="money" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="data_pagamento">Data</label>
                    <input type="date" id="data_pagamento" name="data_pagamento" class="form-control" 
                           value="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="form-group">
                    <label for="forma_pagamento">Forma de Pagamento</label>
                    <select id="forma_pagamento" name="forma_pagamento" class="form-control">
                        <option value="">Selecione...</option>
                        <?php foreach ($formasPagamento as $value => $label): ?>
                            <option value="<?= $value ?>"><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="bi bi-check"></i> Registrar
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label for="observacoes">Observações</label>
                <input type="text" id="observacoes" name="observacoes" class="form-control" 
                       placeholder="Anotações sobre este pagamento...">
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Histórico de Pagamentos -->
<?php if (!empty($conta->pagamentos)): ?>
<div class="card">
    <div class="card-header">
        <h3><i class="bi bi-clock-history"></i> Histórico de Pagamentos</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Valor</th>
                        <th>Forma</th>
                        <th>Observações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($conta->pagamentos as $pagamento): ?>
                        <tr>
                            <td><?= format_date($pagamento->data_pagamento) ?></td>
                            <td class="text-success"><strong><?= money($pagamento->valor) ?></strong></td>
                            <td><?= $formasPagamento[$pagamento->forma_pagamento] ?? ucfirst($pagamento->forma_pagamento ?? '-') ?></td>
                            <td><?= e($pagamento->observacoes ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Descrição/Observações -->
<div class="card">
    <div class="card-header">
        <h3>Descrição</h3>
    </div>
    <div class="card-body">
        <p><strong><?= e($conta->descricao) ?></strong></p>
        <?php if (!empty($conta->observacoes)): ?>
            <hr>
            <p class="text-muted"><?= nl2br(e($conta->observacoes)) ?></p>
        <?php endif; ?>
    </div>
</div>

<style>
.status-banner.status-vencido { background: #fee2e2; }
.status-banner.status-parcial { background: #fef3c7; }
.status-banner.status-pago { background: #d1fae5; }
.status-alert {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #dc2626;
    font-weight: 500;
}
</style>

