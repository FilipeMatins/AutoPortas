/**
 * Auto Portas - Orçamentos
 * Scripts específicos da página de orçamentos
 */

const OrcamentosPage = {
    elements: {
        servicoChecks: null,
        valorTotalInput: null,
        descontoInput: null,
        valorFinalDisplay: null,
        pecasContainer: null,
        pecasEmpty: null
    },
    
    pecasSelecionadas: [],
    
    init() {
        this.elements.servicoChecks = document.querySelectorAll('.servico-check');
        this.elements.valorTotalInput = document.getElementById('valor_total');
        this.elements.descontoInput = document.getElementById('desconto');
        this.elements.valorFinalDisplay = document.getElementById('valor_final_display');
        this.elements.pecasContainer = document.getElementById('pecas-selecionadas');
        this.elements.pecasEmpty = document.getElementById('pecas-empty');
        
        if (this.elements.servicoChecks.length > 0) {
            this.bindServicosEvents();
            this.initServicosState();
        }
        
        this.bindValoresEvents();
        this.initPecasSearch();
        this.loadPecasExistentes();
    },
    
    bindServicosEvents() {
        this.elements.servicoChecks.forEach(check => {
            check.addEventListener('change', (e) => this.toggleServico(e.target));
        });
        
        document.querySelectorAll('.servico-qtd, .servico-valor').forEach(input => {
            input.addEventListener('change', () => this.updateTotal());
            input.addEventListener('input', () => this.updateTotal());
        });
    },
    
    initServicosState() {
        this.elements.servicoChecks.forEach(check => {
            const item = check.closest('.servico-item');
            const inputs = item.querySelector('.servico-inputs');
            
            if (check.checked) {
                inputs.style.display = 'flex';
            }
        });
    },
    
    toggleServico(checkbox) {
        const item = checkbox.closest('.servico-item');
        const inputs = item.querySelector('.servico-inputs');
        
        inputs.style.display = checkbox.checked ? 'flex' : 'none';
        this.updateTotal();
    },
    
    updateTotal() {
        let total = 0;
        
        this.elements.servicoChecks.forEach(check => {
            if (check.checked) {
                const item = check.closest('.servico-item');
                const qtdInput = item.querySelector('.servico-qtd');
                const valorInput = item.querySelector('.servico-valor');
                
                const qtd = parseInt(qtdInput.value) || 1;
                const valor = Utils.parseMoney(valorInput.value);
                
                total += qtd * valor;
            }
        });
        
        if (this.elements.valorTotalInput) {
            this.elements.valorTotalInput.value = total.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }
        
        this.updateFinalValue();
    },
    
    bindValoresEvents() {
        if (this.elements.valorTotalInput) {
            this.elements.valorTotalInput.addEventListener('input', () => this.updateFinalValue());
        }
        
        if (this.elements.descontoInput) {
            this.elements.descontoInput.addEventListener('input', () => this.updateFinalValue());
        }
    },
    
    updateFinalValue() {
        if (!this.elements.valorTotalInput || !this.elements.valorFinalDisplay) return;
        
        const total = Utils.parseMoney(this.elements.valorTotalInput.value);
        const desconto = this.elements.descontoInput ? Utils.parseMoney(this.elements.descontoInput.value) : 0;
        const final = total - desconto;
        
        this.elements.valorFinalDisplay.textContent = Utils.formatMoney(final);
    },
    
    // ========================================
    // PEÇAS
    // ========================================
    
    initPecasSearch() {
        const container = document.querySelector('.peca-add-container .peca-search-container');
        if (!container) {
            console.log('Container de peças não encontrado');
            return;
        }
        
        const input = container.querySelector('.peca-add-input');
        const dropdown = container.querySelector('.peca-add-dropdown');
        
        let pecasData = [];
        try {
            pecasData = JSON.parse(container.dataset.pecas || '[]');
            console.log('Peças carregadas:', pecasData.length, pecasData);
        } catch(e) {
            console.error('Erro ao parsear peças:', e);
            console.log('Dados brutos:', container.dataset.pecas);
        }
        
        input.addEventListener('input', () => {
            const term = input.value.toLowerCase().trim();
            console.log('Buscando:', term);
            
            if (term.length < 2) {
                dropdown.classList.remove('show');
                return;
            }
            
            console.log('Filtrando peças...');
            const filtered = pecasData.filter(p => 
                !this.pecasSelecionadas.includes(p.id) && (
                    p.nome.toLowerCase().includes(term) ||
                    p.codigo.toLowerCase().includes(term) ||
                    p.marca.toLowerCase().includes(term)
                )
            ).slice(0, 8);
            
            console.log('Encontrados:', filtered.length, filtered);
            
            if (filtered.length === 0) {
                dropdown.innerHTML = '<div class="client-search-empty"><i class="bi bi-search"></i> Nenhuma peça encontrada</div>';
            } else {
                dropdown.innerHTML = filtered.map(p => `
                    <div class="client-search-item" data-id="${p.id}" data-codigo="${p.codigo}" data-nome="${p.nome}" data-preco="${p.preco}" data-estoque="${p.estoque}">
                        <div class="client-search-avatar"><i class="bi bi-box-seam"></i></div>
                        <div class="client-search-info">
                            <strong>${p.codigo} - ${p.nome}</strong>
                            <span>${p.marca} | Estoque: ${p.estoque} | R$ ${parseFloat(p.preco).toFixed(2).replace('.', ',')}</span>
                        </div>
                    </div>
                `).join('');
            }
            
            dropdown.classList.add('show');
            
            dropdown.querySelectorAll('.client-search-item').forEach(item => {
                item.addEventListener('click', () => {
                    this.adicionarPeca({
                        id: item.dataset.id,
                        codigo: item.dataset.codigo,
                        nome: item.dataset.nome,
                        preco: parseFloat(item.dataset.preco),
                        estoque: parseInt(item.dataset.estoque)
                    });
                    input.value = '';
                    dropdown.innerHTML = '';
                    dropdown.classList.remove('show');
                });
            });
        });
        
        document.addEventListener('click', (e) => {
            if (!container.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    },
    
    loadPecasExistentes() {
        // Carrega peças que já existem (para edição)
        const pecasExistentes = document.querySelectorAll('.peca-item-existente');
        pecasExistentes.forEach(item => {
            const id = parseInt(item.dataset.id);
            if (id) {
                this.pecasSelecionadas.push(id);
            }
        });
        this.updatePecasEmpty();
    },
    
    adicionarPeca(peca) {
        if (this.pecasSelecionadas.includes(parseInt(peca.id))) {
            return;
        }
        
        this.pecasSelecionadas.push(parseInt(peca.id));
        
        const html = `
            <div class="peca-item" data-id="${peca.id}">
                <div class="peca-item-info">
                    <strong>${peca.codigo} - ${peca.nome}</strong>
                    <small>Estoque: ${peca.estoque}</small>
                </div>
                <div class="peca-item-inputs">
                    <input type="hidden" name="pecas[${peca.id}][selecionado]" value="1">
                    <div class="form-group">
                        <label>Qtd</label>
                        <input type="number" name="pecas[${peca.id}][quantidade]" value="1" min="1" max="${peca.estoque}" class="form-control peca-qtd" onchange="OrcamentosPage.updateTotal()">
                    </div>
                    <div class="form-group">
                        <label>Valor Unit.</label>
                        <input type="text" name="pecas[${peca.id}][preco_unitario]" value="${peca.preco.toFixed(2).replace('.', ',')}" class="form-control peca-valor" data-mask="money" onchange="OrcamentosPage.updateTotal()">
                    </div>
                </div>
                <button type="button" class="btn btn-ghost btn-sm text-danger" onclick="OrcamentosPage.removerPeca(${peca.id})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        
        this.elements.pecasContainer.insertAdjacentHTML('beforeend', html);
        this.updatePecasEmpty();
        this.updateTotal();
        
        // Aplica máscara ao novo input
        const newInput = this.elements.pecasContainer.querySelector(`[name="pecas[${peca.id}][preco_unitario]"]`);
        if (newInput && typeof Masks !== 'undefined') {
            Masks.money(newInput);
        }
    },
    
    removerPeca(pecaId) {
        const item = this.elements.pecasContainer.querySelector(`.peca-item[data-id="${pecaId}"]`);
        if (item) {
            item.remove();
        }
        
        this.pecasSelecionadas = this.pecasSelecionadas.filter(id => id !== parseInt(pecaId));
        this.updatePecasEmpty();
        this.updateTotal();
    },
    
    updatePecasEmpty() {
        if (!this.elements.pecasEmpty) return;
        
        if (this.pecasSelecionadas.length > 0) {
            this.elements.pecasEmpty.style.display = 'none';
        } else {
            this.elements.pecasEmpty.style.display = 'block';
        }
    },
    
    updateTotal() {
        let total = 0;
        
        // Soma serviços
        if (this.elements.servicoChecks) {
            this.elements.servicoChecks.forEach(check => {
                if (check.checked) {
                    const item = check.closest('.servico-item');
                    const qtdInput = item.querySelector('.servico-qtd');
                    const valorInput = item.querySelector('.servico-valor');
                    
                    const qtd = parseInt(qtdInput.value) || 1;
                    const valor = Utils.parseMoney(valorInput.value);
                    
                    total += qtd * valor;
                }
            });
        }
        
        // Soma peças
        if (this.elements.pecasContainer) {
            this.elements.pecasContainer.querySelectorAll('.peca-item').forEach(item => {
                const qtdInput = item.querySelector('.peca-qtd');
                const valorInput = item.querySelector('.peca-valor');
                
                const qtd = parseInt(qtdInput.value) || 1;
                const valor = Utils.parseMoney(valorInput.value);
                
                total += qtd * valor;
            });
        }
        
        if (this.elements.valorTotalInput) {
            this.elements.valorTotalInput.value = total.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }
        
        this.updateFinalValue();
    }
};

// Exporta para uso global
window.OrcamentosPage = OrcamentosPage;

