<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="site-base-url" content="<?php echo rtrim(base_url(), '/'); ?>">
    <meta name="ci-csrf-name" content="<?php echo $this->security->get_csrf_token_name(); ?>">
    <meta name="ci-csrf-value" content="<?php echo $this->security->get_csrf_hash(); ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>BluestoneOverseas - Dashboard</title>
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png'); ?>" type="image/png">
    <!-- Vendors Style-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/horizontal-menu.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/skin_color.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/custom.css">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <style>
        #main-menu a.active {
            background-color: #0f1eb3;
            color: white !important;
            font-weight: bold;
            border-radius: 5px;
        }

        .main-menu-btn {
            position: absolute;
            right: 10px;
            top: 10px;
        }

        #main-menu-state:checked+label+ul {
            display: block;
            animation: slideIn 0.3s ease-in-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(20px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .required::after {
            content: " *";
            color: red;
        }

        /* Chat panel styles */
        #chat-panel {
            position: fixed;
            top: 0;
            right: -420px;
            /* hidden by default */
            width: 380px;
            height: 100vh;
            background: #fff;
            box-shadow: -6px 0 20px rgba(0, 0, 0, 0.12);
            z-index: 2000;
            transition: right 0.28s ease;
            display: flex;
            flex-direction: column;
            font-family: "Inter", "Segoe UI", system-ui, -apple-system, sans-serif;
        }

        #chat-panel.open {
            right: 0;
        }

        #chat-header {
            padding: 14px 16px;
            background: linear-gradient(90deg, #5cb85c, #3c8c40);
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #chat-header h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        #chat-header .chat-close {
            margin-left: auto;
            cursor: pointer;
            font-size: 18px;
            opacity: 0.95;
        }

        #chat-body {
            padding: 12px;
            overflow-y: auto;
            flex: 1;
            background: #f7f9fb;
        }

        .msg {
            display: block;
            margin: 8px 0;
            max-width: 85%;
            clear: both;
            word-wrap: break-word;
        }

        .msg.user {
            background: #0b74de;
            color: #fff;
            padding: 10px 12px;
            border-radius: 12px;
            float: right;
        }

        .msg.bot {
            background: #fff;
            color: #222;
            padding: 10px 12px;
            border-radius: 12px;
            float: left;
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.04);
        }

        .typing {
            width: 48px;
            height: 18px;
            display: inline-block;
            vertical-align: middle;
        }

        .dots {
            display: inline-block;
        }

        .dot {
            width: 6px;
            height: 6px;
            margin: 0 2px;
            background: #bbb;
            border-radius: 50%;
            display: inline-block;
            animation: blink 1s infinite;
        }

        .dot:nth-child(2) {
            animation-delay: .15s
        }

        .dot:nth-child(3) {
            animation-delay: .3s
        }

        @keyframes blink {
            0% {
                opacity: 0.2
            }

            50% {
                opacity: 1
            }

            100% {
                opacity: 0.2
            }
        }

        #chat-footer {
            padding: 10px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 8px;
            align-items: center;
            background: #fff;
        }

        #chat-input {
            flex: 1;
            border: 1px solid #e4e7ea;
            border-radius: 8px;
            padding: 8px 10px;
            min-height: 38px;
            outline: none;
        }

        #chat-send {
            background: #3c8c40;
            color: white;
            border: none;
            padding: 9px 12px;
            border-radius: 8px;
            cursor: pointer;
        }

        /* Small screens adjust */
        @media (max-width: 480px) {
            #chat-panel {
                width: 100vw;
                right: -100vw;
            }

            #chat-panel.open {
                right: 0;
            }
        }

    </style>
</head>

<?php
// Load Master_model to keep compatibility if other parts expect it
$this->load->model('Master_model', 'master', 1);

// Get session info
$company1 = $this->session->userdata('company1');
$user_group_id = null;
$is_admin_flag = false;
if (is_array($company1) && isset($company1['user_group_id'])) {
    $user_group_id = $company1['user_group_id'];
    $is_admin_flag = isset($company1['is_admin']) ? (bool)$company1['is_admin'] : false;
}

