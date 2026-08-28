// main.js - Bluestone Overseas
// ==============================

// ── Navbar scroll effect ──
const navbar = document.getElementById('mainNavbar');
const handleNavbarScroll = () => {
  const isScrolled = window.scrollY > 50;
  if (!navbar) return;
  navbar.classList.toggle('scrolled', isScrolled);
  document.body.style.paddingTop = isScrolled ? `${navbar.offsetHeight}px` : '0';
  document.getElementById('backToTop')?.classList.toggle('visible', window.scrollY > 400);
};
window.addEventListener('scroll', handleNavbarScroll);
window.addEventListener('load', handleNavbarScroll);

// ── Mobile hamburger ──
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');
const navOverlay = document.getElementById('navOverlay');
hamburger?.addEventListener('click', () => {
  const open = hamburger.classList.toggle('open');
  navMenu?.classList.toggle('open', open);
  navOverlay?.classList.toggle('active', open);
  hamburger.setAttribute('aria-expanded', open);
});
navOverlay?.addEventListener('click', () => {
  hamburger?.classList.remove('open');
  navMenu?.classList.remove('open');
  navOverlay?.classList.remove('active');
});
document.getElementById('mobileMenuClose')?.addEventListener('click', () => {
  hamburger?.classList.remove('open');
  navMenu?.classList.remove('open');
  navOverlay?.classList.remove('active');
});

// ── Mobile accordion dropdowns ──
document.querySelectorAll('.has-dropdown > a').forEach(link => {
  link.addEventListener('click', e => {
    if (window.innerWidth <= 992) {
      e.preventDefault();
      link.parentElement.classList.toggle('open');
    }
  });
});

// ── Back to top ──
document.getElementById('backToTop')?.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

// ── Animate on scroll ──
if ('IntersectionObserver' in window) {
  // Auto-add animate-on-scroll to key elements across the entire site
  document.querySelectorAll('.section__header, .section__title, .section__subtitle, .card, .stat-box, .service-card, .destination-card, .blog-card, .about-grid > div, .contact-grid > div, .collage-item').forEach(el => {
      if (!el.classList.contains('animate-on-scroll')) {
          el.classList.add('animate-on-scroll');
      }
  });

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { 
        if (e.isIntersecting) {
            e.target.classList.add('animated'); 
        } else {
            // Remove the class when out of view so it animates again!
            e.target.classList.remove('animated');
        }
    });
  }, { threshold: 0.05 });
  document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
} else {
  document.querySelectorAll('.animate-on-scroll').forEach(el => el.classList.add('animated'));
}

// ── Counter animation ──
function animateCounter(el) {
  const targetStr = el.dataset.target || el.innerText.replace(/[^\d]/g, '');
  if (!targetStr || isNaN(parseInt(targetStr))) return;
  const target = parseInt(targetStr);
  const suffix = el.dataset.suffix || '';
  const duration = 2000;
  const step = target / (duration / 16);
  let current = 0;
  const timer = setInterval(() => {
    current += step;
    if (current >= target) { clearInterval(timer); current = target; }
    el.textContent = Math.floor(current).toLocaleString() + suffix;
  }, 16);
}
const counterObserver = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      animateCounter(e.target);
      counterObserver.unobserve(e.target);
    }
  });
}, { threshold: 0.5 });
document.querySelectorAll('.stat-item__num, .hero-stat__num, .h-stat-num').forEach(el => {
  el.dataset.target = el.innerText.replace(/\D/g, '');
  el.dataset.suffix = el.innerText.replace(/[\d,]/g, '');
  counterObserver.observe(el);
});

// ── Country tabs ──
document.querySelectorAll('.ctab').forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelectorAll('.ctab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
  });
});

// ── Newsletter ──
function handleNewsletter(e) {
  e.preventDefault();
  const btn = e.target.querySelector('button');
  btn.textContent = 'Subscribed! ✓';
  btn.style.background = 'linear-gradient(135deg,#14b8a6,#0d9488)';
  setTimeout(() => { btn.innerHTML = 'Subscribe <i class="fa-solid fa-paper-plane"></i>'; btn.style.background = ''; }, 3000);
  return false;
}

