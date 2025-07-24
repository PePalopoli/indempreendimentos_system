/*
Navicat MySQL Data Transfer

Source Server         : LocalHost
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : avp_brasil

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-11-11 10:01:29
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
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `banner_image_unique` (`image`),
  KEY `banner_type_foreign` (`type`),
  CONSTRAINT `banner_type_foreign` FOREIGN KEY (`type`) REFERENCES `banner_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of banner
-- ----------------------------

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
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of banner_type
-- ----------------------------

-- ----------------------------
-- Table structure for beneficios_type
-- ----------------------------
DROP TABLE IF EXISTS `beneficios_type`;
CREATE TABLE `beneficios_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `order` int(8) NOT NULL DEFAULT '1',
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of beneficios_type
-- ----------------------------
INSERT INTO `beneficios_type` VALUES ('1', 'Proteção veicular completa', '<p>Roubo, inc&ecirc;ndio, colis&atilde;o e perda total</p>', '1', '2020-02-12 14:51:30', '2020-02-12 14:54:33', null, '0', '2b6ad44071e6443f3877275e57b87a0193ba4618.png');
INSERT INTO `beneficios_type` VALUES ('2', 'Avaliação', '<p>100% da tabela Fipe</p>', '1', '2020-02-12 14:52:05', '2020-02-12 14:54:33', null, '1', 'fb3819baf49e32ae04cc1542a7e66397c07797f8.png');
INSERT INTO `beneficios_type` VALUES ('3', 'Rastreador', '<p>Central com monitoramento 24 Horas</p>', '1', '2020-02-12 14:52:21', '2020-02-12 14:54:33', null, '2', '7e396b89be2f20bf170825f16e42f543529b4eab.png');
INSERT INTO `beneficios_type` VALUES ('4', 'Seguro de vida', null, '1', '2020-02-12 14:52:51', '2020-02-12 14:54:33', null, '3', '8de8f19146ba4b8d57b6133749bb7605861fef28.png');
INSERT INTO `beneficios_type` VALUES ('5', 'Danos a terceiros', null, '1', '2020-02-12 14:53:04', '2020-02-12 14:54:33', null, '4', '44dc81f2fbe1f159873a47700d938aab08a22274.png');
INSERT INTO `beneficios_type` VALUES ('6', 'Reboque', null, '1', '2020-02-12 14:53:24', '2020-02-12 14:54:33', null, '5', '4d32dc519143b685134aa51dc574ad698623a0c5.png');
INSERT INTO `beneficios_type` VALUES ('7', 'Assistência 24h', null, '1', '2020-02-12 14:53:32', '2020-02-12 14:54:33', null, '6', '725fc5d385bbfab09189892935bb704e3da67df5.png');

-- ----------------------------
-- Table structure for blog_externo_type
-- ----------------------------
DROP TABLE IF EXISTS `blog_externo_type`;
CREATE TABLE `blog_externo_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of blog_externo_type
-- ----------------------------
INSERT INTO `blog_externo_type` VALUES ('2', 'Sua internet cai quando chove? Entenda quais as causas e o que fazer', 'https://minhainternet.cabonnet.com.br/internet-cai-quando-chove/', '<p>Sua internet cai quando chove? Entenda quais as causas e o que fazer</p>', '1', '2020-08-13 11:52:40', '2020-08-13 11:52:40', null, '58413f1e556e89d0bccf2547a66f3d7c761398df.jpeg');
INSERT INTO `blog_externo_type` VALUES ('3', 'Velocidade de upload e velocidade de download: quais são as diferenças?', 'https://minhainternet.cabonnet.com.br/velocidade-de-upload/', '<h1>Velocidade de upload e velocidade de download: quais s&atilde;o as diferen&ccedil;as?</h1>', '1', '2020-08-13 11:53:18', '2020-08-13 11:53:18', null, 'b82cd27415894e670faba531549d6bcb79bab255.jpeg');
INSERT INTO `blog_externo_type` VALUES ('4', 'Quais são os fatores que contribuem para uma internet lenta?', 'https://minhainternet.cabonnet.com.br/internet-lenta/', '<p>Quais s&atilde;o os fatores que contribuem para uma internet lenta?</p>', '1', '2020-08-13 11:54:11', '2020-08-13 11:54:11', null, '1fda4f47969fa3c09f5532584ab5a70823a3e745.jpeg');
INSERT INTO `blog_externo_type` VALUES ('5', '5 vantagens da internet fibra óptica', 'https://minhainternet.cabonnet.com.br/internet-por-fibra-optica/', '<p>5 vantagens da internet fibra &oacute;ptica</p>', '1', '2020-08-13 13:37:16', '2020-08-13 13:37:16', null, '8c60face529b224f91b3209887e22e3d39508ca5.jpeg');
INSERT INTO `blog_externo_type` VALUES ('6', 'Como o vírus de computador invade um dispositivo? Entenda como evitar!', 'https://minhainternet.cabonnet.com.br/virus-de-computador/', '<p>Como o v&iacute;rus de computador invade um dispositivo? Entenda como evitar!</p>', '1', '2020-08-13 13:37:49', '2020-08-13 13:37:49', null, '46caf78255f9ea47041ebe563be3f8cdb2602bc1.jpeg');
INSERT INTO `blog_externo_type` VALUES ('7', '6 motivos por trás de uma internet instável: como resolver o problema?', 'https://minhainternet.cabonnet.com.br/internet-instavel/', '<p>6 motivos por tr&aacute;s de uma internet inst&aacute;vel: como resolver o problema?</p>', '1', '2020-08-13 13:38:18', '2020-08-13 13:38:18', null, '87e97d03a30dfb6e77641575b980cbf88983a7c6.jpeg');
INSERT INTO `blog_externo_type` VALUES ('8', 'Quais são as vantagens da tecnologia e da internet em casa?', 'https://minhainternet.cabonnet.com.br/vantagens-da-tecnologia-e-internet-em-casa/', '<p>Quais s&atilde;o as vantagens da tecnologia e da internet em casa?</p>', '1', '2020-08-13 13:39:12', '2020-08-13 13:39:12', null, '13a876a31e01f947641226f424b8e5bc29567a0c.jpeg');
INSERT INTO `blog_externo_type` VALUES ('9', 'Precisa trabalhar em casa? Entenda por que você precisa de uma boa conexão à rede!', 'https://minhainternet.cabonnet.com.br/internet-para-trabalhar-em-casa/', '<p>Precisa trabalhar em casa? Entenda por que voc&ecirc; precisa de uma boa conex&atilde;o &agrave; rede!</p>', '1', '2020-08-13 13:40:05', '2020-08-13 13:40:05', null, '91f8a9d375318a0e7e6e4625b43142bf1dd7d350.jpeg');
INSERT INTO `blog_externo_type` VALUES ('10', 'Modem e roteador: entenda o que são e as diferenças entre eles', 'https://minhainternet.cabonnet.com.br/modem-e-roteador/', '<p>Modem e roteador: entenda o que s&atilde;o e as diferen&ccedil;as entre eles</p>', '1', '2020-08-13 13:40:35', '2020-08-13 13:40:35', null, '36e9269be7b4b19f425fc7c4191e08de923397af.jpeg');
INSERT INTO `blog_externo_type` VALUES ('11', 'Conheça a história da Netflix e descubra por que ela é inspiradora', 'https://minhainternet.cabonnet.com.br/historia-da-netflix/', '<p>Conhe&ccedil;a a hist&oacute;ria da Netflix e descubra por que ela &eacute; inspiradora</p>', '1', '2020-08-13 13:41:24', '2020-08-13 13:41:24', null, '4630d8badcf794e2a7980d83747a62551bd7e190.jpeg');
INSERT INTO `blog_externo_type` VALUES ('12', 'Conexão para jogar online: dicas para um bom desempenho!', 'https://minhainternet.cabonnet.com.br/conexao-para-jogar-online/', '<p>Conex&atilde;o para jogar online: dicas para um bom desempenho!</p>', '1', '2020-08-13 13:42:11', '2020-08-13 13:42:11', null, '441669eadedff0869d86ed3ad8a340be4e6ccd22.jpeg');
INSERT INTO `blog_externo_type` VALUES ('13', 'Internet de fibra óptica e internet comum: quais são as principais diferenças?', 'https://minhainternet.cabonnet.com.br/internet-de-fibra-optica/', '<p>Internet de fibra &oacute;ptica e internet comum: quais s&atilde;o as principais diferen&ccedil;as?</p>', '1', '2020-08-13 13:42:44', '2020-08-13 13:42:44', null, '6c4fb78f36641d8c2f7c278d5284b06b249d668f.jpeg');
INSERT INTO `blog_externo_type` VALUES ('14', 'Guia completo sobre o funcionamento e os benefícios da cobrança recorrente', 'https://minhainternet.cabonnet.com.br/cobranca-recorrente/', '<p>Guia completo sobre o funcionamento e os benef&iacute;cios da cobran&ccedil;a recorrente</p>', '1', '2020-08-13 13:43:11', '2020-08-13 13:43:11', null, '317d4798ae5a3720817c8f342b42fbbc692e41c5.jpeg');
INSERT INTO `blog_externo_type` VALUES ('15', 'Quais são os principais meios de pagamento online atualmente?', 'https://minhainternet.cabonnet.com.br/meios-de-pagamento-online/', '<p>Quais s&atilde;o os principais meios de pagamento online atualmente?</p>', '1', '2020-08-13 13:43:39', '2020-08-13 13:43:39', null, 'c3465d6659163f2e52f43d0253a1052a5302e9af.jpeg');
INSERT INTO `blog_externo_type` VALUES ('16', '4 riscos de Wi-Fi aberto que podem prejudicar sua segurança digital', 'https://minhainternet.cabonnet.com.br/riscos-de-wifi-aberto/', '<p>4 riscos de Wi-Fi aberto que podem prejudicar sua seguran&ccedil;a digital</p>', '1', '2020-08-13 13:44:23', '2020-08-13 13:44:23', null, '30a6831ee11734f7e2aa65033d2622e75d7a1690.jpeg');
INSERT INTO `blog_externo_type` VALUES ('17', 'Como escolher um bom serviço de internet no interior? Descubra!', 'https://minhainternet.cabonnet.com.br/servico-de-internet-no-interior/', '<p>Como escolher um bom servi&ccedil;o de internet no interior? Descubra!</p>', '1', '2020-08-13 13:44:54', '2020-08-13 13:44:54', null, '593707eb6b707c2558b3e22cdfc4ddd89a48e02b.jpeg');
INSERT INTO `blog_externo_type` VALUES ('18', 'O que considerar ao escolher um roteador para jogar online? Descubra!', 'https://minhainternet.cabonnet.com.br/roteador-para-jogar-online/', '<p>O que considerar ao escolher um roteador para jogar online? Descubra!</p>', '1', '2020-08-13 13:45:35', '2020-08-13 13:45:35', null, '92e70747a9d7939b0d1d76972d933b92682712e5.jpeg');
INSERT INTO `blog_externo_type` VALUES ('19', '7 dicas que garantem segurança para usar internet!', 'https://minhainternet.cabonnet.com.br/seguranca-para-usar-internet/', '<p>7 dicas que garantem seguran&ccedil;a para usar internet!</p>', '1', '2020-08-13 13:46:03', '2020-08-13 13:46:03', null, '465318706316220f3d0d7b39310acb1e22ddeeed.jpeg');
INSERT INTO `blog_externo_type` VALUES ('20', '3 maiores vantagens da TV a cabo e por que ter esse serviço na sua casa', 'https://minhainternet.cabonnet.com.br/vantagens-da-tv-a-cabo/', '<p>3 maiores vantagens da TV a cabo e por que ter esse servi&ccedil;o na sua casa</p>', '1', '2020-08-13 13:47:22', '2020-08-13 13:47:22', null, 'cf6523d94da13d83a623f7bcba1524493b3d9d93.jpeg');
INSERT INTO `blog_externo_type` VALUES ('21', 'Guia definitivo sobre como funciona a conexão Wi-Fi', 'https://minhainternet.cabonnet.com.br/como-funciona-a-conexao-wi-fi/', '<p>Guia definitivo sobre como funciona a conex&atilde;o Wi-Fi</p>', '1', '2020-08-13 13:47:55', '2020-08-13 13:47:55', null, '71b710762a6f2f5a317233eb18caebc7f9439c2e.jpeg');
INSERT INTO `blog_externo_type` VALUES ('22', 'Localização do roteador wireless: miniguia para você instalar', 'https://minhainternet.cabonnet.com.br/localizacao-do-roteador-wireless/', '<p>Localiza&ccedil;&atilde;o do roteador wireless: miniguia para voc&ecirc; instalar</p>', '1', '2020-08-13 13:49:02', '2020-08-13 13:49:02', null, '87355e7f3d9987fc351c8b880fd4a5099fd4fa6f.jpeg');
INSERT INTO `blog_externo_type` VALUES ('23', 'Saiba como limpar o cache do navegador para navegar mais rápido', 'https://minhainternet.cabonnet.com.br/limpar-o-cache-do-navegador/', '<p>Saiba como limpar o cache do navegador para navegar mais r&aacute;pido</p>', '1', '2020-08-13 13:49:39', '2020-08-13 13:49:39', null, '52c7b593479f2d3e4e02152b8aed83d62b7e88c3.jpeg');
INSERT INTO `blog_externo_type` VALUES ('24', 'Velocidade de internet: tudo que você precisa saber sobre o assunto', 'https://minhainternet.cabonnet.com.br/velocidade-de-internet/', '<p>Velocidade de internet: tudo que voc&ecirc; precisa saber sobre o assunto</p>', '1', '2020-08-13 13:51:14', '2020-08-13 13:51:14', null, 'ffbfce3bee0ebfa462e7d1f874ac2dbe9e55cadb.jpeg');

-- ----------------------------
-- Table structure for categorias_blog
-- ----------------------------
DROP TABLE IF EXISTS `categorias_blog`;
CREATE TABLE `categorias_blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categorias_blog_url_unique` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of categorias_blog
-- ----------------------------
INSERT INTO `categorias_blog` VALUES ('1', 'Carros', 'carros', null, '1', '2020-01-20 11:04:07', '2020-01-20 11:04:07', null);
INSERT INTO `categorias_blog` VALUES ('2', 'Antigos', 'antigos', null, '1', '2020-01-20 11:04:15', '2020-01-20 12:17:45', null);
INSERT INTO `categorias_blog` VALUES ('3', 'Caminhões', 'caminhoes', null, '1', '2020-01-20 11:04:24', '2020-01-20 11:04:24', null);
INSERT INTO `categorias_blog` VALUES ('4', 'Novidades', 'novidades', null, '1', '2020-01-20 11:04:33', '2020-01-20 11:04:33', null);

-- ----------------------------
-- Table structure for configuracoes_type
-- ----------------------------
DROP TABLE IF EXISTS `configuracoes_type`;
CREATE TABLE `configuracoes_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `telefone_rodape` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `email_rodape` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `texto_rodape` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone_contato` longtext COLLATE utf8_unicode_ci,
  `funcionamento_contato` longtext COLLATE utf8_unicode_ci,
  `telefone_rodape_socorro` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of configuracoes_type
-- ----------------------------
INSERT INTO `configuracoes_type` VALUES ('1', '2', '2020-02-12 15:34:09', '2020-02-12 15:34:09', null, null, null, null, null, null);

-- ----------------------------
-- Table structure for contatos_type
-- ----------------------------
DROP TABLE IF EXISTS `contatos_type`;
CREATE TABLE `contatos_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mensagem` longtext COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `email` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `assunto` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of contatos_type
-- ----------------------------
INSERT INTO `contatos_type` VALUES ('1', 'Pedro Palopoli', 'Olá\r\ngdfgsdf\r\nããããã\r\n\r\n\r\nfdfdf', '2020-01-20 22:04:50', '2020-01-20 22:04:50', null, 'pedro.palopoli@hotmail.com', 'Teste');
INSERT INTO `contatos_type` VALUES ('2', 'Pedro Palopoli', 'Olá\r\ngdfgsdf\r\nããããã\r\n\r\n\r\nfdfdf', '2020-01-20 22:05:05', '2020-01-20 22:05:05', null, 'pedro.palopoli@hotmail.com', 'Teste');

-- ----------------------------
-- Table structure for cotacoes_type
-- ----------------------------
DROP TABLE IF EXISTS `cotacoes_type`;
CREATE TABLE `cotacoes_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `email` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ddd` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_contato` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_cotacao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of cotacoes_type
-- ----------------------------
INSERT INTO `cotacoes_type` VALUES ('1', 'Pedro Palopoli', '2020-01-20 19:48:38', '2020-01-20 19:48:38', null, 'pedro.palopoli@hotmail.com', '18', '981502669', 'Whatsapp', 'Caminhões');
INSERT INTO `cotacoes_type` VALUES ('2', 'Pedro Palopoli', '2020-01-20 19:52:30', '2020-01-20 19:52:30', null, 'pedro.palopoli@hotmail.com', '18', '981502669', 'Whatsapp', 'Caminhões');

-- ----------------------------
-- Table structure for depoimentos_type
-- ----------------------------
DROP TABLE IF EXISTS `depoimentos_type`;
CREATE TABLE `depoimentos_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of depoimentos_type
-- ----------------------------
INSERT INTO `depoimentos_type` VALUES ('1', 'João da Silva, Empresario', '<p>O trecho padr&atilde;o original de Lorem Ipsum, usado desde o s&eacute;culo XVI, est&aacute; reproduzido abaixo para os interessados. Se&ccedil;&otilde;es 1.10.32 e 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; de Cicero tamb&eacute;m foram reproduzidas abaixo em sua forma exata original, acompanhada das vers&otilde;es para o ingl&ecirc;s da tradu&ccedil;&atilde;o feita por H. Rackham em 1914.</p>', '1', '2020-01-20 14:20:52', '2020-01-20 14:20:52', null, '98a642144087cf873627c9c93dabd80a5dfef756.jpeg');
INSERT INTO `depoimentos_type` VALUES ('2', 'João da Silva, Empresario', '<p>O trecho padr&atilde;o original de Lorem Ipsum, usado desde o s&eacute;culo XVI, est&aacute; reproduzido abaixo para os interessados. Se&ccedil;&otilde;es 1.10.32 e 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; de Cicero tamb&eacute;m foram reproduzidas abaixo em sua forma exata original, acompanhada das vers&otilde;es para o ingl&ecirc;s da tradu&ccedil;&atilde;o feita por H. Rackham em 1914.</p>', '1', '2020-01-20 14:20:52', '2020-01-20 14:20:52', null, '98a642144087cf873627c9c93dabd80a5dfef756.jpeg');
INSERT INTO `depoimentos_type` VALUES ('3', 'João da Silva, Empresario', '<p>O trecho padr&atilde;o original de Lorem Ipsum, usado desde o s&eacute;culo XVI, est&aacute; reproduzido abaixo para os interessados. Se&ccedil;&otilde;es 1.10.32 e 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; de Cicero tamb&eacute;m foram reproduzidas abaixo em sua forma exata original, acompanhada das vers&otilde;es para o ingl&ecirc;s da tradu&ccedil;&atilde;o feita por H. Rackham em 1914.</p>', '1', '2020-01-20 14:20:52', '2020-01-20 14:20:52', null, '98a642144087cf873627c9c93dabd80a5dfef756.jpeg');
INSERT INTO `depoimentos_type` VALUES ('4', 'João da Silva, Empresario', '<p>O trecho padr&atilde;o original de Lorem Ipsum, usado desde o s&eacute;culo XVI, est&aacute; reproduzido abaixo para os interessados. Se&ccedil;&otilde;es 1.10.32 e 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; de Cicero tamb&eacute;m foram reproduzidas abaixo em sua forma exata original, acompanhada das vers&otilde;es para o ingl&ecirc;s da tradu&ccedil;&atilde;o feita por H. Rackham em 1914.</p>', '1', '2020-01-20 14:20:52', '2020-01-20 14:20:52', null, '98a642144087cf873627c9c93dabd80a5dfef756.jpeg');
INSERT INTO `depoimentos_type` VALUES ('5', 'João da Silva, Empresario', '<p>O trecho padr&atilde;o original de Lorem Ipsum, usado desde o s&eacute;culo XVI, est&aacute; reproduzido abaixo para os interessados. Se&ccedil;&otilde;es 1.10.32 e 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; de Cicero tamb&eacute;m foram reproduzidas abaixo em sua forma exata original, acompanhada das vers&otilde;es para o ingl&ecirc;s da tradu&ccedil;&atilde;o feita por H. Rackham em 1914.</p>', '1', '2020-01-20 14:20:52', '2020-01-20 14:20:52', null, '98a642144087cf873627c9c93dabd80a5dfef756.jpeg');

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
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `institutional_url_unique` (`url`),
  KEY `institutional_type_foreign` (`type`),
  CONSTRAINT `institutional_ibfk_1` FOREIGN KEY (`type`) REFERENCES `institutional_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `institutional_type_url_unique` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of institutional_type
-- ----------------------------

-- ----------------------------
-- Table structure for noticias_blog
-- ----------------------------
DROP TABLE IF EXISTS `noticias_blog`;
CREATE TABLE `noticias_blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned NOT NULL,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `noticias_blog_url_unique` (`url`),
  KEY `fk_categorias_blog` (`type`),
  CONSTRAINT `fk_categorias_blog` FOREIGN KEY (`type`) REFERENCES `categorias_blog` (`id`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of noticias_blog
-- ----------------------------
INSERT INTO `noticias_blog` VALUES ('1', '1', 'Tiulo do post - veículos antigos e fora de serie', 'tiulo-do-post-veiculos-antigos-e-fora-de-serie', '<p>Lorem Ipsum &eacute; simplesmente uma simula&ccedil;&atilde;o de texto da ind&uacute;stria tipogr&aacute;fica e de impressos, e vem sendo utilizado desde o s&eacute;culo XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos. Lorem Ipsum sobreviveu n&atilde;o s&oacute; a cinco s&eacute;culos, como tamb&eacute;m ao salto para a editora&ccedil;&atilde;o eletr&ocirc;nica, permanecendo essencialmente inalterado. Se popularizou na d&eacute;cada de 60, quando a Letraset lan&ccedil;ou decalques contendo passagens de Lorem Ipsum, e mais recentemente quando passou a ser integrado a softwares de editora&ccedil;&atilde;o eletr&ocirc;nica como Aldus PageMaker.</p>', '1', '2020-01-20 11:36:50', '2020-01-20 11:36:50', null, '40c5834fe4f685797efe89eeff15dc059694f06c.jpeg');

-- ----------------------------
-- Table structure for parceiros_type
-- ----------------------------
DROP TABLE IF EXISTS `parceiros_type`;
CREATE TABLE `parceiros_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of parceiros_type
-- ----------------------------
INSERT INTO `parceiros_type` VALUES ('1', 'Siga Trucks', '1', '2020-01-20 15:42:04', '2020-01-20 15:48:10', null, '121714ab50f37367d8f3746acb538524ba2c0909.png', 'https://www.google.com');
INSERT INTO `parceiros_type` VALUES ('2', 'Moura', '1', '2020-01-20 15:42:17', '2020-01-20 15:42:17', null, 'e015f927e6aaa4790b8f9d5dfa25ef65fdfc99a2.png', null);
INSERT INTO `parceiros_type` VALUES ('3', 'Bridgestone', '1', '2020-01-20 15:42:32', '2020-01-20 15:42:32', null, 'ef9512e9e36cf430ce7802b611c60d4afd5834cf.png', null);
INSERT INTO `parceiros_type` VALUES ('4', 'Rodotruck', '1', '2020-01-20 15:42:48', '2020-01-20 15:42:48', null, '1ac71129262388a5bfedd86d46d7f4aaa564226c.png', null);

-- ----------------------------
-- Table structure for perguntas_type
-- ----------------------------
DROP TABLE IF EXISTS `perguntas_type`;
CREATE TABLE `perguntas_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of perguntas_type
-- ----------------------------
INSERT INTO `perguntas_type` VALUES ('1', 'Pergunta 1?', '<p>Ao contr&aacute;rio do que se acredita, Lorem Ipsum n&atilde;o &eacute; simplesmente um texto rand&ocirc;mico. Com mais de 2000 anos, suas ra&iacute;zes podem ser encontradas em uma obra de literatura latina cl&aacute;ssica datada de 45 AC. Richard McClintock, um professor de latim do Hampden-Sydney College na Virginia, pesquisou uma das mais obscuras palavras em latim, consectetur, oriunda de uma passagem de Lorem Ipsum, e, procurando por entre cita&ccedil;&otilde;es da palavra na literatura cl&aacute;ssica, descobriu a sua indubit&aacute;vel origem. Lorem Ipsum vem das se&ccedil;&otilde;es 1.10.32 e 1.10.33 do &quot;de Finibus Bonorum et Malorum&quot; (Os Extremos do Bem e do Mal), de C&iacute;cero, escrito em 45 AC. Este livro &eacute; um tratado de teoria da &eacute;tica muito popular na &eacute;poca da Renascen&ccedil;a. A primeira linha de Lorem Ipsum, &quot;Lorem Ipsum dolor sit amet...&quot; vem de uma linha na se&ccedil;&atilde;o 1.10.32.</p>', '1', '2020-01-20 15:13:08', '2020-01-20 15:13:08', null);
INSERT INTO `perguntas_type` VALUES ('2', 'Pergunta 2?', '<p>O trecho padr&atilde;o original de Lorem Ipsum, usado desde o s&eacute;culo XVI, est&aacute; reproduzido abaixo para os interessados. Se&ccedil;&otilde;es 1.10.32 e 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; de Cicero tamb&eacute;m foram reproduzidas abaixo em sua forma exata original, acompanhada das vers&otilde;es para o ingl&ecirc;s da tradu&ccedil;&atilde;o feita por H. Rackham em 1914.</p>', '1', '2020-01-20 15:13:21', '2020-01-20 15:18:58', null);
INSERT INTO `perguntas_type` VALUES ('3', 'Pergunta 3?', '<p>Ao contr&aacute;rio do que se acredita, Lorem Ipsum n&atilde;o &eacute; simplesmente um texto rand&ocirc;mico. Com mais de 2000 anos, suas ra&iacute;zes podem ser encontradas em uma obra de literatura latina cl&aacute;ssica datada de 45 AC. Richard McClintock, um professor de latim do Hampden-Sydney College na Virginia, pesquisou uma das mais obscuras palavras em latim, consectetur, oriunda de uma passagem de Lorem Ipsum, e, procurando por entre cita&ccedil;&otilde;es da palavra na literatura cl&aacute;ssica, descobriu a sua indubit&aacute;vel origem. Lorem Ipsum vem das se&ccedil;&otilde;es 1.10.32 e 1.10.33 do &quot;de Finibus Bonorum et Malorum&quot; (Os Extremos do Bem e do Mal), de C&iacute;cero, escrito em 45 AC. Este livro &eacute; um tratado de teoria da &eacute;tica muito popular na &eacute;poca da Renascen&ccedil;a. A primeira linha de Lorem Ipsum, &quot;Lorem Ipsum dolor sit amet...&quot; vem de uma linha na se&ccedil;&atilde;o 1.10.32.</p>', '1', '2020-01-20 15:13:35', '2020-01-20 15:19:02', null);
INSERT INTO `perguntas_type` VALUES ('4', 'Pergunta 4?', '<p>Ao contr&aacute;rio do que se acredita, Lorem Ipsum n&atilde;o &eacute; simplesmente um texto rand&ocirc;mico. Com mais de 2000 anos, suas ra&iacute;zes podem ser encontradas em uma obra de literatura latina cl&aacute;ssica datada de 45 AC. Richard McClintock, um professor de latim do Hampden-Sydney College na Virginia, pesquisou uma das mais obscuras palavras em latim, consectetur, oriunda de uma passagem de Lorem Ipsum, e, procurando por entre cita&ccedil;&otilde;es da palavra na literatura cl&aacute;ssica, descobriu a sua indubit&aacute;vel origem. Lorem Ipsum vem das se&ccedil;&otilde;es 1.10.32 e 1.10.33 do &quot;de Finibus Bonorum et Malorum&quot; (Os Extremos do Bem e do Mal), de C&iacute;cero, escrito em 45 AC. Este livro &eacute; um tratado de teoria da &eacute;tica muito popular na &eacute;poca da Renascen&ccedil;a. A primeira linha de Lorem Ipsum, &quot;Lorem Ipsum dolor sit amet...&quot; vem de uma linha na se&ccedil;&atilde;o 1.10.32.</p>', '1', '2020-01-20 15:13:49', '2020-01-20 15:19:05', null);

-- ----------------------------
-- Table structure for quem_somos
-- ----------------------------
DROP TABLE IF EXISTS `quem_somos`;
CREATE TABLE `quem_somos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` longtext COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `adesao` longtext COLLATE utf8_unicode_ci,
  `precos` longtext COLLATE utf8_unicode_ci,
  `coberturas` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of quem_somos
-- ----------------------------
INSERT INTO `quem_somos` VALUES ('1', 'APV BRASILL', '<p>Ao contr&aacute;rio do que se acredita, Lorem Ipsum n&atilde;o &eacute; simplesmente um texto rand&ocirc;mico. Com mais de 2000 anos, suas ra&iacute;zes podem ser encontradas em uma obra de literatura latina cl&aacute;ssica datada de 45 AC. Richard McClintock, um professor de latim do Hampden-Sydney College na Virginia, pesquisou uma das mais obscuras palavras em latim, consectetur, oriunda de uma passagem de Lorem Ipsum, e, procurando por entre cita&ccedil;&otilde;es da palavra na literatura cl&aacute;ssica, descobriu a sua indubit&aacute;vel origem. Lorem Ipsum vem das se&ccedil;&otilde;es 1.10.32 e 1.10.33 do &quot;de Finibus Bonorum et Malorum&quot; (Os Extremos do Bem e do Mal), de C&iacute;cero, escrito em 45 AC. Este livro &eacute; um tratado de teoria da &eacute;tica muito popular na &eacute;poca da Renascen&ccedil;a. A primeira linha de Lorem Ipsum, &quot;Lorem Ipsum dolor sit amet...&quot; vem de uma linha na se&ccedil;&atilde;o 1.10.32.</p>\r\n\r\n<p>O trecho padr&atilde;o original de Lorem Ipsum, usado desde o s&eacute;culo XVI, est&aacute; reproduzido abaixo para os interessados. Se&ccedil;&otilde;es 1.10.32 e 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; de Cicero tamb&eacute;m foram reproduzidas abaixo em sua forma exata original, acompanhada das vers&otilde;es para o ingl&ecirc;s da tradu&ccedil;&atilde;o feita por H. Rackham em 1914.</p>\r\n\r\n<p>Ao contr&aacute;rio do que se acredita, Lorem Ipsum n&atilde;o &eacute; simplesmente um texto rand&ocirc;mico. Com mais de 2000 anos, suas ra&iacute;zes podem ser encontradas em uma obra de literatura latina cl&aacute;ssica datada de 45 AC. Richard McClintock, um professor de latim do Hampden-Sydney College na Virginia, pesquisou uma das mais obscuras palavras em latim, consectetur, oriunda de uma passagem de Lorem Ipsum, e, procurando por entre cita&ccedil;&otilde;es da palavra na literatura cl&aacute;ssica, descobriu a sua indubit&aacute;vel origem. Lorem Ipsum vem das se&ccedil;&otilde;es 1.10.32 e 1.10.33 do &quot;de Finibus Bonorum et Malorum&quot; (Os Extremos do Bem e do Mal), de C&iacute;cero, escrito em 45 AC. Este livro &eacute; um tratado de teoria da &eacute;tica muito popular na &eacute;poca da Renascen&ccedil;a. A primeira linha de Lorem Ipsum, &quot;Lorem Ipsum dolor sit amet...&quot; vem de uma linha na se&ccedil;&atilde;o 1.10.32.</p>\r\n\r\n<p>Ao contr&aacute;rio do que se acredita, Lorem Ipsum n&atilde;o &eacute; simplesmente um texto rand&ocirc;mico. Com mais de 2000 anos, suas ra&iacute;zes podem ser encontradas em uma obra de literatura latina cl&aacute;ssica datada de 45 AC. Richard McClintock, um professor de latim do Hampden-Sydney College na Virginia, pesquisou uma das mais obscuras palavras em latim, consectetur, oriunda de uma passagem de Lorem Ipsum, e, procurando por entre cita&ccedil;&otilde;es da palavra na literatura cl&aacute;ssica, descobriu a sua indubit&aacute;vel origem. Lorem Ipsum vem das se&ccedil;&otilde;es 1.10.32 e 1.10.33 do &quot;de Finibus Bonorum et Malorum&quot; (Os Extremos do Bem e do Mal), de C&iacute;cero, escrito em 45 AC. Este livro &eacute; um tratado de teoria da &eacute;tica muito popular na &eacute;poca da Renascen&ccedil;a. A primeira linha de Lorem Ipsum, &quot;Lorem Ipsum dolor sit amet...&quot; vem de uma linha na se&ccedil;&atilde;o 1.10.32.</p>', '1', '2020-01-20 14:53:18', '2020-01-20 14:56:25', null, '<p>O trecho padr&atilde;o original de Lorem Ipsum, usado desde o s&eacute;culo XVI, est&aacute; reproduzido abaixo para os interessados. Se&ccedil;&otilde;es 1.10.32 e 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; de Cicero tamb&eacute;m foram reproduzidas abaixo em sua forma exata original, acompanhada das vers&otilde;es para o ingl&ecirc;s da tradu&ccedil;&atilde;o feita por H. Rackham em 1914.</p>', '<p>O trecho padr&atilde;o original de Lorem Ipsum, usado desde o s&eacute;culo XVI, est&aacute; reproduzido abaixo para os interessados. Se&ccedil;&otilde;es 1.10.32 e 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; de Cicero tamb&eacute;m foram reproduzidas abaixo em sua forma exata original, acompanhada das vers&otilde;es para o ingl&ecirc;s da tradu&ccedil;&atilde;o feita por H. Rackham em 1914.</p>', '<p>O trecho padr&atilde;o original de Lorem Ipsum, usado desde o s&eacute;culo XVI, est&aacute; reproduzido abaixo para os interessados. Se&ccedil;&otilde;es 1.10.32 e 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; de Cicero tamb&eacute;m foram reproduzidas abaixo em sua forma exata original, acompanhada das vers&otilde;es para o ingl&ecirc;s da tradu&ccedil;&atilde;o feita por H. Rackham em 1914.</p>');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `is_removable` tinyint(1) NOT NULL DEFAULT '1',
  `description` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_role_unique` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_access_path_unique` (`path`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seo_url_unique` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'alcateia', '5MMk4Lq3zU+Q2XB3PZVDoaqiBk4WJUgDvKwLao6MseLEmOnCzT4h272e+tbUkpEj0DVQTFqiQPqiloaTclqAjw==', 'alcateia@alcateiawebdigital.com', 'Alcatéia Digital', '1', '2015-08-18 15:36:38', '2015-08-18 15:45:44', null);
INSERT INTO `users` VALUES ('2', 'pedro.palopoli@hotmail.com', '5MMk4Lq3zU+Q2XB3PZVDoaqiBk4WJUgDvKwLao6MseLEmOnCzT4h272e+tbUkpEj0DVQTFqiQPqiloaTclqAjw==', 'pedro.palopoli@hotmail.com', 'Pedro Palópoli', '1', '2020-08-12 08:17:40', '2020-08-12 08:17:40', null);

-- ----------------------------
-- Table structure for users_roles
-- ----------------------------
DROP TABLE IF EXISTS `users_roles`;
CREATE TABLE `users_roles` (
  `user` int(10) unsigned NOT NULL,
  `role` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user`,`role`),
  KEY `users_roles_role_foreign` (`role`,`user`),
  CONSTRAINT `users_roles_role_foreign` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_roles_user_foreign` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users_roles
-- ----------------------------
INSERT INTO `users_roles` VALUES ('1', '1');
INSERT INTO `users_roles` VALUES ('2', '1');
INSERT INTO `users_roles` VALUES ('2', '2');
INSERT INTO `users_roles` VALUES ('2', '3');
