-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/06/2025 às 04:20
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
(1, 2, 'Pós-graduação');

-- --------------------------------------------------------

--
-- Estrutura para tabela `gerente`
--

CREATE TABLE `gerente` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rg` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Café Gelado', 5.50, 1, 1, 'Média', 50, '2025-05-31 22:46:32'),
(2, 'Frapuccino de Café', 8.00, 1, 1, 'Grande', 35, '2025-05-31 22:46:32'),
(3, 'Frapuccino de Chocolate', 8.00, 1, 1, 'Grande', 40, '2025-05-31 22:46:32'),
(4, 'Chá Gelado', 5.00, 1, 1, 'Pequena', 60, '2025-05-31 22:46:32'),
(5, 'Limonada Refrescante', 7.50, 1, 1, 'Média', 45, '2025-05-31 22:46:32'),
(6, 'Milkshake de Morango', 7.50, 1, 1, 'Grande', 30, '2025-05-31 22:46:32'),
(7, 'Milkshake de Chocolate', 4.50, 1, 1, 'Média', 55, '2025-05-31 22:46:32'),
(8, 'Smoothie de Frutas', 5.00, 1, 1, 'Média', 40, '2025-05-31 22:46:32'),
(9, 'Café Expresso', 3.50, 1, 2, 'Pequena', 100, '2025-05-31 22:46:32'),
(10, 'Café Americano', 5.00, 1, 2, 'Média', 80, '2025-05-31 22:46:32'),
(11, 'Cappuccino', 5.00, 1, 2, 'Média', 70, '2025-05-31 22:46:32'),
(12, 'Latte', 5.00, 1, 2, 'Grande', 60, '2025-05-31 22:46:32'),
(13, 'Macchiato', 7.50, 1, 2, 'Média', 65, '2025-05-31 22:46:32'),
(14, 'Mocha', 5.00, 1, 2, 'Grande', 50, '2025-05-31 22:46:32'),
(15, 'Chá Preto', 4.50, 1, 2, 'Pequena', 90, '2025-05-31 22:46:32'),
(16, 'Chá Verde', 5.50, 1, 2, 'Pequena', 85, '2025-05-31 22:46:32'),
(17, 'Affogato', 5.00, 1, 3, 'Pequena', 40, '2025-05-31 22:46:32'),
(18, 'Irish Coffee', 8.50, 1, 3, 'Média', 30, '2025-05-31 22:46:32'),
(19, 'Cold Brew', 5.00, 1, 3, 'Média', 45, '2025-05-31 22:46:32'),
(20, 'Tônica de Café', 7.50, 1, 3, 'Grande', 25, '2025-05-31 22:46:32'),
(21, 'Pão de Queijo', 8.00, 2, 4, 'Pequena', 120, '2025-05-31 22:46:32'),
(22, 'Empada de Frango', 7.50, 2, 4, 'Média', 90, '2025-05-31 22:46:32'),
(23, 'Croissant', 6.50, 2, 4, 'Média', 80, '2025-05-31 22:46:32'),
(24, 'Biscoito de Polvilho', 5.00, 2, 4, 'Pequena', 150, '2025-05-31 22:46:32'),
(25, 'Torta de Frango', 12.00, 2, 4, 'Grande', 60, '2025-05-31 22:46:32'),
(26, 'Coxinha', 6.00, 2, 5, 'Média', 110, '2025-05-31 22:46:32'),
(27, 'Pastel de Carne', 7.00, 2, 5, 'Grande', 75, '2025-05-31 22:46:32'),
(28, 'Bolinha de Queijo', 9.00, 2, 5, 'Pequena', 130, '2025-05-31 22:46:32'),
(29, 'Kibe', 8.50, 2, 5, 'Média', 95, '2025-05-31 22:46:32'),
(30, 'Risole de Presunto', 6.50, 2, 5, 'Média', 85, '2025-05-31 22:46:32'),
(31, 'Bolo de Fubá', 25.00, 3, 6, 'Grande', 20, '2025-05-31 22:46:32'),
(32, 'Bolo de Laranja', 28.00, 3, 6, 'Grande', 18, '2025-05-31 22:46:32'),
(33, 'Bolo de Cenoura', 30.00, 3, 6, 'Grande', 25, '2025-05-31 22:46:32'),
(34, 'Bolo de Chocolate Simples', 32.00, 3, 6, 'Grande', 30, '2025-05-31 22:46:32'),
(35, 'Bolo de Milho', 26.00, 3, 6, 'Média', 22, '2025-05-31 22:46:32'),
(36, 'Red Velvet', 45.00, 3, 7, 'Grande', 15, '2025-05-31 22:46:32'),
(37, 'Bolo de Nutella', 48.00, 3, 7, 'Grande', 12, '2025-05-31 22:46:32'),
(38, 'Cheesecake de Morango', 50.00, 3, 7, 'Média', 18, '2025-05-31 22:46:32'),
(39, 'Bolo Floresta Negra', 52.00, 3, 7, 'Grande', 10, '2025-05-31 22:46:32'),
(40, 'Bolo de Limão Siciliano', 46.00, 3, 7, 'Média', 14, '2025-05-31 22:46:32'),
(41, 'Bolo Trufado', 65.00, 3, 8, 'Grande', 8, '2025-05-31 22:46:32'),
(42, 'Bolo Frutas Vermelhas', 68.00, 3, 8, 'Grande', 10, '2025-05-31 22:46:32'),
(43, 'Bolo Opera', 70.00, 3, 8, 'Grande', 6, '2025-05-31 22:46:32'),
(44, 'Bolo de Champagne', 75.00, 3, 8, 'Grande', 5, '2025-05-31 22:46:32'),
(45, 'Bolo Diamante Negro', 80.00, 3, 8, 'Grande', 7, '2025-05-31 22:46:32');

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
(1, 'Administrador', '12345678901', 'admin@admin.com', NULL, NULL, '$2y$10$E6d.qxxZw.l/RWzm4pBOXOHaA9QJ5QmfWKjoYQeVnnbtEdrxZlvrS', 1, '2025-05-31 22:46:32'),
(2, 'Elinardy', '99999999999', 'elinardy@gmail.com', '77777777', '2025-05-31', '$2y$10$FTYXse75Vpk0nu85l/VbhuTxikMfHynz6EKKBTz3UFAMm2HaInoJ2', 3, '2025-06-01 02:00:16');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `garcom`
--
ALTER TABLE `garcom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `gerente`
--
ALTER TABLE `gerente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_comissao`
--
ALTER TABLE `historico_comissao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `subcategoria`
--
ALTER TABLE `subcategoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
