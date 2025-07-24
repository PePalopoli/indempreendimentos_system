// Initialize AOS
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });
    
    // MMP Section Animation
    const mmpLogos = document.querySelectorAll('.mmp-logo');
    mmpLogos.forEach((logo, index) => {
        logo.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        logo.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});

// Smooth scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Add parallax effect to hero section
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const rocket = document.querySelector('.rocket-animation');
    if (rocket) {
        rocket.style.transform = `translateY(${scrolled * 0.1}px)`;
    }
});

// Services Slider
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card-wrapper');
    const descriptions = document.querySelectorAll('.description-content');
    const indicators = document.querySelectorAll('.indicator');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const playIcon = playPauseBtn.querySelector('.play-icon');
    const pauseIcon = playPauseBtn.querySelector('.pause-icon');
    let currentIndex = 1;
    let isPlaying = true;
    let slideInterval;

    function updateSlider() {
        cards.forEach((card, index) => {
            if (index === currentIndex) {
                card.style.transform = 'scale(1) translateX(0)';
                card.style.opacity = '1';
                card.style.zIndex = '2';
            } else if (index === getPreviousIndex()) {
                card.style.transform = 'scale(0.8) translateX(-75%)';
                card.style.opacity = '0.4';
                card.style.zIndex = '1';
            } else {
                card.style.transform = 'scale(0.8) translateX(75%)';
                card.style.opacity = '0.4';
                card.style.zIndex = '1';
            }
        });

        descriptions.forEach((desc, index) => {
            if (index === currentIndex) {
                desc.classList.add('active');
            } else {
                desc.classList.remove('active');
            }
        });

        indicators.forEach((indicator, index) => {
            if (index === currentIndex) {
                indicator.classList.add('active');
            } else {
                indicator.classList.remove('active');
            }
        });
    }

    function getPreviousIndex() {
        return (currentIndex - 1 + cards.length) % cards.length;
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % cards.length;
        updateSlider();
    }

    function startSlider() {
        isPlaying = true;
        playIcon.classList.add('hidden');
        pauseIcon.classList.remove('hidden');
        if (slideInterval) clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 4000);
    }

    function pauseSlider() {
        isPlaying = false;
        playIcon.classList.remove('hidden');
        pauseIcon.classList.add('hidden');
        clearInterval(slideInterval);
    }

    // Click handlers for cards
    cards.forEach((card, index) => {
        card.addEventListener('click', () => {
            if (index !== currentIndex) {
                currentIndex = index;
                updateSlider();
                if (isPlaying) {
                    startSlider();
                }
            }
        });
    });

    // Click handlers for indicators
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            if (index !== currentIndex) {
                currentIndex = index;
                updateSlider();
                if (isPlaying) {
                    startSlider();
                }
            }
        });
    });

    playPauseBtn.addEventListener('click', () => {
        if (isPlaying) {
            pauseSlider();
        } else {
            startSlider();
        }
    });

    // Initialize slider
    updateSlider();
    startSlider();
});

