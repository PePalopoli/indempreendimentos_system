-- Script corrigido para criação da tabela empreendimentos_beneficios
-- Data: 2024-12-19
-- Versão que verifica estruturas das tabelas primeiro

-- Primeiro, vamos verificar/mostrar a estrutura das tabelas existentes
-- Execute estes comandos separadamente se precisar verificar:
-- DESCRIBE empreendimentos;
-- DESCRIBE beneficios_empreendimentos;

-- Criação da tabela sem as foreign keys primeiro
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

-- Agora vamos adicionar as foreign keys uma por vez para identificar qual está causando problema

-- Primeira foreign key - para empreendimentos
-- Se esta der erro, significa que a tabela empreendimentos não existe ou tem estrutura diferente
ALTER TABLE `empreendimentos_beneficios`
ADD CONSTRAINT `fk_emp_beneficios_empreendimento` 
FOREIGN KEY (`empreendimento_id`) REFERENCES `empreendimentos`(`id`) ON DELETE CASCADE;

-- Segunda foreign key - para beneficios_empreendimentos  
-- Se esta der erro, significa que a tabela beneficios_empreendimentos não existe ou tem estrutura diferente
ALTER TABLE `empreendimentos_beneficios`
ADD CONSTRAINT `fk_emp_beneficios_beneficio` 
FOREIGN KEY (`beneficio_id`) REFERENCES `beneficios_empreendimentos`(`id`) ON DELETE CASCADE;

-- Índice único para evitar duplicatas
ALTER TABLE `empreendimentos_beneficios`
ADD UNIQUE KEY `unique_emp_beneficio` (`empreendimento_id`, `beneficio_id`);

-- Comentários para documentação
ALTER TABLE `empreendimentos_beneficios` COMMENT = 'Tabela de relacionamento entre empreendimentos e benefícios personalizados';

-- Script alternativo caso a tabela empreendimentos use int(11) ao invés de int(10) unsigned:
-- Se o script acima falhar, tente executar este:
/*
DROP TABLE IF EXISTS `empreendimentos_beneficios`;

CREATE TABLE IF NOT EXISTS `empreendimentos_beneficios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empreendimento_id` int(11) NOT NULL,
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

ALTER TABLE `empreendimentos_beneficios`
ADD CONSTRAINT `fk_emp_beneficios_empreendimento` 
FOREIGN KEY (`empreendimento_id`) REFERENCES `empreendimentos`(`id`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_emp_beneficios_beneficio` 
FOREIGN KEY (`beneficio_id`) REFERENCES `beneficios_empreendimentos`(`id`) ON DELETE CASCADE;

ALTER TABLE `empreendimentos_beneficios`
ADD UNIQUE KEY `unique_emp_beneficio` (`empreendimento_id`, `beneficio_id`);
*/ 