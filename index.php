<?php
require_once 'includes/config.php';
$pageTitle = 'Best Study Abroad Consultants in Coimbatore | Bluestone Overseas';
$pageDesc = 'Looking for the best study abroad consultants in Coimbatore? Bluestone Overseas provides expert counselling, university admissions, scholarships, IELTS and student visa assistance for students across Tamil Nadu.';
$pageKeywords = 'Study Abroad Consultants in Coimbatore, Study abroad consultancy in Coimbatore, Overseas education consultants in Coimbatore, Study abroad consultants in Tamil Nadu, Overseas consultancy in Coimbatore, Best overseas education consultants in Coimbatore, Abroad education consultants in Coimbatore';
$pageImage = 'assets/images/seminar.png';
$heroSlides = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY id ASC");
    $stmt->execute();
    $heroSlides = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Silently ignore if table doesn't exist or error occurs
}

require_once 'includes/header.php';
?>
<main>
<section class="hero-new" id="home">
  <!-- CSS for the hero section -->
  <style>
    .hero-new {
      position: relative;
      width: 100%;
      min-height: 60vh; /* Reduced to make the hero section shorter */
      background: url('assets/images/herobg.jpg') no-repeat center top;
      background-size: cover; /* cover is better to avoid stretch, zoom out slightly */
      display: flex;
      align-items: center;
      overflow: hidden;
      padding: 60px 0 0px 0; /* Reduced padding */
    }
    .hero-new .container {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 15px;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
    }
    .hero-new__left {
      flex: 1;
      min-width: 300px;
      max-width: 650px;
      padding-right: 2rem;
    }
    .hero-new__right {
      flex: 1;
      min-width: 300px;
      position: relative;
      height: 500px; /* Reduced to match shorter hero section */
    }
    @import url('https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&display=swap');

    .hero-tagline {
      font-size: 0.8rem;
      font-weight: 700;
      color: #1e3a8a; /* Deep navy matching image */
      letter-spacing: 4px;
      margin-bottom: 1.2rem;
      display: flex;
      align-items: center;
      text-transform: uppercase;
    }
    .hero-tagline::after {
      content: '';
      width: 45px;
      height: 2px;
      background: #1e3a8a;
      margin-left: 15px;
    }
    .hero-title {
      font-size: clamp(2rem, 3vw, 3rem);
      font-weight: 900; /* Extra bold */
      color: #112a63; /* Very dark vibrant navy */
      line-height: 1.15;
      margin-bottom: 1.2rem;
      letter-spacing: -0.5px;
    }
    .hero-title .cursive {
      font-family: 'Caveat', cursive; /* Caveat perfectly matches the 'J' */
      color: #e11d48; /* Vibrant pink */
      font-weight: 700;
      font-size: 1.4em;
      margin-right: 25px;
      display: inline-block;
      line-height: 0.8;
      transform: translateY(5px);
    }
    .hero-desc {
      font-size: 1.05rem;
      font-weight: 500;
      color: #475569; /* Slate text */
      line-height: 1.6;
      margin-bottom: 2rem;
      max-width: 500px;
    }
    .hero-buttons {
      display: flex;
      gap: 1.25rem;
      margin-bottom: 3.5rem;
      flex-wrap: wrap;
    }
    .btn-pink {
      background: #ff527b;
      color: white;
      padding: 0.8rem 2rem;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      transition: all 0.3s;
      font-size: 1.05rem;
    }
    .btn-pink:hover {
      background: #e63961;
      transform: translateY(-2px);
      color: white;
    }
    .btn-outline-blue {
      background: white;
      color: #1e3a8a;
      border: 2px solid #1e3a8a;
      padding: 0.8rem 2rem;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      transition: all 0.3s;
      font-size: 1.05rem;
    }
    .btn-outline-blue:hover {
      background: #1e3a8a;
      color: white;
      transform: translateY(-2px);
    }
    .hero-features {
      display: flex;
      gap: 2rem;
      align-items: center;
      margin-bottom: 2.5rem;
      flex-wrap: wrap;
    }
    .feature-item {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      font-size: 0.85rem;
      font-weight: 700;
      color: #1e3a8a; /* Dark blue text */
      line-height: 1.2;
    }
    .feature-icon-wrapper {
      width: 34px;
      height: 34px;
      background: #eff6ff; /* Soft blue bg */
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #1e3a8a; /* Dark blue icon */
      font-size: 0.95rem;
    }
    .hero-bottom-text {
      font-family: 'Caveat', cursive;
      color: #2563eb;
      font-size: 2.4rem;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      position: relative;
    }
    .hero-bottom-text i {
      font-size: 1.2rem;
      color: #1e3a8a;
      transform: translateY(-5px) rotate(-15deg);
    }
    .swoosh-underline {
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 100%;
      height: 12px;
      border-bottom: 2px solid #1e3a8a;
      border-radius: 100%;
      transform: rotate(-2deg);
    }
    
    /* Right Side - Globe and Cards */
    .hero-new__right {
      flex: 1;
      min-width: 300px;
      position: relative;
      height: 700px;
    }
    
    .hero-globe {
      position: absolute;
      bottom: -150px; /* Raise it up slightly so more earth is visible */
      right: -100px; /* Anchor it to the right side rather than centering */
      width: 900px !important; /* Reduced from 1400px to prevent overlapping left text */
      max-width: none;
      height: auto;
      z-index: 5;
      pointer-events: none;
    }

    .cards-orbit-container {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      /* Removed mask-image to fix the weird transparent fading over the sky */
      z-index: 10; /* Made this 10 to put all cards clearly ABOVE the globe */
    }

    .cards-pivot {
      position: absolute;
      bottom: 35px; /* Lower the wheel center closer to the globe */
      right: 50%; /* Center to right container */
      width: 0;
      height: 0;
      transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1); /* Smooth rotation */
      transform: rotate(0deg); /* Managed by JS */
    }
    
    .card-float-wrapper {
      position: absolute;
      top: -100px; left: -62px; /* Center perfectly on spoke (half of width) */
      width: 135px; /* Reduced size of country cards */
      transform: translateY(-380px); /* Tighter radius to hug the globe curve */
      z-index: 1;
    }
    
    .card-spoke {
      position: absolute;
      top: 0; left: 0;
      width: 0; height: 0;
    }
    
    .country-card-anim {
      width: 100%;
      height: 100%;
      background: linear-gradient(180deg, var(--card-color-1), var(--card-color-2));
      border-radius: 12px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      padding: 15px 10px;
      text-align: center;
      color: white;
      border: 1px solid rgba(255,255,255,0.3);
      transform: scale(0.85); /* Slightly smaller when not focused */
      opacity: 0.7; /* Dimmed */
      transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1);
      cursor: pointer;
    }

    .country-card-anim.active {
      transform: scale(1.15); /* Pop out and grow! */
      opacity: 1;
      box-shadow: 0 20px 40px rgba(0,0,0,0.4);
      border: 2px solid rgba(255,255,255,0.6);
      z-index: 10;
    }
    
    .country-card-anim h4 {
      font-size: 1.05rem;
      font-weight: 800;
      margin: 0 0 2px 0;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: white;
      text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .country-card-anim p {
      font-size: 0.75rem;
      margin: 0 0 10px 0;
      opacity: 0.9;
    }
    
    .country-card-anim img {
      width: 95%;
      height: auto;
      margin: 0 auto;
      display: block;
      filter: drop-shadow(0 10px 10px rgba(0,0,0,0.3));
    }
    
    .pin-icon {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 0.8rem;
      color: white;
    }

    /* Colors */
    .card-singapore { --card-color-1: #6366f1; --card-color-2: #4f46e5; }
    .card-russia { --card-color-1: #fbbf24; --card-color-2: #f59e0b; }
    .card-spain { --card-color-1: #ef4444; --card-color-2: #dc2626; }
    .card-uk { --card-color-1: #f97316; --card-color-2: #ea580c; }
    .card-canada { --card-color-1: #8b5cf6; --card-color-2: #7c3aed; }

    /* Navigation Arrows */
    .orbit-nav {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 20px;
      z-index: 20;
    }
    
    .orbit-btn {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.5);
      color: white;
      font-size: 1.2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .orbit-btn:hover {
      background: rgba(255, 255, 255, 0.4);
      transform: scale(1.1);
    }
    
    @media (max-width: 1400px) {
      .hero-new__right { 
        transform: scale(0.85); 
        transform-origin: right center;
      }
    }
    
    @media (max-width: 1200px) {
      .hero-new__right { 
        transform: scale(0.7); 
        transform-origin: right center;
      }
    }

    @media (max-width: 992px) {
      .hero-new { 
        padding: 160px 0 0 0; /* Increase padding to push text out from under the navbar */
        min-height: auto; 
        height: auto;
      }
      .hero-new .container { 
        display: block; 
        text-align: center; 
      }
      .hero-new__left { 
        display: block;
        width: 100%; 
        max-width: 100%; 
        padding-right: 0; 
        margin-bottom: 30px; 
        position: relative;
        z-index: 20; 
      }
      .hero-new__left .hero-buttons { 
        justify-content: center; 
      }
      .hero-new__right { 
        display: block;
        width: 100%; 
        height: 550px; 
        margin-top: -250px; /* Aggressive pull up to eliminate the huge transparent sky gap */
        position: relative;
        transform: none;
        z-index: 10;
      }
      
      .hero-globe {
        width: 130vw !important;
        max-width: 600px !important;
        right: 50%;
        margin-right: 0;
        transform: translateX(50%); 
        bottom: -50px; 
      }
      
      .cards-orbit-container {
        transform: scale(0.65);
        transform-origin: bottom center;
        top: auto;
        bottom: -50px;
        height: 600px;
      }
      
      .hero-title { font-size: 2.2rem; }
      .hero-title .cursive { font-size: 2.5rem; }
    }
    
    @media (max-width: 576px) {
      .hero-new { padding: 140px 0 0 0; }
      .hero-new__left {
        margin-bottom: 20px;
      }
      .hero-new__right { 
        height: 380px;
        margin-top: -150px; 
      }
      .hero-globe {
        width: 140vw !important;
        max-width: 500px !important;
        right: 50%;
        margin-right: 0;
        transform: translateX(50%);
        bottom: -30px;
      }
      .cards-orbit-container {
        transform: scale(0.5);
        bottom: -30px;
        height: 500px;
      }
      .hero-title { font-size: 1.8rem; }
      .hero-title .cursive { font-size: 2rem; }
    }
  </style>

  <div class="container">
    <div class="hero-new__left" style="margin-bottom: 3rem;">
      <h1 class="hero-title">
        Your Global Education<br>
        <span class="cursive">Journey</span>Starts Here
      </h1>
        <div style="font-size: 1.2rem; color: #64748b; margin-bottom: 0.5rem; font-weight: 500;">
          Where <span style="color: #3978d7ff; font-weight: 800;">10,000+ Aspirations</span> Became Global Journeys in a Decade.
        </div>

           <div style="font-size: 1.2rem; color: #64748b; margin-bottom: 0.5rem; font-weight: 500;">
           <span style="color: #3978d7ff; font-weight: 800;">Now, let yours begin!!</span>
        </div>

        
    
      
      <div class="hero-buttons" style="margin-top:50px">
   
        <a href="consultation.php" class="btn-pink">
          <i class="fa-regular fa-calendar-check"></i> Book Free Consultation
        </a>
      </div>

    </div>

    <div class="hero-new__right">
      
      <!-- Rotational Orbital Wheel Container -->
      <div class="cards-orbit-container" id="orbital-slider-container">
        <!-- Interactive Wheel -->
        <div class="cards-pivot" id="orbital-pivot" style="transform: rotate(0deg);">
            <div class="card-spoke" data-index="0" style="transform: rotate(-75deg); opacity: 0; pointer-events: none;"><div class="card-float-wrapper"><div class="country-card-anim card-australia" style="background: rgba(234, 179, 8, 0.85); color: #fff;"><i class="fa-solid fa-location-dot pin-icon" style="color: #fff;"></i><h4 style="color: #fff;">Australia</h4><p style="color: rgba(255,255,255,0.9);">Land Down Under</p><img src="assets/images/3d_australia_new.png" alt="Australia"></div></div></div>
            <div class="card-spoke" data-index="1" style="transform: rotate(-50deg); opacity: 1;"><div class="card-float-wrapper"><div class="country-card-anim card-uk" style="background: rgba(249, 115, 22, 0.85); color: #fff;"><i class="fa-solid fa-location-dot pin-icon" style="color: #fff;"></i><h4 style="color: #fff;">UK</h4><p style="color: rgba(255,255,255,0.9);">Historic Excellence</p><img src="assets/images/3d_uk_new.png" alt="UK"></div></div></div>
            <div class="card-spoke" data-index="2" style="transform: rotate(-25deg); opacity: 1;"><div class="card-float-wrapper"><div class="country-card-anim card-newzealand" style="background: rgba(16, 185, 129, 0.85); color: #fff;"><i class="fa-solid fa-location-dot pin-icon" style="color: #fff;"></i><h4 style="color: #fff;">New Zealand</h4><p style="color: rgba(255,255,255,0.9);">Scenic Beauty</p><img src="assets/images/3d_new_zealand.png" alt="New Zealand"></div></div></div>
            <div class="card-spoke" data-index="3" style="transform: rotate(0deg); opacity: 1; z-index: 10;"><div class="card-float-wrapper"><div class="country-card-anim card-usa active" style="background: rgba(59, 130, 246, 0.85); color: #fff;"><i class="fa-solid fa-location-dot pin-icon" style="color: #fff;"></i><h4 style="color: #fff;">US</h4><p style="color: rgba(255,255,255,0.9);">Opportunity</p><img src="assets/images/3d_usa.png" alt="US"></div></div></div>
            <div class="card-spoke" data-index="4" style="transform: rotate(25deg); opacity: 1;"><div class="card-float-wrapper"><div class="country-card-anim card-ireland" style="background: rgba(20, 184, 166, 0.85); color: #fff;"><i class="fa-solid fa-location-dot pin-icon" style="color: #fff;"></i><h4 style="color: #fff;">Ireland</h4><p style="color: rgba(255,255,255,0.9);">Tech Hub</p><img src="assets/images/3d_ireland.png" alt="Ireland"></div></div></div>
            <div class="card-spoke" data-index="5" style="transform: rotate(50deg); opacity: 1;"><div class="card-float-wrapper"><div class="country-card-anim card-canada" style="background: rgba(139, 92, 246, 0.85); color: #fff;"><i class="fa-solid fa-location-dot pin-icon" style="color: #fff;"></i><h4 style="color: #fff;">Canada</h4><p style="color: rgba(255,255,255,0.9);">Welcoming Culture</p><img src="assets/images/3d_canada_new.png" alt="Canada"></div></div></div>
            <div class="card-spoke" data-index="6" style="transform: rotate(75deg); opacity: 0; pointer-events: none;"><div class="card-float-wrapper"><div class="country-card-anim card-malaysia" style="background: rgba(236, 72, 153, 0.85); color: #fff;"><i class="fa-solid fa-location-dot pin-icon" style="color: #fff;"></i><h4 style="color: #fff;">Malaysia</h4><p style="color: rgba(255,255,255,0.9);">Asian Education</p><img src="assets/images/3d_pagoda.png" alt="Malaysia"></div></div></div>
            <div class="card-spoke" data-index="7" style="transform: rotate(100deg); opacity: 0; pointer-events: none;"><div class="card-float-wrapper"><div class="country-card-anim card-singapore" style="background: rgba(99, 102, 241, 0.85); color: #fff;"><i class="fa-solid fa-location-dot pin-icon" style="color: #fff;"></i><h4 style="color: #fff;">Singapore</h4><p style="color: rgba(255,255,255,0.9);">Asian Gateway</p><img src="assets/images/3d_singapore_new.png" alt="Singapore"></div></div></div>
        </div>
        
        <!-- Navigation Buttons Removed as per request -->
      </div>
      
      <!-- Separate globe image directly positioned -->
      <img src="assets/images/globe.png" alt="Globe" class="hero-globe" fetchpriority="high">
    </div>
  </div>
</section>
<!-- TRUST NUMBERS -->
<style>
.trust-numbers-flex {
  display: flex;
  justify-content: center;
  align-items: stretch;
  gap: 1rem;
  flex-wrap: nowrap;
  text-align: center;
  width: 100%;
}
.stat-item {
  flex: 1 1 0;
  min-width: 0;
  background: #ffffff;
  border-radius: 12px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 0.25rem 0.5rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
.stat-item h3 {
  font-size: clamp(1.5rem, 3.5vw, 2.5rem);
  font-weight: 800;
  color: #1a365d;
  margin: 0;
}
.stat-item p {
  color: #64748b;
  font-weight: 600;
  margin: 0;
  text-transform: uppercase;
  font-size: clamp(0.75rem, 1.5vw, 1.1rem);
  margin-top: 0.2rem;
  letter-spacing: 0.5px;
}
@media (max-width: 768px) {
  .trust-numbers-flex {
    flex-wrap: wrap;
    justify-content: center;
  }
  .stat-item {
    flex: 0 0 calc(33.333% - 1rem);
    min-width: 120px;
  }
}
@media (max-width: 480px) {
  .trust-numbers-flex {
    gap: 0.4rem;
  }
  .stat-item {
    padding: 0.75rem 0.25rem;
    border-radius: 8px;
  }
  .stat-item p {
    font-size: 0.7rem;
  }
  .stat-item h3 {
    font-size: 1.25rem;
  }
}
</style>
<section class="trust-numbers" style="background: #1a365d; padding: 0.8rem 0; border-bottom: none;">
  <div class="container" style="padding: 0 10px;">
    <div class="trust-numbers-flex">
      <div class="stat-item" data-aos="fade-up" data-aos-delay="50">
        <h3 class="counter-value" data-target="10000" data-suffix="+">0</h3>
        <p>Students</p>
      </div>
      <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
        <h3 class="counter-value" data-target="700" data-suffix="+">0</h3>
        <p>Partners</p>
      </div>
      <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
        <h3 class="counter-value" data-target="25" data-suffix="+">0</h3>
        <p>Countries</p>
      </div>
      <div class="stat-item" data-aos="fade-up" data-aos-delay="500">
        <h3 class="counter-value" data-target="2015" data-suffix="" data-comma="false">0</h3>
        <p>Since</p>
      </div>
      <div class="stat-item" data-aos="fade-up" data-aos-delay="700">
        <h3 class="counter-value" data-target="99" data-suffix="%">0</h3>
        <p>Visa Success</p>
      </div>
    </div>
  </div>
  
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const counters = document.querySelectorAll(".counter-value");
      const speed = 150; // Controls animation speed

      const animateCounters = (entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const targetElement = entry.target;
            const target = +targetElement.getAttribute("data-target");
            const suffix = targetElement.getAttribute("data-suffix") || "";
            const useComma = targetElement.getAttribute("data-comma") !== "false";
            
            const updateCount = () => {
              const currentText = targetElement.innerText.replace(/,/g, '').replace(/[^\d]/g, '');
              const count = +currentText;
              const inc = target / speed;
              
              if (count < target) {
                let current = Math.ceil(count + inc);
                if (current > target) current = target;
                
                let displayVal = useComma ? current.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : current.toString();
                targetElement.innerText = displayVal + suffix;
                setTimeout(updateCount, 15);
              } else {
                let displayVal = useComma ? target.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : target.toString();
                targetElement.innerText = displayVal + suffix;
              }
            };
            
            updateCount();
            observer.unobserve(targetElement);
          }
        });
      };

      const observer = new IntersectionObserver(animateCounters, {
        threshold: 0.5
      });

      counters.forEach(counter => {
        observer.observe(counter);
      });
    });
  </script>
</section>

<?php
$find_uni_countries = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT id, name FROM countries WHERE is_active = 1 ORDER BY name ASC");
        $find_uni_countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}
}
?>