// Solutions Slider Home
document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.solutions-track');
    const cards = document.querySelectorAll('.solution-card');
    const indicators = document.querySelectorAll('.solutions-slider .indicator');
    const playPauseBtn = document.getElementById('solutionsPlayPauseBtn');
    const playIcon = playPauseBtn.querySelector('.play-icon');
    const pauseIcon = playPauseBtn.querySelector('.pause-icon');
    let currentExpandingIndex = 0;
    let isExpanding = false;
    let isPlaying = true;
    let slideInterval;
    const expansionDuration = 4000;
    const pauseBetweenExpansions = 2000;
    
    function scrollToStart() {
        track.style.transition = 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
        track.style.transform = 'translateX(0)';
        
        // Remove a transição após a animação
        setTimeout(() => {
            track.style.transition = '';
        }, 500);
    }
    
    function expandNextCard() {
        if (isExpanding || !isPlaying) return;
        
        isExpanding = true;
        const cardToExpand = cards[currentExpandingIndex];
        
        if (cardToExpand) {
            expandCard(cardToExpand);
            
            // Atualiza o índice para o próximo card
            currentExpandingIndex = (currentExpandingIndex + 1) % cards.length;
            
            setTimeout(() => {
                isExpanding = false;
                if (isPlaying) {
                    expandNextCard();
                }
            }, expansionDuration + pauseBetweenExpansions);
        }
    }
    
    function expandCard(card) {
        cards.forEach(c => {
            c.classList.remove('expanded');
            const description = c.querySelector('.solution-description');
            if (description) {
                description.style.opacity = '0';
                description.style.maxHeight = '0';
            }
        });
        
        indicators.forEach(indicator => {
            indicator.classList.remove('active');
        });
        
        // Add expanded class to current card
        card.classList.add('expanded');
        
        // Update active indicator
        const cardIndex = Array.from(cards).indexOf(card);
        indicators[cardIndex].classList.add('active');
        
        // Se o card estiver no início do slider, rola para o início
        if (cardIndex === 0) {
            scrollToStart();
        } else {
            // Calcular e aplicar o deslocamento para centralizar o card expandido
            const cardWidth = card.offsetWidth;
            const trackWidth = track.offsetWidth;
            const cardOffset = card.offsetLeft;
            const targetOffset = (trackWidth - cardWidth) / 2;
            const scrollOffset = cardOffset - targetOffset;
            
            // Limitar o deslocamento para não ultrapassar o final do slider
            const maxScroll = track.scrollWidth - trackWidth;
            const finalOffset = Math.min(Math.max(0, scrollOffset), maxScroll);
            
            track.style.transform = `translateX(-${finalOffset}px)`;
        }
        
        if (!card.querySelector('.solution-header')) {
            const icon = card.querySelector('.solution-icon');
            const title = card.querySelector('.solution-title');
            
            const header = document.createElement('div');
            header.className = 'solution-header';
            
            if (icon && title) {
                const iconClone = icon.cloneNode(true);
                const titleClone = title.cloneNode(true);
                
                header.appendChild(iconClone);
                header.appendChild(titleClone);
                
                card.insertBefore(header, card.firstChild);
                
                icon.style.display = 'none';
                title.style.display = 'none';
            }
        }
        
        const description = card.querySelector('.solution-description');
        if (description) {
            description.style.opacity = '1';
            description.style.maxHeight = '200px';
        }
        
        if (!card.querySelector('.expanded-content')) {
            const expandedContent = document.createElement('div');
            expandedContent.className = 'expanded-content';
            
            // Usar o conteúdo do data attribute
            const expandedText = card.getAttribute('data-expanded-content') || '';
            
            expandedContent.innerHTML = `<p class="expanded-text">${expandedText}</p>`;
            card.appendChild(expandedContent);
        }
    }

    function startSlider() {
        isPlaying = true;
        playIcon.classList.add('hidden');
        pauseIcon.classList.remove('hidden');
        if (slideInterval) clearInterval(slideInterval);
        expandNextCard();
    }

    function pauseSlider() {
        isPlaying = false;
        playIcon.classList.remove('hidden');
        pauseIcon.classList.add('hidden');
        if (slideInterval) clearInterval(slideInterval);
    }

    // Click handlers for cards
    cards.forEach((card, index) => {
        card.addEventListener('click', () => {
            currentExpandingIndex = index;
            expandCard(card);
            if (isPlaying) {
                startSlider();
            }
        });
    });

    // Click handlers for indicators
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            currentExpandingIndex = index;
            expandCard(cards[index]);
            if (isPlaying) {
                startSlider();
            }
        });
    });

    playPauseBtn.addEventListener('click', () => {
        if (isPlaying) {
            pauseSlider();
        } else {
            startSlider();
        }
    });
    
    // Start the animation
    startSlider();
    
    // Pause on hover
    track.addEventListener('mouseenter', () => {
        pauseSlider();
    });
    
    track.addEventListener('mouseleave', () => {
        if (!isPlaying) {
            startSlider();
        }
    });
});

