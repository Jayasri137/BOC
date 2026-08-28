<?php
require_once 'includes/config.php';
$pageTitle = 'PG Programs Abroad | Study Abroad Blogs & Latest Updates';
$pageDesc = 'Explore expert tips, university updates, admission guidance and career advice for students planning Postgraduate study in New Zealand, with the latest study abroad insights.';
$pageKeywords = 'UK Education Consultants in Coimbatore, Australia Education Consultants in Coimbatore, New Zealand Education Consultants in Coimbatore, UG Programs Abroad, PG Programs Abroad, Study Abroad Consultants in Coimbatore, IELTS Coaching in Coimbatore, IELTS classes in Coimbatore, Best IELTS Coaching in Coimbatore, IELTS Training in Coimbatore, German language course, Japanese language course, German language classes, Japanese language classes, German Language Course in Coimbatore, Japanese Language Course in Coimbatore, German Language Training Centre in Coimbatore, Japanese Language Training Centre in Coimbatore, Postgraduate study in UK, Postgraduate study in Australia, Postgraduate study in New Zealand, Undergraduate study in Australia, Undergraduate study in UK, Undergraduate study in New Zealand, Postgraduate Study in UK – Coimbatore, Postgraduate Study in Australia – Coimbatore, Undergraduate Study in UK – Coimbatore, Undergraduate Study in Australia – Coimbatore, Postgraduate Study in New Zealand – Coimbatore, Undergraduate Study in New Zealand – Coimbatore';
require_once 'includes/header.php';
?>
<main>
  <section class="section">
    <div class="container">
      <div class="blog-grid grid grid--3 gap--2">
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM news_articles WHERE is_active = 1 ORDER BY id DESC");
            $db_blogs = $stmt->fetchAll();
        } catch (PDOException $e) {
            $db_blogs = [];
        }
        
        $db_blogs = array_values(array_filter($db_blogs, function ($art) {
            return !is_hidden_news_article($art);
        }));

        if (empty($db_blogs)) {
            $db_blogs = [
                ['title' => 'Top 5 Scholarships for Indian Students in UK', 'date_string' => 'November 2024', 'tag' => 'Scholarships', 'excerpt' => 'Explore the best funding options for your master\'s degree in the UK...', 'link' => 'blog-details.php?id=9', 'emoji' => '🇬🇧']
            ];
        }
        
        foreach ($db_blogs as $art):
            $title = clean_output($art['title']);
            $date = clean_output($art['date_string']);
            $tag = clean_output($art['tag']);
            $excerpt = clean_output($art['excerpt']);
            $link = clean_output($art['link']);
            $emoji = clean_output($art['emoji']);
        ?>
        <div class="blog-card animate-on-scroll">
          <div class="blog-card__img" style="position:relative; height:200px; display:flex; align-items:center; justify-content:center; background:#f1f5f9; overflow:hidden; border-radius: 12px 12px 0 0;">
            <?php if (!empty($art['image_path'])): ?>
              <img src="<?= clean_output($art['image_path']) ?>" alt="<?= $title ?>" style="width:100%; height:100%; object-fit:cover;">
            <?php else: ?>
              <span style="font-size:4rem"><?= $emoji ?></span>
            <?php endif; ?>
            <span class="blog-card__tag" style="z-index:2;"><?= $tag ?></span>
          </div>
          <div class="blog-card__body">
            <div class="blog-card__meta"><span><i class="fa-regular fa-calendar"></i> <?= $date ?></span></div>
            <h3><a href="<?= $link ?>"><?= $title ?></a></h3>
            <p style="font-size:.85rem;color:var(--gray);line-height:1.65;margin-bottom:1rem"><?= $excerpt ?></p>
            <a href="<?= $link ?>" class="blog-card__link">Read More <i class="fa-solid fa-arrow-right"></i></a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
