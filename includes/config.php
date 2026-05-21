<?php
define('SITE_NAME', 'Bluestone Overseas Consultants');
define('SITE_TAGLINE', 'Your Gateway to Global Education');
define('SITE_PHONE', '+91 93428 99904');
define('SITE_EMAIL', 'info@bluestoneocs.com');
define('SITE_ADDRESS', 'Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College, Coimbatore, Tamil Nadu - 641018');
define('SITE_HOURS', 'Mon-Fri: 09:00 AM - 6:30 PM');
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
