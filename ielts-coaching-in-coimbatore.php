<?php
require_once 'includes/config.php';
$pageTitle = 'Best IELTS Coaching in Coimbatore | Score Band 7.5+ | Bluestone Overseas';
$pageDesc = 'Looking for top IELTS coaching in Coimbatore? Bluestone Overseas offers certified trainers, complete syllabus coverage, mock tests, 1-on-1 speaking sessions, and study abroad guidance.';
$pageKeywords = 'IELTS Coaching in Coimbatore, Best IELTS Training Centre Coimbatore, IELTS Academic coaching, IELTS General Training, IELTS classes in Coimbatore, IELTS syllabus Coimbatore, IELTS band 8 coaching';
$pageHeroImage = 'assets/images/ielts.png';
$hideDefaultHero = true;
require_once 'includes/header.php';
?>

<style>
/* IELTS Premium Theme Variables - Official Crimson Red Palette */
:root {
  --IELTS-primary: #dc2626; /* red-600 */
  --IELTS-primary-hover: #b91c1c; /* red-700 */
  --IELTS-light: #fef2f2; /* red-50 */
  --IELTS-light-border: #fecaca; /* red-200 */
  --IELTS-gradient: linear-gradient(135deg, #ef4444, #dc2626);
  --dark: #0f172a;
  --gray: #475569;
  --muted: #64748b;
}

/* Base Styles specific to IELTS page */
.IELTS-text-gradient {
  background: var(--IELTS-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* 1. WAVE HERO SECTION */
.IELTS-hero {
  position: relative;
  padding: 8rem 0 12rem;
  background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
  overflow: hidden;
  border-bottom: none;
}
.IELTS-hero::before {
  content: '';
  position: absolute;
  top: -20%; right: -10%;
  width: 700px; height: 700px;
  background: linear-gradient(135deg, rgba(220, 38, 38, 0.05), rgba(239, 68, 68, 0.08));
  border-radius: 50%;
  filter: blur(80px);
  z-index: 0;
  animation: float 10s infinite ease-in-out alternate;
}
.IELTS-hero::after {
  content: '';
  position: absolute;
  bottom: -10%; left: -10%;
  width: 500px; height: 500px;
  background: linear-gradient(135deg, rgba(249, 115, 22, 0.05), rgba(220, 38, 38, 0.08));
  border-radius: 50%;
  filter: blur(60px);
  z-index: 0;
  animation: float 8s infinite ease-in-out alternate-reverse;
}
@keyframes float {
  0% { transform: translateY(0) scale(1); }
  100% { transform: translateY(-30px) scale(1.05); }
}
.IELTS-hero .container { position: relative; z-index: 2; }
.IELTS-hero-tag {
  display: inline-block;
  padding: 0.5rem 1.5rem;
  background: white;
  color: var(--IELTS-primary);
  border-radius: 50px;
  font-weight: 700;
  font-size: 0.9rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 10px 20px rgba(220, 38, 38, 0.1);
  border: 1px solid rgba(220, 38, 38, 0.15);
  letter-spacing: 1px;
  text-transform: uppercase;
}
.IELTS-hero-title {
  font-size: clamp(2.5rem, 5vw, 4.5rem);
  font-weight: 800;
  color: var(--dark);
  line-height: 1.1;
  margin-bottom: 1.5rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.IELTS-hero-subtitle {
  font-size: 1.15rem;
  color: var(--gray);
  line-height: 1.8;
  max-width: 600px;
  margin-bottom: 2.5rem;
}
.IELTS-hero-stats {
  display: flex;
  gap: 3rem;
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 1px solid rgba(0,0,0,0.05);
}
.IELTS-stat-item h4 {
  font-size: 2rem;
  font-weight: 800;
  color: var(--dark);
  margin-bottom: 0.2rem;
}
.IELTS-stat-item p { color: var(--gray); font-size: 0.9rem; font-weight: 500; }

/* 2. WAVE CARDS SECTION (Why Choose Us) */
.IELTS-wave-feature-card {
  background: white;
  border-radius: 24px;
  padding: 3.5rem 2.5rem 2.5rem;
  position: relative;
  overflow: hidden;
  z-index: 1;
  box-shadow: 0 15px 35px rgba(0,0,0,0.04);
  border: 1px solid rgba(0,0,0,0.04);
  transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  height: 100%;
}
.IELTS-wave-feature-card:hover {
  transform: translateY(-15px);
  box-shadow: 0 30px 60px rgba(0,0,0,0.08);
}
.twf-icon-wrap {
  width: 80px;
  height: 80px;
  border-radius: 20px;
  background: linear-gradient(135deg, var(--w-c1), var(--w-c2));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  color: white;
  margin-bottom: 2rem;
  box-shadow: 0 15px 30px rgba(0,0,0,0.1);
  position: relative;
  z-index: 2;
  transition: transform 0.4s ease;
}
.IELTS-wave-feature-card:hover .twf-icon-wrap { transform: scale(1.1) rotate(-5deg); }
.twf-title {
  font-size: 1.4rem;
  font-weight: 800;
  color: var(--dark);
  margin-bottom: 1rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
  position: relative;
  z-index: 2;
  transition: color 0.4s ease;
}
.twf-desc {
  color: var(--gray);
  line-height: 1.7;
  position: relative;
  z-index: 2;
  transition: color 0.4s ease;
}
/* Wave effect bottom */
.twf-bottom {
  position: absolute;
  bottom: 0; left: 0; width: 100%; height: 65%;
  background: linear-gradient(180deg, var(--w-c1), var(--w-c2));
  color: var(--w-c1);
  z-index: 1;
  transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
  transform: translateY(100%);
}
.twf-svg {
  position: absolute;
  top: -29px; left: 0; width: 100%; height: 30px;
  fill: currentColor;
}
.IELTS-wave-feature-card:hover .twf-bottom { transform: translateY(0); }
.IELTS-wave-feature-card:hover .twf-title,
.IELTS-wave-feature-card:hover .twf-desc { color: white; }

/* ===================================================
   SYLLABUS MODULE TAB LAYOUT
   =================================================== */
.syllabus-tab-wrap {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 2rem;
  align-items: start;
  margin-top: 2.5rem;
}
/* Left Nav */
.syllabus-nav {
  position: sticky;
  top: 110px;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}
.syllabus-nav-btn {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.1rem 1.4rem;
  border-radius: 18px;
  border: 2px solid transparent;
  background: #f8fafc;
  cursor: pointer;
  text-align: left;
  transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  font-family: 'Plus Jakarta Sans', sans-serif;
  position: relative;
  overflow: hidden;
}
.syllabus-nav-btn::after {
  content: '';
  position: absolute;
  right: 1.2rem;
  top: 50%;
  transform: translateY(-50%);
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--snb-color, #dc2626);
  opacity: 0;
  transition: opacity 0.3s ease;
}
.syllabus-nav-btn:hover {
  border-color: var(--snb-color, #dc2626);
  background: white;
  transform: translateX(4px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.06);
}
.syllabus-nav-btn.active {
  background: white;
  border-color: var(--snb-color, #dc2626);
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
  transform: translateX(6px);
}
.syllabus-nav-btn.active::after { opacity: 1; }
.snb-icon {
  width: 46px;
  height: 46px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  color: white;
  flex-shrink: 0;
  background: linear-gradient(135deg, var(--snb-color, #dc2626), var(--snb-color2, #b91c1c));
  box-shadow: 0 6px 16px rgba(0,0,0,0.12);
  transition: transform 0.3s ease;
}
.syllabus-nav-btn:hover .snb-icon,
.syllabus-nav-btn.active .snb-icon { transform: scale(1.1) rotate(-5deg); }
.snb-text-label {
  font-size: 0.78rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  color: #94a3b8;
  margin-bottom: 0.15rem;
}
.snb-text-title {
  font-size: 1.05rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.2;
}
.snb-text-sub {
  font-size: 0.82rem;
  color: #64748b;
  font-weight: 500;
}
/* Right Panels */
.syllabus-panels-wrap {
  position: relative;
}
.syllabus-mod-panel {
  display: none;
  animation: smpFadeIn 0.4s ease;
}
.syllabus-mod-panel.active { display: block; }
@keyframes smpFadeIn {
  from { opacity: 0; transform: translateY(14px); }
  to   { opacity: 1; transform: translateY(0); }
}
/* Module Hero Banner */
.smp-hero {
  border-radius: 24px;
  padding: 2.5rem 3rem;
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 2rem;
  flex-wrap: wrap;
  margin-bottom: 2rem;
  position: relative;
  overflow: hidden;
}
.smp-hero::before {
  content: '';
  position: absolute;
  right: -60px;
  top: -60px;
  width: 260px;
  height: 260px;
  border-radius: 50%;
  background: rgba(255,255,255,0.08);
}
.smp-hero::after {
  content: '';
  position: absolute;
  right: 40px;
  bottom: -80px;
  width: 180px;
  height: 180px;
  border-radius: 50%;
  background: rgba(255,255,255,0.06);
}
.smp-hero-left { position: relative; z-index: 1; }
.smp-hero-icon {
  font-size: 2.5rem;
  margin-bottom: 0.75rem;
  opacity: 0.9;
}
.smp-hero-title {
  font-size: 1.8rem;
  font-weight: 800;
  margin-bottom: 0.5rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
  line-height: 1.2;
}
.smp-hero-desc {
  font-size: 0.97rem;
  opacity: 0.88;
  line-height: 1.65;
  max-width: 520px;
}
.smp-hero-actions {
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 1rem;
  margin-left: auto;
  flex-shrink: 0;
}
.smp-task-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.55rem;
  min-width: 260px;
  padding: 0.9rem 1.45rem;
  border: 2px solid rgba(255,255,255,0.8);
  border-radius: 999px;
  color: white;
  font-size: 1rem;
  font-weight: 800;
  text-decoration: none;
  background: rgba(255,255,255,0.06);
  box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
  transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
}
.smp-task-link:hover,
.smp-task-link:focus-visible {
  color: white;
  background: rgba(255,255,255,0.12);
  transform: translateY(-1px);
  box-shadow: 0 18px 28px rgba(15, 23, 42, 0.16);
}
.smp-task-link:focus-visible { outline: 2px solid white; outline-offset: 3px; }
.smp-hero-stats {
  display: flex;
  gap: 1.25rem;
  flex-wrap: wrap;
  justify-content: flex-end;
  position: relative;
  z-index: 1;
  flex-shrink: 0;
}
.smp-stat {
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.2);
  backdrop-filter: blur(8px);
  border-radius: 16px;
  padding: 1rem 1.4rem;
  text-align: center;
  min-width: 100px;
}
.smp-stat-val {
  font-size: 1.5rem;
  font-weight: 800;
  display: block;
  line-height: 1.2;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.smp-stat-label {
  font-size: 0.78rem;
  opacity: 0.8;
  font-weight: 500;
  margin-top: 0.2rem;
  white-space: nowrap;
}
/* Content Card inside panel */
.smp-card {
  background: white;
  border-radius: 20px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 8px 24px rgba(0,0,0,0.04);
  overflow: hidden;
  margin-bottom: 1.5rem;
}
.smp-card-header {
  padding: 1.25rem 1.75rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.75rem;
  border-bottom: 1px solid #f1f5f9;
  background: #fafbfc;
}
.smp-card-header h4 {
  font-size: 1.1rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
  font-family: 'Plus Jakarta Sans', sans-serif;
  display: flex;
  align-items: center;
  gap: 0.6rem;
}
.smp-card-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.3rem 0.85rem;
  border-radius: 50px;
  font-size: 0.82rem;
  font-weight: 700;
}
/* Key points grid */
.smp-key-points {
  padding: 1.5rem 1.75rem;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 0.85rem;
}
.smp-kp-item {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  padding: 0.85rem 1rem;
  background: #f8fafc;
  border-radius: 12px;
  border: 1px solid #f1f5f9;
  transition: all 0.25s ease;
}
.smp-kp-item:hover {
  border-color: var(--smp-accent, #dc2626);
  background: white;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.smp-kp-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--smp-accent, #dc2626);
  flex-shrink: 0;
  margin-top: 6px;
}
.smp-kp-text {
  font-size: 0.93rem;
  color: #334155;
  line-height: 1.5;
  font-weight: 500;
}
/* SMP Table */
.smp-table-wrap { overflow-x: auto; }
.smp-table {
  width: 100%;
  border-collapse: collapse;
}
.smp-table thead th {
  padding: 0.9rem 1.5rem;
  font-size: 0.9rem;
  font-weight: 800;
  color: #0f172a;
  background: white;
  border-bottom: 2px solid #e2e8f0;
  font-family: 'Plus Jakarta Sans', sans-serif;
  text-align: left;
}
.smp-table tbody td {
  padding: 1rem 1.5rem;
  font-size: 0.93rem;
  color: #334155;
  border-bottom: 1px solid #f1f5f9;
  line-height: 1.55;
  vertical-align: middle;
}
.smp-table tbody tr:last-child td { border-bottom: none; }
.smp-table tbody tr:hover { background: #fafbfc; }
.smp-td-strong {
  font-weight: 800;
  color: #0f172a;
  white-space: nowrap;
}

/* IELTS Reading practice tasks */
.art-section {
  margin-top: 1.5rem;
  background: #fff;
  border: 1px solid #a7f3d0;
  border-radius: 20px;
  overflow: hidden;
}
.art-header {
  padding: 1.35rem 1.75rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
  background: linear-gradient(135deg, #ecfdf5, #fff);
  border-bottom: 1px solid #d1fae5;
}
.art-header h3 {
  margin: 0;
  color: #0f172a;
  font: 800 1.25rem 'Plus Jakarta Sans', sans-serif;
}
.art-toggle-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.65rem 1rem;
  border: 1px solid #059669;
  border-radius: 10px;
  background: #059669;
  color: white;
  font-weight: 700;
  cursor: pointer;
}
.art-toggle-btn:hover { background: #047857; }
.art-practice-body { padding: 1.5rem 1.75rem; }
.art-intro { margin: 0 0 1rem; color: #475569; line-height: 1.65; }
.art-tabs { display: flex; flex-wrap: wrap; gap: 0.55rem; margin-bottom: 1.25rem; }
.art-tab {
  padding: 0.55rem 0.85rem;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  background: #fff;
  color: #334155;
  font-weight: 700;
  cursor: pointer;
}
.art-tab:hover, .art-tab.active { border-color: #059669; background: #ecfdf5; color: #047857; }
.art-panel { display: none; }
.art-panel.active { display: block; animation: smpFadeIn 0.25s ease; }
.art-panel h4 { margin: 0 0 0.8rem; color: #0f172a; font-size: 1.05rem; }
.art-passage {
  padding: 1.15rem 1.25rem;
  border-left: 3px solid #10b981;
  border-radius: 0 10px 10px 0;
  background: #f8fafc;
  color: #334155;
  line-height: 1.75;
}
.art-questions { margin: 1rem 0; padding-left: 1.35rem; color: #334155; line-height: 1.8; }
.art-question { padding-left: 0.25rem; margin-bottom: 0.85rem; }
.art-answer-input {
  display: block;
  width: min(100%, 360px);
  margin-top: 0.35rem;
  padding: 0.65rem 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  color: #0f172a;
  font: inherit;
}
.art-answer-input:focus { outline: 3px solid rgba(16, 185, 129, 0.2); border-color: #059669; }
.art-question.is-correct .art-answer-input { border-color: #059669; background: #f0fdf4; }
.art-question.is-incorrect .art-answer-input { border-color: #dc2626; background: #fef2f2; }
.art-question-result { display: block; margin-top: 0.3rem; font-size: 0.9rem; font-weight: 700; }
.art-question.is-correct .art-question-result { color: #047857; }
.art-question.is-incorrect .art-question-result { color: #b91c1c; }
.art-check-btn {
  padding: 0.7rem 1.1rem;
  border: 0;
  border-radius: 9px;
  background: #059669;
  color: white;
  font: inherit;
  font-weight: 700;
  cursor: pointer;
}
.art-check-btn:hover { background: #047857; }
.art-check-btn:focus-visible { outline: 3px solid rgba(16, 185, 129, 0.3); outline-offset: 2px; }
.art-task-feedback { margin: 0.85rem 0 0; color: #334155; font-weight: 700; }
.art-task-feedback[hidden] { display: none; }

/* Speaking timeline */
.smp-speaking-timeline {
  padding: 1.5rem 1.75rem;
  display: flex;
  flex-direction: column;
  gap: 0;
}
.smp-tl-step {
  display: flex;
  gap: 1.5rem;
  position: relative;
}
.smp-tl-left {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex-shrink: 0;
  width: 56px;
}
.smp-tl-bubble {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  font-weight: 800;
  color: white;
  flex-shrink: 0;
  font-family: 'Plus Jakarta Sans', sans-serif;
  box-shadow: 0 6px 18px rgba(0,0,0,0.15);
  position: relative;
  z-index: 1;
}
.smp-tl-line {
  flex: 1;
  width: 2px;
  background: linear-gradient(to bottom, var(--smp-accent, #8b5cf6), transparent);
  margin: 0.5rem auto 0;
  min-height: 40px;
}
.smp-tl-step:last-child .smp-tl-line { display: none; }
.smp-tl-content {
  padding: 0.25rem 0 2rem;
  flex: 1;
}
.smp-tl-title {
  font-size: 1.1rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 0.4rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
  display: flex;
  align-items: center;
  gap: 0.65rem;
  flex-wrap: wrap;
}
.smp-tl-time-tag {
  font-size: 0.8rem;
  font-weight: 700;
  padding: 0.2rem 0.7rem;
  border-radius: 50px;
  background: var(--smp-accent-bg, #f5f3ff);
  color: var(--smp-accent, #8b5cf6);
  border: 1px solid var(--smp-accent-border, #ddd6fe);
}
.smp-tl-desc {
  color: #475569;
  font-size: 0.93rem;
  line-height: 1.65;
}
.smp-tl-bullets {
  list-style: none;
  padding: 0;
  margin: 0.65rem 0 0;
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}
.smp-tl-bullets li {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 50px;
  padding: 0.25rem 0.75rem;
  font-size: 0.85rem;
  color: #334155;
  font-weight: 500;
}
.smp-tl-bullets li::before {
  content: '';
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--smp-accent, #8b5cf6);
  flex-shrink: 0;
}
/* Responsive */
@media (max-width: 992px) {
  .syllabus-tab-wrap {
    grid-template-columns: 1fr;
  }
  .syllabus-nav {
    position: static;
    flex-direction: row;
    flex-wrap: wrap;
    gap: 0.5rem;
  }
  .syllabus-nav-btn {
    flex: 1;
    min-width: 140px;
    padding: 0.85rem 1rem;
  }
  .syllabus-nav-btn.active,
  .syllabus-nav-btn:hover { transform: none; }
  .snb-text-sub { display: none; }
  .smp-hero { padding: 2rem; }
  .smp-hero-title { font-size: 1.45rem; }
  .smp-hero-stats { gap: 0.75rem; }
}
@media (max-width: 600px) {
  .smp-key-points { grid-template-columns: 1fr; }
  .smp-stat { min-width: 80px; padding: 0.75rem 1rem; }
  .smp-hero-stats { flex-wrap: wrap; }
}

/* 3. ELITE HORIZONTAL CARDS (Curriculum) */
.IELTS-elite-card {
  display: flex;
  background: white;
  border-radius: 30px;
  overflow: hidden;
  box-shadow: 0 20px 50px rgba(0,0,0,0.05);
  margin-bottom: 2rem;
  transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  border: 1px solid rgba(0,0,0,0.03);
}
.IELTS-elite-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 30px 60px rgba(0,0,0,0.08);
}
.tec-image {
  flex: 0 0 38%;
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, var(--tec-bg1), var(--tec-bg2));
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}
.tec-image img {
  width: 100%;
  max-width: 250px;
  object-fit: contain;
  transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  filter: drop-shadow(0 20px 30px rgba(0,0,0,0.15));
}
.IELTS-elite-card:hover .tec-image img { transform: scale(1.08); }
.tec-content {
  flex: 1;
  padding: 2.5rem 3rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.tec-badge-row {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1rem;
  flex-wrap: wrap;
}
.tec-duration-tag {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.35rem 0.9rem;
  border-radius: 50px;
  font-size: 0.85rem;
  font-weight: 700;
  background: var(--tec-bg1);
  color: var(--tec-c1);
}
.tec-questions-tag {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.35rem 0.9rem;
  border-radius: 50px;
  font-size: 0.85rem;
  font-weight: 700;
  background: #f1f5f9;
  color: #475569;
}
.tec-title {
  font-size: 1.85rem;
  font-weight: 800;
  color: var(--dark);
  margin-bottom: 1rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.tec-list {
  list-style: none; padding: 0; margin: 0;
  display: grid; gap: 0.75rem;
}
.tec-list li {
  position: relative;
  padding-left: 2rem;
  color: var(--gray);
  font-size: 1rem;
  line-height: 1.6;
}
.tec-list li::before {
  content: '\f00c';
  font-family: 'Font Awesome 6 Free';
  font-weight: 900;
  position: absolute;
  left: 0; top: 2px;
  color: var(--tec-c1);
}
/* Alternate layout */
.IELTS-elite-card:nth-child(even) { flex-direction: row-reverse; }

/* 4. SPLIT LAYOUT (Target Audience) */
.IELTS-split-card {
  background: white; border-radius: 20px; padding: 2.5rem; display: flex; gap: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.03); transition: all 0.3s ease;
}
.IELTS-split-card:hover {
  transform: translateX(10px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.08);
  border-color: rgba(220, 38, 38, 0.25);
}

/* 5. COMPLETE IELTS EXAM STRUCTURE TABLE (Requested Content) */
.ielts-structure-section {
  padding: 5rem 0;
  background: #ffffff;
}
.ielts-structure-container {
  background: #ffffff;
  border-radius: 24px;
  box-shadow: 0 20px 50px rgba(15, 23, 42, 0.06);
  border: 1px solid #f1f5f9;
  overflow: hidden;
  margin-top: 2.5rem;
}
.ielts-structure-header {
  padding: 2.5rem 3rem 2rem;
  background: linear-gradient(135deg, #fef2f2 0%, #fff 100%);
  border-bottom: 1px solid #fee2e2;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1.5rem;
}
.ielts-structure-header h2 {
  font-size: 2.2rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
  font-family: 'Plus Jakarta Sans', sans-serif;
  letter-spacing: -0.5px;
}
.ielts-total-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  background: #dc2626;
  color: white;
  padding: 0.6rem 1.4rem;
  border-radius: 50px;
  font-weight: 700;
  font-size: 0.95rem;
  box-shadow: 0 8px 20px rgba(220, 38, 38, 0.25);
}
.ielts-table-responsive {
  width: 100%;
  overflow-x: auto;
}
.ielts-structure-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  text-align: left;
}
.ielts-structure-table thead th {
  padding: 1.5rem 2.5rem;
  font-size: 1.1rem;
  font-weight: 800;
  color: #0f172a;
  background: #ffffff;
  border-bottom: 2px solid #e2e8f0;
  text-transform: capitalize;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.ielts-structure-table thead th:first-child { width: 32%; }
.ielts-structure-table thead th:nth-child(2) { width: 34%; }
.ielts-structure-table thead th:nth-child(3) { width: 34%; }

.ielts-structure-table tbody tr {
  transition: all 0.25s ease;
  border-bottom: 1px solid #f1f5f9;
}
.ielts-structure-table tbody tr:hover {
  background: #fff8f8;
}
.ielts-structure-table tbody td {
  padding: 1.75rem 2.5rem;
  font-size: 1.05rem;
  color: #334155;
  border-bottom: 1px solid #f1f5f9;
  vertical-align: middle;
}
.ielts-structure-table tbody tr:last-child td {
  border-bottom: none;
}
.module-cell {
  display: flex;
  align-items: center;
  gap: 1.25rem;
  font-weight: 800;
  font-size: 1.15rem;
  color: #0f172a;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.module-icon-box {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  color: white;
  flex-shrink: 0;
  box-shadow: 0 6px 16px rgba(0,0,0,0.08);
}
.duration-cell {
  font-weight: 600;
  color: #1e293b;
}
.duration-pill {
  display: inline-block;
  background: #f1f5f9;
  padding: 0.45rem 1rem;
  border-radius: 50px;
  font-weight: 700;
  color: #334155;
  font-size: 0.95rem;
}
.tasks-cell {
  font-weight: 600;
  color: #334155;
}
.tasks-highlight {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 700;
  color: #0f172a;
}

/* ===================================================
   ACADEMIC WRITING TASK 1 — INTERACTIVE CHART UI
   =================================================== */
.awt1-section {
  background: #ffffff;
  border-radius: 20px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  overflow: hidden;
  margin-top: 2rem;
}
.awt1-header {
  padding: 1.5rem 2rem;
  background: linear-gradient(135deg, #fef2f2 0%, #fff 100%);
  border-bottom: 1px solid #fee2e2;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}
.awt1-header h3 {
  font-size: 1.35rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.awt1-intro {
  padding: 1.25rem 2rem 0.5rem;
  color: #475569;
  font-size: 1rem;
  line-height: 1.6;
  margin: 0;
}
/* Button Row */
.awt1-btn-row {
  display: flex;
  flex-wrap: wrap;
  gap: 0.65rem;
  padding: 1.25rem 2rem 0;
}
.awt1-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.55rem 1.15rem;
  border-radius: 50px;
  border: 2px solid #e2e8f0;
  background: #f8fafc;
  color: #475569;
  font-size: 0.92rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.28s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  font-family: 'Plus Jakarta Sans', sans-serif;
  user-select: none;
  position: relative;
  overflow: hidden;
}
.awt1-btn::before {
  content: '';
  position: absolute;
  inset: 0;
  background: var(--awt1-color, #dc2626);
  opacity: 0;
  transition: opacity 0.25s ease;
  border-radius: inherit;
}
.awt1-btn:hover {
  border-color: var(--awt1-color, #dc2626);
  color: var(--awt1-color, #dc2626);
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}
.awt1-btn.active {
  background: var(--awt1-color, #dc2626);
  border-color: var(--awt1-color, #dc2626);
  color: white;
  box-shadow: 0 8px 20px rgba(220,38,38,0.25);
  transform: translateY(-2px);
}
.awt1-btn .awt1-num {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  background: rgba(0,0,0,0.08);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.78rem;
  font-weight: 800;
  flex-shrink: 0;
  transition: background 0.25s ease;
}
.awt1-btn.active .awt1-num {
  background: rgba(255,255,255,0.25);
}
/* Content Panel */
.awt1-panels {
  padding: 0 2rem 2rem;
  margin-top: 1.25rem;
}
.awt1-panel {
  display: none;
  animation: awt1FadeIn 0.35s ease;
}
.awt1-panel.active {
  display: block;
}
@keyframes awt1FadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}
.awt1-card-inner {
  display: flex;
  gap: 2rem;
  background: #f8fafc;
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid #e2e8f0;
}
.awt1-card-thumb {
  flex: 0 0 220px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
  background: linear-gradient(135deg, var(--awt1-panel-bg1, #fef2f2), var(--awt1-panel-bg2, #fee2e2));
  min-height: 180px;
}
.awt1-card-thumb .awt1-chart-icon {
  font-size: 4.5rem;
  color: var(--awt1-color, #dc2626);
  opacity: 0.85;
  filter: drop-shadow(0 8px 16px rgba(0,0,0,0.1));
  transition: transform 0.4s ease;
}
.awt1-card-inner:hover .awt1-chart-icon {
  transform: scale(1.12) rotate(-4deg);
}
.awt1-card-body {
  flex: 1;
  padding: 1.75rem 1.75rem 1.75rem 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.awt1-card-title {
  font-size: 1.45rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 1rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}
.awt1-card-title span.awt1-title-dot {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: var(--awt1-color, #dc2626);
  flex-shrink: 0;
}
.awt1-card-time {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.35rem 0.9rem;
  border-radius: 50px;
  background: var(--awt1-panel-bg1, #fef2f2);
  color: var(--awt1-color, #dc2626);
  font-size: 0.85rem;
  font-weight: 700;
  border: 1px solid var(--awt1-panel-bg2, #fecaca);
  margin-bottom: 0.85rem;
  align-self: flex-start;
}
.awt1-desc-text {
  color: #475569;
  font-size: 0.97rem;
  line-height: 1.65;
  margin-bottom: 1rem;
}
.awt1-bullet-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 0.5rem;
}
.awt1-bullet-list li {
  position: relative;
  padding-left: 1.5rem;
  color: #334155;
  font-size: 0.97rem;
  line-height: 1.55;
  font-weight: 500;
}
.awt1-bullet-list li::before {
  content: '';
  position: absolute;
  left: 0;
  top: 7px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--awt1-color, #dc2626);
}
@media (max-width: 768px) {
  .awt1-card-inner { flex-direction: column; }
  .awt1-card-thumb { flex: none; min-height: 130px; }
  .awt1-card-body  { padding: 1.25rem; }
  .awt1-btn-row    { gap: 0.5rem; }
  .awt1-bullet-list { grid-template-columns: 1fr; }
}
/* 6. SYLLABUS BOXES & TABLES STYLES (User Requested Images A, B, C & 5) */
.syllabus-block-wrap {
  margin-top: 1.5rem;
  margin-bottom: 3.5rem;
}
.syllabus-box {
  background: #ffffff;
  border-radius: 20px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  overflow: hidden;
  margin-top: 1.25rem;
}
.syllabus-box-header {
  padding: 1.5rem 2rem;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}
.syllabus-box-header h3 {
  font-size: 1.35rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
  font-family: 'Plus Jakarta Sans', sans-serif;
  letter-spacing: -0.3px;
}
.syllabus-time-pill {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.4rem 1rem;
  border-radius: 50px;
  background: #fef2f2;
  color: #dc2626;
  font-size: 0.88rem;
  font-weight: 700;
  border: 1px solid #fecaca;
}
.syllabus-intro-text {
  padding: 1.25rem 2rem 0.25rem;
  color: #475569;
  font-size: 1rem;
  line-height: 1.6;
  margin: 0;
}
.syllabus-table-responsive {
  width: 100%;
  overflow-x: auto;
}
.syllabus-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  text-align: left;
}
.syllabus-table th {
  padding: 1.2rem 2rem;
  font-size: 1rem;
  font-weight: 800;
  color: #0f172a;
  background: #ffffff;
  border-bottom: 2px solid #e2e8f0;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.syllabus-table td {
  padding: 1.25rem 2rem;
  font-size: 0.98rem;
  color: #334155;
  border-bottom: 1px solid #f1f5f9;
  vertical-align: middle;
  line-height: 1.6;
}
.syllabus-table tr:last-child td {
  border-bottom: none;
}
.syllabus-table tbody tr:hover {
  background: #fcfcfd;
}
.td-bold {
  font-weight: 700;
  color: #0f172a;
  white-space: nowrap;
}

/* 7. SPEAKING SYLLABUS CARDS (User Requested Image 5) */
.speaking-syllabus-wrapper {
  background: #ffffff;
  border-radius: 20px;
  border: 1px solid #e2e8f0;
  padding: 2.5rem 2.5rem 1.5rem;
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  margin-top: 1.25rem;
}
.speaking-syllabus-header {
  font-size: 1.5rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 1.75rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.speaking-step-card {
  display: flex;
  gap: 2.25rem;
  align-items: flex-start;
  padding: 1.75rem 0;
  border-bottom: 1px solid #f1f5f9;
}
.speaking-step-card:last-child {
  border-bottom: none;
}
.speaking-step-badge {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  width: 95px;
  padding: 1rem 0.6rem;
  text-align: center;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(0,0,0,0.02);
}
.speaking-step-num {
  font-size: 1.6rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1;
  margin-bottom: 0.4rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.speaking-step-time {
  font-size: 0.82rem;
  font-weight: 600;
  color: #64748b;
  white-space: nowrap;
}
.speaking-step-content {
  flex: 1;
}
.speaking-step-title {
  font-size: 1.25rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 0.6rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.speaking-step-desc {
  color: #475569;
  font-size: 1rem;
  line-height: 1.7;
  margin: 0;
}
.speaking-step-bullets {
  list-style: none;
  padding: 0;
  margin: 0.6rem 0 0;
  display: grid;
  gap: 0.45rem;
}
.speaking-step-bullets li {
  position: relative;
  padding-left: 1.35rem;
  color: #475569;
  font-size: 1rem;
  line-height: 1.6;
}
.speaking-step-bullets li::before {
  content: '•';
  position: absolute;
  left: 0;
  color: #dc2626;
  font-weight: 900;
  font-size: 1.3rem;
  line-height: 1;
}

@media (max-width: 992px) {
  .IELTS-elite-card, .IELTS-elite-card:nth-child(even) { flex-direction: column; }
  .tec-content { padding: 2.5rem; }
  .audience-left { position: static !important; margin-bottom: 3rem; }
  .IELTS-split-card { flex-direction: column; gap: 1.5rem; }
  .IELTS-hero-title { font-size: 2.5rem; }
  .IELTS-hero-stats { flex-direction: column; gap: 1.5rem; }
}

@media (max-width: 768px) {
  .ielts-structure-header { padding: 1.75rem 1.5rem; }
  .ielts-structure-header h2 { font-size: 1.6rem; }
  .ielts-structure-table thead th { padding: 1rem 1.25rem; font-size: 0.95rem; }
  .ielts-structure-table tbody td { padding: 1.25rem 1.25rem; font-size: 0.95rem; }
  .module-cell { font-size: 1rem; gap: 0.85rem; }
  .module-icon-box { width: 38px; height: 38px; font-size: 1rem; }
  .syllabus-table th, .syllabus-table td { padding: 1rem 1.25rem; font-size: 0.92rem; }
  .syllabus-box-header { padding: 1.25rem 1.5rem; }
  .syllabus-intro-text { padding: 1rem 1.5rem 0.25rem; }
  .speaking-syllabus-wrapper { padding: 1.75rem 1.5rem; }
  .speaking-step-card { flex-direction: column; gap: 1rem; }
  .speaking-step-badge {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    width: auto;
    padding: 0.6rem 1.25rem;
  }
  .speaking-step-num { margin-bottom: 0; font-size: 1.3rem; }
}

/* Theme Colors */
/* Wave Cards */
.tw-theme-1 { --w-c1: #dc2626; --w-c2: #991b1b; } /* Crimson Red */
.tw-theme-2 { --w-c1: #0ea5e9; --w-c2: #0284c7; } /* Sky Blue */
.tw-theme-3 { --w-c1: #10b981; --w-c2: #059669; } /* Emerald */

/* Elite Cards */
.tec-theme-listening { --tec-c1: #3b82f6; --tec-c2: #1d4ed8; --tec-bg1: #eff6ff; --tec-bg2: #dbeafe; } /* Blue */
.tec-theme-reading { --tec-c1: #10b981; --tec-c2: #047857; --tec-bg1: #ecfdf5; --tec-bg2: #d1fae5; } /* Emerald */
.tec-theme-writing { --tec-c1: #dc2626; --tec-c2: #991b1b; --tec-bg1: #fef2f2; --tec-bg2: #fee2e2; } /* Crimson */
.tec-theme-speaking { --tec-c1: #8b5cf6; --tec-c2: #6d28d9; --tec-bg1: #f5f3ff; --tec-bg2: #ede9fe; } /* Purple */

</style>

<main>
  <!-- 1. HERO SECTION -->
  <section class="country-hero-custom" style="background-image: url('<?= htmlspecialchars($pageHeroImage) ?>');">
    <!-- Dark overlay to ensure text readability -->
    <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.45));"></div>
    
    <div class="container animate-on-scroll" style="position: relative; z-index: 2; text-align: left; color: white; width: 100%;">
      <div style="max-width: 800px;">
        <h1 style="font-size: clamp(2.5rem, 5.5vw, 4.4rem); font-weight: 800; margin-bottom: 1.5rem; line-height: 1.15; text-shadow: 0 10px 30px rgba(0,0,0,0.5);">IELTS Coaching</h1>
        <p class="country-hero-desc" style="font-size: 1.15rem; opacity: 0.95; line-height: 1.8; text-shadow: 0 4px 15px rgba(0,0,0,0.5); border-left: 4px solid #8b5cf6; padding-left: 1.5rem;">Fast results, fair scoring, and targeted preparation. In association with Bluestone Language Hub, our highly strategic IELTS coaching decodes the AI algorithm to help you rapidly meet visa and university requirements for Australia, New Zealand, and the UK.</p>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 2.2rem;">
            <a href="consultation.php" class="btn pulse-btn" style="background: #8b5cf6; color: white; border-radius: 50px; padding: 0.9rem 2.2rem; font-weight: 700; box-shadow: 0 10px 25px rgba(139, 92, 246, 0.4); text-decoration: none; border: none;">Join IELTS Batch</a>
            <a href="#curriculum" class="btn" style="border: 1px solid rgba(255,255,255,0.3); background: rgba(255, 255, 255, 0.1); color: white; border-radius: 50px; padding: 0.9rem 2.2rem; font-weight: 600; text-decoration: none;">View Syllabus</a>
        </div>
      </div>
    </div>
    
    <!-- Decorative bottom curve matching the site background -->
    <div class="page-hero__curve">
      <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,100 C480,0 960,0 1440,100 L1440,100 L0,100 Z" fill="currentColor"/>
      </svg>
    </div>
  </section>

  <style>
  .country-hero-custom {
      position: relative;
      width: 100%;
      min-height: 600px;
      padding-bottom: 120px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      padding-top: 100px;
  }
  @media (max-width: 768px) {
      .country-hero-custom {
          height: auto;
          min-height: 420px;
          padding-top: 120px;
          padding-bottom: 60px;
      }
      .country-hero-custom .container {
          text-align: center !important;
      }
      .country-hero-custom h1 {
          font-size: 2.6rem !important;
      }
      .country-hero-desc {
          font-size: 1.05rem !important;
          border-left: none !important;
          padding-left: 0 !important;
      }
  }

  .country-fact-pill {
      display: flex;
      align-items: center;
      gap: 1.25rem;
      background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.95));
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      padding: 1.5rem;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
      border: 1px solid rgba(255,255,255,0.9);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }
  .country-fact-pill:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 0 15px 35px rgba(220, 38, 38, 0.15);
  }
  .cf-icon {
      width: 60px;
      height: 60px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.75rem;
      color: white;
      flex-shrink: 0;
  }
  .cf-icon--red { background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 8px 20px rgba(220, 38, 38, 0.3); }
  .cf-icon--blue { background: linear-gradient(135deg, #0ea5e9, #3b82f6); box-shadow: 0 8px 20px rgba(14, 165, 233, 0.3); }
  .cf-icon--purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3); }
  .cf-icon--orange { background: linear-gradient(135deg, #f97316, #ea580c); box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3); }
  .cf-icon--green { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3); }
  .cf-text-label { font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin-bottom: 0.25rem; }
  .cf-text-val { font-size: 1.35rem; font-weight: 800; color: #0f172a; line-height: 1.2; }
  </style>

  <!-- QUICK FACTS & ROI (BENTO STYLE) -->
  <section class="section" style="padding-top: 0; margin-top: -50px; position: relative; z-index: 10;">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        
        <div class="country-fact-pill animate-on-scroll">
            <div class="cf-icon cf-icon--blue"><i class="fa-solid fa-chart-line"></i></div>
            <div>
                <div class="cf-text-label">Average Band Score</div>
                <div class="cf-text-val">6.5 to 7</div>
            </div>
        </div>

        <div class="country-fact-pill animate-on-scroll delay-1">
            <div class="cf-icon cf-icon--purple"><i class="fa-solid fa-clock"></i></div>
            <div>
                <div class="cf-text-label">Test Results</div>
                <div class="cf-text-val">2–5 Days</div>
            </div>
        </div>

        <div class="country-fact-pill animate-on-scroll delay-2">
            <div class="cf-icon cf-icon--orange"><i class="fa-solid fa-clipboard-check"></i></div>
            <div>
                <div class="cf-text-label">Mock Scored</div>
                <div class="cf-text-val">100%</div>
            </div>
        </div>

      </div>
    </div>
  </section>

  <!-- 1. COMPLETE IELTS EXAM STRUCTURE SECTION -->
  <section id="exam-structure" class="ielts-structure-section">
    <div class="container">
      
      <div class="text-center animate-on-scroll" style="margin-bottom: 2rem;">
        <span class="section__tag" style="color: var(--IELTS-primary); background: rgba(220, 38, 38, 0.08);">Exam Format & Breakdown</span>
        <h2 class="section__title">Understanding the <span>IELTS Structure</span></h2>
        <p class="section__subtitle" style="max-width: 650px; margin: 0 auto;">The International English Language Testing System (IELTS) assesses your complete English proficiency across all four essential language competencies.</p>
      </div>

      <!-- The Table Matching the User's Image 1 -->
      <div class="ielts-structure-container animate-on-scroll">
        <div class="ielts-structure-header">
          <div>
            <h2>1. Complete IELTS exam structure</h2>
            <p style="color: var(--muted); margin: 0.35rem 0 0; font-size: 0.95rem;">Standard timing and task allocations for Listening, Reading, Writing, and Speaking.</p>
          </div>
          <div class="ielts-total-badge">
            <i class="fa-regular fa-clock"></i> Total Duration: 2 Hours 30 Minutes
          </div>
        </div>

        <div class="ielts-table-responsive">
          <table class="ielts-structure-table">
            <thead>
              <tr>
                <th>Module</th>
                <th>Test duration</th>
                <th>Questions / tasks</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="module-cell">
                    <div class="module-icon-box" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                      <i class="fa-solid fa-headphones"></i>
                    </div>
                    <span>Listening</span>
                  </div>
                </td>
                <td class="duration-cell">
                  <span class="duration-pill"><i class="fa-regular fa-clock" style="color: #3b82f6; margin-right: 0.3rem;"></i> About 30 minutes</span>
                </td>
                <td class="tasks-cell">
                  <span class="tasks-highlight"><i class="fa-solid fa-list-check" style="color: #3b82f6;"></i> 40 questions, 4 parts</span>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="module-cell">
                    <div class="module-icon-box" style="background: linear-gradient(135deg, #10b981, #059669);">
                      <i class="fa-solid fa-book-open"></i>
                    </div>
                    <span>Reading</span>
                  </div>
                </td>
                <td class="duration-cell">
                  <span class="duration-pill"><i class="fa-regular fa-clock" style="color: #10b981; margin-right: 0.3rem;"></i> 60 minutes</span>
                </td>
                <td class="tasks-cell">
                  <span class="tasks-highlight"><i class="fa-solid fa-list-check" style="color: #10b981;"></i> 40 questions, 3 sections</span>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="module-cell">
                    <div class="module-icon-box" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                      <i class="fa-solid fa-pen-nib"></i>
                    </div>
                    <span>Writing</span>
                  </div>
                </td>
                <td class="duration-cell">
                  <span class="duration-pill"><i class="fa-regular fa-clock" style="color: #dc2626; margin-right: 0.3rem;"></i> 60 minutes</span>
                </td>
                <td class="tasks-cell">
                  <span class="tasks-highlight"><i class="fa-solid fa-list-check" style="color: #dc2626;"></i> 2 tasks</span>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="module-cell">
                    <div class="module-icon-box" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                      <i class="fa-solid fa-microphone-lines"></i>
                    </div>
                    <span>Speaking</span>
                  </div>
                </td>
                <td class="duration-cell">
                  <span class="duration-pill"><i class="fa-regular fa-clock" style="color: #8b5cf6; margin-right: 0.3rem;"></i> 11–14 minutes</span>
                </td>
                <td class="tasks-cell">
                  <span class="tasks-highlight"><i class="fa-solid fa-list-check" style="color: #8b5cf6;"></i> 3 parts</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>

  <!-- LEAD FORM & UPCOMING BATCHES SECTION -->
  <section class="section bg-light" style="padding-top: 3.5rem;">
    <div class="container">
       <div class="grid grid--2 align-center gap--4">
           <div class="animate-on-scroll">
               <h2 class="section__title" style="text-align: left;">Start Your <span>IELTS Prep</span> Today</h2>
               <p class="section__subtitle" style="text-align: left; margin-bottom: 2rem;">Join Coimbatore's highest-rated IELTS training batches. Book a free diagnostic assessment and counseling session with our language specialists.</p>

               <?php
               try {
                   $stmtBatch = $pdo->prepare("SELECT * FROM upcoming_batches WHERE course_slug = 'ielts' AND is_active = 1 ORDER BY id DESC");
                   $stmtBatch->execute();
                   $batches = $stmtBatch->fetchAll();
                   
                   if (!empty($batches)):
               ?>
               <div class="upcoming-batches-wrapper animate-on-scroll" style="background: var(--IELTS-primary); border-radius: 24px; padding: 2.5rem; box-shadow: 0 25px 50px rgba(220, 38, 38, 0.25); margin-top: 1rem;">
                   <h3 style="font-size: 1.6rem; font-weight: 800; color: white; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-family: 'Plus Jakarta Sans', sans-serif;"><i class="fa-solid fa-calendar-check"></i> Upcoming IELTS Batches</h3>
                   <div style="display: flex; flex-direction: column; gap: 1rem; max-height: 350px; overflow-y: auto; padding-right: 0.5rem;" class="batch-list-scroll">
                       <?php foreach($batches as $batch): 
                            $statusColor = '#64748b';
                            $s = strtolower($batch['status']);
                            if (strpos($s, 'filling') !== false || strpos($s, 'fast') !== false) $statusColor = '#f59e0b';
                            elseif (strpos($s, 'open') !== false) $statusColor = '#10b981';
                            elseif (strpos($s, 'closed') !== false || strpos($s, 'full') !== false) $statusColor = '#ef4444';
                       ?>
                       <div style="background: white; border-radius: 16px; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                           <div>
                               <div style="font-weight: 800; color: var(--dark); font-size: 1.1rem; margin-bottom: 0.4rem;"><?php echo clean_output($batch['start_date']); ?></div>
                               <div style="color: var(--gray); font-size: 0.9rem; display: flex; gap: 1rem; flex-wrap: wrap; font-weight: 500;">
                                   <span><i class="fa-regular fa-clock" style="color: var(--IELTS-primary);"></i> <?php echo clean_output($batch['batch_time']); ?></span>
                                   <?php if(!empty($batch['duration'])): ?><span><i class="fa-solid fa-hourglass-half" style="color: var(--IELTS-primary);"></i> <?php echo clean_output($batch['duration']); ?></span><?php endif; ?>
                                   <span><i class="fa-solid fa-laptop-house" style="color: var(--IELTS-primary);"></i> <?php echo clean_output($batch['batch_mode']); ?></span>
                               </div>
                           </div>
                           <div>
                               <span style="display: inline-block; padding: 0.4rem 1rem; border-radius: 50px; background: <?php echo $statusColor; ?>15; color: <?php echo $statusColor; ?>; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                                   <?php echo clean_output($batch['status']); ?>
                               </span>
                           </div>
                       </div>
                       <?php endforeach; ?>
                   </div>
               </div>
               <?php 
                   endif;
               } catch(PDOException $e) {}
               ?>
           </div>
           <div class="animate-on-scroll delay-1" style="background: white; border-radius: 24px; padding: 2.5rem; box-shadow: 0 25px 50px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05);">
             <h3 style="font-size: 1.6rem; font-weight: 800; color: var(--dark); margin-bottom: 2rem; font-family: 'Plus Jakarta Sans', sans-serif;">Book a Free Demo Class</h3>
             <form id="heroLeadForm" onsubmit="return handleFormSubmit(event)">
               <input type="hidden" name="form_type" value="course_enquiry">
               <input type="hidden" name="destination" value="IELTS">
               <div style="margin-bottom: 1rem;">
                 <input type="text" name="first_name" placeholder="Full Name" required style="width: 100%; padding: 1rem 1.2rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: var(--dark); font-size: 0.95rem; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--IELTS-primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(220, 38, 38, 0.1)';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
               </div>
               <div style="margin-bottom: 1rem;">
                 <input type="tel" name="phone" placeholder="Phone / WhatsApp Number" required style="width: 100%; padding: 1rem 1.2rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: var(--dark); font-size: 0.95rem; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--IELTS-primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(220, 38, 38, 0.1)';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
               </div>
               <div style="margin-bottom: 1.5rem;">
                 <input type="email" name="email" placeholder="Email Address" required style="width: 100%; padding: 1rem 1.2rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: var(--dark); font-size: 0.95rem; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--IELTS-primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(220, 38, 38, 0.1)';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';">
               </div>
               <button type="submit" class="btn btn--primary" style="width: 100%; background: var(--IELTS-gradient); border: none; padding: 1.1rem; border-radius: 12px; color: white; font-weight: 700; font-size: 1rem; cursor: pointer; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 20px rgba(220, 38, 38, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">Enroll for IELTS Demo</button>
             </form>
           </div>
       </div>
    </div>
  </section>

  <!-- 2. WHY CHOOSE BLUESTONE FOR IELTS (Wave Cards) -->
  <section class="section bg-light">
    <div class="container">
      <div class="text-center animate-on-scroll" style="margin-bottom: 4rem;">
        <span class="section__tag" style="color: var(--IELTS-primary); background: rgba(220, 38, 38, 0.08);">The Bluestone Edge</span>
        <h2 class="section__title">Why Choose <span>Bluestone for IELTS?</span></h2>
        <p class="section__subtitle" style="max-width: 650px; margin: 0 auto;">We combine British Council and IDP certified pedagogy with intensive practice to guarantee substantial band score improvement.</p>
      </div>

      <div class="grid grid--3 gap--2">
        <!-- Card 1 -->
        <div class="IELTS-wave-feature-card tw-theme-1 animate-on-scroll">
          <div class="twf-icon-wrap"><i class="fa-solid fa-chalkboard-user"></i></div>
          <h3 class="twf-title">Certified Mentors</h3>
          <p class="twf-desc">Train directly under British Council & IDP certified faculty who evaluate your performance using official Cambridge band descriptors.</p>
          <div class="twf-bottom">
            <svg class="twf-svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,133.3C672,128,768,160,864,181.3C960,203,1056,213,1152,192C1248,171,1344,117,1392,90.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
          </div>
        </div>
        <!-- Card 2 -->
        <div class="IELTS-wave-feature-card tw-theme-2 animate-on-scroll delay-1">
          <div class="twf-icon-wrap"><i class="fa-solid fa-comments"></i></div>
          <h3 class="twf-title">1-on-1 Speaking Drills</h3>
          <p class="twf-desc">Overcome hesitations with realistic face-to-face mock interviews, personalized cue card coaching, and live accent neutralisation.</p>
          <div class="twf-bottom">
            <svg class="twf-svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,133.3C672,128,768,160,864,181.3C960,203,1056,213,1152,192C1248,171,1344,117,1392,90.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
          </div>
        </div>
        <!-- Card 3 -->
        <div class="IELTS-wave-feature-card tw-theme-3 animate-on-scroll delay-2">
          <div class="twf-icon-wrap"><i class="fa-solid fa-file-signature"></i></div>
          <h3 class="twf-title">Line-by-Line Writing Review</h3>
          <p class="twf-desc">Receive detailed corrections on Task 1 and Task 2 essays focusing on Lexical Resource, Coherence, and Complex Sentence structures.</p>
          <div class="twf-bottom">
            <svg class="twf-svg" viewBox="0 0 1440 320" preserveAspectRatio="none"><path d="M0,160L48,170.7C96,181,192,203,288,192C384,181,480,139,576,133.3C672,128,768,160,864,181.3C960,203,1056,213,1152,192C1248,171,1344,117,1392,90.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. COMPREHENSIVE MODULE BREAKDOWN & DETAILED SYLLABUS SECTION -->
  <section id="curriculum" class="section" style="padding: 6rem 0; background: #f8fafc;">
    <div class="container">
      <div class="text-center animate-on-scroll" style="margin-bottom: 2rem;">
        <span class="section__tag" style="color: var(--IELTS-primary); background: rgba(220, 38, 38, 0.08);">Official Curriculum</span>
        <h2 class="section__title">Complete <span>IELTS Syllabus</span> Breakdown</h2>
        <p class="section__subtitle" style="max-width: 650px; margin: 0 auto;">Master every section with our exhaustive syllabus guide, structured practice topics, and authentic Cambridge patterns.</p>
      </div>

      <div class="syllabus-tab-wrap animate-on-scroll">

        <!-- ===== LEFT NAV ===== -->
        <div class="syllabus-nav">
          <button class="syllabus-nav-btn active" style="--snb-color:#3b82f6; --snb-color2:#1d4ed8;" onclick="showSyllabusPanel('listening', this)">
            <div class="snb-icon"><i class="fa-solid fa-headphones"></i></div>
            <div>
              <div class="snb-text-label">Module 01</div>
              <div class="snb-text-title">Listening</div>
              <div class="snb-text-sub">~30 min · 40 questions</div>
            </div>
          </button>
          <button class="syllabus-nav-btn" style="--snb-color:#10b981; --snb-color2:#059669;" onclick="showSyllabusPanel('reading', this)">
            <div class="snb-icon"><i class="fa-solid fa-book-open"></i></div>
            <div>
              <div class="snb-text-label">Module 02</div>
              <div class="snb-text-title">Reading</div>
              <div class="snb-text-sub">60 min · 40 questions</div>
            </div>
          </button>
          <button class="syllabus-nav-btn" style="--snb-color:#dc2626; --snb-color2:#b91c1c;" onclick="showSyllabusPanel('writing', this)">
            <div class="snb-icon"><i class="fa-solid fa-pen-nib"></i></div>
            <div>
              <div class="snb-text-label">Module 03</div>
              <div class="snb-text-title">Writing</div>
              <div class="snb-text-sub">60 min · 2 tasks</div>
            </div>
          </button>
          <button class="syllabus-nav-btn" style="--snb-color:#8b5cf6; --snb-color2:#7c3aed;" onclick="showSyllabusPanel('speaking', this)">
            <div class="snb-icon"><i class="fa-solid fa-microphone-lines"></i></div>
            <div>
              <div class="snb-text-label">Module 04</div>
              <div class="snb-text-title">Speaking</div>
              <div class="snb-text-sub">11–14 min · 3 parts</div>
            </div>
          </button>
        </div><!-- /.syllabus-nav -->

        <!-- ===== RIGHT PANELS ===== -->
        <div class="syllabus-panels-wrap">

          <!-- PANEL: LISTENING -->
          <div class="syllabus-mod-panel active" id="smp-listening" style="--smp-accent:#3b82f6; --smp-accent-bg:#eff6ff; --smp-accent-border:#bfdbfe;">
            <div class="smp-hero" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
              <div class="smp-hero-left">
                <div class="smp-hero-icon"><i class="fa-solid fa-headphones"></i></div>
                <div class="smp-hero-title">Listening Module</div>
                <div class="smp-hero-desc">Audio recordings played once only, featuring British, Australian and North American accents. Question types span multiple choice, matching, diagram labelling, and sentence completion.</div>
              </div>
              <div class="smp-hero-actions">
                <a class="smp-task-link" href="https://bluestonelanguagehub.com/courses/ielts/listening-tasks" target="_blank" rel="noopener noreferrer">Start Listening Task <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
                <div class="smp-hero-stats">
                  <div class="smp-stat"><span class="smp-stat-val">~30</span><div class="smp-stat-label">Minutes</div></div>
                  <div class="smp-stat"><span class="smp-stat-val">40</span><div class="smp-stat-label">Questions</div></div>
                  <div class="smp-stat"><span class="smp-stat-val">4</span><div class="smp-stat-label">Parts</div></div>
                </div>
              </div>
            </div>
            <div class="smp-card">
              <div class="smp-card-header">
                <h4><i class="fa-solid fa-circle-info" style="color:#3b82f6;"></i> Key Exam Facts</h4>
                <span class="smp-card-badge" style="background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe;">4 Recorded Sections</span>
              </div>
              <div class="smp-key-points">
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text">Recordings played <strong>once only</strong> — no repeat</div></div>
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text">Computer-based: <strong>2 min</strong> review time</div></div>
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text">British, Australian &amp; North American accents</div></div>
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text">Multiple choice, matching, diagram labelling</div></div>
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text">Sentence completion &amp; note/table completion</div></div>
              </div>
            </div>
            <div class="smp-card">
              <div class="smp-card-header">
                <h4><i class="fa-solid fa-headphones" style="color:#3b82f6;"></i> Part-by-Part Syllabus</h4>
                <span class="smp-card-badge" style="background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe;">4 Audio Sections</span>
              </div>
              <div class="smp-table-wrap">
                <table class="smp-table">
                  <thead><tr><th style="width:12%">Part</th><th style="width:44%">Audio context</th><th>Content to practise</th></tr></thead>
                  <tbody>
                    <tr><td class="smp-td-strong">Part 1</td><td>Conversation between two speakers</td><td>Bookings, enquiries, registration, personal details</td></tr>
                    <tr><td class="smp-td-strong">Part 2</td><td>One speaker in a social or everyday context</td><td>Public facilities, tours, events, instructions, announcements</td></tr>
                    <tr><td class="smp-td-strong">Part 3</td><td>Conversation involving students or academic participants</td><td>Assignments, projects, research, opinions and decisions</td></tr>
                    <tr><td class="smp-td-strong">Part 4</td><td>One speaker giving an academic talk</td><td>Lectures, explanations, processes and subject-specific information</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div><!-- /.smp-listening -->

          <!-- PANEL: READING -->
          <div class="syllabus-mod-panel" id="smp-reading" style="--smp-accent:#10b981; --smp-accent-bg:#ecfdf5; --smp-accent-border:#a7f3d0;">
            <div class="smp-hero" style="background: linear-gradient(135deg, #059669, #047857);">
              <div class="smp-hero-left">
                <div class="smp-hero-icon"><i class="fa-solid fa-book-open"></i></div>
                <div class="smp-hero-title">Reading Module</div>
                <div class="smp-hero-desc">Tests a range of reading skills — skimming for gist, scanning for detail, understanding logical argument and recognising the writer's opinions. No extra transfer time; all answers within 60 minutes.</div>
              </div>
              <div class="smp-hero-actions">
                <a class="smp-task-link" href="https://bluestonelanguagehub.com/courses/ielts/reading-tasks" target="_blank" rel="noopener noreferrer">Start Reading Task <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
                <div class="smp-hero-stats">
                  <div class="smp-stat"><span class="smp-stat-val">60</span><div class="smp-stat-label">Minutes</div></div>
                  <div class="smp-stat"><span class="smp-stat-val">40</span><div class="smp-stat-label">Questions</div></div>
                  <div class="smp-stat"><span class="smp-stat-val">3</span><div class="smp-stat-label">Sections</div></div>
                </div>
              </div>
            </div>
            <div class="smp-card">
              <div class="smp-card-header">
                <h4><i class="fa-solid fa-circle-info" style="color:#10b981;"></i> Key Exam Facts</h4>
                <span class="smp-card-badge" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0;">Question Types</span>
              </div>
              <div class="smp-key-points">
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text">True / False / Not Given</div></div>
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text">Yes / No / Not Given</div></div>
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text">Matching headings &amp; information</div></div>
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text">Summary &amp; note completion</div></div>
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text">Multiple choice questions</div></div>
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text">Sentence endings matching</div></div>
              </div>
            </div>
            <div class="smp-card">
              <div class="smp-card-header">
                <h4><i class="fa-solid fa-book-open-reader" style="color:#10b981;"></i> Academic vs General Training</h4>
                <span class="smp-card-badge" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0;">Comparative Structure</span>
              </div>
              <div class="smp-table-wrap">
                <table class="smp-table">
                  <thead><tr><th style="width:22%">Feature</th><th style="width:39%">Academic</th><th>General Training</th></tr></thead>
                  <tbody>
                    <tr><td class="smp-td-strong">Sections</td><td>3 sections</td><td>3 sections</td></tr>
                    <tr><td class="smp-td-strong">Text style</td><td>Academic &amp; general-interest prose</td><td>Everyday, workplace &amp; general texts</td></tr>
                    <tr><td class="smp-td-strong">Section 1</td><td>Passage(s) on academic or general topic</td><td>Everyday notices, ads, schedules, services</td></tr>
                    <tr><td class="smp-td-strong">Section 2</td><td>Further academic reading</td><td>Workplace documents &amp; practical info</td></tr>
                    <tr><td class="smp-td-strong">Section 3</td><td>Longer, more complex text</td><td>Longer general-interest text</td></tr>
                    <tr><td class="smp-td-strong">Questions</td><td>40</td><td>40</td></tr>
                    <tr><td class="smp-td-strong">Duration</td><td>60 minutes</td><td>60 minutes</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
            <section class="art-section" aria-labelledby="art-reading-title">
              <div class="art-header">
                <h3 id="art-reading-title"><i class="fa-solid fa-book-open" style="color:#059669;"></i> Reading Practice Tasks</h3>
                <button class="art-toggle-btn" id="art-toggle-btn" type="button" aria-expanded="false" aria-controls="art-practice-body" onclick="toggleARTasks()">
                  <i class="fa-solid fa-list-check"></i> <span class="art-toggle-label">Open Practice Tasks</span> <i class="fa-solid fa-chevron-down"></i>
                </button>
              </div>
              <div class="art-practice-body" id="art-practice-body" style="display:none;">
                <p class="art-intro">Practise scanning for details, identifying stated information and completing answers from short passages. Read each text first, then check your answers.</p>
                <div class="art-tabs" role="tablist" aria-label="Reading practice topics">
                  <button class="art-tab active" id="art-tab-routine" type="button" role="tab" aria-selected="true" aria-controls="art-panel-routine" onclick="showARTask('routine', this)">Daily Routines</button>
                  <button class="art-tab" id="art-tab-photography" type="button" role="tab" aria-selected="false" aria-controls="art-panel-photography" onclick="showARTask('photography', this)">Photography</button>
                  <button class="art-tab" id="art-tab-places" type="button" role="tab" aria-selected="false" aria-controls="art-panel-places" onclick="showARTask('places', this)">Public Places</button>
                  <button class="art-tab" id="art-tab-opinions" type="button" role="tab" aria-selected="false" aria-controls="art-panel-opinions" onclick="showARTask('opinions', this)">Changing Opinions</button>
                </div>

                <div class="art-panel active" id="art-panel-routine" role="tabpanel" aria-labelledby="art-tab-routine">
                  <h4>Task 1: Daily routines · Sentence completion</h4>
                  <div class="art-passage"><strong>A routine that works</strong><br>People often assume that a productive morning begins very early. However, researchers who study daily habits say consistency matters more than the hour a person wakes up. A short plan made the night before can reduce the number of small decisions people face in the morning. Some workers prepare breakfast and clothes in advance, while others reserve the first part of the day for their most demanding task. The best routine is usually one that can be repeated without creating unnecessary pressure. When work or family responsibilities change, routines may need to change too.</div>
                  <p><strong>Complete the sentences using no more than TWO words from the passage.</strong></p>
                  <form class="art-task-form" onsubmit="checkARTask(event)">
                    <ol class="art-questions">
                      <li class="art-question">Researchers say that ________ is more important than waking up early.
                        <input class="art-answer-input" type="text" data-answer="consistency" aria-label="Your answer for question 1" autocomplete="off">
                        <span class="art-question-result"></span>
                      </li>
                      <li class="art-question">A plan prepared the previous evening can reduce morning ________.
                        <input class="art-answer-input" type="text" data-answer="decisions" aria-label="Your answer for question 2" autocomplete="off">
                        <span class="art-question-result"></span>
                      </li>
                      <li class="art-question">A useful routine should not create unnecessary ________.
                        <input class="art-answer-input" type="text" data-answer="pressure" aria-label="Your answer for question 3" autocomplete="off">
                        <span class="art-question-result"></span>
                      </li>
                    </ol>
                    <button class="art-check-btn" type="submit">Check answers</button>
                    <p class="art-task-feedback" role="status" aria-live="polite" hidden></p>
                  </form>
                </div>

                <div class="art-panel" id="art-panel-photography" role="tabpanel" aria-labelledby="art-tab-photography" hidden>
                  <h4>Task 2: Photography · True / False / Not Given</h4>
                  <div class="art-passage"><strong>Photographs and memory</strong><br>Taking photographs can help people remember events, but the effect depends on how they use a camera. In one study, visitors who photographed selected objects later remembered fewer visual details than visitors who simply observed them. Yet participants who chose particular details to photograph remembered those details well. The researchers suggested that active decisions, such as selecting a viewpoint, may encourage closer attention. They did not conclude that photography always weakens memory. The study focused on a museum visit, so its findings may not apply equally to every social occasion.</div>
                  <p><strong>Write TRUE, FALSE or NOT GIVEN.</strong></p>
                  <form class="art-task-form" onsubmit="checkARTask(event)">
                    <ol class="art-questions">
                      <li class="art-question">Every visitor in the study was asked to photograph all the objects.
                        <input class="art-answer-input" type="text" data-answer="false" aria-label="Your answer for question 1" autocomplete="off">
                        <span class="art-question-result"></span>
                      </li>
                      <li class="art-question">Some participants remembered the details they had deliberately photographed.
                        <input class="art-answer-input" type="text" data-answer="true" aria-label="Your answer for question 2" autocomplete="off">
                        <span class="art-question-result"></span>
                      </li>
                      <li class="art-question">The researchers tested how photographs affected memory at family celebrations.
                        <input class="art-answer-input" type="text" data-answer="not given" aria-label="Your answer for question 3" autocomplete="off">
                        <span class="art-question-result"></span>
                      </li>
                    </ol>
                    <button class="art-check-btn" type="submit">Check answers</button>
                    <p class="art-task-feedback" role="status" aria-live="polite" hidden></p>
                  </form>
                </div>

                <div class="art-panel" id="art-panel-places" role="tabpanel" aria-labelledby="art-tab-places" hidden>
                  <h4>Task 3: Public places · Matching information</h4>
                  <div class="art-passage"><strong>Three ways to improve a town square</strong><br><strong>A.</strong> In a number of towns, planners have added trees and shaded seating to central squares. These changes make the spaces more comfortable during warm weather and encourage people to stay longer.<br><br><strong>B.</strong> Other councils have invited local groups to organise markets, performances and seasonal events. Regular activities can bring visitors into the town centre and give residents reasons to meet.<br><br><strong>C.</strong> Some projects have focused on access. Ramps, smooth paths and clearer signs help people with mobility needs, families with pushchairs and visitors unfamiliar with the area.</div>
                  <p><strong>Which paragraph contains the following information? Write A, B or C.</strong></p>
                  <form class="art-task-form" onsubmit="checkARTask(event)">
                    <ol class="art-questions">
                      <li class="art-question">Improvements that make a space easier to navigate for different visitors.
                        <input class="art-answer-input" type="text" data-answer="c" aria-label="Your answer for question 1" autocomplete="off">
                        <span class="art-question-result"></span>
                      </li>
                      <li class="art-question">Changes intended to provide comfort in hot conditions.
                        <input class="art-answer-input" type="text" data-answer="a" aria-label="Your answer for question 2" autocomplete="off">
                        <span class="art-question-result"></span>
                      </li>
                      <li class="art-question">Activities designed to bring the community together.
                        <input class="art-answer-input" type="text" data-answer="b" aria-label="Your answer for question 3" autocomplete="off">
                        <span class="art-question-result"></span>
                      </li>
                    </ol>
                    <button class="art-check-btn" type="submit">Check answers</button>
                    <p class="art-task-feedback" role="status" aria-live="polite" hidden></p>
                  </form>
                </div>

                <div class="art-panel" id="art-panel-opinions" role="tabpanel" aria-labelledby="art-tab-opinions" hidden>
                  <h4>Task 4: Changing opinions · Yes / No / Not Given</h4>
                  <div class="art-passage"><strong>Why people revise their views</strong><br>Changing an opinion is sometimes treated as a sign of uncertainty, but psychologists point out that beliefs can develop as people encounter new evidence. A person may reconsider a view after a conversation, a course of study or direct experience. The process is easier when people can examine information without feeling that their identity is under attack. Researchers also note that simply presenting more facts does not guarantee a change of mind; trust in the source and the way evidence is discussed can matter. The researchers did not suggest that people should abandon their principles whenever someone disagrees with them.</div>
                  <p><strong>Do the statements agree with the writer's claims? Write YES, NO or NOT GIVEN.</strong></p>
                  <form class="art-task-form" onsubmit="checkARTask(event)">
                    <ol class="art-questions">
                      <li class="art-question">Psychologists believe that opinions can develop when people encounter new evidence.
                        <input class="art-answer-input" type="text" data-answer="yes" aria-label="Your answer for question 1" autocomplete="off">
                        <span class="art-question-result"></span>
                      </li>
                      <li class="art-question">The passage says that giving someone more facts will always change their mind.
                        <input class="art-answer-input" type="text" data-answer="no" aria-label="Your answer for question 2" autocomplete="off">
                        <span class="art-question-result"></span>
                      </li>
                      <li class="art-question">According to the passage, trust in the information source may influence whether a view changes.
                        <input class="art-answer-input" type="text" data-answer="yes" aria-label="Your answer for question 3" autocomplete="off">
                        <span class="art-question-result"></span>
                      </li>
                    </ol>
                    <button class="art-check-btn" type="submit">Check answers</button>
                    <p class="art-task-feedback" role="status" aria-live="polite" hidden></p>
                  </form>
                </div>
              </div>
            </section>
          </div><!-- /.smp-reading -->

          <!-- PANEL: WRITING -->
          <div class="syllabus-mod-panel" id="smp-writing" style="--smp-accent:#dc2626; --smp-accent-bg:#fef2f2; --smp-accent-border:#fecaca;">
            <div class="smp-hero" style="background: linear-gradient(135deg, #dc2626, #991b1b);">
              <div class="smp-hero-left">
                <div class="smp-hero-icon"><i class="fa-solid fa-pen-nib"></i></div>
                <div class="smp-hero-title">Writing Module</div>
                <div class="smp-hero-desc">Two tasks assessed on Task Achievement/Response, Coherence &amp; Cohesion, Lexical Resource, and Grammatical Range &amp; Accuracy. Task 2 carries double the weight of Task 1.</div>
              </div>
              <div class="smp-hero-actions">
                <a class="smp-task-link" href="https://bluestonelanguagehub.com/courses/ielts/writing-tasks" target="_blank" rel="noopener noreferrer">Start Writing Task <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
                <div class="smp-hero-stats">
                  <div class="smp-stat"><span class="smp-stat-val">60</span><div class="smp-stat-label">Minutes</div></div>
                  <div class="smp-stat"><span class="smp-stat-val">2</span><div class="smp-stat-label">Tasks</div></div>
                  <div class="smp-stat"><span class="smp-stat-val">400+</span><div class="smp-stat-label">Min Words</div></div>
                </div>
              </div>
            </div>
            <!-- Task overview mini-cards -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
              <div class="smp-card" style="margin-bottom:0;">
                <div class="smp-card-header">
                  <h4><i class="fa-solid fa-chart-line" style="color:#dc2626;"></i> Task 1</h4>
                  <span class="smp-card-badge" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">20 min · 150 words</span>
                </div>
                <div style="padding:1.25rem 1.75rem; color:#475569; font-size:0.93rem; line-height:1.65;">
                  <strong style="color:#0f172a;">Academic:</strong> Describe, summarise or compare a graph, chart, table, map or process diagram.<br><br>
                  <strong style="color:#0f172a;">General Training:</strong> Write a formal, semi-formal or informal letter.
                </div>
              </div>
              <div class="smp-card" style="margin-bottom:0;">
                <div class="smp-card-header">
                  <h4><i class="fa-solid fa-file-lines" style="color:#dc2626;"></i> Task 2</h4>
                  <span class="smp-card-badge" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">40 min · 250 words</span>
                </div>
                <div style="padding:1.25rem 1.75rem; color:#475569; font-size:0.93rem; line-height:1.65;">
                  Present a well-structured discursive essay in response to a point of view, argument or contemporary issue. <strong style="color:#0f172a;">Worth double the marks of Task 1.</strong>
                </div>
              </div>
            </div>
            <!-- Academic Task 1 Interactive UI -->
            <div class="awt1-section" id="academic-writing-task1">
              <div class="awt1-header">
                <h3><i class="fa-solid fa-chart-line" style="color:#dc2626;"></i>&nbsp; A. Academic Writing Task 1 — Chart Types</h3>
                <span class="syllabus-time-pill"><i class="fa-regular fa-clock"></i> Recommended: 20 minutes</span>
              </div>
              <p class="awt1-intro">Students describe, summarise and compare visual information without giving unsupported opinions.</p>
              <div class="awt1-btn-row">
                <button class="awt1-btn active" style="--awt1-color:#dc2626;" onclick="showAWT1Panel('line-graph2', this)"><span class="awt1-num">1</span> Line graph</button>
                <button class="awt1-btn" style="--awt1-color:#f97316;" onclick="showAWT1Panel('bar-chart2', this)"><span class="awt1-num">2</span> Bar chart</button>
                <button class="awt1-btn" style="--awt1-color:#8b5cf6;" onclick="showAWT1Panel('pie-charts2', this)"><span class="awt1-num">3</span> Pie charts</button>
                <button class="awt1-btn" style="--awt1-color:#0ea5e9;" onclick="showAWT1Panel('tables2', this)"><span class="awt1-num">4</span> Tables</button>
                <button class="awt1-btn" style="--awt1-color:#10b981;" onclick="showAWT1Panel('mixed-charts2', this)"><span class="awt1-num">5</span> Mixed charts</button>
                <button class="awt1-btn" style="--awt1-color:#f59e0b;" onclick="showAWT1Panel('maps2', this)"><span class="awt1-num">6</span> Maps</button>
                <button class="awt1-btn" style="--awt1-color:#6366f1;" onclick="showAWT1Panel('process-diagrams2', this)"><span class="awt1-num">7</span> Process diagrams</button>
              </div>
              <div class="awt1-panels">
                <div class="awt1-panel active" id="awt1-line-graph2" style="--awt1-color:#dc2626; --awt1-panel-bg1:#fef2f2; --awt1-panel-bg2:#fecaca;"><div class="awt1-card-inner"><div class="awt1-card-thumb"><i class="fa-solid fa-chart-line awt1-chart-icon"></i></div><div class="awt1-card-body"><div class="awt1-card-time"><i class="fa-regular fa-clock"></i> Recommended: 20 minutes</div><div class="awt1-card-title"><span class="awt1-title-dot"></span>1. Line Graph</div><p class="awt1-desc-text">Describe trends and changes over time using appropriate language for movements and comparisons.</p><ul class="awt1-bullet-list"><li>Trends over time</li><li>Fluctuations and stability</li><li>Peaks and lowest points</li><li>Comparisons between series</li><li>Starting and ending values</li></ul></div></div></div>
                <div class="awt1-panel" id="awt1-bar-chart2" style="--awt1-color:#f97316; --awt1-panel-bg1:#fff7ed; --awt1-panel-bg2:#fed7aa;"><div class="awt1-card-inner"><div class="awt1-card-thumb"><i class="fa-solid fa-chart-bar awt1-chart-icon"></i></div><div class="awt1-card-body"><div class="awt1-card-time"><i class="fa-regular fa-clock"></i> Recommended: 20 minutes</div><div class="awt1-card-title"><span class="awt1-title-dot"></span>2. Bar Chart</div><p class="awt1-desc-text">Compare categories and groups using clear, precise language for quantities and differences.</p><ul class="awt1-bullet-list"><li>Comparing categories</li><li>Highest and lowest figures</li><li>Differences between groups</li><li>Changes across periods</li></ul></div></div></div>
                <div class="awt1-panel" id="awt1-pie-charts2" style="--awt1-color:#8b5cf6; --awt1-panel-bg1:#f5f3ff; --awt1-panel-bg2:#ddd6fe;"><div class="awt1-card-inner"><div class="awt1-card-thumb"><i class="fa-solid fa-chart-pie awt1-chart-icon"></i></div><div class="awt1-card-body"><div class="awt1-card-time"><i class="fa-regular fa-clock"></i> Recommended: 20 minutes</div><div class="awt1-card-title"><span class="awt1-title-dot"></span>3. Pie Charts</div><p class="awt1-desc-text">Describe proportions and shares within a whole, often comparing two distributions side by side.</p><ul class="awt1-bullet-list"><li>Proportions and percentages</li><li>Major and minor shares</li><li>Comparing two or more distributions</li><li>Changes in composition</li></ul></div></div></div>
                <div class="awt1-panel" id="awt1-tables2" style="--awt1-color:#0ea5e9; --awt1-panel-bg1:#f0f9ff; --awt1-panel-bg2:#bae6fd;"><div class="awt1-card-inner"><div class="awt1-card-thumb"><i class="fa-solid fa-table awt1-chart-icon"></i></div><div class="awt1-card-body"><div class="awt1-card-time"><i class="fa-regular fa-clock"></i> Recommended: 20 minutes</div><div class="awt1-card-title"><span class="awt1-title-dot"></span>4. Tables</div><p class="awt1-desc-text">Select and compare numerical data, identifying the most significant figures and trends.</p><ul class="awt1-bullet-list"><li>Comparing numerical information</li><li>Identifying significant differences</li><li>Selecting key figures</li><li>Grouping related data</li></ul></div></div></div>
                <div class="awt1-panel" id="awt1-mixed-charts2" style="--awt1-color:#10b981; --awt1-panel-bg1:#f0fdf4; --awt1-panel-bg2:#a7f3d0;"><div class="awt1-card-inner"><div class="awt1-card-thumb"><i class="fa-solid fa-chart-mixed awt1-chart-icon"></i></div><div class="awt1-card-body"><div class="awt1-card-time"><i class="fa-regular fa-clock"></i> Recommended: 20 minutes</div><div class="awt1-card-title"><span class="awt1-title-dot"></span>5. Mixed Charts</div><p class="awt1-desc-text">Interpret two different chart types together, identifying shared trends and linking data accurately.</p><ul class="awt1-bullet-list"><li>Interpreting two chart types together</li><li>Identifying common trends</li><li>Comparing related variables</li><li>Combining information accurately</li></ul></div></div></div>
                <div class="awt1-panel" id="awt1-maps2" style="--awt1-color:#f59e0b; --awt1-panel-bg1:#fffbeb; --awt1-panel-bg2:#fde68a;"><div class="awt1-card-inner"><div class="awt1-card-thumb"><i class="fa-solid fa-map-location-dot awt1-chart-icon"></i></div><div class="awt1-card-body"><div class="awt1-card-time"><i class="fa-regular fa-clock"></i> Recommended: 20 minutes</div><div class="awt1-card-title"><span class="awt1-title-dot"></span>6. Maps</div><p class="awt1-desc-text">Describe changes between two maps of the same location, using direction and spatial language precisely.</p><ul class="awt1-bullet-list"><li>Describing past and present layouts</li><li>Identifying construction and demolition</li><li>Describing changes in land use</li><li>Using location and direction language</li></ul></div></div></div>
                <div class="awt1-panel" id="awt1-process-diagrams2" style="--awt1-color:#6366f1; --awt1-panel-bg1:#eef2ff; --awt1-panel-bg2:#c7d2fe;"><div class="awt1-card-inner"><div class="awt1-card-thumb"><i class="fa-solid fa-diagram-project awt1-chart-icon"></i></div><div class="awt1-card-body"><div class="awt1-card-time"><i class="fa-regular fa-clock"></i> Recommended: 20 minutes</div><div class="awt1-card-title"><span class="awt1-title-dot"></span>7. Process Diagrams</div><p class="awt1-desc-text">Sequence steps in a natural or man-made process clearly, using passive voice and appropriate connectors.</p><ul class="awt1-bullet-list"><li>Describing stages in sequence</li><li>Natural and man-made processes</li><li>Active and passive voice</li><li>Sequencing and cause-effect relationships</li></ul></div></div></div>
              </div>
            </div><!-- /.awt1-section -->
            <!-- B. GT Task 1 -->
            <div class="smp-card" style="margin-top:1.5rem;">
              <div class="smp-card-header">
                <h4><i class="fa-solid fa-envelope-open-text" style="color:#dc2626;"></i> B. General Training Writing Task 1 — Letter Types</h4>
                <span class="smp-card-badge" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">20 min · 150 words</span>
              </div>
              <div class="smp-table-wrap">
                <table class="smp-table">
                  <thead><tr><th style="width:25%">Letter Type</th><th>Common Purposes</th></tr></thead>
                  <tbody>
                    <tr><td class="smp-td-strong">Formal</td><td>Complaints, applications, requests to organisations</td></tr>
                    <tr><td class="smp-td-strong">Semi-formal</td><td>Writing to a landlord, manager, teacher or colleague</td></tr>
                    <tr><td class="smp-td-strong">Informal</td><td>Writing to a friend or relative</td></tr>
                  </tbody>
                </table>
              </div>
              <div class="gt-letter-practice">
                <div class="gt-letter-practice-heading">
                  <h5><i class="fa-solid fa-pen-to-square" style="color:#dc2626;"></i> General Training Letter Questions</h5>
                  <span>Write at least 150 words for each task</span>
                </div>
                <details class="gt-letter-question">
                  <summary><span>Question 1 – Formal Letter</span><i class="fa-solid fa-chevron-down"></i></summary>
                  <div class="gt-letter-prompt">
                    <span class="gt-letter-label">Writing</span>
                    <p>You recently purchased a household appliance from a local shop, but it stopped working after a few days.</p>
                    <p>Write a letter to the shop manager. In your letter:</p>
                    <ul><li>Explain what you purchased and when.</li><li>Describe the problem with the appliance.</li><li>Say what you would like the manager to do.</li></ul>
                    <strong>Write at least 150 words.</strong>
                  </div>
                </details>
                <details class="gt-letter-question">
                  <summary><span>Question 2 – Semi-formal Letter</span><i class="fa-solid fa-chevron-down"></i></summary>
                  <div class="gt-letter-prompt">
                    <span class="gt-letter-label">Writing</span>
                    <p>You are planning to organise a small celebration at your home and would like to invite your neighbour.</p>
                    <p>Write a letter to your neighbour. In your letter:</p>
                    <ul><li>Explain the reason for the celebration.</li><li>Describe the arrangements you are making.</li><li>Ask whether your neighbour can help you with anything.</li></ul>
                    <strong>Write at least 150 words.</strong>
                  </div>
                </details>
                <details class="gt-letter-question">
                  <summary><span>Question 3 – Informal Letter</span><i class="fa-solid fa-chevron-down"></i></summary>
                  <div class="gt-letter-prompt">
                    <span class="gt-letter-label">Writing</span>
                    <p>A friend from another city is planning to visit your hometown for the first time.</p>
                    <p>Write a letter to your friend. In your letter:</p>
                    <ul><li>Suggest the best time to visit.</li><li>Recommend some places to see.</li><li>Explain how you can help your friend during the visit.</li></ul>
                    <strong>Write at least 150 words.</strong>
                  </div>
                </details>
                <details class="gt-letter-question">
                  <summary><span>Question 4 – Complaint Letter</span><i class="fa-solid fa-chevron-down"></i></summary>
                  <div class="gt-letter-prompt">
                    <span class="gt-letter-label">Writing</span>
                    <p>You recently travelled by bus to another city. The bus was delayed for several hours, and no clear information was provided to passengers.</p>
                    <p>Write a letter to the bus company. In your letter:</p>
                    <ul><li>Explain when and where you travelled.</li><li>Describe the problems you experienced.</li><li>Suggest what the company should do to improve its service.</li></ul>
                    <strong>Write at least 150 words.</strong>
                  </div>
                </details>
              </div>
            </div>
            <!-- C. Task 2 Essay Types -->
            <div class="smp-card" style="margin-top:1.5rem;">
              <div class="smp-card-header">
                <h4><i class="fa-solid fa-file-lines" style="color:#dc2626;"></i> C. Writing Task 2 — Essay Types</h4>
                <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
                  <span class="smp-card-badge" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">40 min · 250 words</span>
                  <button class="awt2-toggle-btn" id="awt2-toggle-btn" onclick="toggleAWT2Topics()">
                    <i class="fa-solid fa-pen-to-square"></i> Practice Essay Questions (15) <i class="fa-solid fa-chevron-down awt2-toggle-chevron"></i>
                  </button>
                </div>
              </div>
              <div class="smp-table-wrap">
                <table class="smp-table">
                  <thead><tr><th style="width:35%">Essay Category</th><th>Main Skills</th></tr></thead>
                  <tbody>
                    <tr><td class="smp-td-strong">Opinion (Agree/Disagree)</td><td>Agreeing, disagreeing or presenting a qualified position</td></tr>
                    <tr><td class="smp-td-strong">Discuss Both Views</td><td>Explaining two perspectives and giving your own opinion</td></tr>
                    <tr><td class="smp-td-strong">Advantages &amp; Disadvantages</td><td>Developing benefits and drawbacks with clear examples</td></tr>
                    <tr><td class="smp-td-strong">Outweigh Essay</td><td>Evaluating which side is more significant overall</td></tr>
                    <tr><td class="smp-td-strong">Problem &amp; Solution</td><td>Identifying causes, effects and practical remedies</td></tr>
                    <tr><td class="smp-td-strong">Causes &amp; Solutions</td><td>Explaining reasons behind an issue and proposing measures</td></tr>
                    <tr><td class="smp-td-strong">Two-part Question</td><td>Responding fully and equally to both questions</td></tr>
                    <tr><td class="smp-td-strong">Direct Question Essay</td><td>Answering specific questions with relevant support</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- D. Practice Essay Topics by Set (hidden by default, toggled by button) -->
            <div class="awt2-topics-section" id="awt2-topics-main" style="display:none; margin-top:1rem;">
              <p class="awt2-practice-intro">Choose a topic set and open a question to see the complete task prompt. Plan your response, address every part of the question, and write at least 250 words within 40 minutes.</p>

              <!-- Topic Set Tabs -->
              <div class="awt2-set-tabs">
                <button class="awt2-set-tab active" onclick="switchAWT2Set('education', this)"><i class="fa-solid fa-graduation-cap"></i> Education</button>
                <button class="awt2-set-tab" onclick="switchAWT2Set('technology', this)"><i class="fa-solid fa-microchip"></i> Technology</button>
                <button class="awt2-set-tab" onclick="switchAWT2Set('work', this)"><i class="fa-solid fa-briefcase"></i> Work &amp; Lifestyle</button>
                <button class="awt2-set-tab" onclick="switchAWT2Set('environment', this)"><i class="fa-solid fa-leaf"></i> Environment</button>
                <button class="awt2-set-tab" onclick="switchAWT2Set('health', this)"><i class="fa-solid fa-heart-pulse"></i> Health &amp; Society</button>
              </div>

              <!-- SET 1: Education -->
              <div class="awt2-set-panel active" id="awt2-education">
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q1</span><span class="awt2-q-type awt2-type-opinion">Agree or Disagree</span></div>
                  <div class="awt2-q-preview">Should schools focus more on practical skills than traditional academic subjects?</div>
                  <div class="awt2-q-prompt">Some people believe that schools should focus more on teaching practical skills, such as managing money and communication, rather than traditional academic subjects.<br><br><em>To what extent do you agree or disagree?</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q2</span><span class="awt2-q-type awt2-type-discuss">Discuss Both Views</span></div>
                  <div class="awt2-q-preview">Should university education be free for all students?</div>
                  <div class="awt2-q-prompt">Some people think that university education should be free for everyone, regardless of their financial background. Others believe that students should pay for their own education.<br><br><em>Discuss both views and give your own opinion.</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q3</span><span class="awt2-q-type awt2-type-adv">Advantages &amp; Disadvantages</span></div>
                  <div class="awt2-q-preview">More students are choosing to study online rather than attend classes in person.</div>
                  <div class="awt2-q-prompt">An increasing number of students are choosing to study online rather than attend classes in person.<br><br><em>Do the advantages of this development outweigh the disadvantages?</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
              </div>

              <!-- SET 2: Technology -->
              <div class="awt2-set-panel" id="awt2-technology">
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q4</span><span class="awt2-q-type awt2-type-opinion">Agree or Disagree</span></div>
                  <div class="awt2-q-preview">Will artificial intelligence replace many jobs currently performed by humans?</div>
                  <div class="awt2-q-prompt">Artificial intelligence is likely to replace many jobs currently performed by humans.<br><br><em>To what extent do you agree or disagree?</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q5</span><span class="awt2-q-type awt2-type-discuss">Discuss Both Views</span></div>
                  <div class="awt2-q-preview">Has social media improved or weakened real-life relationships?</div>
                  <div class="awt2-q-prompt">Some people believe that social media has improved communication between people, while others argue that it has weakened real-life relationships.<br><br><em>Discuss both views and give your own opinion.</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q6</span><span class="awt2-q-type awt2-type-causes">Causes &amp; Solutions</span></div>
                  <div class="awt2-q-preview">Young adults spend too much time on digital devices, affecting daily life negatively.</div>
                  <div class="awt2-q-prompt">Many people, particularly young adults, spend an increasing amount of time using digital devices, which can negatively affect their daily lives.<br><br><em>What are the reasons for this? What measures can be taken to address this problem?</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
              </div>

              <!-- SET 3: Work & Lifestyle -->
              <div class="awt2-set-panel" id="awt2-work">
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q7</span><span class="awt2-q-type awt2-type-opinion">Agree or Disagree</span></div>
                  <div class="awt2-q-preview">Is working from home better for both employees and employers?</div>
                  <div class="awt2-q-prompt">Working from home has become increasingly popular in recent years. Some people believe it benefits both employees and employers, while others disagree.<br><br><em>To what extent do you agree or disagree?</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q8</span><span class="awt2-q-type awt2-type-two">Two-Part Question</span></div>
                  <div class="awt2-q-preview">Why do many people struggle to maintain a healthy work-life balance?</div>
                  <div class="awt2-q-prompt">Many people today find it difficult to balance their work and personal lives.<br><br><em>Why is it becoming harder to maintain a healthy work-life balance? What can individuals and governments do to address this?</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q9</span><span class="awt2-q-type awt2-type-adv">Advantages &amp; Disadvantages</span></div>
                  <div class="awt2-q-preview">Some people choose to retire early. What are the advantages and disadvantages?</div>
                  <div class="awt2-q-prompt">An increasing number of people are choosing to retire early, in their 40s or 50s, rather than working until the traditional retirement age.<br><br><em>What are the advantages and disadvantages of retiring early?</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
              </div>

              <!-- SET 4: Environment -->
              <div class="awt2-set-panel" id="awt2-environment">
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q10</span><span class="awt2-q-type awt2-type-opinion">Agree or Disagree</span></div>
                  <div class="awt2-q-preview">Should individuals be responsible for protecting the environment, not governments?</div>
                  <div class="awt2-q-prompt">Some people believe that individuals should be responsible for protecting the environment, while others think governments and large corporations should take the lead.<br><br><em>To what extent do you agree or disagree?</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q11</span><span class="awt2-q-type awt2-type-causes">Causes &amp; Solutions</span></div>
                  <div class="awt2-q-preview">Air pollution in cities is a growing problem. What are the causes and possible solutions?</div>
                  <div class="awt2-q-prompt">Air pollution levels in many cities around the world continue to rise, causing serious health and environmental problems.<br><br><em>What are the main causes of air pollution in cities? What measures could be taken to reduce it?</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q12</span><span class="awt2-q-type awt2-type-discuss">Discuss Both Views</span></div>
                  <div class="awt2-q-preview">Nuclear energy vs. renewable energy — which is a better solution to climate change?</div>
                  <div class="awt2-q-prompt">Some experts argue that nuclear energy is the best solution to reducing carbon emissions, while others believe that investment in renewable sources like solar and wind is more effective.<br><br><em>Discuss both views and give your own opinion.</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
              </div>

              <!-- SET 5: Health & Society -->
              <div class="awt2-set-panel" id="awt2-health">
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q13</span><span class="awt2-q-type awt2-type-opinion">Agree or Disagree</span></div>
                  <div class="awt2-q-preview">Should governments ban all forms of advertising for unhealthy food and drinks?</div>
                  <div class="awt2-q-prompt">The rise in obesity rates globally has prompted debate about whether governments should ban advertising for unhealthy food and beverages, particularly those targeting children.<br><br><em>To what extent do you agree or disagree?</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q14</span><span class="awt2-q-type awt2-type-two">Two-Part Question</span></div>
                  <div class="awt2-q-preview">Why are mental health issues increasing, especially among young people?</div>
                  <div class="awt2-q-prompt">Mental health problems, including anxiety and depression, are becoming increasingly common, particularly among young people.<br><br><em>What are the main reasons for this trend? What can be done to improve mental health outcomes?</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
                <div class="awt2-q-card" onclick="toggleAWT2Q(this)">
                  <div class="awt2-q-meta"><span class="awt2-q-num">Q15</span><span class="awt2-q-type awt2-type-adv">Advantages &amp; Disadvantages</span></div>
                  <div class="awt2-q-preview">Living in a multi-cultural society — what are the advantages and disadvantages?</div>
                  <div class="awt2-q-prompt">Many countries today are becoming increasingly multicultural as a result of immigration and globalisation.<br><br><em>What are the advantages and disadvantages of living in a multicultural society?</em></div>
                  <div class="awt2-q-toggle"><i class="fa-solid fa-chevron-down"></i> Show Prompt</div>
                </div>
              </div>

            </div><!-- /.awt2-topics-section -->

          </div><!-- /.smp-writing -->

          <!-- PANEL: SPEAKING -->
          <div class="syllabus-mod-panel" id="smp-speaking" style="--smp-accent:#8b5cf6; --smp-accent-bg:#f5f3ff; --smp-accent-border:#ddd6fe;">
            <div class="smp-hero" style="background: linear-gradient(135deg, #7c3aed, #6d28d9);">
              <div class="smp-hero-left">
                <div class="smp-hero-icon"><i class="fa-solid fa-microphone-lines"></i></div>
                <div class="smp-hero-title">Speaking Module</div>
                <div class="smp-hero-desc">Face-to-face oral interview with a certified Cambridge/IDP examiner (or HD video call). Assesses fluency, grammatical accuracy, lexical variety, and natural pronunciation. Identical scoring for Academic and General Training.</div>
              </div>
              <div class="smp-hero-actions">
                <a class="smp-task-link" href="https://bluestonelanguagehub.com/courses/ielts/speaking-tasks" target="_blank" rel="noopener noreferrer">Start Speaking Task <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
                <div class="smp-hero-stats">
                  <div class="smp-stat"><span class="smp-stat-val">11–14</span><div class="smp-stat-label">Minutes</div></div>
                  <div class="smp-stat"><span class="smp-stat-val">3</span><div class="smp-stat-label">Parts</div></div>
                  <div class="smp-stat"><span class="smp-stat-val">1:1</span><div class="smp-stat-label">Interview</div></div>
                </div>
              </div>
            </div>
            <div class="smp-card">
              <div class="smp-card-header">
                <h4><i class="fa-solid fa-star" style="color:#8b5cf6;"></i> Scoring Criteria</h4>
                <span class="smp-card-badge" style="background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe;">4 Band Descriptors</span>
              </div>
              <div class="smp-key-points">
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text"><strong>Fluency &amp; Coherence</strong> — speaks without long pauses, ideas well-linked</div></div>
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text"><strong>Lexical Resource</strong> — varied and precise vocabulary use</div></div>
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text"><strong>Grammatical Range &amp; Accuracy</strong> — complex structures used correctly</div></div>
                <div class="smp-kp-item"><div class="smp-kp-dot"></div><div class="smp-kp-text"><strong>Pronunciation</strong> — clear, intelligible and natural delivery</div></div>
              </div>
            </div>
            <div class="smp-card">
              <div class="smp-card-header">
                <h4><i class="fa-solid fa-microphone-lines" style="color:#8b5cf6;"></i> Speaking Syllabus — 3-Part Structure</h4>
                <span class="smp-card-badge" style="background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe;">1-on-1 Interview</span>
              </div>
              <div class="smp-speaking-timeline">
                <div class="smp-tl-step">
                  <div class="smp-tl-left">
                    <div class="smp-tl-bubble" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);">01</div>
                    <div class="smp-tl-line"></div>
                  </div>
                  <div class="smp-tl-content">
                    <div class="smp-tl-title">Part 1 – Introduction &amp; Interview <span class="smp-tl-time-tag">4–5 min</span></div>
                    <p class="smp-tl-desc">Topics include hometown, work, studies, family, hobbies, food, weather, technology, transport, daily routine, free time and so on.</p>
                  </div>
                </div>
                <div class="smp-tl-step">
                  <div class="smp-tl-left">
                    <div class="smp-tl-bubble" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);">02</div>
                    <div class="smp-tl-line"></div>
                  </div>
                  <div class="smp-tl-content">
                    <div class="smp-tl-title">Part 2 – Individual Long Turn <span class="smp-tl-time-tag">3–4 min</span></div>
                    <ul class="smp-tl-bullets">
                      <li>One cue card</li>
                      <li>1 minute to prepare</li>
                      <li>Speak for up to 2 minutes</li>
                      <li>Describe a person, place, event, object, experience, activity and so on.</li>
                    </ul>
                  </div>
                </div>
                <div class="smp-tl-step">
                  <div class="smp-tl-left">
                    <div class="smp-tl-bubble" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);">03</div>
                    <div class="smp-tl-line"></div>
                  </div>
                  <div class="smp-tl-content">
                    <div class="smp-tl-title">Part 3 – Discussion <span class="smp-tl-time-tag">4–5 min</span></div>
                    <p class="smp-tl-desc">Abstract questions related to the Part 2 topic, requiring explanations, comparisons, examples, opinions, predictions, discussion of social changes and so on.</p>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- /.smp-speaking -->

        </div><!-- /.syllabus-panels-wrap -->
      </div><!-- /.syllabus-tab-wrap -->
    </div>
  </section>




  <!-- 4. TARGET AUDIENCE (Split Layout) -->
  <section class="section" style="background: linear-gradient(to right, #f8fafc, #ffffff); padding: 8rem 0; overflow: hidden;">
    <div class="container">
      <div class="grid grid--2 gap--4" style="align-items: flex-start;">
        
        <!-- Left Sticky Column -->
        <div class="audience-left animate-on-scroll" style="position: sticky; top: 120px;">
          <span class="section__tag" style="color: var(--IELTS-primary); background: rgba(220, 38, 38, 0.08);">Who Needs IELTS?</span>
          <h2 class="section__title" style="text-align: left; margin-bottom: 1.5rem;">Who is this <span>training for?</span></h2>
          <p class="section__subtitle" style="text-align: left; max-width: 100%; margin-bottom: 3rem;">Whether you are applying for top universities abroad or seeking permanent residency, IELTS is the gold standard of international English evaluation recognized by 140+ countries.</p>
          
          <div style="position: relative; border-radius: 30px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.08);">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(220, 38, 38, 0.1), rgba(14, 165, 233, 0.1)); z-index: 1;"></div>
            <img src="assets/images/ielts_audience_3d.png" alt="IELTS Target Audience" onerror="this.src='assets/images/img.png'" style="width: 100%; display: block; position: relative; z-index: 2; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
          </div>
        </div>
        
        <!-- Right Cards Column -->
        <div class="audience-right animate-on-scroll delay-1" style="display: flex; flex-direction: column; gap: 2rem;">
          
          <!-- Card 1 -->
          <div class="IELTS-split-card" style="background: linear-gradient(135deg, #fef2f2, #fee2e2); border: 1px solid rgba(220, 38, 38, 0.2); box-shadow: 0 10px 30px rgba(220, 38, 38, 0.05);">
            <div style="width: 60px; height: 60px; border-radius: 16px; background: white; color: var(--IELTS-primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 5px 15px rgba(220, 38, 38, 0.1);">
              <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
              <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif;">Study Abroad Aspirants</h3>
              <p style="color: var(--gray); line-height: 1.6; font-size: 1rem; margin-bottom: 1rem;">Undergraduate and postgraduate applicants heading to top universities in the UK, Canada, Australia, the USA, Ireland, and New Zealand requiring Band 6.5 to 7.5+.</p>
              <span style="display: inline-block; padding: 0.4rem 1rem; background: white; color: var(--IELTS-primary); font-weight: 600; font-size: 0.85rem; border-radius: 50px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">IELTS Academic</span>
            </div>
          </div>

          <!-- Card 2 -->
          <div class="IELTS-split-card" style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border: 1px solid rgba(14, 165, 233, 0.2); box-shadow: 0 10px 30px rgba(14, 165, 233, 0.05);">
            <div style="width: 60px; height: 60px; border-radius: 16px; background: white; color: #0ea5e9; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 5px 15px rgba(14, 165, 233, 0.1);">
              <i class="fa-solid fa-passport"></i>
            </div>
            <div>
              <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif;">Immigration & PR Seekers</h3>
              <p style="color: var(--gray); line-height: 1.6; font-size: 1rem; margin-bottom: 1rem;">Professionals applying for Canada Express Entry (targeting CLB 9/10: 8-7-7-7) or Australian Skilled Migration requiring maximum visa point allocation.</p>
              <span style="display: inline-block; padding: 0.4rem 1rem; background: white; color: #0ea5e9; font-weight: 600; font-size: 0.85rem; border-radius: 50px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">IELTS General Training</span>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="IELTS-split-card" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 1px solid rgba(16, 185, 129, 0.2); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.05);">
            <div style="width: 60px; height: 60px; border-radius: 16px; background: white; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 5px 15px rgba(16, 185, 129, 0.1);">
              <i class="fa-solid fa-user-doctor"></i>
            </div>
            <div>
              <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif;">Nurses & Healthcare Professionals</h3>
              <p style="color: var(--gray); line-height: 1.6; font-size: 1rem; margin-bottom: 1rem;">Nurses, pharmacists, and medical practitioners securing license registrations with the UK Nursing and Midwifery Council (NMC) or Australian AHPRA.</p>
              <span style="display: inline-block; padding: 0.4rem 1rem; background: white; color: #10b981; font-weight: 600; font-size: 0.85rem; border-radius: 50px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">Medical Licensure</span>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- 5. CALL TO ACTION -->
  <section class="section" style="padding-top: 2rem;">
    <div class="container animate-on-scroll">
      <div style="background: var(--IELTS-gradient); padding: 4.5rem 2.5rem; border-radius: 30px; text-align: center; color: white; box-shadow: 0 20px 45px rgba(220, 38, 38, 0.3);">
        <h2 style="font-size: 2.6rem; margin-bottom: 1rem; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif;">Ready to Secure Band 7.5+ in IELTS?</h2>
        <p style="font-size: 1.15rem; opacity: 0.95; max-width: 650px; margin: 0 auto 2.5rem; line-height: 1.7;">Join hundreds of Bluestone Overseas students who have unlocked admissions to top universities and secured permanent residency worldwide.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="consultation.php" class="btn btn--white btn--lg pulse-btn" style="background: white; color: var(--IELTS-primary); font-weight: 800;">Book Free Consultation</a>
            <a href="test-prep.php" class="btn btn--outline btn--lg cta-explore-btn" style="border-color: rgba(255,255,255,0.4); color: white;">Explore All Courses</a>
        </div>
      </div>
    </div>
  </section>


<style>
/* ===== AWT2 TOGGLE BUTTON ===== */
.awt2-toggle-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  padding: 0.45rem 1rem;
  background: linear-gradient(135deg, #dc2626, #b91c1c);
  color: white;
  border: none;
  border-radius: 50px;
  font-size: 0.83rem;
  font-weight: 700;
  cursor: pointer;
  font-family: 'Plus Jakarta Sans', sans-serif;
  transition: all 0.25s ease;
  box-shadow: 0 4px 12px rgba(220,38,38,0.25);
  white-space: nowrap;
}
.awt2-toggle-btn:hover {
  background: linear-gradient(135deg, #b91c1c, #991b1b);
  box-shadow: 0 6px 18px rgba(220,38,38,0.35);
  transform: translateY(-1px);
}

/* CTA Explore All Courses button */
.cta-explore-btn {
  transition: all 0.25s ease;
}
.cta-explore-btn:hover {
  background: white !important;
  color: var(--IELTS-primary) !important;
  border-color: white !important;
}

.awt2-toggle-chevron {
  transition: transform 0.3s ease;
}
.awt2-toggle-btn.open .awt2-toggle-chevron {
  transform: rotate(180deg);
}

.gt-letter-practice { padding: 1.4rem 1.75rem 1.6rem; }
.gt-letter-practice-heading {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 0.75rem;
  margin-bottom: 1rem;
}
.gt-letter-practice-heading h5 {
  margin: 0;
  color: #0f172a;
  font: 800 1rem 'Plus Jakarta Sans', sans-serif;
}
.gt-letter-practice-heading > span { color: #64748b; font-size: 0.88rem; font-weight: 600; }
.gt-letter-question {
  margin-top: 0.75rem;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  background: #fff;
  overflow: hidden;
}
.gt-letter-question summary {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 1rem 1.15rem;
  color: #0f172a;
  font-weight: 700;
  cursor: pointer;
  list-style: none;
}
.gt-letter-question summary::-webkit-details-marker { display: none; }
.gt-letter-question summary i { color: #dc2626; transition: transform 0.2s ease; }
.gt-letter-question[open] summary { border-bottom: 1px solid #fee2e2; background: #fffafa; }
.gt-letter-question[open] summary i { transform: rotate(180deg); }
.gt-letter-prompt {
  margin: 1rem 1.15rem 1.15rem;
  padding: 1.1rem 1.2rem;
  border: 1px solid #e5e7eb;
  border-radius: 16px;
  color: #1f2937;
  line-height: 1.7;
}
.gt-letter-label { color: #64748b; font-size: 0.9rem; }
.gt-letter-prompt p { margin: 0.75rem 0; }
.gt-letter-prompt ul { margin: 0.5rem 0 0.75rem; padding-left: 1.5rem; }
.awt2-practice-intro { margin: 0; padding: 1.1rem 1.75rem 0; color: #475569; line-height: 1.65; }

/* ===== AWT2 PRACTICE TOPICS =====*/
.awt2-topics-section {
  margin-top: 1.5rem;
  background: white;
  border-radius: 20px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 8px 24px rgba(0,0,0,0.04);
  overflow: hidden;
}
.awt2-topics-header {
  padding: 1.25rem 1.75rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.75rem;
  border-bottom: 1px solid #f1f5f9;
  background: #fafbfc;
}
.awt2-topics-header h4 {
  font-size: 1.1rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
  font-family: 'Plus Jakarta Sans', sans-serif;
  display: flex;
  align-items: center;
  gap: 0.6rem;
}
/* Set tabs */
.awt2-set-tabs {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
  padding: 1rem 1.75rem;
  border-bottom: 1px solid #f1f5f9;
  background: #f8fafc;
}
.awt2-set-tab {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.5rem 1rem;
  border-radius: 50px;
  font-size: 0.85rem;
  font-weight: 700;
  color: #64748b;
  background: white;
  border: 2px solid #e2e8f0;
  cursor: pointer;
  transition: all 0.25s ease;
  font-family: 'Plus Jakarta Sans', sans-serif;
}
.awt2-set-tab:hover {
  border-color: #dc2626;
  color: #dc2626;
  background: #fef2f2;
}
.awt2-set-tab.active {
  background: #dc2626;
  border-color: #dc2626;
  color: white;
  box-shadow: 0 4px 12px rgba(220,38,38,0.25);
}
/* Set panels */
.awt2-set-panel { display: none; padding: 1.5rem 1.75rem; }
.awt2-set-panel.active { display: block; animation: smpFadeIn 0.35s ease; }
/* Question cards */
.awt2-q-card {
  background: #f8fafc;
  border: 1.5px solid #e2e8f0;
  border-radius: 14px;
  padding: 1.1rem 1.4rem;
  margin-bottom: 0.85rem;
  cursor: pointer;
  transition: all 0.25s ease;
  position: relative;
}
.awt2-q-card:hover {
  border-color: #dc2626;
  background: white;
  box-shadow: 0 6px 20px rgba(220,38,38,0.07);
}
.awt2-q-card.open {
  border-color: #dc2626;
  background: white;
  box-shadow: 0 8px 24px rgba(220,38,38,0.08);
}
.awt2-q-meta {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  margin-bottom: 0.5rem;
}
.awt2-q-num {
  font-size: 0.78rem;
  font-weight: 800;
  color: #94a3b8;
  font-family: 'Plus Jakarta Sans', sans-serif;
  letter-spacing: 0.06em;
}
.awt2-q-type {
  font-size: 0.78rem;
  font-weight: 700;
  padding: 0.2rem 0.7rem;
  border-radius: 50px;
}
.awt2-type-opinion  { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
.awt2-type-discuss  { background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; }
.awt2-type-adv      { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; }
.awt2-type-causes   { background:#fff7ed; color:#ea580c; border:1px solid #fed7aa; }
.awt2-type-two      { background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe; }
.awt2-q-preview {
  font-size: 0.95rem;
  font-weight: 600;
  color: #0f172a;
  line-height: 1.5;
  margin-bottom: 0;
}
.awt2-q-prompt {
  display: none;
  margin-top: 0.85rem;
  padding: 1rem 1.2rem;
  background: linear-gradient(135deg, #fef2f2, #fff8f8);
  border-left: 3px solid #dc2626;
  border-radius: 10px;
  font-size: 0.93rem;
  color: #334155;
  line-height: 1.7;
}
.awt2-q-card.open .awt2-q-prompt { display: block; animation: smpFadeIn 0.3s ease; }
.awt2-q-toggle {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  margin-top: 0.75rem;
  font-size: 0.82rem;
  font-weight: 700;
  color: #dc2626;
  transition: all 0.25s ease;
}
.awt2-q-card.open .awt2-q-toggle i { transform: rotate(180deg); }
.awt2-q-toggle i { transition: transform 0.3s ease; }
.awt2-q-card.open .awt2-q-toggle::after { content: 'Hide Prompt'; }
.awt2-q-card:not(.open) .awt2-q-toggle::after { content: ''; }
</style>

<script>
function showAWT1Panel(panelId, btn) {
  var row = btn.closest('.awt1-btn-row');
  row.querySelectorAll('.awt1-btn').forEach(function(b) { b.classList.remove('active'); });
  var section = btn.closest('.awt1-section');
  section.querySelectorAll('.awt1-panel').forEach(function(p) { p.classList.remove('active'); });
  btn.classList.add('active');
  var panel = section.querySelector('#awt1-' + panelId);
  if (panel) {
    panel.classList.add('active');
    var color = btn.style.getPropertyValue('--awt1-color');
    panel.style.setProperty('--awt1-color', color);
  }
}

function showSyllabusPanel(panelId, btn) {
  document.querySelectorAll('.syllabus-nav-btn').forEach(function(b) { b.classList.remove('active'); });
  document.querySelectorAll('.syllabus-mod-panel').forEach(function(p) { p.classList.remove('active'); });
  btn.classList.add('active');
  var panel = document.getElementById('smp-' + panelId);
  if (panel) { panel.classList.add('active'); }
}

function checkARTask(event) {
  event.preventDefault();
  var form = event.currentTarget;
  var inputs = Array.from(form.querySelectorAll('.art-answer-input'));
  var feedback = form.querySelector('.art-task-feedback');

  form.querySelectorAll('.art-question').forEach(function(question) {
    question.classList.remove('is-correct', 'is-incorrect');
    question.querySelector('.art-question-result').textContent = '';
  });

  var unanswered = inputs.find(function(input) { return input.value.trim() === ''; });
  if (unanswered) {
    feedback.textContent = 'Please answer all ' + inputs.length + ' questions before checking.';
    feedback.hidden = false;
    unanswered.focus();
    return;
  }

  var score = 0;
  inputs.forEach(function(input) {
    var question = input.closest('.art-question');
    var expected = input.dataset.answer.trim().toLowerCase().replace(/\s+/g, ' ');
    var actual = input.value.trim().toLowerCase().replace(/\s+/g, ' ');
    var isCorrect = actual === expected;
    var answerLabel = input.dataset.answer;
    if (/^(true|false|not given|yes|no|[a-c])$/i.test(answerLabel)) {
      answerLabel = answerLabel.toUpperCase();
    }

    question.classList.add(isCorrect ? 'is-correct' : 'is-incorrect');
    question.querySelector('.art-question-result').textContent = isCorrect
      ? 'Correct! Answer: ' + answerLabel
      : 'Not quite. Correct answer: ' + answerLabel;
    if (isCorrect) { score += 1; }
  });

  feedback.textContent = 'You got ' + score + ' out of ' + inputs.length + ' correct.';
  feedback.hidden = false;
}

function toggleARTasks() {
  var body = document.getElementById('art-practice-body');
  var button = document.getElementById('art-toggle-btn');
  if (!body || !button) return;
  var isOpen = body.style.display !== 'none';
  body.style.display = isOpen ? 'none' : 'block';
  button.setAttribute('aria-expanded', String(!isOpen));
  button.querySelector('.art-toggle-label').textContent = isOpen ? 'Open Practice Tasks' : 'Close Practice Tasks';
}

function showARTask(taskId, btn) {
  var section = btn.closest('.art-section');
  section.querySelectorAll('.art-tab').forEach(function(tab) {
    var isSelected = tab === btn;
    tab.classList.toggle('active', isSelected);
    tab.setAttribute('aria-selected', String(isSelected));
  });
  section.querySelectorAll('.art-panel').forEach(function(panel) {
    var isSelected = panel.id === 'art-panel-' + taskId;
    panel.classList.toggle('active', isSelected);
    panel.hidden = !isSelected;
  });
}

function switchAWT2Set(setId, btn) {
  var section = btn.closest('.awt2-topics-section');
  section.querySelectorAll('.awt2-set-tab').forEach(function(t) { t.classList.remove('active'); });
  section.querySelectorAll('.awt2-set-panel').forEach(function(p) { p.classList.remove('active'); });
  btn.classList.add('active');
  var panel = document.getElementById('awt2-' + setId);
  if (panel) { panel.classList.add('active'); }
}

function toggleAWT2Q(card) {
  var isOpen = card.classList.contains('open');
  var allCards = card.closest('.awt2-set-panel').querySelectorAll('.awt2-q-card');
  allCards.forEach(function(c) { c.classList.remove('open'); });
  if (!isOpen) { card.classList.add('open'); }
}

function toggleAWT2Topics() {
  var panel = document.getElementById('awt2-topics-main');
  var btn   = document.getElementById('awt2-toggle-btn');
  if (!panel) return;
  var isOpen = panel.style.display !== 'none';
  if (isOpen) {
    panel.style.display = 'none';
    btn.classList.remove('open');
  } else {
    panel.style.display = 'block';
    btn.classList.add('open');
    // Smooth scroll into view
    setTimeout(function() { panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }, 50);
  }
}
</script>


</main>

<?php require_once 'includes/footer.php'; ?>
