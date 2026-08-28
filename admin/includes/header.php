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
    <link rel="stylesheet" href="css/admin-style.css?v=<?php echo time(); ?>">
    <script>
        // Toggle mobile sidebar
        function toggleSidebar() {
            document.querySelector('.app-sidebar').classList.toggle('active');
        }
    </script>
    <style>
        .menu-accordion { border-radius: var(--radius-sm); margin-bottom: 0.25rem; overflow: hidden; }
        .menu-accordion-header { display:flex; align-items:center; justify-content:space-between; gap:0.75rem; padding:0.7rem 1rem; color: var(--text-secondary); font-weight:500; font-size:0.88rem; cursor: pointer; transition: var(--transition); border-radius: var(--radius-sm); }
        .menu-accordion-header i:first-child { font-size:1rem; width:18px; text-align:center; }
        .menu-accordion-header:hover { background: var(--primary-light); color: var(--primary); }
        .menu-accordion.active .menu-accordion-header { background: linear-gradient(135deg, var(--primary), #dc2626); color:#fff; font-weight:600; box-shadow: 0 4px 10px var(--primary-glow); }
        .accordion-arrow { font-size: 0.75rem !important; transition: transform 0.3s; }
        .menu-accordion.open > .menu-accordion-header .accordion-arrow { transform: rotate(180deg); }
        .menu-accordion-body { display: none; padding-left: 0.5rem; border-left: 2px solid var(--border); margin-left: 1.5rem; margin-top: 0.25rem; }
        .menu-accordion.open > .menu-accordion-body { display: block; }
        .submenu-item { display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; color: var(--text-muted); font-size:0.8rem; border-radius: var(--radius-sm); text-decoration:none; margin-bottom: 0.25rem; transition: var(--transition); }
        .submenu-item:hover, .submenu-item.active { background: rgba(0,0,0,0.03); color: var(--primary); font-weight: 500; }
        .nested-accordion-header { display:flex; align-items:center; justify-content:space-between; padding:0.5rem 0.75rem; color: var(--text-secondary); font-size:0.85rem; cursor: pointer; border-radius: var(--radius-sm); font-weight: 500; }
        .nested-accordion-header:hover { background: rgba(0,0,0,0.03); color: var(--primary); }
        .nested-accordion.open > .nested-accordion-header .accordion-arrow { transform: rotate(180deg); }
        .nested-accordion-body { display: none; padding-left: 0.5rem; border-left: 1px dashed var(--border); margin-left: 0.75rem; }
        .nested-accordion.open > .nested-accordion-body { display: flex; flex-direction: column; }
    </style>
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

            <a href="site_popup.php" class="menu-item <?php echo $currentPage === 'site_popup.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-window-restore"></i>
                <span>Site Popup</span>
            </a>

            <a href="announcements.php" class="menu-item <?php echo $currentPage === 'announcements.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-bullhorn"></i>
                <span>Announcements</span>
            </a>

            <a href="services.php" class="menu-item <?php echo $currentPage === 'services.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Services Cards</span>
            </a>

            <a href="test_prep.php" class="menu-item <?php echo $currentPage === 'test_prep.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-book-open-reader"></i>
                <span>Test Preparation</span>
            </a>

            <a href="upcoming_batches.php" class="menu-item <?php echo $currentPage === 'upcoming_batches.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-calendar-alt"></i>
                <span>Upcoming Batches</span>
            </a>

            <a href="specialist_services.php" class="menu-item <?php echo $currentPage === 'specialist_services.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-briefcase"></i>
                <span>Specialist Services</span>
            </a>

            <?php
            // Fetch all countries for the sidebar
            $sidebarCountries = [];
            try {
                $stmtSidebar = $pdo->query("SELECT id, name FROM countries ORDER BY name ASC");
                $sidebarCountries = $stmtSidebar->fetchAll();
            } catch (PDOException $e) {}
            
            // Fetch all dynamic sections grouped by country
            $allSections = [];
            try {
                $stmtSec = $pdo->query("SELECT id, country_id, title FROM country_sections ORDER BY country_id, sort_order ASC");
                while ($row = $stmtSec->fetch()) {
                    $allSections[$row['country_id']][] = $row;
                }
            } catch (PDOException $e) {}
            
            $isCountriesPage = ($currentPage === 'countries.php');
            $isManageSectionsPage = ($currentPage === 'manage_sections.php');
            $isMenuOpen = ($isCountriesPage || $isManageSectionsPage) ? 'open active' : '';
            ?>
            <div class="menu-accordion <?php echo $isMenuOpen; ?>">
                <div class="menu-accordion-header" onclick="this.parentElement.classList.toggle('open')">
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <i class="fa-solid fa-earth-americas"></i>
                        <span>Study Destinations</span>
                    </div>
                    <i class="fa-solid fa-chevron-down accordion-arrow"></i>
                </div>
                <div class="menu-accordion-body">
                    <a href="countries.php" class="submenu-item <?php echo $isCountriesPage ? 'active' : ''; ?>" style="display: block; padding: 0.4rem 0.75rem; color: #64748b; text-decoration: none; font-size: 0.85rem; border-bottom: 1px dashed var(--border);">
                        <i class="fa-solid fa-list" style="font-size:0.8rem; margin-right:0.25rem;"></i> Manage All Countries
                    </a>
                    <?php foreach($sidebarCountries as $sc): 
                        $isScActive = (isset($_GET['country_id']) && $_GET['country_id'] == $sc['id']);
                        $cSections = isset($allSections[$sc['id']]) ? $allSections[$sc['id']] : [];
                    ?>
                        <div class="nested-accordion <?php echo $isScActive ? 'open' : ''; ?>">
                            <div class="nested-accordion-header" onclick="this.parentElement.classList.toggle('open')">
                                <span><?php echo htmlspecialchars($sc['name']); ?></span>
                                <i class="fa-solid fa-chevron-down accordion-arrow"></i>
                            </div>
                            <div class="nested-accordion-body">
                                <a href="manage_sections.php?country_id=<?php echo $sc['id']; ?>" class="submenu-item" style="display: block; padding: 0.4rem 0.75rem; color: var(--primary); text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                                    <i class="fa-solid fa-gear"></i> Manage Sections
                                </a>
                                <?php foreach($cSections as $sec): 
                                    $isSecActive = ($isScActive && isset($_GET['edit_id']) && $_GET['edit_id'] == $sec['id']);
                                ?>
                                    <a href="manage_sections.php?country_id=<?php echo $sc['id']; ?>&edit_id=<?php echo $sec['id']; ?>" class="submenu-item <?php echo $isSecActive ? 'active' : ''; ?>" style="display: block; padding: 0.4rem 0.75rem; color: <?php echo $isSecActive ? 'var(--primary)' : '#64748b'; ?>; text-decoration: none; font-size: 0.85rem;">
                                        <?php echo htmlspecialchars($sec['title']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

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

            <a href="news.php" class="menu-item <?php echo $currentPage === 'news.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-newspaper"></i>
                <span>Blog Posts</span>
            </a>
            
            <a href="testimonials.php" class="menu-item <?php echo $currentPage === 'testimonials.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-comments"></i>
                <span>Student Reviews</span>
            </a>

            <a href="testimonial_videos.php" class="menu-item <?php echo $currentPage === 'testimonial_videos.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-video"></i>
                <span>Video Testimonials</span>
            </a>

            <a href="team_members.php" class="menu-item <?php echo $currentPage === 'team_members.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i>
                <span>Team Members</span>
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
