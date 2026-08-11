<?php
require_once 'includes/config.php';
$pageTitle = 'Study Abroad Events, Seminars & Webinars | Bluestone Overseas';
$pageDesc = 'Join our events and seminars to connect with universities and study abroad experts.';
require_once 'includes/header.php';
?>
<main>

  <section class="section">
    <div class="container">
      <div class="section__header animate-on-scroll">
        <span class="section__tag">Upcoming</span>
        <h2 class="section__title">Join Our <span>Next Event</span></h2>
      </div>
      <div class="events-grid grid grid--2 gap--2">
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM events WHERE is_active = 1 ORDER BY id ASC");
            $db_events = $stmt->fetchAll();
        } catch (PDOException $e) {
            $db_events = [];
        }
        
        if (empty($db_events)) {
            $db_events = [
                ['title' => 'UK Education Fair 2025', 'date_string' => 'May 15, 2025', 'location' => 'Coimbatore Office', 'description' => 'Meet representatives from 20+ top UK universities. Spot assessment and scholarship guidance.']
            ];
        }
        
        $colors = ['blue', 'purple', 'orange', 'teal', 'pink', 'gold'];
        foreach ($db_events as $index => $ev):
            $title = clean_output($ev['title']);
            $date = clean_output($ev['date_string']);
            $loc = clean_output($ev['location']);
            $desc = clean_output($ev['description']);
            
            // split date safely (e.g. "May 15, 2025")
            $date_parts = explode(' ', $date);
            $month = isset($date_parts[0]) ? $date_parts[0] : 'Event';
            $day = isset($date_parts[1]) ? trim($date_parts[1], ',') : 'Day';
        ?>
        <div class="event-card animate-on-scroll" style="flex-direction: column; display: flex; overflow: hidden; border-radius: 16px; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);">
          <?php if (!empty($ev['image_path'])): ?>
            <div style="height: 200px; width: 100%; overflow: hidden; position: relative;">
              <img src="<?= clean_output($ev['image_path']) ?>" alt="<?= $title ?>" style="width: 100%; height: 100%; object-fit: cover;">
              <div class="event-date" style="position: absolute; top: 1rem; right: 1rem; background: linear-gradient(135deg, <?= ['#0ea5e9, #38bdf8', '#8b5cf6, #a78bfa', '#f97316, #fb923c', '#14b8a6, #2dd4bf', '#ec4899, #f472b6', '#f59e0b, #fbbf24'][$index % 6] ?>); z-index: 2; margin: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"><span><?= $day ?></span><small><?= $month ?></small></div>
            </div>
          <?php endif; ?>
          <div style="display: flex; gap: 1.5rem; padding: 2rem; position: relative; width: 100%; flex-grow: 1;">
            <?php if (empty($ev['image_path'])): ?>
              <div class="event-date" style="flex-shrink: 0; background: linear-gradient(135deg, <?= ['#0ea5e9, #38bdf8', '#8b5cf6, #a78bfa', '#f97316, #fb923c', '#14b8a6, #2dd4bf', '#ec4899, #f472b6', '#f59e0b, #fbbf24'][$index % 6] ?>);"><span><?= $day ?></span><small><?= $month ?></small></div>
            <?php endif; ?>
            <div class="event-content" style="flex-grow: 1;">
              <h3 style="margin-top: 0; font-size: 1.25rem; font-weight: 700; color: var(--dark);"><?= $title ?></h3>
              <p class="event-meta" style="margin-top: 0.35rem; display: flex; align-items: center; gap: 0.4rem; color: var(--primary); font-weight: 500;"><i class="fa-solid fa-location-dot"></i> <?= $loc ?></p>
              <p style="color: var(--gray); font-size: 0.9rem; line-height: 1.6; margin: 0.75rem 0 1.5rem;"><?= $desc ?></p>
              <a href="consultation.php" class="btn btn--primary btn--sm" style="display: inline-flex; align-items: center; width: max-content;">Register Now</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
