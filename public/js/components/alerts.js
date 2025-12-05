/**
 * Auto Portas - Alertas
 * Controle de mensagens e notificações na tela
 */

const Alerts = {
    init() {
        this.bindCloseButtons();
        this.autoDismiss();
    },
    
    bindCloseButtons() {
        const closeButtons = document.querySelectorAll('.alert-close');
        
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('.alert');
                Alerts.dismiss(alert);
            });
        });
    },
    
    autoDismiss(delay = 5000) {
        const alerts = document.querySelectorAll('.alert');
        
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert.parentNode) {
                    this.dismiss(alert);
                }
            }, delay);
        });
    },
    
    dismiss(alertElement) {
        alertElement.style.animation = 'slideOut 0.3s ease forwards';
        
        setTimeout(() => {
            if (alertElement.parentNode) {
                alertElement.remove();
            }
        }, 300);
    },
    
    show(message, type = 'info', container = '.content-wrapper') {
        const alertHtml = `
            <div class="alert alert-${type}">
                <i class="bi bi-${this.getIcon(type)}"></i>
                ${message}
                <button class="alert-close">&times;</button>
            </div>
        `;
        
        const containerElement = document.querySelector(container);
        if (containerElement) {
            containerElement.insertAdjacentHTML('afterbegin', alertHtml);
            this.init(); // Re-bind events
        }
    },
    
    getIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'x-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
};

// Exporta para uso global
window.Alerts = Alerts;

