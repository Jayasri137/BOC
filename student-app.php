<?php
require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Bluestone Student App | Your Study Abroad Journey';
$metaDescription = 'Explore the Bluestone student app and see how you can discover study destinations, follow applications, prepare for your journey, and connect with fellow students.';
$playStoreUrl = 'https://play.google.com/store/apps/details?id=com.bluestonegroupofinstitutions.mobile&pcampaignid=web_share';
$appCountries = [];
try {
    $countryStmt = $pdo->query("SELECT name FROM countries WHERE is_active = 1 ORDER BY name ASC");
    $appCountries = $countryStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    error_log('Could not load countries for student app consultation form: ' . $e->getMessage());
}

require_once __DIR__ . '/admin/application/third_party/mpdf/qrcode/qrcode.class.php';
$playStoreQr = new QRcode($playStoreUrl, 'M');
ob_start();
$playStoreQr->displayHTML();
$playStoreQrMarkup = ob_get_clean();
$playStoreQrMarkup = str_replace('<table class="qr"', '<table class="qr" aria-hidden="true"', $playStoreQrMarkup);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="theme-color" content="#f5f9fb">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --ink: #0d1b31;
            --muted: #58677b;
            --teal: #079989;
            --teal-dark: #087c75;
            --pale: #f5f9fb;
            --line: #dfe8ef;
            --white: #fff;
            --shadow: 0 24px 70px rgba(19, 41, 65, .12);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background: var(--white);
            color: var(--ink);
            font-family: 'DM Sans', Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; }
        img { display: block; max-width: 100%; }
        .app-wrap { overflow: hidden; }
        .app-container { width: min(1160px, calc(100% - 48px)); margin: 0 auto; }

        .app-nav {
            position: sticky;
            top: 0;
            z-index: 20;
            height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            background: rgba(255,255,255,.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 1px 0 rgba(223,232,239,.8);
        }
        .app-brand {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        .app-brand-logo {
            width: 230px;
            height: 65px;
            object-fit: cover;
            object-position: center;
        }
        .app-nav-links { display: flex; align-items: center; gap: 32px; }
        .app-nav-links a { color: var(--muted); font-size: 14px; font-weight: 600; text-decoration: none; }
        .app-nav-links a:hover { color: var(--teal-dark); }
        .app-nav-links .nav-signin { color: var(--ink); }
        .app-nav-links a.app-button, .app-nav-links a.app-button:hover { color: var(--white); }
        .app-button {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0 22px;
            border: 1px solid transparent;
            border-radius: 999px;
            background: var(--teal);
            color: var(--white);
            font: inherit;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
        }
        .app-button:hover { transform: translateY(-2px); background: var(--teal-dark); box-shadow: 0 10px 24px rgba(7,153,137,.2); }
        .app-button-light { border-color: var(--line); background: var(--white); color: var(--ink); }
        .app-button-light:hover { background: var(--white); color: var(--teal-dark); }

        .app-hero {
            position: relative;
            isolation: isolate;
            padding: 38px 0 48px;
            background:
                radial-gradient(ellipse at 80% 33%, rgba(181, 231, 226, .58), transparent 34%),
                radial-gradient(ellipse at 5% 100%, rgba(211, 226, 255, .35), transparent 38%),
                linear-gradient(135deg, #f7fbfd 0%, #eff8f7 53%, #f9fbfc 100%);
        }
        .app-hero::after {
            position: absolute;
            z-index: -1;
            top: 110px;
            left: -160px;
            width: 390px;
            height: 390px;
            border-radius: 50%;
            background: rgba(180, 222, 246, .19);
            filter: blur(2px);
            content: '';
        }
        .app-hero::before {
            position: absolute;
            top: 38px;
            right: -170px;
            width: 500px;
            height: 500px;
            border: 1px solid rgba(7,153,137,.14);
            border-radius: 50%;
            box-shadow: 0 0 0 42px rgba(7,153,137,.025), 0 0 0 90px rgba(7,153,137,.018);
            content: '';
        }
        .app-hero-grid {
            position: relative;
            display: grid;
            grid-template-columns: 1fr .88fr;
            align-items: center;
            gap: 56px;
        }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: var(--teal-dark);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
        }
        .app-hero-content > .eyebrow {
            gap: 8px;
            padding: 9px 13px;
            border: 1px solid rgba(7,153,137,.15);
            border-radius: 999px;
            background: rgba(255,255,255,.74);
            box-shadow: 0 5px 16px rgba(19,41,65,.04);
            font-size: 10px;
            letter-spacing: .12em;
        }
        .app-hero-content > .eyebrow::before {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--teal);
            box-shadow: 0 0 0 4px rgba(7,153,137,.11);
            content: '';
        }
        .eyebrow::before { width: 22px; height: 2px; background: var(--teal); content: ''; }
        .app-hero h1 {
            max-width: 610px;
            margin: 23px 0 20px;
            font-size: clamp(40px, 4.1vw, 60px);
            line-height: 1.08;
            letter-spacing: -.055em;
        }
        .app-hero h1 em {
            color: var(--teal-dark);
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 600;
            text-shadow: 0 8px 30px rgba(7,153,137,.12);
        }
        .app-hero-copy { max-width: 525px; margin: 0; color: var(--muted); font-size: 17px; line-height: 1.8; }
        .app-hero-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 30px; }
        .app-hero-actions .app-button:first-child { min-height: 52px; padding: 0 25px; box-shadow: 0 10px 22px rgba(7,153,137,.2); }
        .app-hero-actions .app-button-light { border-color: rgba(190,205,216,.75); background: rgba(255,255,255,.76); }
        .app-hero-note { margin-top: 18px; color: #748399; font-size: 12px; }
        .hero-download {
            width: fit-content;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 25px;
            padding: 11px 17px 11px 11px;
            border: 1px solid rgba(223,232,239,.95);
            border-radius: 18px;
            background: rgba(255,255,255,.92);
            box-shadow: 0 12px 32px rgba(19,41,65,.075);
            text-decoration: none;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .hero-download:hover { transform: translateY(-2px); box-shadow: 0 14px 34px rgba(19,41,65,.1); }
        .hero-download-qr {
            width: 112px;
            height: 112px;
            flex: 0 0 112px;
            display: grid;
            place-items: center;
            padding: 5px;
            border: 1px solid var(--line);
            border-radius: 11px;
            background: var(--white);
        }
        .hero-download-qr .qr { width: 100%; height: 100%; table-layout: fixed; border-collapse: collapse; }
        .hero-download-qr .qr td { padding: 0; }
        .hero-download-qr .qr td.on { background: #101b2d; }
        .hero-download-qr .qr td.off { background: #fff; }
        .hero-download-copy { max-width: 160px; }
        .hero-download-copy strong { display: block; margin-bottom: 6px; color: var(--ink); font-size: 14px; }
        .hero-download-copy span { display: block; color: var(--muted); font-size: 12px; line-height: 1.55; }
        .hero-download-copy span:last-child { margin-top: 7px; color: var(--teal-dark); font-weight: 700; }
        .app-hero-visual { position: relative; min-height: 520px; display: flex; align-items: center; justify-content: center; }
        .app-phone {
            position: relative;
            z-index: 3;
            width: min(235px, 68%);
            overflow: hidden;
            border: 9px solid #111c2d;
            border-radius: 38px;
            background: #111c2d;
            box-shadow: 0 38px 90px rgba(10, 30, 52, .3), 0 0 0 1px rgba(255,255,255,.28);
            transform: rotate(2deg);
            animation: hero-phone-float 6s ease-in-out infinite;
        }
        .app-phone--side {
            position: absolute;
            top: 50%;
            z-index: 1;
            width: min(190px, 56%);
            transform: translateY(-50%);
            animation: none;
            opacity: .94;
        }
        .app-phone--left { left: calc(50% - 170px); transform: translateY(-50%) rotate(-10deg); }
        .app-phone--right { right: calc(50% - 170px); transform: translateY(-50%) rotate(10deg); }
        .app-phone--main { position: relative; z-index: 3; }
        .app-phone--side img { filter: saturate(.92); }
        .app-phone img { width: 100%; height: auto; border-radius: 28px; }
        .app-float-card {
            position: absolute;
            z-index: 4;
            right: -7px;
            bottom: 70px;
            width: 225px;
            padding: 16px;
            border: 1px solid rgba(223,232,239,.9);
            border-radius: 17px;
            background: rgba(255,255,255,.96);
            box-shadow: var(--shadow);
        }
        .app-float-card span { display: block; margin-bottom: 5px; color: var(--muted); font-size: 11px; }
        .app-float-card strong { display: block; font-size: 14px; }
        .app-float-card small { display: block; margin-top: 8px; color: var(--teal-dark); font-size: 11px; font-weight: 700; }
        .app-orbit {
            position: absolute;
            width: 390px;
            height: 390px;
            border: 1px solid rgba(7,153,137,.18);
            border-radius: 50%;
            box-shadow: 0 0 0 32px rgba(7,153,137,.035), 0 0 0 70px rgba(7,153,137,.025), 0 0 90px rgba(7,153,137,.1);
        }
        @keyframes hero-phone-float { 0%, 100% { transform: translateY(0) rotate(2deg); } 50% { transform: translateY(-10px) rotate(1deg); } }

        .app-highlights { position: relative; z-index: 2; margin-top: -1px; padding: 0 0 80px; background: linear-gradient(180deg, #f9fbfc 0%, #fff 100%); }
        .highlight-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border: 1px solid rgba(216,228,236,.9);
            border-radius: 22px;
            background: var(--white);
            box-shadow: 0 20px 55px rgba(19,41,65,.07);
        }
        .highlight {
            display: flex;
            align-items: center;
            gap: 15px;
            min-height: 118px;
            padding: 24px 22px;
            transition: background .2s ease, transform .2s ease;
        }
        .highlight:hover { background: #f9fcfd; transform: translateY(-2px); }
        .highlight + .highlight { border-left: 1px solid var(--line); }
        .highlight > .highlight-icon {
            flex: 0 0 40px;
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: #e8f7f5;
            color: var(--teal-dark);
            font-size: 18px;
            font-weight: 800;
        }
        .highlight-icon svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .highlight strong { display: block; margin: 2px 0 5px; font-size: 14px; }
        .highlight > div > span { display: block; color: var(--muted); font-size: 12px; line-height: 1.5; }

        .app-section { padding: 100px 0; }
        .section-heading { max-width: 700px; margin: 0 auto 46px; text-align: center; }
        .section-heading h2 { margin: 15px 0 12px; font-size: clamp(32px, 4vw, 46px); line-height: 1.15; letter-spacing: -.04em; }
        .section-heading p { margin: 0; color: var(--muted); font-size: 16px; line-height: 1.75; }

        .app-showcase {
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            grid-template-areas: "preview copy";
            align-items: center;
            gap: 78px;
        }
        .showcase-copy { grid-area: copy; }
        .showcase-preview { grid-area: preview; }
        .showcase-copy h2 { margin: 16px 0; font-size: clamp(32px, 4vw, 48px); line-height: 1.15; letter-spacing: -.045em; }
        .showcase-copy > p { color: var(--muted); font-size: 16px; line-height: 1.75; }
        .showcase-list { display: grid; gap: 12px; margin-top: 26px; }
        .showcase-tab {
            position: relative;
            width: 100%;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 15px 15px 21px;
            border: 1px solid transparent;
            border-radius: 15px;
            background: transparent;
            color: var(--ink);
            text-align: left;
            cursor: pointer;
            font: inherit;
            transition: background .2s ease, border-color .2s ease, box-shadow .2s ease;
        }
        .showcase-tab:hover, .showcase-tab[aria-pressed="true"] {
            border-color: var(--line);
            background: var(--white);
            box-shadow: 0 10px 28px rgba(19,41,65,.06);
        }
        .showcase-number {
            flex: 0 0 34px;
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 11px;
            background: #edf3f7;
            color: #566579;
            font-size: 12px;
            font-weight: 800;
        }
        .showcase-tab[aria-pressed="true"] .showcase-number { background: #e1f5f2; color: var(--teal-dark); }
        .showcase-tab strong { display: block; margin: 1px 0 4px; font-size: 14px; }
        .showcase-tab small { display: block; color: var(--muted); font-size: 12px; line-height: 1.5; }
        .showcase-progress {
            position: absolute;
            right: 15px;
            bottom: 9px;
            left: 15px;
            height: 3px;
            overflow: hidden;
            border-radius: 999px;
            background: #edf3f7;
            opacity: 0;
        }
        .showcase-tab[aria-pressed="true"] .showcase-progress { opacity: 1; }
        .showcase-progress span { display: block; width: 100%; height: 100%; border-radius: inherit; background: var(--teal); transform: scaleX(0); transform-origin: left; }
        .app-wrap:not(.showcase-paused) .showcase-tab[aria-pressed="true"] .showcase-progress span { animation: showcase-progress 5.5s linear forwards; }
        .showcase-paused .showcase-tab[aria-pressed="true"] .showcase-progress span { animation-play-state: paused; }
        @keyframes showcase-progress { to { transform: scaleX(1); } }
        .showcase-preview {
            position: relative;
            min-height: 670px;
            display: grid;
            place-items: center;
            overflow: hidden;
            border: 1px solid #e1ebf0;
            border-radius: 32px;
            background:
                radial-gradient(ellipse at 50% 45%, rgba(255,255,255,.94) 0%, rgba(237,248,246,.74) 34%, transparent 67%),
                linear-gradient(145deg, #f1f8fa 0%, #e9f5f3 52%, #f5f7fb 100%);
            box-shadow: inset 0 1px rgba(255,255,255,.9), 0 22px 55px rgba(19,41,65,.065);
        }
        .showcase-toggle {
            position: absolute;
            z-index: 3;
            top: 18px;
            right: 18px;
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(223,232,239,.9);
            border-radius: 50%;
            background: rgba(255,255,255,.94);
            color: var(--ink);
            font: inherit;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 8px 22px rgba(19,41,65,.08);
        }
        .showcase-toggle:hover { color: var(--teal-dark); background: var(--white); }
        .showcase-toggle:focus-visible, .showcase-tab:focus-visible { outline: 3px solid rgba(7,153,137,.45); outline-offset: 3px; }
        .showcase-preview::before, .showcase-preview::after {
            position: absolute;
            border: 1px solid rgba(7,153,137,.12);
            border-radius: 50%;
            content: '';
        }
        .showcase-preview::before { width: 490px; height: 490px; }
        .showcase-preview::after { width: 600px; height: 600px; }
        .showcase-screen {
            position: relative;
            z-index: 1;
            width: min(300px, 70%);
            max-height: 620px;
            overflow: hidden;
            border: 8px solid #101b2d;
            border-radius: 36px;
            background: #111c2d;
            box-shadow: 0 28px 68px rgba(10,30,52,.28), 0 0 0 1px rgba(255,255,255,.8);
            transition: opacity .28s ease, transform .28s ease;
        }
        .showcase-screen img { width: 100%; max-height: 604px; object-fit: cover; object-position: top; border-radius: 24px; }
        .showcase-screen.is-changing { opacity: .35; transform: translateY(7px); }
        .showcase-caption {
            position: absolute;
            z-index: 2;
            right: 24px;
            bottom: 24px;
            left: 24px;
            width: fit-content;
            max-width: calc(100% - 48px);
            padding: 12px 16px;
            border: 1px solid rgba(223,232,239,.85);
            border-radius: 14px;
            background: rgba(255,255,255,.94);
            color: var(--muted);
            font-size: 12px;
            box-shadow: 0 12px 30px rgba(19,41,65,.08);
        }
        .showcase-caption strong { display: block; margin-bottom: 3px; color: var(--ink); font-size: 13px; }

        .journey-section { position: relative; overflow: hidden; background: linear-gradient(135deg, #f3f8fb 0%, #f3f9f8 100%); }
        .journey-section::before {
            position: absolute;
            top: -220px;
            right: -140px;
            width: 450px;
            height: 450px;
            border: 1px solid rgba(7,153,137,.1);
            border-radius: 50%;
            box-shadow: 0 0 0 35px rgba(7,153,137,.025), 0 0 0 75px rgba(7,153,137,.018);
            content: '';
        }
        .journey-section > .app-container { position: relative; z-index: 1; }
        .journey-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .journey-card {
            position: relative;
            overflow: hidden;
            padding: 28px;
            border: 1px solid rgba(218,230,237,.95);
            border-radius: 22px;
            background: var(--white);
            box-shadow: 0 10px 30px rgba(19,41,65,.035);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .journey-card:hover { transform: translateY(-6px); border-color: rgba(7,153,137,.26); box-shadow: 0 20px 45px rgba(19,41,65,.09); }
        .journey-card .step { display: inline-flex; padding: 7px 10px; border-radius: 999px; background: #e8f7f5; color: var(--teal-dark); font-size: 10px; font-weight: 800; letter-spacing: .12em; }
        .journey-card h3 { margin: 21px 0 10px; font-size: 19px; line-height: 1.35; }
        .journey-card p { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.7; }

        .app-download { padding: 24px 0 88px; background: linear-gradient(135deg, #f3f8fb 0%, #f3f9f8 100%); }
        .download-panel {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 36px;
            padding: 38px 44px;
            border: 1px solid rgba(216,228,236,.92);
            border-radius: 26px;
            background: linear-gradient(115deg, #fff 0%, #fff 58%, #f2fbf9 100%);
            box-shadow: 0 20px 55px rgba(19,41,65,.065);
        }
        .download-panel::after { position: absolute; top: -105px; right: 270px; width: 260px; height: 260px; border: 1px solid rgba(7,153,137,.1); border-radius: 50%; box-shadow: 0 0 0 28px rgba(7,153,137,.025); content: ''; pointer-events: none; }
        .download-copy, .download-actions { position: relative; z-index: 1; }
        .download-copy { max-width: 620px; }
        .download-copy h2 { margin: 14px 0 10px; font-size: clamp(28px, 3.4vw, 40px); line-height: 1.15; letter-spacing: -.04em; }
        .download-copy p { margin: 0; color: var(--muted); font-size: 15px; line-height: 1.7; }
        .download-actions { display: flex; align-items: center; gap: 20px; }
        .download-qr {
            width: 174px;
            height: 174px;
            display: grid;
            place-items: center;
            padding: 10px;
            border: 1px solid rgba(216,228,236,.95);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 8px 22px rgba(19,41,65,.07);
        }
        .download-qr .qr { width: 100%; height: 100%; table-layout: fixed; border-collapse: collapse; }
        .download-qr .qr td { padding: 0; }
        .download-qr .qr td.on { background: #101b2d; }
        .download-qr .qr td.off { background: #fff; }
        .download-qr-label { max-width: 130px; color: var(--muted); font-size: 12px; line-height: 1.6; }
        .download-qr-label strong { display: block; margin-bottom: 5px; color: var(--ink); font-size: 14px; }
        .download-qr-label a { display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; color: var(--teal-dark); font-weight: 700; text-decoration: none; }
        .download-qr-label a:hover { text-decoration: underline; }

        .app-cta { padding: 84px 0; }
        .cta-panel {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
            overflow: hidden;
            padding: 52px 58px;
            border-radius: 28px;
            background: linear-gradient(120deg, #0d1b31 0%, #103f49 100%);
            color: var(--white);
        }
        .cta-panel::after {
            position: absolute;
            top: -160px;
            right: 16%;
            width: 360px;
            height: 360px;
            border: 1px solid rgba(121,224,211,.18);
            border-radius: 50%;
            box-shadow: 0 0 0 35px rgba(121,224,211,.04), 0 0 0 75px rgba(121,224,211,.03);
            content: '';
        }
        .cta-copy, .cta-actions { position: relative; z-index: 1; }
        .cta-copy { max-width: 620px; }
        .cta-copy .eyebrow { color: #8ce0d7; }
        .cta-copy h2 { margin: 14px 0 10px; font-size: clamp(28px, 3.6vw, 42px); letter-spacing: -.04em; }
        .cta-copy p { margin: 0; color: #c5d3df; font-size: 15px; line-height: 1.7; }
        .cta-actions { display: flex; flex-wrap: wrap; gap: 10px; }
        .cta-actions .app-button-light { border-color: rgba(255,255,255,.26); background: transparent; color: var(--white); }
        .cta-actions .app-button-light:hover { background: rgba(255,255,255,.1); }
        .app-consultation { padding: 28px 0 48px; background: #fff7ed; }
        .app-consultation-inner {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .app-consultation-form-area { padding: .5rem 5% 4rem; font-family: 'Inter', sans-serif; }
        .app-consultation-title {
            margin: 0 0 .5rem;
            color: #0f172a;
            font: 800 clamp(2rem, 7vw, 2.6rem)/1.2 'Plus Jakarta Sans', sans-serif;
        }
        .app-consultation-title::after { display: block; width: 40px; height: 4px; margin-top: 10px; border-radius: 2px; background: #ea580c; content: ''; }
        .app-consultation-description { max-width: 90%; margin: 0 0 2.5rem; color: #475569; font-size: 1.15rem; line-height: 1.6; }
        .app-consultation-form { display: flex; max-width: 500px; flex-direction: column; gap: .85rem; }
        .app-consultation-form .fg-row { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; }
        .app-consultation-form .fg { display: flex; flex-direction: column; gap: .3rem; }
        .app-consultation-form .fg label { color: #5c4033; font-size: .95rem; font-weight: 600; }
        .app-consultation-form .fg label span { color: #ef4444; }
        .app-consultation-form .c-input {
            padding: .7rem .9rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background: #fff;
            color: #0f172a;
            font: 400 .95rem 'Inter', sans-serif;
            transition: all .3s;
        }
        .app-consultation-form .c-input:focus { outline: none; border-color: #ea580c; box-shadow: 0 0 0 3px rgba(234,88,12,.15); }
        .app-consultation-form .checkbox-group { display: flex; flex-direction: column; gap: .5rem; margin-top: .25rem; }
        .app-consultation-form .c-check { display: flex; align-items: flex-start; gap: .5rem; cursor: pointer; }
        .app-consultation-form .c-check input { width: 14px; height: 14px; margin-top: .2rem; accent-color: #ea580c; }
        .app-consultation-form .c-check span { color: #475569; font-size: .85rem; line-height: 1.4; }
        .app-consultation-form .c-check a { color: #ea580c; font-weight: 600; text-decoration: underline; }
        .app-consultation-submit {
            width: fit-content;
            margin-top: .75rem;
            border: 0;
            border-radius: 50px;
            background: #ea580c;
            color: #fff;
            padding: .9rem 2rem;
            font: 600 1.1rem 'Inter', sans-serif;
            cursor: pointer;
            transition: all .3s;
        }
        .app-consultation-submit:hover { transform: translateY(-2px); background: #c2410c; }
        .app-consultation-submit:disabled { cursor: wait; opacity: .7; }
        .app-consultation-message { display: none; margin-top: 1rem; padding: 1rem; border-radius: 8px; font-size: .95rem; font-weight: 500; }
        .app-consultation-message.success { display: block; border: 1px solid #bbf7d0; background: #dcfce7; color: #166534; }
        .app-consultation-message.error { display: block; border: 1px solid #fecaca; background: #fee2e2; color: #991b1b; }
        .app-consultation-graphic {
            position: relative;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 0 2rem 2rem;
        }
        .app-consultation-graphic img { position: relative; z-index: 1; width: 100%; max-width: 550px; object-fit: cover; mix-blend-mode: multiply; }
        .footer-redesign .container { width: min(1160px, calc(100% - 48px)); margin: 0 auto; }

        @media (max-width: 992px) {
            .app-consultation-inner { grid-template-columns: 1fr; }
            .app-consultation-graphic { order: -1; padding: 2rem 5% 0; }
            .app-consultation-form-area { padding: 2rem 5% 3rem; }
            .app-consultation-form .fg-row { grid-template-columns: 1fr; }
            .app-consultation-submit { width: 100%; text-align: center; font-size: 1.15rem; }
        }

        @media (max-width: 900px) {
            .app-hero { padding: 34px 0 42px; }
            .app-hero-grid { grid-template-columns: 1fr .8fr; gap: 25px; }
            .app-hero-visual { min-height: 470px; }
            .app-phone { width: min(215px, 72%); }
            .app-phone--side { width: min(170px, 56%); }
            .app-phone--left { left: calc(50% - 148px); }
            .app-phone--right { right: calc(50% - 148px); }
            .app-float-card { right: -15px; bottom: 56px; width: 180px; }
            .highlight-grid { grid-template-columns: repeat(2, 1fr); }
            .highlight:nth-child(3) { border-left: 0; border-top: 1px solid var(--line); }
            .highlight:nth-child(4) { border-top: 1px solid var(--line); }
            .app-showcase { grid-template-columns: .9fr 1.1fr; gap: 32px; }
            .showcase-preview { min-height: 610px; }
            .cta-panel { padding: 42px; }
            .download-panel { padding: 30px; }
            .download-actions { gap: 14px; }
            .download-qr { width: 148px; height: 148px; }
        }
        @media (max-width: 680px) {
            .app-container { width: min(100% - 36px, 520px); }
            .app-nav { height: 70px; }
            .app-brand-logo { width: 174px; height: 56px; }
            .app-nav-links { gap: 14px; }
            .app-nav-links > a:not(.nav-signin) { display: none; }
            .app-nav .app-button { min-height: 40px; padding: 0 15px; font-size: 12px; }
            .app-hero { padding: 28px 0 34px; }
            .app-hero-grid { grid-template-columns: 1fr; gap: 6px; }
            .app-hero h1 { max-width: 510px; margin-top: 14px; font-size: clamp(36px, 10vw, 50px); }
            .app-hero-copy { font-size: 15px; }
            .app-hero-actions { margin-top: 22px; }
            .hero-download { gap: 12px; margin-top: 16px; padding: 9px 13px 9px 9px; }
            .hero-download-qr { width: 88px; height: 88px; flex-basis: 88px; }
            .hero-download-copy { max-width: 180px; }
            .app-hero-note { margin-top: 12px; }
            .app-hero-visual { min-height: 390px; margin-top: 0; }
            .app-phone { width: min(185px, 54%); border-width: 7px; border-radius: 32px; }
            .app-phone--side { width: min(145px, 43%); border-width: 6px; border-radius: 26px; }
            .app-phone--left { left: calc(50% - 132px); }
            .app-phone--right { right: calc(50% - 132px); }
            .app-phone img { border-radius: 24px; }
            .app-orbit { width: 260px; height: 260px; }
            .app-float-card { right: 0; bottom: 48px; width: 165px; padding: 11px; }
            .app-highlights { padding-bottom: 50px; }
            .highlight { min-height: 100px; padding: 17px 13px; gap: 10px; }
            .highlight > .highlight-icon { flex-basis: 34px; width: 34px; height: 34px; font-size: 15px; }
            .highlight strong { font-size: 12px; }
            .highlight > div > span { font-size: 11px; }
            .app-section { padding: 68px 0; }
            .section-heading { margin-bottom: 30px; }
            .section-heading p { font-size: 14px; }
            .app-showcase { grid-template-columns: 1fr; grid-template-areas: "copy" "preview"; gap: 24px; }
            .showcase-copy h2 { font-size: 36px; }
            .showcase-copy > p { font-size: 14px; }
            .showcase-list { grid-template-columns: repeat(2, 1fr); gap: 8px; }
            .showcase-tab { gap: 8px; padding: 10px 8px; border-color: var(--line); background: #fff; }
            .showcase-tab { padding-bottom: 18px; }
            .showcase-progress { right: 8px; bottom: 7px; left: 8px; }
            .showcase-number { flex-basis: 28px; width: 28px; height: 28px; border-radius: 9px; font-size: 10px; }
            .showcase-tab strong { font-size: 11px; }
            .showcase-tab small { font-size: 10px; line-height: 1.4; }
            .showcase-preview { min-height: 590px; border-radius: 22px; }
            .showcase-screen { width: min(275px, 74%); }
            .showcase-caption { right: 12px; bottom: 12px; left: 12px; max-width: calc(100% - 24px); padding: 10px 12px; }
            .journey-grid { grid-template-columns: 1fr; gap: 12px; }
            .journey-card { padding: 22px; }
            .journey-card h3 { margin: 12px 0 7px; }
            .app-download { padding: 10px 0 58px; }
            .download-panel { grid-template-columns: 1fr; gap: 22px; padding: 26px 22px; }
            .download-copy h2 { font-size: 30px; }
            .download-copy p { font-size: 14px; }
            .download-actions { justify-content: space-between; }
            .download-qr { width: 150px; height: 150px; }
            .download-qr-label { max-width: 160px; }
            .cta-panel { align-items: flex-start; flex-direction: column; padding: 32px 24px; border-radius: 22px; }
            .cta-actions { width: 100%; }
            .cta-actions .app-button { flex: 1; }
            .app-cta { padding: 58px 0; }
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { transition-duration: .01ms !important; }
            .app-phone { animation: none; }
        }
    </style>
</head>
<body>
<div class="app-wrap showcase-paused">
    <header class="app-container app-nav">
        <a class="app-brand" href="index.php" aria-label="Bluestone Overseas home">
            <img class="app-brand-logo" src="assets/images/Logo_old.png" alt="Bluestone Overseas Consultants">
        </a>
        <nav class="app-nav-links" aria-label="Main navigation">
            <a href="#app-features">Explore the app</a>
            <a href="#how-it-helps">Your journey</a>
            <a class="nav-signin" href="sign-in.php">Sign in</a>
            <a class="app-button" href="sign-up.php">Get started <span aria-hidden="true">→</span></a>
        </nav>
    </header>

    <main>
        <section class="app-hero">
            <div class="app-container app-hero-grid">
                <div class="app-hero-content">
                    <span class="eyebrow">Meet your student app</span>
                    <h1>Your study abroad journey, <em>all in one place.</em></h1>
                    <p class="app-hero-copy">Discover universities, keep up with your applications, and find support for every step towards your future overseas.</p>
                    <div class="app-hero-actions">
                        <a class="app-button" href="sign-up.php">Start your journey <span aria-hidden="true">→</span></a>
                        <a class="app-button app-button-light" href="#app-features">See what’s inside</a>
                    </div>
                    <a class="hero-download" href="<?= htmlspecialchars($playStoreUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" aria-label="Scan the QR code or open the Bluestone app on Google Play">
                        <span class="hero-download-qr"><?= $playStoreQrMarkup; ?></span>
                        <span class="hero-download-copy">
                            <strong>Get the app</strong>
                            <span>Scan this code with your phone to open Google Play.</span>
                            <span>Download on Google Play <span aria-hidden="true">↗</span></span>
                        </span>
                    </a>
                    <p class="app-hero-note">A closer look at the Bluestone student app experience.</p>
                </div>
                <div class="app-hero-visual" role="group" aria-label="Previews of the Bluestone student app">
                    <div class="app-orbit" aria-hidden="true"></div>
                    <div class="app-phone app-phone--side app-phone--left" aria-hidden="true">
                        <img src="assets/images/SS14.png" alt="">
                    </div>
                    <div class="app-phone app-phone--side app-phone--right" aria-hidden="true">
                        <img src="assets/images/SS15.png" alt="">
                    </div>
                    <div class="app-phone app-phone--main">
                        <img src="assets/images/SS10.png" alt="Bluestone app home screen with destination search and study services">
                    </div>
                    <div class="app-float-card">
                        <span>Keep your plans in view</span>
                        <strong>Study options to next steps</strong>
                        <small>Explore the app features below →</small>
                    </div>
                </div>
            </div>
        </section>

        <section class="app-highlights" aria-label="App features">
            <div class="app-container highlight-grid">
                <div class="highlight"><span class="highlight-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="10.8" cy="10.8" r="6.8"></circle><path d="m16 16 4.5 4.5"></path></svg></span><div><strong>Explore options</strong><span>Browse study destinations and courses.</span></div></div>
                <div class="highlight"><span class="highlight-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M8 12.5 10.5 15 16 9.5"></path><circle cx="12" cy="12" r="9"></circle></svg></span><div><strong>Follow applications</strong><span>Keep your admissions journey in view.</span></div></div>
                <div class="highlight"><span class="highlight-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 13v-1a8 8 0 0 1 16 0v1"></path><path d="M4 13h3v6H6a2 2 0 0 1-2-2v-4Zm16 0h-3v6h1a2 2 0 0 0 2-2v-4ZM9 20h6"></path></svg></span><div><strong>Get student support</strong><span>Find guidance and useful services.</span></div></div>
                <div class="highlight"><span class="highlight-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M16 20v-1.5a3.5 3.5 0 0 0-3.5-3.5h-5A3.5 3.5 0 0 0 4 18.5V20"></path><circle cx="10" cy="8" r="3.5"></circle><path d="M16 11.5a3.5 3.5 0 0 0 0-7M17 15h.5a3.5 3.5 0 0 1 3.5 3.5V20"></path></svg></span><div><strong>Connect with peers</strong><span>Meet students heading abroad too.</span></div></div>
            </div>
        </section>

        <section class="app-section" id="app-features">
            <div class="app-container app-showcase">
                <div class="showcase-copy">
                    <span class="eyebrow">See it for yourself</span>
                    <h2>One app for the steps that move you forward.</h2>
                    <p>Explore a preview of the app screens. Choose a feature to see how the experience brings your study abroad plans together.</p>
                    <div class="showcase-list" role="group" aria-label="Choose an app screen to preview">
                        <button class="showcase-tab" type="button" data-screen="home" aria-pressed="true">
                            <span class="showcase-number">01</span><span><strong>Your home</strong><small>See destinations, services, and your next steps.</small></span><span class="showcase-progress" aria-hidden="true"><span></span></span>
                        </button>
                        <button class="showcase-tab" type="button" data-screen="discover" aria-pressed="false">
                            <span class="showcase-number">02</span><span><strong>Discover abroad</strong><small>Explore countries, universities, and courses.</small></span><span class="showcase-progress" aria-hidden="true"><span></span></span>
                        </button>
                        <button class="showcase-tab" type="button" data-screen="applications" aria-pressed="false">
                            <span class="showcase-number">03</span><span><strong>My applications</strong><small>View your application stages in one place.</small></span><span class="showcase-progress" aria-hidden="true"><span></span></span>
                        </button>
                        <button class="showcase-tab" type="button" data-screen="profile" aria-pressed="false">
                            <span class="showcase-number">04</span><span><strong>Your study profile</strong><small>Keep your goals and study journey together.</small></span><span class="showcase-progress" aria-hidden="true"><span></span></span>
                        </button>
                        <button class="showcase-tab" type="button" data-screen="community" aria-pressed="false">
                            <span class="showcase-number">05</span><span><strong>Student community</strong><small>Connect with peers and destination groups.</small></span><span class="showcase-progress" aria-hidden="true"><span></span></span>
                        </button>
                    </div>
                </div>
                <div class="showcase-preview">
                    <button class="showcase-toggle" id="showcase-toggle" type="button" aria-label="Play automatic app screen preview" aria-pressed="true">▶</button>
                    <div class="showcase-screen" id="showcase-frame">
                        <img id="showcase-image" src="assets/images/SS10.png" alt="Bluestone app home screen with destination search and study services">
                    </div>
                    <div class="showcase-caption" aria-live="polite">
                        <strong id="showcase-title">Your home</strong>
                        <span id="showcase-description">A quick view of destinations, services, and the admissions journey.</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="app-section journey-section" id="how-it-helps">
            <div class="app-container">
                <div class="section-heading">
                    <span class="eyebrow">A little more clarity at every step</span>
                    <h2>From first search to your next chapter.</h2>
                    <p>Use the app as a companion while you explore, prepare, and work towards studying overseas.</p>
                </div>
                <div class="journey-grid">
                    <article class="journey-card">
                        <span class="step">01 / EXPLORE</span>
                        <h3>Find a direction that feels right</h3>
                        <p>Browse destinations and study options, then save the opportunities you want to revisit.</p>
                    </article>
                    <article class="journey-card">
                        <span class="step">02 / PREPARE</span>
                        <h3>Keep your application moving</h3>
                        <p>Check application progress and find documents, guidance, and support for the steps ahead.</p>
                    </article>
                    <article class="journey-card">
                        <span class="step">03 / CONNECT</span>
                        <h3>Feel supported along the way</h3>
                        <p>Discover student groups and connect with a community planning for life overseas.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="app-download" id="download-app" aria-labelledby="download-app-title">
            <div class="app-container">
                <div class="download-panel">
                    <div class="download-copy">
                        <span class="eyebrow">Take Bluestone with you</span>
                        <h2 id="download-app-title">Get the app on Google Play.</h2>
                        <p>Scan the QR code with your phone to open the official Bluestone Overseas app listing, or open it directly on Google Play.</p>
                    </div>
                    <div class="download-actions">
                        <div class="download-qr" role="img" aria-label="QR code linking to the Bluestone Overseas app on Google Play">
                            <?= $playStoreQrMarkup; ?>
                        </div>
                        <div class="download-qr-label">
                            <strong>Scan to download</strong>
                            Open your camera and point it at the code.
                            <a href="<?= htmlspecialchars($playStoreUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Open Play Store <span aria-hidden="true">↗</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="app-cta">
            <div class="app-container">
                <div class="cta-panel">
                    <div class="cta-copy">
                        <span class="eyebrow">Your next chapter starts here</span>
                        <h2>Ready to take your next step?</h2>
                        <p>Create your student account or sign in to continue with Bluestone Overseas.</p>
                    </div>
                    <div class="cta-actions">
                        <a class="app-button" href="sign-up.php">Create an account <span aria-hidden="true">→</span></a>
                        <a class="app-button app-button-light" href="sign-in.php">Sign in</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="app-consultation" id="consultation" aria-labelledby="app-consultation-title">
            <div class="app-consultation-inner">
                <div class="app-consultation-form-area">
                    <h2 class="app-consultation-title"><span style="color: #ec4899;">Get FREE</span> <span style="color: #0ea5e9;">Counselling Today!</span></h2>
                    <p class="app-consultation-description">Enter your details and our expert will reach out to you to discuss your plans. By the way, all our services are free!</p>

                    <form id="appConsultationForm" class="app-consultation-form">
                        <input type="hidden" name="form_type" value="enquiry">

                        <div class="fg-row">
                            <div class="fg">
                                <label for="app-first-name">First name<span>*</span></label>
                                <input id="app-first-name" type="text" name="first_name" class="c-input" required>
                            </div>
                            <div class="fg">
                                <label for="app-last-name">Last name<span>*</span></label>
                                <input id="app-last-name" type="text" name="last_name" class="c-input" required>
                            </div>
                        </div>

                        <div class="fg-row">
                            <div class="fg">
                                <label for="app-email">Email address<span>*</span></label>
                                <input id="app-email" type="email" name="email" class="c-input" required>
                            </div>
                            <div class="fg">
                                <label for="app-phone">Phone Number<span>*</span></label>
                                <input id="app-phone" type="tel" name="phone" class="c-input" required pattern="[0-9]{10,15}" title="Please enter a valid phone number (10-15 digits)">
                            </div>
                        </div>

                        <div class="fg">
                            <label for="app-city">Your City<span>*</span></label>
                            <input id="app-city" type="text" name="city" class="c-input" required>
                        </div>

                        <div class="fg-row">
                            <div class="fg">
                                <label for="app-destination">Preferred Study Destination<span>*</span></label>
                                <select id="app-destination" name="study_destination" class="c-input" required>
                                    <option value="">Select Country</option>
                                    <?php foreach ($appCountries as $country): ?>
                                        <option value="<?= htmlspecialchars($country, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($country, ENT_QUOTES, 'UTF-8'); ?></option>
                                    <?php endforeach; ?>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="fg">
                                <label for="app-counselling-mode">Preferred mode of counselling<span>*</span></label>
                                <select id="app-counselling-mode" name="counselling_mode" class="c-input" required>
                                    <option value="">Select</option>
                                    <option value="In-Person (Office Visit)">In-Person (Office Visit)</option>
                                    <option value="Virtual (Video Call)">Virtual (Video Call)</option>
                                    <option value="Phone Call">Phone Call</option>
                                </select>
                            </div>
                        </div>

                        <div class="fg-row">
                            <div class="fg">
                                <label for="app-study-level">Preferred study level<span>*</span></label>
                                <select id="app-study-level" name="study_level" class="c-input" required>
                                    <option value="">Select</option>
                                    <option value="Undergraduate (Bachelors)">Undergraduate (Bachelors)</option>
                                    <option value="Postgraduate (Masters)">Postgraduate (Masters)</option>
                                    <option value="Doctorate (PhD)">Doctorate (PhD)</option>
                                    <option value="Diploma / Certificate">Diploma / Certificate</option>
                                </select>
                            </div>
                            <div class="fg">
                                <label for="app-funding-mode">How would you fund your education?<span>*</span></label>
                                <select id="app-funding-mode" name="funding_mode" class="c-input" required>
                                    <option value="">Select</option>
                                    <option value="Self-Funded / Family">Self-Funded / Family</option>
                                    <option value="Education Loan">Education Loan</option>
                                    <option value="Seeking Scholarships">Seeking Scholarships</option>
                                </select>
                            </div>
                        </div>

                        <div class="checkbox-group">
                            <label class="c-check">
                                <input type="checkbox" required>
                                <span>I agree to Bluestone <a href="terms.php" target="_blank">Terms</a> and <a href="privacy.php" target="_blank">privacy policy</a> *</span>
                            </label>
                            <label class="c-check">
                                <input type="checkbox">
                                <span>Please contact me by phone, email or SMS to assist with my enquiry</span>
                            </label>
                            <label class="c-check">
                                <input type="checkbox">
                                <span>I would like to receive updates and offers from Bluestone</span>
                            </label>
                        </div>

                        <button type="submit" class="app-consultation-submit" id="appConsultationSubmit">Avail FREE Counselling</button>
                        <div class="app-consultation-message" id="appConsultationMessage" role="status" aria-live="polite"></div>
                    </form>
                </div>
                <div class="app-consultation-graphic">
                    <img src="assets/images/img4.png" alt="Student Counselling">
                </div>
            </div>
        </section>
    </main>

</div>
<script>
    (() => {
        const screens = {
            home: {
                src: 'assets/images/SS10.png',
                alt: 'Bluestone app home screen with destination search and study services',
                title: 'Your home',
                description: 'A quick view of destinations, services, and the admissions journey.'
            },
            discover: {
                src: 'assets/images/SS14.png',
                alt: 'Discover Abroad screen with country selection and study categories',
                title: 'Discover abroad',
                description: 'Browse study destinations and explore university and course categories.'
            },
            applications: {
                src: 'assets/images/SS15.png',
                alt: 'My Applications screen with an application progress card',
                title: 'My applications',
                description: 'See active applications, current stages, and intake information.'
            },
            profile: {
                src: 'assets/images/SS13.png',
                alt: 'Student profile with application readiness and study journey sections',
                title: 'Your study profile',
                description: 'Keep your study goals, readiness, and journey details together.'
            },
            community: {
                src: 'assets/images/SS17.png',
                alt: 'Student community screen showing destination and intake groups',
                title: 'Student community',
                description: 'Explore student groups and connect with peers heading abroad.'
            }
        };
        const buttons = document.querySelectorAll('.showcase-tab');
        const showcase = document.querySelector('.app-wrap');
        const toggle = document.getElementById('showcase-toggle');
        const image = document.getElementById('showcase-image');
        const frame = document.getElementById('showcase-frame');
        const title = document.getElementById('showcase-title');
        const description = document.getElementById('showcase-description');
        const screenKeys = Object.keys(screens);
        const slideDelay = 5500;
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        let activeScreen = 'home';
        let timer;
        let manuallyPaused = reducedMotion.matches;

        const setPaused = (paused) => {
            manuallyPaused = paused;
            showcase.classList.toggle('showcase-paused', manuallyPaused || document.hidden);
            toggle.textContent = manuallyPaused ? '▶' : 'Ⅱ';
            toggle.setAttribute('aria-label', manuallyPaused ? 'Play automatic app screen preview' : 'Pause automatic app screen preview');
            toggle.setAttribute('aria-pressed', String(manuallyPaused));
            window.clearTimeout(timer);
            if (!manuallyPaused && !document.hidden) {
                timer = window.setTimeout(showNextScreen, slideDelay);
            }
        };

        const showScreen = (screenKey) => {
            const screen = screens[screenKey];
            if (!screen) return;

            activeScreen = screenKey;
            buttons.forEach((button) => button.setAttribute('aria-pressed', String(button.dataset.screen === screenKey)));
            frame.classList.add('is-changing');
            image.onload = () => frame.classList.remove('is-changing');
            image.onerror = () => frame.classList.remove('is-changing');
            image.src = screen.src;
            image.alt = screen.alt;
            title.textContent = screen.title;
            description.textContent = screen.description;
        };

        function showNextScreen() {
            const currentIndex = screenKeys.indexOf(activeScreen);
            showScreen(screenKeys[(currentIndex + 1) % screenKeys.length]);
            if (!manuallyPaused && !document.hidden) {
                timer = window.setTimeout(showNextScreen, slideDelay);
            }
        }

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                showScreen(button.dataset.screen);
                if (!manuallyPaused) {
                    window.clearTimeout(timer);
                    timer = window.setTimeout(showNextScreen, slideDelay);
                }
            });
        });

        toggle.addEventListener('click', () => setPaused(!manuallyPaused));
        document.addEventListener('visibilitychange', () => {
            showcase.classList.toggle('showcase-paused', manuallyPaused || document.hidden);
            window.clearTimeout(timer);
            if (!manuallyPaused && !document.hidden) {
                timer = window.setTimeout(showNextScreen, slideDelay);
            }
        });
        if (!manuallyPaused) setPaused(false);
    })();

    document.getElementById('appConsultationForm').addEventListener('submit', async function (event) {
        event.preventDefault();
        const form = event.currentTarget;
        const button = document.getElementById('appConsultationSubmit');
        const message = document.getElementById('appConsultationMessage');
        const originalButtonText = button.innerHTML;

        button.disabled = true;
        button.textContent = 'Sending request...';
        message.className = 'app-consultation-message';
        message.textContent = '';

        try {
            const response = await fetch('submit-enquiry.php', {
                method: 'POST',
                body: new FormData(form)
            });
            const result = await response.json();
            if (!response.ok || !result.success) {
                throw new Error(result.error || 'We could not send your request. Please try again.');
            }

            form.reset();
            message.className = 'app-consultation-message success';
            message.textContent = 'Thank you! Your consultation request has been received. Our team will contact you shortly.';
            button.textContent = 'Request received';
        } catch (error) {
            message.className = 'app-consultation-message error';
            message.textContent = error.message || 'A network error occurred. Please try again.';
            button.innerHTML = originalButtonText;
            button.disabled = false;
        }
    });
</script>
<?php $hide_floating_widgets = true; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
