-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 05/05/2020 às 18:33
-- Versão do servidor: 5.6.41-84.1
-- Versão do PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `hugocu75_advocacia`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `advogados`
--

CREATE TABLE `advogados` (
  `id` int(11) NOT NULL,
  `nome` varchar(35) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(35) NOT NULL,
  `especialidade` varchar(35) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `biografia` varchar(350) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `advogados`
--

INSERT INTO `advogados` (`id`, `nome`, `cpf`, `telefone`, `email`, `especialidade`, `foto`, `biografia`) VALUES
(9, 'Advogado Teste', '000.000.000-10', '(00) 00000-0000', 'advogado@hotmail.com', 'Familiar', 'hugo-profile.jpeg', 'Atuo ja hÃ¡ mais de 30 anos no direito familiar, jÃ¡ consegui ganhar centenas de processos, sempre com muito comprometimento e responsabilidade .....'),
(10, 'Pedro Freitas', '777.777.777-77', '(77) 77777-7777', 'pedro@hotmail.com', 'Criminal', '20200127_134456.jpg', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,'),
(12, 'Advgo', '789.999.999-99', '(33) 33333-3333', 'adva@hotmail.com', 'Trabalhista', '20200125_083610.jpg', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,');

-- --------------------------------------------------------

--
-- Estrutura para tabela `audiencias`
--

CREATE TABLE `audiencias` (
  `id` int(11) NOT NULL,
  `processo` varchar(35) NOT NULL,
  `descricao` varchar(35) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `local` varchar(35) NOT NULL,
  `advogado` varchar(20) NOT NULL,
  `cliente` varchar(20) NOT NULL,
  `obs` varchar(350) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `audiencias`
--

INSERT INTO `audiencias` (`id`, `processo`, `descricao`, `data`, `hora`, `local`, `advogado`, `cliente`, `obs`) VALUES
(1, '8888888-88.8888.888.8888', 'AudiÃªncia de ConciliaÃ§Ã£o', '2020-05-05', '20:00:00', 'FÃ³run Lafaiete', '000.000.000-10', '888.888.888-88', ''),
(2, '8795689-78.7878.788.5558', 'AudiÃªncia Acordo', '2020-05-05', '19:00:00', 'Forun X', '000.000.000-10', '111.111.111-11', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargos`
--

CREATE TABLE `cargos` (
  `id` int(11) NOT NULL,
  `nome` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `cargos`
--

INSERT INTO `cargos` (`id`, `nome`) VALUES
(1, 'Advogado'),
(2, 'Tesoureiro'),
(3, 'Recepcionista'),
(4, 'Motoboy');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(35) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(35) NOT NULL,
  `endereco` varchar(50) NOT NULL,
  `advogado` varchar(20) NOT NULL,
  `data` date NOT NULL,
  `obs` varchar(350) NOT NULL,
  `tipo_pessoa` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `cpf`, `telefone`, `email`, `endereco`, `advogado`, `data`, `obs`, `tipo_pessoa`) VALUES
(1, 'Marcos Souza', '111.111.111-11', '(22) 22222-2222', 'marcos@gmail.com', 'Rua A', '000.000.000-10', '2020-04-27', 'Rua A', 'Pessoa Jurídica'),
(3, 'Matheus Campos', '112.332.556-66', '(66) 66666-6666', 'mateus@hotmail.com', 'Rua 5', '000.000.000-10', '2020-04-28', 'aa', 'Pessoa Física'),
(4, 'Empresa X', '11.212.122/2121-23', '(33) 33333-3333', 'empresa@hotmail.com', 'Rua A', '000.000.000-10', '2020-04-28', 'AAAAA', 'Pessoa Jurídica'),
(10, 'Hugo Vasconcelos', '888.888.888-88', '(88) 88888-8888', 'hugovasconcelosf@hotmail.com', 'Rua A', '000.000.000-10', '2020-05-05', 'aaaa', 'Pessoa FÃ­sica');

-- --------------------------------------------------------

--
-- Estrutura para tabela `documentos`
--

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `data` date NOT NULL,
  `processo` varchar(30) NOT NULL,
  `arquivo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `documentos`
--

INSERT INTO `documentos` (`id`, `descricao`, `data`, `processo`, `arquivo`) VALUES
(1, 'Identidade', '2020-05-04', '8795689-78.7878.788.5558', 'SITE + SISTEMA + APP.jpg'),
(7, 'Documento de identidade', '2020-05-05', '8888888-88.8888.888.8888', 'curso-de-sistema-gestao-advocacia.jpeg'),
(8, 'Documento do Processo', '2020-05-05', '8888888-88.8888.888.8888', 'modelos-de-peticoes-gratis-peticoesonline.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `especialidades`
--

CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL,
  `nome` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `especialidades`
--

INSERT INTO `especialidades` (`id`, `nome`) VALUES
(1, 'Criminal'),
(2, 'Trabalhista'),
(3, 'Familiar');

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(35) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `cargo` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id`, `nome`, `cpf`, `telefone`, `email`, `cargo`) VALUES
(20, 'Advogado Teste', '000.000.000-10', '(00) 00000-0000', 'advogado@hotmail.com', 'Advogado'),
(22, 'Pedro Freitas', '777.777.777-77', '(77) 77777-7777', 'pedro@hotmail.com', 'Advogado'),
(24, 'Advgo', '789.999.999-99', '(33) 33333-3333', 'adva@hotmail.com', 'Advogado'),
(25, 'Tesoureiro', '111.111.111-11', '(11) 11111-1111', 'tesoureiro@hotmail.c', 'Tesoureiro'),
(27, 'Paloma Campos', '444.444.444-44', '(22) 22222-2222', 'paloma@hotmail.com', 'Recepcionista');

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico`
--

CREATE TABLE `historico` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `obs` varchar(350) DEFAULT NULL,
  `data` date NOT NULL,
  `processo` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `historico`
--

INSERT INTO `historico` (`id`, `titulo`, `obs`, `data`, `processo`) VALUES
(2, 'Entrega de Documentos', 'Foram Entregados documentos que estavam pendentes para ....', '2020-05-04', '8795689-78.7878.788.5558'),
(4, 'Movimentação Dois', 'Segunda movimentação para o processo', '2020-05-13', '8795689-78.7878.788.5558'),
(5, 'Documentos no Forun', 'Ouve uma movimentaÃ§Ã£o no processo, os documentos...', '2020-05-05', '8888888-88.8888.888.8888'),
(6, 'Entrada nos Documentos', 'Na Data de hoje entramos com os documentos para o processo, agora Ã© sÃ³ ....', '2020-05-05', '8888888-88.8888.888.8888');

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacoes`
--

CREATE TABLE `movimentacoes` (
  `id` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `movimento` varchar(35) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `tesoureiro` varchar(20) NOT NULL,
  `data` date NOT NULL,
  `id_movimento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `movimentacoes`
--

INSERT INTO `movimentacoes` (`id`, `tipo`, `movimento`, `valor`, `tesoureiro`, `data`, `id_movimento`) VALUES
(1, 'Entrada', 'Processo', '1800.00', '444.444.444-44', '2020-04-29', 2),
(2, 'Entrada', 'Processo', '1450.00', '444.444.444-44', '2020-04-29', 3),
(3, 'Saída', 'Pagamento Conta', '560.00', '444.444.444-44', '2020-04-29', 1),
(4, 'Saída', 'Pgto Funcionário', '2500.00', '444.444.444-44', '2020-04-29', 1),
(6, 'Saída', 'Pgto Funcionário', '850.00', '444.444.444-44', '2020-04-29', 3),
(7, 'Saída', 'Pgto Funcionário', '680.00', '444.444.444-44', '2020-04-30', 4),
(8, 'Entrada', 'Processo', '1900.00', '111.111.111-11', '2020-04-30', 4),
(9, 'Saída', 'Pagamento Conta', '690.00', '111.111.111-11', '2020-04-30', 6),
(10, 'Saída', 'Pgto Funcionário', '1600.00', '111.111.111-11', '2020-04-30', 5),
(11, 'Entrada', 'Processo', '2500.00', '444.444.444-44', '2020-04-30', 5),
(12, 'Saída', 'Pgto Funcionário', '1800.00', '444.444.444-44', '2020-05-04', 6),
(13, 'SaÃ­da', 'Pgto FuncionÃ¡rio', '1250.00', '444.444.444-44', '2020-05-05', 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos`
--

CREATE TABLE `pagamentos` (
  `id` int(11) NOT NULL,
  `funcionario` varchar(20) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `tesoureiro` varchar(20) NOT NULL,
  `data` date NOT NULL,
  `nome_funcionario` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pagamentos`
--

INSERT INTO `pagamentos` (`id`, `funcionario`, `valor`, `tesoureiro`, `data`, `nome_funcionario`) VALUES
(1, '000.000.000-10', '2500.00', '444.444.444-44', '2020-04-29', 'Advogado Teste'),
(3, '444.444.444-44', '850.00', '444.444.444-44', '2020-04-29', 'Paloma Campos'),
(4, '444.444.444-44', '680.00', '444.444.444-44', '2020-04-30', 'Paloma Campos'),
(5, '111.111.111-11', '1600.00', '111.111.111-11', '2020-04-30', 'Tesoureiro'),
(6, '789.999.999-99', '1800.00', '444.444.444-44', '2020-05-04', 'Advgo'),
(7, '444.444.444-44', '1250.00', '444.444.444-44', '2020-05-05', 'Paloma Campos');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagar`
--

CREATE TABLE `pagar` (
  `id` int(11) NOT NULL,
  `descricao` varchar(30) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `vencimento` date NOT NULL,
  `pagamento` date DEFAULT NULL,
  `pago` varchar(5) NOT NULL,
  `funcionario` varchar(20) NOT NULL,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `pagar`
--

INSERT INTO `pagar` (`id`, `descricao`, `valor`, `vencimento`, `pagamento`, `pago`, `funcionario`, `foto`) VALUES
(1, 'Conta de Luz', '560.00', '2020-04-29', '2020-04-29', 'Sim', '444.444.444-44', 'curso-de-sistema-de-controle-de-cobrancas.jpeg'),
(3, 'Conta de Água', '350.00', '2020-04-29', NULL, 'Não', '444.444.444-44', 'sem-foto.png'),
(4, 'Pagamento Eletrecista', '450.00', '2020-04-29', NULL, 'Não', '444.444.444-44', 'sem-foto.png'),
(5, 'Conta de Telefone', '890.00', '2020-04-29', NULL, 'Não', '111.111.111-11', 'conta3.jpg'),
(6, 'Conta de Água', '690.00', '2020-04-30', '2020-04-30', 'Sim', '111.111.111-11', 'conta3.jpg'),
(7, 'Conta de Luz', '980.00', '2020-04-30', NULL, 'Não', '444.444.444-44', 'sem-foto.png'),
(8, 'Conta de Água', '590.00', '2020-04-30', NULL, 'Não', '444.444.444-44', 'sem-foto.png'),
(9, 'Eletrecista', '550.00', '2020-04-30', NULL, 'Não', '444.444.444-44', 'sem-foto.png'),
(10, 'Compra de Cadeiras', '890.00', '2020-04-30', NULL, 'Não', '444.444.444-44', 'sem-foto.png'),
(12, 'Conta de Luz', '1400.00', '2020-05-04', NULL, 'Não', '444.444.444-44', 'sem-foto.png'),
(13, 'Conta de Luz', '680.00', '2020-05-05', NULL, 'NÃ£o', '444.444.444-44', 'sem-foto.png'),
(14, 'Conta de Ãgua', '890.00', '2020-05-05', NULL, 'NÃ£o', '444.444.444-44', 'sem-foto.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `peticoes`
--

CREATE TABLE `peticoes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) DEFAULT NULL,
  `acao` varchar(50) DEFAULT NULL,
  `processo` varchar(35) DEFAULT NULL,
  `agravante` varchar(35) DEFAULT NULL,
  `agravado` varchar(35) DEFAULT NULL,
  `texto` varchar(1500) DEFAULT NULL,
  `data` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `peticoes`
--

INSERT INTO `peticoes` (`id`, `titulo`, `acao`, `processo`, `agravante`, `agravado`, `texto`, `data`) VALUES
(1, 'PetiÃ§Ã£o Criminal', 'Criminal', '8888888-88.8888.888.8888', 'Marcio Silva', 'Silverio Campos', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\r\n\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2020-05-05'),
(3, 'zzzzzzzaaa', 'ccccccccc', '8795689-78.7878.788.5558', 'ccccccccccc', 'ccccccccc', 'ccccccccc', '2020-05-05'),
(4, 'PetiÃ§Ã£o Inicial CÃ­vil', 'Civil', '7878999-99.9999.999.9999', 'Pedro Campos Souza', 'Marcos Pedro Silva', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2020-05-05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `processados`
--

CREATE TABLE `processados` (
  `id` int(11) NOT NULL,
  `nome` varchar(35) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(35) NOT NULL,
  `endereco` varchar(50) NOT NULL,
  `advogado` varchar(20) NOT NULL,
  `data` date NOT NULL,
  `obs` varchar(350) NOT NULL,
  `tipo_pessoa` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `processados`
--

INSERT INTO `processados` (`id`, `nome`, `cpf`, `telefone`, `email`, `endereco`, `advogado`, `data`, `obs`, `tipo_pessoa`) VALUES
(1, 'Paola Silva', '444.445.566-66', '(55) 55555-5555', 'paola@hotmail.com', 'Rua 5', '000.000.000-10', '2020-04-28', 'Nenhuma', 'Pessoa Física'),
(2, 'Empreza Z', '44.444.444/4444-44', '(66) 66666-6666', 'emprezaz@hotmail.com', 'Rua 5', '000.000.000-10', '2020-04-28', 'Nenhuma', 'Pessoa Jurídica');

-- --------------------------------------------------------

--
-- Estrutura para tabela `processos`
--

CREATE TABLE `processos` (
  `id` int(11) NOT NULL,
  `num_processo` varchar(35) DEFAULT NULL,
  `vara` varchar(35) NOT NULL,
  `tipo_acao` varchar(35) NOT NULL,
  `advogado` varchar(20) NOT NULL,
  `cliente` varchar(20) NOT NULL,
  `processado` varchar(20) DEFAULT NULL,
  `data_audiencia` date DEFAULT NULL,
  `hora_audiencia` time DEFAULT NULL,
  `data_peticao` date DEFAULT NULL,
  `data_abertura` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `tipo_pessoa` varchar(20) NOT NULL,
  `audiencias` int(11) NOT NULL,
  `obs` varchar(350) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `processos`
--

INSERT INTO `processos` (`id`, `num_processo`, `vara`, `tipo_acao`, `advogado`, `cliente`, `processado`, `data_audiencia`, `hora_audiencia`, `data_peticao`, `data_abertura`, `status`, `tipo_pessoa`, `audiencias`, `obs`) VALUES
(1, '5555555-55.5555.555.5555', '2', '3', '000.000.000-10', '11.212.122/2121-23', '444.445.566-66', '2020-04-30', '12:30:00', '2020-04-28', '2020-04-28', 'Andamento', 'Pessoa Física', 9, NULL),
(2, '2222222-22.2222.222.2229', '2', '3', '000.000.000-10', '112.332.556-66', '444.445.566-66', '2020-04-29', '10:30:00', '0000-00-00', '2020-04-28', 'Cancelado', 'Pessoa Física', 3, NULL),
(3, '7878999-99.9999.999.9999', '2', '2', '000.000.000-10', '111.111.111-11', '44.444.444/4444-44', '2020-05-04', '11:10:00', '2020-05-05', '2020-04-28', 'ConcluÃ­do', 'Pessoa Jurídica', 4, NULL),
(4, '8795689-78.7878.788.5558', '2', '3', '000.000.000-10', '111.111.111-11', '44.444.444/4444-44', '2020-05-05', '19:00:00', '2020-05-05', '2020-04-28', 'Andamento', 'Pessoa Jurídica', 6, 'Esta é a observação do Processo xxxx Esta é a observação do Processo xxxx Esta é a observação do Processo xxxx Esta é a observação do Processo xxxx '),
(5, '8888888-88.8888.888.8888', '1', '1', '000.000.000-10', '888.888.888-88', '444.445.566-66', '2020-05-05', '20:00:00', '2020-05-05', '2020-05-05', 'Aberto', 'Pessoa FÃ­sica', 2, 'Documentos despachados para o FÃ³run, aguardando anÃ¡lise.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `receber`
--

CREATE TABLE `receber` (
  `id` int(11) NOT NULL,
  `descricao` varchar(35) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `advogado` varchar(20) NOT NULL,
  `cliente` varchar(20) NOT NULL,
  `data` date NOT NULL,
  `pago` varchar(5) NOT NULL,
  `processo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `receber`
--

INSERT INTO `receber` (`id`, `descricao`, `valor`, `advogado`, `cliente`, `data`, `pago`, `processo`) VALUES
(2, 'Custas Iniciais', '1800.00', '000.000.000-10', '111.111.111-11', '2020-04-29', 'sim', 3),
(3, 'Custas Iniciais', '1450.00', '000.000.000-10', '112.332.556-66', '2020-04-29', 'sim', 2),
(4, 'Custas Iniciais', '1900.00', '000.000.000-10', '111.111.111-11', '2020-04-30', 'sim', 3),
(5, 'Custas Iniciais', '2500.00', '000.000.000-10', '111.111.111-11', '2020-04-30', 'sim', 4),
(6, 'Custas de Documentos', '1500.00', '000.000.000-10', '111.111.111-11', '2020-05-04', 'nao', 4),
(7, 'Custas Iniciais', '1600.00', '000.000.000-10', '888.888.888-88', '2020-05-05', 'nao', 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefas`
--

CREATE TABLE `tarefas` (
  `id` int(11) NOT NULL,
  `nome` varchar(25) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `data` date NOT NULL,
  `hora` time DEFAULT NULL,
  `advogado` varchar(20) NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `tarefas`
--

INSERT INTO `tarefas` (`id`, `nome`, `descricao`, `data`, `hora`, `advogado`, `status`) VALUES
(1, 'Reunião com Cliente', 'Marcos Paulo', '2020-04-27', '16:30:00', '000.000.000-10', 'Agendada'),
(7, 'Encontar com Cliente', 'Empresa SysMedical', '2020-04-27', '15:30:00', '000.000.000-10', 'Agendada'),
(8, 'Ir ao Fórum', 'Verificar Papelada', '2020-04-27', '16:50:00', '000.000.000-10', 'Agendada'),
(9, 'Almoço com Cliente', 'Marta Silva', '2020-04-27', '12:30:00', '000.000.000-10', 'Agendada'),
(10, 'Teste', 'aaaaa', '2020-04-29', '12:00:00', '000.000.000-10', 'Agendada'),
(11, 'Reunião com Cliente', 'Marcos Paulo', '2020-04-28', '10:30:00', '000.000.000-10', 'Concluida'),
(12, 'Almoço com Cliente', 'Marcia Correa', '2020-04-28', '12:30:00', '000.000.000-10', 'Agendada'),
(13, 'Visita a Empresa X', 'Fechamento de Contrato', '2020-04-28', '17:20:00', '000.000.000-10', 'Agendada'),
(14, 'Ir ao Fórum', 'Atraso nos documentos', '2020-04-28', '15:30:00', '000.000.000-10', 'Agendada'),
(15, 'Reunião com Cliente', 'Marcos Paulo', '2020-04-30', '10:30:00', '000.000.000-10', 'Agendada'),
(16, 'Almoço com Cliente', 'Marcos Paulo', '2020-04-30', '12:30:00', '000.000.000-10', 'Agendada'),
(17, 'Ir ao Fórum', 'Fórun Lafaeite', '2020-04-30', '13:30:00', '000.000.000-10', 'Agendada'),
(18, 'ReuniÃ£o com Cliente', 'AlmoÃ§o na casa do cliente', '2020-05-05', '12:30:00', '000.000.000-10', 'Agendada'),
(19, 'Ir ao FÃ³rum', 'Levar Documentos', '2020-05-05', '20:30:00', '000.000.000-10', 'Agendada');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(35) NOT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `usuario` varchar(35) NOT NULL,
  `senha` varchar(150) NOT NULL,
  `senha_original` varchar(20) NOT NULL,
  `nivel` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `cpf`, `usuario`, `senha`, `senha_original`, `nivel`) VALUES
(2, 'Administrador', '000.000.000-00', 'hvfadvocacia@gmail.com', '202cb962ac59075b964b07152d234b70', '123', 'admin'),
(14, 'Advogado Teste', '000.000.000-10', 'advogado@hotmail.com', '202cb962ac59075b964b07152d234b70', '123', 'Advogado'),
(16, 'Pedro Freitas', '777.777.777-77', 'pedro@hotmail.com', '202cb962ac59075b964b07152d234b70', '123', 'Advogado'),
(17, 'Advgo', '789.999.999-99', 'adva@hotmail.com', '202cb962ac59075b964b07152d234b70', '123', 'Advogado'),
(18, 'Marcos Souza', '111.111.111-11', 'marcos@gmail.com', '202cb962ac59075b964b07152d234b70', '123', 'Cliente'),
(20, 'Matheus Campos', '112.332.556-66', 'mateus@hotmail.com', 'ad8df87f9d73022bc9a5d95e3539bcda', '11233255666', 'Cliente'),
(21, 'Empresa X', '11.212.122/2121-23', 'empresa@hotmail.com', '149f0f9c61a9a38e352f05eeb2f03a48', '11212122212123', 'Cliente'),
(26, 'Tesoureiro', '111.111.111-11', 'tesoureiro@hotmail.com', '202cb962ac59075b964b07152d234b70', '123', 'Tesoureiro'),
(27, 'Paloma Campos', '444.444.444-44', 'paloma@hotmail.com', '202cb962ac59075b964b07152d234b70', '123', 'Recepcionista'),
(29, 'Hugo Vasconcelos', '888.888.888-88', 'hugovasconcelosf@hotmail.com', '202cb962ac59075b964b07152d234b70', '123', 'Cliente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `varas`
--

CREATE TABLE `varas` (
  `id` int(11) NOT NULL,
  `nome` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `varas`
--

INSERT INTO `varas` (`id`, `nome`) VALUES
(1, 'Vara 1'),
(2, 'Vara 2'),
(3, 'Vara 3');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `advogados`
--
ALTER TABLE `advogados`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `audiencias`
--
ALTER TABLE `audiencias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `movimentacoes`
--
ALTER TABLE `movimentacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pagar`
--
ALTER TABLE `pagar`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `peticoes`
--
ALTER TABLE `peticoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `processados`
--
ALTER TABLE `processados`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `processos`
--
ALTER TABLE `processos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `receber`
--
ALTER TABLE `receber`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tarefas`
--
ALTER TABLE `tarefas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `varas`
--
ALTER TABLE `varas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `advogados`
--
ALTER TABLE `advogados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `audiencias`
--
ALTER TABLE `audiencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `movimentacoes`
--
ALTER TABLE `movimentacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `pagar`
--
ALTER TABLE `pagar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `peticoes`
--
ALTER TABLE `peticoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `processados`
--
ALTER TABLE `processados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `processos`
--
ALTER TABLE `processos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `receber`
--
ALTER TABLE `receber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tarefas`
--
ALTER TABLE `tarefas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `varas`
--
ALTER TABLE `varas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
