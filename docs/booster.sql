/*
Navicat MySQL Data Transfer

Source Server         : LocalHost
Source Server Version : 80031
Source Host           : localhost:3306
Source Database       : booster

Target Server Type    : MYSQL
Target Server Version : 80031
File Encoding         : 65001

Date: 2025-04-23 00:09:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for apps_type
-- ----------------------------
DROP TABLE IF EXISTS `apps_type`;
CREATE TABLE `apps_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `image` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of apps_type
-- ----------------------------
INSERT INTO `apps_type` VALUES ('1', 'Candy Crush', '1', '2025-04-22 20:14:43', '2025-04-22 20:14:43', null, '246283d34debe833200a676e1184d0a4f495797d.png');
INSERT INTO `apps_type` VALUES ('2', 'Waze', '1', '2025-04-22 20:14:56', '2025-04-22 20:14:56', null, '71edbb08777a0532015f03c357860f830e754176.png');
INSERT INTO `apps_type` VALUES ('3', 'Moovit', '1', '2025-04-22 20:15:11', '2025-04-22 20:15:11', null, '22c72e6f7be5cc3b2387301919875dec3c61b3aa.png');
INSERT INTO `apps_type` VALUES ('4', 'Talking Tom', '1', '2025-04-22 20:15:30', '2025-04-22 20:15:30', null, '1004766195895484d71086ebf73991235440ceac.png');
INSERT INTO `apps_type` VALUES ('5', 'ZQ-app', '1', '2025-04-22 20:15:46', '2025-04-22 20:15:46', null, 'de7b92101c67d9fcbdaebd2bc6f04ee40efb2654.png');

-- ----------------------------
-- Table structure for banner
-- ----------------------------
DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` int unsigned NOT NULL,
  `title` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `image` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '1',
  `show_in` datetime DEFAULT NULL,
  `show_out` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `banner_image_unique` (`image`) USING BTREE,
  KEY `banner_type_foreign` (`type`) USING BTREE,
  CONSTRAINT `banner_type_foreign` FOREIGN KEY (`type`) REFERENCES `banner_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

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
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `width` int NOT NULL,
  `height` int NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of banner_type
-- ----------------------------
INSERT INTO `banner_type` VALUES ('1', 'Banner Home', '1920', '634', '1', '2020-05-05 08:37:32', '2020-05-05 08:37:32', null);
INSERT INTO `banner_type` VALUES ('2', 'Banner Home Mobile', '777', '257', '1', '2020-05-05 08:48:43', '2020-05-05 08:48:43', null);

-- ----------------------------
-- Table structure for depoimentos_type
-- ----------------------------
DROP TABLE IF EXISTS `depoimentos_type`;
CREATE TABLE `depoimentos_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `image` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of depoimentos_type
-- ----------------------------
INSERT INTO `depoimentos_type` VALUES ('1', 'Nome', '<p>Lorem ipsum dolor sit amet consectetur. Bibendum quis fringilla odio maecenas magna eu ultrices aliquam egestas.</p>', '1', '2025-04-22 20:00:49', '2025-04-22 20:00:49', null, '6f582d8fd4f276a8ebfc45d6dc3014ab46d4efe2.png');
INSERT INTO `depoimentos_type` VALUES ('2', 'Nome', '<p>Lorem ipsum dolor sit amet consectetur. Bibendum quis fringilla odio maecenas magna eu ultrices aliquam egestas.</p>', '1', '2025-04-22 20:00:49', '2025-04-22 20:00:49', '0000-00-00 00:00:00', '6f582d8fd4f276a8ebfc45d6dc3014ab46d4efe2.png');
INSERT INTO `depoimentos_type` VALUES ('3', 'Nome', '<p>Lorem ipsum dolor sit amet consectetur. Bibendum quis fringilla odio maecenas magna eu ultrices aliquam egestas.</p>', '1', '2025-04-22 20:00:49', '2025-04-22 20:00:49', '0000-00-00 00:00:00', '6f582d8fd4f276a8ebfc45d6dc3014ab46d4efe2.png');
INSERT INTO `depoimentos_type` VALUES ('4', 'Nome', '<p>Lorem ipsum dolor sit amet consectetur. Bibendum quis fringilla odio maecenas magna eu ultrices aliquam egestas.</p>', '1', '2025-04-22 20:00:49', '2025-04-22 20:00:49', '0000-00-00 00:00:00', '6f582d8fd4f276a8ebfc45d6dc3014ab46d4efe2.png');
INSERT INTO `depoimentos_type` VALUES ('5', 'Nome', '<p>Lorem ipsum dolor sit amet consectetur. Bibendum quis fringilla odio maecenas magna eu ultrices aliquam egestas.</p>', '1', '2025-04-22 20:00:49', '2025-04-22 20:00:49', '0000-00-00 00:00:00', '6f582d8fd4f276a8ebfc45d6dc3014ab46d4efe2.png');
INSERT INTO `depoimentos_type` VALUES ('6', 'Nome', '<p>Lorem ipsum dolor sit amet consectetur. Bibendum quis fringilla odio maecenas magna eu ultrices aliquam egestas.</p>', '1', '2025-04-22 20:00:49', '2025-04-22 20:00:49', '0000-00-00 00:00:00', '6f582d8fd4f276a8ebfc45d6dc3014ab46d4efe2.png');
INSERT INTO `depoimentos_type` VALUES ('7', 'Nome', '<p>Lorem ipsum dolor sit amet consectetur. Bibendum quis fringilla odio maecenas magna eu ultrices aliquam egestas.</p>', '1', '2025-04-22 20:00:49', '2025-04-22 20:00:49', '0000-00-00 00:00:00', '6f582d8fd4f276a8ebfc45d6dc3014ab46d4efe2.png');
INSERT INTO `depoimentos_type` VALUES ('8', 'Nome', '<p>Lorem ipsum dolor sit amet consectetur. Bibendum quis fringilla odio maecenas magna eu ultrices aliquam egestas.</p>', '1', '2025-04-22 20:00:49', '2025-04-22 20:00:49', '0000-00-00 00:00:00', '6f582d8fd4f276a8ebfc45d6dc3014ab46d4efe2.png');
INSERT INTO `depoimentos_type` VALUES ('9', 'Nome', '<p>Lorem ipsum dolor sit amet consectetur. Bibendum quis fringilla odio maecenas magna eu ultrices aliquam egestas.</p>', '1', '2025-04-22 20:00:49', '2025-04-22 20:00:49', '0000-00-00 00:00:00', '6f582d8fd4f276a8ebfc45d6dc3014ab46d4efe2.png');
INSERT INTO `depoimentos_type` VALUES ('10', 'Nome', '<p>Lorem ipsum dolor sit amet consectetur. Bibendum quis fringilla odio maecenas magna eu ultrices aliquam egestas.</p>', '1', '2025-04-22 20:00:49', '2025-04-22 20:00:49', '0000-00-00 00:00:00', '6f582d8fd4f276a8ebfc45d6dc3014ab46d4efe2.png');

-- ----------------------------
-- Table structure for impulsos_type
-- ----------------------------
DROP TABLE IF EXISTS `impulsos_type`;
CREATE TABLE `impulsos_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `image` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of impulsos_type
-- ----------------------------
INSERT INTO `impulsos_type` VALUES ('1', 'Usuário abre o app de preferência', '1', '2025-04-22 20:25:07', '2025-04-22 20:25:07', null, 'b1fde9bfcfef368554e5c2529781d9732d390db0.png');
INSERT INTO `impulsos_type` VALUES ('2', 'Usuário é impactado pelo anúncio.', '1', '2025-04-22 20:25:21', '2025-04-22 20:25:21', null, '40bc3bbdd825c70b038ef54364957fef4b764c87.png');
INSERT INTO `impulsos_type` VALUES ('3', 'Usuário é direcionado a loja de aplicativos.', '1', '2025-04-22 20:25:34', '2025-04-22 20:25:34', null, '08ad51d9101b1c42a7dd7709596ed98769434ae2.png');
INSERT INTO `impulsos_type` VALUES ('4', 'Usuário fazendo dowloand do seu app.', '1', '2025-04-22 20:25:46', '2025-04-22 20:25:46', null, '4e9645f340eaa73c33e918ea2649b9e2e8c377b9.png');
INSERT INTO `impulsos_type` VALUES ('5', 'Usuário interagindo com seu app.', '1', '2025-04-22 20:26:01', '2025-04-22 20:26:01', null, '416a1d79adc715572e319a5066f95fa6dc6a4698.png');

-- ----------------------------
-- Table structure for institutional
-- ----------------------------
DROP TABLE IF EXISTS `institutional`;
CREATE TABLE `institutional` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` int unsigned NOT NULL,
  `title` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `institutional_url_unique` (`url`) USING BTREE,
  KEY `institutional_type_foreign` (`type`) USING BTREE,
  CONSTRAINT `institutional_ibfk_1` FOREIGN KEY (`type`) REFERENCES `institutional_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of institutional
-- ----------------------------
INSERT INTO `institutional` VALUES ('1', '1', '+150M', '150m', '<p>De interações realizadas <br>por mês</p>', '1', '2025-04-22 21:39:45', '2025-04-22 21:39:45', null);
INSERT INTO `institutional` VALUES ('2', '1', '350%', '350', '<p>Crescimento em downloads (no último ano)</p>', '1', '2025-04-22 21:39:57', '2025-04-22 21:39:57', null);
INSERT INTO `institutional` VALUES ('3', '1', '+100', '100', '<p>Clientes satisfeitos em todo mundo</p>', '1', '2025-04-22 17:40:11', '2025-04-22 17:40:11', null);
INSERT INTO `institutional` VALUES ('4', '3', 'O que é a Booster?', 'o-que-e-a-booster', '<p>Somos uma AdTech (tecnologia de publicidade), e temos em nosso DNA a inovação, a tecnologia e a alta performance. Nossa missão é conectar pessoas a marcas que fazem a diferença. Por isso, nós impulsionamos a aquisição e retenção de usuários para a sua marca por meio de publicidade mobile e hub de parcerias, com garantia de segurança e assertividade.</p>', '1', '2025-04-22 20:02:32', '2025-04-22 20:02:32', null);
INSERT INTO `institutional` VALUES ('5', '3', 'Por que anunciar com a Booster?', 'por-que-anunciar-com-a-booster', '<p>Porque prezamos por relacionamentos sólidos com nossos clientes, baseados na confiança, transparência e resultados tangíveis. Nosso objetivo principal é entender as necessidades e expectativas dos nossos clientes e garantir que nossos serviços atendam a esses requisitos de forma consistente, sempre respeitando a estratégia do cliente e seguindo regras de divulgação estabelecidas.</p>', '1', '2025-04-22 20:02:51', '2025-04-22 20:02:51', null);
INSERT INTO `institutional` VALUES ('6', '3', 'Quanto custa os serviços da Booster? Qual o budget mínimo para testar?', 'quanto-custa-os-servicos-da-booster-qual-o-budget-minimo-para-testar', '<p>Aqui você só paga se houver a conversão, de acordo com todos os termos estabelecidos em contrato. Não existe valor mínimo, nós podemos começar com um budget menor e uma campanha piloto. Com resultados positivos, podemos expandir gradualmente o investimento à medida que geramos resultados positivos para sua empresa.</p>', '1', '2025-04-22 20:03:12', '2025-04-22 20:03:12', null);
INSERT INTO `institutional` VALUES ('7', '3', 'Como vou poder acompanhar o andamento e controle da campanha?', 'como-vou-poder-acompanhar-o-andamento-e-controle-da-campanha', '<p>Prezamos pela transparência em todos os nossos processos, além de atuarmos em colaboração e controle conjunto com os nossos clientes. Semanalmente seu gerente de conta fará um checkpoint para detalhar as ações em andamento, fornecer acesso a um dash para acompanhamento da performance em tempo real e relatórios com métricas-chave como taxa de conversão, custo por aquisição e ROI. Além de manter uma comunicação transparente e aberta, também temos flexibilidade para nos adaptarmos às demandas individuais de cada cliente.</p>', '1', '2025-04-22 20:03:30', '2025-04-22 20:03:30', null);
INSERT INTO `institutional` VALUES ('8', '3', 'Como a Booster garante a qualidade do tráfego?', 'como-a-booster-garante-a-qualidade-do-trafego', '<p>Nós monitoramos eventos que vão além daqueles em que somos remunerados para garantir a qualidade. É o que chamamos de soft KPIs, como por exemplo o uso de cupons, inadimplência e outras movimentações no app. Além disso, todas nossas práticas são orientadas por uma robusta política de qualidade que inclui identificação de padrões de comportamento suspeitos e otimização constante com base nas métricas de desempenho do cliente. Nosso compromisso com a transparência e integridade nos diferencia, assegurando que cada interação e conversão seja genuína e valiosa para os nossos clientes.</p>', '1', '2025-04-22 20:03:55', '2025-04-22 20:03:55', null);
INSERT INTO `institutional` VALUES ('9', '3', 'Quais medidas de segurança a Booster implementa para assegurar a integridade e a qualidade das campanhas mobile?', 'quais-medidas-de-seguranca-a-booster-implementa-para-assegurar-a-integridade-e-a-qualidade-das-campanhas-mobile', '<p>Para nós a qualidade é coisa séria. Por isso, utilizamos ferramentas avançadas de verificação de tráfego para identificar e filtrar atividades suspeitas, também controlamos diariamente os CAPs de cliques, installs, eventos, budget, índices de P360 e taxa de conversão de nossos parceiros, garantindo que os resultados das campanhas sejam autênticos e precisos. Se ainda assim houver fraude, você não pagará por essas conversões.</p>', '1', '2025-04-22 20:04:20', '2025-04-22 20:04:20', null);
INSERT INTO `institutional` VALUES ('10', '3', 'Como podemos ter certeza de que minha campanha trará os resultados desejados para meu negócio?', 'como-podemos-ter-certeza-de-que-minha-campanha-trara-os-resultados-desejados-para-meu-negocio', '<p>Nossa abordagem se baseia em dados concretos e estratégias comprovadas. Por isso, antes de lançar uma campanha, nós fazemos uma análise do seu mercado-alvo, concorrentes, comportamento do cliente. Isso nos permite identificar oportunidades e desenvolver estratégias eficazes. Também mantemos uma comunicação aberta, que nos permite monitoramento e otimização contínuos. Temos uma sólida experiência em ajudar empresas como a sua a alcançar seus objetivos de marketing.</p>', '1', '2025-04-22 20:04:37', '2025-04-22 20:04:37', null);
INSERT INTO `institutional` VALUES ('11', '4', 'Alcance mais audiências.', 'alcance-mais-audiencias', '<p>Atinja diferentes segmentos de audiência em várias plataformas. Tenha mais&nbsp; abrangência e segmentação.</p>', '1', '2025-04-22 20:27:28', '2025-04-22 20:27:28', null);
INSERT INTO `institutional` VALUES ('12', '4', 'Usuários que ficam.', 'usuarios-que-ficam', '<p>Ao diversificar os canais de aquisição, aumentamos a retenção de usuários em +33% no iOS e +21% no Android entre a 1ª e a 4ª semana.</p>', '1', '2025-04-22 20:27:44', '2025-04-22 20:27:44', null);
INSERT INTO `institutional` VALUES ('13', '4', 'Menos churn. Mais LTV.', 'menos-churn-mais-ltv', '<p>Com estratégias avançadas de segmentação e aquisição, reduzimos em 33% a taxa média de desinstalação.</p>', '1', '2025-04-22 20:28:04', '2025-04-22 20:28:04', null);
INSERT INTO `institutional` VALUES ('14', '5', 'Mais receita por usuário', 'mais-receita-por-usuario', '<p>Com retargeting in-app, seus usuários geram 57% mais receita, garantindo mais conversões e engajamento contínuo.</p>', '1', '2025-04-22 20:29:09', '2025-04-22 20:29:09', null);
INSERT INTO `institutional` VALUES ('15', '5', 'Impacto preciso. Resultados reais.', '', '<p>Utilizamos Audience Builder e All Media Source para conectar seu app ao usuário certo. Aquele que fica.</p>', '1', '2025-04-22 20:29:23', '2025-04-22 20:29:23', null);
INSERT INTO `institutional` VALUES ('17', '5', 'Cross-sell inteligente.', 'cross-sell-inteligente-interna', '<p>Monetize sua base com ofertas relevantes dentro do app. Direcionamos campanhas para ampliar vendas e maximizar seu ROI.</p>', '1', '2025-04-22 20:29:50', '2025-04-22 20:29:50', null);
INSERT INTO `institutional` VALUES ('18', '5', 'Crescimento  escalável.', 'crescimento-escalavel', '<p>Tenha crescimento contínuo do seu app, com estratégias que ampliam DAU, WAU e MAU.</p>', '1', '2025-04-22 20:30:12', '2025-04-22 20:30:12', null);
INSERT INTO `institutional` VALUES ('20', '5', 'Otimização CPA.', 'otimizacao-cpa-interna', '<p>Reduzimos custos e maximizamos o retorno, ajustando o custo por aquisição para atrair usuários mais engajados e lucrativos.</p>', '1', '2025-04-22 20:30:35', '2025-04-22 20:30:35', null);
INSERT INTO `institutional` VALUES ('22', '5', 'Mais receita por usuário', 'mais-receita-por-usuario-interna', '<p>Com retargeting in-app, seus usuários geram 57% mais receita, garantindo mais conversões e engajamento contínuo.</p>', '1', '2025-04-22 20:31:02', '2025-04-22 20:31:02', null);
INSERT INTO `institutional` VALUES ('23', '6', 'CPA', 'cpa', '<p class=\"card-model-text\">Receba por ação, seja ela uma compra ou outra desejada.</p>', '1', '2025-04-22 20:51:54', '2025-04-22 20:51:54', null);
INSERT INTO `institutional` VALUES ('24', '6', 'CPC', 'cpc', '<p><span style=\"color: rgb(0, 0, 0); font-family: Archivo, sans-serif; text-align: center;\">Recebe por clique.</span></p>', '1', '2025-04-22 23:49:10', '2025-04-22 23:49:10', null);
INSERT INTO `institutional` VALUES ('25', '6', 'CPL', 'cpl', '<p class=\"card-model-text\">Receba ao gerar leads.</p>', '1', '2025-04-22 20:52:33', '2025-04-22 20:52:33', null);
INSERT INTO `institutional` VALUES ('26', '6', 'CPI', 'cpi', '<p class=\"card-model-text\">Receba por impressões.</p>', '1', '2025-04-22 20:52:55', '2025-04-22 20:52:55', null);
INSERT INTO `institutional` VALUES ('27', '6', 'Revenue Share', 'revenue-share', '<p><span style=\"color: rgb(0, 0, 0); font-family: Archivo, sans-serif; text-align: center;\">Compartilhamento a receita gerada.</span></p>', '1', '2025-04-22 23:49:41', '2025-04-22 23:49:41', null);
INSERT INTO `institutional` VALUES ('28', '7', 'Disparadores de e-mail marketing', 'disparadores-de-e-mail-marketing', '<p>Disparadores de e-mail marketing</p>', '1', '2025-04-22 20:36:03', '2025-04-22 20:36:03', null);
INSERT INTO `institutional` VALUES ('29', '7', 'Sites de conteúdo', 'sites-de-conteudo', '<p>Sites de conteúdo</p>', '1', '2025-04-22 20:36:11', '2025-04-22 20:36:11', null);
INSERT INTO `institutional` VALUES ('30', '7', 'Grupos de redes sociais', 'grupos-de-redes-sociais', '<p>Grupos de redes sociais</p>', '1', '2025-04-22 20:36:23', '2025-04-22 20:36:23', null);
INSERT INTO `institutional` VALUES ('31', '7', 'Corbans', 'corbans', '<p>Corbans</p>', '1', '2025-04-22 20:36:31', '2025-04-22 20:36:31', null);
INSERT INTO `institutional` VALUES ('32', '7', 'Push notification', 'push-notification', '<p>Push notification</p>', '1', '2025-04-22 20:36:41', '2025-04-22 20:36:41', null);
INSERT INTO `institutional` VALUES ('33', '7', 'Influenciadores', 'influenciadores', '<p>Influenciadores</p>', '1', '2025-04-22 20:36:52', '2025-04-22 20:36:52', null);
INSERT INTO `institutional` VALUES ('34', '7', 'Compra de mídia', 'compra-de-midia', '<p>Compra de mídia</p>', '1', '2025-04-22 20:37:02', '2025-04-22 20:37:02', null);
INSERT INTO `institutional` VALUES ('35', '7', 'Retargeting', 'retargeting', '<p>Retargeting</p>', '1', '2025-04-22 20:37:12', '2025-04-22 20:37:12', null);
INSERT INTO `institutional` VALUES ('36', '10', '2000', '2000', '<p class=\"historia-text\">\r\n                            Nasce a Foregon com a missão de facilitar o acesso a produtos financeiros por meio da internet.\r\n                        </p>', '1', '2025-04-22 20:42:05', '2025-04-22 20:42:05', null);
INSERT INTO `institutional` VALUES ('37', '10', '2021', '2021', '<p class=\"historia-text\">\r\n                            Uma rede de afiliados é estruturada como novo canal de aquisição e já em seu primeiro ano cresce a receita.\r\n                        </p>', '1', '2025-04-22 20:42:22', '2025-04-22 20:42:22', null);
INSERT INTO `institutional` VALUES ('38', '10', '2023', '2023', '<p class=\"historia-text\">\r\n                            A Foregon estrutura uma nova unidade de negócio, a Booster Ad. O objetivo é  acelerar o crescimento e aquisição de usuários para marcas de forma ágil e segura.\r\n                        </p>', '1', '2025-04-22 20:42:40', '2025-04-22 20:42:40', null);
INSERT INTO `institutional` VALUES ('39', '10', '2024', '2024', '<p class=\"historia-text\">\r\n                            A Booster Ad inaugura seu novo escritório em São Paulo para ficar mais próxima dos clientes e com acesso a um centro dinâmico e influente de negócios e tecnologia.\r\n                        </p>', '1', '2025-04-22 20:42:55', '2025-04-22 20:42:55', null);
INSERT INTO `institutional` VALUES ('41', '11', 'Transparência', 'transparencia-interna-empresa', '<p class=\"valor-text\">Acreditamos que relações de confiança é o direcionamento para tudo o que fazemos.</p>', '1', '2025-04-22 20:44:11', '2025-04-22 20:44:11', null);
INSERT INTO `institutional` VALUES ('43', '11', 'Resolutivos', 'resolutivos-interna-empresa', '<p class=\"valor-text\">Estamos comprometidos com nossas entregas, por isso aplicamos nosso know-how para atuar com agilidade e eficiência.</p>', '1', '2025-04-22 20:44:43', '2025-04-22 20:44:43', null);
INSERT INTO `institutional` VALUES ('44', '11', 'Inquietos', 'inquietos-interna-empresa', '<p class=\"valor-text\">A inovação não espera, ela se antecipa. Abraçamos a mudança para superar limites, unindo análise e experiência para abrir novos caminhos.</p>', '1', '2025-04-22 20:45:01', '2025-04-22 20:45:01', null);
INSERT INTO `institutional` VALUES ('45', '11', 'Tecnológicos', 'tecnologicos-interna-empresa', '<p class=\"valor-text\">Combinamos tecnologia e soluções de alta performance para agir de forma estratégica e eficaz.</p>', '1', '2025-04-22 20:45:25', '2025-04-22 20:45:25', null);
INSERT INTO `institutional` VALUES ('46', '13', 'Acompanhamento de performance em tempo real.', 'acompanhamento-de-performance-em-tempo-real', '<p>Acompanhamento de performance em tempo real.</p>', '1', '2025-04-22 20:48:28', '2025-04-22 20:48:28', null);
INSERT INTO `institutional` VALUES ('47', '13', 'Relatórios detalhados.', 'relatorios-detalhados', '<p>Relatórios detalhados.</p>', '1', '2025-04-22 20:48:42', '2025-04-22 20:48:42', null);
INSERT INTO `institutional` VALUES ('48', '13', 'Campanhas exclusivas e lucrativas.', 'campanhas-exclusivas-e-lucrativas', '<p>Campanhas exclusivas e lucrativas.</p>', '1', '2025-04-22 20:48:51', '2025-04-22 20:48:51', null);
INSERT INTO `institutional` VALUES ('49', '13', 'Pagamentos pontuais e seguros.', 'pagamentos-pontuais-e-seguros', '<p>Pagamentos pontuais e seguros.</p>', '1', '2025-04-22 20:49:01', '2025-04-22 20:49:01', null);
INSERT INTO `institutional` VALUES ('50', '13', 'Suporte dedicado e especializado.', 'suporte-dedicado-e-especializado', '<p>Suporte dedicado e especializado.</p>', '1', '2025-04-22 20:49:11', '2025-04-22 20:49:11', null);
INSERT INTO `institutional` VALUES ('51', '6', 'CPM', 'cpm-interna', '<p class=\"card-model-text\">Receba a cada mil impressões.</p>', '1', '2025-04-22 20:53:48', '2025-04-22 20:53:48', null);
INSERT INTO `institutional` VALUES ('52', '2', 'A BoosterAd é uma adtech especializada em aquisição', 'a-boosterad-e-uma-adtech-especializada-em-aquisicao-e-retencao-de-usuarios-para-apps-mais-que-aumentar-o-numero-de-insta', '<p>A BoosterAd é uma adtech especializada em aquisição e retenção de usuários para apps. Mais que aumentar o número de instalações, buscamos gerar mais interações.</p>', '1', '2025-04-22 21:41:21', '2025-04-22 21:41:21', null);

-- ----------------------------
-- Table structure for institutional_type
-- ----------------------------
DROP TABLE IF EXISTS `institutional_type`;
CREATE TABLE `institutional_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `institutional_type_url_unique` (`url`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of institutional_type
-- ----------------------------
INSERT INTO `institutional_type` VALUES ('1', 'Nossos números', 'nossos-numeros', '<p><br></p>', '1', '2025-04-22 17:38:47', '2025-04-22 17:38:47', null);
INSERT INTO `institutional_type` VALUES ('2', 'Baixar é fácil. Reter é nossa <span>estratégia</span>.', 'baixar-e-facil-reter-e-nossa-span-estrategia-span', 'Baixar é fácil. Reter é nossa <span>estratégia</span>.', '1', '2025-04-22 17:41:46', '2025-04-22 17:41:46', null);
INSERT INTO `institutional_type` VALUES ('3', 'Perguntas', 'faq', '<p>FAQ</p>', '1', '2025-04-22 20:02:01', '2025-04-22 20:02:01', null);
INSERT INTO `institutional_type` VALUES ('4', 'Diversifique seus canais. Amplifique seus resultados.', 'diversifique-seus-canais-amplifique-seus-resultados', '<p>Diversifique seus canais. Amplifique seus resultados.</p>', '1', '2025-04-22 20:26:52', '2025-04-22 20:26:52', null);
INSERT INTO `institutional_type` VALUES ('5', 'ROI com retargeting eficiente.', 'roi-com-retargeting-eficiente', '<p>ROI com retargeting eficiente.</p>', '1', '2025-04-22 20:28:35', '2025-04-22 20:28:35', null);
INSERT INTO `institutional_type` VALUES ('6', 'Expanda sem desperdiçar investimento.', 'expanda-sem-desperdicar-investimento', '<p>Expanda sem desperdiçar investimento.</p>', '1', '2025-04-22 20:32:32', '2025-04-22 20:32:32', null);
INSERT INTO `institutional_type` VALUES ('7', 'Tenha mais diversificação de formatos.', 'tenha-mais-diversificacao-de-formatos', '<p>Tenha mais diversificação de formatos.</p>', '1', '2025-04-22 20:35:33', '2025-04-22 20:35:33', null);
INSERT INTO `institutional_type` VALUES ('8', 'Sobre nós', 'sobre-nos', '<p class=\"empresa-sobre-text\" data-aos=\"fade-up\" data-aos-duration=\"1000\" data-aos-delay=\"200\">\r\n                    Inovação, tecnologia e alta performance estão no nosso DNA. \r\n                    \r\n                    <br>Usamos ferramentas modernas para gerenciar parceiros, DSPs e SSPs globalmente, impulsionando marcas e parceiros.\r\n                    \r\n                    <br>Nosso time (ou melhor, nossa tripulação), é formada por especialistas em growth marketing, prontos para alavancar seus resultados. \r\n    \r\n                    <br>Quer fazer parte da nossa tripulação? Cadastre-se agora!\r\n                </p>', '1', '2025-04-22 20:40:11', '2025-04-22 20:40:11', null);
INSERT INTO `institutional_type` VALUES ('9', 'Depoimento-interno-empresa', 'depoimento-interno-empresa', '<p class=\"depoimento-text\" data-aos=\"fade-up\" data-aos-duration=\"1000\" data-aos-delay=\"200\">\r\n                        \" Texto depoimento \"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\"\r\n                    </p>\r\n                    <p class=\"depoimento-author\" data-aos=\"fade-up\" data-aos-duration=\"1000\" data-aos-delay=\"300\">| Nome Sobrenome, Cargo</p>', '1', '2025-04-22 20:41:07', '2025-04-22 20:41:07', null);
INSERT INTO `institutional_type` VALUES ('10', 'Nossa Historia', 'nossa-historia', '<p>Nossa Historia</p>', '1', '2025-04-22 20:41:38', '2025-04-22 20:41:38', null);
INSERT INTO `institutional_type` VALUES ('11', 'Nossos-valores-interno-empresa', 'nossos-valores-interno-empresa', '<p>Nossos-valores-interno-empresa</p>', '1', '2025-04-22 20:43:28', '2025-04-22 20:43:28', null);
INSERT INTO `institutional_type` VALUES ('12', 'Quer trabalhar com a gente? - interna empresa', 'quer-trabalhar-com-a-gente-interna-empresa', '<p class=\"final-text\" data-aos=\"fade-up\" data-aos-duration=\"1000\" data-aos-delay=\"200\">\r\n                        Acreditamos que resultados extraordinários vêm de pessoas extraordinárias.&nbsp;\r\n                        <br><br>\r\n                        Valorizamos a diversidade de ideias e talentos, que nos impulsionam a sermos inovadores e disruptivos.\r\n                        Estamos prontos para ajudar você a alcançar seus objetivos de marketing digital com soluções personalizadas e eficientes.\r\n                    </p>', '1', '2025-04-22 20:46:26', '2025-04-22 20:46:26', null);
INSERT INTO `institutional_type` VALUES ('13', 'seja parceiro - interna publisher', 'seja-parceiro-interna-publisher', '<p>seja parceiro - interna publisher</p>', '1', '2025-04-22 20:47:36', '2025-04-22 20:47:36', null);

-- ----------------------------
-- Table structure for marcas_type
-- ----------------------------
DROP TABLE IF EXISTS `marcas_type`;
CREATE TABLE `marcas_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `image` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of marcas_type
-- ----------------------------
INSERT INTO `marcas_type` VALUES ('2', 'neon', '1', '2025-04-22 16:37:10', '2025-04-22 16:37:10', null, '916d2953be26965244b1d863cd7959b8e06ef040.png');
INSERT INTO `marcas_type` VALUES ('3', 'portoseguro', '1', '2025-04-22 16:40:04', '2025-04-22 16:40:04', null, '843f6ba0e76dadba58283ac9e076851c5dffab17.png');
INSERT INTO `marcas_type` VALUES ('4', 'bradesco', '1', '2025-04-22 16:40:21', '2025-04-22 16:40:21', null, '3e31642bfbe13d9f47adb38102691c53eab2abe0.png');
INSERT INTO `marcas_type` VALUES ('5', 'santander', '1', '2025-04-22 16:47:32', '2025-04-22 16:47:32', null, '016586d27173bd9b226403219fc1762f15467a4a.png');
INSERT INTO `marcas_type` VALUES ('6', 'acordocerto', '1', '2025-04-22 16:47:56', '2025-04-22 16:47:56', null, '10a10dfd6090f67a4ae67cd426385e3d9b3c4a03.png');

-- ----------------------------
-- Table structure for resultados_type
-- ----------------------------
DROP TABLE IF EXISTS `resultados_type`;
CREATE TABLE `resultados_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `icon` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of resultados_type
-- ----------------------------
INSERT INTO `resultados_type` VALUES ('1', 'Brand Safety', '<p>Monitoramento contínuo de criativos e impressões.</p>', '1', '2025-04-22 17:53:18', '2025-04-22 17:53:18', null, 'fas fa-image');
INSERT INTO `resultados_type` VALUES ('2', 'Foco em Resultados', '<p>Monitoramos e entregamos resultados focados nos seus KPIs.</p>', '1', '2025-04-22 17:56:11', '2025-04-22 17:56:11', null, 'fa-pen-to-square');
INSERT INTO `resultados_type` VALUES ('3', 'Tecnologia anti-fraude', '<p>Tecnologia anti-fraude, ambiente seguro e confiável.</p>', '1', '2025-04-22 17:56:33', '2025-04-22 17:56:33', null, 'fa-lock');
INSERT INTO `resultados_type` VALUES ('4', 'Tráfego qualificado', '<p>Refinamento diário de segmentação e inventário.</p>', '1', '2025-04-22 17:56:55', '2025-04-22 17:56:55', null, 'fa-medal');
INSERT INTO `resultados_type` VALUES ('5', 'Usuários que ficam', '<p>Retenção de usuários com campanhas de retargeting.</p>', '1', '2025-04-22 17:57:15', '2025-04-22 17:57:15', null, 'fa-user');
INSERT INTO `resultados_type` VALUES ('6', 'Tecnologia em Ação', '<p>Aplicação de IA para otimização de resultados.</p>', '1', '2025-04-22 17:57:37', '2025-04-22 17:57:37', null, 'fa-mobile-screen-button');
INSERT INTO `resultados_type` VALUES ('7', 'Acompanhamento Integrado', '<p>Gestão e monitoramento da rede de parceiros.</p>', '1', '2025-04-22 17:57:59', '2025-04-22 17:57:59', null, 'fa-chart-simple');
INSERT INTO `resultados_type` VALUES ('8', 'Apoio sem intervalos', '<p>Customer Success Manager disponível 24h por dia.</p>', '1', '2025-04-22 17:58:22', '2025-04-22 17:58:22', null, 'fa-headphones');
INSERT INTO `resultados_type` VALUES ('9', 'Decisões Baseadas em Dados', '<p>Relatórios detalhados com análises e insights acionáveis.</p>', '1', '2025-04-22 17:58:42', '2025-04-22 17:58:42', null, 'fa-list');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `is_removable` tinyint(1) NOT NULL DEFAULT '1',
  `description` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `roles_role_unique` (`role`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

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
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `roles` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `methods` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `host` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ip` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `order` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `roles_access_path_unique` (`path`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

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
  `id` int NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `title` varchar(80) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `keyword` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `h1` varchar(80) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `order` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `seo_url_unique` (`url`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of seo
-- ----------------------------

-- ----------------------------
-- Table structure for servicos_type
-- ----------------------------
DROP TABLE IF EXISTS `servicos_type`;
CREATE TABLE `servicos_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `image` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of servicos_type
-- ----------------------------
INSERT INTO `servicos_type` VALUES ('2', 'hub-parceiros', '<p>Otimizamos suas campanhas para maximizar resultados e ROI através de estratégias data-driven.</p>', '1', '2025-04-23 00:01:36', '2025-04-23 00:01:36', null, 'bfc3a890542222c6543e710b04268dcd3fd4d204.png', 'hub-parceiros');
INSERT INTO `servicos_type` VALUES ('3', 'mobilemkt', '<p>Estratégias completas de marketing digital para aumentar sua presença online e gerar mais leads.</p>', '1', '2025-04-23 00:00:47', '2025-04-23 00:00:47', null, '9d194e7e55be26c51db6e4c927732f6160634db1.png', 'mobile-marketing');
INSERT INTO `servicos_type` VALUES ('4', 'mobileretarg', '<p>Análise especializada e recomendações personalizadas para impulsionar seu negócio.</p>', '1', '2025-04-23 00:00:51', '2025-04-23 00:00:51', null, 'bb5c88b8e2342382e60658f3843c1fad9c6d6061.png', 'hub-parceiros');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(120) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `tipo_user` int unsigned DEFAULT NULL,
  `image_avatar` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `users_username_unique` (`username`) USING BTREE,
  UNIQUE KEY `users_email_unique` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'alcateia', '5MMk4Lq3zU+Q2XB3PZVDoaqiBk4WJUgDvKwLao6MseLEmOnCzT4h272e+tbUkpEj0DVQTFqiQPqiloaTclqAjw==', 'alcateia@alcateiawebdigital.com', 'Alcatéia Digital', '1', '2020-05-05 08:36:09', '2020-05-05 08:36:09', null, '1', null);

-- ----------------------------
-- Table structure for users_roles
-- ----------------------------
DROP TABLE IF EXISTS `users_roles`;
CREATE TABLE `users_roles` (
  `user` int unsigned NOT NULL,
  `role` int unsigned NOT NULL,
  PRIMARY KEY (`user`,`role`) USING BTREE,
  KEY `users_roles_role_foreign` (`role`,`user`) USING BTREE,
  CONSTRAINT `users_roles_role_foreign` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_roles_user_foreign` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of users_roles
-- ----------------------------
INSERT INTO `users_roles` VALUES ('1', '1');
