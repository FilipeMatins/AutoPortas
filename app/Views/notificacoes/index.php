<div class="page-header">
    <div class="page-header-left">
        <h2>Notificações</h2>
        <p>Alertas e lembretes do sistema</p>
    </div>
    <?php if (!empty($notificacoes)): ?>
    <div class="page-header-actions">
        <form action="<?= base_url('notificacoes/todas-lidas') ?>" method="POST" style="display: inline;">
            <button type="submit" class="btn btn-outline">
                <i class="bi bi-check-all"></i> Marcar todas como lidas
            </button>
        </form>
    </div>
    <?php endif; ?>
</div>

<?php if (!empty($notificacoes)): ?>
    <div class="notificacoes-list">
        <?php foreach ($notificacoes as $notificacao): ?>
            <?php $isLida = in_array($notificacao['hash'], $lidas ?? []); ?>
            <div class="notificacao-card notificacao-<?= $notificacao['tipo'] ?> <?= $isLida ? 'notificacao-lida' : '' ?>" data-hash="<?= $notificacao['hash'] ?>">
                <a href="<?= $notificacao['link'] ?>" class="notificacao-link" onclick="marcarLida('<?= $notificacao['hash'] ?>')">
                    <div class="notificacao-icon">
                        <i class="bi bi-<?= $notificacao['icone'] ?>"></i>
                    </div>
                    <div class="notificacao-content">
                        <h4><?= e($notificacao['titulo']) ?></h4>
                        <p><?= e($notificacao['mensagem']) ?></p>
                        <?php if ($isLida): ?>
                            <span class="notificacao-status"><i class="bi bi-check"></i> Lida</span>
                        <?php endif; ?>
                    </div>
                </a>
                <div class="notificacao-actions">
                    <?php if (!$isLida): ?>
                    <button type="button" class="btn btn-sm btn-ghost" onclick="marcarLidaReload('<?= $notificacao['hash'] ?>')" title="Marcar como lida">
                        <i class="bi bi-check"></i>
                    </button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-sm btn-ghost text-danger" onclick="excluirNotificacaoPage('<?= $notificacao['hash'] ?>', this)" title="Excluir">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-bell"></i>
                <h4>Nenhuma notificação</h4>
                <p>Você está em dia! Não há alertas pendentes.</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
function marcarLidaReload(hash) {
    fetch(window.BASE_URL + '/notificacoes/lida', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'hash=' + encodeURIComponent(hash)
    }).then(() => location.reload());
}

function excluirNotificacaoPage(hash, btn) {
    if (!confirm('Excluir esta notificação?')) return;
    
    fetch(window.BASE_URL + '/notificacoes/excluir', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'hash=' + encodeURIComponent(hash)
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            const card = btn.closest('.notificacao-card');
            card.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => card.remove(), 300);
        }
    });
}
</script>

<style>
.notificacoes-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.notificacao-card {
    display: flex;
    align-items: center;
    gap: var(--spacing-lg);
    padding: var(--spacing-lg);
    background: var(--bg-secondary);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-color);
    transition: all var(--transition-fast);
}

.notificacao-card:hover {
    box-shadow: var(--shadow-md);
}

.notificacao-card.notificacao-lida {
    opacity: 0.6;
}

.notificacao-link {
    display: flex;
    align-items: center;
    gap: var(--spacing-lg);
    flex: 1;
    text-decoration: none;
    color: inherit;
}

.notificacao-link:hover {
    color: inherit;
}

.notificacao-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.notificacao-danger .notificacao-icon {
    background: #fee2e2;
    color: #dc2626;
}

.notificacao-warning .notificacao-icon {
    background: #fef3c7;
    color: #d97706;
}

.notificacao-info .notificacao-icon {
    background: #dbeafe;
    color: #2563eb;
}

.notificacao-success .notificacao-icon {
    background: #d1fae5;
    color: #059669;
}

.notificacao-content {
    flex: 1;
}

.notificacao-content h4 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 4px;
}

.notificacao-content p {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 0;
}

.notificacao-status {
    display: inline-block;
    font-size: 0.75rem;
    color: var(--success);
    margin-top: 4px;
}

.notificacao-actions {
    display: flex;
    gap: var(--spacing-sm);
}

.notificacao-danger {
    border-left: 4px solid #dc2626;
}

.notificacao-warning {
    border-left: 4px solid #d97706;
}

.notificacao-info {
    border-left: 4px solid #2563eb;
}

@keyframes fadeOut {
    from { opacity: 1; transform: translateX(0); }
    to { opacity: 0; transform: translateX(50px); }
}
</style>
