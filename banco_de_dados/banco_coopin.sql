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
	cpf INT UNIQUE PRIMARY KEY,
    email VARCHAR(260) NOT NULL,
    senha VARCHAR(260) NOT NULL
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
    cpf_gerente INT,
    FOREIGN KEY (cpf_gerente) REFERENCES gerente(cpf)
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
    valor DECIMAL(10, 2),
    categoria VARCHAR(260),
    quant_estoque INT
);

Select * from usuario;	