<!-- CONSULTATION SECTION -->
<style>
    .home-consultation-section {
      background-color: #fff7ed; /* Very light tint of orange */
      color: #0f172a; /* Dark text for readability */
      padding: 4rem 0;
    }
    
    .home-consultation-section .landing-wrapper {
      width: 100%;
      max-width: 1400px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
    }
    
    @media(max-width: 992px) {
      .home-consultation-section .landing-wrapper {
        grid-template-columns: 1fr;
      }
    }

    /* Left Side: Form Area */
    .home-consultation-section .form-area {
      padding: 0.5rem 5% 4rem 5%;
    }
    .home-consultation-section .form-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 2.6rem;
      font-weight: 800;
      color: #0f172a; /* Dark text */
      margin-bottom: 0.5rem;
    }
    .home-consultation-section .highlight-yellow {
      color: #ea580c; /* Orange shade */
    }
    .home-consultation-section .form-title::after {
      content: '';
      display: block;
      width: 40px; height: 4px;
      background: #ea580c; /* Orange underline */
      margin-top: 10px;
      border-radius: 2px;
    }
    .home-consultation-section .form-desc {
      color: #475569; /* Slate text */
      margin-bottom: 2.5rem;
      line-height: 1.6;
      font-size: 1.15rem;
      max-width: 90%;
    }

    /* Form Elements */
    .home-consultation-section .c-form { display: flex; flex-direction: column; gap: 1.25rem; max-width: 600px; }
    .home-consultation-section .fg-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    .home-consultation-section .fg { display: flex; flex-direction: column; gap: 0.4rem; }
    .home-consultation-section .fg label { font-size: 1.05rem; font-weight: 600; color: #5c4033; }
    .home-consultation-section .fg label span { color: var(--danger); }
    
    .home-consultation-section .c-input {
      padding: 1rem 1.2rem;
      border: 1px solid #cbd5e1; /* Darker border for light bg */
      border-radius: 4px;
      font-family: inherit;
      font-size: 1.1rem;
      background: #ffffff;
      color: #0f172a;
      transition: all 0.3s;
    }
    .home-consultation-section .c-input:focus {
      outline: none;
      border-color: #ea580c;
      box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15);
    }
    
    /* Checkboxes */
    .home-consultation-section .checkbox-group {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      margin-top: 0.5rem;
    }
    .home-consultation-section .c-check {
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      cursor: pointer;
    }
    .home-consultation-section .c-check input {
      margin-top: 0.25rem;
      width: 16px; height: 16px;
      accent-color: #ea580c;
    }
    .home-consultation-section .c-check span {
      font-size: 0.95rem;
      color: #475569;
      line-height: 1.5;
    }
    .home-consultation-section .c-check a { color: #ea580c; font-weight: 600; text-decoration: underline; }
    
    .home-consultation-section .submit-btn {
      margin-top: 1rem;
      background: #ea580c; /* Orange shade */
      color: #ffffff;
      border: none;
      padding: 1.1rem 2.5rem;
      font-size: 1.3rem;
      font-weight: 600;
      border-radius: 50px;
      cursor: pointer;
      width: fit-content;
      transition: all 0.3s;
    }
    .home-consultation-section .submit-btn:hover { background: #c2410c; transform: translateY(-2px); }

    /* Right Side: Graphic Area */
    .home-consultation-section .graphic-area {
      position: relative;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 0 2rem 2rem 2rem;
    }
    
    .home-consultation-section .graphic-img-wrap {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 550px;
    }
    
    .home-consultation-section .graphic-img {
      position: relative;
      z-index: 2;
      width: 100%;
      object-fit: cover;
      mix-blend-mode: multiply;
    }
    
    @media(max-width: 992px) {
      .home-consultation-section .graphic-area { padding: 2rem 5% 0 5%; order: -1; }
      .home-consultation-section .form-area { padding: 2rem 5% 3rem 5%; }
      .home-consultation-section .fg-row { grid-template-columns: 1fr; }
    }
    
    .home-consultation-section #formMsg { display: none; margin-top: 1rem; padding: 1rem; border-radius: 8px; font-weight: 500; font-size: 0.95rem; }
    .home-consultation-section #formMsg.success { display: block; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .home-consultation-section #formMsg.error { display: block; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
</style>

<section class="section home-consultation-section">
<div class="landing-wrapper">
  
  <!-- Left Form Area (Desktop: Left, Mobile: Bottom) -->
  <div class="form-area">
    <h1 class="form-title" style="color: #0ea5e9;">Get <span style="color: #ec4899;">FREE</span> <span style="color: #0ea5e9;">Counselling Today!</span></h1>
    <p class="form-desc">Enter your details and our expert will reach out to you to discuss your plans. By the way, all our services are free!</p>
    
    <form id="counsellingFormHome" class="c-form">
      <input type="hidden" name="form_type" value="enquiry">
      
      <div class="fg-row">
        <div class="fg">
          <label>First name<span>*</span></label>
          <input type="text" name="first_name" class="c-input" required>
        </div>
        <div class="fg">
          <label>Last name<span>*</span></label>
          <input type="text" name="last_name" class="c-input" required>
        </div>
      </div>
      
      <div class="fg-row">
        <div class="fg">
          <label>Email address<span>*</span></label>
          <input type="email" name="email" class="c-input" required>
        </div>
        <div class="fg">
          <label>Phone Number<span>*</span></label>
          <input type="tel" name="phone" class="c-input" required pattern="[0-9]{10,15}" title="Please enter a valid phone number (10-15 digits)">
        </div>
      </div>

      <div class="fg">
        <label>Your City<span>*</span></label>
        <input type="text" name="city" class="c-input" required>
      </div>

      <div class="fg-row">
        <div class="fg">
          <label>Preferred Study Destination<span>*</span></label>
          <select name="study_destination" class="c-input" required>
            <option value="">Select Country</option>
            <?php if (!empty($globalCountries)): ?>
              <?php foreach($globalCountries as $c): ?>
                <option value="<?= htmlspecialchars($c['name']) ?>"><?= htmlspecialchars($c['name']) ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="fg">
          <label>Preferred mode of counselling<span>*</span></label>
          <select name="counselling_mode" class="c-input" required>
            <option value="">Select</option>
            <option value="In-Person (Office Visit)">In-Person (Office Visit)</option>
            <option value="Virtual (Video Call)">Virtual (Video Call)</option>
            <option value="Phone Call">Phone Call</option>
          </select>
        </div>
      </div>

      <div class="fg-row">
        <div class="fg">
          <label>Preferred study level<span>*</span></label>
          <select name="study_level" class="c-input" required>
            <option value="">Select</option>
            <option value="Undergraduate (Bachelors)">Undergraduate (Bachelors)</option>
            <option value="Postgraduate (Masters)">Postgraduate (Masters)</option>
            <option value="Doctorate (PhD)">Doctorate (PhD)</option>
            <option value="Diploma / Certificate">Diploma / Certificate</option>
          </select>
        </div>
        <div class="fg">
          <label>How would you fund your education?<span>*</span></label>
          <select name="funding_mode" class="c-input" required>
            <option value="">Select</option>
            <option value="Self-Funded / Family">Self-Funded / Family</option>
            <option value="Education Loan">Education Loan</option>
            <option value="Seeking Scholarships">Seeking Scholarships</option>
          </select>
        </div>
      </div>
      
      <div class="checkbox-group">
        <label class="c-check">
          <input type="checkbox" required>
          <span>I agree to Bluestone <a href="terms.php" target="_blank">Terms</a> and <a href="privacy.php" target="_blank">privacy policy</a> *</span>
        </label>
        <label class="c-check">
          <input type="checkbox">
          <span>Please contact me by phone, email or SMS to assist with my enquiry</span>
        </label>
        <label class="c-check">
          <input type="checkbox">
          <span>I would like to receive updates and offers from Bluestone</span>
        </label>
      </div>
      
      <button type="submit" class="submit-btn" id="submitBtnHome">Avail FREE Counselling</button>
      <div id="formMsgHome"></div>
    </form>
  </div>

  <!-- Right Graphic Area (Desktop: Right, Mobile: Top via CSS order) -->
  <div class="graphic-area">
    <div class="graphic-img-wrap">
      <!-- User image -->
      <img src="assets/images/img4.png" alt="Student Counselling" class="graphic-img">
    </div>
  </div>

</div>
</section>

<script>
document.getElementById('counsellingFormHome').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const form = this;
  const btn = document.getElementById('submitBtnHome');
  const msg = document.getElementById('formMsgHome');
  const originalBtnHtml = btn.innerHTML;
  
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
  btn.disabled = true;
  msg.className = '';
  msg.style.display = 'none';
  
  const formData = new FormData(form);
  
  fetch('submit-enquiry.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    msg.style.display = 'block';
    if(data.success) {
      msg.className = 'success';
      msg.innerHTML = '<i class="fa-solid fa-check-circle"></i> Thanks! Your counselling session request has been received. Our team will contact you shortly.';
      form.reset();
      btn.innerHTML = '<i class="fa-solid fa-check"></i> Requested';
    } else {
      msg.className = 'error';
      msg.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + (data.error || 'Failed to submit request.');
      btn.innerHTML = originalBtnHtml;
      btn.disabled = false;
    }
  })
  .catch(err => {
    msg.style.display = 'block';
    msg.className = 'error';
    msg.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Network error. Please try again.';
    btn.innerHTML = originalBtnHtml;
    btn.disabled = false;
  });
});
</script>
<!-- FIND YOUR UNIVERSITY SECTION -->
<section id="find-university" class="section" style="display: none; background: #ffffff; padding: 4rem 0;">
  <div class="container">
    <div style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); border-radius: 40px; padding: 4rem; box-shadow: 0 20px 50px rgba(59, 130, 246, 0.25); position: relative;">
      
      <!-- Decorative faint background shapes -->
      <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; border-radius: 40px; pointer-events: none; z-index: 0;">
        <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
      </div>

      <div style="position: relative; z-index: 1;">
        <div class="section__header text-center" style="margin-bottom: 2rem;">
          <h2 class="section__title" style="color: #ffffff;">Find Your <span style="color: #fde047; -webkit-text-fill-color: initial; background: none;">University</span></h2>
          <p class="section__subtitle" style="color: rgba(255,255,255,0.9);">Search across 1,000+ top institutions globally</p>
        </div>
        
        <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); max-width: 1000px; margin: 0 auto;">
          <form id="universitySearchForm" action="universities.php" method="GET" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
              <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.9rem;">Country</label>
              <select name="country" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc; color: #1e293b; font-family: inherit; font-size: 1rem; outline: none; transition: border-color 0.3s ease;">
                <option value="">Any Country</option>
                <?php foreach($find_uni_countries as $c): ?>
                  <option value="<?= htmlspecialchars($c['id']) ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
              <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.9rem;">Course Level</label>
              <select name="course" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc; color: #1e293b; font-family: inherit; font-size: 1rem; outline: none; transition: border-color 0.3s ease;">
                <option value="">Any Course Level</option>
                <option value="ug">Undergraduate</option>
                <option value="pg">Postgraduate</option>
                <option value="phd">PhD</option>
                <option value="diploma">Diploma</option>
              </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
              <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.9rem;">Intake</label>
              <select name="intake" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc; color: #1e293b; font-family: inherit; font-size: 1rem; outline: none; transition: border-color 0.3s ease;">
                <option value="">Any Intake</option>
                <option value="fall">Fall (Sep/Oct)</option>
                <option value="spring">Spring (Jan/Feb)</option>
                <option value="summer">Summer (May/Jun)</option>
              </select>
            </div>
            <div style="flex: 0 0 auto;">
              <button type="submit" class="btn btn--primary" style="padding: 0.8rem 2rem; border-radius: 10px; height: 50px; justify-content: center; background:#0d315c; color:white; border:none; cursor:pointer;">
                <i class="fa-solid fa-search"></i> Search
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- PROCESS -->
<section class="section process-section" style="background: #ffffff; padding: 4rem 0;">
  <div class="container">
    <div style="background: #17b0a4; border-radius: 30px; padding: 4rem; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(23, 176, 164, 0.25);">
      
      <!-- Decorative faint background shapes -->
      <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
      <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>

      <div style="position: relative; z-index: 1;">
        
        <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3rem;">
          <h2 class="section__title" style="color: #fff; font-size: 2.8rem; line-height: 1.2;">From Ambition to <span style="color: #facc15; -webkit-text-fill-color: initial; background: none;">Arrival</span></h2>
          <p style="color: rgba(255,255,255,0.85); font-size: 1.1rem; max-width: 700px; margin: 1rem auto 0; line-height: 1.6;">From your first conversation to your first day abroad, Bluestone Overseas brings guidance, expertise and support together under one roof.</p>
        </div>
        
        <style>
          .process-grid {
              display: grid;
              grid-template-columns: repeat(3, 1fr);
              gap: 2rem;
              margin-top: 2rem;
          }
          @media (max-width: 992px) {
              .process-grid { grid-template-columns: repeat(2, 1fr); }
          }
          @media (max-width: 600px) {
              .process-grid { grid-template-columns: 1fr; }
          }
          
          .process-card {
              background: #ffffff;
              position: relative;
              border: 1px solid #e2e8f0;
              border-radius: 40px 0 40px 0; /* Unique leaf-like shape */
              text-align: center;
              color: #1e293b;
              text-decoration: none;
              overflow: hidden;
              transition: all 0.4s ease;
              display: flex;
              flex-direction: column;
              align-items: center;
              justify-content: center;
              min-height: 140px;
              box-shadow: 0 10px 30px rgba(0,0,0,0.1);
          }
          .process-card:hover {
              transform: translateY(-5px);
              box-shadow: 0 15px 30px rgba(0,0,0,0.15);
              border-color: #facc15;
              border-radius: 0 40px 0 40px; /* Swaps shape on hover! */
          }
          
          .process-card-front {
              transition: all 0.4s ease;
              display: flex;
              flex-direction: column;
              align-items: center;
              justify-content: center;
              width: 100%;
              padding: 1.5rem 1rem;
          }
          .process-card:hover .process-card-front {
              opacity: 0;
              transform: translateY(-20px);
          }
          
          .process-card-back {
              position: absolute;
              top: 0; left: 0; width: 100%; height: 100%;
              padding: 1.5rem;
              opacity: 0;
              transform: translateY(20px);
              transition: all 0.4s ease;
              display: flex;
              flex-direction: column;
              align-items: center;
              justify-content: center;
              background: #facc15;
              color: #0f172a;
          }
          .process-card:hover .process-card-back {
              opacity: 1;
              transform: translateY(0);
          }
          
          .process-card-step-title {
              color: #1b408fff;
              font-size: clamp(1rem, 3vw, 1.2rem);
              font-weight: 800;
              line-height: 1.3;
              text-transform: uppercase;
              letter-spacing: 1px;
              margin-top: 1rem;
          }
          
          .process-card-back h4 {
              font-size: 1rem;
              font-weight: 800;
              margin-bottom: 0.25rem;
              color: #0f172a;
              text-transform: uppercase;
          }
          .process-card-back p {
              font-size: 0.85rem;
              line-height: 1.4;
              margin: 0;
              font-weight: 600;
              color: #334155;
          }
        </style>
        
        <div class="process-grid">
          <?php
          $steps=[
            ['CAREER & STUDENT COUNSELLING', 'Find Your Direction', 'Understand your strengths, ambitions and possibilities before choosing your destination.', 'student-counselling.php'],
            ['COURSE & UNIVERSITY SELECTION', 'Find Your Right Fit', 'Explore the right courses and universities based on your academic profile, budget and career goals.', 'university-selection.php'],
            ['ADMISSIONS & APPLICATIONS', 'Turn Plans Into Possibilities', 'Build a strong application and navigate the journey from documentation to offer letter.', 'admission-processing.php'],
            ['SCHOLARSHIPS & EDUCATION FINANCE', 'Unlock More Opportunities', 'Explore scholarships, funding options and financial pathways for your international education.', 'scholarships.php'],
            ['MAKE THE JOURNEY OFFICIAL', 'Student Visa Assistance', 'Prepare your documentation and navigate the visa process with confidence and clarity.', 'visa-processing.php'],
            ['PRE-DEPARTURE & STUDENT SUPPORT', 'Prepare For Your New Beginning', 'From accommodation and travel to essential arrangements, we help you prepare for life abroad.', 'accommodation.php'],
          ];
          
          foreach($steps as $i => [$step_header, $title, $desc, $link]):
          ?>
          <a href="<?= htmlspecialchars($link) ?>" class="process-card animate-on-scroll delay-<?= $i ?>">
            
            <div class="process-card-front">
              <div class="process-card-step-title"><?= htmlspecialchars($step_header) ?></div>
            </div>
            
            <div class="process-card-back">
              <h4><?= htmlspecialchars($title) ?></h4>
              <p><?= htmlspecialchars($desc) ?></p>
              <div style="margin-top: 1rem; font-size: 0.9rem; font-weight: 800; text-transform: uppercase; border-bottom: 2px solid #0f172a; padding-bottom: 2px; display: inline-block;">Explore Service <i class="fa-solid fa-arrow-right"></i></div>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
          <a href="consultation.php" class="btn btn--primary" style="background: #facc15; color: #0f172a; padding: 1rem 2.5rem; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; box-shadow: 0 10px 20px rgba(30, 41, 59, 0.2);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 25px rgba(30, 41, 59, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 20px rgba(30, 41, 59, 0.2)';">
          Talk to Expert <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<section class="section why-elite-section" id="about" style="display: none; background: var(--light); padding: 5rem 0;">
  <div class="container">
    <div style="background: var(--gradient); border-radius: 40px; padding: 4rem; box-shadow: 0 20px 50px rgba(204, 35, 102, 0.25); position: relative;">
      
      <!-- Decorative faint background shapes -->
      <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; border-radius: 40px; pointer-events: none; z-index: 0;">
        <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
      </div>

      <div style="text-align: center; display: flex; flex-direction: column; align-items: center; width: 100%; margin: 0 auto 4rem; position: relative; z-index: 2;" class="animate-on-scroll">
        <span class="section__tag" style="background: rgba(255,255,255,0.2); color: #fff; border-color: rgba(255,255,255,0.3); margin-bottom: 1.5rem;">Why Choose</span>
        <h2 class="section__title" style="margin-bottom: 1rem; color: #fff;">Why Choose Bluestone</h2>
        <p class="section__subtitle" style="color: rgba(255,255,255,0.9); text-align: center; max-width: 100%;">
          Bluestone Overseas is a trusted study abroad consultancy in Coimbatore, helping students achieve their dream of studying at leading universities around the world. Since 2015, we have provided personalised overseas education guidance to students across Coimbatore and Tamil Nadu.
