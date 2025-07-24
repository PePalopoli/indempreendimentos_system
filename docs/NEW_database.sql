/*
Navicat MySQL Data Transfer

Source Server         : LocalHost
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : encurtador

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-11-11 10:09:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for banner
-- ----------------------------
DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned NOT NULL,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `order` int(8) NOT NULL DEFAULT '1',
  `show_in` datetime DEFAULT NULL,
  `show_out` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `banner_image_unique` (`image`) USING BTREE,
  KEY `banner_type_foreign` (`type`) USING BTREE,
  CONSTRAINT `banner_type_foreign` FOREIGN KEY (`type`) REFERENCES `banner_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of banner
-- ----------------------------
INSERT INTO `banner` VALUES ('1', '1', 'Banner 1', null, '420002b56cfb5c7e5e5459a1ad3e90329bbe4060.jpeg', '1', '1', null, null, '2020-05-05 08:38:30', '2020-05-05 08:38:30', null);
INSERT INTO `banner` VALUES ('2', '2', 'banner 1', null, '2e3147fbf10671513c110690892354b389f2bc25.jpeg', '1', '1', null, null, '2020-05-05 08:50:00', '2020-05-05 08:50:00', null);

-- ----------------------------
-- Table structure for banner_type
-- ----------------------------
DROP TABLE IF EXISTS `banner_type`;
CREATE TABLE `banner_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of banner_type
-- ----------------------------
INSERT INTO `banner_type` VALUES ('1', 'Banner Home', '1920', '634', '1', '2020-05-05 08:37:32', '2020-05-05 08:37:32', null);
INSERT INTO `banner_type` VALUES ('2', 'Banner Home Mobile', '777', '257', '1', '2020-05-05 08:48:43', '2020-05-05 08:48:43', null);

-- ----------------------------
-- Table structure for institutional
-- ----------------------------
DROP TABLE IF EXISTS `institutional`;
CREATE TABLE `institutional` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned NOT NULL,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `institutional_url_unique` (`url`) USING BTREE,
  KEY `institutional_type_foreign` (`type`) USING BTREE,
  CONSTRAINT `institutional_ibfk_1` FOREIGN KEY (`type`) REFERENCES `institutional_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of institutional
-- ----------------------------

-- ----------------------------
-- Table structure for institutional_type
-- ----------------------------
DROP TABLE IF EXISTS `institutional_type`;
CREATE TABLE `institutional_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `institutional_type_url_unique` (`url`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of institutional_type
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `is_removable` tinyint(1) NOT NULL DEFAULT '1',
  `description` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `roles_role_unique` (`role`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', 'ROLE_ADMIN', '0', 'Administrador do sistema.', '2015-08-07 14:56:41', '2015-08-07 14:56:41');
INSERT INTO `roles` VALUES ('2', 'ROLE_PAINEL', '0', 'Usuário do sistema, permissão para acessar o painel administrativo.', '2015-08-07 14:56:41', '2015-08-07 14:56:41');
INSERT INTO `roles` VALUES ('3', 'ROLE_USER', '0', 'Usuário.', '2015-08-07 14:56:41', '2015-08-07 14:56:41');

-- ----------------------------
-- Table structure for roles_access
-- ----------------------------
DROP TABLE IF EXISTS `roles_access`;
CREATE TABLE `roles_access` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` text COLLATE utf8_unicode_ci NOT NULL,
  `methods` text COLLATE utf8_unicode_ci,
  `host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` int(8) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `roles_access_path_unique` (`path`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of roles_access
-- ----------------------------
INSERT INTO `roles_access` VALUES ('1', '^/alcasystemadm', '[\"ROLE_PAINEL\",\"ROLE_ADMIN\"]', null, null, null, '0', '2015-08-10 11:25:03', '2015-08-14 11:04:32');
INSERT INTO `roles_access` VALUES ('2', '^/alcasystemadm/access', '[\"ROLE_ADMIN\"]', null, null, null, '2', '2015-08-14 11:06:06', '2015-08-14 11:06:06');
INSERT INTO `roles_access` VALUES ('3', '^/alcasystemadm/roles', '[\"ROLE_ADMIN\"]', null, null, null, '1', '2015-08-14 11:06:18', '2015-08-14 11:06:18');
INSERT INTO `roles_access` VALUES ('4', '^/alcasystemadm/user', '[\"ROLE_ADMIN\"]', null, null, null, '3', '2015-08-14 11:06:29', '2015-08-14 11:06:29');

-- ----------------------------
-- Table structure for seo
-- ----------------------------
DROP TABLE IF EXISTS `seo`;
CREATE TABLE `seo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keyword` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `h1` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `order` int(8) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `seo_url_unique` (`url`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of seo
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `tipo_user` int(10) unsigned DEFAULT NULL,
  `image_avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `users_username_unique` (`username`) USING BTREE,
  UNIQUE KEY `users_email_unique` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'alcateia', '5MMk4Lq3zU+Q2XB3PZVDoaqiBk4WJUgDvKwLao6MseLEmOnCzT4h272e+tbUkpEj0DVQTFqiQPqiloaTclqAjw==', 'alcateia@alcateiawebdigital.com', 'Alcatéia Digital', '1', '2020-05-05 08:36:09', '2020-05-05 08:36:09', null, '1', null);

-- ----------------------------
-- Table structure for users_roles
-- ----------------------------
DROP TABLE IF EXISTS `users_roles`;
CREATE TABLE `users_roles` (
  `user` int(10) unsigned NOT NULL,
  `role` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user`,`role`) USING BTREE,
  KEY `users_roles_role_foreign` (`role`,`user`) USING BTREE,
  CONSTRAINT `users_roles_role_foreign` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_roles_user_foreign` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of users_roles
-- ----------------------------
INSERT INTO `users_roles` VALUES ('1', '1');
