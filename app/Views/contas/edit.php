<?php use App\Models\Orcamento; $formasPagamento = Orcamento::getFormasPagamento(); ?>

<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('contas') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2>Editar Conta #<?= $conta->id ?></h2>
            <p>Atualize os dados da conta</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url("contas/{$conta->id}") ?>" method="POST" class="form">
            <?= csrf_field() ?>
            
            <?php 
            // Busca cliente atual da conta
            $clienteAtual = null;
            foreach ($clientes as $c) {
                if ($c->id == $conta->cliente_id) {
                    $clienteAtual = $c;
                    break;
                }
            }
            ?>
            <div class="form-section">
                <h4>Informações da Conta</h4>
                <div class="form-grid">
                    <div class="form-group col-span-2">
                        <label>Cliente <span class="required">*</span></label>
                        <div class="client-search-container" data-clientes='<?= json_encode(array_map(function($c) { return ["id" => $c->id, "nome" => $c->nome, "telefone" => format_phone($c->telefone)]; }, $clientes)) ?>'>
                            <i class="bi bi-search client-search-icon"></i>
                            <input type="text" class="client-search-input" placeholder="Digite o nome do cliente..." autocomplete="off" value="<?= $clienteAtual ? e($clienteAtual->nome) : '' ?>">
                            <input type="hidden" name="cliente_id" id="cliente_id" class="client-search-value" value="<?= $conta->cliente_id ?>" required>
                            <div class="client-search-dropdown"></div>
                        </div>
                        <small class="text-muted">Digite pelo menos 2 letras para buscar</small>
                    </div>
                    
                    <div class="form-group col-span-3">
                        <label for="descricao">Descrição <span class="required">*</span></label>
                        <input type="text" id="descricao" name="descricao" class="form-control" 
                               value="<?= e($conta->descricao) ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Informações Adicionais</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Valor Total</label>
                        <div class="input-group">
                            <span class="input-prefix">R$</span>
                            <input type="text" class="form-control" value="<?= number_format($conta->valor_total, 2, ',', '.') ?>" disabled>
                        </div>
                        <small class="text-muted">Para alterar valores, exclua e crie novamente</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="data_vencimento">Data de Vencimento <span class="required">*</span></label>
                        <input type="date" id="data_vencimento" name="data_vencimento" class="form-control" 
                               value="<?= $conta->data_vencimento ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="forma_pagamento">Forma de Pagamento</label>
                        <select id="forma_pagamento" name="forma_pagamento" class="form-control">
                            <option value="">Selecione...</option>
                            <?php foreach ($formasPagamento as $value => $label): ?>
                                <option value="<?= $value ?>" <?= ($conta->forma_pagamento ?? '') === $value ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Observações</h4>
                <div class="form-group">
                    <textarea id="observacoes" name="observacoes" class="form-control" rows="3"><?= e($conta->observacoes ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="<?= base_url('contas') ?>" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

