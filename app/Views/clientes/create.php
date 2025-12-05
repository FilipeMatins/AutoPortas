<div class="page-header">
    <div class="page-header-left">
        <a href="<?= base_url('clientes') ?>" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2>Novo Cliente</h2>
            <p>Preencha os dados do cliente</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('clientes') ?>" method="POST" class="form">
            <?= csrf_field() ?>
            
            <div class="form-section">
                <h4>Dados Pessoais</h4>
                
                <div class="form-grid">
                    <div class="form-group col-span-2">
                        <label for="nome">Nome Completo <span class="required">*</span></label>
                        <input type="text" id="nome" name="nome" class="form-control" 
                               value="<?= old('nome') ?>" required>
                        <?php if (isset($_SESSION['errors']['nome'])): ?>
                            <span class="form-error"><?= $_SESSION['errors']['nome'][0] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?= old('email') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone">Telefone <span class="required">*</span></label>
                        <input type="text" id="telefone" name="telefone" class="form-control" 
                               value="<?= old('telefone') ?>" data-mask="phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cpf_cnpj">CPF/CNPJ</label>
                        <input type="text" id="cpf_cnpj" name="cpf_cnpj" class="form-control" 
                               value="<?= old('cpf_cnpj') ?>" data-mask="document">
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Endereço</h4>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" name="cep" class="form-control" 
                               value="<?= old('cep') ?>" data-mask="cep">
                    </div>
                    
                    <div class="form-group col-span-2">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco" class="form-control" 
                               value="<?= old('endereco') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade" class="form-control" 
                               value="<?= old('cidade') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" class="form-control">
                            <option value="">Selecione...</option>
                            <option value="AC" <?= old('estado') === 'AC' ? 'selected' : '' ?>>Acre</option>
                            <option value="AL" <?= old('estado') === 'AL' ? 'selected' : '' ?>>Alagoas</option>
                            <option value="AP" <?= old('estado') === 'AP' ? 'selected' : '' ?>>Amapá</option>
                            <option value="AM" <?= old('estado') === 'AM' ? 'selected' : '' ?>>Amazonas</option>
                            <option value="BA" <?= old('estado') === 'BA' ? 'selected' : '' ?>>Bahia</option>
                            <option value="CE" <?= old('estado') === 'CE' ? 'selected' : '' ?>>Ceará</option>
                            <option value="DF" <?= old('estado') === 'DF' ? 'selected' : '' ?>>Distrito Federal</option>
                            <option value="ES" <?= old('estado') === 'ES' ? 'selected' : '' ?>>Espírito Santo</option>
                            <option value="GO" <?= old('estado') === 'GO' ? 'selected' : '' ?>>Goiás</option>
                            <option value="MA" <?= old('estado') === 'MA' ? 'selected' : '' ?>>Maranhão</option>
                            <option value="MT" <?= old('estado') === 'MT' ? 'selected' : '' ?>>Mato Grosso</option>
                            <option value="MS" <?= old('estado') === 'MS' ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                            <option value="MG" <?= old('estado') === 'MG' ? 'selected' : '' ?>>Minas Gerais</option>
                            <option value="PA" <?= old('estado') === 'PA' ? 'selected' : '' ?>>Pará</option>
                            <option value="PB" <?= old('estado') === 'PB' ? 'selected' : '' ?>>Paraíba</option>
                            <option value="PR" <?= old('estado') === 'PR' ? 'selected' : '' ?>>Paraná</option>
                            <option value="PE" <?= old('estado') === 'PE' ? 'selected' : '' ?>>Pernambuco</option>
                            <option value="PI" <?= old('estado') === 'PI' ? 'selected' : '' ?>>Piauí</option>
                            <option value="RJ" <?= old('estado') === 'RJ' ? 'selected' : '' ?>>Rio de Janeiro</option>
                            <option value="RN" <?= old('estado') === 'RN' ? 'selected' : '' ?>>Rio Grande do Norte</option>
                            <option value="RS" <?= old('estado') === 'RS' ? 'selected' : '' ?>>Rio Grande do Sul</option>
                            <option value="RO" <?= old('estado') === 'RO' ? 'selected' : '' ?>>Rondônia</option>
                            <option value="RR" <?= old('estado') === 'RR' ? 'selected' : '' ?>>Roraima</option>
                            <option value="SC" <?= old('estado') === 'SC' ? 'selected' : '' ?>>Santa Catarina</option>
                            <option value="SP" <?= old('estado') === 'SP' ? 'selected' : '' ?>>São Paulo</option>
                            <option value="SE" <?= old('estado') === 'SE' ? 'selected' : '' ?>>Sergipe</option>
                            <option value="TO" <?= old('estado') === 'TO' ? 'selected' : '' ?>>Tocantins</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Observações</h4>
                
                <div class="form-group">
                    <label for="observacoes">Observações</label>
                    <textarea id="observacoes" name="observacoes" class="form-control" rows="4"><?= old('observacoes') ?></textarea>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="<?= base_url('clientes') ?>" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check"></i> Salvar Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<?php unset($_SESSION['errors'], $_SESSION['old']); ?>

