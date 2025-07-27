-- Criação da tabela de plantas dos empreendimentos
-- Data: 2024-12-19

CREATE TABLE IF NOT EXISTS `empreendimentos_plantas` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `empreendimento_id` int(10) unsigned NOT NULL,
    `imagem` varchar(255) NOT NULL,
    `titulo_principal` varchar(120) NOT NULL,
    `sub_titulo` varchar(120) DEFAULT NULL,
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
ALTER TABLE `empreendimentos_plantas` COMMENT = 'Tabela de plantas dos empreendimentos - imagens de plantas com títulos';

-- Dados de exemplo (opcional)
INSERT INTO `empreendimentos_plantas` (`empreendimento_id`, `imagem`, `titulo_principal`, `sub_titulo`, `order`, `enabled`, `created_at`, `updated_at`) VALUES
(1, 'planta_exemplo.jpg', 'Planta Tipo 1', 'Apartamento 2 quartos', 1, 1, NOW(), NOW()),
(1, 'planta_exemplo2.jpg', 'Planta Tipo 2', 'Apartamento 3 quartos', 2, 1, NOW(), NOW()); 