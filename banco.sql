create database locacar;
--
-- Banco de dados: `locacar`
--
use locacar;
-- --------------------------------------------------------

--
-- Estrutura para tabela `carros`
--

CREATE TABLE `carros` (
  `id_carro` int(11) NOT NULL,
  `marca` varchar(30) DEFAULT NULL,
  `placa` varchar(7) DEFAULT NULL,
  `renavan` int(15) NOT NULL,
  `cor` varchar(15) DEFAULT NULL,
  `ano` varchar(4) DEFAULT NULL,
  `modelo` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carros`
--

INSERT INTO `carros` (`id_carro`, `marca`, `placa`, `renavan`, `cor`, `ano`, `modelo`) VALUES
(1, 'chevrolet', 'bdx1b98', 1223726123, 'vermelho', '2020', 'onix');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefone` varchar(11) DEFAULT NULL,
  `cep` int(8) DEFAULT NULL,
  `rua` varchar(100) DEFAULT NULL,
  `numero_casa` varchar(10) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(30) DEFAULT NULL,
  `profissao` varchar(30) DEFAULT NULL,
  `dt_nascimento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `cpf`, `email`, `telefone`, `cep`, `rua`, `numero_casa`, `bairro`, `cidade`, `profissao`, `dt_nascimento`) VALUES
(1, 'tatiane', '0', 'tatianw@exemplo.com', '2147483647', 23585562, 'rosa', '21', 'campo grande', 'rio de janeiro', NULL, '1994-07-12'),
(2, 'tatiane', '00000000000', 'tatianw@exemplo.com', '21999999999', 23585562, 'rosa', '21', 'campo grande', 'rio de janeiro', NULL, '1994-07-12'),
(6, 'maria', '12345678912', 'maria@exemplo.com', '21999999999', 23695852, 'azul', '70', 'centro', 'sao paulo', NULL, '1959-07-25');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carros`
--
ALTER TABLE `carros`
  ADD PRIMARY KEY (`id_carro`),
  ADD UNIQUE KEY `renavan` (`renavan`),
  ADD UNIQUE KEY `placa` (`placa`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carros`
--
ALTER TABLE `carros`
  MODIFY `id_carro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

select * from clientes;

create table alugar (
	id_alugar int auto_increment primary key, 
	id_cliente int, 
	id_carro int, 
	dt_aluguel date, 
	dt_devolucao date,
    foreign key (id_cliente) references clientes(id_cliente),
    foreign key (id_carro) references carros(id_carro)
);

alter table carros 
	add column disponivel BOOLEAN DEFAULT TRUE;
    
ALTER TABLE carros ADD status ENUM('disponível', 'alugado', 'manutenção') DEFAULT 'disponível';

ALTER TABLE carros
	DROP COLUMN disponivel;