As one of the leading study abroad consultants in Coimbatore, we assist students with country selection, course selection, university applications, scholarships, education loans, IELTS preparation and student visa processing.</p>
      </div>

      <style>
        .why-grid {
          display: grid;
          grid-template-columns: repeat(3, 1fr);
          gap: 1.5rem;
          max-width: 1200px;
          margin: 0 auto;
          position: relative;
          z-index: 2;
        }
        .why-grid-card {
          background: #ffffff;
          border-radius: 12px;
          padding: 2rem;
          box-shadow: 0 4px 6px rgba(0,0,0,0.05);
          border: 1px solid #e2e8f0;
          display: flex;
          flex-direction: column;
          transition: all 0.3s ease;
          height: 100%;
        }
        .why-grid-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 10px 20px rgba(0,0,0,0.1);
          border-color: var(--theme-color);
        }
        .wgc-icon {
          font-size: 2rem;
          color: var(--theme-color);
          margin-bottom: 1.25rem;
        }
        .wgc-title {
          font-size: 1.25rem;
          font-weight: 700;
          color: #1e293b;
          margin-bottom: 0.75rem;
        }
        .wgc-desc {
          font-size: 0.95rem;
          color: #64748b;
          line-height: 1.5;
          margin-bottom: 2rem;
          flex-grow: 1;
        }
        .wgc-link {
          color: var(--theme-color);
          font-weight: 600;
          font-size: 0.9rem;
          text-decoration: none;
          display: inline-flex;
          align-items: center;
          gap: 0.25rem;
          transition: opacity 0.3s;
        }
        .wgc-link:hover {
          opacity: 0.8;
        }
        @media (max-width: 991px) {
          .why-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 576px) {
          .why-grid { grid-template-columns: 1fr; }
        }
      </style>
      
      <div class="why-grid">
        <!-- Card 1 -->
        <div class="why-grid-card" style="--theme-color: #3b82f6;">
          <div class="wgc-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
          <h3 class="wgc-title">Personalised Counselling</h3>
          <p class="wgc-desc">One-to-one guidance based on your academic profile, career goals, and budget. Our expert counsellors take the time to understand your unique aspirations and match you with the best educational pathways.</p>
          <a href="student-counselling.php" class="wgc-link">Learn More <i class="fa-solid fa-angle-right"></i></a>
        </div>
        
        <!-- Card 2 -->
        <div class="why-grid-card" style="--theme-color: #a855f7;">
          <div class="wgc-icon"><i class="fa-solid fa-building-columns"></i></div>
          <h3 class="wgc-title">University Partnerships</h3>
          <p class="wgc-desc">With over 700+ direct university tie-ups across the globe, we provide you with priority access to partner universities. Enjoy faster application processing and direct communication with admissions officers.</p>
          <a href="universities.php" class="wgc-link">Learn More <i class="fa-solid fa-angle-right"></i></a>
        </div>
        
        <!-- Card 3 -->
        <div class="why-grid-card" style="--theme-color: #10b981;">
          <div class="wgc-icon"><i class="fa-solid fa-graduation-cap"></i></div>
          <h3 class="wgc-title">Scholarship Assistance</h3>
          <p class="wgc-desc">We actively identify and help you apply for scholarships that match your profile. We provide comprehensive financial aid guidance and assist with all necessary application documentation.</p>
          <a href="scholarships.php" class="wgc-link">Learn More <i class="fa-solid fa-angle-right"></i></a>
        </div>
        
        <!-- Card 4 -->
        <div class="why-grid-card" style="--theme-color: #f97316;">
          <div class="wgc-icon"><i class="fa-solid fa-passport"></i></div>
          <h3 class="wgc-title">Visa Expertise</h3>
          <p class="wgc-desc">Benefit from our exceptional visa success rate. We offer dedicated documentation support, mock interview preparation, and step-by-step guidance through complex immigration requirements.</p>
          <a href="visa-processing.php" class="wgc-link">Learn More <i class="fa-solid fa-angle-right"></i></a>
        </div>
        
        <!-- Card 5 -->
        <div class="why-grid-card" style="--theme-color: #14b8a6;">
          <div class="wgc-icon"><i class="fa-solid fa-location-dot"></i></div>
          <h3 class="wgc-title">Local Support</h3>
          <p class="wgc-desc">With conveniently located branches across Tamil Nadu and an extensive international support network, we are always within reach. Whether preparing to leave or facing a new challenge abroad, we have your back.</p>
          <a href="branch.php" class="wgc-link">Learn More <i class="fa-solid fa-angle-right"></i></a>
        </div>
        
        <!-- Card 6 -->
        <div class="why-grid-card" style="--theme-color: #f43f5e;">
          <div class="wgc-icon"><i class="fa-solid fa-plane-departure"></i></div>
          <h3 class="wgc-title">End-to-End Assistance</h3>
          <p class="wgc-desc">Our commitment doesn't end with a university offer. From your first counselling session to pre-departure briefings, travel arrangements, and accommodation, we provide a full-service experience.</p>
          <a href="accommodation.php" class="wgc-link">Learn More <i class="fa-solid fa-angle-right"></i></a>
        </div>
        
      </div>
    </div>
  </div>
