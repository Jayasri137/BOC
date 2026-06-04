<?php
// admin/index.php - Analytical Dashboard for Bluestone Overseas Consultants
$pageTitle = 'Dashboard';
require_once 'includes/header.php'; // handles authentication and pdo loading

$setupNeeded = false;
$stats = [
    'total_leads' => 0,
    'new_leads' => 0,
    'services_active' => 0,
    'services_total' => 0,
    'countries_active' => 0,
    'countries_total' => 0,
    'testimonials_active' => 0,
    'testimonials_total' => 0
];
$recentLeads = [];

try {
    // Check if tables exist by querying them. If they fail, trigger setup notice.
    // 1. Leads counts
    $stats['total_leads'] = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
    $stats['new_leads'] = $pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'New'")->fetchColumn();
    
    // 2. Services counts
    $stats['services_total'] = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
    $stats['services_active'] = $pdo->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn();
    
    // 3. Countries counts
    $stats['countries_total'] = $pdo->query("SELECT COUNT(*) FROM countries")->fetchColumn();
    $stats['countries_active'] = $pdo->query("SELECT COUNT(*) FROM countries WHERE is_active = 1")->fetchColumn();
    
    // 4. Testimonials counts
    $stats['testimonials_total'] = $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
    $stats['testimonials_active'] = $pdo->query("SELECT COUNT(*) FROM testimonials WHERE is_active = 1")->fetchColumn();
    
    // 5. Fetch 6 most recent leads
    $stmt = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC, id DESC LIMIT 6");
    $recentLeads = $stmt->fetchAll();
    
} catch (PDOException $e) {
    // If table doesn't exist, we must guide them to run the setup script!
    $setupNeeded = true;
}
?>

<h1 class="page-title">
    Dashboard
    <span>Analytical Overview of Bluestone Overseas CRM & content metrics</span>
</h1>

<?php if ($setupNeeded): ?>
    <div class="panel-card" style="margin-top: 2rem; border-color: var(--warning); text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 4rem; color: var(--warning); margin-bottom: 1.5rem;">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h2 style="font-size: 1.75rem; margin-bottom: 1rem;">Database Setup Required</h2>
        <p style="color: var(--text-secondary); max-width: 500px; margin: 0 auto 2rem; line-height: 1.6;">
            It looks like some of the required administrative tables (such as <code>services</code>, <code>countries</code>, or <code>testimonials</code>) are missing from your database.
        </p>
        <a href="setup.php" class="btn-pill" style="padding: 1rem 2rem; font-size: 1rem;">
            <i class="fa-solid fa-screwdriver-wrench"></i>
            <span>Run One-Click Setup & Seeding</span>
        </a>
    </div>
