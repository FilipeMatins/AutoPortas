<?php
use App\Models\Orcamento;
$formasPagamento = Orcamento::getFormasPagamento();
?>

<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('orcamentos') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2>Novo Orçamento</h2>
            <p>Crie um novo orçamento para o cliente</p>
        </div>
    </div>
</div>

<form action="<?= base_url('orcamentos') ?>" method="POST" class="form">
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
                        // Verifica se veio cliente_id na URL
                        $clienteSelecionado = null;
                        $clienteIdUrl = $_GET['cliente_id'] ?? '';
                        if ($clienteIdUrl) {
                            foreach ($clientes as $c) {
                                if ($c->id == $clienteIdUrl) {
                                    $clienteSelecionado = $c;
                                    break;
                                }
                            }
                        }
                        ?>
                        <div class="form-group col-span-2">
                            <label>Cliente <span class="required">*</span></label>
                            <div class="client-search-container" data-clientes='<?= json_encode(array_map(function($c) { return ["id" => $c->id, "nome" => $c->nome, "telefone" => format_phone($c->telefone)]; }, $clientes)) ?>'>
                                <i class="bi bi-search client-search-icon"></i>
                                <input type="text" class="client-search-input" placeholder="Digite o nome do cliente..." autocomplete="off" value="<?= $clienteSelecionado ? e($clienteSelecionado->nome) : '' ?>">
                                <input type="hidden" name="cliente_id" id="cliente_id" class="client-search-value" value="<?= $clienteIdUrl ?>" required>
                                <div class="client-search-dropdown"></div>
                            </div>
                            <small class="text-muted">Digite pelo menos 2 letras para buscar</small>
                        </div>
                        
                        <div class="form-group col-span-3">
                            <label for="descricao">Descrição do Serviço <span class="required">*</span></label>
                            <textarea id="descricao" name="descricao" class="form-control" rows="4" required placeholder="Descreva detalhadamente o serviço a ser realizado..."><?= old('descricao') ?></textarea>
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
                            <?php foreach ($servicos as $servico): ?>
                                <div class="servico-item">
                                    <label class="checkbox-container">
                                        <input type="checkbox" name="servicos[<?= $servico->id ?>][selecionado]" value="1" class="servico-check" data-preco="<?= $servico->preco ?>">
                                        <span class="checkmark"></span>
                                        <div class="servico-info">
                                            <strong><?= e($servico->nome) ?></strong>
                                            <span class="servico-preco"><?= money($servico->preco) ?></span>
                                        </div>
                                    </label>
                                    <div class="servico-inputs" style="display: none;">
                                        <div class="form-group">
                                            <label>Qtd</label>
                                            <input type="number" name="servicos[<?= $servico->id ?>][quantidade]" value="1" min="1" class="form-control servico-qtd">
                                        </div>
                                        <div class="form-group">
                                            <label>Valor Unit.</label>
                                            <input type="text" name="servicos[<?= $servico->id ?>][valor_unitario]" value="<?= number_format($servico->preco, 2, ',', '.') ?>" class="form-control servico-valor" data-mask="money">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state small">
                            <i class="bi bi-wrench"></i>
                            <p>Nenhum serviço cadastrado</p>
                            <a href="<?= base_url('servicos/novo') ?>" class="btn btn-sm btn-outline">Cadastrar Serviço</a>
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
                        <div class="peca-add-container">
                            <div class="peca-search-container" data-pecas='<?= json_encode(array_map(function($p) { return ["id" => $p->id, "codigo" => $p->codigo, "nome" => $p->nome, "marca" => $p->marca_nome ?? "", "estoque" => $p->quantidade_estoque, "preco" => $p->preco_venda]; }, $pecas), JSON_UNESCAPED_UNICODE | JSON_HEX_APOS) ?>'>
                                <i class="bi bi-search client-search-icon"></i>
                                <input type="text" class="client-search-input peca-add-input" placeholder="Buscar peça por código ou nome..." autocomplete="off">
                                <div class="client-search-dropdown peca-add-dropdown"></div>
                            </div>
                        </div>
                        <div id="pecas-selecionadas" class="pecas-selecionadas">
                            <!-- Peças adicionadas aparecem aqui -->
                        </div>
                        <div class="empty-state small" id="pecas-empty">
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
                        <textarea id="observacoes" name="observacoes" class="form-control" rows="3" placeholder="Observações adicionais..."><?= old('observacoes') ?></textarea>
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
                        <label for="valor_total">Valor Total</label>
                        <div class="input-group">
                            <span class="input-prefix">R$</span>
                            <input type="text" id="valor_total" name="valor_total" class="form-control" 
                                   value="<?= old('valor_total', '0,00') ?>" data-mask="money" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="desconto">Desconto</label>
                        <div class="input-group">
                            <span class="input-prefix">R$</span>
                            <input type="text" id="desconto" name="desconto" class="form-control" 
                                   value="<?= old('desconto', '0,00') ?>" data-mask="money">
                        </div>
                    </div>
                    
                    <div class="total-display">
                        <span>Valor Final</span>
                        <strong id="valor_final_display">R$ 0,00</strong>
                    </div>
                    
                    <hr>
                    
                    <div class="form-group">
                        <label for="forma_pagamento">Forma de Pagamento</label>
                        <select id="forma_pagamento" name="forma_pagamento" class="form-control">
                            <option value="">Selecione...</option>
                            <?php foreach ($formasPagamento as $value => $label): ?>
                                <option value="<?= $value ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_validade">Validade do Orçamento</label>
                        <input type="date" id="data_validade" name="data_validade" class="form-control" 
                               value="<?= old('data_validade', date('Y-m-d', strtotime('+15 days'))) ?>">
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?= base_url('orcamentos') ?>" class="btn btn-outline btn-block">Cancelar</a>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="bi bi-check"></i> Criar Orçamento
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php unset($_SESSION['errors'], $_SESSION['old']); ?>

