/**
 * Auto Portas - Máscaras de Input
 * Formatação automática de campos
 */

const Masks = {
    init() {
        this.applyAll();
    },
    
    applyAll() {
        document.querySelectorAll('[data-mask="phone"]').forEach(el => this.phone(el));
        document.querySelectorAll('[data-mask="cpf"]').forEach(el => this.cpf(el));
        document.querySelectorAll('[data-mask="cnpj"]').forEach(el => this.cnpj(el));
        document.querySelectorAll('[data-mask="document"]').forEach(el => this.document(el));
        document.querySelectorAll('[data-mask="cep"]').forEach(el => this.cep(el));
        document.querySelectorAll('[data-mask="money"]').forEach(el => this.money(el));
    },
    
    phone(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            } else {
                value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
            }
            
            e.target.value = value;
        });
    },
    
    cpf(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
            e.target.value = value;
        });
    },
    
    cnpj(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2})/, '$1.$2.$3/$4-$5');
            e.target.value = value;
        });
    },
    
    document(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
            } else {
                value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2})/, '$1.$2.$3/$4-$5');
            }
            
            e.target.value = value;
        });
    },
    
    cep(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d{0,3})/, '$1-$2');
            e.target.value = value;
        });
    },
    
    money(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = (parseInt(value) / 100).toFixed(2);
            value = value.replace('.', ',');
            value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            
            if (value === 'NaN') value = '0,00';
            
            e.target.value = value;
        });
    }
};

// Exporta para uso global
window.Masks = Masks;

