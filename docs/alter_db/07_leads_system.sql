-- Sistema de Captação de Leads
-- Data: 2024-12-19
-- Descrição: Tabela para armazenar todos os leads capturados dos formulários do front-end

CREATE TABLE IF NOT EXISTS `leads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source_url` varchar(500) COLLATE utf8_unicode_ci NOT NULL COMMENT 'URL de origem do lead',
  `source_page` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Página identificada (home, contato, empreendimento, etc)',
  `form_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Tipo do formulário (contato, empreendimento, etc)',
  `user_agent` text COLLATE utf8_unicode_ci COMMENT 'User Agent do navegador',
  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'IP do usuário',
  `referrer` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Referrer (de onde veio)',
  
  -- Campos principais padrão
  `nome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `assunto` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `mensagem` text COLLATE utf8_unicode_ci DEFAULT NULL,
  
  -- Campo flexível para dados adicionais do formulário
  `form_data` longtext COLLATE utf8_unicode_ci COMMENT 'Dados completos do formulário em JSON',
  
  -- Dados de rastreamento
  `utm_source` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `utm_medium` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `utm_campaign` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `utm_term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `utm_content` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  
  -- Status e controle
  `status` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'novo' COMMENT 'novo, contatado, convertido, perdido',
  `email_sent` tinyint(1) DEFAULT '0' COMMENT 'Se o email foi enviado com sucesso',
  `notes` text COLLATE utf8_unicode_ci COMMENT 'Observações do lead',
  
  -- Controle de timestamps
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `leads_source_page_index` (`source_page`),
  KEY `leads_form_type_index` (`form_type`),
  KEY `leads_status_index` (`status`),
  KEY `leads_email_index` (`email`),
  KEY `leads_created_at_index` (`created_at`),
  KEY `leads_utm_source_index` (`utm_source`),
  KEY `leads_utm_campaign_index` (`utm_campaign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Tabela para captação de leads dos formulários do front-end';

-- Criar tabela para tokens CSRF
CREATE TABLE IF NOT EXISTS `csrf_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `form_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `used` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `csrf_tokens_token_unique` (`token`),
  KEY `csrf_tokens_form_type_index` (`form_type`),
  KEY `csrf_tokens_expires_at_index` (`expires_at`),
  KEY `csrf_tokens_used_index` (`used`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Tokens CSRF para proteção dos formulários';