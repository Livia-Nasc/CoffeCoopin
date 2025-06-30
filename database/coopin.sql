-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/06/2025 às 04:40
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `coopin`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `tipo` enum('BEBIDA','SALGADO','BOLO') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`, `tipo`) VALUES
(1, 'Bebidas', 'BEBIDA'),
(2, 'Salgados', 'SALGADO'),
(3, 'Bolos', 'BOLO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `conta`
--

CREATE TABLE `conta` (
  `id` int(11) NOT NULL,
  `mesa` int(11) NOT NULL,
  `garcom_id` int(11) NOT NULL,
  `data_abertura` date NOT NULL,
  `hora_abertura` time NOT NULL,
  `data_fechamento` date DEFAULT NULL,
  `hora_fechamento` time DEFAULT NULL,
  `valor_total` decimal(65,2) DEFAULT 0.00,
  `status` varchar(20) DEFAULT 'aberta',
  `status_conta` varchar(20) DEFAULT 'aberta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `conta`
--

INSERT INTO `conta` (`id`, `mesa`, `garcom_id`, `data_abertura`, `hora_abertura`, `data_fechamento`, `hora_fechamento`, `valor_total`, `status`, `status_conta`) VALUES
(1, 1, 1, '2025-06-10', '00:00:00', '2025-06-10', NULL, 5.00, 'fechada', 'aberta'),
(2, 2, 1, '2025-05-15', '12:30:00', '2025-05-15', '14:00:00', 34.00, 'fechada', 'fechada'),
(3, 3, 2, '2025-05-10', '10:00:00', '2025-05-10', '11:30:00', 42.00, 'fechada', 'fechada'),
(4, 4, 2, '2025-05-20', '18:00:00', '2025-05-20', '19:30:00', 39.00, 'fechada', 'fechada'),
(5, 5, 3, '2025-05-05', '09:00:00', '2025-05-05', '10:30:00', 14.50, 'fechada', 'fechada'),
(6, 6, 3, '2025-05-12', '15:00:00', '2025-05-12', '16:30:00', 50.00, 'fechada', 'fechada'),
(7, 7, 4, '2025-05-08', '14:00:00', '2025-05-08', '15:30:00', 73.50, 'fechada', 'fechada'),
(8, 8, 4, '2025-05-25', '19:00:00', '2025-05-25', '20:30:00', 38.50, 'fechada', 'fechada'),
(9, 1, 1, '2025-06-01', '09:00:00', '2025-06-01', '10:30:00', 24.50, 'fechada', 'fechada'),
(10, 2, 2, '2025-06-02', '10:00:00', NULL, NULL, 11.50, 'aberta', 'aberta'),
(11, 3, 3, '2025-06-02', '11:00:00', NULL, NULL, 35.50, 'aberta', 'aberta'),
(12, 4, 4, '2025-06-03', '14:00:00', '2025-06-03', '15:30:00', 28.00, 'fechada', 'fechada'),
(13, 5, 1, '2025-06-03', '16:00:00', NULL, NULL, 13.50, 'aberta', 'aberta'),
(14, 6, 2, '2025-06-04', '18:00:00', NULL, NULL, 53.00, 'aberta', 'aberta'),
(15, 2, 1, '2025-06-15', '19:26:47', NULL, NULL, 48.00, 'aberta', 'aberta'),
(16, 9, 1, '2025-06-15', '19:37:02', NULL, NULL, 0.00, 'aberta', 'aberta'),
(17, 52, 1, '2025-06-16', '13:57:21', NULL, NULL, 80.00, 'aberta', 'aberta'),
(18, 1, 1, '2025-06-29', '23:02:18', NULL, NULL, 0.00, 'aberta', 'aberta');

-- --------------------------------------------------------

--
-- Estrutura para tabela `garcom`
--

CREATE TABLE `garcom` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `escolaridade` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `garcom`
--

INSERT INTO `garcom` (`id`, `user_id`, `escolaridade`) VALUES
(1, 3, 'Doutorado'),
(2, 4, 'Ensino Médio Completo'),
(3, 5, 'Graduação em Gastronomia'),
(4, 6, 'Ensino Superior Incompleto');

-- --------------------------------------------------------

--
-- Estrutura para tabela `gerente`
--

CREATE TABLE `gerente` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rg` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `gerente`
--

