<?php
require_once 'includes/config.php';
$pageTitle = 'Study Abroad Blog | Bluestone Overseas Consultants';
$pageDesc = 'Read latest articles, news, and guides on study abroad, visa rules, and international student life.';
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