// Cases Carousel
function initCasesCarousel() {
    const carousel = document.querySelector('.cases-carousel');
    const cards = document.querySelectorAll('.case-card');
    let currentIndex = 0;
    const cardWidth = cards[0].offsetWidth + 30; // 30px é o gap entre os cards
    const visibleCards = 3; // Número de cards visíveis por vez
    
    function updateCarousel() {
        // Calcula a posição para mostrar 3 cards, com o último ultrapassando o limite
        const offset = currentIndex * cardWidth;
        carousel.style.transform = `translateX(-${offset}px)`;
    }
    
    function nextSlide() {
        // Avança para o próximo card, mas mantém 3 cards visíveis
        // Garantir que o carrossel nunca ultrapasse o limite da margem esquerda
        if (currentIndex < cards.length - visibleCards) {
            currentIndex++;
            updateCarousel();
        } else {
            // Volta ao início quando chegar ao final
            currentIndex = 0;
            updateCarousel();
        }
    }
    
    // Auto slide
    setInterval(nextSlide, 5000);
    
    // Touch events for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    carousel.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
    });
    
    carousel.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].clientX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe left
                nextSlide();
            } else {
                // Swipe right
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            }
        }
    }
}

// Initialize all components
document.addEventListener('DOMContentLoaded', function() {
    // ... existing initialization code ...
    
    // Initialize cases carousel
    initCasesCarousel();
});

// Accordion functionality
document.addEventListener('DOMContentLoaded', function() {
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    accordionItems.forEach(item => {
        const header = item.querySelector('.accordion-header');
        
        header.addEventListener('click', () => {
            // Close all other items
            accordionItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });
            
            // Toggle current item
            item.classList.toggle('active');
        });
    });
});

// Steps Carousel
document.addEventListener('DOMContentLoaded', function() {
    const stepsCarousel = {
        currentStep: 1,
        totalSteps: 5,
        interval: null,
        isPaused: false,
        
        init: function() {
            this.setupControls();
            this.startAutoPlay();
        },
        
        setupControls: function() {
            // Setup dots click events
            document.querySelectorAll('.dot').forEach(dot => {
                dot.addEventListener('click', () => {
                    const step = parseInt(dot.dataset.step);
                    this.goToStep(step);
                });
            });
            
            // Setup pause button
            const pauseBtn = document.querySelector('.steps-pause-btn');
            pauseBtn.addEventListener('click', () => {
                this.togglePause();
                const icon = pauseBtn.querySelector('i');
                icon.classList.toggle('fa-pause');
                icon.classList.toggle('fa-play');
            });
        },
        
        startAutoPlay: function() {
            this.interval = setInterval(() => {
                if (!this.isPaused) {
                    this.nextStep();
                }
            }, 5000); // Change slide every 5 seconds
        },
        
        togglePause: function() {
            this.isPaused = !this.isPaused;
        },
        
        nextStep: function() {
            let nextStep = this.currentStep + 1;
            if (nextStep > this.totalSteps) {
                nextStep = 1;
            }
            this.goToStep(nextStep);
        },
        
        goToStep: function(step) {
            // Remove active classes from current step
            document.querySelector(`.step-item[data-step="${this.currentStep}"]`).classList.remove('active');
            document.querySelector(`.step-image[data-step="${this.currentStep}"]`).classList.remove('active');
            document.querySelector(`.dot[data-step="${this.currentStep}"]`).classList.remove('active');
            
            // Update current step
            this.currentStep = step;
            
            // Add active classes to new step
            document.querySelector(`.step-item[data-step="${this.currentStep}"]`).classList.add('active');
            document.querySelector(`.step-image[data-step="${this.currentStep}"]`).classList.add('active');
            document.querySelector(`.dot[data-step="${this.currentStep}"]`).classList.add('active');
        }
    };
    
    stepsCarousel.init();
});

