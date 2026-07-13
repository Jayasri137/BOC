<?php
require_once 'includes/config.php';
$pageTitle = 'Bluestone Overseas Gallery | Student Success Stories';
$pageDesc = 'Explore photos, student success stories, events, and overseas education milestones.';
require_once 'includes/header.php';
?>
<main>
<div class="container" style="padding-top: 2rem; padding-bottom: 1rem;"><h1 class="section__title" style="text-align:center; margin:0; font-size: 2.2rem;">Gallery & Student Achievements</h1></div>

  <section class="section">
    <div class="container">
      <div class="gallery-filters animate-on-scroll">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="Events">Events</button>
        <button class="filter-btn" data-filter="Training">Training</button>
        <button class="filter-btn" data-filter="Workshops">Workshops</button>
        <button class="filter-btn" data-filter="Success">Success</button>
      </div>

      <div class="masonry-gallery" id="galleryContainer">
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM gallery_items WHERE is_active = 1 ORDER BY id DESC");
            $items = $stmt->fetchAll();
        } catch (PDOException $e) {
            $items = [];
        }
        
        if (empty($items)) {
            $items = [
                ['image_path' => 'assets/images/md-gallery5.png', 'title' => 'Student Seminar Event', 'category' => 'Events'],
                ['image_path' => 'assets/images/ias5.png', 'title' => 'IELTS Coaching Session', 'category' => 'Training'],
                ['image_path' => 'assets/images/start.png', 'title' => 'Pre-Departure Briefing', 'category' => 'Workshops'],
                ['image_path' => 'assets/images/img1.png', 'title' => 'Visa Success Meet', 'category' => 'Success'],
                ['image_path' => 'assets/images/placement.jpeg', 'title' => 'Placement Seminar', 'category' => 'Events'],
                ['image_path' => 'assets/images/img4.png', 'title' => 'Education Fair 2026', 'category' => 'Events']
            ];
        }
        
        foreach($items as $item):
            $img = clean_output($item['image_path']);
            $title = clean_output($item['title']);
            $cat = clean_output($item['category'] ?? 'Gallery');
        ?>
        <div class="masonry-item animate-on-scroll" data-category="<?= $cat ?>">
          <img src="<?= $img ?>" alt="<?= $title ?>">
          <div class="masonry-overlay">
            <div class="masonry-info">
              <span class="masonry-cat"><?= $cat ?></span>
              <h4 class="masonry-title"><?= $title ?></h4>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <script>
      document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          // Update active button
          document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');

          const filter = btn.getAttribute('data-filter');
          const items = document.querySelectorAll('.masonry-item');

          items.forEach(item => {
            if (filter === 'all' || item.getAttribute('data-category') === filter) {
              item.style.display = 'block';
              setTimeout(() => item.style.opacity = '1', 10);
            } else {
              item.style.opacity = '0';
              setTimeout(() => item.style.display = 'none', 300);
            }
          });
        });
      });
    </script>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
