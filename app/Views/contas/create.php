<?php use App\Models\Orcamento; $formasPagamento = Orcamento::getFormasPagamento(); ?>

<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('contas') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2>Nova Conta a Receber</h2>
            <p>Cadastre uma nova conta/cobrança</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('contas') ?>" method="POST" class="form">
            <?= csrf_field() ?>
            
            <!-- Vincular Orçamento -->
            <div class="form-section">
                <h4><i class="bi bi-link-45deg"></i> Vincular a Orçamento (opcional)</h4>
                <div class="form-group">
                    <label for="orcamento_id">Selecione um orçamento para puxar os dados</label>
                    <select id="orcamento_id" name="orcamento_id" class="form-control" onchange="preencherDadosOrcamento()">
                        <option value="">-- Criar cobrança avulsa --</option>
                        <?php if (!empty($orcamentos)): ?>
                            <?php foreach ($orcamentos as $orc): ?>
                                <option value="<?= $orc->id ?>" 
                                        data-cliente="<?= $orc->cliente_id ?>"
                                        data-descricao="Orçamento #<?= $orc->id ?> - <?= e(substr($orc->descricao, 0, 50)) ?>"
                                        data-valor="<?= $orc->valor_final ?>">
                                    #<?= $orc->id ?> - <?= e($orc->cliente_nome) ?> - <?= money($orc->valor_final) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (empty($orcamentos)): ?>
                        <small class="text-warning"><i class="bi bi-info-circle"></i> Não há orçamentos aprovados disponíveis para vincular. Aprove um orçamento primeiro ou crie uma cobrança avulsa.</small>
                    <?php else: ?>
                        <small class="text-muted">Ao selecionar, os campos serão preenchidos automaticamente</small>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Informações da Conta</h4>
                <div class="form-grid">
                    <div class="form-group col-span-2">
                        <label>Cliente <span class="required">*</span></label>
                        <div class="client-search-container" data-clientes='<?= json_encode(array_map(function($c) { return ["id" => $c->id, "nome" => $c->nome, "telefone" => format_phone($c->telefone)]; }, $clientes)) ?>'>
                            <i class="bi bi-search client-search-icon"></i>
                            <input type="text" class="client-search-input" placeholder="Digite o nome do cliente..." autocomplete="off">
                            <input type="hidden" name="cliente_id" id="cliente_id" class="client-search-value" required>
                            <div class="client-search-dropdown"></div>
                        </div>
                        <small class="text-muted">Digite pelo menos 2 letras para buscar</small>
                    </div>
                    
                    <div class="form-group col-span-3">
                        <label for="descricao">Descrição <span class="required">*</span></label>
                        <input type="text" id="descricao" name="descricao" class="form-control" 
                               value="<?= old('descricao') ?>" placeholder="Ex: Serviço de manutenção portão" required>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Valores</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="valor_total">Valor Total <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-prefix">R$</span>
                            <input type="text" id="valor_total" name="valor_total" class="form-control" 
                                   value="<?= old('valor_total', '0,00') ?>" data-mask="money" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="valor_pago">Valor já Pago</label>
                        <div class="input-group">
                            <span class="input-prefix">R$</span>
                            <input type="text" id="valor_pago" name="valor_pago" class="form-control" 
                                   value="<?= old('valor_pago', '0,00') ?>" data-mask="money">
                        </div>
                        <small class="text-muted">Se o cliente já pagou uma parte, informe aqui</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="prazo_vencimento">Prazo para pagamento <span class="required">*</span></label>
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
                        <input type="date" id="data_vencimento" name="data_vencimento" class="form-control" 
                               value="<?= old('data_vencimento', date('Y-m-d', strtotime('+7 days'))) ?>" 
                               style="display: none; margin-top: 8px;" required>
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
                </div>
            </div>
            
            <div class="form-section">
                <h4>Observações</h4>
                <div class="form-group">
                    <textarea id="observacoes" name="observacoes" class="form-control" rows="3" 
                              placeholder="Anotações sobre o pagamento..."><?= old('observacoes') ?></textarea>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="<?= base_url('contas') ?>" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check"></i> Salvar Conta
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function preencherDadosOrcamento() {
    const select = document.getElementById('orcamento_id');
    const option = select.options[select.selectedIndex];
    const clienteInput = document.querySelector('.client-search-input');
    const clienteHidden = document.getElementById('cliente_id');
    
    if (option.value) {
        // Preenche os campos com os dados do orçamento
        clienteHidden.value = option.dataset.cliente;
        
        // Busca o nome do cliente no data
        const container = document.querySelector('.client-search-container');
        const clientes = JSON.parse(container.dataset.clientes);
        const cliente = clientes.find(c => c.id == option.dataset.cliente);
        if (cliente) {
            clienteInput.value = cliente.nome;
        }
        
        document.getElementById('descricao').value = option.dataset.descricao;
        
        // Formata o valor
        const valor = parseFloat(option.dataset.valor);
        document.getElementById('valor_total').value = valor.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    } else {
        // Limpa os campos se desmarcar
        clienteInput.value = '';
        clienteHidden.value = '';
        document.getElementById('descricao').value = '';
        document.getElementById('valor_total').value = '0,00';
    }
}
</script>

<?php unset($_SESSION['errors'], $_SESSION['old']); ?>