// Mobile Info Accordion
document.addEventListener('DOMContentLoaded', function() {
    const mobileAccordionItems = document.querySelectorAll('.mobile-accordion .accordion-item');
    
    mobileAccordionItems.forEach(item => {
        const header = item.querySelector('.accordion-header');
        const icon = item.querySelector('.accordion-icon');
        
        // Adiciona evento de clique tanto no header quanto no ícone
        [header, icon].forEach(element => {
            element.addEventListener('click', (e) => {
                e.stopPropagation(); // Previne propagação do evento
                const isActive = item.classList.contains('active');
                
                // Fecha todos os outros itens
                mobileAccordionItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                        const content = otherItem.querySelector('.accordion-content');
                        if (content) {
                            content.style.maxHeight = '0px';
                        }
                    }
                });
                
                // Toggle do item atual
                item.classList.toggle('active');
                const content = item.querySelector('.accordion-content');
                
                if (content) {
                    if (!isActive) {
                        content.style.maxHeight = content.scrollHeight + 'px';
                    } else {
                        content.style.maxHeight = '0px';
                    }
                }
            });
        });
    });
    
    // Abre o primeiro item por padrão
    if (mobileAccordionItems.length > 0) {
        const firstItem = mobileAccordionItems[0];
        firstItem.classList.add('active');
        const content = firstItem.querySelector('.accordion-content');
        if (content) {
            content.style.maxHeight = content.scrollHeight + 'px';
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.publishers-hero-container');
    const numStars = 10; // ajuste a quantidade

    for (let i = 0; i < numStars; i++) {
        const star = document.createElement('span');
        star.className = 'star';
        // Posição aleatória dentro do container
        star.style.top = Math.random() * 100 + '%';
        star.style.left = Math.random() * 100 + '%';
        // Duração e delay aleatórios para animação
        const duration = 2 + Math.random() * 2; // entre 2s e 4s
        const delay = Math.random() * 2; // até 2s
        star.style.animationDuration = duration + 's';
        star.style.animationDelay = delay + 's';
        // Tamanho aleatório
        const size = 1.5 + Math.random() * 2.5; // entre 1.5px e 4px
        star.style.width = size + 'px';
        star.style.height = size + 'px';
        container.appendChild(star);
    }
});

// Publishers Carousel
document.addEventListener('DOMContentLoaded', function() {
    const sliderWrapper = document.querySelector('.publishers-slider-wrapper');
    if (!sliderWrapper) return;

    const track = sliderWrapper.querySelector('.publishers-slider-track');
    const cards = Array.from(track.children);
    const nextButton = sliderWrapper.querySelector('.slider-arrow.next');
    const prevButton = sliderWrapper.querySelector('.slider-arrow.prev');

    let cardWidth = cards[0].offsetWidth + parseInt(getComputedStyle(cards[0]).marginLeft) + parseInt(getComputedStyle(cards[0]).marginRight);
    let currentIndex = 0;
    let cardsToShow = 3;

    function updateCardsToShow() {
        if (window.innerWidth <= 768) {
            cardsToShow = 1;
        } else if (window.innerWidth <= 991) {
            cardsToShow = 2;
        } else {
            cardsToShow = 3;
        }
        cardWidth = cards[0].offsetWidth + parseInt(getComputedStyle(cards[0]).marginLeft) + parseInt(getComputedStyle(cards[0]).marginRight);
        // Recalcular o posicionamento quando o número de cards visíveis muda
        track.style.transform = 'translateX(-' + currentIndex * cardWidth + 'px)';
    }

    function slideTo(index) {
        if (index < 0) {
            index = 0;
        }
        // Calcula o índice máximo para que os últimos 'cardsToShow' cards fiquem visíveis
        const maxIndex = cards.length - cardsToShow;
        if (index > maxIndex) {
            index = maxIndex;
        }
        
        track.style.transform = 'translateX(-' + index * cardWidth + 'px)';
        currentIndex = index;

        // Atualiza estado dos botões (opcional: desabilitar no fim/início)
        prevButton.disabled = currentIndex === 0;
        nextButton.disabled = currentIndex === maxIndex;
    }

    // Event Listeners para botões
    nextButton.addEventListener('click', () => {
        slideTo(currentIndex + 1);
    });

    prevButton.addEventListener('click', () => {
        slideTo(currentIndex - 1);
    });

    // Inicializa e atualiza em resize
    updateCardsToShow();
    slideTo(currentIndex); // Garante o estado inicial correto dos botões
    window.addEventListener('resize', () => {
        updateCardsToShow();
        slideTo(currentIndex); // Reajusta ao redimensionar
    });
});

