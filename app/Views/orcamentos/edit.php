<?php
use App\Models\Orcamento;
$statuses = Orcamento::getStatuses();
$formasPagamento = Orcamento::getFormasPagamento();
$servicosIds = array_column($orcamento->servicos ?? [], 'servico_id');
?>

<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('orcamentos') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2>Editar Orçamento #<?= $orcamento->id ?></h2>
            <p>Atualize as informações do orçamento</p>
        </div>
    </div>
</div>

<form action="<?= base_url("orcamentos/{$orcamento->id}") ?>" method="POST" class="form">
    <?= csrf_field() ?>
    
    <div class="form-layout">
        <!-- Coluna Principal -->
        <div class="form-main">
            <div class="card">
                <div class="card-header">
                    <h3>Informações do Orçamento</h3>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <?php 
                        // Busca cliente atual do orçamento
                        $clienteAtual = null;
                        foreach ($clientes as $c) {
                            if ($c->id == $orcamento->cliente_id) {
                                $clienteAtual = $c;
                                break;
                            }
                        }
                        ?>
                        <div class="form-group col-span-2">
                            <label>Cliente <span class="required">*</span></label>
                            <div class="client-search-container" data-clientes='<?= json_encode(array_map(function($c) { return ["id" => $c->id, "nome" => $c->nome, "telefone" => format_phone($c->telefone)]; }, $clientes)) ?>'>
                                <i class="bi bi-search client-search-icon"></i>
                                <input type="text" class="client-search-input" placeholder="Digite o nome do cliente..." autocomplete="off" value="<?= $clienteAtual ? e($clienteAtual->nome) : '' ?>">
                                <input type="hidden" name="cliente_id" id="cliente_id" class="client-search-value" value="<?= $orcamento->cliente_id ?>" required>
                                <div class="client-search-dropdown"></div>
                            </div>
                            <small class="text-muted">Digite pelo menos 2 letras para buscar</small>
                        </div>
                        
                        <div class="form-group col-span-3">
                            <label for="descricao">Descrição do Serviço <span class="required">*</span></label>
                            <textarea id="descricao" name="descricao" class="form-control" rows="4" required><?= e($orcamento->descricao) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Serviços -->
            <div class="card">
                <div class="card-header">
                    <h3>Serviços</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($servicos)): ?>
                        <div class="servicos-list">
                            <?php foreach ($servicos as $servico): 
                                $servicoOrcamento = null;
                                foreach ($orcamento->servicos ?? [] as $so) {
                                    if ($so->servico_id == $servico->id) {
                                        $servicoOrcamento = $so;
                                        break;
                                    }
                                }
                                $selecionado = $servicoOrcamento !== null;
                            ?>
                                <div class="servico-item">
                                    <label class="checkbox-container">
                                        <input type="checkbox" name="servicos[<?= $servico->id ?>][selecionado]" value="1" class="servico-check" data-preco="<?= $servico->preco ?>" <?= $selecionado ? 'checked' : '' ?>>
                                        <span class="checkmark"></span>
                                        <div class="servico-info">
                                            <strong><?= e($servico->nome) ?></strong>
                                            <span class="servico-preco"><?= money($servico->preco) ?></span>
                                        </div>
                                    </label>
                                    <div class="servico-inputs" style="<?= $selecionado ? '' : 'display: none;' ?>">
                                        <div class="form-group">
                                            <label>Qtd</label>
                                            <input type="number" name="servicos[<?= $servico->id ?>][quantidade]" value="<?= $servicoOrcamento->quantidade ?? 1 ?>" min="1" class="form-control servico-qtd">
                                        </div>
                                        <div class="form-group">
                                            <label>Valor Unit.</label>
                                            <input type="text" name="servicos[<?= $servico->id ?>][valor_unitario]" value="<?= number_format($servicoOrcamento->valor_unitario ?? $servico->preco, 2, ',', '.') ?>" class="form-control servico-valor" data-mask="money">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state small">
                            <i class="bi bi-wrench"></i>
                            <p>Nenhum serviço cadastrado</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Peças -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="bi bi-box-seam"></i> Peças Utilizadas</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($pecas)): ?>
                        <?php $pecasIds = array_column($orcamento->pecas ?? [], 'peca_id'); ?>
                        <div class="peca-add-container">
                            <div class="peca-search-container" data-pecas='<?= json_encode(array_map(function($p) { return ["id" => $p->id, "codigo" => $p->codigo, "nome" => $p->nome, "marca" => $p->marca_nome ?? "", "estoque" => $p->quantidade_estoque, "preco" => $p->preco_venda]; }, $pecas), JSON_UNESCAPED_UNICODE | JSON_HEX_APOS) ?>'>
                                <i class="bi bi-search client-search-icon"></i>
                                <input type="text" class="client-search-input peca-add-input" placeholder="Buscar peça por código ou nome..." autocomplete="off">
                                <div class="client-search-dropdown peca-add-dropdown"></div>
                            </div>
                        </div>
                        <div id="pecas-selecionadas" class="pecas-selecionadas">
                            <?php foreach ($orcamento->pecas ?? [] as $pecaOrc): ?>
                                <div class="peca-item peca-item-existente" data-id="<?= $pecaOrc->peca_id ?>">
                                    <div class="peca-item-info">
                                        <strong><?= e($pecaOrc->peca_codigo) ?> - <?= e($pecaOrc->peca_nome) ?></strong>
                                        <small>Estoque: <?= $pecaOrc->quantidade_estoque ?></small>
                                    </div>
                                    <div class="peca-item-inputs">
                                        <input type="hidden" name="pecas[<?= $pecaOrc->peca_id ?>][selecionado]" value="1">
                                        <div class="form-group">
                                            <label>Qtd</label>
                                            <input type="number" name="pecas[<?= $pecaOrc->peca_id ?>][quantidade]" value="<?= $pecaOrc->quantidade ?>" min="1" class="form-control peca-qtd" onchange="OrcamentosPage.updateTotal()">
                                        </div>
                                        <div class="form-group">
                                            <label>Valor Unit.</label>
                                            <input type="text" name="pecas[<?= $pecaOrc->peca_id ?>][preco_unitario]" value="<?= number_format($pecaOrc->preco_unitario, 2, ',', '.') ?>" class="form-control peca-valor" data-mask="money" onchange="OrcamentosPage.updateTotal()">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-ghost btn-sm text-danger" onclick="OrcamentosPage.removerPeca(<?= $pecaOrc->peca_id ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="empty-state small" id="pecas-empty" style="<?= !empty($orcamento->pecas) ? 'display:none' : '' ?>">
                            <i class="bi bi-box"></i>
                            <p>Nenhuma peça adicionada</p>
                            <small>Busque uma peça acima para adicionar</small>
                        </div>
                    <?php else: ?>
                        <div class="empty-state small">
                            <i class="bi bi-box"></i>
                            <p>Nenhuma peça cadastrada</p>
                            <a href="<?= base_url('pecas/novo') ?>" class="btn btn-sm btn-outline">Cadastrar Peça</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Observações -->
            <div class="card">
                <div class="card-header">
                    <h3>Observações</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea id="observacoes" name="observacoes" class="form-control" rows="3"><?= e($orcamento->observacoes ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="form-sidebar">
            <div class="card sticky">
                <div class="card-header">
                    <h3>Resumo</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <?php foreach ($statuses as $value => $label): ?>
                                <option value="<?= $value ?>" <?= $orcamento->status === $value ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="valor_total">Valor Total</label>
                        <div class="input-group">
                            <span class="input-prefix">R$</span>
                            <input type="text" id="valor_total" name="valor_total" class="form-control" 
                                   value="<?= number_format($orcamento->valor_total ?? 0, 2, ',', '.') ?>" data-mask="money" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="desconto">Desconto</label>
                        <div class="input-group">
                            <span class="input-prefix">R$</span>
                            <input type="text" id="desconto" name="desconto" class="form-control" 
                                   value="<?= number_format($orcamento->desconto ?? 0, 2, ',', '.') ?>" data-mask="money">
                        </div>
                    </div>
                    
                    <div class="total-display">
                        <span>Valor Final</span>
                        <strong id="valor_final_display"><?= money($orcamento->valor_final ?? 0) ?></strong>
                    </div>
                    
                    <hr>
                    
                    <div class="form-group">
                        <label for="forma_pagamento">Forma de Pagamento</label>
                        <select id="forma_pagamento" name="forma_pagamento" class="form-control">
                            <option value="">Selecione...</option>
                            <?php foreach ($formasPagamento as $value => $label): ?>
                                <option value="<?= $value ?>" <?= ($orcamento->forma_pagamento ?? '') === $value ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_validade">Validade do Orçamento</label>
                        <input type="date" id="data_validade" name="data_validade" class="form-control" 
                               value="<?= $orcamento->data_validade ?? '' ?>">
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?= base_url('orcamentos') ?>" class="btn btn-outline btn-block">Cancelar</a>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="bi bi-check"></i> Salvar Alterações
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

