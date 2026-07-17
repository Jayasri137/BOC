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
                <?php
                $is_local = (strpos($url, 'uploads/') === 0);
                ?>
                <!-- Video Media Frame (Actual video behind clickable overlay) -->
                <div style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%; background: #0f172a; cursor: pointer; overflow: hidden;" onclick="openVideoModal('<?= $url ?>', <?= $is_local ? 'true' : 'false' ?>)" onmouseover="this.querySelector('.play-overlay').style.opacity='1';" onmouseout="this.querySelector('.play-overlay').style.opacity='0';">
                    
                    <div style="pointer-events: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                    <?php if ($is_local): ?>
                        <video src="<?= $url ?>" style="width: 100%; height: 100%; border: none; object-fit: cover;"></video>
                    <?php else: ?>
                        <iframe src="<?= $url ?>" style="width: 100%; height: 100%; border: none;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    <?php endif; ?>
                    </div>

                    <div class="play-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
                        <div class="play-btn" style="width: 64px; height: 64px; background: rgba(239, 68, 68, 0.9); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);">
                            <i class="fa-solid fa-play" style="margin-left: 4px;"></i>
                        </div>
                    </div>
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

<!-- Video Modal -->
<div id="videoModal" style="display: none; position: fixed; z-index: 99999; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(5px); align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;" onclick="closeVideoModal()">
  <div style="position: relative; width: 90%; max-width: 900px; background: #000; border-radius: 12px; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.5); transform: scale(0.95); transition: transform 0.3s ease;" id="videoModalContent" onclick="event.stopPropagation()">
    <button onclick="closeVideoModal()" style="position: absolute; top: -40px; right: 0; z-index: 10; background: transparent; color: #fff; border: none; font-size: 2rem; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#fff'"><i class="fa-solid fa-xmark"></i></button>
    <div id="modalVideoContainer" style="position: relative; width: 100%; padding-bottom: 56.25%;">
      <!-- video or iframe will be injected here -->
    </div>
  </div>
</div>

<script>
function openVideoModal(url, isLocal) {
    const container = document.getElementById('modalVideoContainer');
    if (isLocal) {
        container.innerHTML = `<video src="${url}" controls autoplay style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; object-fit: contain;"></video>`;
    } else {
        // Append autoplay if not present
        const sep = url.includes('?') ? '&' : '?';
        const finalUrl = url.includes('autoplay=1') ? url : url + sep + 'autoplay=1';
        container.innerHTML = `<iframe src="${finalUrl}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" allow="autoplay; fullscreen; encrypted-media" allowfullscreen></iframe>`;
    }
    const modal = document.getElementById('videoModal');
    const content = document.getElementById('videoModalContent');
    modal.style.display = 'flex';
    // Trigger reflow
    void modal.offsetWidth;
    modal.style.opacity = '1';
    content.style.transform = 'scale(1)';
}

function closeVideoModal() {
    const modal = document.getElementById('videoModal');
    const content = document.getElementById('videoModalContent');
    modal.style.opacity = '0';
    content.style.transform = 'scale(0.95)';
    setTimeout(() => {
        modal.style.display = 'none';
        document.getElementById('modalVideoContainer').innerHTML = '';
    }, 300);
}
</script>

<?php require_once 'includes/footer.php'; ?>
