/**
 * Otimização Simples de Imagens
 * Apenas melhora performance sem alterar aparência
 */

// Preload de imagens críticas (melhorar performance inicial)
document.addEventListener('DOMContentLoaded', function() {
    
    // Precarregar apenas as primeiras 3 imagens visíveis
    const firstImages = document.querySelectorAll('img[loading="lazy"]');
    for (let i = 0; i < Math.min(3, firstImages.length); i++) {
        const img = firstImages[i];
        if (img.getBoundingClientRect().top < window.innerHeight + 100) {
            img.removeAttribute('loading');
        }
    }
    
    // Otimizar imagens para WebP quando suportado
    if (supportsWebP()) {
        document.documentElement.classList.add('webp-supported');
    }
    
    // Melhorar performance em dispositivos lentos
    if (navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 2) {
        document.documentElement.classList.add('low-performance');
    }
});

// Verificar suporte WebP
function supportsWebP() {
    const canvas = document.createElement('canvas');
    canvas.width = 1;
    canvas.height = 1;
    return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
}

// Utility simples para preload manual
window.preloadImage = function(src) {
    const img = new Image();
    img.src = src;
    return img;
};