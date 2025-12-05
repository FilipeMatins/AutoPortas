/**
 * Auto Portas - Sistema de Gestão
 * Arquivo principal JavaScript
 * 
 * ESTRUTURA:
 * ├── core/         - Utilitários e funções base
 * ├── components/   - Componentes reutilizáveis
 * └── pages/        - Scripts específicos de páginas
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('App.js carregado!');
    
    // ========================================
    // INICIALIZAÇÃO DOS COMPONENTES
    // ========================================
    
    // Sidebar (menu lateral)
    if (typeof Sidebar !== 'undefined') {
        Sidebar.init();
    }
    
    // Notificações (dropdown)
    if (typeof Notifications !== 'undefined') {
        Notifications.init();
    }
    
    // Alertas (mensagens flash)
    if (typeof Alerts !== 'undefined') {
        Alerts.init();
    }
    
    // Máscaras de input
    if (typeof Masks !== 'undefined') {
        Masks.init();
    }
    
    // Busca de CEP
    if (typeof CepSearch !== 'undefined') {
        CepSearch.init();
    }
    
    // Busca de Cliente (autocomplete)
    if (typeof ClientSearch !== 'undefined') {
        ClientSearch.init();
    }
    
    // ========================================
    // INICIALIZAÇÃO DAS PÁGINAS
    // ========================================
    
    // Página de Orçamentos
    console.log('OrcamentosPage existe?', typeof OrcamentosPage);
    if (typeof OrcamentosPage !== 'undefined') {
        console.log('Iniciando OrcamentosPage...');
        OrcamentosPage.init();
    }
    
    // Página de Contas
    if (typeof ContasPage !== 'undefined') {
        ContasPage.init();
    }
    
    // ========================================
    // CONFIRMAÇÃO DE EXCLUSÃO
    // ========================================
    document.querySelectorAll('[data-confirm]').forEach(element => {
        element.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
});