// We'll use a small helper that checks user_rights -> menu_master by mm_name.
// It will return true if user is admin or has ur_view=1 for that menu name.
$ci = &get_instance();
$ci->load->database();

function can_view($menu_name, $user_group_id, $is_admin_flag, $ci)
{
    // Super admin sees everything
    if ($is_admin_flag) return true;

    if (empty($user_group_id) || empty($menu_name)) return false;

    $sql = "SELECT ur.ur_view
            FROM user_rights ur
            JOIN menu_master mm ON ur.ur_menu_master_id = mm.mm_id
            WHERE mm.mm_name = ? AND ur.ur_user_group_id = ? AND ur.ur_status = 1
            LIMIT 1";
    $row = $ci->db->query($sql, [$menu_name, $user_group_id])->row();
    if (!$row) return false;
    return ((int)$row->ur_view) === 1;
}

// Optionally, fetch all rights once to avoid repeated DB queries. We'll keep the simple per-menu query
// for clarity and minimal changes to your structure.

?>

<body class="layout-top-nav light-skin theme-fruit fixed">
    <div class="wrapper">
        <div id="loader"></div>

        <div class="art-bg">
            <img src="<?= base_url(); ?>assets/images/art1.svg" alt="" class="art-img light-img">
            <img src="<?= base_url(); ?>assets/images/art3.svg" alt="" class="art-img dark-img">
        </div>

        <header class="main-header">
            <div class="inside-header">
                <div class="d-flex align-items-center logo-box justify-content-start">
                    <a href="<?= base_url(); ?>Dashboard" class="logo">
                        <div class="logo-lg d-flex align-items-center justify-content-center bg-white rounded shadow-sm mt-2 p-2" style="height: 50px; max-width: 100%;">
                            <img src="<?= base_url('assets/images/bluestone.png'); ?>" alt="Bluestone OCS Logo" class="img-fluid" style="max-height: 40px; width: auto; object-fit: contain;">
                        </div>
                    </a>
                </div>
            </div>

            <nav class="navbar navbar-static-top">
                <div class="app-menu">
                    <ul class="header-megamenu nav">
                        <li class="btn-group d-lg-inline-flex d-none">
                            <div class="app-menu"></div>
                        </li>
                    </ul>
                </div>

                <div class="navbar-custom-menu r-side">
                    <ul class="nav navbar-nav">
                        <li class="btn-group nav-item d-lg-inline-flex d-none">
                            <a href="#" data-provide="fullscreen" class="waves-effect waves-light nav-link btn-outline no-border full-screen btn-warning-light text-white" title="Full Screen">
                                <i data-feather="maximize"></i>
                            </a>
                        </li>

                        <!-- Notifications button (always visible; individual links inside dropdown are permissioned below) -->
                        <li class="dropdown notifications-menu">
                            <a href="#" class="waves-effect waves-light dropdown-toggle btn-outline no-border btn-info-light text-white" data-bs-toggle="dropdown" title="Notifications">
                                <i data-feather="bell"></i>
                                <?php
                                // Get CodeIgniter database instance counts (kept as original)
                                $total_enquiry = $ci->db->query("SELECT COUNT(*) as count FROM enquiry")->row()->count ?? 0;
                                $today_enquiry = $ci->db->query("SELECT COUNT(*) as count FROM enquiry WHERE DATE(created_at)=CURDATE()")->row()->count ?? 0;

                                $total_blogs = $ci->db->query("SELECT COUNT(*) as count FROM blogs")->row()->count ?? 0;
                                $today_blogs = $ci->db->query("SELECT COUNT(*) as count FROM blogs WHERE DATE(created_at)=CURDATE()")->row()->count ?? 0;

                                $total_contact = $ci->db->query("SELECT COUNT(*) as count FROM contact_enquiry")->row()->count ?? 0;
                                $today_contact = $ci->db->query("SELECT COUNT(*) as count FROM contact_enquiry WHERE DATE(created_at)=CURDATE()")->row()->count ?? 0;

                                $total_web_enquiry = $ci->db->query("SELECT COUNT(*) as count FROM web_enquiry")->row()->count ?? 0;
                                $today_web_enquiry = $ci->db->query("SELECT COUNT(*) as count FROM web_enquiry WHERE DATE(created_at)=CURDATE()")->row()->count ?? 0;

                                $total_liveclasses = $ci->db->query("SELECT COUNT(*) as count FROM liveclasses")->row()->count ?? 0;
                                $today_liveclasses = $ci->db->query("SELECT COUNT(*) as count FROM liveclasses WHERE DATE(created_at)=CURDATE()")->row()->count ?? 0;
                                $active_liveclasses = $ci->db->query("SELECT COUNT(*) as count FROM liveclasses WHERE status=1")->row()->count ?? 0;

                                // Fetch lead counts
                                $lead_counts = [];
                                $statuses = ['Open', 'Registered', 'Hot', 'Warm', 'Cold', 'Not Interested'];
                                foreach ($statuses as $status) {
                                    $count = $ci->db->query("SELECT COUNT(*) as count FROM lead WHERE status=?", [$status])->row()->count ?? 0;
                                    $lead_counts[$status] = $count;
                                }
                                $total_leads = array_sum($lead_counts);

                                $total_items = $total_enquiry + $total_blogs + $total_contact + $total_web_enquiry + $total_liveclasses + $total_leads;
                                ?>

                                <?php if ($total_items > 0): ?>
                                    <span class="badge bg-danger badge-pill" style="position: absolute; top: -5px; right: -5px; font-size: 10px;">
                                        <?= $total_items ?>
                                    </span>
                                <?php endif; ?>
                            </a>

                            <ul class="dropdown-menu animated bounceIn" style="width: 350px; max-height: 400px; overflow-y: auto;">
                                <li class="header">
                                    <div class="p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-bold">Notifications</h6>
                                            <a href="#" class="text-danger small">Clear All</a>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <ul class="menu">
                                        <!-- Enquiries -->
                                        <?php if (can_view('Enquiry', $user_group_id, $is_admin_flag, $ci)): ?>
                                            <li>
                                                <a href="<?= site_url('enquiry/index') ?>" class="d-flex align-items-center py-2 px-3 text-dark text-decoration-none">
                                                    <div class="me-3">
                                                        <i class="fas fa-question-circle text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="d-block fw-semibold">Enquiries</span>
                                                        <small class="text-muted d-block">Total: <?= $total_enquiry ?> | Today: <?= $today_enquiry ?></small>
                                                    </div>
                                                    <?php if ($today_enquiry > 0): ?>
                                                        <span class="badge bg-primary ms-2"><?= $today_enquiry ?></span>
                                                    <?php endif; ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <!-- Web Enquiries -->
                                        <?php if (can_view('Web Enquiry', $user_group_id, $is_admin_flag, $ci)): ?>
                                            <li>
                                                <a href="<?= site_url('webenquiry/index') ?>" class="d-flex align-items-center py-2 px-3 text-dark text-decoration-none">
                                                    <div class="me-3">
                                                        <i class="fas fa-globe text-success"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="d-block fw-semibold">Web Enquiries</span>
                                                        <small class="text-muted d-block">Total: <?= $total_web_enquiry ?> | Today: <?= $today_web_enquiry ?></small>
                                                    </div>
                                                    <?php if ($today_web_enquiry > 0): ?>
                                                        <span class="badge bg-success ms-2"><?= $today_web_enquiry ?></span>
                                                    <?php endif; ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <!-- Contact Enquiries -->
                                        <?php if (can_view('Contact Enquiry', $user_group_id, $is_admin_flag, $ci)): ?>
                                            <li>
                                                <a href="<?= site_url('contact/index') ?>" class="d-flex align-items-center py-2 px-3 text-dark text-decoration-none">
                                                    <div class="me-3">
                                                        <i class="fas fa-envelope text-warning"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="d-block fw-semibold">Contact Enquiries</span>
                                                        <small class="text-muted d-block">Total: <?= $total_contact ?> | Today: <?= $today_contact ?></small>
                                                    </div>
                                                    <?php if ($today_contact > 0): ?>
                                                        <span class="badge bg-warning ms-2"><?= $today_contact ?></span>
                                                    <?php endif; ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <!-- Blogs -->
                                        <?php if (can_view('Blog', $user_group_id, $is_admin_flag, $ci)): ?>
                                            <li>
                                                <a href="<?= site_url('blog/index') ?>" class="d-flex align-items-center py-2 px-3 text-dark text-decoration-none">
                                                    <div class="me-3">
                                                        <i class="fas fa-blog text-info"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="d-block fw-semibold">Blogs</span>
                                                        <small class="text-muted d-block">Total: <?= $total_blogs ?> | Today: <?= $today_blogs ?></small>
                                                    </div>
                                                    <?php if ($today_blogs > 0): ?>
                                                        <span class="badge bg-info ms-2"><?= $today_blogs ?></span>
                                                    <?php endif; ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <!-- Live Classes -->
                                        <?php if (can_view('Live Session', $user_group_id, $is_admin_flag, $ci) || can_view('Live Classes', $user_group_id, $is_admin_flag, $ci)): ?>
                                            <li>
                                                <a href="<?= site_url('livesession/index') ?>" class="d-flex align-items-center py-2 px-3 text-dark text-decoration-none">
                                                    <div class="me-3">
                                                        <i class="fas fa-video text-danger"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="d-block fw-semibold">Live Classes</span>
                                                        <small class="text-muted d-block">Total: <?= $total_liveclasses ?> | Today: <?= $today_liveclasses ?> | Active: <?= $active_liveclasses ?></small>
                                                    </div>
                                                    <?php if ($today_liveclasses > 0): ?>
                                                        <span class="badge bg-danger ms-2"><?= $today_liveclasses ?></span>
                                                    <?php endif; ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <!-- Leads -->
                                        <?php if (can_view('Lead', $user_group_id, $is_admin_flag, $ci)): ?>
                                            <li>
                                                <a href="<?= site_url('lead/index') ?>" class="d-flex align-items-center py-2 px-3 text-dark text-decoration-none">
                                                    <div class="me-3">
                                                        <i class="fas fa-users text-purple"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="d-block fw-semibold">Leads</span>
                                                        <small class="text-muted d-block">Total: <?= $total_leads ?></small>
                                                        <div class="mt-1 d-flex flex-wrap gap-1">
                                                            <?php foreach ($lead_counts as $status => $count): ?>
                                                                <?php if ($count > 0): ?>
                                                                    <!-- <span class="badge bg-light text-dark border" style="font-size: 9px;">
                                                                        <?= substr($status, 0, 1) ?>:<?= $count ?>
                                                                    </span> -->
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                    <?php if ($total_leads > 0): ?>
                                                        <span class="badge bg-purple ms-2"><?= $total_leads ?></span>
                                                    <?php endif; ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <li><hr class="my-1"></li>

                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="<?= site_url('Dashboard') ?>" class="text-center d-block py-2 text-decoration-none border-top">
                                        <small class="text-primary">View Dashboard</small>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <style>
                            .bg-purple {
                                background-color: #6f42c1 !important;
                            }

                            .text-purple {
                                color: #6f42c1 !important;
                            }
                        </style>

                        <li class="dropdown user user-menu">
                            <a href="#" class="waves-effect waves-light dropdown-toggle no-border p-5" data-bs-toggle="dropdown" title="User">
                                <img class="avatar avatar-pill" src="<?= base_url(); ?>assets/images/avatar/3.jpg" alt="">
                            </a>
                            <ul class="dropdown-menu animated flipInX">
                                <li class="user-body p-30">
                                    <!-- <a class="dropdown-item" href="<?= base_url() ?>master/profile"><i class="ti-user text-muted me-2"></i>Profile</a> -->
                                    <a class="dropdown-item" href="<?= base_url(); ?>welcome/logout">
                                        <i class="ti-lock text-muted me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>

            </nav>

        </header>

        <nav class="main-nav" role="navigation" style="z-index: 999;">
            <input id="main-menu-state" type="checkbox" />
            <label class="main-menu-btn" for="main-menu-state">
                <span class="main-menu-btn-icon"></span> Toggle main menu visibility
            </label>

            <ul id="main-menu" class="sm sm-blue">
                <!-- Dashboard (everyone logged in should have dashboard; you can change name in menu_master if needed) -->
                <?php if (can_view('Dashboard', $user_group_id, $is_admin_flag, $ci) || $is_admin_flag): ?>
                    <li>
                        <a href="<?= base_url(); ?>Dashboard"><i class="fas fa-home"></i>Dashboard</a>
                    </li>
                <?php endif; ?>

                <!-- Blog -->
                <?php if (can_view('Blog', $user_group_id, $is_admin_flag, $ci)): ?>
                    <li>
                        <a href="<?= base_url(); ?>blog/index" class="cursor-pointer"><i class="fas fa-blog"></i>Blog</a>
                    </li>
                <?php endif; ?>

                <!-- Enquiry -->
                <?php if (can_view('Enquiry', $user_group_id, $is_admin_flag, $ci)): ?>
                    <li>
                        <a href="<?= base_url(); ?>enquiry/index" class="cursor-pointer">
                            <i class="fas fa-clipboard-list"></i> Enquiry
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Lead -->
                <?php if (can_view('Lead', $user_group_id, $is_admin_flag, $ci)): ?>
                    <li>
                        <a href="<?= base_url(); ?>lead/index" class="cursor-pointer">
                            <i class="fas fa-user-tie"></i> Lead
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Web Enquiry -->
                <?php if (can_view('Web Enquiry', $user_group_id, $is_admin_flag, $ci)): ?>
                    <li>
                        <a href="<?= base_url(); ?>webenquiry/index" class="cursor-pointer">
                            <i class="fas fa-globe"></i> Web Enquiry
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Contact Enquiry -->
                <?php if (can_view('Contact Enquiry', $user_group_id, $is_admin_flag, $ci)): ?>
                    <li>
                        <a href="<?= base_url(); ?>contact/index" class="cursor-pointer">
                            <i class="fas fa-address-book"></i> Contact Enquiry
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Live Session -->
                <?php if (can_view('Live Session', $user_group_id, $is_admin_flag, $ci) || can_view('Live Classes', $user_group_id, $is_admin_flag, $ci)): ?>
                    <li>
                        <a href="<?= base_url(); ?>livesession/index" class="cursor-pointer">
                            <i class="fas fa-video"></i> Live Session
                        </a>
                    </li>
                <?php endif; ?>

                <!-- User Master dropdown (show only if any child is permitted OR admin) -->
                <?php
                $userMasterChildren = [
                    'User', 'Role', 'Branch', 'Invoice Type', 'Country', 'University', 'In Take Year', 'User Rights'
                ];
                $showUserMaster = $is_admin_flag;
                if (!$showUserMaster) {
                    foreach ($userMasterChildren as $mn) {
                        if (can_view($mn, $user_group_id, $is_admin_flag, $ci)) {
                            $showUserMaster = true;
                            break;
                        }
                    }
                }
                ?>

                <?php if ($showUserMaster): ?>
                    <li class="nav-item dropdown">
                        <a href="javascript:void(0);" class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse">
                            <i class="fas fa-cog"></i> User Master
                        </a>
                        <ul class="dropdown-menu">
                            

                            <!-- <?php if (can_view('Role', $user_group_id, $is_admin_flag, $ci)): ?>
                                <li>
                                    <a href="<?= base_url(); ?>master/role" class="dropdown-item">
                                        <i class="fas fa-user-shield me-2"></i> Role
                                    </a>
                                </li>
                            <?php endif; ?> -->

                            <!-- <?php if (can_view('Branch', $user_group_id, $is_admin_flag, $ci)): ?>
                                <li>
                                    <a href="<?= base_url(); ?>master/branch" class="dropdown-item">
                                        <i class="fas fa-code-branch me-2"></i> Branch
                                    </a>
                                </li>
                            <?php endif; ?> -->

                            <?php if (can_view('Country', $user_group_id, $is_admin_flag, $ci)): ?>
                                <li>
                                    <a href="<?= base_url(); ?>master/country" class="dropdown-item">
                                        <i class="fas fa-globe-asia me-2"></i> Country
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can_view('University', $user_group_id, $is_admin_flag, $ci)): ?>
                                <li>
                                    <a href="<?= base_url(); ?>master/university" class="dropdown-item">
                                        <i class="fas fa-university me-2"></i> University
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can_view('In Take Year', $user_group_id, $is_admin_flag, $ci)): ?>
                                <li>
                                    <a href="<?= base_url(); ?>master/intake_year" class="dropdown-item">
                                        <i class="fas fa-calendar-alt me-2"></i> Intakeyear
                                    </a>
                                </li>
                            <?php endif; ?>

                             <?php if (can_view('User Rights', $user_group_id, $is_admin_flag, $ci)): ?>
                                <li>
                                    <a href="<?= base_url('master/usergroup'); ?>" class="dropdown-item">
                                       <i class="fas fa-user-tie me-2"></i> Role
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can_view('User', $user_group_id, $is_admin_flag, $ci)): ?>
                                <li>
                                    <a href="<?= base_url('master/user'); ?>" class="dropdown-item">
                                        <i class="fas fa-user me-2"></i> User
                                    </a>
                                </li>
                            <?php endif; ?>
                            

                            <?php if (can_view('User Rights', $user_group_id, $is_admin_flag, $ci)): ?>
                                <li>
                                    <a href="<?= base_url('master/userrights'); ?>" class="dropdown-item">
                                        <i class="fas fa-user-lock me-2"></i> User Rights
                                    </a>
                                </li>
                            <?php endif; ?>
                           
                        </ul>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>
    </div>

    <!-- ========= IMPROVED ACTIVE MENU SCRIPT ========= -->
    <script>
        (function() {
            // Get the base URL from the meta tag or calculate it
            const baseUrlMeta = document.querySelector('meta[name="site-base-url"]');
            const baseUrl = baseUrlMeta ? baseUrlMeta.content : window.location.origin;
            
            function getCurrentPath() {
                // Get the pathname relative to the site root
                const fullPath = window.location.pathname;
                const basePath = new URL(baseUrl).pathname;
                
                // Remove base path if present
                let relativePath = fullPath;
                if (basePath !== '/' && fullPath.startsWith(basePath)) {
                    relativePath = fullPath.substring(basePath.length);
                }
                
                // Clean up the path
                relativePath = relativePath.replace(/^\/+/, '');
                relativePath = relativePath.replace(/\/+$/, '');
                
                // Remove index.php if present
                relativePath = relativePath.replace(/index\.php\/?/, '');
                
                return relativePath.toLowerCase();
            }
            
            function getLinkPath(href) {
                try {
                    const url = new URL(href, window.location.origin);
                    let path = url.pathname;
                    
                    // Remove base path if present
                    const basePath = new URL(baseUrl).pathname;
                    if (basePath !== '/' && path.startsWith(basePath)) {
                        path = path.substring(basePath.length);
                    }
                    
                    // Clean up the path
                    path = path.replace(/^\/+/, '');
                    path = path.replace(/\/+$/, '');
                    
                    // Remove index.php if present
                    path = path.replace(/index\.php\/?/, '');
                    
                    return path.toLowerCase();
                } catch (e) {
                    // If URL parsing fails, try to extract path manually
                    let path = href.replace(baseUrl, '');
                    path = path.replace(/^\/+/, '');
                    path = path.replace(/\/+$/, '');
                    path = path.replace(/index\.php\/?/, '');
                    return path.toLowerCase();
                }
            }
            
            function setActiveForLink(link) {
                if (!link) return;
                
                // Clear existing active classes
                clearActive();
                
                // Set active on this link
                link.classList.add('active');
                let li = link.closest('li');
                if (li) {
                    li.classList.add('active');
                    
                    // Open parent dropdowns if any
                    let parentLi = li.parentElement.closest('li');
                    while (parentLi && !parentLi.classList.contains('sm')) {
                        parentLi.classList.add('open');
                        const parentLink = parentLi.querySelector(':scope > a');
                        if (parentLink) parentLink.classList.add('active');
                        parentLi = parentLi.parentElement.closest('li');
                    }
                }
            }
            
            function clearActive() {
                document.querySelectorAll('#main-menu a.active').forEach(a => a.classList.remove('active'));
                document.querySelectorAll('#main-menu li.active').forEach(li => li.classList.remove('active'));
                document.querySelectorAll('#main-menu li.open').forEach(li => li.classList.remove('open'));
            }
            
            function findBestMatch() {
                const links = Array.from(document.querySelectorAll('#main-menu a[href]'));
                if (!links.length) return null;
                
                const currentPath = getCurrentPath();
                
                // Try exact match first
                for (const link of links) {
                    const linkPath = getLinkPath(link.href);
                    if (linkPath === currentPath) {
                        return link;
                    }
                }
                
                // Try partial match (for nested routes)
                for (const link of links) {
                    const linkPath = getLinkPath(link.href);
                    if (linkPath && currentPath.startsWith(linkPath)) {
                        return link;
                    }
                }
                
                // Try reverse partial match (current is parent of link)
                for (const link of links) {
                    const linkPath = getLinkPath(link.href);
                    if (linkPath && linkPath.startsWith(currentPath)) {
                        return link;
                    }
                }
                
                return null;
            }
            
            function applyActive() {
                // Small delay to ensure DOM is ready
                setTimeout(() => {
                    const match = findBestMatch();
                    if (match) {
                        setActiveForLink(match);
                    } else {
                        // Fallback: check if we're on dashboard
                        const currentPath = getCurrentPath();
                        if (!currentPath || currentPath === '' || currentPath === 'dashboard') {
                            const dashboardLink = document.querySelector('#main-menu a[href*="dashboard"]');
                            if (dashboardLink) setActiveForLink(dashboardLink);
                        }
                    }
                }, 100);
            }
            
            // Initialize on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', applyActive);
            } else {
                applyActive();
            }
            
            // Re-apply when navigating via pushState/replaceState
            const originalPushState = history.pushState;
            const originalReplaceState = history.replaceState;
            
            history.pushState = function() {
                const result = originalPushState.apply(this, arguments);
                applyActive();
                return result;
            };
            
            history.replaceState = function() {
                const result = originalReplaceState.apply(this, arguments);
                applyActive();
                return result;
            };
            
            window.addEventListener('popstate', applyActive);
            
            // Also apply when links are clicked (for smoother UX)
            document.addEventListener('click', function(e) {
                const link = e.target.closest('#main-menu a[href]');
                if (link) {
                    // Small delay to allow navigation
                    setTimeout(applyActive, 50);
                }
            });
            
            // Debug function - uncomment if needed
            // function debugActiveMenu() {
            //     const currentPath = getCurrentPath();
            //     console.log('Current path:', currentPath);
            //     console.log('Base URL:', baseUrl);
                
            //     const links = Array.from(document.querySelectorAll('#main-menu a[href]'));
            //     links.forEach(link => {
            //         const linkPath = getLinkPath(link.href);
            //         console.log('Menu link:', link.textContent.trim(), 'Path:', linkPath, 'Full href:', link.href);
            //     });
                
            //     const match = findBestMatch();
            //     console.log('Best match:', match ? match.textContent.trim() : 'None');
            // }
            // debugActiveMenu();
            
        })();
    </script>
    <!-- ========= END IMPROVED ACTIVE MENU SCRIPT ========= -->

</body>

</html>