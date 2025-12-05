<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Auto Portas') ?> - Auto Portas</title>
    
    <!-- Google Fonts - Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- CSS Principal (modular) -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="bi bi-door-open-fill"></i>
                <span>Auto Portas</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul>
                <li class="<?= ($title ?? '') === 'Dashboard' ? 'active' : '' ?>">
                    <a href="<?= base_url('/') ?>">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="<?= str_contains($title ?? '', 'Cliente') ? 'active' : '' ?>">
                    <a href="<?= base_url('clientes') ?>">
                        <i class="bi bi-people"></i>
                        <span>Clientes</span>
                    </a>
                </li>
                <li class="<?= str_contains($title ?? '', 'Serviço') ? 'active' : '' ?>">
                    <a href="<?= base_url('servicos') ?>">
                        <i class="bi bi-wrench-adjustable"></i>
                        <span>Serviços</span>
                    </a>
                </li>
                <li class="<?= str_contains($title ?? '', 'Orçamento') ? 'active' : '' ?>">
                    <a href="<?= base_url('orcamentos') ?>">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Orçamentos</span>
                    </a>
                </li>
                
                <li class="nav-divider"></li>
                
                <li class="<?= str_contains($title ?? '', 'Peça') || str_contains($title ?? '', 'Marca') ? 'active' : '' ?>">
                    <a href="<?= base_url('pecas') ?>">
                        <i class="bi bi-box-seam"></i>
                        <span>Peças</span>
                    </a>
                </li>
                <li class="<?= str_contains($title ?? '', 'Estoque') || str_contains($title ?? '', 'Movimentaç') ? 'active' : '' ?>">
                    <a href="<?= base_url('estoque') ?>">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Estoque</span>
                    </a>
                </li>
                <li class="<?= str_contains($title ?? '', 'Conta') ? 'active' : '' ?>">
                    <a href="<?= base_url('contas') ?>">
                        <i class="bi bi-cash-stack"></i>
                        <span>Contas a Receber</span>
                    </a>
                </li>
            </ul>
        </nav>
        
<?php use Core\Auth; ?>
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="user-details">
                    <span class="user-name"><?= Auth::userName() ?></span>
                    <span class="user-role">Admin</span>
                </div>
                <a href="<?= base_url('logout') ?>" class="btn-logout" title="Sair">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="main-header">
            <div class="header-left">
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title"><?= e($title ?? 'Dashboard') ?></h1>
            </div>
            <?php
            // Carrega notificações
            $notificacaoController = new \App\Controllers\NotificacaoController();
            $notificacoes = $notificacaoController->getNotificacoes();
            $totalNotificacoes = count($notificacoes);
            ?>
            <div class="header-right">
                <div class="header-search">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Buscar...">
                </div>
                <div class="notification-dropdown">
                    <button class="notification-btn" id="notificationBtn">
                        <i class="bi bi-bell"></i>
                        <?php if ($totalNotificacoes > 0): ?>
                            <span class="notification-badge"><?= $totalNotificacoes > 9 ? '9+' : $totalNotificacoes ?></span>
                        <?php endif; ?>
                    </button>
                    <div class="notification-menu" id="notificationMenu">
                        <div class="notification-header">
                            <h4>Notificações</h4>
                            <?php if ($totalNotificacoes > 0): ?>
                                <button type="button" class="btn btn-sm btn-ghost" onclick="marcarTodasLidas()" title="Marcar todas como lidas">
                                    <i class="bi bi-check-all"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                        <div class="notification-body">
                            <?php if (!empty($notificacoes)): ?>
                                <?php foreach (array_slice($notificacoes, 0, 5) as $notif): ?>
                                    <div class="notification-item notification-<?= $notif['tipo'] ?>" data-hash="<?= $notif['hash'] ?>">
                                        <a href="<?= $notif['link'] ?>" class="notification-link" onclick="marcarLida('<?= $notif['hash'] ?>')">
                                            <div class="notification-icon">
                                                <i class="bi bi-<?= $notif['icone'] ?>"></i>
                                            </div>
                                            <div class="notification-info">
                                                <strong><?= e($notif['titulo']) ?></strong>
                                                <span><?= e($notif['mensagem']) ?></span>
                                            </div>
                                        </a>
                                        <div class="notification-actions">
                                            <button type="button" class="btn-notif-action" onclick="excluirNotificacao('<?= $notif['hash'] ?>', this)" title="Excluir">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="notification-empty">
                                    <i class="bi bi-check-circle"></i>
                                    <p>Nenhuma notificação</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($totalNotificacoes > 0): ?>
                            <div class="notification-footer">
                                <a href="<?= base_url('notificacoes') ?>">Ver todas (<?= $totalNotificacoes ?>)</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Content Area -->
        <div class="content-wrapper">
            <?php if (isset($_SESSION['flash'])): ?>
                <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                    <div class="alert alert-<?= $type ?>">
                        <i class="bi bi-<?= $type === 'success' ? 'check-circle' : ($type === 'error' ? 'x-circle' : 'info-circle') ?>"></i>
                        <?= e($message) ?>
                        <button class="alert-close">&times;</button>
                    </div>
                <?php endforeach; ?>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>
            
            <?= $content ?>
        </div>
    </main>
    
    <!-- Overlay para mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- JavaScript Modular -->
    <script>window.BASE_URL = '<?= rtrim(base_url(''), '/') ?>';</script>
    <script src="<?= asset('js/core/utils.js') ?>"></script>
    <script src="<?= asset('js/components/sidebar.js') ?>"></script>
    <script src="<?= asset('js/components/notifications.js') ?>"></script>
    <script src="<?= asset('js/components/alerts.js') ?>"></script>
    <script src="<?= asset('js/components/masks.js') ?>"></script>
    <script src="<?= asset('js/components/cep-search.js') ?>"></script>
    <script src="<?= asset('js/components/client-search.js') ?>"></script>
    <script src="<?= asset('js/pages/orcamentos.js') ?>"></script>
    <script src="<?= asset('js/pages/contas.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>

