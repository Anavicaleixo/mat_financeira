-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Set-2025 às 14:18
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_mat`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `despesas`
--

CREATE TABLE `despesas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `categoria` enum('alimentacao','transporte','moradia','lazer','saude','outros') DEFAULT 'outros',
  `valor` decimal(15,2) NOT NULL,
  `data_despesa` date NOT NULL,
  `data_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `despesas`
--

INSERT INTO `despesas` (`id`, `usuario_id`, `descricao`, `categoria`, `valor`, `data_despesa`, `data_registro`) VALUES
(3, 1, 'roupa', 'outros', '347.90', '2025-09-30', '2025-09-29 08:54:40'),
(4, 1, 'Alimentação', 'outros', '1000.00', '2025-09-30', '2025-09-29 09:00:31'),
(5, 1, 'Moradia', 'outros', '1000000.00', '2025-09-30', '2025-09-29 09:09:26');

-- --------------------------------------------------------

--
-- Estrutura da tabela `entradas`
--

CREATE TABLE `entradas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_entrada` date NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `financiamentos`
--

CREATE TABLE `financiamentos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('imobiliario','veicular','pessoal') NOT NULL,
  `valor` decimal(15,2) NOT NULL,
  `juros` decimal(5,2) NOT NULL,
  `parcelas` int(11) NOT NULL,
  `prestacao` decimal(15,2) NOT NULL,
  `data_simulacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `financiamentos`
--

INSERT INTO `financiamentos` (`id`, `usuario_id`, `tipo`, `valor`, `juros`, `parcelas`, `prestacao`, `data_simulacao`) VALUES
(1, 1, 'imobiliario', '100.00', '5.00', 5, '23.10', '2025-09-29 08:40:09');

-- --------------------------------------------------------

--
-- Estrutura da tabela `investimentos`
--

CREATE TABLE `investimentos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('renda_fixa','renda_variavel','tesouro','poupanca','outros') NOT NULL,
  `valor_investido` decimal(15,2) NOT NULL,
  `taxa_retorno` decimal(5,2) NOT NULL,
  `prazo` int(11) NOT NULL COMMENT 'Prazo em meses',
  `data_investimento` datetime DEFAULT current_timestamp(),
  `resultado` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `investimentos`
--

INSERT INTO `investimentos` (`id`, `usuario_id`, `tipo`, `valor_investido`, `taxa_retorno`, `prazo`, `data_investimento`, `resultado`) VALUES
(1, 1, '', '2000000000.00', '0.20', 480, '2025-09-29 08:52:13', '2939543135938.1685');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `data_cadastro`) VALUES
(1, 'Manuela Catarina', 'manulinda@gmail.com', '$2y$10$mvETNE0ce92baoeQp2GzFuuy6gPIzAYOf/rmyozdz6J/d1530H6hS', '2025-09-29 08:25:41');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `despesas`
--
ALTER TABLE `despesas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `financiamentos`
--
ALTER TABLE `financiamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `investimentos`
--
ALTER TABLE `investimentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `despesas`
--
ALTER TABLE `despesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `financiamentos`
--
ALTER TABLE `financiamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `investimentos`
--
ALTER TABLE `investimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `despesas`
--
ALTER TABLE `despesas`
  ADD CONSTRAINT `despesas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `financiamentos`
--
ALTER TABLE `financiamentos`
  ADD CONSTRAINT `financiamentos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `investimentos`
--
ALTER TABLE `investimentos`
  ADD CONSTRAINT `investimentos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
