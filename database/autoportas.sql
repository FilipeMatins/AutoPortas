-- ========================================
-- Auto Portas - Sistema de Gestão
-- Script de Criação do Banco de Dados
-- ========================================

-- Cria o banco de dados
CREATE DATABASE IF NOT EXISTS autoportas_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE autoportas_db;

-- ========================================
-- TABELA: CLIENTES
-- ========================================
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL,
    telefone VARCHAR(20) NOT NULL,
    cpf_cnpj VARCHAR(20) NULL,
    endereco VARCHAR(255) NULL,
    cidade VARCHAR(100) NULL,
    estado CHAR(2) NULL,
    cep VARCHAR(10) NULL,
    observacoes TEXT NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_nome (nome),
    INDEX idx_email (email),
    INDEX idx_telefone (telefone),
    INDEX idx_cpf_cnpj (cpf_cnpj)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: SERVIÇOS
-- ========================================
CREATE TABLE IF NOT EXISTS servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NULL,
    preco DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    categoria ENUM('instalacao', 'manutencao', 'reparo', 'automatizacao', 'vistoria', 'outros') DEFAULT 'outros',
    tempo_estimado VARCHAR(50) NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_nome (nome),
    INDEX idx_categoria (categoria),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: ORÇAMENTOS
-- ========================================
CREATE TABLE IF NOT EXISTS orcamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    descricao TEXT NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    desconto DECIMAL(10, 2) DEFAULT 0.00,
    valor_final DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    data_validade DATE NULL,
    forma_pagamento VARCHAR(50) NULL,
    observacoes TEXT NULL,
    status ENUM('pendente', 'aprovado', 'rejeitado', 'em_execucao', 'concluido') DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    
    INDEX idx_cliente_id (cliente_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: ORÇAMENTO_SERVIÇOS (Relacionamento)
-- ========================================
CREATE TABLE IF NOT EXISTS orcamento_servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orcamento_id INT NOT NULL,
    servico_id INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    valor_unitario DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    valor_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id) ON DELETE CASCADE,
    FOREIGN KEY (servico_id) REFERENCES servicos(id) ON DELETE CASCADE,
    
    INDEX idx_orcamento_id (orcamento_id),
    INDEX idx_servico_id (servico_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- DADOS DE EXEMPLO
-- ========================================

-- Inserir alguns serviços de exemplo
INSERT INTO servicos (nome, descricao, preco, categoria, tempo_estimado, status) VALUES
('Instalação de Porta Automática', 'Instalação completa de porta automática de vidro ou metal, incluindo motor e sensores.', 2500.00, 'instalacao', '4-6 horas', 'ativo'),
('Manutenção Preventiva', 'Manutenção preventiva completa com lubrificação, ajustes e verificação de componentes.', 350.00, 'manutencao', '2 horas', 'ativo'),
('Reparo de Motor', 'Reparo ou substituição de motor de porta automática.', 800.00, 'reparo', '3-4 horas', 'ativo'),
('Troca de Sensores', 'Substituição de sensores de presença e segurança.', 450.00, 'reparo', '1-2 horas', 'ativo'),
('Automatização de Porta Existente', 'Conversão de porta comum para automática.', 1800.00, 'automatizacao', '4-5 horas', 'ativo'),
('Vistoria Técnica', 'Avaliação técnica completa do sistema de porta automática.', 150.00, 'vistoria', '1 hora', 'ativo'),
('Ajuste de Velocidade', 'Regulagem de velocidade de abertura e fechamento.', 200.00, 'manutencao', '30 min', 'ativo'),
('Instalação de Controle Remoto', 'Instalação de sistema de controle remoto para portas automáticas.', 400.00, 'automatizacao', '1-2 horas', 'ativo');

-- Inserir alguns clientes de exemplo
INSERT INTO clientes (nome, email, telefone, cpf_cnpj, endereco, cidade, estado, cep, status) VALUES
('João Silva', 'joao.silva@email.com', '11999998888', '123.456.789-00', 'Rua das Flores, 123', 'São Paulo', 'SP', '01234-567', 'ativo'),
('Maria Santos', 'maria.santos@email.com', '11988887777', '987.654.321-00', 'Av. Brasil, 456', 'São Paulo', 'SP', '04567-890', 'ativo'),
('Empresa ABC Ltda', 'contato@empresaabc.com.br', '1133334444', '12.345.678/0001-90', 'Rua Comercial, 789', 'São Paulo', 'SP', '01000-000', 'ativo'),
('Pedro Oliveira', 'pedro.oliveira@email.com', '11977776666', '456.789.123-00', 'Rua do Comércio, 100', 'Guarulhos', 'SP', '07000-000', 'ativo');

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
-- TABELA: PAGAMENTOS (histórico de pagamentos)
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
-- DADOS DE EXEMPLO - MARCAS DE VEÍCULOS
-- ========================================
INSERT INTO marcas (nome, descricao, status) VALUES
('Fiat', 'Peças para veículos Fiat', 'ativo'),
('Volkswagen (VW)', 'Peças para veículos Volkswagen', 'ativo'),
('Chevrolet (GM)', 'Peças para veículos Chevrolet/General Motors', 'ativo'),
('Ford', 'Peças para veículos Ford', 'ativo'),
('Toyota', 'Peças para veículos Toyota', 'ativo'),
('Honda', 'Peças para veículos Honda', 'ativo'),
('Hyundai', 'Peças para veículos Hyundai', 'ativo'),
('Renault', 'Peças para veículos Renault', 'ativo'),
('Nissan', 'Peças para veículos Nissan', 'ativo'),
('Jeep', 'Peças para veículos Jeep', 'ativo'),
('Peugeot', 'Peças para veículos Peugeot', 'ativo'),
('Citroën', 'Peças para veículos Citroën', 'ativo'),
('Mitsubishi', 'Peças para veículos Mitsubishi', 'ativo'),
('Chery (Caoa Chery)', 'Peças para veículos Chery', 'ativo'),
('BMW', 'Peças para veículos BMW', 'ativo'),
('Mercedes-Benz', 'Peças para veículos Mercedes-Benz', 'ativo'),
('Audi', 'Peças para veículos Audi', 'ativo'),
('Volvo', 'Peças para veículos Volvo', 'ativo'),
('Kia', 'Peças para veículos Kia', 'ativo');

-- ========================================
-- TABELA: MOVIMENTAÇÕES DE ESTOQUE
-- ========================================
CREATE TABLE IF NOT EXISTS movimentacoes_estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    peca_id INT NOT NULL,
    tipo ENUM('entrada', 'saida') NOT NULL,
    quantidade INT NOT NULL,
    motivo ENUM('compra', 'servico', 'ajuste', 'perda', 'devolucao') NOT NULL,
    orcamento_id INT NULL,
    observacoes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (peca_id) REFERENCES pecas(id) ON DELETE CASCADE,
    FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id) ON DELETE SET NULL,
    
    INDEX idx_peca_id (peca_id),
    INDEX idx_tipo (tipo),
    INDEX idx_orcamento_id (orcamento_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: PEÇAS USADAS NO ORÇAMENTO
-- ========================================
CREATE TABLE IF NOT EXISTS orcamento_pecas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orcamento_id INT NOT NULL,
    peca_id INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    preco_total DECIMAL(10, 2) NOT NULL,
    baixa_estoque TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id) ON DELETE CASCADE,
    FOREIGN KEY (peca_id) REFERENCES pecas(id) ON DELETE CASCADE,
    
    INDEX idx_orcamento_id (orcamento_id),
    INDEX idx_peca_id (peca_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: NOTIFICAÇÕES DO USUÁRIO
-- ========================================
CREATE TABLE IF NOT EXISTS notificacoes_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hash_notificacao VARCHAR(64) NOT NULL,
    status ENUM('lida', 'excluida') NOT NULL DEFAULT 'lida',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_hash (hash_notificacao),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT 'Banco de dados criado com sucesso!' AS Status;

