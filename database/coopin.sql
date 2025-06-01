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
    tipo ENUM('BEBIDA', 'SALGADO', 'BOLO') NOT NULL
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
    valor_total DECIMAL(65,2) DEFAULT 0,
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
SELECT SUM(valor_total) AS valor_total FROM conta WHERE garcom_id = 1;
ALTER TABLE conta
ADD COLUMN status_conta VARCHAR(20) DEFAULT 'aberta';

SELECT * FROM pedido;

-- CATEGORIAS PRINCIPAIS
INSERT INTO categoria (nome, tipo) VALUES 
('Bebidas', 'BEBIDA'),
('Salgados', 'SALGADO'),
('Bolos', 'BOLO');

-- SUBCATEGORIAS DE BEBIDAS
INSERT INTO subcategoria (categoria_id, nome) VALUES 
(1, 'Fria'),
(1, 'Quente'),
(1, 'Especial');

-- SUBCATEGORIAS DE SALGADOS
INSERT INTO subcategoria (categoria_id, nome) VALUES 
(2, 'Forno'),
(2, 'Frito');

-- SUBCATEGORIAS DE BOLOS
INSERT INTO subcategoria (categoria_id, nome) VALUES 
(3, 'Tradicionais'),
(3, 'Especiais'),
(3, 'Premium');

-- BEBIDAS FRIAS (subcategoria_id = 1)
INSERT INTO produto (nome, preco, categoria_id, subcategoria_id, porcao, qtd_estoque) VALUES
('Café Gelado', 5.50, 1, 1, 'Média', 50),
('Frapuccino de Café', 8.00, 1, 1, 'Grande', 35),
('Frapuccino de Chocolate', 8.00, 1, 1, 'Grande', 40),
('Chá Gelado', 5.00, 1, 1, 'Pequena', 60),
('Limonada Refrescante', 7.50, 1, 1, 'Média', 45),
('Milkshake de Morango', 7.50, 1, 1, 'Grande', 30),
('Milkshake de Chocolate', 4.50, 1, 1, 'Média', 55),
('Smoothie de Frutas', 5.00, 1, 1, 'Média', 40);

-- BEBIDAS QUENTES (subcategoria_id = 2)
INSERT INTO produto (nome, preco, categoria_id, subcategoria_id, porcao, qtd_estoque) VALUES
('Café Expresso', 3.50, 1, 2, 'Pequena', 100),
('Café Americano', 5.00, 1, 2, 'Média', 80),
('Cappuccino', 5.00, 1, 2, 'Média', 70),
('Latte', 5.00, 1, 2, 'Grande', 60),
('Macchiato', 7.50, 1, 2, 'Média', 65),
('Mocha', 5.00, 1, 2, 'Grande', 50),
('Chá Preto', 4.50, 1, 2, 'Pequena', 90),
('Chá Verde', 5.50, 1, 2, 'Pequena', 85);

-- BEBIDAS ESPECIAIS (subcategoria_id = 3)
INSERT INTO produto (nome, preco, categoria_id, subcategoria_id, porcao, qtd_estoque) VALUES
('Affogato', 5.00, 1, 3, 'Pequena', 40),
('Irish Coffee', 8.50, 1, 3, 'Média', 30),
('Cold Brew', 5.00, 1, 3, 'Média', 45),
('Tônica de Café', 7.50, 1, 3, 'Grande', 25);

-- SALGADOS DE FORNO (subcategoria_id = 4)
INSERT INTO produto (nome, preco, categoria_id, subcategoria_id, porcao, qtd_estoque) VALUES
('Pão de Queijo', 8.00, 2, 4, 'Pequena', 120),
('Empada de Frango', 7.50, 2, 4, 'Média', 90),
('Croissant', 6.50, 2, 4, 'Média', 80),
('Biscoito de Polvilho', 5.00, 2, 4, 'Pequena', 150),
('Torta de Frango', 12.00, 2, 4, 'Grande', 60);

-- SALGADOS FRITOS (subcategoria_id = 5)
INSERT INTO produto (nome, preco, categoria_id, subcategoria_id, porcao, qtd_estoque) VALUES
('Coxinha', 6.00, 2, 5, 'Média', 110),
('Pastel de Carne', 7.00, 2, 5, 'Grande', 75),
('Bolinha de Queijo', 9.00, 2, 5, 'Pequena', 130),
('Kibe', 8.50, 2, 5, 'Média', 95),
('Risole de Presunto', 6.50, 2, 5, 'Média', 85);

-- BOLOS TRADICIONAIS (subcategoria_id = 6)
INSERT INTO produto (nome, preco, categoria_id, subcategoria_id, porcao, qtd_estoque) VALUES
('Bolo de Fubá', 25.00, 3, 6, 'Grande', 20),
('Bolo de Laranja', 28.00, 3, 6, 'Grande', 18),
('Bolo de Cenoura', 30.00, 3, 6, 'Grande', 25),
('Bolo de Chocolate Simples', 32.00, 3, 6, 'Grande', 30),
('Bolo de Milho', 26.00, 3, 6, 'Média', 22);

-- BOLOS ESPECIAIS (subcategoria_id = 7)
INSERT INTO produto (nome, preco, categoria_id, subcategoria_id, porcao, qtd_estoque) VALUES
('Red Velvet', 45.00, 3, 7, 'Grande', 15),
('Bolo de Nutella', 48.00, 3, 7, 'Grande', 12),
('Cheesecake de Morango', 50.00, 3, 7, 'Média', 18),
('Bolo Floresta Negra', 52.00, 3, 7, 'Grande', 10),
('Bolo de Limão Siciliano', 46.00, 3, 7, 'Média', 14);

-- BOLOS PREMIUM (subcategoria_id = 8)
INSERT INTO produto (nome, preco, categoria_id, subcategoria_id, porcao, qtd_estoque) VALUES
('Bolo Trufado', 65.00, 3, 8, 'Grande', 8),
('Bolo Frutas Vermelhas', 68.00, 3, 8, 'Grande', 10),
('Bolo Opera', 70.00, 3, 8, 'Grande', 6),
('Bolo de Champagne', 75.00, 3, 8, 'Grande', 5),
('Bolo Diamante Negro', 80.00, 3, 8, 'Grande', 7);