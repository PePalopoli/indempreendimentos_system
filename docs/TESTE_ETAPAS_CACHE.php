<?php
/**
 * Script de teste para verificar se o sistema de cache das etapas está funcionando
 * Execute este arquivo para testar a funcionalidade
 */

// Simular ambiente de teste (ajustar conforme necessário)
function testarCacheEtapas() {
    echo "🧪 TESTANDO SISTEMA DE CACHE DE ETAPAS\n\n";
    
    // 1. Verificar se arquivo de cache é criado
    $cacheFile = sys_get_temp_dir() . '/etapas_empreendimentos_cache.json';
    echo "📁 Local do cache: " . $cacheFile . "\n";
    
    // 2. Verificar se cache existe
    if (file_exists($cacheFile)) {
        echo "✅ Cache encontrado!\n";
        
        $cacheData = json_decode(file_get_contents($cacheFile), true);
        
        if ($cacheData) {
            echo "📊 Dados do cache:\n";
            echo "   ⏰ Timestamp: " . date('Y-m-d H:i:s', $cacheData['timestamp']) . "\n";
            echo "   📋 Total de etapas: " . count($cacheData['data']) . "\n";
            
            if (!empty($cacheData['data'])) {
                echo "   📋 Etapas encontradas:\n";
                foreach ($cacheData['data'] as $etapa) {
                    echo "      - ID: {$etapa['id']}, Título: {$etapa['titulo']}, Slug: {$etapa['slug']}\n";
                }
            }
            
            // Verificar se cache expirou
            $cacheTime = 3600; // 1 hora
            $tempoDecorrido = time() - $cacheData['timestamp'];
            $tempoRestante = $cacheTime - $tempoDecorrido;
            
            if ($tempoRestante > 0) {
                echo "✅ Cache válido! Expira em " . gmdate('H:i:s', $tempoRestante) . "\n";
            } else {
                echo "⚠️ Cache expirado! Será renovado na próxima consulta.\n";
            }
        } else {
            echo "❌ Erro ao ler dados do cache\n";
        }
    } else {
        echo "⚠️ Cache não encontrado. Será criado na primeira consulta.\n";
    }
    
    echo "\n🔧 COMANDOS ÚTEIS:\n";
    echo "   - Limpar cache: rm " . $cacheFile . "\n";
    echo "   - Ver cache: cat " . $cacheFile . "\n";
    
    echo "\n✅ TESTE CONCLUÍDO!\n";
}

// Executar teste
testarCacheEtapas();

/**
 * COMO TESTAR NO SILEX:
 * 
 * 1. Verificar se as etapas aparecem no menu:
 *    - Acessar qualquer página do site
 *    - Verificar dropdown "Empreendimentos"
 *    - Confirmar que mostra etapas do banco em vez de links hardcoded
 * 
 * 2. Testar cache:
 *    - Primeira visita: consulta banco + cria cache
 *    - Visitas seguintes: usa cache (mais rápido)
 *    - Após 1 hora: renova cache automaticamente
 * 
 * 3. Testar limpeza automática:
 *    - Ir no painel admin -> Obra Etapas
 *    - Criar/editar/excluir uma etapa
 *    - Verificar se menu atualiza automaticamente
 * 
 * 4. Verificar logs de erro:
 *    - Checar logs do PHP para erros de cache
 *    - Verificar se fallbacks funcionam corretamente
 */
?>