// Add Stars to Contact Hero
document.addEventListener('DOMContentLoaded', function () {
    const contactHeroContainer = document.querySelector('.contact-hero-container');
    if (!contactHeroContainer) return; // Sai se o container não existir

    const numStars = 20; // Ajuste a quantidade de estrelas

    for (let i = 0; i < numStars; i++) {
        const star = document.createElement('span');
        star.className = 'star'; // Usa a classe CSS já definida

        // Posição aleatória dentro do container
        star.style.top = Math.random() * 100 + '%';
        star.style.left = Math.random() * 100 + '%';

        // Duração e delay aleatórios para animação (usando a mesma lógica da outra página)
        const duration = 2 + Math.random() * 2; // entre 2s e 4s
        const delay = Math.random() * 2; // até 2s
        star.style.animationDuration = duration + 's';
        star.style.animationDelay = delay + 's';

        // Tamanho aleatório (usando a mesma lógica da outra página)
        const size = 1.5 + Math.random() * 2.5; // entre 1.5px e 4px
        star.style.width = size + 'px';
        star.style.height = size + 'px';

        contactHeroContainer.appendChild(star);
    }
});

// Add Stars to Empresa Hero
document.addEventListener('DOMContentLoaded', function () {
    const empresaHeroSection = document.querySelector('.empresa-hero-section');
    if (!empresaHeroSection) return;

    const numStars = 30; // Ajuste a quantidade

    for (let i = 0; i < numStars; i++) {
        const star = document.createElement('span');
        star.className = 'star';
        star.style.top = Math.random() * 100 + '%';
        star.style.left = Math.random() * 100 + '%';
        const duration = 2 + Math.random() * 3; // 2s a 5s
        const delay = Math.random() * 3;
        star.style.animationDuration = duration + 's';
        star.style.animationDelay = delay + 's';
        const size = 1 + Math.random() * 2; // 1px a 3px
        star.style.width = size + 'px';
        star.style.height = size + 'px';
        empresaHeroSection.appendChild(star);
    }
});

// História Carousel
document.addEventListener('DOMContentLoaded', function() {
    const historiaTrack = document.querySelector('.historia-track');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const timelineProgress = document.querySelector('.timeline-progress');
    
    if (!historiaTrack || !prevBtn || !nextBtn || !timelineProgress) return;
    
    let currentPosition = 0;
    const totalSlides = document.querySelectorAll('.historia-item').length;
    const visibleSlides = 2.5; // Mostra 2.5 slides na tela
    const slidePercentage = 100 / totalSlides;
    
    // Inicializa o estado dos botões
    updateButtonStates();
    
    // Adiciona event listeners aos botões
    prevBtn.addEventListener('click', movePrev);
    nextBtn.addEventListener('click', moveNext);
    
    function movePrev() {
        if (currentPosition > 0) {
            currentPosition--;
            updateCarouselPosition();
        }
    }
    
    function moveNext() {
        if (currentPosition < totalSlides - visibleSlides) {
            currentPosition++;
            updateCarouselPosition();
        }
    }
    
    function updateCarouselPosition() {
        // Calcula o deslocamento para mostrar 2.5 slides visíveis
        const offset = (currentPosition / (totalSlides - visibleSlides)) * (100 - (visibleSlides * 100 / totalSlides));
        historiaTrack.style.transform = `translateX(-${offset}%)`;
        
        // Atualiza a barra de progresso
        const progressPercentage = (currentPosition / (totalSlides - visibleSlides)) * 100;
        timelineProgress.style.width = `${slidePercentage + (progressPercentage * (100 - slidePercentage) / 100)}%`;
        
        // Atualiza estados dos botões
        updateButtonStates();
    }
    
    function updateButtonStates() {
        prevBtn.disabled = currentPosition === 0;
        nextBtn.disabled = currentPosition >= totalSlides - visibleSlides;
        
        prevBtn.style.opacity = prevBtn.disabled ? '0.5' : '1';
        nextBtn.style.opacity = nextBtn.disabled ? '0.5' : '1';
    }
});

