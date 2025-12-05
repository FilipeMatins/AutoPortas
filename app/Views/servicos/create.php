<?php
use App\Models\Servico;
$categorias = Servico::getCategorias();
?>

<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('servicos') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2>Novo Serviço</h2>
            <p>Cadastre um novo serviço</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('servicos') ?>" method="POST" class="form">
            <?= csrf_field() ?>
            
            <div class="form-section">
                <h4>Informações do Serviço</h4>
                
                <div class="form-grid">
                    <div class="form-group col-span-2">
                        <label for="nome">Nome do Serviço <span class="required">*</span></label>
                        <input type="text" id="nome" name="nome" class="form-control" 
                               value="<?= old('nome') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="categoria">Categoria</label>
                        <select id="categoria" name="categoria" class="form-control">
                            <option value="">Selecione...</option>
                            <?php foreach ($categorias as $value => $label): ?>
                                <option value="<?= $value ?>" <?= old('categoria') === $value ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="preco">Preço Base <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-prefix">R$</span>
                            <input type="text" id="preco" name="preco" class="form-control" 
                                   value="<?= old('preco') ?>" data-mask="money" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tempo_estimado">Tempo Estimado</label>
                        <input type="text" id="tempo_estimado" name="tempo_estimado" class="form-control" 
                               value="<?= old('tempo_estimado') ?>" placeholder="Ex: 2 horas">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="ativo" <?= old('status', 'ativo') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                            <option value="inativo" <?= old('status') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                        </select>
                    </div>
                    
                    <div class="form-group col-span-3">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" class="form-control" rows="4"><?= old('descricao') ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="<?= base_url('servicos') ?>" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check"></i> Salvar Serviço
                </button>
            </div>
        </form>
    </div>
</div>

<?php unset($_SESSION['errors'], $_SESSION['old']); ?>

