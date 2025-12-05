<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('pecas') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2>Editar Peça</h2>
            <p>Atualize os dados da peça</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url("pecas/{$peca->id}") ?>" method="POST" class="form">
            <?= csrf_field() ?>
            
            <div class="form-section">
                <h4>Informações da Peça</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="codigo">Código</label>
                        <input type="text" id="codigo" name="codigo" class="form-control" 
                               value="<?= e($peca->codigo ?? '') ?>">
                    </div>
                    
                    <div class="form-group col-span-2">
                        <label for="nome">Nome da Peça <span class="required">*</span></label>
                        <input type="text" id="nome" name="nome" class="form-control" 
                               value="<?= e($peca->nome) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="marca_id">Marca</label>
                        <select id="marca_id" name="marca_id" class="form-control">
                            <option value="">Selecione...</option>
                            <?php foreach ($marcas as $marca): ?>
                                <option value="<?= $marca->id ?>" <?= ($peca->marca_id ?? '') == $marca->id ? 'selected' : '' ?>>
                                    <?= e($marca->nome) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group col-span-2">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" class="form-control" rows="2"><?= e($peca->descricao ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Preços e Estoque</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="preco_custo">Preço de Custo</label>
                        <div class="input-group">
                            <span class="input-prefix">R$</span>
                            <input type="text" id="preco_custo" name="preco_custo" class="form-control" 
                                   value="<?= number_format($peca->preco_custo ?? 0, 2, ',', '.') ?>" data-mask="money">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="preco_venda">Preço de Venda <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-prefix">R$</span>
                            <input type="text" id="preco_venda" name="preco_venda" class="form-control" 
                                   value="<?= number_format($peca->preco_venda ?? 0, 2, ',', '.') ?>" data-mask="money" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantidade_estoque">Quantidade em Estoque</label>
                        <input type="number" id="quantidade_estoque" name="quantidade_estoque" class="form-control" 
                               value="<?= $peca->quantidade_estoque ?? 0 ?>" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="estoque_minimo">Estoque Mínimo</label>
                        <input type="number" id="estoque_minimo" name="estoque_minimo" class="form-control" 
                               value="<?= $peca->estoque_minimo ?? 5 ?>" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="localizacao">Localização</label>
                        <input type="text" id="localizacao" name="localizacao" class="form-control" 
                               value="<?= e($peca->localizacao ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="ativo" <?= ($peca->status ?? '') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                            <option value="inativo" <?= ($peca->status ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="<?= base_url('pecas') ?>" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

