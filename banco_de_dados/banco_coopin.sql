CREATE DATABASE Coopin;
USE Coopin;

CREATE TABLE usuario(
	cod_user INT PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(260) NOT NULL,
    telefone CHAR(11),
    data_nasc DATE,
    email VARCHAR(260) NOT NULL UNIQUE,
    senha VARCHAR(260) NOT NULL,
    cpf CHAR(11) UNIQUE
);

CREATE TABLE gerente(
	cod_gerente INT PRIMARY KEY AUTO_INCREMENT,
	rg INT UNIQUE,
    cod_user INT,
    FOREIGN KEY (cod_user) REFERENCES usuario(cod_user)
);

CREATE TABLE garcom (
    cod_garcom INT PRIMARY KEY AUTO_INCREMENT,
    escolaridade VARCHAR(260),
    cod_user INT,
    FOREIGN KEY (cod_user) REFERENCES usuario(cod_user)
);

CREATE TABLE control_garcom (
    cod_control_garcom INT PRIMARY KEY AUTO_INCREMENT,
    cod_garcom INT,
    FOREIGN KEY (cod_garcom) REFERENCES garcom(cod_garcom)
);

CREATE TABLE control_gerente (
    cod_control_gerente INT PRIMARY KEY AUTO_INCREMENT,
    rg_gerente INT,
    FOREIGN KEY (rg_gerente) REFERENCES gerente(rg)
);

CREATE TABLE conta (
    cod_conta INT PRIMARY KEY AUTO_INCREMENT,
    cod_garcom INT,
    data_abertura DATE,
    hora_abertura TIME,
    valor_total DECIMAL(10, 2),
    FOREIGN KEY (cod_garcom) REFERENCES garcom(cod_garcom)
);

CREATE TABLE pedido (
    cod_pedido INT PRIMARY KEY AUTO_INCREMENT,
    cod_user INT,
    data_pedido DATE,
    hora_pedido TIME,
    valor_total DECIMAL(10, 2),
    FOREIGN KEY (cod_user) REFERENCES usuario(cod_user)
);

CREATE TABLE produto (
    cod_produto INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(260) NOT NULL,
    porcao VARCHAR(260),
    preco DECIMAL(10, 2),
    categoria VARCHAR(260),
    qtd_estoque INT
);
Select * from usuario;	
Select * from gerente;
Select * from produto;
SELECT u.cpf, u.nome, u.telefone, u.email, u.senha, g.rg
    FROM gerente as g 
    JOIN usuario as u ON g.cod_user = u.cod_user;	
    