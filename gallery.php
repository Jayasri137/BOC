<?php
require_once 'includes/config.php';
$pageTitle = 'Bluestone Overseas Gallery | Student Success Stories';
$pageDesc = 'Explore photos, student success stories, events, and overseas education milestones.';
require_once 'includes/header.php';
?>
<main>

  <section class="section" style="background:#ffffff">
    <div class="container">

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
        
        $categories = [];
        foreach($items as $item) {
            $cat = trim($item['category'] ?? '');
            if (!empty($cat) && !in_array($cat, $categories)) {
                $categories[] = $cat;
            }
        }
        sort($categories);
      ?>

      <div class="gallery-filters animate-on-scroll">
        <button class="filter-btn active" data-filter="all">All</button>
        <?php foreach($categories as $cat): ?>
          <button class="filter-btn" data-filter="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></button>
        <?php endforeach; ?>
      </div>

      <div id="galleryContainer" class="simple-grid-gallery animate-on-scroll delay-1">
        <?php
        foreach($items as $item):
            $img = clean_output($item['image_path']);
            $title = clean_output($item['title'] ?? '');
            $itemCat = clean_output($item['category'] ?? '');
        ?>
        <div class="grid-item" data-category="<?= htmlspecialchars($itemCat) ?>">
          <img src="<?= $img ?>" alt="<?= $title ?>">
        </div>
        <?php endforeach; ?>
      </div>


  </section>

  <!-- Lightbox Implementation -->
  <style>
    .lightbox-modal {
      display: none;
      position: fixed;
      z-index: 99999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.9);
      align-items: center;
      justify-content: center;
    }
    .lightbox-modal.active {
      display: flex;
    }
    .lightbox-content {
      max-width: 90%;
      max-height: 90vh;
      object-fit: contain;
      border-radius: 8px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    }
    .lightbox-close {
      position: absolute;
      top: 20px;
      right: 30px;
      color: #fff;
      font-size: 40px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }
    .lightbox-close:hover {
      color: #bbb;
    }
    
    /* Filter Tabs */
    .gallery-filters {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 0.8rem;
      margin-bottom: 2.5rem;
    }
    .filter-btn {
      padding: 0.6rem 1.5rem;
      border: 2px solid #e2e8f0;
      background: transparent;
      border-radius: 50px;
      font-weight: 600;
      color: #64748b;
      cursor: pointer;
      transition: all 0.3s ease;
      font-family: inherit;
    }
    .filter-btn:hover, .filter-btn.active {
      background: var(--primary);
      border-color: var(--primary);
      color: white;
      box-shadow: 0 4px 15px rgba(236, 72, 153, 0.2);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }

    .simple-grid-gallery {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
      margin-bottom: 3rem;
    }
    .grid-item {
      border-radius: 12px;
      overflow: hidden;
      aspect-ratio: 1; /* Makes them square */
      background: #f1f5f9;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .grid-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      cursor: pointer;
      transition: transform 0.3s ease, filter 0.3s ease;
    }
    .grid-item img:hover {
      transform: scale(1.05);
      filter: brightness(1.1);
    }
    @media (max-width: 1024px) {
      .simple-grid-gallery { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
      .simple-grid-gallery { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
      .simple-grid-gallery { grid-template-columns: 1fr; }
    }
  </style>

  <div class="lightbox-modal" id="lightbox">
    <span class="lightbox-close" id="lightboxClose">&times;</span>
    <img class="lightbox-content" id="lightboxImg">
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const lightbox = document.getElementById('lightbox');
      const lightboxImg = document.getElementById('lightboxImg');
      const lightboxClose = document.getElementById('lightboxClose');

      document.querySelectorAll('.grid-item img').forEach(img => {
        img.addEventListener('click', function() {
          lightbox.classList.add('active');
          lightboxImg.src = this.src;
        });
      });

      lightboxClose.addEventListener('click', function() {
        lightbox.classList.remove('active');
      });

      lightbox.addEventListener('click', function(e) {
        if (e.target !== lightboxImg) {
          lightbox.classList.remove('active');
        }
      });

      // Gallery Filtering
      const filterBtns = document.querySelectorAll('.filter-btn');
      const gridItems = document.querySelectorAll('.grid-item');

      filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          filterBtns.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');

          const filterValue = btn.getAttribute('data-filter');

          gridItems.forEach(item => {
            if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
              item.style.display = 'block';
              item.style.animation = 'fadeIn 0.5s ease forwards';
            } else {
              item.style.display = 'none';
            }
          });
        });
      });
    });
  </script>
  <!-- End Lightbox -->
</main>
<?php require_once 'includes/footer.php'; ?>