</section>

<!-- BLUESTONE CIRCLE SECTION -->
<style>
.bluestone-circle-section {
    background: linear-gradient(135deg, #ffffff 0%, #f9fafb 50%, #f3e8ff 100%);
    padding: 6rem 0;
    position: relative;
    overflow: hidden;
}
.bluestone-circle-section::before {
    content: '';
    position: absolute;
    bottom: -150px;
    left: -100px;
    width: 600px;
    height: 400px;
    background: rgba(109, 40, 217, 0.08);
    border-radius: 50%;
    filter: blur(60px);
    z-index: 0;
}
.bc-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 4rem;
}
.bc-left {
    flex: 1;
    max-width: 550px;
}
.bc-title-wrapper {
    margin-bottom: 1.5rem;
    line-height: 1;
}
.bc-title-bluestone {
    font-size: 4.8rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -2px;
    display: block;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}
.bc-title-circle {
    font-size: 4.8rem;
    font-weight: 900;
    color: #5b21b6;
    letter-spacing: -2px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}
.bc-spark {
    width: 50px;
    height: 50px;
    margin-left: 5px;
    margin-top: -20px;
}
.bc-subtitle {
    font-size: 1.6rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1.5rem;
    line-height: 1.4;
}
.bc-subtitle-underline {
    position: relative;
    display: inline-block;
    color: #1e293b;
}
.bc-subtitle-underline::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 0;
    width: 100%;
    height: 4px;
    background: #7c3aed;
    border-radius: 4px;
    transform: rotate(-1deg);
}
.bc-desc {
    font-size: 1.1rem;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 2.5rem;
    max-width: 480px;
}
.bc-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: #5b21b6;
    color: #ffffff;
    padding: 1.2rem 2.2rem;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}
.bc-btn:hover {
    background: #4c1d95;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(91, 33, 182, 0.2);
    color: #ffffff;
}
.bc-right {
    flex: 1.2;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}
.bc-globe-bg {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 120%;
    height: auto;
    opacity: 0.15;
    z-index: 0;
    pointer-events: none;
    filter: hue-rotate(240deg) saturate(2);
}
.bc-students-img {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 650px;
    height: auto;
    border-radius: 20px;
}
.bc-floating-note {
    position: absolute;
    top: -100px;
    right: 10px;
    z-index: 3;
    transform: rotate(-8deg);
    font-family: 'Caveat', cursive;
    font-size: 1.2rem;
    color: #1e293b;
    font-weight: 700;
    line-height: 1.2;
    text-align: center;
}
.bc-floating-note::after {
    content: '';
    display: block;
    width: 40px;
    height: 2px;
    background: #5b21b6;
    margin: 5px auto 0;
    transform: rotate(-3deg);
}
.bc-plane-path {
    position: absolute;
    top: -50px;
    right: 150px;
    width: 150px;
    height: 100px;
    z-index: 1;
}

@media(max-width: 992px) {
    .bc-container {
        flex-direction: column;
        gap: 3rem;
    }
    .bc-title-bluestone, .bc-title-circle {
        font-size: 3.5rem;
    }
    .bc-subtitle {
        font-size: 1.3rem;
    }
    .bc-floating-note {
        right: 0;
    }
}
</style>

<section class="bluestone-circle-section">
    <div class="bc-container">
        <div class="bc-left animate-on-scroll">
            <div class="bc-title-wrapper">
                <span class="bc-title-bluestone">Bluestone</span>
                <span class="bc-title-circle">
                    Circle 
                    <svg class="bc-spark" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 70 L35 55 M45 20 L55 45 M85 45 L65 60" stroke="#5b21b6" stroke-width="8" stroke-linecap="round"/>
                    </svg>
                </span>
            </div>
            <h3 class="bc-subtitle">
                More than a community &ndash; <br>
                a circle that takes <span class="bc-subtitle-underline">you further.</span>
            </h3>
            <p class="bc-desc">
                Connect with our alumni, engage with fellow students and get real insights to support your study abroad journey.
            </p>
            <a href="sign-in.php" class="bc-btn">
                Join the Bluestone Circle <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="bc-right animate-on-scroll delay-1">
            <!-- Background Globe (Tinted purple via CSS filter) -->
            <img src="assets/images/world.png" alt="World Map" class="bc-globe-bg">
            
            <!-- Flight path graphic -->
            <svg class="bc-plane-path" viewBox="0 0 200 100" fill="none" style="top: -120px; right: 90px;">
                <path d="M10 90 Q 80 10 180 20" stroke="#5b21b6" stroke-width="2" stroke-dasharray="6, 6"/>
            </svg>
            <i class="fa-solid fa-plane" style="position:absolute; top:-110px; right:80px; color:#5b21b6; transform:rotate(45deg); font-size:1.2rem; z-index:2;"></i>
            
            <!-- Floating Text -->
            <div class="bc-floating-note">
                A global<br>network for<br>a brighter you.
            </div>
            
            <!-- Main Image -->
            <img src="assets/images/s3.jpg" alt="Students in Bluestone Circle" class="bc-students-img" style="box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
        </div>
    </div>
