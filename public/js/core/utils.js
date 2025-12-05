/**
 * Auto Portas - Utilitários
 * Funções auxiliares JavaScript
 */

const Utils = {
    /**
     * Formata valor para moeda brasileira
     */
    formatMoney(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    },
    
    /**
     * Converte string de moeda para número
     */
    parseMoney(value) {
        if (typeof value !== 'string') return value;
        return parseFloat(value.replace(/\./g, '').replace(',', '.')) || 0;
    },
    
    /**
     * Formata data para exibição
     */
    formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR');
    },
    
    /**
     * Adiciona dias a uma data
     */
    addDays(date, days) {
        const result = new Date(date);
        result.setDate(result.getDate() + days);
        return result;
    },
    
    /**
     * Formata data para input date
     */
    toInputDate(date) {
        return date.toISOString().split('T')[0];
    },
    
    /**
     * Debounce function
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Exporta para uso global
window.Utils = Utils;

