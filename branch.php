<?php
require_once 'includes/config.php';

$slug = $_GET['b'] ?? '';
$branches_data = [
    'coimbatore' => [
        'name' => 'Coimbatore (Head Office)',
        'addr' => 'Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College, Coimbatore, TN - 641018',
        'phone' => '+91 93428 99904',
        'email' => 'coimbatore@bluestoneocs.com',
        'map' => 'https://www.google.com/maps/embed?pb=...'
    ],
    'chennai' => [
        'name' => 'Chennai Branch',
        'addr' => 'No.13, Velachery Main Road, Mailai Balaji Nagar, Pallikaranai, Chennai - 600100',
        'phone' => '+91 93428 99904',
        'email' => 'chennai@bluestoneocs.com',
        'map' => 'https://www.google.com/maps/embed?pb=...'
    ],
    // Add more branches
];

$branch = $branches_data[$slug] ?? null;

if (!$branch) {
    } else {
    $pageTitle = $branch['name'] . ' | Bluestone Overseas Consultants';
}

$pageTitle = 'Bluestone Overseas Branches | Visit Our Offices';
$pageDesc = 'Locate the nearest Bluestone Overseas office for expert study abroad guidance.';
require_once 'includes/header.php';
?>
<main>
<div class="container" style="padding-top: 2rem; padding-bottom: 1rem;"><h1 class="section__title" style="text-align:center; margin:0; font-size: 2.2rem;">Our Branch Locations</h1></div>

  <section class="section">
    <div class="container">
      <?php if ($branch): ?>
        <div class="grid grid--2 gap--4">
          <div class="animate-on-scroll">
            <h2 class="section__title" style="text-align:left">Contact <span>Details</span></h2>
            <div class="contact-cards" style="margin-top:2rem">
              <div class="contact-card">
                <div class="stat-icon stat-icon--blue" style="width:40px;height:40px;font-size:1rem"><i class="fa-solid fa-location-dot"></i></div>
                <div><h4>Address</h4><p><?= $branch['addr'] ?></p></div>
              </div>
              <div class="contact-card">
                <div class="stat-icon stat-icon--purple" style="width:40px;height:40px;font-size:1rem"><i class="fa-solid fa-phone"></i></div>
                <div><h4>Phone</h4><a href="tel:<?= $branch['phone'] ?>"><?= $branch['phone'] ?></a></div>
              </div>
              <div class="contact-card">
                <div class="stat-icon stat-icon--pink" style="width:40px;height:40px;font-size:1rem"><i class="fa-solid fa-envelope"></i></div>
                <div><h4>Email</h4><a href="mailto:<?= $branch['email'] ?>"><?= $branch['email'] ?></a></div>
              </div>
            </div>
            <div style="margin-top:2.5rem">
              <a href="consultation.php" class="btn btn--primary btn--lg">Book an Appointment</a>
            </div>
          </div>
          <div class="animate-on-scroll delay-1">
            <div style="width:100%; height:400px; background:#f1f5f9; border-radius:var(--radius-lg); display:grid; place-items:center; color:var(--gray)">
              <i class="fa-solid fa-map-location-dot" style="font-size:3rem"></i>
              <span>Google Maps Placeholder</span>
            </div>
          </div>
        </div>
      <?php else: ?>
        <!-- List all branches as cards -->
        <div class="branches-grid grid grid--3 gap--2">
          <?php foreach(['Coimbatore','Chennai','Salem','Erode','Namakkal','Tirunelveli','Nepal','Canada'] as $b): ?>
            <div class="animate-on-scroll" style="background:#fff; border-radius:var(--radius-lg); padding:2rem; box-shadow:var(--shadow); border-top:4px solid var(--primary)">
              <h4 style="font-size:1.25rem; font-weight:800; margin-bottom:1rem"><?= $b ?></h4>
              <p style="font-size:.85rem; color:var(--gray); margin-bottom:1.5rem">Visit our <?= $b ?> branch for personalized study abroad counselling.</p>
              <a href="branch.php?b=<?= strtolower($b) ?>" class="btn btn--outline btn--sm">View Branch Details</a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