<?php else: ?>

    <!-- KPI Dashboard Grid -->
    <div class="stats-grid">
        <a href="leads.php" class="stat-card" style="text-decoration: none;">
            <div class="stat-info">
                <div class="stat-label">Total Enquiries</div>
                <div class="stat-value"><?php echo number_format($stats['total_leads']); ?></div>
            </div>
            <div class="stat-icon-wrap icon-blue">
                <i class="fa-solid fa-users"></i>
            </div>
        </a>
        
        <a href="leads.php?status=New" class="stat-card" style="text-decoration: none;">
            <div class="stat-info">
                <div class="stat-label">New Leads</div>
                <div class="stat-value" style="color: var(--accent);"><?php echo number_format($stats['new_leads']); ?></div>
            </div>
            <div class="stat-icon-wrap icon-purple">
                <i class="fa-solid fa-user-plus"></i>
            </div>
        </a>
        
        <a href="services.php" class="stat-card" style="text-decoration: none;">
            <div class="stat-info">
                <div class="stat-label">Active Services</div>
                <div class="stat-value"><?php echo $stats['services_active']; ?> <span style="font-size: 1rem; color: var(--text-muted); font-weight: 400;">/ <?php echo $stats['services_total']; ?></span></div>
            </div>
            <div class="stat-icon-wrap icon-orange">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
        </a>
        
        <a href="countries.php" class="stat-card" style="text-decoration: none;">
            <div class="stat-info">
                <div class="stat-label">Destinations</div>
                <div class="stat-value"><?php echo $stats['countries_active']; ?> <span style="font-size: 1rem; color: var(--text-muted); font-weight: 400;">/ <?php echo $stats['countries_total']; ?></span></div>
            </div>
            <div class="stat-icon-wrap icon-teal">
                <i class="fa-solid fa-earth-americas"></i>
            </div>
        </a>

        <a href="testimonials.php" class="stat-card" style="text-decoration: none;">
            <div class="stat-info">
                <div class="stat-label">Reviews</div>
                <div class="stat-value"><?php echo $stats['testimonials_active']; ?> <span style="font-size: 1rem; color: var(--text-muted); font-weight: 400;">/ <?php echo $stats['testimonials_total']; ?></span></div>
            </div>
            <div class="stat-icon-wrap icon-pink">
                <i class="fa-solid fa-star"></i>
            </div>
        </a>
    </div>

    <!-- Main Dashboard Section -->
    <div class="panel-grid">
        <!-- Recent Enquiries -->
        <div class="panel-card">
            <div class="panel-header">
                <h3 class="panel-title">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Recent Student Enquiries</span>
                </h3>
                <a href="leads.php" class="btn-outline" style="padding: 0.35rem 1rem; font-size: 0.78rem;">
                    <span>View CRM Sheet</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="data-table-wrapper">
                <?php if (empty($recentLeads)): ?>
                    <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        <i class="fa-regular fa-folder-open" style="font-size: 2.5rem; margin-bottom: 1rem; display: block; color: var(--text-muted);"></i>
                        No student leads found in your database.
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Contact</th>
                                <th>Interest</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentLeads as $lead): 
                                $statusClass = 'badge-new';
                                $lbl = strtolower($lead['status'] ?? 'new');
                                if ($lbl === 'follow up') $statusClass = 'badge-follow';
                                elseif ($lbl === 'waiting for confirmation') $statusClass = 'badge-waiting';
                                elseif ($lbl === 'enrolled') $statusClass = 'badge-enrolled';
                                elseif ($lbl === 'closed') $statusClass = 'badge-closed';
                                elseif ($lbl === 'invalid') $statusClass = 'badge-invalid';
                                elseif ($lbl === 'dropped') $statusClass = 'badge-dropped';
                            ?>
                                <tr>
                                    <td>
                                        <strong style="display: block; font-size: 0.95rem;"><?php echo clean_output($lead['student_name']); ?></strong>
                                        <span style="font-size: 0.75rem; color: var(--text-muted); font-family: monospace;"><?php echo clean_output($lead['lead_code']); ?></span>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.85rem;"><?php echo clean_output($lead['email']); ?></div>
                                        <div style="font-size: 0.78rem; color: var(--text-secondary);"><?php echo clean_output($lead['phone']); ?></div>
                                    </td>
                                    <td>
                                        <span style="font-size: 0.85rem; font-weight: 500;"><?php echo clean_output($lead['interested_in'] ?: $lead['domain']); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $statusClass; ?>">
                                            <i class="fa-solid fa-circle"></i>
                                            <?php echo clean_output($lead['status']); ?>
                                        </span>
                                    </td>
                                    <td style="font-size: 0.8rem; color: var(--text-secondary);">
                                        <?php echo $lead['created_at'] ? date('M d, Y h:i A', strtotime($lead['created_at'])) : 'N/A'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Info Widget -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="panel-card" style="height: 100%;">
                <div class="panel-header" style="margin-bottom: 1.25rem;">
                    <h3 class="panel-title">
                        <i class="fa-solid fa-lightbulb"></i>
                        <span>System Quick Guides</span>
                    </h3>
                </div>
                <div style="font-size: 0.88rem; line-height: 1.7; display: flex; flex-direction: column; gap: 1.25rem; color: var(--text-secondary);">
                    <div>
                        <strong style="color: var(--text-primary); display: block; margin-bottom: 0.25rem;"><i class="fa-solid fa-users-line" style="color: var(--accent);"></i> CRM Lead Flow</strong>
                        All website contact forms and inquiry fields feed straight into the <strong>Leads CRM</strong>. Filter by "New" to see fresh submissions.
                    </div>
                    <div>
                        <strong style="color: var(--text-primary); display: block; margin-bottom: 0.25rem;"><i class="fa-solid fa-globe" style="color: var(--success);"></i> Website Syncing</strong>
                        Adding, editing, or pausing cards in the Services, Destinations, and Reviews tabs will sync automatically and update the front-end dynamically.
                    </div>
                    <div>
                        <strong style="color: var(--text-primary); display: block; margin-bottom: 0.25rem;"><i class="fa-solid fa-shield-halved" style="color: var(--primary);"></i> Security Recommendation</strong>
                        Always protect your password! You can change your admin email, username, and password under <strong>My Profile</strong>.
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
