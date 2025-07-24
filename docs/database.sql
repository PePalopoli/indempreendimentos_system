

-- Copiando estrutura para tabela develop.seo
CREATE TABLE `seo` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `url` VARCHAR(255) NOT NULL,
  `title` VARCHAR(80) NULL DEFAULT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `keyword` VARCHAR(255) NULL DEFAULT NULL,
  `h1` VARCHAR(80) NULL DEFAULT NULL,
  `enabled` TINYINT(1) NOT NULL,
  `order` int(8) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `seo_url_unique` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela develop.banner
DROP TABLE IF EXISTS `banner`;
CREATE TABLE IF NOT EXISTS `banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned NOT NULL,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `order` int(8) NOT NULL DEFAULT '1',
  `show_in` datetime DEFAULT NULL,
  `show_out` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `banner_image_unique` (`image`),
  KEY `banner_type_foreign` (`type`),
  CONSTRAINT `banner_type_foreign` FOREIGN KEY (`type`) REFERENCES `banner_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela develop.banner_type
DROP TABLE IF EXISTS `banner_type`;
CREATE TABLE IF NOT EXISTS `banner_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela develop.institutional
DROP TABLE IF EXISTS `institutional`;
CREATE TABLE IF NOT EXISTS `institutional` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned NOT NULL,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `institutional_url_unique` (`url`),
  KEY `institutional_type_foreign` (`type`),
  CONSTRAINT `institutional_type_foreign` FOREIGN KEY (`type`) REFERENCES `institutional_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela develop.institutional_type
DROP TABLE IF EXISTS `institutional_type`;
CREATE TABLE IF NOT EXISTS `institutional_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `institutional_type_url_unique` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela develop.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `is_removable` tinyint(1) NOT NULL DEFAULT '1',
  `description` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_role_unique` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela develop.roles: ~4 rows (aproximadamente)
DELETE FROM `roles`;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `role`, `is_removable`, `description`, `created_at`, `updated_at`) VALUES
    (1, 'ROLE_ADMIN', 0, 'Administrador do sistema.', '2015-08-07 14:56:41', '2015-08-07 14:56:41'),
    (2, 'ROLE_PAINEL', 0, 'Usuário do sistema, permissão para acessar o painel administrativo.', '2015-08-07 14:56:41', '2015-08-07 14:56:41'),
    (3, 'ROLE_USER', 0, 'Usuário.', '2015-08-07 14:56:41', '2015-08-07 14:56:41');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Copiando estrutura para tabela develop.roles_access
DROP TABLE IF EXISTS `roles_access`;
CREATE TABLE IF NOT EXISTS `roles_access` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` text COLLATE utf8_unicode_ci NOT NULL,
  `methods` text COLLATE utf8_unicode_ci,
  `host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` int(8) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_access_path_unique` (`path`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela develop.roles_access: ~4 rows (aproximadamente)
DELETE FROM `roles_access`;
/*!40000 ALTER TABLE `roles_access` DISABLE KEYS */;
INSERT INTO `roles_access` (`id`, `path`, `roles`, `methods`, `host`, `ip`, `order`, `created_at`, `updated_at`) VALUES
  (1, '^/PaloSystemadm', '["ROLE_PAINEL","ROLE_ADMIN"]', NULL, NULL, NULL, 0, '2015-08-10 11:25:03', '2015-08-14 11:04:32'),
  (2, '^/PaloSystemadm/access', '["ROLE_ADMIN"]', NULL, NULL, NULL, 2, '2015-08-14 11:06:06', '2015-08-14 11:06:06'),
  (3, '^/PaloSystemadm/roles', '["ROLE_ADMIN"]', NULL, NULL, NULL, 1, '2015-08-14 11:06:18', '2015-08-14 11:06:18'),
  (4, '^/PaloSystemadm/user', '["ROLE_ADMIN"]', NULL, NULL, NULL, 3, '2015-08-14 11:06:29', '2015-08-14 11:06:29');
/*!40000 ALTER TABLE `roles_access` ENABLE KEYS */;

-- Copiando estrutura para tabela develop.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportação de dados foi desmarcado.

-- Copiando dados para a tabela develop.users: ~2 rows (aproximadamente)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`, `email`, `name`, `enabled`, `created_at`, `updated_at`, `deleted_at`) VALUES
  (1, 'Audi', '5MMk4Lq3zU+Q2XB3PZVDoaqiBk4WJUgDvKwLao6MseLEmOnCzT4h272e+tbUkpEj0DVQTFqiQPqiloaTclqAjw==', 'Audi@Audiwebdigital.com', 'Alcatéia Digital', 1, '2015-08-18 15:36:38', '2015-08-18 15:45:44', NULL);

-- #####################################
-- login: Palopoli
-- senha: paloSystem
-- #####################################

/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Copiando estrutura para tabela develop.users_roles
DROP TABLE IF EXISTS `users_roles`;
CREATE TABLE IF NOT EXISTS `users_roles` (
  `user` int(10) unsigned NOT NULL,
  `role` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user`,`role`),
  KEY `users_roles_role_foreign` (`role`,`user`),
  CONSTRAINT `users_roles_role_foreign` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_roles_user_foreign` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela develop.users_roles: ~3 rows (aproximadamente)
DELETE FROM `users_roles`;
/*!40000 ALTER TABLE `users_roles` DISABLE KEYS */;
INSERT INTO `users_roles` (`user`, `role`) VALUES
    (1, 1);
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
