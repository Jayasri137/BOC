// main.js - Bluestone Overseas

// Navbar scroll effect
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

// Mobile hamburger
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

// Mobile accordion dropdowns
document.querySelectorAll('.has-dropdown > a').forEach(link => {
  link.addEventListener('click', e => {
    if (window.innerWidth <= 768) {
      e.preventDefault();
      link.parentElement.classList.toggle('open');
    }
  });
});

// Back to top
document.getElementById('backToTop')?.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Animate on scroll
if ('IntersectionObserver' in window) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('animated'); });
  }, { threshold: 0.05 });
  document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
} else {
  // Fallback for older browsers
  document.querySelectorAll('.animate-on-scroll').forEach(el => el.classList.add('animated'));
}

// Hero Slider
function initBasicHeroSlider() {
  const slider = document.querySelector('.hero-slider');
  if (!slider) return;

  const slides = slider.querySelectorAll('.slide');
  const dotsContainer = slider.querySelector('.slider-dots');
  const prevBtn = slider.querySelector('.slider-prev');
  const nextBtn = slider.querySelector('.slider-next');
  let currentSlide = 0;
  let slideInterval;

  // Create dots
  slides.forEach((_, i) => {
    const dot = document.createElement('div');
    dot.classList.add('dot');
    if (i === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goToSlide(i));
    dotsContainer?.appendChild(dot);
  });

  const dots = slider.querySelectorAll('.dot');

  function goToSlide(n) {
    slides[currentSlide].classList.remove('active');
    dots[currentSlide]?.classList.remove('active');
    currentSlide = (n + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
    dots[currentSlide]?.classList.add('active');
    resetInterval();
  }

  function nextSlide() { goToSlide(currentSlide + 1); }
  function prevSlide() { goToSlide(currentSlide - 1); }

  function resetInterval() {
    clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, 6000);
  }

  prevBtn?.addEventListener('click', prevSlide);
  nextBtn?.addEventListener('click', nextSlide);

  resetInterval();
}
initBasicHeroSlider();

// Counter animation
function animateCounter(el) {
  const targetStr = el.dataset.target || el.innerText.replace(/[^\d]/g, '');
  if (!targetStr || isNaN(parseInt(targetStr))) return; // Skip if not a valid number (e.g., "FREE")

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

// Country tabs
document.querySelectorAll('.ctab').forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelectorAll('.ctab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
  });
});

// Newsletter
function handleNewsletter(e) {
  e.preventDefault();
  const btn = e.target.querySelector('button');
  btn.textContent = 'Subscribed! ✓';
  btn.style.background = 'linear-gradient(135deg,#14b8a6,#0d9488)';
  setTimeout(() => { btn.innerHTML = 'Subscribe <i class="fa-solid fa-paper-plane"></i>'; btn.style.background = ''; }, 3000);
  return false;
}

// Form submission
function handleFormSubmit(e) {
  e.preventDefault();
  const form = e.target;
  const btn = form.querySelector('button[type=submit]');
  if (!btn) return false;

  const orig = btn.innerHTML;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
  btn.disabled = true;

  // Build FormData and submit to submit-enquiry.php
  const formData = new FormData(form);

  fetch('submit-enquiry.php', {
    method: 'POST',
    body: formData
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Sent Successfully!';
        btn.style.background = 'linear-gradient(135deg,#14b8a6,#0d9488)';
        form.reset(); // Clear the form fields after successful submission
        setTimeout(() => {
          btn.innerHTML = orig;
          btn.style.background = '';
          btn.disabled = false;
        }, 4000);
      } else {
        btn.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Failed';
        btn.style.background = 'linear-gradient(135deg,#ef4444,#dc2626)';
        alert(data.error || 'Submission failed. Please try again.');
        setTimeout(() => {
          btn.innerHTML = orig;
          btn.style.background = '';
          btn.disabled = false;
        }, 4000);
      }
    })
    .catch(err => {
      console.error('Submission error:', err);
      btn.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Error';
      btn.style.background = 'linear-gradient(135deg,#ef4444,#dc2626)';
      alert('Failed to connect to server. Please check your internet connection.');
      setTimeout(() => {
        btn.innerHTML = orig;
        btn.style.background = '';
        btn.disabled = false;
      }, 4000);
    });

  return false;
}

