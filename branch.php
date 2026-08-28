<?php
require_once 'includes/config.php';

$slug = $_GET['b'] ?? '';
$branch = null;
$all_branches = [];

try {
    // Fetch all active branches from database
    $stmt = $pdo->query("SELECT * FROM branches WHERE is_active = 1 ORDER BY id ASC");
    $all_branches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Find matching branch if slug is provided
    if (!empty($slug)) {
        foreach ($all_branches as $b) {
            if (strtolower(str_replace(' ', '-', $b['city'])) === strtolower($slug)) {
                $branch = [
                    'name' => $b['city'] . ' Branch' . (!empty($b['badge']) ? ' ' . $b['badge'] : ''),
                    'addr' => $b['address'],
                    'phone' => '+91 93428 99904', // Using default global phone since it's not in DB
                    'email' => 'info@bluestoneocs.com',
                    'icon' => $b['icon'],
                    'map_iframe' => isset($b['map_iframe']) ? $b['map_iframe'] : ''
                ];
                break;
            }
        }
    }
} catch (PDOException $e) {
    // Fallback if table doesn't exist or DB error
    if (empty($all_branches)) {
        $all_branches = [];
    }
}

if (!$branch) {
    $pageTitle = 'Our Branches | Bluestone Overseas Consultants';
} else {
    $pageTitle = $branch['name'] . ' | Bluestone Overseas Consultants';
}
$pageDesc = 'PG Programs Abroad | Study Abroad Blogs & Latest Updates Explore Bluestone Overseas branches and get expert guidance from New Zealand Education Consultants in Coimbatore for admissions, visas and study abroad support.';
$pageKeywords = 'UK Education Consultants in Coimbatore, Australia Education Consultants in Coimbatore, New Zealand Education Consultants in Coimbatore, UG Programs Abroad, PG Programs Abroad, Study Abroad Consultants in Coimbatore, IELTS Coaching in Coimbatore, IELTS classes in Coimbatore, Best IELTS Coaching in Coimbatore, IELTS Training in Coimbatore, German language course, Japanese language course, German language classes, Japanese language classes, German Language Course in Coimbatore, Japanese Language Course in Coimbatore, German Language Training Centre in Coimbatore, Japanese Language Training Centre in Coimbatore, Postgraduate study in UK, Postgraduate study in Australia, Postgraduate study in New Zealand, Undergraduate study in Australia, Undergraduate study in UK, Undergraduate study in New Zealand, Postgraduate Study in UK – Coimbatore, Postgraduate Study in Australia – Coimbatore, Undergraduate Study in UK – Coimbatore, Undergraduate Study in Australia – Coimbatore, Postgraduate Study in New Zealand – Coimbatore, Undergraduate Study in New Zealand – Coimbatore';
$pageHeroImage = 'assets/images/img4.png';
require_once 'includes/header.php';
?>
<main>

  <section class="section" style="background: #ffffff;">
    <div class="container">
      <?php if ($branch): ?>
        <div class="grid grid--2 gap--4">
          <div class="animate-on-scroll">
            <h2 class="section__title" style="text-align:left">Contact <span>Details</span></h2>
            <div class="contact-cards" style="margin-top:2rem">
              <div class="contact-card">
                <div style="width:40px;height:40px;font-size:1.5rem; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-location-dot"></i></div>
                <div><h4>Address</h4><p><?= $branch['addr'] ?></p></div>
              </div>
              <div class="contact-card">
                <div style="width:40px;height:40px;font-size:1.5rem; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-phone"></i></div>
                <div><h4>Phone</h4><a href="tel:<?= $branch['phone'] ?>"><?= $branch['phone'] ?></a></div>
              </div>
              <div class="contact-card">
                <div style="width:40px;height:40px;font-size:1.5rem; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-envelope"></i></div>
                <div><h4>Email</h4><a href="mailto:<?= $branch['email'] ?>"><?= $branch['email'] ?></a></div>
              </div>
            </div>
            <div style="margin-top:2.5rem">
              <a href="consultation.php" class="btn btn--primary btn--lg">Book an Appointment</a>
            </div>
          </div>
          <div class="animate-on-scroll delay-1">
            <?php if (!empty($branch['map_iframe'])): ?>
              <div class="branch-map-container" style="width:100%; height:400px; border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow);">
                <style>
                  .branch-map-container iframe {
                      width: 100% !important;
                      height: 100% !important;
                      border: 0;
                  }
                </style>
                <?= $branch['map_iframe'] ?>
              </div>
            <?php else: ?>
              <div style="width:100%; height:400px; background:#f1f5f9; border-radius:var(--radius-lg); display:grid; place-items:center; color:var(--gray)">
                <i class="fa-solid fa-map-location-dot" style="font-size:3rem"></i>
                <span>Google Maps Placeholder</span>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php else: ?>
        <!-- List all branches as cards -->
        <div class="branches-grid grid grid--3 gap--2">
          <?php 
          $icons = ['fa-building', 'fa-map-location-dot', 'fa-city', 'fa-store', 'fa-location-crosshairs', 'fa-building-columns', 'fa-mountain-city', 'fa-tree-city'];
          $palettes = [
            ['#0ea5e9', '#2563eb', 'rgba(37, 99, 235, 0.05)', '#2563eb', 'rgba(37, 99, 235, 0.2)'], // Blue
            ['#f43f5e', '#e11d48', 'rgba(225, 29, 72, 0.05)', '#e11d48', 'rgba(225, 29, 72, 0.2)'], // Rose
            ['#f59e0b', '#d97706', 'rgba(217, 119, 6, 0.05)', '#d97706', 'rgba(217, 119, 6, 0.2)'], // Amber
            ['#10b981', '#059669', 'rgba(5, 150, 105, 0.05)', '#059669', 'rgba(5, 150, 105, 0.2)'], // Emerald
            ['#8b5cf6', '#6d28d9', 'rgba(109, 40, 217, 0.05)', '#6d28d9', 'rgba(109, 40, 217, 0.2)'], // Purple
            ['#06b6d4', '#0891b2', 'rgba(8, 145, 178, 0.05)', '#0891b2', 'rgba(8, 145, 178, 0.2)'], // Cyan
            ['#f97316', '#ea580c', 'rgba(234, 88, 12, 0.05)', '#ea580c', 'rgba(234, 88, 12, 0.2)'], // Orange
            ['#14b8a6', '#0f766e', 'rgba(15, 118, 110, 0.05)', '#0f766e', 'rgba(15, 118, 110, 0.2)'], // Teal
          ];
          
          if (empty($all_branches)) {
              // Complete fallback array simulating DB structure
              $all_branches = [
                  ['city' => 'Coimbatore', 'icon' => 'fa-building', 'badge' => '(HQ)', 'address' => 'Renaissance Terrace, Coimbatore'],
                  ['city' => 'Chennai', 'icon' => 'fa-city', 'badge' => '', 'address' => 'Velachery Main Road, Chennai'],
                  ['city' => 'Salem', 'icon' => 'fa-store', 'badge' => '', 'address' => 'Salem Branch Address'],
                  ['city' => 'Erode', 'icon' => 'fa-location-crosshairs', 'badge' => '', 'address' => 'Erode Branch Address'],
                  ['city' => 'Namakkal', 'icon' => 'fa-building-columns', 'badge' => '', 'address' => 'Namakkal Branch Address'],
                  ['city' => 'Tirunelveli', 'icon' => 'fa-city', 'badge' => 'New', 'address' => '123 Main St, Tirunelveli']
              ];
          }

          foreach($all_branches as $index => $row): 
            $b = htmlspecialchars($row['city']);
            $db_icon = !empty($row['icon']) ? htmlspecialchars($row['icon']) : '';
            $icon = $db_icon ? $db_icon : $icons[$index % count($icons)];
            $address = !empty($row['address']) ? htmlspecialchars($row['address']) : 'Visit our '.$b.' branch for personalized study abroad counselling, admission guidance, and visa processing.';
            $c = $palettes[$index % count($palettes)];
          ?>
            <div class="branch-card animate-on-scroll delay-<?= $index % 4 ?>" style="background: var(--white); border-radius: 20px; padding: 2.5rem 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04); position: relative; overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); display: flex; flex-direction: column; gap: 1rem; align-items: flex-start; z-index: 1;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.03)';">
              <!-- Decorative background shape -->
              <div style="position: absolute; top: 0; right: 0; width: 120px; height: 120px; background: linear-gradient(135deg, <?= $c[0] ?>, <?= $c[1] ?>); opacity: 0.05; border-radius: 50%; transform: translate(30%, -30%); z-index: -1;"></div>
              
              <div style="width: 60px; height: 60px; border-radius: 16px; background: linear-gradient(135deg, <?= $c[0] ?>, <?= $c[1] ?>); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 0.5rem; box-shadow: 0 10px 20px <?= $c[4] ?>;">
                <i class="fa-solid <?= $icon ?>"></i>
              </div>
              
              <h4 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.4rem; font-weight: 800; color: var(--dark); margin: 0; letter-spacing: -0.02em;"><?= $b ?> Branch <?= !empty($row['badge']) ? '<span style="font-size:0.8rem; color:'.$c[1].';">'.$row['badge'].'</span>' : '' ?></h4>
              
              <p style="font-size: 0.95rem; color: var(--gray); margin: 0; line-height: 1.6;"><i class="fa-solid fa-location-dot" style="margin-right:0.4rem; color:<?= $c[1] ?>;"></i> <?= $address ?></p>
              
              <div style="display: flex; gap: 1rem; margin-top: auto; padding-top: 1.5rem; width: 100%;">
                <a href="tel:+919342899904" style="flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1rem; background: <?= $c[2] ?>; color: <?= $c[3] ?>; font-weight: 700; font-size: 0.9rem; border-radius: 10px; transition: all 0.3s ease; text-decoration: none;" onmouseover="this.style.background='<?= $c[3] ?>'; this.style.color='white';" onmouseout="this.style.background='<?= $c[2] ?>'; this.style.color='<?= $c[3] ?>';">
                  <i class="fa-solid fa-phone"></i> Call
                </a>
                <a href="branch.php?b=<?= strtolower(str_replace(' ', '-', $b)) ?>" style="flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; color: var(--dark); font-weight: 700; font-size: 0.9rem; border-radius: 10px; transition: all 0.3s ease; text-decoration: none;" onmouseover="this.style.borderColor='<?= $c[1] ?>'; this.style.color='<?= $c[1] ?>';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='var(--dark)';">
                  Details <i class="fa-solid fa-arrow-right" style="font-size: 0.8rem;"></i>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
