-- Script para verificar e criar slugs para etapas de empreendimentos
-- Execute este script para garantir que todas as etapas tenham slug

-- Verificar etapas sem slug
SELECT id, titulo, slug FROM obra_etapas WHERE slug IS NULL OR slug = '';

-- Atualizar slugs baseados no título (exemplos)
-- Você deve ajustar conforme seus dados específicos

-- Exemplo 1: Se você tem uma etapa chamada "Residencial"
UPDATE obra_etapas SET slug = 'residencial' WHERE titulo LIKE '%Residencial%' AND (slug IS NULL OR slug = '');

-- Exemplo 2: Se você tem uma etapa chamada "Comercial"  
UPDATE obra_etapas SET slug = 'comercial' WHERE titulo LIKE '%Comercial%' AND (slug IS NULL OR slug = '');

-- Exemplo 3: Se você tem uma etapa chamada "Loteamento"
UPDATE obra_etapas SET slug = 'loteamento' WHERE titulo LIKE '%Loteamento%' AND (slug IS NULL OR slug = '');

-- Para gerar slugs automaticamente baseados no título (converte espaços em hífens e remove acentos)
-- ATENÇÃO: Teste primeiro em ambiente de desenvolvimento!

/*
UPDATE obra_etapas 
SET slug = LOWER(
    REPLACE(
        REPLACE(
            REPLACE(
                REPLACE(
                    REPLACE(titulo, ' ', '-'),
                    'ã', 'a'
                ),
                'á', 'a'
            ),
            'é', 'e'
        ),
        'ç', 'c'
    )
) 
WHERE slug IS NULL OR slug = '';
*/

-- Verificar resultado final
SELECT id, titulo, slug FROM obra_etapas ORDER BY `order` ASC;