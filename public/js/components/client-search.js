/**
 * Auto Portas - Busca de Cliente
 * Campo de autocomplete para seleção de clientes
 */

const ClientSearch = {
    init() {
        const searchContainers = document.querySelectorAll('.client-search-container');
        searchContainers.forEach(container => this.setup(container));
    },
    
    setup(container) {
        const input = container.querySelector('.client-search-input');
        const hiddenInput = container.querySelector('.client-search-value');
        const dropdown = container.querySelector('.client-search-dropdown');
        const clientesData = container.dataset.clientes ? JSON.parse(container.dataset.clientes) : [];
        
        if (!input || !dropdown) return;
        
        // Ao digitar, filtra os clientes
        input.addEventListener('input', () => {
            const term = input.value.toLowerCase().trim();
            
            if (term.length < 2) {
                dropdown.classList.remove('show');
                return;
            }
            
            const filtered = clientesData.filter(cliente => 
                cliente.nome.toLowerCase().includes(term) ||
                (cliente.telefone && cliente.telefone.includes(term)) ||
                (cliente.cpf_cnpj && cliente.cpf_cnpj.includes(term))
            ).slice(0, 10); // Limita a 10 resultados
            
            this.renderDropdown(dropdown, filtered, input, hiddenInput);
        });
        
        // Ao focar, mostra dropdown se tiver texto
        input.addEventListener('focus', () => {
            if (input.value.length >= 2) {
                input.dispatchEvent(new Event('input'));
            }
        });
        
        // Fecha dropdown ao clicar fora
        document.addEventListener('click', (e) => {
            if (!container.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
        
        // Navegação por teclado
        input.addEventListener('keydown', (e) => {
            const items = dropdown.querySelectorAll('.client-search-item');
            const active = dropdown.querySelector('.client-search-item.active');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (!active && items.length > 0) {
                    items[0].classList.add('active');
                } else if (active && active.nextElementSibling) {
                    active.classList.remove('active');
                    active.nextElementSibling.classList.add('active');
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (active && active.previousElementSibling) {
                    active.classList.remove('active');
                    active.previousElementSibling.classList.add('active');
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (active) {
                    active.click();
                }
            } else if (e.key === 'Escape') {
                dropdown.classList.remove('show');
            }
        });
    },
    
    renderDropdown(dropdown, clientes, input, hiddenInput) {
        if (clientes.length === 0) {
            dropdown.innerHTML = '<div class="client-search-empty"><i class="bi bi-search"></i> Nenhum cliente encontrado</div>';
            dropdown.classList.add('show');
            return;
        }
        
        dropdown.innerHTML = clientes.map(cliente => `
            <div class="client-search-item" data-id="${cliente.id}" data-nome="${cliente.nome}">
                <div class="client-search-avatar">${cliente.nome.charAt(0).toUpperCase()}</div>
                <div class="client-search-info">
                    <strong>${cliente.nome}</strong>
                    <span>${cliente.telefone || ''}</span>
                </div>
            </div>
        `).join('');
        
        dropdown.classList.add('show');
        
        // Ao clicar em um item
        dropdown.querySelectorAll('.client-search-item').forEach(item => {
            item.addEventListener('click', () => {
                input.value = item.dataset.nome;
                hiddenInput.value = item.dataset.id;
                dropdown.classList.remove('show');
                
                // Dispara evento de change para outros scripts
                hiddenInput.dispatchEvent(new Event('change'));
            });
        });
    }
};

// Exporta para uso global
window.ClientSearch = ClientSearch;

