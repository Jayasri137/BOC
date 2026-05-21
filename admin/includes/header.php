<?php
// admin/includes/header.php - Main Layout Header & Sidebar for Admin Panel
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/auth.php';

// Secure the page
check_auth();

// Find the current active page
$currentPage = basename($_SERVER['PHP_SELF']);

// User Session info
$adminName = $_SESSION['admin_full_name'] ?? 'Administrator';
$adminUser = $_SESSION['admin_username'] ?? 'admin';
$adminEmail = $_SESSION['admin_email'] ?? 'admin@bluestoneocs.com';
$avatarColor = get_avatar_color($adminName);
$initials = '';
$nameParts = explode(' ', $adminName);
foreach ($nameParts as $part) {
    $initials .= substr($part, 0, 1);
}
$initials = strtoupper(substr($initials, 0, 2));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin Panel'; ?> | Bluestone Overseas</title>
    <!-- FontAwesome & Outfit Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Main Style -->
    <link rel="stylesheet" href="css/admin-style.css">
    <script>
        // Toggle mobile sidebar
        function toggleSidebar() {
            document.querySelector('.app-sidebar').classList.toggle('active');
        }
    </script>
</head>
<body>

<div class="app-container">
    <!-- Responsive Navigation Sidebar -->
    <aside class="app-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo-icon">
                <i class="fa-solid fa-plane-departure"></i>
            </div>
            <div class="sidebar-logo-text">
                Bluestone
                <span>Overseas Admin</span>
            </div>
            <div class="sidebar-toggle" style="margin-left: auto;" onclick="toggleSidebar()">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>
        
        <nav class="sidebar-menu">
            <div class="menu-label">Main Console</div>
            
            <a href="index.php" class="menu-item <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="leads.php" class="menu-item <?php echo $currentPage === 'leads.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-users-line"></i>
                <span>Leads CRM</span>
            </a>
            
            <div class="menu-label">Web Content Cards</div>
            
            <a href="hero_slides.php" class="menu-item <?php echo $currentPage === 'hero_slides.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-rectangle-ad"></i>
                <span>Hero Slides</span>
            </a>

            <a href="services.php" class="menu-item <?php echo $currentPage === 'services.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Services Cards</span>
            </a>

            <a href="test_prep.php" class="menu-item <?php echo $currentPage === 'test_prep.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-book-open-reader"></i>
                <span>Test Preparation</span>
            </a>

            <a href="specialist_services.php" class="menu-item <?php echo $currentPage === 'specialist_services.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-briefcase"></i>
                <span>Specialist Services</span>
            </a>

            <a href="countries.php" class="menu-item <?php echo $currentPage === 'countries.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-earth-americas"></i>
                <span>Study Destinations</span>
            </a>

            <a href="universities.php" class="menu-item <?php echo $currentPage === 'universities.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-building-columns"></i>
                <span>Universities & Courses</span>
            </a>
            
            <a href="essential_partners.php" class="menu-item <?php echo $currentPage === 'essential_partners.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-handshake"></i>
                <span>Essential Partners</span>
            </a>

            <a href="branches.php" class="menu-item <?php echo $currentPage === 'branches.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-map-location-dot"></i>
                <span>Office Branches</span>
            </a>

            <a href="gallery.php" class="menu-item <?php echo $currentPage === 'gallery.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-camera-retro"></i>
                <span>Photo Gallery</span>
            </a>

            <a href="events.php" class="menu-item <?php echo $currentPage === 'events.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Events & Fairs</span>
            </a>

            <a href="news.php" class="menu-item <?php echo $currentPage === 'news.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-newspaper"></i>
                <span>News & Articles</span>
            </a>
            
            <a href="testimonials.php" class="menu-item <?php echo $currentPage === 'testimonials.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-comments"></i>
                <span>Student Reviews</span>
            </a>

            <a href="testimonial_videos.php" class="menu-item <?php echo $currentPage === 'testimonial_videos.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-video"></i>
                <span>Video Testimonials</span>
            </a>

            <div class="menu-label">System Control</div>
            
            <a href="profile.php" class="menu-item <?php echo $currentPage === 'profile.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-gear"></i>
                <span>My Profile</span>
            </a>
            
            <a href="setup.php" class="menu-item" target="_blank" style="color: var(--text-muted);">
                <i class="fa-solid fa-gears"></i>
                <span>Database setup</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-profile-badge">
                <div class="user-avatar user-avatar--<?php echo $avatarColor; ?>">
                    <?php echo $initials; ?>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo clean_output($adminName); ?></div>
                    <div class="user-role">Super Admin</div>
                </div>
                <a href="logout.php" class="logout-btn" title="Sign Out">
                    <i class="fa-solid fa-power-off"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Panel -->
    <main class="app-content">
        <!-- Top Navigation Utility Bar (Responsive) -->
        <div class="content-header">
            <div>
                <button class="sidebar-toggle btn-action" onclick="toggleSidebar()" style="display: block; margin-right: 1rem; width: 40px; height: 40px; border-radius: 50%;">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
            <div class="header-actions">
                <a href="../index.php" target="_blank" class="btn-outline">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>Visit Live Website</span>
                </a>
            </div>
        </div>