// Animate plane naturally along the SVG arc path
function initPlaneOnArc() {
  const svg       = document.querySelector('.hero-sky__arc-svg');
  const path      = svg?.querySelector('#sky-arc-path');
  const maskPath  = svg?.querySelector('#mask-path');
  const plane     = document.querySelector('.hero-sky__plane-on-arc');
  const container = document.querySelector('.hero-sky__arc-container');
  if (!svg || !path || !plane || !container) return;

  const pathLen = path.getTotalLength();
  const DURATION = 9000;   // ms for one full pass — slower = more majestic
  const SAMPLE   = 8;      // px look-ahead for heading calculation
  
  if (maskPath) {
    maskPath.style.strokeDasharray = pathLen;
    maskPath.style.strokeDashoffset = pathLen;
  }

  // Ease in-out sine: starts slow, accelerates in middle, slows at end
  function easeInOutSine(t) {
    return -(Math.cos(Math.PI * t) - 1) / 2;
  }

  // Get curvature-based bank angle (how much the plane leans into turns)
  function getBankAngle(dist) {
    const d1 = Math.max(0, dist - SAMPLE);
    const d2 = Math.min(pathLen, dist + SAMPLE);
    const pA  = path.getPointAtLength(d1);
    const pB  = path.getPointAtLength(d2);
    // Direction vector change approximates curvature
    const dx  = pB.x - pA.x;
    const dy  = pB.y - pA.y;
    const len = Math.hypot(dx, dy) || 1;
    // curvature direction in normalised coords
    const curv = (dx / len) * 18; // ±18° max bank
    return curv;
  }

  let startTime = null;
  let lastElapsed = 0;

  function tick(now) {
    if (!startTime) startTime = now;
    const elapsed = (now - startTime) % DURATION;
    
    // If elapsed jumps backwards, the loop has restarted
    if (elapsed < lastElapsed - 1000) {
      document.dispatchEvent(new Event('planeLoopRestart'));
    }
    lastElapsed = elapsed;

    const raw = elapsed / DURATION;              // 0..1 linear
    const t   = easeInOutSine(raw);             // eased 0..1
    // Travel left→right: start at dist=0, end at dist=pathLen
    const dist = t * pathLen;

    // Current position point
    const p = path.getPointAtLength(dist);
    
    // Draw trail exactly up to plane
    if (maskPath) {
      maskPath.style.strokeDashoffset = pathLen - dist;
    }

    // Look-ahead point for heading angle (sample AHEAD in travel direction)
    const aheadDist = Math.min(pathLen, dist + SAMPLE);
    const p2 = path.getPointAtLength(aheadDist);

    // Convert SVG coords → screen → container-relative
    const svgPt = svg.createSVGPoint();
    svgPt.x = p.x; svgPt.y = p.y;
    const screen = svgPt.matrixTransform(svg.getScreenCTM());
    const rect   = container.getBoundingClientRect();

    const cx = screen.x - rect.left;
    const cy = screen.y - rect.top;

    // Very subtle altitude bob (simulates air pocket turbulence)
    const bob = Math.sin(now / 600) * 1.8;

    plane.style.left      = `${cx}px`;
    plane.style.top       = `${cy + bob}px`;
    plane.style.transform = `translate(-50%, -50%)`;

    requestAnimationFrame(tick);
  }

  requestAnimationFrame(tick);
}

window.addEventListener('load', initPlaneOnArc);


/* --- HERO SLIDER LOGIC --- */
function initHeroSlider() {
  const slides = document.querySelectorAll('.hero-slide');
  if (slides.length <= 1) return; // No slider needed if only 1 slide

  let currentSlide = 0;
  let isPaused = false;

  const nextBtn = document.querySelector('.hero-slider-next');
  const prevBtn = document.querySelector('.hero-slider-prev');

  function updateArrows() {
    if (currentSlide === 0) {
      if (nextBtn) nextBtn.style.display = 'none';
      if (prevBtn) prevBtn.style.display = 'none';
    } else {
      if (nextBtn) nextBtn.style.display = 'flex';
      if (prevBtn) prevBtn.style.display = 'flex';
    }
  }

  function nextSlide() {
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide + 1) % slides.length;
    slides[currentSlide].classList.add('active');
    updateArrows();
  }

  function prevSlide() {
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
    updateArrows();
  }

  // Initial arrow state
  updateArrows();

  // Sync banner change exactly when the airplane finishes its flyby
  document.addEventListener('planeLoopRestart', () => {
    if (!isPaused) nextSlide();
  });

  // Manual Navigation
  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      nextSlide();
      isPaused = true; // Pause auto-sync temporarily on manual click
      setTimeout(() => isPaused = false, 15000); // Resume sync after 15 seconds
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      prevSlide();
      isPaused = true;
      setTimeout(() => isPaused = false, 15000);
    });
  }
}

// Initialize slider immediately since script is at the bottom of the body
initHeroSlider();