</section>

<!-- COUNTRIES -->
<section class="section countries-section" id="destinations" style="background: #ffffff;">
  <div class="container">
    <div class="section__header animate-on-scroll" style="margin-bottom: 1rem;">
      <span class="section__tag">Study Destinations</span>
      <h2 class="section__title">Choose Your <span>Dream Country</span></h2>
      <div class="accent-bar"></div>
    </div>
    <div class="country-grid-container" style="padding-top: 0;">
      <style>
      .dream-country-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        margin-top: 2rem;
      }
      .country-card-large {
        display: flex;
        flex-direction: column;
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        border: 1px solid #f1f5f9;
      }
      .country-card-large:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        border-color: #e2e8f0;
      }
      .country-card-large__img {
        width: 100%;
        height: 220px;
        overflow: hidden;
      }
      .country-card-large__img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
      }
      .country-card-large:hover .country-card-large__img img {
        transform: scale(1.1);
      }
      .country-card-large__content {
        padding: 1.5rem;
        text-align: center;
      }
      .country-card-large__content h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.4rem;
        color: #1e293b;
      }
      .country-card-large__content p {
        margin: 0;
        font-size: 1rem;
        color: #64748b;
        line-height: 1.5;
      }
      .dream-country-track {
        display: contents;
      }
      .mobile-clone {
        display: none !important;
      }
      @media (max-width: 1024px) {
        .dream-country-grid { grid-template-columns: repeat(3, 1fr); }
      }
      @media (max-width: 768px) {
        .mobile-clone {
          display: flex !important;
        }
        .dream-country-grid { 
           display: flex;
           flex-direction: column;
           gap: 1.5rem;
           overflow: hidden;
        }
        .dream-country-track {
           display: flex;
           gap: 1rem;
           width: max-content;
           animation: scrollLeft 20s linear infinite;
        }
        .dream-country-track.reverse {
           direction: ltr;
           animation: scrollRight 20s linear infinite;
        }
        .dream-country-track.reverse > * {
           direction: ltr;
        }
        .dream-country-track:active {
           animation-play-state: paused;
        }
        .country-card-large {
           width: 260px;
           flex-shrink: 0;
        }
        @keyframes scrollLeft {
           0% { transform: translateX(0); }
           100% { transform: translateX(calc(-50% - 0.5rem)); }
        }
        @keyframes scrollRight {
           0% { transform: translateX(calc(-50% - 0.5rem)); }
           100% { transform: translateX(0); }
        }
      }
      </style>

      <div class="dream-country-grid">
        <?php
        $dream_countries = [
          ['australia', 'Australia', '🇦🇺', 'Globally recognised degrees with excellent post-study work rights.', '#eab308'],
          ['uk', 'United Kingdom', '🇬🇧', 'Short duration courses with excellent academic reputation.', '#ef4444'],
          ['newzealand', 'New Zealand', '🇳🇿', 'Safe, scenic and student-friendly with excellent QS-ranked universities.', '#10b981'],
          ['usa', 'United States', '🇺🇸', 'World-class universities with cutting-edge research facilities.', '#3b82f6'],
          ['ireland', 'Ireland', '🇮🇪', 'English-speaking, tech-hub with a vibrant student community.', '#22c55e'],
          ['canada', 'Canada', '🇨🇦', 'Safe, multicultural and affordable with great PR pathways.', '#f43f5e'],
          ['malaysia', 'Malaysia', '🇲🇾', 'UK and Australian degrees at a fraction of the cost.', '#f59e0b'],
          ['singapore', 'Singapore', '🇸🇬', 'Asia\'s education capital with globally ranked universities.', '#8b5cf6']
        ];
        ?>
        <div class="dream-country-track">
        <?php 
        // Original 4
        for($i=0; $i<4; $i++): 
            [$slug, $name, $flag, $desc, $color] = $dream_countries[$i];
        ?>
          <a href="study-in-<?= $slug ?>.php" class="country-card-large" style="border-top: 4px solid <?= $color ?>;">
            <div class="country-card-large__img">
              <img src="<?= get_country_image_url($slug) ?>" alt="<?= $name ?>">
            </div>
            <div class="country-card-large__content" style="background: linear-gradient(to bottom, <?= $color ?>15, #ffffff 40%);">
              <h3 style="color: <?= $color ?>; filter: brightness(0.6);"><?= $name ?></h3>
              <p><?= $desc ?></p>
            </div>
          </a>
        <?php endfor; ?>
        <?php 
        // Clones for infinite scroll on mobile
        for($i=0; $i<4; $i++): 
            [$slug, $name, $flag, $desc, $color] = $dream_countries[$i];
        ?>
          <a href="study-in-<?= $slug ?>.php" class="country-card-large mobile-clone" style="border-top: 4px solid <?= $color ?>;">
            <div class="country-card-large__img">
              <img src="<?= get_country_image_url($slug) ?>" alt="<?= $name ?>">
            </div>
            <div class="country-card-large__content" style="background: linear-gradient(to bottom, <?= $color ?>15, #ffffff 40%);">
              <h3 style="color: <?= $color ?>; filter: brightness(0.6);"><?= $name ?></h3>
              <p><?= $desc ?></p>
            </div>
          </a>
        <?php endfor; ?>
        </div>
        
        <div class="dream-country-track reverse">
        <?php 
        // Original 4
        for($i=4; $i<8; $i++): 
            [$slug, $name, $flag, $desc, $color] = $dream_countries[$i];
        ?>
          <a href="study-in-<?= $slug ?>.php" class="country-card-large" style="border-top: 4px solid <?= $color ?>;">
            <div class="country-card-large__img">
              <img src="<?= get_country_image_url($slug) ?>" alt="<?= $name ?>">
            </div>
            <div class="country-card-large__content" style="background: linear-gradient(to bottom, <?= $color ?>15, #ffffff 40%);">
              <h3 style="color: <?= $color ?>; filter: brightness(0.6);"><?= $name ?></h3>
              <p><?= $desc ?></p>
            </div>
          </a>
        <?php endfor; ?>
        <?php 
        // Clones for infinite scroll on mobile
        for($i=4; $i<8; $i++): 
            [$slug, $name, $flag, $desc, $color] = $dream_countries[$i];
        ?>
          <a href="study-in-<?= $slug ?>.php" class="country-card-large mobile-clone" style="border-top: 4px solid <?= $color ?>;">
            <div class="country-card-large__img">
              <img src="<?= get_country_image_url($slug) ?>" alt="<?= $name ?>">
            </div>
            <div class="country-card-large__content" style="background: linear-gradient(to bottom, <?= $color ?>15, #ffffff 40%);">
              <h3 style="color: <?= $color ?>; filter: brightness(0.6);"><?= $name ?></h3>
              <p><?= $desc ?></p>
            </div>
          </a>
        <?php endfor; ?>
        </div>
      </div>
    </div>

    <div style="text-align:center;margin-top:3.5rem">
      <a href="country.php" class="btn btn--outline btn--lg"><i class="fa-solid fa-earth-americas"></i> View All 25+ Destinations</a>
    </div>
  </div>
</section>

<section class="section services-bento" id="services" style="background: #ffffff; display: none;">
  <div class="container">
    <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3rem;">
      <span class="section__tag" style="background: rgba(124,58,237,0.1); color: #7c3aed; border-radius: 20px; padding: 5px 15px; font-weight: 600; display: inline-block; margin-bottom: 1rem; text-transform: none;">What We Do</span>
      <h2 class="section__title" style="text-transform: none; font-size: 2.8rem; font-weight: 500; color: #1e293b; line-height: 1.2;">Comprehensive <span class="serif-italic" style="color: #6366f1; font-weight: 400; font-size: 1.1em;">Study Abroad</span> Services</h2>
      <p class="section__subtitle">From counselling to visa processing &mdash; we support you at every step of your international education journey.</p>
    </div>

    <!-- Full-width grid container -->
    <div style="width: 100%; overflow: hidden;">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY id ASC LIMIT 8");
          $services_db = $stmt->fetchAll();
          $services = [];
          foreach ($services_db as $s) {
              $services[] = [
                  $s['icon'],
                  $s['title'],
                  $s['description'],
                  $s['link'],
                  $s['color']
              ];
          }
      } catch (PDOException $e) {
          $services = [
              ['fa-user-graduate','Student Counselling','Personalised guidance to help you choose the right course and institution matching your academic goals and budget.','student-counselling.php','blue'],
              ['fa-university','University Selection','We help identify the best-fit universities across 25+ countries based on your profile and aspirations.','university-selection.php','purple'],
              ['fa-file-contract','Admission Processing','Expert application management ensuring all documents are accurate, complete and submitted on time.','admission-processing.php','orange'],
              ['fa-hand-holding-dollar','Financial Assistance','Guidance on scholarships, student loans and funding options to make your dream affordable.','financial-assistance.php','teal'],
              ['fa-passport','Visa Processing','End-to-end visa assistance with a 99% success rate, navigating complex immigration requirements.','visa-processing.php','pink'],
              ['fa-house','Accommodation & Travel','We help arrange housing and travel plans so you arrive and settle comfortably in your new country.','accommodation.php','gold'],
              ['fa-pen-to-square','Test Preparation','Specialised coaching for IELTS, TOEFL and PTE to achieve the scores required by top universities.','test-prep.php','blue'],
              ['fa-briefcase','Part-Time Job Help','Guidance on finding legal part-time work opportunities abroad to support yourself financially.','part-time-jobs.php','purple'],
          ];
      }

      // Dynamic URL normalization mapping to ensure DB records also link directly to dedicated pages
      $linkMap = [
          'services.php?s=counselling' => 'student-counselling.php',
          'services.php?s=university' => 'university-selection.php',
          'services.php?s=admission' => 'admission-processing.php',
          'services.php?s=financial' => 'financial-assistance.php',
          'services.php?s=visa' => 'visa-processing.php',
          'services.php?s=accommodation' => 'accommodation.php',
          'services.php?s=jobs' => 'part-time-jobs.php'
      ];
      $titleMap = [
          'student counselling' => 'student-counselling.php',
          'university selection' => 'university-selection.php',
          'application assistance' => 'admission-processing.php',
          'admission processing' => 'admission-processing.php',
          'scholarship assistance' => 'financial-assistance.php',
          'financial assistance' => 'financial-assistance.php',
          'visa guidance' => 'visa-processing.php',
          'visa processing' => 'visa-processing.php',
          'travel & accommodation' => 'accommodation.php',
          'accommodation & travel' => 'accommodation.php',
          'pre-departure briefing' => 'accommodation.php',
          'ielts/toefl coaching' => 'test-prep.php',
          'part-time job help' => 'part-time-jobs.php',
          'part-time job assistance' => 'part-time-jobs.php'
      ];
      
      $imageMap = [
          'student counselling' => 's7.jpg',
          'university selection' => 's2.png',
          'admission processing' => 's3.jpg',
          'application assistance' => 's3.jpg',
          'financial assistance' => 's5.jpg',
          'scholarship assistance' => 's5.jpg',
          'visa processing' => 's5.jpg',
          'visa guidance' => 's5.jpg',
          'accommodation & travel' => 's9.webp',
          'travel & accommodation' => 's9.webp',
          'pre-departure briefing' => 's9.webp',
          'test preparation' => 's6.jpg',
          'ielts/toefl coaching' => 's6.jpg',
          'part-time job help' => 's6.jpg',
          'part-time job assistance' => 's6.jpg'
      ];

      foreach ($services as &$s_item) {
          $currentLink = $s_item[3];
          $titleLower = strtolower(trim($s_item[1]));
          if (isset($linkMap[$currentLink])) {
              $s_item[3] = $linkMap[$currentLink];
          } elseif ($currentLink === '#' || strpos($currentLink, 'services.php') !== false) {
              if (isset($titleMap[$titleLower])) {
                  $s_item[3] = $titleMap[$titleLower];
              }
          }
      }
      unset($s_item); // break loop reference safety
      ?>
      <div class="portfolio-grid">
      <?php
      // Varying heights to create the masonry effect, reduced to fit screen
      $heights = ['200px', '260px', '220px', '280px', '210px', '250px', '270px', '190px'];
      foreach($services as $i=>[$icon,$title,$desc,$link,$color]):
          $imgHeight = $heights[$i % 8];
          $titleLower = strtolower(trim($title));
          $imgSrc = isset($imageMap[$titleLower]) ? $imageMap[$titleLower] : "img".(($i % 8) + 1).".png";
      ?>
      <a href="<?= $link ?>" class="portfolio-card animate-on-scroll delay-<?= $i%4 ?>" style="height: <?= $imgHeight ?>;">
        <img src="assets/images/<?= $imgSrc ?>" alt="<?= $title ?>" class="portfolio-card__bg">
        <div class="portfolio-card__overlay"></div>
        <div class="portfolio-card__content">
          <h3 class="portfolio-card__title"><?= $title ?></h3>
          <p class="portfolio-card__desc"><?= $desc ?></p>
        </div>
      </a>
      <?php endforeach; ?>
      </div>
  </div>
