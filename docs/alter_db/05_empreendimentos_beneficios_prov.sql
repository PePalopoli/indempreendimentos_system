-- Script provisório para criação da tabela empreendimentos_beneficios (SEM FOREIGN KEYS)
-- Data: 2024-12-19
-- Versão de teste para identificar problemas

-- Criação da tabela SEM foreign keys
CREATE TABLE IF NOT EXISTS `empreendimentos_beneficios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empreendimento_id` int(10) unsigned NOT NULL,
  `beneficio_id` int(11) NOT NULL,
  `valor_personalizado` varchar(120) DEFAULT NULL,
  `cor_hex` varchar(7) NOT NULL DEFAULT '#000000',
  `order` int(11) DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_empreendimento` (`empreendimento_id`),
  KEY `idx_beneficio` (`beneficio_id`),
  KEY `idx_order` (`order`),
  KEY `idx_enabled` (`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Índice único para evitar duplicatas
ALTER TABLE `empreendimentos_beneficios`
ADD UNIQUE KEY `unique_emp_beneficio` (`empreendimento_id`, `beneficio_id`);

-- Comentários para documentação
ALTER TABLE `empreendimentos_beneficios` COMMENT = 'Tabela de relacionamento entre empreendimentos e benefícios personalizados';

-- Para adicionar as foreign keys depois (execute separadamente após verificar as estruturas):
/*
-- Verificar estruturas primeiro:
DESCRIBE empreendimentos;
DESCRIBE beneficios_empreendimentos;

-- Depois adicionar as FKs:
ALTER TABLE `empreendimentos_beneficios`
ADD CONSTRAINT `fk_emp_beneficios_empreendimento` 
FOREIGN KEY (`empreendimento_id`) REFERENCES `empreendimentos`(`id`) ON DELETE CASCADE;

ALTER TABLE `empreendimentos_beneficios`
ADD CONSTRAINT `fk_emp_beneficios_beneficio` 
FOREIGN KEY (`beneficio_id`) REFERENCES `beneficios_empreendimentos`(`id`) ON DELETE CASCADE;
*/ 