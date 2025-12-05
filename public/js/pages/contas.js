/**
 * Auto Portas - Contas a Receber
 * Scripts específicos da página de cobranças
 */

const ContasPage = {
    init() {
        this.initPrazoVencimento();
    },
    
    initPrazoVencimento() {
        const prazoSelect = document.getElementById('prazo_vencimento');
        const dataInput = document.getElementById('data_vencimento');
        
        if (prazoSelect && dataInput) {
            prazoSelect.addEventListener('change', () => {
                this.atualizarDataVencimento(prazoSelect, dataInput);
            });
        }
    },
    
    atualizarDataVencimento(prazoSelect, dataInput) {
        const prazo = prazoSelect.value;
        
        if (prazo === 'custom') {
            dataInput.style.display = 'block';
        } else {
            dataInput.style.display = 'none';
            const data = Utils.addDays(new Date(), parseInt(prazo));
            dataInput.value = Utils.toInputDate(data);
        }
    }
};

// Função global para uso inline (compatibilidade)
function atualizarDataVencimento() {
    const prazoSelect = document.getElementById('prazo_vencimento');
    const dataInput = document.getElementById('data_vencimento');
    
    if (prazoSelect && dataInput) {
        ContasPage.atualizarDataVencimento(prazoSelect, dataInput);
    }
}

// Exporta para uso global
window.ContasPage = ContasPage;

