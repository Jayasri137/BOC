<?php
$pageTitle = 'Our Team | Bluestone Overseas Consultants';
$pageDesc = 'Meet the experienced professionals dedicated to making your global education dreams a reality.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- TEAM MEMBERS SECTION -->
<section id="team" class="section team-section bg-light" style="padding: 5rem 1rem; background: #f8fafc; position: relative;">
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
            height: 260px; 
            transform: translateY(100%);
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 3;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
          }

          .wave-card:hover .wave-card__bottom {
            transform: translateY(0);
          }

          .wave-card__info {
            position: absolute;
            top: -110px; /* Sits above the wave */
            left: 0;
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

          .wave-svg {
            position: absolute;
            top: -45px;
            left: 0;
            width: 100%;
            height: 50px;
            display: block;
            pointer-events: none;
          }

          .wave-card__content {
            position: relative;
            padding: 0 1.5rem 1.5rem;
            color: white;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            overflow-y: auto;
            scrollbar-width: none; /* Firefox */
          }
          
          .wave-card__content::-webkit-scrollbar {
            display: none; /* Chrome/Safari */
          }

          .wave-card__desc {
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 0;
            padding-top: 1rem;
          }

          /* Gradients for cards */
          .bg-grad-0 { background: linear-gradient(135deg, #a78bfa 0%, #c084fc 100%); }
          .bg-grad-0 .wave-svg { color: #a78bfa; }
          
          .bg-grad-1 { background: linear-gradient(135deg, #fb923c 0%, #f97316 100%); }
          .bg-grad-1 .wave-svg { color: #fb923c; }
          
          .bg-grad-2 { background: linear-gradient(135deg, #2dd4bf 0%, #14b8a6 100%); }
          .bg-grad-2 .wave-svg { color: #2dd4bf; }
          
          .bg-grad-3 { background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%); }
          .bg-grad-3 .wave-svg { color: #60a5fa; }

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

          <div class="wave-card__bottom <?= $gradClass ?>">
            <!-- Name and Role pinned above the wave -->
            <div class="wave-card__info">
              <h3 class="wave-card__title"><?= clean_output($member['name']) ?></h3>
              <p class="wave-card__role-top"><?= clean_output($member['role']) ?></p>
            </div>

            <!-- SVG Wave shape -->
            <svg class="wave-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
              <path fill="currentColor" fill-opacity="1" d="M0,128L48,144C96,160,192,192,288,181.3C384,171,480,117,576,117.3C672,117,768,171,864,192C960,213,1056,203,1152,176C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
            
            <div class="wave-card__content">
              <p class="wave-card__desc"><?= nl2br(clean_output($member['description'])) ?></p>
            </div>
          </div>
          
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const teamSlider = document.getElementById('teamSlider');
    const teamPrev = document.getElementById('teamPrev');
    const teamNext = document.getElementById('teamNext');

    if(teamSlider && teamPrev && teamNext) {
      teamPrev.addEventListener('click', () => {
        const cardWidth = teamSlider.querySelector('.wave-card').offsetWidth + 32; // width + gap
        teamSlider.scrollBy({ left: -cardWidth, behavior: 'smooth' });
      });

      teamNext.addEventListener('click', () => {
        const cardWidth = teamSlider.querySelector('.wave-card').offsetWidth + 32; // width + gap
        teamSlider.scrollBy({ left: cardWidth, behavior: 'smooth' });
      });
    }
  });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