// ── Form submission ──
function handleFormSubmit(e) {
  e.preventDefault();
  const form = e.target;
  const btn = form.querySelector('button[type=submit]');
  if (!btn) return false;
  const orig = btn.innerHTML;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
  btn.disabled = true;
  const formData = new FormData(form);
  fetch('submit-enquiry.php', { method: 'POST', body: formData })
    .then(response => {
      if (!response.ok) throw new Error('Network response was not ok');
      return response.json();
    })
    .then(data => {
      if (data.success) {
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Sent Successfully!';
        btn.style.background = 'linear-gradient(135deg,#14b8a6,#0d9488)';
        form.reset();
        setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; btn.disabled = false; }, 4000);
      } else {
        btn.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Failed';
        btn.style.background = 'linear-gradient(135deg,#ef4444,#dc2626)';
        alert(data.error || 'Submission failed. Please try again.');
        setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; btn.disabled = false; }, 4000);
      }
    })
    .catch(err => {
      console.error('Submission error:', err);
      btn.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Error';
      btn.style.background = 'linear-gradient(135deg,#ef4444,#dc2626)';
      alert('Failed to connect to server. Please check your internet connection.');
      setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; btn.disabled = false; }, 4000);
    });
  return false;
}

// ══════════════════════════════════════════
// ── Plane animation along SVG arc path ──
// ══════════════════════════════════════════
function initPlaneOnArc() {
  const svg       = document.querySelector('.hero-sky__arc-svg');
  const path      = svg?.querySelector('#sky-arc-path');
  const maskPath  = svg?.querySelector('#mask-path');
  const plane     = document.querySelector('.hero-sky__plane-on-arc');
  const container = document.querySelector('.hero-sky__arc-container');
  if (!svg || !path || !plane || !container) return;

  const pathLen  = path.getTotalLength();
  const DURATION = 9000;
  const SAMPLE   = 8;

  if (maskPath) {
    maskPath.style.strokeDasharray  = pathLen;
    maskPath.style.strokeDashoffset = pathLen;
  }

  function easeInOutSine(t) { return -(Math.cos(Math.PI * t) - 1) / 2; }

  let startTime   = null;
  let lastElapsed = 0;

  function tick(now) {
    if (!startTime) startTime = now;
    const elapsed = (now - startTime) % DURATION;
    if (elapsed < lastElapsed - 1000) {
      document.dispatchEvent(new Event('planeLoopRestart'));
    }
    lastElapsed = elapsed;

    const raw  = elapsed / DURATION;
    const t    = easeInOutSine(raw);
    const dist = t * pathLen;
    const p    = path.getPointAtLength(dist);

    if (maskPath) maskPath.style.strokeDashoffset = pathLen - dist;

    const svgPt  = svg.createSVGPoint();
    svgPt.x = p.x; svgPt.y = p.y;
    const screen = svgPt.matrixTransform(svg.getScreenCTM());
    const rect   = container.getBoundingClientRect();
    const cx     = screen.x - rect.left;
    const cy     = screen.y - rect.top;
    const bob    = Math.sin(now / 600) * 1.8;

    plane.style.left      = `${cx}px`;
    plane.style.top       = `${cy + bob}px`;
    plane.style.transform = `translate(-50%, -50%)`;

    requestAnimationFrame(tick);
  }
  requestAnimationFrame(tick);
}
window.addEventListener('load', initPlaneOnArc);

// ══════════════════════════════════════════
// ── Hero slide transition logic ──
// ══════════════════════════════════════════
function initHeroSlider() {
  let slides = document.querySelectorAll('.hero-slide');
  const sliderContainer = document.querySelector('.hero-slider-container') || document.querySelector('.hero-slider');
  const legacySlider = !sliderContainer && document.querySelector('.hero-slider');

  if (!slides || slides.length === 0) {
    slides = legacySlider ? document.querySelectorAll('.slide') : document.querySelectorAll('.hero-slide');
  }

  if (!slides || slides.length === 0) return;

  let current = 0;
  let isPaused = false;
  const nextBtn = document.querySelector('.hero-slider-next') || document.querySelector('.slider-next');
  const prevBtn = document.querySelector('.hero-slider-prev') || document.querySelector('.slider-prev');
  const slideCount = slides.length;
  let autoSlideTimer = null;

  function activateSlide(index) {
    const normalized = ((index % slideCount) + slideCount) % slideCount;
    slides.forEach((slide, i) => {
      const active = i === normalized;
      slide.classList.toggle('active', active);
      if (legacySlider) {
        slide.style.display = active ? 'flex' : 'none';
      }
    });
    current = normalized;
  }

  function updateArrows() {
    const visible = slideCount > 1;
    if (nextBtn) nextBtn.style.display = visible ? 'flex' : 'none';
    if (prevBtn) prevBtn.style.display = visible ? 'flex' : 'none';
  }

  function goTo(index) {
    activateSlide(index);
  }

  function nextSlide() { goTo(current + 1); }
  function prevSlide() { goTo(current - 1); }

  function resetAutoSlide() {
    clearInterval(autoSlideTimer);
    autoSlideTimer = setInterval(() => {
      if (!isPaused) nextSlide();
    }, 9000);
  }

  activateSlide(0);
  updateArrows();
  resetAutoSlide();

  document.addEventListener('planeLoopRestart', () => {
    if (!isPaused) nextSlide();
  });

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      nextSlide();
      isPaused = true;
      resetAutoSlide();
      setTimeout(() => { isPaused = false; }, 15000);
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      prevSlide();
      isPaused = true;
      resetAutoSlide();
      setTimeout(() => { isPaused = false; }, 15000);
    });
  }
}

