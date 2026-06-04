<?php
define('SITE_NAME', 'Bluestone Overseas Consultants');
define('SITE_TAGLINE', 'Your Gateway to Global Education');
define('SITE_PHONE', '+91 93428 99904');
define('SITE_EMAIL', 'info@bluestoneocs.com');
define('SITE_ADDRESS', 'Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College, Coimbatore, Tamil Nadu - 641018');
define('SITE_HOURS', 'Mon-Fri: 09:30 AM - 06:00 PM');
define('SITE_FACEBOOK', 'https://www.facebook.com/bluestoneocs');
define('SITE_INSTAGRAM', 'https://www.instagram.com/bluestoneoverseas');
define('SITE_YOUTUBE', 'https://www.youtube.com/@bluestoneeducation');
define('SITE_LINKEDIN', 'https://in.linkedin.com/company/bluestoneocs');
define('SITE_MAP_LINK', 'https://maps.app.goo.gl/6ej5K3skV5yBYjXS7');
define('BASE_URL', ''); 
define('ASSETS_URL', 'assets');
define('YEAR', date('Y'));

// Include Database Connection
require_once __DIR__ . '/db.php';

// Safe XSS cleaner utility
if (!function_exists('clean_output')) {
    function clean_output($str) {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('resolve_country_slug')) {
    function resolve_country_slug($slug) {
        $slug = strtolower(trim($slug ?? ''));
        $aliases = [
            'netherland' => 'netherlands',
            'balgaria' => 'bulgaria',
            'phillipines' => 'philippines',
            'luxemburg' => 'luxembourg',
            'kazagasthan' => 'kazakhstan',
            'kazagathan' => 'kazakhstan',
            'new-zealand' => 'newzealand',
            'new zealand' => 'newzealand',
            'nz' => 'newzealand',
            'dubai' => 'uae',
        ];
        return $aliases[$slug] ?? $slug;
    }
}

if (!function_exists('get_country_image_url')) {
    function get_country_image_url($slug, $overrideUrl = null) {
        $originalSlug = strtolower(trim($slug ?? ''));
        $slug = resolve_country_slug($originalSlug);
        $candidates = [];

        if (!empty($overrideUrl)) {
            $candidates[] = $overrideUrl;
        }

        if ($originalSlug !== '') {
            $candidates[] = "assets/images/countries/{$originalSlug}.png";
            $candidates[] = "assets/images/countries/{$originalSlug}.jpg";
        }

        if ($slug !== '' && $slug !== $originalSlug) {
            $candidates[] = "assets/images/countries/{$slug}.png";
            $candidates[] = "assets/images/countries/{$slug}.jpg";
        }

        $candidates[] = 'assets/images/countries/default.svg';

        foreach ($candidates as $path) {
            if (empty($path)) {
                continue;
            }

            if (preg_match('#^(https?:)?//#i', $path)) {
                return $path;
            }

            $localPath = __DIR__ . '/../' . ltrim($path, '/\\');
            if (file_exists($localPath)) {
                return $path;
            }
        }

        return 'assets/images/countries/default.svg';
    }
}

// Pagination Helper: Calculate parameters
if (!function_exists('get_pagination_params')) {
    function get_pagination_params($totalRows, $defaultLimit = 10) {
        $limit = $defaultLimit;
        if (isset($_GET['limit'])) {
            $get_limit = $_GET['limit'];
            if (in_array($get_limit, ['10', '20', '50'])) {
                $limit = intval($get_limit);
            } elseif ($get_limit === 'all') {
                $limit = 999999;
            }
        }
        
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        if ($page < 1) $page = 1;
        
        $totalPages = ceil($totalRows / $limit);
        if ($totalPages < 1) $totalPages = 1;
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        
        $offset = ($page - 1) * $limit;
        
        return [
            'limit' => $limit,
            'page' => $page,
            'totalPages' => $totalPages,
            'offset' => $offset,
            'totalRows' => $totalRows
        ];
    }
}

// Pagination Helper: Render limit dropdown inside a filter-bar structure
if (!function_exists('render_limit_dropdown')) {
    function render_limit_dropdown($limit, $actionUrl = '', $extraFields = []) {
        if (empty($actionUrl)) {
            $actionUrl = basename($_SERVER['PHP_SELF']);
        }
        
        $html = '<form method="GET" action="' . clean_output($actionUrl) . '" class="filter-bar" style="justify-content: flex-end; align-items: center; padding: 0.75rem 1.25rem;">';
        
        foreach ($extraFields as $key => $val) {
            if ($key === 'limit' || $key === 'page') continue;
            $html .= '<input type="hidden" name="' . clean_output($key) . '" value="' . clean_output($val) . '">';
        }
        
        $html .= '    <div class="filter-group" style="flex-direction: row; align-items: center; gap: 0.5rem; margin-bottom: 0;">';
        $html .= '        <label for="limit_select" style="font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0;">Show:</label>';
        $html .= '        <select name="limit" id="limit_select" class="filter-control" onchange="this.form.submit()" style="padding: 0.35rem 2rem 0.35rem 0.75rem; font-size: 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border); min-width: auto; background-color: #fff; cursor: pointer;">';
        
        $options = [10 => '10 entries', 20 => '20 entries', 50 => '50 entries', 999999 => 'Show All'];
        foreach ($options as $optVal => $optLabel) {
            $valStr = ($optVal === 999999) ? 'all' : $optVal;
            $selected = ($limit === $optVal) ? 'selected' : '';
            $html .= '<option value="' . $valStr . '" ' . $selected . '>' . $optLabel . '</option>';
        }
        
        $html .= '        </select>';
        $html .= '    </div>';
        $html .= '</form>';
        
        return $html;
    }
}

// Pagination Helper: Render page buttons
if (!function_exists('render_pagination_buttons')) {
    function render_pagination_buttons($page, $totalPages, $extraParams = []) {
        if ($totalPages <= 1) return '';
        
        $actionUrl = basename($_SERVER['PHP_SELF']);
        
        // build base query string excluding page
        $queryParams = $extraParams;
        
        $html = '<div class="pagination">';
        
        // Previous page
        $queryParams['page'] = $page - 1;
        $prevUrl = $actionUrl . '?' . http_build_query($queryParams);
        $prevDisabled = ($page === 1) ? 'disabled' : '';
        $html .= '<a href="' . clean_output($prevUrl) . '" class="page-btn ' . $prevDisabled . '">';
        $html .= '<i class="fa-solid fa-angle-left"></i>';
        $html .= '</a>';
        
        // Page numbers
        for ($i = 1; $i <= $totalPages; $i++) {
            $queryParams['page'] = $i;
            $pageUrl = $actionUrl . '?' . http_build_query($queryParams);
            $activeClass = ($page === $i) ? 'active' : '';
            $html .= '<a href="' . clean_output($pageUrl) . '" class="page-btn ' . $activeClass . '">';
            $html .= $i;
            $html .= '</a>';
        }
        
        // Next page
        $queryParams['page'] = $page + 1;
        $nextUrl = $actionUrl . '?' . http_build_query($queryParams);
        $nextDisabled = ($page === $totalPages) ? 'disabled' : '';
        $html .= '<a href="' . clean_output($nextUrl) . '" class="page-btn ' . $nextDisabled . '">';
        $html .= '<i class="fa-solid fa-angle-right"></i>';
        $html .= '</a>';
        
        $html .= '</div>';
        
        return $html;
    }
}

