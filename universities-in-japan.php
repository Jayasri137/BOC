<?php
require_once 'includes/config.php';
$pageTitle = 'Top Universities in Japan | Bluestone Overseas';
$pageDescription = 'Explore the top national, imperial, and private universities in Japan for international students.';
$pageKeywords = 'Top Universities in Japan, Imperial Universities Japan, Study in Japan';
$pageHeroImage = 'assets/images/areowomen.png';
require_once 'includes/header.php';
?>

<style>
/* Premium Theme Variables (Purple) */
:root {
  --pte-primary: #8b5cf6; 
  --pte-light: #f5f3ff;
  --pte-gradient: linear-gradient(135deg, #a78bfa, #7c3aed);
  --dark: #0f172a;
  --gray: #475569;
}

.pte-text-gradient {
  background: var(--pte-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.pte-hero { padding: 8rem 0 6rem; background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); text-align: center; }
.pte-hero-title { font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; color: var(--dark); margin-bottom: 1.5rem; }

/* Glassmorphism Background Animation */
.pte-glass-section { position: relative; overflow: hidden; background: #f8fafc; }
.pte-glass-section::before, .pte-glass-section::after { content: ''; position: absolute; border-radius: 50%; filter: blur(100px); z-index: 0; animation: floatBlobs 12s infinite ease-in-out alternate; }
.pte-glass-section::before { top: 5%; left: -5%; width: 500px; height: 500px; background: rgba(139, 92, 246, 0.2); }
.pte-glass-section::after { bottom: 5%; right: -5%; width: 600px; height: 600px; background: rgba(56, 189, 248, 0.15); animation-direction: alternate-reverse; }
@keyframes floatBlobs { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(50px, -50px) scale(1.1); } }

/* Modern Glassmorphic Cards */
.uni-card {
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border-radius: 20px;
  padding: 2.5rem 2rem;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
  border: 1px solid rgba(139, 92, 246, 0.1);
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  height: 100%;
  position: relative;
  overflow: hidden;
  z-index: 1;
}
.uni-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
  background: linear-gradient(135deg, rgba(139, 92, 246, 0.05), rgba(139, 92, 246, 0.15));
  z-index: 0; opacity: 0; transition: opacity 0.4s ease;
}
.uni-card::after {
  content: '学';
  position: absolute; bottom: -20px; right: -10px;
  font-size: 10rem; font-weight: 900;
  color: rgba(139, 92, 246, 0.05);
  z-index: 0; pointer-events: none;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  line-height: 1; font-family: "Noto Sans JP", "Yu Gothic", "Meiryo", sans-serif;
}
.uni-card:hover { 
  transform: translateY(-12px); 
  box-shadow: 0 30px 60px rgba(139, 92, 246, 0.15), inset 0 0 0 1px rgba(255, 255, 255, 0.9);
  border-color: rgba(139, 92, 246, 0.3);
}
.uni-card:hover::before { opacity: 1; }
.uni-card:hover::after { color: rgba(139, 92, 246, 0.12); transform: scale(1.1) rotate(-5deg); }

.uni-num, .uni-name, .uni-city, .uni-desc { position: relative; z-index: 2; }
.uni-num { 
  display: inline-flex; justify-content: center; align-items: center; 
  width: 48px; height: 48px; 
  background: linear-gradient(135deg, var(--pte-primary), #7c3aed); 
  color: white; 
  border-radius: 14px; 
  font-weight: 800; 
  margin-bottom: 1.5rem; 
  font-size: 1.2rem;
  box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3);
  transition: transform 0.4s ease;
}
.uni-card:hover .uni-num { transform: scale(1.1) rotate(5deg); }
.uni-name { font-size: 1.25rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem; }
.uni-city { font-size: 0.9rem; color: var(--pte-primary); font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;}
.uni-desc { color: var(--gray); font-size: 0.95rem; line-height: 1.6; }

.category-title { font-size: 2rem; font-weight: 800; text-align: center; margin: 4rem 0 2rem; color: var(--dark); position: relative; }
.category-title::after { content: ''; display: block; width: 60px; height: 4px; background: var(--pte-gradient); margin: 1rem auto 0; border-radius: 2px;}
</style>

<main>
  <section class="pte-hero">
    <div class="container">
      <span style="display: inline-block; padding: 0.5rem 1.5rem; background: white; color: var(--pte-primary); border-radius: 50px; font-weight: 700; margin-bottom: 1rem; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">Study in Japan</span>
      <h1 class="pte-hero-title">Top Universities <br><span class="pte-text-gradient">in Japan</span></h1>
      <p style="font-size: 1.1rem; color: var(--gray); max-width: 700px; margin: 0 auto;">Japan's top universities are consistently led by the former Imperial Universities alongside prestigious national institutes and top private institutions.</p>
    </div>
  </section>

  <section class="section pte-glass-section" style="padding-bottom: 6rem;">
    <div class="container" style="position: relative; z-index: 1;">
      
      <h2 class="category-title">Top National Universities <br><span style="font-size: 1.2rem; font-weight: 500; color: var(--gray);">(Former Imperial Universities & Specialized Institutes)</span></h2>
      
      <div class="grid grid--3 gap--2">
        <?php
        $national_unis = [
          ['The University of Tokyo', 'Tokyo', 'The flagship national university, consistently ranked #1 in Japan.'],
          ['Kyoto University', 'Kyoto', 'Renowned for world-class research and produce of Nobel laureates.'],
          ['Osaka University', 'Osaka', 'Leading comprehensive national university strength in medicine and engineering.'],
          ['Tohoku University', 'Sendai', 'A premier science and engineering powerhouse.'],
          ['Institute of Science Tokyo', 'Tokyo', 'Formed from the merger of Tokyo Institute of Technology and Tokyo Medical and Dental University.'],
          ['Nagoya University', 'Nagoya', 'Famous for physics, chemistry, and automotive engineering ties.'],
          ['Kyushu University', 'Fukuoka', 'Major research center in western Japan.'],
          ['Hokkaido University', 'Sapporo', 'Leading northern campus strong in agriculture and environmental sciences.'],
          ['University of Tsukuba', 'Ibaraki', 'Known for innovation, STEM programs, and internationalization.'],
          ['Kobe University', 'Kobe', 'Highly regarded for social sciences, business, and economics.'],
          ['Hiroshima University', 'Hiroshima', 'Notable national university for education and science.'],
          ['Chiba University', 'Chiba', 'Outstanding programs in design, medicine, and humanities.'],
          ['Okayama University', 'Okayama', 'Prominent public university with strong international research ties.'],
          ['Kanazawa University', 'Kanazawa', 'Key national university in the Hokuriku region.']
        ];
        
        $count = 1;
        foreach($national_unis as $uni):
        ?>
        <div class="uni-card animate-on-scroll">
          <div class="uni-num"><?= $count++ ?></div>
          <h3 class="uni-name"><?= $uni[0] ?></h3>
          <div class="uni-city"><i class="fa-solid fa-location-dot"></i> <?= $uni[1] ?></div>
          <p class="uni-desc"><?= $uni[2] ?></p>
        </div>
        <?php endforeach; ?>
      </div>

      <h2 class="category-title" style="margin-top: 6rem;">Top Private Universities</h2>
      
      <div class="grid grid--3 gap--2">
        <?php
        $private_unis = [
          ['Waseda University', 'Tokyo', 'Among the top private institutions, popular for international degrees and humanities.'],
          ['Keio University', 'Tokyo', 'Oldest private institution in Japan, highly prized for business and medicine.'],
          ['Sophia University', 'Tokyo', 'A pioneer in global education with extensive English-taught courses.'],
          ['Doshisha University', 'Kyoto', 'Prestigious private university located in central Kyoto.'],
          ['Ritsumeikan University', 'Kyoto', 'Comprehensive university with active global exchange programs.'],
          ['Meiji University', 'Tokyo', 'Leading Tokyo private university with strong law and business faculties.']
        ];
        
        foreach($private_unis as $uni):
        ?>
        <div class="uni-card animate-on-scroll">
          <div class="uni-num"><?= $count++ ?></div>
          <h3 class="uni-name"><?= $uni[0] ?></h3>
          <div class="uni-city"><i class="fa-solid fa-location-dot"></i> <?= $uni[1] ?></div>
          <p class="uni-desc"><?= $uni[2] ?></p>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </section>
  
  <section class="section" style="padding-top: 2rem;">
    <div class="container animate-on-scroll">
      <div style="background: var(--pte-gradient); padding: 4rem 2rem; border-radius: 30px; text-align: center; color: white; box-shadow: 0 20px 40px rgba(139, 92, 246, 0.2);">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif;">Ready to Apply?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">Get complete support for higher education, language school admissions, scholarship guidance, and student visa processing.</p>
        <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--pte-primary); font-weight: 700;">Book Admission Counseling</a>
      </div>
    </div>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>
