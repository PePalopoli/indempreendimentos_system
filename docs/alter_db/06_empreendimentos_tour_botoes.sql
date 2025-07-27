-- Criação da tabela de botões de tour para empreendimentos (SEM FOREIGN KEYS)
-- Data: 2024-12-19
-- Versão sem FKs para evitar problemas de compatibilidade

-- Criação da tabela principal
CREATE TABLE IF NOT EXISTS `empreendimentos_tour_botoes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empreendimento_id` int(10) unsigned NOT NULL,
  `texto_botao` varchar(100) NOT NULL,
  `url_destino` varchar(500) NOT NULL,
  `target_blank` tinyint(1) DEFAULT '1',
  `cor_fundo` varchar(7) DEFAULT '#007bff',
  `cor_texto` varchar(7) DEFAULT '#ffffff',
  `icone_class` varchar(50) DEFAULT NULL,
  `order` int(11) DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_empreendimento` (`empreendimento_id`),
  KEY `idx_order` (`order`),
  KEY `idx_enabled` (`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Comentários para documentação
ALTER TABLE `empreendimentos_tour_botoes` COMMENT = 'Tabela de botões de tour virtual para empreendimentos';

-- Inserir alguns dados de exemplo (opcional)
INSERT INTO `empreendimentos_tour_botoes` (`empreendimento_id`, `texto_botao`, `url_destino`, `target_blank`, `cor_fundo`, `cor_texto`, `icone_class`, `order`, `enabled`, `created_at`, `updated_at`) VALUES
(1, 'Tour Virtual 360°', 'https://exemplo.com/tour-360', 1, '#28a745', '#ffffff', 'fas fa-vr-cardboard', 1, 1, NOW(), NOW()),
(1, 'Vídeo do Empreendimento', 'https://youtube.com/watch?v=exemplo', 1, '#dc3545', '#ffffff', 'fab fa-youtube', 2, 1, NOW(), NOW()),
(1, 'Galeria de Fotos', 'https://exemplo.com/galeria', 1, '#17a2b8', '#ffffff', 'fas fa-images', 3, 1, NOW(), NOW());

-- Para adicionar as foreign keys depois (execute separadamente se necessário):
/*
-- Verificar estruturas primeiro:
DESCRIBE empreendimentos;

-- Depois adicionar as FKs:
ALTER TABLE `empreendimentos_tour_botoes`
ADD CONSTRAINT `fk_tour_botoes_empreendimento` 
FOREIGN KEY (`empreendimento_id`) REFERENCES `empreendimentos`(`id`) ON DELETE CASCADE;
*/ 