<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('estoque') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2>Entrada de Peças</h2>
            <p>Registre a entrada de peças no estoque</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('estoque/entrada') ?>" method="POST" class="form">
            <?= csrf_field() ?>
            
            <div class="form-section">
                <h4><i class="bi bi-box-arrow-in-down text-success"></i> Dados da Entrada</h4>
                
                <div class="form-grid">
                    <div class="form-group col-span-2">
                        <label>Peça <span class="required">*</span></label>
                        <div class="peca-search-container" data-pecas='<?= json_encode(array_map(function($p) { return ["id" => $p->id, "codigo" => $p->codigo, "nome" => $p->nome, "marca" => $p->marca_nome ?? "", "estoque" => $p->quantidade_estoque, "preco" => $p->preco_venda]; }, $pecas), JSON_UNESCAPED_UNICODE | JSON_HEX_APOS) ?>'>
                            <i class="bi bi-search client-search-icon"></i>
                            <input type="text" class="client-search-input peca-search-input" placeholder="Digite o código ou nome da peça..." autocomplete="off">
                            <input type="hidden" name="peca_id" id="peca_id" class="peca-search-value" required>
                            <div class="client-search-dropdown peca-search-dropdown"></div>
                        </div>
                        <small class="text-muted">Digite pelo menos 2 letras para buscar</small>
                        <?php if (isset($_SESSION['errors']['peca_id'])): ?>
                            <span class="form-error"><?= $_SESSION['errors']['peca_id'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantidade">Quantidade <span class="required">*</span></label>
                        <input type="number" id="quantidade" name="quantidade" class="form-control" 
                               min="1" value="<?= old('quantidade', 1) ?>" required>
                        <small class="text-muted peca-estoque-info"></small>
                        <?php if (isset($_SESSION['errors']['quantidade'])): ?>
                            <span class="form-error"><?= $_SESSION['errors']['quantidade'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="motivo">Motivo <span class="required">*</span></label>
                        <select id="motivo" name="motivo" class="form-control" required>
                            <option value="">Selecione...</option>
                            <option value="compra">Compra/Reposição</option>
                            <option value="devolucao">Devolução de Cliente</option>
                            <option value="ajuste">Ajuste de Estoque</option>
                        </select>
                        <?php if (isset($_SESSION['errors']['motivo'])): ?>
                            <span class="form-error"><?= $_SESSION['errors']['motivo'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group col-span-2">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes" class="form-control" rows="2" 
                                  placeholder="Detalhes da entrada (nota fiscal, fornecedor, etc)..."><?= old('observacoes') ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="<?= base_url('estoque') ?>" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-box-arrow-in-down"></i> Registrar Entrada
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.peca-search-container');
    const input = container.querySelector('.peca-search-input');
    const hiddenInput = container.querySelector('.peca-search-value');
    const dropdown = container.querySelector('.peca-search-dropdown');
    const pecasData = JSON.parse(container.dataset.pecas);
    const estoqueInfo = document.querySelector('.peca-estoque-info');
    
    input.addEventListener('input', function() {
        const term = input.value.toLowerCase().trim();
        
        if (term.length < 2) {
            dropdown.classList.remove('show');
            return;
        }
        
        const filtered = pecasData.filter(p => 
            p.nome.toLowerCase().includes(term) ||
            p.codigo.toLowerCase().includes(term) ||
            p.marca.toLowerCase().includes(term)
        ).slice(0, 10);
        
        if (filtered.length === 0) {
            dropdown.innerHTML = '<div class="client-search-empty"><i class="bi bi-search"></i> Nenhuma peça encontrada</div>';
        } else {
            dropdown.innerHTML = filtered.map(p => `
                <div class="client-search-item" data-id="${p.id}" data-nome="${p.nome}" data-estoque="${p.estoque}">
                    <div class="client-search-avatar"><i class="bi bi-box-seam"></i></div>
                    <div class="client-search-info">
                        <strong>${p.codigo} - ${p.nome}</strong>
                        <span>${p.marca} | Estoque atual: ${p.estoque}</span>
                    </div>
                </div>
            `).join('');
        }
        
        dropdown.classList.add('show');
        
        dropdown.querySelectorAll('.client-search-item').forEach(item => {
            item.addEventListener('click', function() {
                input.value = item.querySelector('strong').textContent;
                hiddenInput.value = item.dataset.id;
                estoqueInfo.textContent = `Estoque atual: ${item.dataset.estoque} unidades`;
                dropdown.classList.remove('show');
            });
        });
    });
    
    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
});
</script>

<?php unset($_SESSION['errors'], $_SESSION['old']); ?>

