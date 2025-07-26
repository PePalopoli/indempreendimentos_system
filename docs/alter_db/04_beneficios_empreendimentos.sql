-- Criação da tabela beneficios_empreendimentos
-- Data: 2024-12-19

CREATE TABLE IF NOT EXISTS `beneficios_empreendimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `svg_code` text NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` int(11) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Inserir alguns dados de exemplo
INSERT INTO `beneficios_empreendimentos` (`titulo`, `svg_code`, `enabled`, `order`, `created_at`, `updated_at`) VALUES
('Localização Privilegiada', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/></svg>', 1, 1, NOW(), NOW()),
('Segurança 24h', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11H15.5A1.5,1.5 0 0,1 17,12.5V18.5A1.5,1.5 0 0,1 15.5,20H8.5A1.5,1.5 0 0,1 7,18.5V12.5A1.5,1.5 0 0,1 8.5,11H9.2V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z" fill="currentColor"/></svg>', 1, 2, NOW(), NOW()),
('Área de Lazer Completa', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14,6V4H10V6H4V18H20V6H14M12,17A3,3 0 0,1 9,14A3,3 0 0,1 12,11A3,3 0 0,1 15,14A3,3 0 0,1 12,17Z" fill="currentColor"/></svg>', 1, 3, NOW(), NOW()); 