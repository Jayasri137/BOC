<?php
require_once 'includes/config.php';
$pageTitle = 'Awards & Achievements | Bluestone Overseas Consultants';
$pageDesc = 'Explore the recognition and milestones of Bluestone Overseas Consultants in the field of global education.';
require_once 'includes/header.php';
?>
<main>
  <section class="section">
    <div class="container">
      <div class="grid grid--3 gap--2">
        <?php
        $awards = [
          ['fa-award', 'Best Overseas Education Consultant 2023', 'Awarded for excellence in student placement and visa success rates.'],
          ['fa-certificate', 'Top Recruitment Partner - UK Universities', 'Recognized as a premier partner for leading UK institutions.'],
          ['fa-star', 'Excellent Support Award', 'Awarded for providing exceptional post-landing support to students in Canada.'],
          ['fa-handshake', 'Global Partnership Excellence', 'For fostering strong relationships with 500+ universities worldwide.'],
          ['fa-trophy', 'Innovation in Counselling', 'Recognized for our personalized and tech-driven approach to student guidance.'],
          ['fa-medal', 'Community Impact Award', 'For making global education accessible across regional Tamil Nadu.'],
        ];
        $colors = ['blue', 'purple', 'orange', 'teal', 'pink', 'gold'];
        foreach ($awards as $index => [$icon, $title, $desc]):
          $color = $colors[$index % 6];
        ?>
        <div class="award-card animate-on-scroll">
          <div class="icon-colorful icon-colorful--<?= $color ?>" style="margin-bottom: 1.5rem;"><i class="fa-solid <?= $icon ?>"></i></div>
          <h3><?= $title ?></h3>
          <p><?= $desc ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
