-- ========================================
-- Auto Portas - Atualização v2
-- Novas tabelas: Marcas, Peças, Contas a Receber
-- Execute este SQL no banco autoportas_db existente
-- ========================================

USE autoportas_db;

-- ========================================
-- TABELA: MARCAS (de veículos/portas)
-- ========================================
CREATE TABLE IF NOT EXISTS marcas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_nome (nome),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: PEÇAS
-- ========================================
CREATE TABLE IF NOT EXISTS pecas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marca_id INT NULL,
    codigo VARCHAR(50) NULL,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NULL,
    preco_custo DECIMAL(10, 2) DEFAULT 0.00,
    preco_venda DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    quantidade_estoque INT DEFAULT 0,
    estoque_minimo INT DEFAULT 5,
    localizacao VARCHAR(100) NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (marca_id) REFERENCES marcas(id) ON DELETE SET NULL,
    
    INDEX idx_marca_id (marca_id),
    INDEX idx_codigo (codigo),
    INDEX idx_nome (nome),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: CONTAS A RECEBER
-- ========================================
CREATE TABLE IF NOT EXISTS contas_receber (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orcamento_id INT NULL,
    cliente_id INT NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL,
    valor_pago DECIMAL(10, 2) DEFAULT 0.00,
    valor_pendente DECIMAL(10, 2) NOT NULL,
    data_vencimento DATE NOT NULL,
    data_pagamento DATE NULL,
    forma_pagamento VARCHAR(50) NULL,
    observacoes TEXT NULL,
    status ENUM('pendente', 'parcial', 'pago', 'vencido', 'cancelado') DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id) ON DELETE SET NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    
    INDEX idx_cliente_id (cliente_id),
    INDEX idx_orcamento_id (orcamento_id),
    INDEX idx_status (status),
    INDEX idx_data_vencimento (data_vencimento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: PAGAMENTOS (histórico)
-- ========================================
CREATE TABLE IF NOT EXISTS pagamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conta_id INT NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    data_pagamento DATE NOT NULL,
    forma_pagamento VARCHAR(50) NULL,
    observacoes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (conta_id) REFERENCES contas_receber(id) ON DELETE CASCADE,
    
    INDEX idx_conta_id (conta_id),
    INDEX idx_data_pagamento (data_pagamento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- DADOS DE EXEMPLO - MARCAS
-- ========================================
INSERT INTO marcas (nome, descricao, status) VALUES
('Garen', 'Motores e peças Garen para portões', 'ativo'),
('PPA', 'Automatizadores PPA', 'ativo'),
('RCG', 'Motores e acessórios RCG', 'ativo'),
('Rossi', 'Automatizadores Rossi', 'ativo'),
('Nice', 'Sistemas de automação Nice', 'ativo'),
('Peccinin', 'Motores Peccinin', 'ativo'),
('Unisystem', 'Peças Unisystem', 'ativo'),
('Genérico', 'Peças genéricas/universais', 'ativo');

-- ========================================
-- DADOS DE EXEMPLO - PEÇAS
-- ========================================
INSERT INTO pecas (marca_id, codigo, nome, descricao, preco_custo, preco_venda, quantidade_estoque, estoque_minimo) VALUES
(1, 'GAR-MOT-001', 'Motor Garen Basculante 1/4HP', 'Motor para portão basculante até 300kg', 450.00, 750.00, 10, 3),
(1, 'GAR-CON-001', 'Controle Remoto Garen', 'Controle remoto 433MHz', 25.00, 55.00, 50, 10),
(2, 'PPA-MOT-001', 'Motor PPA Deslizante 1/4HP', 'Motor para portão deslizante até 400kg', 520.00, 850.00, 8, 3),
(2, 'PPA-CRE-001', 'Cremalheira PPA 1,5m', 'Cremalheira de nylon para portão deslizante', 35.00, 65.00, 30, 10),
(3, 'RCG-MOT-001', 'Motor RCG Pivotante', 'Motor para portão pivotante até 2,5m', 380.00, 650.00, 5, 2),
(4, 'ROS-SEN-001', 'Sensor de Presença Rossi', 'Sensor infravermelho ativo', 85.00, 150.00, 20, 5),
(5, 'NIC-CEN-001', 'Central de Comando Nice', 'Central eletrônica para motor Nice', 180.00, 320.00, 8, 3),
(8, 'GEN-FIM-001', 'Fim de Curso Universal', 'Kit fim de curso magnético', 15.00, 35.00, 40, 15),
(8, 'GEN-BOT-001', 'Botoeira Externa', 'Botoeira para abertura externa', 22.00, 45.00, 25, 10),
(8, 'GEN-TRA-001', 'Trava Eletromagnética', 'Trava elétrica para portão', 95.00, 180.00, 12, 5);

SELECT 'Atualização concluída com sucesso!' AS Status;

