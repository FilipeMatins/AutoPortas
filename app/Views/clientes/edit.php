<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('clientes') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2>Editar Cliente</h2>
            <p>Atualize os dados do cliente</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url("clientes/{$cliente->id}") ?>" method="POST" class="form">
            <?= csrf_field() ?>
            
            <div class="form-section">
                <h4>Dados Pessoais</h4>
                
                <div class="form-grid">
                    <div class="form-group col-span-2">
                        <label for="nome">Nome Completo <span class="required">*</span></label>
                        <input type="text" id="nome" name="nome" class="form-control" 
                               value="<?= e($cliente->nome) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?= e($cliente->email ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone">Telefone <span class="required">*</span></label>
                        <input type="text" id="telefone" name="telefone" class="form-control" 
                               value="<?= e($cliente->telefone ?? '') ?>" data-mask="phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cpf_cnpj">CPF/CNPJ</label>
                        <input type="text" id="cpf_cnpj" name="cpf_cnpj" class="form-control" 
                               value="<?= e($cliente->cpf_cnpj ?? '') ?>" data-mask="document">
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Endereço</h4>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" name="cep" class="form-control" 
                               value="<?= e($cliente->cep ?? '') ?>" data-mask="cep">
                    </div>
                    
                    <div class="form-group col-span-2">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco" class="form-control" 
                               value="<?= e($cliente->endereco ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade" class="form-control" 
                               value="<?= e($cliente->cidade ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" class="form-control">
                            <option value="">Selecione...</option>
                            <?php
                            $estados = ['AC'=>'Acre','AL'=>'Alagoas','AP'=>'Amapá','AM'=>'Amazonas','BA'=>'Bahia','CE'=>'Ceará','DF'=>'Distrito Federal','ES'=>'Espírito Santo','GO'=>'Goiás','MA'=>'Maranhão','MT'=>'Mato Grosso','MS'=>'Mato Grosso do Sul','MG'=>'Minas Gerais','PA'=>'Pará','PB'=>'Paraíba','PR'=>'Paraná','PE'=>'Pernambuco','PI'=>'Piauí','RJ'=>'Rio de Janeiro','RN'=>'Rio Grande do Norte','RS'=>'Rio Grande do Sul','RO'=>'Rondônia','RR'=>'Roraima','SC'=>'Santa Catarina','SP'=>'São Paulo','SE'=>'Sergipe','TO'=>'Tocantins'];
                            foreach ($estados as $sigla => $nome): ?>
                                <option value="<?= $sigla ?>" <?= ($cliente->estado ?? '') === $sigla ? 'selected' : '' ?>><?= $nome ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Observações</h4>
                
                <div class="form-group">
                    <label for="observacoes">Observações</label>
                    <textarea id="observacoes" name="observacoes" class="form-control" rows="4"><?= e($cliente->observacoes ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="<?= base_url('clientes') ?>" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

