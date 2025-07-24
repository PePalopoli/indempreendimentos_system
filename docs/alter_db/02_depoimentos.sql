-- Script para criação do sistema de Depoimentos
-- Data: 2024

-- Tabela de depoimentos
CREATE TABLE `depoimentos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `texto` text COLLATE utf8_unicode_ci NOT NULL,
  `youtube_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `youtube_id` varchar(50) COLLATE utf8_unicode_ci,
  `autor_nome` varchar(100) COLLATE utf8_unicode_ci,
  `autor_cargo` varchar(100) COLLATE utf8_unicode_ci,
  `autor_empresa` varchar(100) COLLATE utf8_unicode_ci,
  `foto_autor` varchar(120) COLLATE utf8_unicode_ci,
  `destaque` tinyint(1) DEFAULT '0',
  `order` int(8) NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `depoimentos_enabled_index` (`enabled`),
  KEY `depoimentos_destaque_index` (`destaque`),
  KEY `depoimentos_order_index` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 