</section>
<!-- STUDENT ESSENTIALS SERVICES (PINK CONTAINER) -->
<section class="section student-essentials" style="background: #ffffff; padding: 4rem 0;">
  <div class="container">
    <div style="background: #db2777; border-radius: 30px; padding: 4rem; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(219, 39, 119, 0.25);">
      
      <!-- Decorative blob background shapes -->
      <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(20px); pointer-events: none;"></div>
      <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(30px); pointer-events: none;"></div>

      <div style="position: relative; z-index: 1;">
        <div class="section__header animate-on-scroll" style="margin-bottom: 4rem; text-align: center;">
          <span class="section__tag" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3);">Beyond Academics</span>
          <h2 class="section__title" style="margin: 0; color: #ffffff; font-size: 2.8rem;">Everything You Need for Your <span style="color: #facc15; -webkit-text-fill-color: initial; background: none;">New Journey</span></h2>
          <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem; max-width: 600px; margin: 1rem auto 0;">Essential services to help you settle in, manage your finances, and thrive in your new study destination.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
          
          <!-- Education loan -->
          <div class="ess-card-pink animate-on-scroll">
            <div class="ess-icon-wrapper" style="color: #f59e0b;"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            <div class="ess-content">
              <h3>Education Loan</h3>
              <p>Get the financial support you need to fund your education and turn your study-abroad plans into reality.</p>
              <a href="education-loan.php">Learn More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
          
          <!-- Accommodation -->
          <div class="ess-card-pink animate-on-scroll delay-1">
            <div class="ess-icon-wrapper" style="color: #10b981;"><i class="fa-solid fa-house"></i></div>
            <div class="ess-content">
              <h3>Accommodation</h3>
              <p>Find a comfortable place to stay, from student residences and apartments to homestays.</p>
              <a href="accommodation.php">Learn More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
          
          <!-- Banking -->
          <div class="ess-card-pink animate-on-scroll delay-2">
            <div class="ess-icon-wrapper" style="color: #8b5cf6;"><i class="fa-solid fa-building-columns"></i></div>
            <div class="ess-content">
              <h3>Banking</h3>
              <p>Set up your bank account with ease and manage your finances confidently from the moment you arrive.</p>
              <a href="bank-account.php">Learn More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
          
          <!-- Health cover -->
          <div class="ess-card-pink animate-on-scroll">
            <div class="ess-icon-wrapper" style="color: #ef4444;"><i class="fa-solid fa-heart-pulse"></i></div>
            <div class="ess-content">
              <h3>Health Cover</h3>
              <p>Stay protected abroad with suitable health insurance and enjoy greater peace of mind throughout your journey.</p>
              <a href="health-insurance.php">Learn More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
          
          <!-- Money transfer -->
          <div class="ess-card-pink animate-on-scroll delay-1">
            <div class="ess-icon-wrapper" style="color: #eab308;"><i class="fa-solid fa-money-bill-transfer"></i></div>
            <div class="ess-content">
              <h3>Money Transfer</h3>
              <p>Send and receive funds securely with convenient and reliable international money transfer solutions.</p>
              <a href="money-transfer.php">Learn More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
          
          <!-- SIM Cards -->
          <div class="ess-card-pink animate-on-scroll delay-2">
            <div class="ess-icon-wrapper" style="color: #0ea5e9;"><i class="fa-solid fa-sim-card"></i></div>
            <div class="ess-content">
              <h3>SIM Cards</h3>
              <p>Stay connected from day one with a local SIM card and hassle-free mobile connectivity.</p>
              <a href="sim-card.php">Learn More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
          
        </div>
        
        <style>
          .ess-card-pink {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 2rem;
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            transition: all 0.4s ease;
            cursor: pointer;
            position: relative;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
          }
          .ess-card-pink:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            border-color: #facc15;
          }
          .ess-card-pink .ess-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            flex-shrink: 0;
            transition: all 0.4s ease;
          }
          .ess-card-pink:hover .ess-icon-wrapper i {
            transform: scale(1.1);
          }
          .ess-card-pink .ess-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
          }
          .ess-card-pink .ess-content h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.5rem;
          }
          .ess-card-pink .ess-content p {
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
          }
          .ess-card-pink .ess-content a {
            color: #db2777;
            font-weight: 700;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            margin-top: auto;
            text-transform: uppercase;
            letter-spacing: 0.5px;
          }
          .ess-card-pink:hover .ess-content a {
            color: #be185d;
          }
          .ess-card-pink .ess-content a i {
            font-size: 0.8rem;
            transition: transform 0.3s;
          }
          .ess-card-pink:hover .ess-content a i {
            transform: translateX(5px);
          }
        </style>
      </div>
    </div>
  </div>
