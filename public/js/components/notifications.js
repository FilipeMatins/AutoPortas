/**
 * Auto Portas - Notificações
 * Controle do dropdown de notificações
 */

const Notifications = {
    elements: {
        btn: null,
        menu: null
    },
    
    init() {
        this.elements.btn = document.getElementById('notificationBtn');
        this.elements.menu = document.getElementById('notificationMenu');
        
        if (this.elements.btn && this.elements.menu) {
            this.bindEvents();
        }
    },
    
    bindEvents() {
        // Toggle ao clicar no botão
        this.elements.btn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggle();
        });
        
        // Fecha ao clicar fora
        document.addEventListener('click', (e) => {
            if (!this.elements.menu.contains(e.target) && e.target !== this.elements.btn) {
                this.close();
            }
        });
        
        // Fecha ao pressionar ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.close();
            }
        });
    },
    
    toggle() {
        this.elements.menu.classList.toggle('show');
    },
    
    open() {
        this.elements.menu.classList.add('show');
    },
    
    close() {
        this.elements.menu.classList.remove('show');
    }
};

// ========================================
// FUNÇÕES GLOBAIS PARA NOTIFICAÇÕES
// ========================================

function marcarLida(hash) {
    // Apenas marca como lida, não bloqueia navegação
    if (hash) {
        fetch(window.BASE_URL + '/notificacoes/lida', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'hash=' + encodeURIComponent(hash)
        }).catch(err => console.log('Erro ao marcar lida:', err));
    }
}

function marcarTodasLidas() {
    fetch(window.BASE_URL + '/notificacoes/todas-lidas', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualiza a página para refletir as mudanças
            location.reload();
        }
    });
}

function excluirNotificacao(hash, btn) {
    event.preventDefault();
    event.stopPropagation();
    
    fetch(window.BASE_URL + '/notificacoes/excluir', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'hash=' + encodeURIComponent(hash)
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove o item da lista
            const item = btn.closest('.notification-item');
            item.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => {
                item.remove();
                
                // Atualiza o contador
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    const count = parseInt(badge.textContent) - 1;
                    if (count <= 0) {
                        badge.remove();
                        // Se não tem mais notificações, mostra mensagem vazia
                        const body = document.querySelector('.notification-body');
                        body.innerHTML = '<div class="notification-empty"><i class="bi bi-check-circle"></i><p>Nenhuma notificação</p></div>';
                    } else {
                        badge.textContent = count > 9 ? '9+' : count;
                    }
                }
            }, 300);
        }
    });
}

// Exporta para uso global
window.Notifications = Notifications;
window.marcarLida = marcarLida;
window.marcarTodasLidas = marcarTodasLidas;
window.excluirNotificacao = excluirNotificacao;

