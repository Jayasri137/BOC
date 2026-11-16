<?php
require_once 'includes/config.php';
$pageTitle = 'Student Accommodation Abroad | Bluestone Overseas';
$pageDesc = 'Find safe, affordable, and convenient student accommodation abroad. Explore options in the UK, Australia, Canada, USA, and more with Bluestone Overseas.';
require_once 'includes/header.php';
?>
<main>
  <!-- 1. Hero Section -->
  <section class="section" style="position: relative; overflow: hidden; padding-top: 1.5rem; padding-bottom: 5rem; background-color: #ffffff;">
    <div style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(14,165,233,0.1) 0%, transparent 70%); border-radius: 50%; z-index: 1;"></div>
    
    <div class="container" style="position: relative; z-index: 2;">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem; align-items: center;">
        
        <!-- Text Side -->
        <div class="animate-on-scroll">
          <h1 style="font-size: clamp(1.5rem, 4vw, 3rem); line-height: 1.15; margin-bottom: 1.5rem; color: #1e3a8a; font-weight: 800;">
            Your Home Away <br>
            From <span style="color: #0ea5e9;">Home.</span>
          </h1>
          <p style="color: #334155; font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem;">
            Safe, Affordable, and Student-Friendly Accommodation Abroad.
          </p>
          <p style="color: var(--gray); font-size: 1.15rem; line-height: 1.7; margin-bottom: 2.5rem; max-width: 600px;">
            Finding the perfect place to live is a crucial part of your study abroad journey. We help you secure the best student housing near your university, fitting your budget and lifestyle preferences.
          </p>
          <a href="consultation.php" class="btn btn--primary btn--lg pulse-btn">Get Accommodation Help <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i></a>
        </div>

        <!-- Image Side -->
        <div class="animate-on-scroll" style="position: relative;">
          <div style="position: absolute; bottom: -30px; right: -30px; width: 250px; height: 250px; background: radial-gradient(circle, rgba(236,72,153,0.2) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>
          <!-- Using a placeholder or appropriate image from assets -->
          <img src="assets/images/s3.jpg" alt="Student Accommodation" style="width: 100%; height: auto; display: block; max-width: 600px; margin: 0 auto; border-radius: 24px; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.1));">
        </div>

      </div>
    </div>
  </section>


  <!-- 2. Why Book With Us (Slider with Organic Wave Cards) -->
  <style>
    .slider-wrapper {
        position: relative;
        width: 100%;
    }
    .feature-grid {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        gap: 2rem;
        padding-top: 1rem;
        padding-bottom: 2rem;
        margin-top: 1rem;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .feature-grid::-webkit-scrollbar {
        display: none;
    }
    .feature-card {
        flex: 0 0 320px;
        scroll-snap-align: start;
        background: #ffffff;
        border-radius: 20px;
        padding: 3rem 2rem 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        z-index: 1;
        display: flex;
        flex-direction: column;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
        min-height: 400px;
        cursor: default;
        border: 1px solid #f1f5f9;
    }
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }
    
    .card-wave {
        position: absolute;
        bottom: calc(-100% + 15px); /* Only a sliver showing initially */
        left: -10%;
        width: 120%;
        height: 100%;
        background: var(--card-color);
        /* Creates an asymmetrical organic wave top */
        border-radius: 45% 55% 0 0 / 10% 15% 0 0;
        z-index: 0;
        transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1);
    }
    .feature-card:hover .card-wave {
        bottom: -25%; /* Wave expands to cover text */
        border-radius: 55% 45% 0 0 / 15% 10% 0 0; /* Subtle morph */
    }

    .card-watermark {
        position: absolute;
        bottom: -20px;
        right: -20px;
        font-size: 10rem;
        color: var(--card-color);
        opacity: 0.08;
        z-index: 1;
        transition: color 0.6s ease, opacity 0.6s ease, transform 0.6s ease;
        pointer-events: none; /* Prevents blocking clicks */
    }
    .feature-card:hover .card-watermark {
        color: #ffffff;
        opacity: 0.15;
        transform: scale(1.05);
    }

    .feature-icon-wrapper {
        width: 65px;
        height: 65px;
        border-radius: 18px; /* Squircle appearance */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 2rem;
        background: var(--card-color);
        color: #ffffff;
        box-shadow: 0 10px 20px var(--card-shadow);
        position: relative;
        z-index: 2;
        transition: transform 0.4s ease;
    }
    .feature-card:hover .feature-icon-wrapper {
        transform: scale(1.08);
    }
    
    .feature-card h4 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 1rem;
        transition: color 0.4s ease;
        position: relative;
        z-index: 2;
    }
    .feature-card p {
        font-size: 0.95rem;
        color: #64748b;
        line-height: 1.6;
        margin: 0;
        transition: color 0.4s ease;
        position: relative;
        z-index: 2;
    }
    
    .feature-card:hover h4, 
    .feature-card:hover p {
        color: #ffffff;
    }

    /* Slider Controls */
    .slider-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .slider-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #1e293b;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .slider-btn:hover {
        background: #1e293b;
        color: #ffffff;
        transform: translateY(-2px);
        border-color: #1e293b;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        .slider-header-flex { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
        .feature-card { flex: 0 0 280px; }
    }
  </style>
  <section class="section" style="background-color: #f8fafc; padding: 6rem 1rem; position: relative;">
    <!-- Background Decor -->
    <div style="position: absolute; top: 0; right: 0; width: 100%; height: 100%; background: radial-gradient(circle at top right, rgba(56,189,248,0.05) 0%, transparent 50%); pointer-events: none;"></div>
    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle at bottom left, rgba(236,72,153,0.05) 0%, transparent 50%); pointer-events: none;"></div>

    <div class="container animate-on-scroll" style="position: relative; z-index: 2;">
      
      <div class="slider-header-flex">
        <div class="text-left" style="max-width: 600px;">
          <span class="section__tag" style="background: white; border: 1px solid #e2e8f0;">Why Choose Our Partners</span>
          <h2 class="section__title" style="margin-bottom: 0;">Quality Living for <span>Quality Learning.</span></h2>
          <p class="section__subtitle" style="margin-top: 1rem; margin-bottom: 0;">We ensure your accommodation is secure, comfortable, and conducive to your academic success.</p>
        </div>
        
        <div class="slider-controls" style="display: flex; gap: 1rem;">
          <button id="featPrevBtn" class="slider-btn" aria-label="Previous slide"><i class="fa-solid fa-chevron-left"></i></button>
          <button id="featNextBtn" class="slider-btn" aria-label="Next slide"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
      </div>

      <div class="slider-wrapper">
        <div class="feature-grid" id="featureSlider">
          
          <div class="feature-card" style="--card-color: #10b981; --card-shadow: rgba(16, 185, 129, 0.4);">
              <div class="card-wave"></div>
              <div class="card-watermark"><i class="fa-solid fa-shield-halved"></i></div>
              <div class="feature-icon-wrapper"><i class="fa-solid fa-shield-halved"></i></div>
              <h4>Safe & Secure</h4>
              <p>Vetted properties with 24/7 security, CCTV, and secure access systems.</p>
          </div>

          <div class="feature-card" style="--card-color: #0ea5e9; --card-shadow: rgba(14, 165, 233, 0.4);">
              <div class="card-wave"></div>
              <div class="card-watermark"><i class="fa-solid fa-map-location-dot"></i></div>
              <div class="feature-icon-wrapper"><i class="fa-solid fa-map-location-dot"></i></div>
              <h4>Prime Locations</h4>
              <p>Close to universities, public transport, and city centers for your convenience.</p>
          </div>

          <div class="feature-card" style="--card-color: #f59e0b; --card-shadow: rgba(245, 158, 11, 0.4);">
              <div class="card-wave"></div>
              <div class="card-watermark"><i class="fa-solid fa-tags"></i></div>
              <div class="feature-icon-wrapper"><i class="fa-solid fa-tags"></i></div>
              <h4>Best Prices</h4>
              <p>Options to suit every budget, often with utilities and Wi-Fi included.</p>
          </div>

          <div class="feature-card" style="--card-color: #ec4899; --card-shadow: rgba(236, 72, 153, 0.4);">
              <div class="card-wave"></div>
              <div class="card-watermark"><i class="fa-solid fa-users"></i></div>
              <div class="feature-icon-wrapper"><i class="fa-solid fa-users"></i></div>
              <h4>Student Community</h4>
              <p>Vibrant social spaces, events, and networking opportunities with peers.</p>
          </div>

          <div class="feature-card" style="--card-color: #8b5cf6; --card-shadow: rgba(139, 92, 246, 0.4);">
              <div class="card-wave"></div>
              <div class="card-watermark"><i class="fa-solid fa-headset"></i></div>
              <div class="feature-icon-wrapper"><i class="fa-solid fa-headset"></i></div>
              <h4>End-to-End Support</h4>
              <p>From exploring options to signing the lease and moving in, our team guides you through the entire process seamlessly.</p>
          </div>

        </div>
      </div>
      
      <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('featureSlider');
            const prevBtn = document.getElementById('featPrevBtn');
            const nextBtn = document.getElementById('featNextBtn');
            
            if(slider && prevBtn && nextBtn) {
                const getScrollAmount = () => {
                    const card = slider.querySelector('.feature-card');
                    // card width + gap (2rem = 32px roughly)
                    return card ? card.offsetWidth + 32 : 352;
                };

                prevBtn.addEventListener('click', () => {
                    slider.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
                });

                nextBtn.addEventListener('click', () => {
                    slider.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
                });
            }
        });
      </script>

    </div>
  </section>

  <!-- 3. Types of Accommodation -->
  <section class="section" style="background-color: #f8fafc; position: relative; overflow: hidden;">
    <div style="position: absolute; right: -5%; top: -10%; font-size: 30rem; color: rgba(0,0,0,0.02); z-index: 0; font-weight: 900; line-height: 1;"><i class="fa-solid fa-building"></i></div>
    
    <div class="container" style="position: relative; z-index: 1;">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">Housing Options</span>
        <h2 class="section__title">Find the setup that fits <span>your lifestyle.</span></h2>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem; margin-top: 4rem; align-items: center;">
        
        <!-- Content Side -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="animate-on-scroll hover-lift" style="background: #0f766e; border: 1px solid #115e59; padding: 2rem; border-radius: 20px; box-shadow: 0 15px 35px rgba(15,118,110,0.2); position: relative; overflow: hidden; border-left: 6px solid #2dd4bf;">
                <div style="font-size: 1.25rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; position: relative; z-index: 1;"><i class="fa-solid fa-building-user" style="margin-right: 10px;"></i> Purpose-Built Student Accommodation (PBSA)</div>
                <p style="color: #ccfbf1; font-size: 0.95rem; line-height: 1.6; margin: 0; position: relative; z-index: 1;">Modern apartment complexes designed specifically for students. Features en-suite rooms, studios, gyms, and communal study areas.</p>
            </div>

            <div class="animate-on-scroll hover-lift delay-1" style="background: #4338ca; border: 1px solid #3730a3; padding: 2rem; border-radius: 20px; box-shadow: 0 15px 35px rgba(67,56,202,0.2); position: relative; overflow: hidden; border-left: 6px solid #818cf8; margin-left: 2rem;">
                <div style="font-size: 1.25rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; position: relative; z-index: 1;"><i class="fa-solid fa-house" style="margin-right: 10px;"></i> Private Rentals & Shared Houses</div>
                <p style="color: #e0e7ff; font-size: 0.95rem; line-height: 1.6; margin: 0; position: relative; z-index: 1;">Rent a flat or a house with friends. Great for independence and experiencing authentic local living.</p>
            </div>

            <div class="animate-on-scroll hover-lift delay-2" style="background: #be185d; border: 1px solid #9f1239; padding: 2rem; border-radius: 20px; box-shadow: 0 15px 35px rgba(190,24,93,0.2); position: relative; overflow: hidden; border-left: 6px solid #f472b6;">
                <div style="font-size: 1.25rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; position: relative; z-index: 1;"><i class="fa-solid fa-people-roof" style="margin-right: 10px;"></i> Homestay</div>
                <p style="color: #fce7f3; font-size: 0.95rem; line-height: 1.6; margin: 0; position: relative; z-index: 1;">Live with a local host family. Ideal for improving language skills and immersing yourself in the destination\'s culture.</p>
            </div>
            
            <div class="animate-on-scroll hover-lift delay-3" style="background: #b45309; border: 1px solid #92400e; padding: 2rem; border-radius: 20px; box-shadow: 0 15px 35px rgba(180,83,9,0.2); position: relative; overflow: hidden; border-left: 6px solid #fbbf24; margin-left: 2rem;">
                <div style="font-size: 1.25rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; position: relative; z-index: 1;"><i class="fa-solid fa-school" style="margin-right: 10px;"></i> On-Campus Halls</div>
                <p style="color: #fef3c7; font-size: 0.95rem; line-height: 1.6; margin: 0; position: relative; z-index: 1;">University-owned dormitories right on campus. Perfect for first-year students looking for extreme convenience.</p>
            </div>
        </div>

        <!-- Image Side -->
        <div class="animate-on-scroll delay-2" style="position: relative;">
            <div style="position: absolute; top: -20px; right: -20px; bottom: 20px; left: 20px; background: linear-gradient(135deg, #e0f2fe, #fce7f3); border-radius: 24px; z-index: 0;"></div>
            <!-- Using a placeholder or appropriate image from assets -->
            <img src="assets/images/img.png" alt="Different types of student accommodation" style="width: 100%; height: 100%; object-fit: cover; border-radius: 24px; min-height: 400px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); position: relative; z-index: 1;">
        </div>

      </div>
    </div>
  </section>

  <!-- 4. How It Works Section -->
  <section class="section" style="background-color: #ffffff;">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <span class="section__tag">The Process</span>
        <h2 class="section__title">3 Simple Steps to Your <span>New Home.</span></h2>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 4rem;">
        
        <!-- Step 1 -->
        <div class="animate-on-scroll" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); padding: 2.5rem 2rem; text-align: center;">
            <div style="background: #e0f2fe; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #0ea5e9; margin: 0 auto 1.5rem;">
                1
            </div>
            <h4 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">Explore & Compare</h4>
            <p style="font-size: 0.95rem; color: #64748b; line-height: 1.6; margin: 0;">Tell us your university, budget, and preferences. We\'ll provide a curated list of the best options.</p>
        </div>

        <!-- Step 2 -->
        <div class="animate-on-scroll delay-1" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); padding: 2.5rem 2rem; text-align: center;">
            <div style="background: #fce7f3; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #ec4899; margin: 0 auto 1.5rem;">
                2
            </div>
            <h4 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">Select Your Room</h4>
            <p style="font-size: 0.95rem; color: #64748b; line-height: 1.6; margin: 0;">Choose your preferred room type, from shared dorms to private studios, and verify the amenities.</p>
        </div>

        <!-- Step 3 -->
        <div class="animate-on-scroll delay-2" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); padding: 2.5rem 2rem; text-align: center;">
            <div style="background: #dcfce7; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #10b981; margin: 0 auto 1.5rem;">
                3
            </div>
            <h4 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">Book securely</h4>
            <p style="font-size: 0.95rem; color: #64748b; line-height: 1.6; margin: 0;">Complete your booking with secure payments and lease signing guidance from our experts.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- 5. CTA section -->
  <section class="section" style="background-color: #f8fafc;">
    <div class="container animate-on-scroll">
      <div style="background: linear-gradient(135deg, #1e293b, #0f172a); padding: 4rem 2rem; border-radius: var(--radius-lg); text-align: center; color: white; box-shadow: var(--shadow-lg); border: 1px solid rgba(255,255,255,0.1);">
        
        <h2 style="font-size: clamp(2rem, 4vw, 2.5rem); margin-bottom: 1rem; font-weight: 800;">Secure Your Student Home Today</h2>
        <p style="font-size: 1.15rem; color: #cbd5e1; max-width: 600px; margin: 0 auto 2.5rem; line-height: 1.6;">Don't wait until the last minute. The best properties sell out fast. Let us help you find the perfect place.</p>
        
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem;">
            <a href="consultation.php" class="btn btn--primary btn--lg pulse-btn">Enquire Now <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></a>
            <a href="https://wa.me/919342899904" target="_blank" class="btn btn--lg" style="background: #25D366; color: white; border: none;"><i class="fa-brands fa-whatsapp" style="margin-right: 8px; font-size: 1.2rem;"></i> Chat on WhatsApp</a>
        </div>
        
      </div>
    </div>
  </section>

</main>
<?php require_once 'includes/footer.php'; ?>