</section>
<!-- STUDENT SUCCESS STORIES -->
<!-- GALLERY SECTION REDESIGN -->
<section class="gallery-redesign" id="gallery">
  <div class="gallery-redesign__bg">
    <div class="gallery-redesign__map"></div>
    
    <!-- Dashed Plane Trail -->
    <svg class="gallery-redesign__trail" viewBox="0 0 1200 300" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M-100,250 C 300,300 700,50 1300,100" fill="none" stroke="#e5e7eb" stroke-width="2" stroke-dasharray="12, 12" />
    </svg>
    <div class="gallery-redesign__plane"><i class="fa-solid fa-plane"></i></div>
    
    <!-- Decorative Landmarks -->
    <i class="fa-solid fa-monument gallery-redesign__landmark gallery-redesign__landmark--left"></i>
    <i class="fa-solid fa-tower-observation gallery-redesign__landmark gallery-redesign__landmark--right"></i>
  </div>
  <div class="container">
    <div class="gallery-redesign__header animate-on-scroll">
      <h2 class="gallery-redesign__title">From local aspirations to<br><span class="highlight">global graduation</span></h2>
      <div class="gallery-redesign__divider"></div>
    </div>
    
    <div class="gallery-redesign__grid animate-on-scroll delay-1">
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM gallery_items WHERE is_active = 1 ORDER BY id DESC LIMIT 7");
          $db_gallery = $stmt->fetchAll();
      } catch (PDOException $e) {
          $db_gallery = [];
      }
      
      $fallbacks = [
          ['image_path' => 'assets/images/md-gallery5.png', 'title' => 'Student Seminar Event'],
          ['image_path' => 'assets/images/ocs5.png', 'title' => 'IELTS Coaching Session'],
          ['image_path' => 'assets/images/start.png', 'title' => 'Pre-Departure Briefing'],
          ['image_path' => 'assets/images/img1.png', 'title' => 'Visa Success Meet'],
          ['image_path' => 'assets/images/ocs.png', 'title' => 'Placement Seminar'],
          ['image_path' => 'assets/images/img2.png', 'title' => 'University Tour'],
          ['image_path' => 'assets/images/img3.png', 'title' => 'Admission Success']
      ];
      
      $items = [];
      for ($i=0; $i<7; $i++) {
          if (isset($db_gallery[$i])) {
              $items[] = $db_gallery[$i];
          } else {
              $items[] = $fallbacks[$i % count($fallbacks)];
          }
      }
      ?>

      <!-- Column 1 -->
      <div class="gr-item gr-item-1">
        <img src="<?= clean_output($items[0]['image_path']) ?>" alt="<?= clean_output($items[0]['title']) ?>">
      </div>

      <!-- Column 2 -->
      <div class="gr-col-wrapper-2" style="display: flex; flex-direction: column; gap: 1.5rem; height: 100%; justify-content: center;">
        <div class="gr-item gr-item-2" style="grid-column: auto; margin: 0;">
          <img src="<?= clean_output($items[1]['image_path']) ?>" alt="<?= clean_output($items[1]['title']) ?>">
        </div>
        <div class="gr-item gr-item-3" style="grid-column: auto;">
          <img src="<?= clean_output($items[2]['image_path']) ?>" alt="<?= clean_output($items[2]['title']) ?>">
        </div>
      </div>

      <!-- Column 3 (Center) -->
      <div class="gr-item gr-item-4">
        <img src="<?= clean_output($items[3]['image_path']) ?>" alt="<?= clean_output($items[3]['title']) ?>">
      </div>

      <!-- Column 4 -->
      <div class="gr-col-wrapper-4" style="display: flex; flex-direction: column; gap: 1.5rem; height: 100%; justify-content: center;">
        <div class="gr-item gr-item-5" style="grid-column: auto; margin: 0;">
          <img src="<?= clean_output($items[4]['image_path']) ?>" alt="<?= clean_output($items[4]['title']) ?>">
        </div>
        <div class="gr-item gr-item-6" style="grid-column: auto;">
          <img src="<?= clean_output($items[5]['image_path']) ?>" alt="<?= clean_output($items[5]['title']) ?>">
        </div>
      </div>

      <!-- Column 5 -->
      <div class="gr-item gr-item-7">
        <img src="<?= clean_output($items[6]['image_path']) ?>" alt="<?= clean_output($items[6]['title']) ?>">
      </div>

    </div>
    <div style="text-align:center; margin-top:3rem">
      <p class="gallery-redesign__subtitle" style="font-family: 'Dancing Script', 'Caveat', 'Brush Script MT', cursive; font-size: clamp(2rem, 8vw, 3.5rem); color: #0ea5e9; font-weight: 400; transform: rotate(-2deg); margin-bottom: 2rem; letter-spacing: 1px; line-height: 1.2;">Bluestone Overseas journey continues.....</p>
      <style>
        .gallery-btn { border-color: #ec4899; color: #ec4899; }
        .gallery-btn:hover { background-color: #ec4899; color: #ffffff !important; }
      </style>
      <a href="gallery.php" class="btn btn--outline gallery-btn">View All Gallery <i class="fa-solid fa-images"></i></a>
    </div>
  </div>
</section>
<!-- VIDEO TESTIMONIALS -->
<section class="section testimonials-section" id="testimonials" style="background: #1e293b; padding: 5rem 0;">
  <div class="container">
    <div class="section__header animate-on-scroll" style="text-align: left; margin-bottom: 3rem;">
      <h2 class="section__title" style="font-size: 2.2rem; max-width: 1000px; line-height: 1.4; color: #ffffff; margin-bottom: 0.5rem;">Hear directly from our successful scholars sharing their <span style="color: #0ea5e9;">visa journey and university experiences.</span></h2>
      <div class="accent-bar" style="margin: 1.5rem 0 0; background: #ec4899; width: 60px; height: 4px; border-radius: 2px;"></div>
    </div>
    
    <div class="video-reels-grid">
      <style>
        .video-reels-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
        .reel-card {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            aspect-ratio: 9/16;
            background: #000;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .reel-card:hover {
            transform: translateY(-5px);
        }
        .reel-card iframe, .reel-card video {
            width: 100%;
            height: 100%;
            border: none;
            pointer-events: none;
            object-fit: cover;
        }
        .reel-play-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: transform 0.2s;
        }
        .reel-card:hover .reel-play-btn {
            transform: translate(-50%, -50%) scale(1.1);
        }
        .reel-play-btn i {
            color: #1e293b;
            font-size: 1.2rem;
            margin-left: 3px;
        }
        .reel-content {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 3rem 1rem 1.5rem;
            background: linear-gradient(to top, rgba(0,0,0,0.95), rgba(0,0,0,0));
            z-index: 10;
            text-align: center;
        }
        @media (max-width: 992px) {
            .video-reels-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .video-reels-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        }
      </style>
      
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM testimonial_videos WHERE is_active = 1 ORDER BY id DESC LIMIT 4");
          $db_videos = $stmt->fetchAll();
      } catch (PDOException $e) {
          $db_videos = [];
      }
      
      if (empty($db_videos)) {
          $db_videos = [
              ['student_name' => 'Sai Raksha', 'details' => 'MSc in United Kingdom', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
              ['student_name' => 'Ashok Saravanan', 'details' => 'MBA in Canada', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
              ['student_name' => 'Priya K.', 'details' => 'MS in United States', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
              ['student_name' => 'Anish Kumar', 'details' => 'BE in Australia', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ']
          ];
      }
      
      foreach($db_videos as $i => $video):
          $v_src = clean_output($video['youtube_url']);
          $is_local = (strpos($v_src, 'uploads/') === 0);
      ?>
      <div class="reel-card animate-on-scroll delay-<?= $i ?>" onclick="playVideo(this)">
        <?php if ($is_local): ?>
            <video src="<?= $v_src ?>" muted loop playsinline></video>
        <?php else: ?>
            <iframe src="<?= $v_src ?>?autoplay=0&controls=0&mute=1&loop=1&showinfo=0&rel=0" allowfullscreen></iframe>
        <?php endif; ?>
        
        <div class="reel-play-btn">
            <i class="fa-solid fa-play"></i>
        </div>
        
        <div class="reel-content">
            <h4 style="color: #facc15; font-size: 1.1rem; font-weight: 800; margin: 0 0 0.3rem;"><?= clean_output($video['student_name']) ?></h4>
            <p style="color: #ffffff; font-size: 0.85rem; margin: 0; font-weight: 500;"><?= clean_output($video['details']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    
    <div style="text-align: center; margin-top: 3.5rem;">
        <a href="testimonial-videos.php" class="btn btn--outline" style="border-color: #ec4899; color: #ec4899; background: transparent;">
          View All Stories <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
  </div>
</section>

<script>
function playVideo(card) {
    // Stop all other videos and reset their UI
    document.querySelectorAll('.reel-card').forEach(otherCard => {
        if (otherCard !== card) {
            otherCard.querySelector('.reel-play-btn').style.display = 'flex';
            otherCard.querySelector('.reel-content').style.display = 'block';
            
            const otherMedia = otherCard.querySelector('video') || otherCard.querySelector('iframe');
            otherMedia.style.pointerEvents = 'none';
            
            if (otherMedia.tagName === 'VIDEO') {
                otherMedia.pause();
                otherMedia.muted = true;
                otherMedia.controls = false;
            } else if (otherMedia.tagName === 'IFRAME') {
                let src = otherMedia.src;
                if (src.includes('autoplay=1')) {
                    otherMedia.src = src.replace('autoplay=1', 'autoplay=0').replace('mute=0', 'mute=1');
                }
            }
        }
    });

    // Play the clicked video
    card.querySelector('.reel-play-btn').style.display = 'none';
    card.querySelector('.reel-content').style.display = 'none';
    
    const media = card.querySelector('video') || card.querySelector('iframe');
    media.style.pointerEvents = 'auto';
    
    if (media.tagName === 'VIDEO') {
        media.controls = true;
        media.muted = false;
        media.play();
    } else if (media.tagName === 'IFRAME') {
        let src = media.src;
        if (src.includes('autoplay=0')) {
            media.src = src.replace('autoplay=0', 'autoplay=1').replace('mute=1', 'mute=0');
        } else if (!src.includes('autoplay=1')) {
            media.src += '&autoplay=1&mute=0';
        }
    }
}
</script>


<!-- CONTACT SECTION -->
<style>
.cf-grid-2 {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}
@media (max-width: 768px) {
  .cf-grid-2 {
    grid-template-columns: 1fr;
  }
}
</style>
<section class="section contact-section" id="contact-home" style="display: none; background: #ffffff; padding: 4rem 0;">
  <div class="container">
    <div style="background: #14b8a6; border-radius: 30px; padding: 4rem; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(20, 184, 166, 0.25);">
      
      <!-- Decorative faint background shapes -->
      <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
      <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>

      <div style="position: relative; z-index: 1;">
        <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3rem;">
          <span class="section__tag" style="background: rgba(255,255,255,0.2); color: #fff;">Contact Us</span>
          <h2 class="section__title" style="color: #fff;">Get a Free <span style="color: #FDE047; background: none; -webkit-text-fill-color: initial;">Consultation</span></h2>
          <p class="section__subtitle" style="color: rgba(255,255,255,0.9);">Reach out to our experts and start your journey today. We respond within 24 hours.</p>
        </div>
        
        <div class="contact-grid">
          <div class="animate-on-scroll">
            <h3 style="color: #fff; margin-bottom: 1rem; font-size: 2rem;">Talk to Our <span style="color: #FDE047; background: none; -webkit-text-fill-color: initial;">Experts</span></h3>
            <p style="color: rgba(255,255,255,0.9); margin-bottom: 2rem;">Whether you&rsquo;re just starting your study abroad journey or need help with a visa application, our counsellors are here to help — for free.</p>
            
            <div class="contact-cards">
              <div class="contact-card contact-card--glass">
                <div class="stat-icon stat-icon--glass" style="width:40px;height:40px;font-size:1rem"><i class="fa-solid fa-phone"></i></div>
                <div><h4>Call Us</h4><a href="tel:+919342899904">+91 93428 99904</a></div>
              </div>
              <div class="contact-card contact-card--glass">
                <div class="stat-icon stat-icon--glass" style="width:40px;height:40px;font-size:1rem"><i class="fa-solid fa-envelope"></i></div>
                <div><h4>Email Us</h4><a href="mailto:info@bluestoneocs.com">info@bluestoneocs.com</a></div>
              </div>
              <div class="contact-card contact-card--glass">
                <div class="stat-icon stat-icon--glass" style="width:40px;height:40px;font-size:1rem"><i class="fa-regular fa-clock"></i></div>
                <div><h4>Working Hours</h4><p>Mon–Fri: 09:00 AM – 6:30 PM</p></div>
              </div>
            </div>
            
            <a href="<?= SITE_MAP_LINK ?>" target="_blank" style="display:block;margin-top:2rem;padding:1.5rem;background:rgba(255,255,255,0.1);backdrop-filter:blur(10px);border-radius:var(--radius);border:1px solid rgba(255,255,255,0.2);text-decoration:none;color:#fff;transition:transform 0.3s ease,background 0.3s ease;" class="hover-scale-card glass-link">
              <h4 style="margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;color:#fff;"><i class="fa-solid fa-location-dot" style="color:#FDE047;"></i> Head Office – Coimbatore</h4>
              <p style="font-size:.875rem;color:rgba(255,255,255,0.9);line-height:1.7">Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College, Coimbatore, TN - 641018</p>
            </a>
          </div>
          
          <div class="contact-form-wrap animate-on-scroll delay-1" style="background: #ffffff; border: none; box-shadow: 0 25px 50px rgba(0,0,0,0.15);">
            <form id="contactHomeForm" onsubmit="return handleFormSubmit(event)">
              <input type="hidden" name="form_type" value="contact">
              <div class="cf-grid-2">
                <div class="cf-group"><label>First Name *</label><input type="text" name="first_name" placeholder="John" required></div>
                <div class="cf-group"><label>Last Name *</label><input type="text" name="last_name" placeholder="Doe" required></div>
              </div>
              <div class="cf-grid-2">
                <div class="cf-group"><label>Email *</label><input type="email" name="email" placeholder="john@email.com" required></div>
                <div class="cf-group"><label>Phone *</label><input type="tel" name="phone" placeholder="+91 98765 43210" required></div>
              </div>
              <div class="cf-group"><label>Preferred Country</label>
                <select name="destination"><option value="">Select Country</option><option>USA</option><option>UK</option><option>Canada</option><option>Australia</option><option>Germany</option><option>Ireland</option><option>New Zealand</option><option>Singapore</option></select>
              </div>
              <div class="cf-group"><label>Your Message</label>
                <textarea name="query" rows="4" placeholder="How can we help you?"></textarea>
              </div>
              <button type="submit" class="btn btn--primary btn--lg" style="width:100%;justify-content:center">
                <i class="fa-solid fa-paper-plane"></i> Send Message
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- TEAM MEMBERS SECTION -->
<section id="team" class="section team-section bg-light" style="display: none; padding: 5rem 1rem; background: #f8fafc; position: relative;">
  <div class="container">
    <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3.5rem;">
      <span class="section__tag">Our Leadership</span>
      <h2 class="section__title">Meet Our <span>Team</span></h2>
      <p class="section__subtitle" style="margin: 0.5rem auto 0; max-width: 600px;">The experienced professionals dedicated to making your global education dreams a reality.</p>
      <div class="accent-bar" style="margin: 1rem auto 0;"></div>
    </div>
    
    <div style="position: relative; padding: 0 40px;">
      <!-- Navigation Buttons -->
      <button id="teamPrev" class="team-nav-btn" style="position: absolute; left: 0; top: 50%; transform: translateY(-50%); z-index: 10; background: white; border: 1px solid var(--border); width: 45px; height: 45px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.2rem; transition: all 0.3s ease;">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button id="teamNext" class="team-nav-btn" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); z-index: 10; background: white; border: 1px solid var(--border); width: 45px; height: 45px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.2rem; transition: all 0.3s ease;">
        <i class="fa-solid fa-chevron-right"></i>
      </button>

      <!-- Slider Container -->
      <div id="teamSlider" class="team-slider" style="display: flex; gap: 2rem; overflow-x: auto; scroll-snap-type: x mandatory; scroll-behavior: smooth; padding: 2rem 5px 3rem; scrollbar-width: none; -ms-overflow-style: none; align-items: flex-end;">
        <style>
          .team-slider::-webkit-scrollbar { display: none; }
          .team-nav-btn:hover { background: var(--primary); color: white !important; }

          .wave-card {
            min-width: 300px;
            max-width: 320px;
            flex: 0 0 auto;
            scroll-snap-align: center;
            background: #0f172a;
            border-radius: 20px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
            height: 420px;
          }

          .wave-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
          }

          .wave-card__full-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
            transition: transform 0.5s ease;
          }

          .wave-card:hover .wave-card__full-img {
            transform: scale(1.08);
          }

          .wave-card__overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Darken the bottom so the name/role is readable */
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 30%, rgba(0,0,0,0) 60%);
            z-index: 1;
            pointer-events: none;
          }

          .wave-card__bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: auto;
            z-index: 3;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding-bottom: 1.5rem;
          }

          .wave-card__info {
            width: 100%;
            text-align: center;
            z-index: 5;
            padding: 0 1rem;
          }

          .wave-card__title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 5px rgba(0,0,0,0.8);
          }

          .wave-card__role-top {
            font-size: 0.95rem;
            font-weight: 600;
            color: #e2e8f0;
            margin: 0.25rem 0 0 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
          }

          .wave-card__exp {
            font-size: 0.85rem;
            color: #fbbf24;
            margin: 0.25rem 0 0 0;
            font-weight: 600;
            text-shadow: 0 1px 3px rgba(0,0,0,0.8);
          }

          .wave-card__social {
            margin-top: 0.75rem;
          }
          
          .wave-card__social a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            transition: background 0.3s ease;
          }
          
          .wave-card__social a:hover {
            background: #0077b5; /* LinkedIn Blue */
          }

          @media(max-width: 768px) { .wave-card { min-width: 280px; } }
        </style>

        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM team_members WHERE is_active = 1 ORDER BY display_order ASC, id ASC");
            $team_members = $stmt->fetchAll();
        } catch (PDOException $e) {
            $team_members = [];
        }
        
        foreach($team_members as $index => $member):
            $gradClass = "bg-grad-" . ($index % 4);
        ?>
        <div class="wave-card animate-on-scroll" style="animation-delay: <?= $index * 100 ?>ms;">
          
          <img src="<?= clean_output($member['image_path']) ?>" alt="<?= clean_output($member['name']) ?>" class="wave-card__full-img">
          <div class="wave-card__overlay"></div>

          <div class="wave-card__bottom">
            <div class="wave-card__info">
              <h3 class="wave-card__title"><?= clean_output($member['name']) ?></h3>
              <p class="wave-card__role-top"><?= clean_output($member['role']) ?></p>
              <?php if (!empty($member['experience'])): ?>
                <p class="wave-card__exp"><i class="fa-solid fa-briefcase"></i> <?= clean_output($member['experience']) ?></p>
              <?php endif; ?>
              <?php if (!empty($member['linkedin_url']) && $member['linkedin_url'] !== '#'): ?>
                <div class="wave-card__social">
                  <a href="<?= clean_output($member['linkedin_url']) ?>" target="_blank" aria-label="LinkedIn Profile">
                    <i class="fa-brands fa-linkedin-in"></i>
                  </a>
                </div>
              <?php endif; ?>
            </div>
          </div>
          
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<!-- CTA BANNER -->
<section class="cta-banner-wrapper" style="padding: 4rem 1rem;">
  <div class="container cta-banner animate-on-scroll">
    <div class="cta-banner__left">
      <h2>Ready to Start Your Study Abroad Journey?</h2>
      <p>Join 5,000+ students who transformed their future with Bluestone Overseas Consultants.<br>Book your FREE consultation today &mdash; no commitment required!</p>
      
      <div class="cta-buttons">
        <a href="consultation.php" class="btn btn--cyan"><i class="fa-solid fa-graduation-cap"></i> Book Free Consultation</a>
        <a href="tel:+919342899904" class="btn btn--orange"><i class="fa-solid fa-phone"></i> Call +91 93428 99904</a>
      </div>
    </div>
    <div class="cta-banner__right">
      <div class="cta-image-circle">
        <img src="assets/images/cont.png" alt="Happy Student">
      </div>
    </div>
  </div>
