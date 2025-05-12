CREATE DATABASE Coopin;
USE Coopin;

-- Tabela de usuários
CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    data_nasc DATE,
    senha VARCHAR(255) NOT NULL,
    tipo INT NOT NULL DEFAULT 0, -- 1=admin, 2=gerente, 3=garcom
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de gerentes
CREATE TABLE gerente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    rg VARCHAR(20) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES usuario(id)
);

-- Tabela de garçons
CREATE TABLE garcom (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    escolaridade VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES usuario(id)
);

-- Tabela de categoria

CREATE TABLE categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    tipo ENUM('BEBIDA', 'SALGADO') NOT NULL
);

-- Tabela de subcategoria

CREATE TABLE subcategoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES categoria(id)
);

-- Tabela de produtos

CREATE TABLE produto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    categoria_id INT NOT NULL,
    subcategoria_id INT,
    porcao VARCHAR(50),
    qtd_estoque INT DEFAULT 0,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categoria(id),
    FOREIGN KEY (subcategoria_id) REFERENCES subcategoria(id)
);

-- Tabela de contas/mesas
CREATE TABLE conta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mesa INT NOT NULL,
    garcom_id INT NOT NULL,
    data_abertura DATE NOT NULL,
    hora_abertura TIME NOT NULL,
    data_fechamento DATE,
    hora_fechamento TIME,
    valor_total DECIMAL(10,2) DEFAULT 0,
    status VARCHAR(20) DEFAULT 'aberta', -- aberta, fechada
    FOREIGN KEY (garcom_id) REFERENCES usuario(id)
);

-- Tabela de pedidos
CREATE TABLE pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conta_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conta_id) REFERENCES conta(id),
    FOREIGN KEY (produto_id) REFERENCES produto(id)
);

-- Tabela de histórico de comissões
CREATE TABLE historico_comissao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    garcom_id INT NOT NULL,
    mes_referencia DATE NOT NULL,
    total_vendido DECIMAL(10,2) NOT NULL,
    valor_comissao DECIMAL(10,2) NOT NULL,
    data_calculo DATETIME NOT NULL,
    FOREIGN KEY (garcom_id) REFERENCES garcom(id)
);

/*senha: admin*/
INSERT INTO usuario (nome, cpf, email, senha, tipo)
VALUES ('Administrador', '12345678901', 'admin@admin.com', '$2y$10$E6d.qxxZw.l/RWzm4pBOXOHaA9QJ5QmfWKjoYQeVnnbtEdrxZlvrS', 1);
Select * from usuario;
Select * from gerente;
Select * from garcom;
Select * from produto;
Select * from conta;
ALTER TABLE conta
ADD COLUMN status_conta VARCHAR(20) DEFAULT 'aberta';

SELECT * FROM pedido;

-- Inserir categorias principais
INSERT INTO categoria (nome, tipo) VALUES 
('Bebidas', 'BEBIDA'),
('Salgados', 'SALGADO');

-- Inserir subcategorias para bebidas
INSERT INTO subcategoria (categoria_id, nome) VALUES 
(1, 'Fria'),
(1, 'Quente'),
(1, 'Especial');

-- Inserir subcategorias para salgados
INSERT INTO subcategoria (categoria_id, nome) VALUES 
(2, 'Forno'),
(2, 'Frito');
