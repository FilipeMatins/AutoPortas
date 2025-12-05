/**
 * Auto Portas - Busca CEP
 * Preenchimento automático de endereço via ViaCEP
 */

const CepSearch = {
    init() {
        const cepInput = document.getElementById('cep');
        
        if (cepInput) {
            cepInput.addEventListener('blur', () => this.search(cepInput));
        }
    },
    
    async search(input) {
        const cep = input.value.replace(/\D/g, '');
        
        if (cep.length !== 8) return;
        
        try {
            const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data = await response.json();
            
            if (!data.erro) {
                this.fillFields(data);
            }
        } catch (error) {
            console.log('Erro ao buscar CEP:', error);
        }
    },
    
    fillFields(data) {
        const fields = {
            endereco: data.logradouro,
            cidade: data.localidade,
            estado: data.uf
        };
        
        for (const [id, value] of Object.entries(fields)) {
            const input = document.getElementById(id);
            if (input && value) {
                input.value = value;
            }
        }
    }
};

// Exporta para uso global
window.CepSearch = CepSearch;

