-- Script para criação do sistema de Empreendimentos
-- Data: 2024

-- Tabela de etapas da obra
CREATE TABLE `obra_etapas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cor_hex` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#000000',
  `descricao` text COLLATE utf8_unicode_ci,
  `order` int(8) NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `obra_etapas_enabled_index` (`enabled`),
  KEY `obra_etapas_order_index` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Tabela principal de empreendimentos
CREATE TABLE `empreendimentos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `etapa_id` int(10) unsigned NOT NULL,
  `nome` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci,
  `descricao_completa` longtext COLLATE utf8_unicode_ci,
  `logo_empreendimento` varchar(120) COLLATE utf8_unicode_ci,
  `endereco` varchar(255) COLLATE utf8_unicode_ci,
  `cidade` varchar(100) COLLATE utf8_unicode_ci,
  `estado` varchar(2) COLLATE utf8_unicode_ci,
  `cep` varchar(10) COLLATE utf8_unicode_ci,
  `area_total` decimal(10,2),
  `unidades` int(11),
  `dormitorios` varchar(50) COLLATE utf8_unicode_ci,
  `area_privativa_min` decimal(10,2),
  `area_privativa_max` decimal(10,2),
  `valor_min` decimal(15,2),
  `valor_max` decimal(15,2),
  `data_lancamento` date,
  `data_entrega_prevista` date,
  `percentual_obra` decimal(5,2) DEFAULT '0.00',
  `destaque` tinyint(1) DEFAULT '0',
  -- Campos SEO
  `meta_title` varchar(160) COLLATE utf8_unicode_ci,
  `meta_description` varchar(255) COLLATE utf8_unicode_ci,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci,
  -- Status e timestamps
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `order` int(8) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `empreendimentos_slug_unique` (`slug`),
  KEY `empreendimentos_etapa_foreign` (`etapa_id`),
  KEY `empreendimentos_enabled_index` (`enabled`),
  KEY `empreendimentos_destaque_index` (`destaque`),
  KEY `empreendimentos_city_index` (`cidade`),
  CONSTRAINT `empreendimentos_etapa_foreign` FOREIGN KEY (`etapa_id`) REFERENCES `obra_etapas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Tabela de galeria de empreendimentos
CREATE TABLE `empreendimentos_galeria` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empreendimento_id` int(10) unsigned NOT NULL,
  `imagem` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `titulo` varchar(100) COLLATE utf8_unicode_ci,
  `legenda` varchar(255) COLLATE utf8_unicode_ci,
  `alt_text` varchar(120) COLLATE utf8_unicode_ci,
  `tipo` enum('fachada','planta','ambiente','obra','localizacao','outros') DEFAULT 'outros',
  `order` int(8) NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `empreendimentos_galeria_empreendimento_foreign` (`empreendimento_id`),
  KEY `empreendimentos_galeria_order_index` (`order`),
  KEY `empreendimentos_galeria_tipo_index` (`tipo`),
  CONSTRAINT `empreendimentos_galeria_empreendimento_foreign` FOREIGN KEY (`empreendimento_id`) REFERENCES `empreendimentos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Inserir etapas padrão
INSERT INTO `obra_etapas` (`titulo`, `cor_hex`, `descricao`, `order`) VALUES
('Planejamento', '#3498db', 'Fase de planejamento e aprovação do projeto', 1),
('Fundação', '#e67e22', 'Preparação do terreno e fundação', 2),
('Estrutura', '#f39c12', 'Construção da estrutura principal', 3),
('Acabamento', '#2ecc71', 'Fase de acabamento e detalhamento', 4),
('Entrega', '#27ae60', 'Empreendimento pronto para entrega', 5); 