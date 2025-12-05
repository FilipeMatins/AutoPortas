<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('marcas') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2>Editar Marca</h2>
            <p>Atualize os dados da marca</p>
        </div>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="<?= base_url("marcas/{$marca->id}") ?>" method="POST" class="form">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="nome">Nome da Marca <span class="required">*</span></label>
                <input type="text" id="nome" name="nome" class="form-control" 
                       value="<?= e($marca->nome) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-control" rows="3"><?= e($marca->descricao ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="ativo" <?= $marca->status === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="inativo" <?= $marca->status === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>
            
            <div class="form-actions">
                <a href="<?= base_url('marcas') ?>" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>

