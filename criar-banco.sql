-- ========================================
-- SCRIPT DE CRIAÇÃO DO BANCO DE DADOS
-- Sistema de Advocacia
-- ========================================

-- Cria o banco de dados se não existir
CREATE DATABASE IF NOT EXISTS advocacia 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Usa o banco de dados
USE advocacia;

-- ========================================
-- TABELA DE USUÁRIOS
-- ========================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    usuario VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    senha_original VARCHAR(50),
    nivel ENUM('admin', 'Advogado', 'Cliente', 'Recepcionista', 'Tesoureiro') NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
);

-- ========================================
-- TABELA DE CLIENTES
-- ========================================
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf_cnpj VARCHAR(18) UNIQUE NOT NULL,
    email VARCHAR(100),
    telefone VARCHAR(20),
    endereco TEXT,
    cidade VARCHAR(100),
    estado CHAR(2),
    cep VARCHAR(10),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
);

-- ========================================
-- TABELA DE PROCESSOS
-- ========================================
CREATE TABLE IF NOT EXISTS processos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_processo VARCHAR(50) UNIQUE NOT NULL,
    cliente_id INT NOT NULL,
    advogado_id INT,
    vara VARCHAR(100),
    comarca VARCHAR(100),
    tipo_processo VARCHAR(100),
    assunto TEXT,
    valor_causa DECIMAL(15,2),
    data_distribuicao DATE,
    status ENUM('Ativo', 'Concluído', 'Arquivado', 'Suspenso') DEFAULT 'Ativo',
    observacoes TEXT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (advogado_id) REFERENCES usuarios(id)
);

-- ========================================
-- TABELA DE AUDIÊNCIAS
-- ========================================
CREATE TABLE IF NOT EXISTS audiencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    processo_id INT NOT NULL,
    data_audiencia DATETIME NOT NULL,
    tipo VARCHAR(100),
    local VARCHAR(200),
    status ENUM('Agendada', 'Realizada', 'Cancelada', 'Adiada') DEFAULT 'Agendada',
    observacoes TEXT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (processo_id) REFERENCES processos(id)
);

-- ========================================
-- TABELA DE MOVIMENTAÇÕES
-- ========================================
CREATE TABLE IF NOT EXISTS movimentacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    processo_id INT NOT NULL,
    data_movimentacao DATE NOT NULL,
    tipo VARCHAR(100),
    descricao TEXT,
    documento VARCHAR(255),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (processo_id) REFERENCES processos(id)
);

-- ========================================
-- TABELA DE PAGAMENTOS
-- ========================================
CREATE TABLE IF NOT EXISTS pagamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    processo_id INT,
    cliente_id INT,
    tipo ENUM('Entrada', 'Parcela', 'Honorários', 'Outros') NOT NULL,
    valor DECIMAL(15,2) NOT NULL,
    data_pagamento DATE NOT NULL,
    forma_pagamento VARCHAR(100),
    status ENUM('Pago', 'Pendente', 'Cancelado') DEFAULT 'Pendente',
    observacoes TEXT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (processo_id) REFERENCES processos(id),
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

-- ========================================
-- TABELA DE ESPECIALIDADES
-- ========================================
CREATE TABLE IF NOT EXISTS especialidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL,
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- ========================================
-- TABELA DE CARGOS
-- ========================================
CREATE TABLE IF NOT EXISTS cargos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL,
    descricao TEXT,
    salario_base DECIMAL(10,2),
    ativo BOOLEAN DEFAULT TRUE
);

-- ========================================
-- TABELA DE FUNCIONÁRIOS
-- ========================================
CREATE TABLE IF NOT EXISTS funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    cargo_id INT,
    data_admissao DATE,
    salario DECIMAL(10,2),
    telefone VARCHAR(20),
    email VARCHAR(100),
    endereco TEXT,
    ativo BOOLEAN DEFAULT TRUE,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cargo_id) REFERENCES cargos(id)
);

-- ========================================
-- INSERÇÃO DE DADOS INICIAIS
-- ========================================

-- Usuário administrador padrão
INSERT INTO usuarios (nome, cpf, usuario, senha, senha_original, nivel) VALUES 
('Administrador', '000.000.000-00', 'admin@advocacia.com', MD5('123'), '123', 'admin')
ON DUPLICATE KEY UPDATE id = id;

-- Cliente de exemplo
INSERT INTO clientes (nome, cpf_cnpj, email, telefone, endereco, cidade, estado, cep) VALUES 
('João Silva', '123.456.789-00', 'joao@email.com', '(11) 99999-9999', 'Rua das Flores, 123', 'São Paulo', 'SP', '01234-567')
ON DUPLICATE KEY UPDATE id = id;

-- Especialidades básicas
INSERT INTO especialidades (nome, descricao) VALUES 
('Direito Civil', 'Contratos, responsabilidade civil, direito de família'),
('Direito Trabalhista', 'Relações de trabalho, rescisão, férias'),
('Direito Previdenciário', 'Aposentadoria, benefícios, INSS'),
('Direito Tributário', 'Impostos, taxas, multas'),
('Direito Administrativo', 'Licitações, concursos, servidores públicos')
ON DUPLICATE KEY UPDATE id = id;

-- Cargos básicos
INSERT INTO cargos (nome, descricao, salario_base) VALUES 
('Advogado', 'Advogado responsável por processos', 5000.00),
('Estagiário', 'Estagiário de direito', 1500.00),
('Recepcionista', 'Atendimento ao público', 2000.00),
('Tesoureiro', 'Controle financeiro', 3000.00),
('Assistente Administrativo', 'Suporte administrativo', 2500.00)
ON DUPLICATE KEY UPDATE id = id;

-- Processo de exemplo
INSERT INTO processos (numero_processo, cliente_id, tipo_processo, assunto, valor_causa, data_distribuicao) VALUES 
('001/2024', 1, 'Direito Civil', 'Indenização por danos morais', 5000.00, '2024-01-15')
ON DUPLICATE KEY UPDATE id = id;

-- ========================================
-- VERIFICAÇÃO FINAL
-- ========================================
SELECT 'Banco de dados criado com sucesso!' as status;
SELECT COUNT(*) as total_usuarios FROM usuarios;
SELECT COUNT(*) as total_clientes FROM clientes;
SELECT COUNT(*) as total_processos FROM processos;
SELECT COUNT(*) as total_especialidades FROM especialidades;
SELECT COUNT(*) as total_cargos FROM cargos;
