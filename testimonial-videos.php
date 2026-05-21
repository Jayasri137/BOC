<?php
// testimonial-videos.php - Video Reviews Front-End Page
require_once 'includes/config.php';
$pageTitle = 'Video Testimonials | Bluestone Overseas Consultants';
$pageDesc = 'Watch video reviews and success stories from students who achieved their study abroad goals with Bluestone.';
require_once 'includes/header.php';
?>

<main>
  <!-- BEAUTIFUL PAGE HERO -->
  <!-- DYNAMIC VIDEOS GRID SECTION -->
  <section class="section" style="background: #fff; padding-bottom: 5rem;">
    <div class="container">
      <div class="section__header text-center animate-on-scroll" style="margin-bottom: 3.5rem;">
        <span class="section__tag">Video Stories</span>
        <h2 class="section__title">Watch <span>Our Achievers</span></h2>
        <div class="accent-bar" style="margin: 1rem auto 0;"></div>
      </div>

      <div class="grid grid--3 gap--2">
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM testimonial_videos WHERE is_active = 1 ORDER BY id ASC");
            $videos = $stmt->fetchAll();
        } catch (PDOException $e) {
            $videos = [];
        }

        if (empty($videos)) {
            $videos = [
                ['student_name' => 'Sai Raksha Manoharan', 'details' => 'MSc in United Kingdom', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
                ['student_name' => 'Ashok Saravanan', 'details' => 'MBA in Canada', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
                ['student_name' => 'Priya Krishnamoorthy', 'details' => 'MS in United States', 'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ']
            ];
        }

        foreach ($videos as $v):
            $name = clean_output($v['student_name']);
            $details = clean_output($v['details']);
            $url = clean_output($v['youtube_url']);
        ?>
            <div class="animate-on-scroll" style="background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid rgba(15, 23, 42, 0.04); display: flex; flex-direction: column; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.05)';">
                <!-- Video Media Frame (Supports YouTube Embed & Local Video Files) -->
                <div style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%; background: #0f172a;">
                    <?php if (strpos($url, 'uploads/') === 0): ?>
                        <video src="<?= $url ?>" controls style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; object-fit: cover;"></video>
                    <?php else: ?>
                        <iframe src="<?= $url ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    <?php endif; ?>
                </div>
                
                <!-- Student Details info footer -->
                <div style="padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <h4 style="font-size: 1.15rem; font-weight: 700; margin: 0 0 0.5rem 0; color: var(--dark);"><?= $name ?></h4>
                        <p style="font-size: 0.85rem; color: var(--gray); margin: 0; display: flex; align-items: center; gap: 0.4rem;">
                            <i class="fa-solid fa-user-graduate" style="color: var(--primary);"></i>
                            <?= $details ?>
                        </p>
                    </div>
                    <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid rgba(15, 23, 42, 0.05); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.75rem; color: #10b981; font-weight: 600; display: flex; align-items: center; gap: 0.3rem;">
                            <i class="fa-solid fa-circle-check"></i> Verified Reviewer
                        </span>
                        <i class="fa-brands fa-youtube" style="color: #ef4444; font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php require_once 'includes/footer.php'; ?>
