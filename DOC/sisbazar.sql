-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 19-Maio-2020 às 22:41
-- Versão do servidor: 5.7.26
-- versão do PHP: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sisbazar`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `banco`
--

DROP TABLE IF EXISTS `banco`;
CREATE TABLE IF NOT EXISTS `banco` (
  `codigo` varchar(8) NOT NULL,
  `nome_banco` varchar(200) NOT NULL,
  `site` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `banco`
--

INSERT INTO `banco` (`codigo`, `nome_banco`, `site`) VALUES
('654', 'Banco A.J.Renner S.A.', 'www.bancorenner.com.br'),
('246', 'Banco ABC Brasil S.A.', 'www.abcbrasil.com.br'),
('75', 'Banco ABN AMRO S.A.', 'www.abnamro.com'),
('121', 'Banco Agibank S.A.', 'www.agibank.com.br'),
('25', 'Banco Alfa S.A.', 'www.bancoalfa.com.br'),
('641', 'Banco Alvorada S.A.', 'Não possui site'),
('65', 'Banco Andbank (Brasil) S.A.', 'www.andbank-lla.com.br'),
('213', 'Banco Arbi S.A.', 'www.arbi.com.br'),
('96', 'Banco B3 S.A.', 'www.bmfbovespa.com.br/bancobmfbovespa/'),
('24', 'Banco BANDEPE S.A.', 'www.santander.com.br'),
('318', 'Banco BMG S.A.', 'www.bancobmg.com.br'),
('752', 'Banco BNP Paribas Brasil S.A.', 'www.bnpparibas.com.br'),
('107', 'Banco BOCOM BBM S.A.', 'www.bancobbm.com.br'),
('63', 'Banco Bradescard S.A.', 'www.ibi.com.br'),
('36', 'Banco Bradesco BBI S.A.', 'Não possui site'),
('122', 'Banco Bradesco BERJ S.A.', ''),
('204', 'Banco Bradesco Cartões S.A.', 'não tem site'),
('394', 'Banco Bradesco Financiamentos S.A.', 'não tem site'),
('237', 'Banco Bradesco S.A.', 'www.bradesco.com.br'),
('218', 'Banco BS2 S.A.', 'www.bs2.com/banco/'),
('208', 'Banco BTG Pactual S.A.', 'www.btgpactual.com'),
('473', 'Banco Caixa Geral - Brasil S.A.', 'www.bcgbrasil.com.br'),
('412', 'Banco Capital S.A.', 'www.bancocapital.com.br'),
('40', 'Banco Cargill S.A.', 'www.bancocargill.com.br'),
('266', 'Banco Cédula S.A.', 'www.bancocedula.com.br'),
('739', 'Banco Cetelem S.A.', 'www.cetelem.com.br'),
('233', 'Banco Cifra S.A.', 'www.bancocifra.com.br'),
('745', 'Banco Citibank S.A.', 'www.citibank.com.br'),
('241', 'Banco Clássico S.A.', 'www.bancoclassico.com.br'),
('756', 'Banco Cooperativo do Brasil S.A. - BANCOOB', 'www.bancoob.com.br'),
('748', 'Banco Cooperativo Sicredi S.A.', 'www.sicredi.com.br'),
('222', 'Banco Credit Agricole Brasil S.A.', 'www.calyon.com.br'),
('505', 'Banco Credit Suisse (Brasil) S.A.', 'www.csfb.com'),
('69', 'Banco Crefisa S.A.', 'www.crefisa.com.br'),
('3', 'Banco da Amazônia S.A.', 'www.bancoamazonia.com.br'),
('83', 'Banco da China Brasil S.A.', 'www.boc-brazil.com'),
('707', 'Banco Daycoval S.A.', 'www.daycoval.com.br'),
('51', 'Banco de Desenvolvimento do Espírito Santo S.A.', ''),
('300', 'Banco de La Nacion Argentina', 'www.bna.com.ar'),
('495', 'Banco de La Provincia de Buenos Aires', 'www.bapro.com.ar'),
('494', 'Banco de La Republica Oriental del Uruguay', 'www.bancorepublica.com.uy'),
('1', 'Banco do Brasil S.A.', 'www.bb.com.br'),
('47', 'Banco do Estado de Sergipe S.A.', 'www.banese.com.br'),
('37', 'Banco do Estado do Pará S.A.', 'www.banpara.b.br'),
('41', 'Banco do Estado do Rio Grande do Sul S.A.', 'www.banrisul.com.br'),
('4', 'Banco do Nordeste do Brasil S.A.', 'www.banconordeste.gov.br'),
('265', 'Banco Fator S.A.', 'www.fator.com.br'),
('224', 'Banco Fibra S.A.', 'www.bancofibra.com.br'),
('626', 'Banco Ficsa S.A.', 'www.ficsa.com.br'),
('94', 'Banco Finaxis S.A.', 'www.bancofinaxis.com.br'),
('612', 'Banco Guanabara S.A.', 'www.bancoguanabara.com.br'),
('12', 'Banco Inbursa S.A.', 'www.bancoinbursa.com'),
('604', 'Banco Industrial do Brasil S.A.', 'www.bancoindustrial.com.br'),
('653', 'Banco Indusval S.A.', 'www.bip.b.br'),
('77', 'Banco Inter S.A.', 'www.bancointer.com.br'),
('630', 'Banco Intercap S.A.', ''),
('249', 'Banco Investcred Unibanco S.A.', 'Não possui site'),
('184', 'Banco Itaú BBA S.A.', 'www.itaubba.com.br'),
('29', 'Banco Itaú Consignado S.A.', ''),
('479', 'Banco ItauBank S.A', 'www.itaubank.com.br'),
('376', 'Banco J. P. Morgan S.A.', 'www.jpmorgan.com'),
('74', 'Banco J. Safra S.A.', 'www.safra.com.br'),
('217', 'Banco John Deere S.A.', 'www.johndeere.com.br'),
('76', 'Banco KDB S.A.', 'www.bancokdb.com.br'),
('757', 'Banco KEB HANA do Brasil S.A.', 'www.bancokeb.com.br'),
('600', 'Banco Luso Brasileiro S.A.', 'www.lusobrasileiro.com.br'),
('243', 'Banco Máxima S.A.', 'www.bancomaxima.com.br'),
('720', 'Banco Maxinvest S.A.', 'www.bancomaxinvest.com.br'),
('389', 'Banco Mercantil do Brasil S.A.', 'www.mercantil.com.br'),
('370', 'Banco Mizuho do Brasil S.A.', 'www.mizuhobank.com/brazil/pt/'),
('746', 'Banco Modal S.A.', 'www.bancomodal.com.br'),
('66', 'Banco Morgan Stanley S.A.', 'www.morganstanley.com.br'),
('456', 'Banco MUFG Brasil S.A.', 'www.br.bk.mufg.jp'),
('7', 'Banco Nacional de Desenvolvimento Econômico e Social - BNDES', 'www.bndes.gov.br'),
('169', 'Banco Olé Bonsucesso Consignado S.A.', 'www.oleconsignado.com.br'),
('79', 'Banco Original do Agronegócio S.A.', 'www.original.com.br'),
('212', 'Banco Original S.A.', 'www.original.com.br'),
('712', 'Banco Ourinvest S.A.', 'www.ourinvest.com.br'),
('623', 'Banco PAN S.A.', 'www.bancopan.com.br'),
('611', 'Banco Paulista S.A.', 'www.bancopaulista.com.br'),
('643', 'Banco Pine S.A.', 'www.pine.com'),
('658', 'Banco Porto Real de Investimentos S.A.', ''),
('747', 'Banco Rabobank International Brasil S.A.', 'www.rabobank.com.br'),
('633', 'Banco Rendimento S.A.', 'www.rendimento.com.br'),
('741', 'Banco Ribeirão Preto S.A.', 'www.brp.com.br'),
('120', 'Banco Rodobens S.A.', 'www.rodobens.com.br'),
('422', 'Banco Safra S.A.', 'www.safra.com.br'),
('33', 'Banco Santander  (Brasil)  S.A.', 'www.santander.com.br'),
('743', 'Banco Semear S.A.', 'www.bancosemear.com.br'),
('754', 'Banco Sistema S.A.', 'www.btgpactual.com'),
('366', 'Banco Société Générale Brasil S.A.', 'www.sgbrasil.com.br'),
('637', 'Banco Sofisa S.A.', 'www.sofisa.com.br'),
('464', 'Banco Sumitomo Mitsui Brasileiro S.A.', 'www.smbcgroup.com.br'),
('82', 'Banco Topázio S.A.', 'www.bancotopazio.com.br'),
('634', 'Banco Triângulo S.A.', 'www.tribanco.com.br'),
('18', 'Banco Tricury S.A.', 'www.bancotricury.com.br'),
('655', 'Banco Votorantim S.A.', 'www.bancovotorantim.com.br'),
('610', 'Banco VR S.A.', 'www.vrinvestimentos.com.br'),
('119', 'Banco Western Union do Brasil S.A.', 'www.bancowesternunion.com.br'),
('124', 'Banco Woori Bank do Brasil S.A.', 'www.wooribank.com.br'),
('81', 'BancoSeguro S.A.', 'www.rendimento.com.br'),
('21', 'BANESTES S.A. Banco do Estado do Espírito Santo', 'www.banestes.com.br'),
('755', 'Bank of America Merrill Lynch Banco Múltiplo S.A.', 'www.ml.com'),
('250', 'BCV - Banco de Crédito e Varejo S.A.', 'www.bancobcv.com.br'),
('144', 'BEXS Banco de Câmbio S.A.', 'www.bexs.com.br'),
('17', 'BNY Mellon Banco S.A.', 'www.bnymellon.com.br'),
('126', 'BR Partners Banco de Investimento S.A.', 'www.brap.com.br'),
('125', 'Brasil Plural S.A. - Banco Múltiplo', 'www.brasilplural.com'),
('70', 'BRB - Banco de Brasília S.A.', 'www.brb.com.br'),
('92', 'Brickell S.A. Crédito, Financiamento e Investimento', 'www.brickellcfi.com.br'),
('104', 'Caixa Econômica Federal', 'www.caixa.gov.br'),
('114-7', 'Central das Cooperativas de Economia e Crédito Mútuo do Estado do Espírito Santo Ltda.', 'www.cecoop.com.br'),
('320', 'China Construction Bank (Brasil) Banco Múltiplo S.A.', 'www.br.ccb.com'),
('477', 'Citibank N.A.', 'www.citibank.com'),
('163', 'Commerzbank Brasil S.A. - Banco Múltiplo', 'www.commerzbank.com.br'),
('136', 'CONFEDERACAO NACIONAL DAS COOPERATIVAS CENTRAIS UNICREDS Ltda.', 'www.unicred.com.br/'),
('97', 'Cooperativa Central de Crédito Noroeste Brasileiro Ltda.', 'www.credisis.com.br'),
('085-x', 'Cooperativa Central de Crédito Urbano-CECRED', 'www.cecred.coop.br/'),
('090-2', 'Cooperativa Central de Economia e Crédito Mutuo - SICOOB UNIMAIS', 'www.sicoobunimais.com/'),
('087-6', 'Cooperativa Central de Economia e Crédito Mútuo das Unicreds de Santa Catarina e Paraná', 'www.unicred.com.br/centralscpr/'),
('089-2', 'Cooperativa de Crédito Rural da Região da Mogiana', 'www.credisan.com.br'),
('098-1', 'CREDIALIANÇA COOPERATIVA DE CRÉDITO RURAL', 'www.credialianca.com.br'),
('487', 'Deutsche Bank S.A. - Banco Alemão', 'www.deutsche-bank.com.br'),
('64', 'Goldman Sachs do Brasil Banco Múltiplo S.A.', 'www.goldmansachs.com'),
('135', 'Gradual Corretora de Câmbio,Títulos e Valores Mobiliários S.A.', 'www.gradualcorretora.com.br'),
('78', 'Haitong Banco de Investimento do Brasil S.A.', 'www.haitongib.com.br'),
('62', 'Hipercard Banco Múltiplo S.A.', 'www.hipercard.com.br'),
('269', 'HSBC Brasil S.A. - Banco de Investimento', ''),
('132', 'ICBC do Brasil Banco Múltiplo S.A.', 'www.icbcbr.com.br'),
('492', 'ING Bank N.V.', 'www.ing.com'),
('139', 'Intesa Sanpaolo Brasil S.A. - Banco Múltiplo', 'www.intesasanpaolobrasil.com.br'),
('652', 'Itaú Unibanco Holding S.A.', 'www.itau.com.br'),
('341', 'Itaú Unibanco S.A.', 'www.itau.com.br'),
('488', 'JPMorgan Chase Bank, National Association', 'www.jpmorganchase.com'),
('399', 'Kirton Bank S.A. - Banco Múltiplo', ''),
('128', 'MS Bank S.A. Banco de Câmbio', 'www.msbank.com.br'),
('14', 'Natixis Brasil S.A. Banco Múltiplo', 'www.br.natixis.com'),
('753', 'Novo Banco Continental S.A. - Banco Múltiplo', 'www.nbcbank.com.br'),
('613', 'Omni Banco S.A.', 'www.bancopecunia.com.br'),
('254', 'Paraná Banco S.A.', 'www.paranabanco.com.br'),
('751', 'Scotiabank Brasil S.A. Banco Múltiplo', 'www.br.scotiabank.com'),
('118', 'Standard Chartered Bank (Brasil) S/A–Bco Invest.', 'www.standardchartered.com'),
('95', 'Travelex Banco de Câmbio S.A.', 'www.bancoconfidence.com.br'),
('129', 'UBS Brasil Banco de Investimento S.A.', 'www.ubs.com'),
('091-4', 'Unicred Central do Rio Grande do Sul', 'www.unicred-rs.com.br'),
('84', 'Uniprime Norte do Paraná - Coop de Economia e Crédito Mútuo dos Médicos, Profissionais das Ciências', 'www.uniprimebr.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `caixa_item`
--

DROP TABLE IF EXISTS `caixa_item`;
CREATE TABLE IF NOT EXISTS `caixa_item` (
  `cx_item_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador da tabela',
  `cx_item` int(11) NOT NULL COMMENT 'armazena id da tabela itens_venda',
  `cx_valor` float NOT NULL COMMENT 'valor a ser pago ao dono da peca',
  `cx_tipo` char(1) DEFAULT NULL COMMENT 'e - especie, c - credito ',
  `cx_pago` char(1) NOT NULL COMMENT '0 - a pagar, 1 - pago',
  `data_pagamento` date DEFAULT NULL COMMENT 'data do pagamento ao dono',
  PRIMARY KEY (`cx_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `caixa_pessoa`
--

DROP TABLE IF EXISTS `caixa_pessoa`;
CREATE TABLE IF NOT EXISTS `caixa_pessoa` (
  `cx_cpf` varchar(11) NOT NULL,
  `cx_credito` float NOT NULL,
  `cx_especie` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagem_pecas`
--

DROP TABLE IF EXISTS `imagem_pecas`;
CREATE TABLE IF NOT EXISTS `imagem_pecas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `arquivo` varchar(200) NOT NULL,
  `pecas` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `itens_venda`
--

DROP TABLE IF EXISTS `itens_venda`;
CREATE TABLE IF NOT EXISTS `itens_venda` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `peca` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `venda` int(11) NOT NULL,
  `preco` float NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Acionadores `itens_venda`
--
DROP TRIGGER IF EXISTS `atualiza_qtd_pecas`;
DELIMITER $$
CREATE TRIGGER `atualiza_qtd_pecas` BEFORE INSERT ON `itens_venda` FOR EACH ROW UPDATE pecas SET quantidade = (quantidade - 1)
WHERE codigo = NEW.peca
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `inset_caixa_item`;
DELIMITER $$
CREATE TRIGGER `inset_caixa_item` AFTER INSERT ON `itens_venda` FOR EACH ROW INSERT INTO caixa_item (cx_item_id, cx_item, cx_valor, cx_tipo, cx_pago, data_pagamento)
VALUES 
(
    null,
    NEW.item_id,
    ((NEW.preco * (SELECT p.percentual_lucro FROM itens_venda iv inner join pecas p on p.codigo = iv.peca WHERE iv.item_id = NEW.item_id)) / 100),
    null,
    0,
    null
)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pecas`
--

DROP TABLE IF EXISTS `pecas`;
CREATE TABLE IF NOT EXISTS `pecas` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `quantidade` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `tamanho` varchar(10) NOT NULL,
  `material` varchar(100) NOT NULL,
  `preco_venda` float NOT NULL,
  `percentual_lucro` float NOT NULL,
  `data_recebimento` date NOT NULL,
  `disponibilidade` int(11) NOT NULL COMMENT 'numero de dias que a peca ficar disponivel',
  `pessoa` varchar(11) NOT NULL COMMENT 'armazena no cpf do dono da peca',
  PRIMARY KEY (`codigo`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa`
--

DROP TABLE IF EXISTS `pessoa`;
CREATE TABLE IF NOT EXISTS `pessoa` (
  `cpf` varchar(11) NOT NULL,
  `nome` varchar(300) NOT NULL,
  `banco` varchar(11) NOT NULL,
  `agencia` varchar(10) NOT NULL,
  `conta` varchar(10) NOT NULL,
  `operacao` varchar(10) DEFAULT NULL,
  `data_nascimento` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `rua` varchar(400) NOT NULL,
  `cep` varchar(8) NOT NULL,
  `bairro` varchar(400) NOT NULL,
  `cidade` varchar(400) NOT NULL,
  `estado` varchar(2) NOT NULL,
  UNIQUE KEY `cpf` (`cpf`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Acionadores `pessoa`
--
DROP TRIGGER IF EXISTS `insert_caixa_pessoa`;
DELIMITER $$
CREATE TRIGGER `insert_caixa_pessoa` BEFORE INSERT ON `pessoa` FOR EACH ROW INSERT INTO caixa_pessoa (cx_cpf, cx_credito, cx_especie)
VALUES(
	NEW.cpf, 0, 0
)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `user` varchar(50) NOT NULL,
  `senha` varchar(300) NOT NULL,
  `perfil` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `user`, `senha`, `perfil`) VALUES
(5, 'Administrador', 'admin', '0192023a7bbd73250516f069df18b500', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_perfil`
--

DROP TABLE IF EXISTS `usuario_perfil`;
CREATE TABLE IF NOT EXISTS `usuario_perfil` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(200) NOT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario_perfil`
--

INSERT INTO `usuario_perfil` (`id_perfil`, `descricao`) VALUES
(1, 'Administrador'),
(2, 'Operador'),
(3, 'Cliente');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vendas`
--

DROP TABLE IF EXISTS `vendas`;
CREATE TABLE IF NOT EXISTS `vendas` (
  `ven_id` int(11) NOT NULL AUTO_INCREMENT,
  `valor` float NOT NULL,
  `desconto` float NOT NULL,
  `valor_total` float NOT NULL,
  `data` date NOT NULL,
  `cliente` varchar(11) NOT NULL,
  `credito` float NOT NULL,
  PRIMARY KEY (`ven_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
