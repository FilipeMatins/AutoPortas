<?php
use App\Models\Orcamento;
use App\Models\ContaReceber;

$statuses = Orcamento::getStatuses();
$formasPagamento = Orcamento::getFormasPagamento();

// Verifica se já tem conta vinculada
$orcamentoModel = new Orcamento();
$contaVinculada = $orcamentoModel->getContaReceber($orcamento->id);
?>

<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('orcamentos') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2>Orçamento #<?= $orcamento->id ?></h2>
            <p>Detalhes do orçamento</p>
        </div>
    </div>
    <div class="page-header-right">
        <a href="<?= base_url("orcamentos/{$orcamento->id}/pdf") ?>" class="btn btn-outline" target="_blank">
            <i class="bi bi-file-pdf"></i> Gerar PDF
        </a>
        <a href="<?= base_url("orcamentos/{$orcamento->id}/editar") ?>" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Editar
        </a>
    </div>
</div>

<div class="orcamento-detail">
    <!-- Status Banner -->
    <div class="status-banner status-<?= $orcamento->status ?>">
        <div class="status-info">
            <span class="status-label">Status:</span>
            <span class="status-value"><?= $statuses[$orcamento->status] ?? ucfirst($orcamento->status) ?></span>
        </div>
        <form action="<?= base_url("orcamentos/{$orcamento->id}/status") ?>" method="POST" class="status-form">
            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                <?php foreach ($statuses as $value => $label): ?>
                    <option value="<?= $value ?>" <?= $orcamento->status === $value ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    
    <!-- Cobrança Vinculada ou Gerar Cobrança -->
    <?php if ($contaVinculada): ?>
        <!-- Já tem cobrança -->
        <div class="card cobranca-card">
            <div class="card-body">
                <div class="cobranca-status">
                    <div class="cobranca-icon <?= $contaVinculada->status ?>">
                        <i class="bi bi-<?= $contaVinculada->status === 'pago' ? 'check-circle' : ($contaVinculada->status === 'vencido' ? 'exclamation-triangle' : 'clock') ?>"></i>
                    </div>
                    <div class="cobranca-info">
                        <h4>
                            <?php if ($contaVinculada->status === 'pago'): ?>
                                <span class="text-success">Pagamento Completo</span>
                            <?php elseif ($contaVinculada->status === 'parcial'): ?>
                                <span class="text-warning">Pagamento Parcial</span>
                            <?php elseif ($contaVinculada->status === 'vencido'): ?>
                                <span class="text-danger">Pagamento Vencido!</span>
                            <?php else: ?>
                                <span class="text-info">Aguardando Pagamento</span>
                            <?php endif; ?>
                        </h4>
                        <p>
                            <?php if ($contaVinculada->status !== 'pago'): ?>
                                Pendente: <strong class="text-danger"><?= money($contaVinculada->valor_pendente) ?></strong>
                                | Vencimento: <?= format_date($contaVinculada->data_vencimento) ?>
                            <?php else: ?>
                                Pago em <?= format_date($contaVinculada->data_pagamento) ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <a href="<?= base_url("contas/{$contaVinculada->id}") ?>" class="btn btn-outline">
                        <i class="bi bi-eye"></i> Ver Cobrança
                    </a>
                </div>
            </div>
        </div>
    <?php elseif (in_array($orcamento->status, ['aprovado', 'em_execucao', 'concluido'])): ?>
        <!-- Formulário para gerar cobrança -->
        <div class="card cobranca-card">
            <div class="card-header">
                <h3><i class="bi bi-cash-stack"></i> Gerar Cobrança</h3>
            </div>
            <div class="card-body">
                <form action="<?= base_url("orcamentos/{$orcamento->id}/cobranca") ?>" method="POST" class="cobranca-form">
                    <?= csrf_field() ?>
                    
                    <div class="cobranca-grid">
                        <div class="cobranca-valor-total">
                            <span>Valor do Orçamento</span>
                            <strong><?= money($orcamento->valor_final) ?></strong>
                        </div>
                        
                        <div class="form-group">
                            <label for="valor_pago">Cliente já pagou?</label>
                            <div class="input-group">
                                <span class="input-prefix">R$</span>
                                <input type="text" id="valor_pago" name="valor_pago" class="form-control" 
                                       value="0,00" data-mask="money" placeholder="Valor já pago">
                            </div>
                            <small class="text-muted">Se pagou metade, informe aqui</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="prazo_vencimento">Prazo para pagamento</label>
                            <select id="prazo_vencimento" class="form-control" onchange="atualizarDataVencimento()">
                                <option value="0">À vista (hoje)</option>
                                <option value="3">3 dias</option>
                                <option value="7" selected>7 dias</option>
                                <option value="15">15 dias</option>
                                <option value="30">30 dias</option>
                                <option value="45">45 dias</option>
                                <option value="60">60 dias</option>
                                <option value="custom">Data personalizada</option>
                            </select>
                            <input type="date" id="data_vencimento" name="data_vencimento" class="form-control mt-2" 
                                   value="<?= date('Y-m-d', strtotime('+7 days')) ?>" style="display: none;">
                        </div>
                        
                        <script>
                        function atualizarDataVencimento() {
                            var prazo = document.getElementById('prazo_vencimento').value;
                            var dataInput = document.getElementById('data_vencimento');
                            
                            if (prazo === 'custom') {
                                dataInput.style.display = 'block';
                            } else {
                                dataInput.style.display = 'none';
                                var data = new Date();
                                data.setDate(data.getDate() + parseInt(prazo));
                                dataInput.value = data.toISOString().split('T')[0];
                            }
                        }
                        </script>
                        
                        <div class="form-group">
                            <label for="forma_pagamento">Forma de Pagamento</label>
                            <select id="forma_pagamento" name="forma_pagamento" class="form-control">
                                <option value="">Selecione...</option>
                                <?php foreach ($formasPagamento as $value => $label): ?>
                                    <option value="<?= $value ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="cobranca-actions">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check"></i> Gerar Cobrança
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="detail-grid">
        <!-- Informações do Cliente -->
        <div class="card">
            <div class="card-header">
                <h3><i class="bi bi-person"></i> Cliente</h3>
            </div>
            <div class="card-body">
                <dl class="detail-list">
                    <div class="detail-item">
                        <dt>Nome</dt>
                        <dd><strong><?= e($orcamento->cliente_nome ?? 'N/A') ?></strong></dd>
                    </div>
                    <?php if (!empty($orcamento->cliente_telefone)): ?>
                    <div class="detail-item">
                        <dt>Telefone</dt>
                        <dd>
                            <a href="https://wa.me/55<?= preg_replace('/\D/', '', $orcamento->cliente_telefone) ?>" target="_blank" class="btn btn-sm btn-outline" style="gap: 4px;">
                                <i class="bi bi-whatsapp"></i> <?= format_phone($orcamento->cliente_telefone) ?>
                            </a>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($orcamento->cliente_email)): ?>
                    <div class="detail-item">
                        <dt>Email</dt>
                        <dd><a href="mailto:<?= e($orcamento->cliente_email) ?>"><?= e($orcamento->cliente_email) ?></a></dd>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($orcamento->cliente_endereco)): ?>
                    <div class="detail-item">
                        <dt>Endereço</dt>
                        <dd><?= e($orcamento->cliente_endereco) ?><?= !empty($orcamento->cliente_cidade) ? ', ' . e($orcamento->cliente_cidade) : '' ?><?= !empty($orcamento->cliente_estado) ? ' - ' . e($orcamento->cliente_estado) : '' ?></dd>
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
                        <dd><?= money($orcamento->valor_total ?? 0) ?></dd>
                    </div>
                    <div class="detail-item">
                        <dt>Desconto</dt>
                        <dd class="text-danger">- <?= money($orcamento->desconto ?? 0) ?></dd>
                    </div>
                    <div class="detail-item total">
                        <dt>Valor Final</dt>
                        <dd class="text-primary"><strong><?= money($orcamento->valor_final ?? 0) ?></strong></dd>
                    </div>
                </dl>
                
                <?php if (!empty($orcamento->forma_pagamento)): ?>
                    <div class="payment-info">
                        <i class="bi bi-credit-card"></i>
                        <?= $formasPagamento[$orcamento->forma_pagamento] ?? ucfirst($orcamento->forma_pagamento) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Descrição -->
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-file-text"></i> Descrição do Serviço</h3>
        </div>
        <div class="card-body">
            <p><?= nl2br(e($orcamento->descricao)) ?></p>
        </div>
    </div>
    
    <!-- Serviços -->
    <?php if (!empty($orcamento->servicos)): ?>
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-wrench"></i> Serviços Incluídos</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Serviço</th>
                            <th class="text-center">Qtd</th>
                            <th class="text-right">Valor Unit.</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orcamento->servicos as $servico): ?>
                            <tr>
                                <td><?= e($servico->servico_nome) ?></td>
                                <td class="text-center"><?= $servico->quantidade ?></td>
                                <td class="text-right"><?= money($servico->valor_unitario) ?></td>
                                <td class="text-right"><strong><?= money($servico->valor_total) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Peças -->
    <?php if (!empty($orcamento->pecas)): ?>
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-box-seam"></i> Peças Utilizadas</h3>
            <?php 
            $pecasBaixadas = array_filter($orcamento->pecas, fn($p) => $p->baixa_estoque);
            if (count($pecasBaixadas) > 0): 
            ?>
                <span class="badge badge-success"><i class="bi bi-check"></i> Estoque baixado</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Peça</th>
                            <th>Marca</th>
                            <th class="text-center">Qtd</th>
                            <th class="text-right">Valor Unit.</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orcamento->pecas as $peca): ?>
                            <tr>
                                <td><code><?= e($peca->peca_codigo) ?></code></td>
                                <td><?= e($peca->peca_nome) ?></td>
                                <td><?= e($peca->marca_nome) ?></td>
                                <td class="text-center"><?= $peca->quantidade ?></td>
                                <td class="text-right"><?= money($peca->preco_unitario) ?></td>
                                <td class="text-right"><strong><?= money($peca->preco_total) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($orcamento->status !== 'concluido' && count($pecasBaixadas) === 0): ?>
                <p class="text-muted mt-2">
                    <i class="bi bi-info-circle"></i> 
                    O estoque será baixado automaticamente quando o orçamento for <strong>Concluído</strong>.
                </p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Observações -->
    <?php if (!empty($orcamento->observacoes)): ?>
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-chat-text"></i> Observações</h3>
        </div>
        <div class="card-body">
            <p><?= nl2br(e($orcamento->observacoes)) ?></p>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Informações de registro -->
    <div class="card">
        <div class="card-body">
            <div class="meta-info">
                <span><i class="bi bi-calendar"></i> Criado em <?= format_datetime($orcamento->created_at) ?></span>
                <?php if (!empty($orcamento->data_validade)): ?>
                    <span><i class="bi bi-calendar-check"></i> Válido até <?= format_date($orcamento->data_validade) ?></span>
                <?php endif; ?>
                <?php if (!empty($orcamento->updated_at)): ?>
                    <span><i class="bi bi-clock"></i> Atualizado em <?= format_datetime($orcamento->updated_at) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.cobranca-card {
    margin-bottom: var(--spacing-lg);
}