</section>
<?php
// Fetch active popups
$popups = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT * FROM site_popup WHERE is_active = 1 ORDER BY id DESC");
        $popups = $stmt->fetchAll();
    } catch (PDOException $e) {
        $popups = [];
    }
}
$popupCount = count($popups);
?>

<?php if ($popupCount > 0): ?>
<div id="sitePopupModal" class="site-popup-overlay">
    <!-- Reduced container size to 400px for smaller posts -->
    <div class="site-popup-container">
        <button id="sitePopupClose" class="site-popup-close">&times;</button>
        
        <?php if ($popupCount === 1): 
            $popup = $popups[0];
            $imgPath = $popup['image_path'];
            if (strpos($imgPath, 'assets/') !== 0) $imgPath = 'assets/images/uploads/' . ltrim($imgPath, '/');
        ?>
            <div class="single-popup-card">
                <?php if (!empty($popup['link_url'])): ?>
                    <a href="<?php echo htmlspecialchars($popup['link_url']); ?>" target="_blank">
                        <img src="<?php echo htmlspecialchars(BASE_URL . $imgPath); ?>" alt="Social Media Business Post">
                    </a>
                <?php else: ?>
                    <img src="<?php echo htmlspecialchars(BASE_URL . $imgPath); ?>" alt="Social Media Business Post">
                <?php endif; ?>
            </div>
            
        <?php else: ?>
            <div class="stack-slider-container">
                <?php foreach ($popups as $index => $popup): 
                    $imgPath = $popup['image_path'];
                    if (strpos($imgPath, 'assets/') !== 0) $imgPath = 'assets/images/uploads/' . ltrim($imgPath, '/');
                ?>
                    <div class="stack-card" data-index="<?= $index ?>">
                        <?php if (!empty($popup['link_url'])): ?>
                            <a href="<?php echo htmlspecialchars($popup['link_url']); ?>" target="_blank">
                                <img src="<?php echo htmlspecialchars(BASE_URL . $imgPath); ?>" alt="Social Media Business Post">
                            </a>
                        <?php else: ?>
                            <img src="<?php echo htmlspecialchars(BASE_URL . $imgPath); ?>" alt="Social Media Business Post">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.site-popup-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.4s ease;
    backdrop-filter: blur(5px);
}
.site-popup-overlay.show {
    display: flex;
    opacity: 1;
}
.site-popup-container {
    position: relative;
    max-width: 540px; /* Slightly larger to allow buffer room */
    width: 85%;
    max-height: 85vh;
    background: transparent;
    /* Removed border-radius and overflow to prevent browser from clipping child layers */
    transform: scale(0.9);
    transition: transform 0.4s ease;
    margin: 20px;
}
.site-popup-overlay.show .site-popup-container {
    transform: scale(1);
}

.single-popup-card {
    background: #fff;
    border: 8px solid #fff;
    border-radius: 12px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.3);
    overflow: hidden;
}
.single-popup-card img {
    width: 100%;
    max-height: 80vh;
    display: block;
    object-fit: contain;
}

.stack-slider-container {
    position: relative;
    width: 100%;
    aspect-ratio: 4/5;
    max-height: 80vh;
    margin: 0 auto;
    perspective: 1200px;
    transform-style: preserve-3d;
    overflow: visible !important;
}
.stack-card {
    position: absolute;
    top: 5%; left: 5%;
    width: 90%; height: 90%;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 15px 35px rgba(0,0,0,0.25);
    transition: transform 0.6s cubic-bezier(0.4, 0.0, 0.2, 1), opacity 0.6s ease;
    border: 8px solid #fff;
    overflow: hidden;
    transform-origin: center center;
}
.stack-card a, .single-popup-card a {
    display: block;
    width: 100%;
    height: 100%;
}
.stack-card img {
    width: 100%; height: 100%;
    object-fit: contain;
    background: #f8fafc;
}

.site-popup-close {
    position: absolute;
    top: -15px;
    right: -15px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    font-size: 22px;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s, transform 0.3s;
}
.site-popup-close:hover {
    background: #dc2626;
    transform: scale(1.1);
}
</style>
<?php endif; ?>

<script>
// ── Popup Modal ──
<?php if ($popupCount > 0): ?>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var popup = document.getElementById('sitePopupModal');
        if (!popup) return;
        popup.classList.add('show');

        var stackCards = document.querySelectorAll('.stack-card');
        if (stackCards.length > 0) {
            var currentIndex = 0;
            var totalCards = stackCards.length;
            function updateStack() {
                stackCards.forEach(function(card, i) {
                    var offset = i - currentIndex;
                    if (offset < 0) offset += totalCards;
                    if (offset === 0) {
                        card.style.transform = 'translateZ(0) rotate(0deg) scale(1)';
                        card.style.zIndex = 100; card.style.opacity = 1;
                    } else if (offset === 1) {
                        card.style.transform = 'translateZ(-50px) translateX(15px) rotate(10deg) scale(0.95)';
                        card.style.zIndex = 90; card.style.opacity = 0.9;
                    } else if (offset === 2) {
                        card.style.transform = 'translateZ(-100px) translateX(-15px) rotate(-15deg) scale(0.9)';
                        card.style.zIndex = 80; card.style.opacity = 0.8;
                    } else {
                        card.style.transform = 'translateZ(-150px) scale(0.8)';
                        card.style.zIndex = 10; card.style.opacity = 0;
                    }
                });
            }
            setTimeout(updateStack, 50);
            setInterval(function() { currentIndex = (currentIndex + 1) % totalCards; updateStack(); }, 3000);
        }
    }, 3000);

    var closeBtn = document.getElementById('sitePopupClose');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            var modal = document.getElementById('sitePopupModal');
            if (modal) {
                modal.classList.remove('show');
                setTimeout(function() { modal.style.display = 'none'; }, 400);
            }
        });
    }
});
<?php endif; ?>
</script>

<!-- Orbital Slider Logic -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const pivot = document.getElementById('orbital-pivot');
    if (!pivot) return; // Only run on home page
    
    const cards = pivot.querySelectorAll('.card-spoke');
    const prevBtn = document.getElementById('orbit-prev');
    const nextBtn = document.getElementById('orbit-next');
    const container = document.getElementById('orbital-slider-container');
    
    let activeIndex = 3; // Center initially (US)
    const totalCards = cards.length;
    const gapAngle = 25; // 25 degrees between each card
    
    let autoPlayInterval;
    const autoPlayDelay = 3000;
    
    function updateSlider() {
        cards.forEach((spoke, index) => {
            const cardAnim = spoke.querySelector('.country-card-anim');
            
            // Calculate shortest distance in a circular array
            let diff = index - activeIndex;
            if (diff > totalCards / 2) diff -= totalCards;
            if (diff < -totalCards / 2) diff += totalCards;
            
            const distance = Math.abs(diff);
            const cardRotation = diff * gapAngle;
            
            // Add a transition class if needed, or rely on existing CSS.
            // Disable transition briefly when a card wraps around to the other side
            if (distance > 2) {
                spoke.style.transition = 'none';
            } else {
                spoke.style.transition = 'transform 0.6s cubic-bezier(0.4, 0.0, 0.2, 1), opacity 0.6s ease';
            }
            
            spoke.style.transform = `rotate(${cardRotation}deg)`;
            
            if (distance === 0) {
                cardAnim.classList.add('active');
                spoke.style.zIndex = 10;
                spoke.style.opacity = 1;
                spoke.style.pointerEvents = 'auto';
            } else {
                cardAnim.classList.remove('active');
                spoke.style.zIndex = 10 - distance;
                
                // Show only 2 cards on each side (5 total)
                if (distance > 2) {
                    spoke.style.opacity = 0;
                    spoke.style.pointerEvents = 'none';
                } else {
                    spoke.style.opacity = 1;
                    spoke.style.pointerEvents = 'auto';
                }
            }
        });
    }
    
    function nextSlide() {
        activeIndex++;
        if (activeIndex >= totalCards) activeIndex = 0; // Loop forward
        updateSlider();
    }
    
    function prevSlide() {
        activeIndex--;
        if (activeIndex < 0) activeIndex = totalCards - 1; // Loop backward
        updateSlider();
    }
    
    // Navigation Events
    if (nextBtn) {
        nextBtn.addEventListener('click', () => { nextSlide(); resetAutoPlay(); });
    }
    if (prevBtn) {
        prevBtn.addEventListener('click', () => { prevSlide(); resetAutoPlay(); });
    }
    
    // Click Card to focus it
    cards.forEach((spoke, index) => {
        const cardAnim = spoke.querySelector('.country-card-anim');
        if (cardAnim) {
            cardAnim.addEventListener('click', () => {
                if (activeIndex !== index) {
                    activeIndex = index;
                    updateSlider();
                    resetAutoPlay();
                }
            });
        }
    });
    
    // Touch/Swipe Support
    let touchStartX = 0;
    let touchEndX = 0;
    
    if (container) {
        container.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
            pauseAutoPlay();
        }, {passive: true});
        
        container.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
            resetAutoPlay();
        });
        
        // Pause on Hover
        container.addEventListener('mouseenter', pauseAutoPlay);
        container.addEventListener('mouseleave', startAutoPlay);
    }
    
    function handleSwipe() {
        const threshold = 50;
        if (touchEndX < touchStartX - threshold) nextSlide();
        if (touchEndX > touchStartX + threshold) prevSlide();
    }
    
    // Auto Play Logic
    function startAutoPlay() {
        autoPlayInterval = setInterval(nextSlide, autoPlayDelay);
    }
    function pauseAutoPlay() {
        clearInterval(autoPlayInterval);
    }
    function resetAutoPlay() {
        pauseAutoPlay();
        startAutoPlay();
    }
    
    // Init
    updateSlider();
    startAutoPlay();
});
</script>

</main>
<?php require_once 'includes/footer.php'; ?>

