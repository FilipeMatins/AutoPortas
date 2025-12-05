-- ========================================
-- ATUALIZAÇÃO: Marcas de Veículos
-- Execute este script para atualizar as marcas
-- ========================================

-- Limpa as tabelas (cuidado: apaga peças cadastradas!)
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE pecas;
TRUNCATE TABLE marcas;
SET FOREIGN_KEY_CHECKS = 1;

-- Insere as novas marcas de veículos
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

SELECT 'Marcas de veículos atualizadas com sucesso!' AS Status;
SELECT * FROM marcas ORDER BY nome;