.cobranca-status {
    display: flex;
    align-items: center;
    gap: var(--spacing-lg);
}

.cobranca-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.cobranca-icon.pago {
    background: var(--success-bg);
    color: var(--success);
}

.cobranca-icon.parcial {
    background: var(--warning-bg);
    color: var(--warning);
}

.cobranca-icon.vencido {
    background: var(--danger-bg);
    color: var(--danger);
}

.cobranca-icon.pendente {
    background: var(--info-bg);
    color: var(--info);
}

.cobranca-info {
    flex: 1;
}

.cobranca-info h4 {
    font-size: 1.125rem;
    margin-bottom: 4px;
}

.cobranca-info p {
    color: var(--text-secondary);
    margin: 0;
}

.cobranca-grid {
    display: grid;
    grid-template-columns: auto 1fr 1fr 1fr;
    gap: var(--spacing-lg);
    align-items: start;
}

.cobranca-grid .form-group {
    margin-bottom: 0;
}

.mt-2 {
    margin-top: 8px;
}

.cobranca-valor-total {
    display: flex;
    flex-direction: column;
    padding: var(--spacing-md) var(--spacing-lg);
    background: var(--primary-bg);
    border-radius: var(--radius-md);
}

.cobranca-valor-total span {
    font-size: 0.75rem;
    color: var(--primary);
    text-transform: uppercase;
}

.cobranca-valor-total strong {
    font-size: 1.5rem;
    color: var(--primary);
}

.cobranca-actions {
    margin-top: var(--spacing-lg);
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: flex-end;
}

@media (max-width: 992px) {
    .cobranca-grid {
        grid-template-columns: 1fr 1fr;
    }
    
    .cobranca-valor-total {
        grid-column: span 2;
    }
}

@media (max-width: 576px) {
    .cobranca-grid {
        grid-template-columns: 1fr;
    }
    
    .cobranca-valor-total {
        grid-column: span 1;
    }
    
    .cobranca-status {
        flex-direction: column;
        text-align: center;
    }
}
</style>