INSERT INTO `gerente` (`id`, `user_id`, `rg`) VALUES
(1, 2, '1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_comissao`
--

CREATE TABLE `historico_comissao` (
  `id` int(11) NOT NULL,
  `garcom_id` int(11) NOT NULL,
  `mes_referencia` date NOT NULL,
  `total_vendido` decimal(10,2) NOT NULL,
  `valor_comissao` decimal(10,2) NOT NULL,
  `data_calculo` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `historico_comissao`
--

INSERT INTO `historico_comissao` (`id`, `garcom_id`, `mes_referencia`, `total_vendido`, `valor_comissao`, `data_calculo`) VALUES
(1, 1, '2025-04-01', 1200.50, 120.05, '2025-05-05 10:00:00'),
(2, 2, '2025-04-01', 850.75, 85.08, '2025-05-05 10:00:00'),
(3, 3, '2025-04-01', 950.25, 95.03, '2025-05-05 10:00:00'),
(4, 4, '2025-04-01', 1100.00, 110.00, '2025-05-05 10:00:00'),
(5, 1, '0000-00-00', 34.00, 3.40, '2025-06-14 21:19:21'),
(6, 2, '0000-00-00', 81.00, 8.10, '2025-06-14 21:19:21'),
(7, 3, '0000-00-00', 64.50, 6.45, '2025-06-14 21:19:21'),
(8, 4, '0000-00-00', 112.00, 11.20, '2025-06-14 21:19:21'),
(9, 4, '0000-00-00', 28.00, 2.80, '2025-06-14 21:59:55'),
(10, 4, '0000-00-00', 28.00, 2.80, '2025-06-14 22:09:52'),
(11, 4, '0000-00-00', 28.00, 2.80, '2025-06-15 18:47:42'),
(12, 4, '0000-00-00', 28.00, 2.80, '2025-06-15 18:48:27'),
(13, 1, '0000-00-00', 29.50, 2.95, '2025-06-15 18:54:19'),
(14, 1, '0000-00-00', 29.50, 2.95, '2025-06-15 22:18:07'),
(15, 3, '2025-06-01', 0.00, 0.00, '2025-06-15 22:49:06'),
(16, 4, '2025-06-01', 28.00, 2.80, '2025-06-15 22:49:06'),
(17, 1, '2025-06-01', 29.50, 2.95, '2025-06-25 18:15:43'),
(18, 2, '2025-06-01', 0.00, 0.00, '2025-06-25 18:15:43'),
(19, 3, '2025-06-01', 0.00, 0.00, '2025-06-29 22:54:30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `conta_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `data_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedido`
--

INSERT INTO `pedido` (`id`, `conta_id`, `produto_id`, `quantidade`, `data_hora`) VALUES
(1, 1, 10, 1, '2025-06-10 10:30:22'),
(2, 2, 11, 2, '2025-05-15 12:35:00'),
(3, 2, 21, 3, '2025-05-15 12:40:00'),
(4, 3, 12, 1, '2025-05-10 10:05:00'),
(5, 3, 33, 1, '2025-05-10 10:10:00'),
(6, 3, 9, 2, '2025-05-10 10:15:00'),
(7, 4, 26, 2, '2025-05-20 18:10:00'),
(8, 4, 27, 1, '2025-05-20 18:15:00'),
(9, 4, 10, 3, '2025-05-20 18:20:00'),
(10, 4, 24, 1, '2025-05-20 18:25:00'),
(11, 5, 15, 1, '2025-05-05 09:05:00'),
(12, 5, 23, 1, '2025-05-05 09:10:00'),
(13, 5, 9, 1, '2025-05-05 09:15:00'),
(14, 6, 36, 1, '2025-05-12 15:05:00'),
(15, 6, 11, 1, '2025-05-12 15:10:00'),
(16, 7, 41, 1, '2025-05-08 14:05:00'),
(17, 7, 18, 1, '2025-05-08 14:10:00'),
(18, 8, 22, 2, '2025-05-25 19:05:00'),
(19, 8, 16, 1, '2025-05-25 19:10:00'),
(20, 8, 28, 2, '2025-05-25 19:15:00'),
(21, 9, 11, 1, '2025-06-01 09:05:00'),
(22, 9, 21, 2, '2025-06-01 09:10:00'),
(23, 9, 9, 1, '2025-06-01 09:15:00'),
(24, 10, 12, 1, '2025-06-02 10:05:00'),
(25, 10, 23, 1, '2025-06-02 10:10:00'),
(26, 11, 33, 1, '2025-06-02 11:05:00'),
(27, 12, 26, 3, '2025-06-03 14:10:00'),
(28, 12, 10, 1, '2025-06-03 14:15:00'),
(29, 12, 24, 1, '2025-06-03 14:20:00'),
(30, 13, 17, 1, '2025-06-03 16:05:00'),
(31, 13, 29, 1, '2025-06-03 16:10:00'),
(32, 14, 37, 1, '2025-06-04 18:05:00'),
(33, 14, 19, 1, '2025-06-04 18:10:00'),
(34, 11, 1, 1, '2025-06-15 19:35:06'),
(35, 15, 37, 1, '2025-06-15 19:36:49'),
(36, 17, 45, 1, '2025-06-16 14:55:22');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `subcategoria_id` int(11) DEFAULT NULL,
  `porcao` varchar(50) DEFAULT NULL,
  `qtd_estoque` int(11) DEFAULT 0,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id`, `nome`, `preco`, `categoria_id`, `subcategoria_id`, `porcao`, `qtd_estoque`, `data_cadastro`) VALUES
(1, 'Café Gelado', 5.50, 1, 1, 'Média', 49, '2025-06-10 13:26:37'),
(2, 'Frapuccino de Café', 8.00, 1, 1, 'Grande', 35, '2025-06-10 13:26:37'),
(3, 'Frapuccino de Chocolate', 8.00, 1, 1, 'Grande', 40, '2025-06-10 13:26:37'),
(4, 'Chá Gelado', 5.00, 1, 1, 'Pequena', 60, '2025-06-10 13:26:37'),
(5, 'Limonada Refrescante', 7.50, 1, 1, 'Média', 45, '2025-06-10 13:26:37'),
(6, 'Milkshake de Morango', 7.50, 1, 1, 'Grande', 30, '2025-06-10 13:26:37'),
(7, 'Milkshake de Chocolate', 4.50, 1, 1, 'Média', 55, '2025-06-10 13:26:37'),
(8, 'Smoothie de Frutas', 5.00, 1, 1, 'Média', 40, '2025-06-10 13:26:37'),
(9, 'Café Expresso', 3.50, 1, 2, 'Pequena', 100, '2025-06-10 13:26:37'),
(10, 'Café Americano', 5.00, 1, 2, 'Média', 79, '2025-06-10 13:26:37'),
(11, 'Cappuccino', 5.00, 1, 2, 'Média', 70, '2025-06-10 13:26:37'),
(12, 'Latte', 5.00, 1, 2, 'Grande', 60, '2025-06-10 13:26:37'),
(13, 'Macchiato', 7.50, 1, 2, 'Média', 65, '2025-06-10 13:26:37'),
(14, 'Mocha', 5.00, 1, 2, 'Grande', 50, '2025-06-10 13:26:37'),
(15, 'Chá Preto', 4.50, 1, 2, 'Pequena', 90, '2025-06-10 13:26:37'),
(16, 'Chá Verde', 5.50, 1, 2, 'Pequena', 85, '2025-06-10 13:26:37'),
(17, 'Affogato', 5.00, 1, 3, 'Pequena', 40, '2025-06-10 13:26:37'),
(18, 'Irish Coffee', 8.50, 1, 3, 'Média', 30, '2025-06-10 13:26:37'),
(19, 'Cold Brew', 5.00, 1, 3, 'Média', 45, '2025-06-10 13:26:37'),
(20, 'Tônica de Café', 7.50, 1, 3, 'Grande', 25, '2025-06-10 13:26:37'),
(21, 'Pão de Queijo', 8.00, 2, 4, 'Pequena', 120, '2025-06-10 13:26:37'),
(22, 'Empada de Frango', 7.50, 2, 4, 'Média', 90, '2025-06-10 13:26:37'),
(23, 'Croissant', 6.50, 2, 4, 'Média', 80, '2025-06-10 13:26:37'),
(24, 'Biscoito de Polvilho', 5.00, 2, 4, 'Pequena', 150, '2025-06-10 13:26:37'),
(25, 'Torta de Frango', 12.00, 2, 4, 'Grande', 60, '2025-06-10 13:26:37'),
(26, 'Coxinha', 6.00, 2, 5, 'Média', 110, '2025-06-10 13:26:37'),
(27, 'Pastel de Carne', 7.00, 2, 5, 'Grande', 75, '2025-06-10 13:26:37'),
(28, 'Bolinha de Queijo', 9.00, 2, 5, 'Pequena', 130, '2025-06-10 13:26:37'),
(29, 'Kibe', 8.50, 2, 5, 'Média', 95, '2025-06-10 13:26:37'),
(30, 'Risole de Presunto', 6.50, 2, 5, 'Média', 85, '2025-06-10 13:26:37'),
(31, 'Bolo de Fubá', 25.00, 3, 6, 'Grande', 20, '2025-06-10 13:26:37'),
(32, 'Bolo de Laranja', 28.00, 3, 6, 'Grande', 18, '2025-06-10 13:26:37'),
(33, 'Bolo de Cenoura', 30.00, 3, 6, 'Grande', 25, '2025-06-10 13:26:37'),
(34, 'Bolo de Chocolate Simples', 32.00, 3, 6, 'Grande', 30, '2025-06-10 13:26:37'),
(35, 'Bolo de Milho', 26.00, 3, 6, 'Média', 22, '2025-06-10 13:26:37'),
(36, 'Red Velvet', 45.00, 3, 7, 'Grande', 15, '2025-06-10 13:26:37'),
(37, 'Bolo de Nutella', 48.00, 3, 7, 'Grande', 11, '2025-06-10 13:26:37'),
(38, 'Cheesecake de Morango', 50.00, 3, 7, 'Média', 18, '2025-06-10 13:26:37'),
(39, 'Bolo Floresta Negra', 52.00, 3, 7, 'Grande', 10, '2025-06-10 13:26:37'),
(40, 'Bolo de Limão Siciliano', 46.00, 3, 7, 'Média', 14, '2025-06-10 13:26:37'),
(41, 'Bolo Trufado', 65.00, 3, 8, 'Grande', 8, '2025-06-10 13:26:37'),
(42, 'Bolo Frutas Vermelhas', 68.00, 3, 8, 'Grande', 10, '2025-06-10 13:26:37'),
(43, 'Bolo Opera', 70.00, 3, 8, 'Grande', 6, '2025-06-10 13:26:37'),
(44, 'Bolo de Champagne', 75.00, 3, 8, 'Grande', 5, '2025-06-10 13:26:37'),
(45, 'Bolo Diamante Negro', 80.00, 3, 8, 'Grande', 6, '2025-06-10 13:26:37');

-- --------------------------------------------------------

--
-- Estrutura para tabela `subcategoria`
--

CREATE TABLE `subcategoria` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `subcategoria`
--

INSERT INTO `subcategoria` (`id`, `categoria_id`, `nome`) VALUES
(1, 1, 'Fria'),
(2, 1, 'Quente'),
(3, 1, 'Especial'),
(4, 2, 'Forno'),
(5, 2, 'Frito'),
(6, 3, 'Tradicionais'),
(7, 3, 'Especiais'),
(8, 3, 'Premium');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` int(11) NOT NULL DEFAULT 0,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `cpf`, `email`, `telefone`, `data_nasc`, `senha`, `tipo`, `data_cadastro`) VALUES
(1, 'Administrador', '12345678901', 'admin@admin.com', NULL, NULL, '$2y$10$E6d.qxxZw.l/RWzm4pBOXOHaA9QJ5QmfWKjoYQeVnnbtEdrxZlvrS', 1, '2025-06-10 13:26:36'),
(2, 'Luis', '66666666666', 'luis@gmail.com', '1', '2025-06-10', '$2y$10$NU7kZZ.P6yPxo63wWdEfHe3c2i.73Q3f9VeU8sxE48ZezV8oedika', 2, '2025-06-10 13:27:21'),
(3, 'Elinardy', '55555555555', 'elinardy@gmail.com', '2', '2025-06-10', '$2y$10$py2sl2661GyJ0rSJpL0y8ed6NemR4wXQA21/gc.KNQBTaHekyPk0e', 3, '2025-06-10 13:28:04'),
(4, 'Carlos Silva', '11111111111', 'carlos@gmail.com', '31987654321', '1990-05-15', '$2y$10$E6d.qxxZw.l/RWzm4pBOXOHaA9QJ5QmfWKjoYQeVnnbtEdrxZlvrS', 3, '2025-05-01 13:00:00'),
(5, 'Ana Oliveira', '22222222222', 'ana@gmail.com', '31987654322', '1988-08-20', '$2y$10$E6d.qxxZw.l/RWzm4pBOXOHaA9QJ5QmfWKjoYQeVnnbtEdrxZlvrS', 3, '2025-05-05 14:00:00'),
(6, 'Pedro Santos', '33333333333', 'pedro@gmail.com', '31987654323', '1992-03-10', '$2y$10$E6d.qxxZw.l/RWzm4pBOXOHaA9QJ5QmfWKjoYQeVnnbtEdrxZlvrS', 3, '2025-05-10 15:00:00');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `conta`
--
ALTER TABLE `conta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `garcom_id` (`garcom_id`);

--
-- Índices de tabela `garcom`
--
ALTER TABLE `garcom`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `gerente`
--
ALTER TABLE `gerente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `historico_comissao`
--
ALTER TABLE `historico_comissao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `garcom_id` (`garcom_id`);

--
-- Índices de tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conta_id` (`conta_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `subcategoria_id` (`subcategoria_id`);

--
-- Índices de tabela `subcategoria`
--
ALTER TABLE `subcategoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `conta`
--
ALTER TABLE `conta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `garcom`
--
ALTER TABLE `garcom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `gerente`
--
ALTER TABLE `gerente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `historico_comissao`
--
ALTER TABLE `historico_comissao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de tabela `subcategoria`
--
ALTER TABLE `subcategoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `conta`
--
ALTER TABLE `conta`
  ADD CONSTRAINT `conta_ibfk_1` FOREIGN KEY (`garcom_id`) REFERENCES `usuario` (`id`);

--
-- Restrições para tabelas `garcom`
--
ALTER TABLE `garcom`
  ADD CONSTRAINT `garcom_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`id`);

--
-- Restrições para tabelas `gerente`
--
ALTER TABLE `gerente`
  ADD CONSTRAINT `gerente_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`id`);

--
-- Restrições para tabelas `historico_comissao`
--
ALTER TABLE `historico_comissao`
  ADD CONSTRAINT `historico_comissao_ibfk_1` FOREIGN KEY (`garcom_id`) REFERENCES `garcom` (`id`);

--
-- Restrições para tabelas `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`conta_id`) REFERENCES `conta` (`id`),
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`);

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`),
  ADD CONSTRAINT `produto_ibfk_2` FOREIGN KEY (`subcategoria_id`) REFERENCES `subcategoria` (`id`);

--
-- Restrições para tabelas `subcategoria`
--
ALTER TABLE `subcategoria`
  ADD CONSTRAINT `subcategoria_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
