<div class="page-header">
    <div class="page-header-left">
        <h2>Lista de Clientes</h2>
        <p>Gerencie todos os clientes cadastrados</p>
    </div>
    <div class="page-header-right">
        <a href="<?= base_url('clientes/novo') ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> Novo Cliente
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($clientes['data'])): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Cidade</th>
                            <th>Cadastro</th>
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes['data'] as $cliente): ?>
                            <tr>
                                <td>
                                    <div class="table-user">
                                        <div class="table-avatar"><?= strtoupper(substr($cliente->nome, 0, 1)) ?></div>
                                        <strong><?= e($cliente->nome) ?></strong>
                                    </div>
                                </td>
                                <td><?= format_phone($cliente->telefone) ?></td>
                                <td><?= e($cliente->email ?: '-') ?></td>
                                <td><?= e($cliente->cidade ?: '-') ?></td>
                                <td><?= format_date($cliente->created_at) ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="<?= base_url("clientes/{$cliente->id}") ?>" class="btn btn-icon btn-ghost" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url("clientes/{$cliente->id}/editar") ?>" class="btn btn-icon btn-ghost" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= base_url("clientes/{$cliente->id}/excluir") ?>" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este cliente?')">
                                            <button type="submit" class="btn btn-icon btn-ghost text-danger" title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($clientes['total_pages'] > 1): ?>
                <div class="pagination">
                    <?php if ($clientes['has_prev']): ?>
                        <a href="?page=<?= $clientes['current_page'] - 1 ?>" class="btn btn-sm btn-outline">
                            <i class="bi bi-chevron-left"></i> Anterior
                        </a>
                    <?php endif; ?>
                    
                    <span class="pagination-info">
                        Página <?= $clientes['current_page'] ?> de <?= $clientes['total_pages'] ?>
                    </span>
                    
                    <?php if ($clientes['has_next']): ?>
                        <a href="?page=<?= $clientes['current_page'] + 1 ?>" class="btn btn-sm btn-outline">
                            Próxima <i class="bi bi-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h4>Nenhum cliente cadastrado</h4>
                <p>Comece adicionando seu primeiro cliente</p>
                <a href="<?= base_url('clientes/novo') ?>" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Novo Cliente
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

