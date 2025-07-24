-- Script para criação do sistema de Blog completo
-- Data: 2024

-- Tabela de categorias do blog
CREATE TABLE `blog_categoria` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci,
  `meta_title` varchar(160) COLLATE utf8_unicode_ci,
  `meta_description` varchar(255) COLLATE utf8_unicode_ci,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `order` int(8) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_categoria_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Tabela principal do blog (atualizada)
CREATE TABLE `blog_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categoria_id` int(10) unsigned NOT NULL,
  `titulo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `subtitulo` varchar(300) COLLATE utf8_unicode_ci,
  `resumo` text COLLATE utf8_unicode_ci,
  `conteudo` longtext COLLATE utf8_unicode_ci NOT NULL,
  `imagem_capa` varchar(120) COLLATE utf8_unicode_ci,
  `autor` varchar(100) COLLATE utf8_unicode_ci,
  `data_publicacao` datetime DEFAULT NULL,
  `visualizacoes` int(11) DEFAULT '0',
  `destaque` tinyint(1) DEFAULT '0',
  `permitir_comentarios` tinyint(1) DEFAULT '1',
  -- Campos SEO
  `meta_title` varchar(160) COLLATE utf8_unicode_ci,
  `meta_description` varchar(255) COLLATE utf8_unicode_ci,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci,
  `og_title` varchar(100) COLLATE utf8_unicode_ci,
  `og_description` varchar(200) COLLATE utf8_unicode_ci,
  `og_image` varchar(120) COLLATE utf8_unicode_ci,
  -- Status e timestamps
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_post_slug_unique` (`slug`),
  KEY `blog_post_categoria_foreign` (`categoria_id`),
  KEY `blog_post_enabled_index` (`enabled`),
  KEY `blog_post_destaque_index` (`destaque`),
  KEY `blog_post_data_publicacao_index` (`data_publicacao`),
  CONSTRAINT `blog_post_categoria_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `blog_categoria` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Tabela de galeria do blog
CREATE TABLE `blog_galeria` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `imagem` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `legenda` varchar(255) COLLATE utf8_unicode_ci,
  `alt_text` varchar(120) COLLATE utf8_unicode_ci,
  `order` int(8) NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `blog_galeria_post_foreign` (`post_id`),
  KEY `blog_galeria_order_index` (`order`),
  CONSTRAINT `blog_galeria_post_foreign` FOREIGN KEY (`post_id`) REFERENCES `blog_post` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Inserir categorias padrão
INSERT INTO `blog_categoria` (`titulo`, `slug`, `descricao`, `enabled`) VALUES
('Notícias', 'noticias', 'Últimas notícias e novidades', 1),
('Dicas', 'dicas', 'Dicas e tutoriais úteis', 1),
('Eventos', 'eventos', 'Eventos e acontecimentos', 1); 