-- Script para criar dados iniciais necessários
-- Execute no banco de dados se houver erro 500

-- 1. Criar tabela permissoes se não existir
CREATE TABLE IF NOT EXISTS `permissoes` (
  `idPermissoes` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `permissoes` text,
  `situacao` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idPermissoes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. Inserir grupos de permissões básicos
INSERT IGNORE INTO `permissoes` (`idPermissoes`, `nome`, `permissoes`, `situacao`) VALUES
(1, 'Admin', 'a:1:{s:12:"admin.access";s:1:"1";}', 1),
(2, 'Advogado', 'a:1:{s:15:"advogado.access";s:1:"1";}', 1),
(3, 'Recepcionista', 'a:1:{s:18:"recepcao.access";s:1:"1";}', 1),
(4, 'Cliente', 'a:1:{s:13:"cliente.access";s:1:"1";}', 1);

-- 3. Atualizar usuários sem permissoes_id para Admin (ID 1)
UPDATE `usuarios` SET `permissoes_id` = 1 WHERE `permissoes_id` IS NULL OR `permissoes_id` = 0;

-- 4. Verificar estrutura da tabela usuarios
-- Se a coluna permissoes_id não existir, adicione:
-- ALTER TABLE `usuarios` ADD COLUMN `permissoes_id` int(11) DEFAULT NULL AFTER `email`;

