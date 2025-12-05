<div class="page-header">
    <div class="page-header-left">
        <div>
            <h2>Movimentações de Estoque</h2>
            <p>Histórico de entradas e saídas de peças</p>
        </div>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('estoque/entrada') ?>" class="btn btn-success">
            <i class="bi bi-box-arrow-in-down"></i> Entrada
        </a>
        <a href="<?= base_url('estoque/saida') ?>" class="btn btn-danger">
            <i class="bi bi-box-arrow-up"></i> Saída
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($movimentacoes)): ?>
            <div class="empty-state">
                <i class="bi bi-arrow-left-right"></i>
                <h3>Nenhuma movimentação</h3>
                <p>Ainda não há movimentações de estoque registradas.</p>
                <a href="<?= base_url('estoque/entrada') ?>" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Registrar Entrada
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Tipo</th>
                            <th>Peça</th>
                            <th>Qtd</th>
                            <th>Motivo</th>
                            <th>Orçamento</th>
                            <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movimentacoes as $mov): ?>
                            <tr>
                                <td>
                                    <span class="text-secondary"><?= date('d/m/Y H:i', strtotime($mov->created_at)) ?></span>
                                </td>
                                <td>
                                    <?php if ($mov->tipo === 'entrada'): ?>
                                        <span class="badge badge-success">
                                            <i class="bi bi-arrow-down"></i> Entrada
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">
                                            <i class="bi bi-arrow-up"></i> Saída
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= e($mov->peca_nome) ?></strong>
                                    <br>
                                    <small class="text-secondary"><?= e($mov->peca_codigo) ?> - <?= e($mov->marca_nome) ?></small>
                                </td>
                                <td>
                                    <span class="<?= $mov->tipo === 'entrada' ? 'text-success' : 'text-danger' ?>">
                                        <?= $mov->tipo === 'entrada' ? '+' : '-' ?><?= $mov->quantidade ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $motivos = \App\Models\MovimentacaoEstoque::getMotivos();
                                    echo $motivos[$mov->motivo] ?? $mov->motivo;
                                    ?>
                                </td>
                                <td>
                                    <?php if ($mov->orcamento_id): ?>
                                        <a href="<?= base_url("orcamentos/{$mov->orcamento_id}") ?>" class="text-primary">
                                            #<?= $mov->orcamento_numero ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= e($mov->observacoes) ?: '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