// ══════════════════════════════════════════
// ── Sky Destination Cards Carousel ──
// ══════════════════════════════════════════
function initSkyCardsCarousel() {
  try {
    const carouselContainer = document.getElementById('skyCardsCarousel');
    if (!carouselContainer) return;

    const cards  = Array.from(carouselContainer.querySelectorAll('.sky-card'));
    const badges = Array.from(document.querySelectorAll('.hero-sky__countries-grid .country-pill-badge'));
    let   focus  = 0;
    const total  = cards.length;
    if (total === 0) return;

    const SLOTS = {
      '-3': { x: -520, y: 90, r: -38, s: 0.60, o: 0,    z: 0  },
      '-2': { x: -310, y: 42, r: -24, s: 0.80, o: 0.68, z: 1  },
      '-1': { x: -160, y: 10, r: -12, s: 0.90, o: 0.87, z: 2  },
       '0': { x:    0, y: -35, r:  0, s: 1.12, o: 1.00, z: 10 },
       '1': { x:  160, y: 10, r:  12, s: 0.90, o: 0.87, z: 2  },
       '2': { x:  310, y: 42, r:  24, s: 0.80, o: 0.68, z: 1  },
       '3': { x:  520, y: 90, r:  38, s: 0.60, o: 0,    z: 0  },
    };

    const EASING   = 'cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    const DURATION = 750;

    function setTransition(card, on) {
      card.style.transition = on
        ? `transform ${DURATION}ms ${EASING}, opacity ${DURATION * 0.7}ms ease-out`
        : 'none';
    }

    function applyCard(card, dist) {
      const key  = Math.max(-3, Math.min(3, dist));
      const slot = SLOTS[String(key)];
      card.style.transform     = `translateX(${slot.x}px) translateY(${slot.y}px) rotate(${slot.r}deg) scale(${slot.s})`;
      card.style.opacity       = String(slot.o);
      card.style.zIndex        = String(slot.z);
      card.style.pointerEvents = dist === 0 ? 'auto' : 'none';
    }

    function shortDist(i) {
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
        card.classList.toggle('active', d === 0);
      });
      badges.forEach((b, i) => b.classList.toggle('active', i === focus));
    }

    // Initial layout — no animation, then enable after one frame
    render(false);
    requestAnimationFrame(() => { render(false); requestAnimationFrame(() => render(true)); });

    // Auto-rotate
    let timer = setInterval(() => { focus = (focus + 1) % total; render(true); }, 3500);

    function resetTimer() {
      clearInterval(timer);
      timer = setInterval(() => { focus = (focus + 1) % total; render(true); }, 3500);
    }

    // Badge clicks
    badges.forEach((badge, i) => {
      badge.addEventListener('click', e => { e.preventDefault(); focus = i; render(true); resetTimer(); });
    });

    // Card clicks — bring off-centre card to centre
    cards.forEach((card, i) => {
      card.addEventListener('click', () => {
        if (shortDist(i) !== 0) { focus = i; render(true); resetTimer(); }
      });
    });
  } catch (err) {
    console.error('SkyCardsCarousel error:', err);
  }
}

// ── Run everything once the document is ready ──
function initHomePageWidgets() {
  initHeroSlider();
  initSkyCardsCarousel();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initHomePageWidgets);
} else {
  initHomePageWidgets();
}

