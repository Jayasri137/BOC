
document.addEventListener("DOMContentLoaded", function() {

    setTimeout(function() {
        const popup = document.getElementById('sitePopupModal');
        if (popup) {
            popup.classList.add('show');
            
            // Initialize stacked card slider if it exists
            const stackCards = document.querySelectorAll('.stack-card');
            if (stackCards.length > 0) {
                let currentIndex = 0;
                const totalCards = stackCards.length;
                
                function updateStack() {
                    stackCards.forEach((card, i) => {
                        let offset = i - currentIndex;
                        if (offset < 0) offset += totalCards;
                        
                        if (offset === 0) {
                            // Front active card
                            card.style.transform = 'translateZ(0) rotate(0deg) scale(1)';
                            card.style.zIndex = 100;
                            card.style.opacity = 1;
                        } else if (offset === 1) {
                            // First card behind
                            card.style.transform = 'translateZ(-50px) translateX(15px) rotate(10deg) scale(0.95)';
                            card.style.zIndex = 90;
                            card.style.opacity = 0.9;
                        } else if (offset === 2) {
                            // Second card behind
                            card.style.transform = 'translateZ(-100px) translateX(-15px) rotate(-15deg) scale(0.9)';
                            card.style.zIndex = 80;
                            card.style.opacity = 0.8;
                        } else {
                            // Hidden behind
                            card.style.transform = 'translateZ(-150px) scale(0.8)';
                            card.style.zIndex = 10;
                            card.style.opacity = 0;
                        }
                    });
                }
                
                // Initial update
                setTimeout(updateStack, 50);
                
                // Auto-slide every 3 seconds
                setInterval(() => {
                    currentIndex = (currentIndex + 1) % totalCards;
                    updateStack();
                }, 3000);
            }
        }
    }, 3000); // 3 second delay

    const closeBtn = document.getElementById('sitePopupClose');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            document.getElementById('sitePopupModal').classList.remove('show');
            setTimeout(() => {
                document.getElementById('sitePopupModal').style.display = 'none';
            }, 400);
        });
    }


    // ── Country Cards Carousel — Smooth Circular Scroll ──
    // Each card waiting off-stage is parked at the nearest entry/exit edge
    // (slots ±3, opacity 0). When the carousel advances, every card slides
    // continuously from its current position to the next — no jumping ever.
    const carouselContainer = document.getElementById('skyCardsCarousel');
    if (carouselContainer) {
        const cards  = Array.from(carouselContainer.querySelectorAll('.sky-card'));
        const badges = Array.from(document.querySelectorAll('.hero-sky__countries-grid .country-pill-badge'));
        let focus = 0;
        const total = cards.length;

        // Slot definitions — 7 visible slots plus two invisible staging slots at ±3
        // Cards ≥4 away park at the ±3 staging slot (opacity 0) on the correct side,
        // so they slide IN smoothly when they become visible.
        const SLOTS = {
            '-3': { x: -520, y: 90, r: -38, s: 0.60, o: 0,    z: 0  }, // staging / exit left
            '-2': { x: -310, y: 42, r: -24, s: 0.80, o: 0.68, z: 1  },
            '-1': { x: -160, y: 10, r: -12, s: 0.90, o: 0.87, z: 2  },
             '0': { x:    0, y:-35, r:   0, s: 1.12, o: 1.00, z: 10 }, // focused centre
             '1': { x:  160, y: 10, r:  12, s: 0.90, o: 0.87, z: 2  },
             '2': { x:  310, y: 42, r:  24, s: 0.80, o: 0.68, z: 1  },
             '3': { x:  520, y: 90, r:  38, s: 0.60, o: 0,    z: 0  }, // staging / exit right
        };

        // Ease-out-quart — fast start, smooth deceleration
        const EASING = 'cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        const DURATION = 750; // ms

        function setTransition(card, on) {
            card.style.transition = on
                ? `transform ${DURATION}ms ${EASING}, opacity ${DURATION * 0.7}ms ease-out`
                : 'none';
        }

        function applyCard(card, dist) {
            // Clamp to staging slot if the card is far off-stage,
            // parking it on the side it will enter from.
            const key = Math.max(-3, Math.min(3, dist));
            const slot = SLOTS[String(key)];
            card.style.transform     = `translateX(${slot.x}px) translateY(${slot.y}px) rotate(${slot.r}deg) scale(${slot.s})`;
            card.style.opacity       = String(slot.o);
            card.style.zIndex        = String(slot.z);
            card.style.pointerEvents = dist === 0 ? 'auto' : 'none';
        }

        function shortDist(i) {
            // Circular shortest-path distance from focus
            let d = i - focus;
            if (d >  Math.floor(total / 2)) d -= total;
            if (d < -Math.floor(total / 2)) d += total;
            return d;
        }

        function render(animated) {
            cards.forEach((card, i) => {
                setTransition(card, animated);
                const d = shortDist(i);
                applyCard(card, d);
                d === 0 ? card.classList.add('active') : card.classList.remove('active');
            });
            badges.forEach((b, i) => {
                i === focus ? b.classList.add('active') : b.classList.remove('active');
            });
        }

        // ── Bootstrap: park all cards at their initial positions without animation ──
        render(false);
        // Then unlock transitions one frame later
        requestAnimationFrame(() => render(true));

        // ── Auto-advance ──
        let timer = setInterval(() => {
            focus = (focus + 1) % total;
            render(true);
        }, 3500);

        function resetTimer() {
            clearInterval(timer);
            timer = setInterval(() => {
                focus = (focus + 1) % total;
                render(true);
            }, 3500);
        }

        // ── Click pill badge → jump to that card ──
        badges.forEach((badge, i) => {
            badge.addEventListener('click', e => {
                e.preventDefault();
                focus = i;
                render(true);
                resetTimer();
            });
        });

        // ── Click a side card → bring it to centre ──
        cards.forEach((card, i) => {
            card.addEventListener('click', () => {
                if (shortDist(i) !== 0) {
                    focus = i;
                    render(true);
                    resetTimer();
                }
            });
        });
    }
});
