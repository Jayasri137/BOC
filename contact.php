<?php
require_once 'includes/config.php';
$pageTitle = 'Contact Us | Bluestone Overseas Consultants';
$pageDesc = 'UG Programs Abroad – expert guidance on courses, universities, applications and visas for students planning overseas education. Get personalised support.';
$pageKeywords = 'UK Education Consultants in Coimbatore, Australia Education Consultants in Coimbatore, New Zealand Education Consultants in Coimbatore, UG Programs Abroad, PG Programs Abroad, Study Abroad Consultants in Coimbatore, IELTS Coaching in Coimbatore, IELTS classes in Coimbatore, Best IELTS Coaching in Coimbatore, IELTS Training in Coimbatore, German language course, Japanese language course, German language classes, Japanese language classes, German Language Course in Coimbatore, Japanese Language Course in Coimbatore, German Language Training Centre in Coimbatore, Japanese Language Training Centre in Coimbatore, Postgraduate study in UK, Postgraduate study in Australia, Postgraduate study in New Zealand, Undergraduate study in Australia, Undergraduate study in UK, Undergraduate study in New Zealand, Postgraduate Study in UK – Coimbatore, Postgraduate Study in Australia – Coimbatore, Undergraduate Study in UK – Coimbatore, Undergraduate Study in Australia – Coimbatore, Postgraduate Study in New Zealand – Coimbatore, Undergraduate Study in New Zealand – Coimbatore';
$pageHeroImage = 'assets/images/cont.png';
require_once 'includes/header.php';
?>
<main>

<section class="section contact-section" style="background: #ffffff;">
  <div class="container">
    <div class="contact-grid">
      <div>
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem;">Talk to Our <span class="text-gradient">Experts</span></h1>
        <p>Whether you&rsquo;re just starting your study abroad journey or need help with a visa application, our counsellors are here to help — for free.</p>
        <style>
          .contact-cards-premium {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
          }
          .c-card-p {
            background: #ffffff;
            border-radius: 16px;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0.75rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
          }
          .c-card-p:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
          }
          .c-card-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #ffffff;
            flex-shrink: 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 0.5rem;
          }
          .c-card-p h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #0f172a;
          }
          .c-card-p a, .c-card-p p {
            color: #475569;
            font-size: 0.95rem;
            text-decoration: none;
            margin: 0;
            line-height: 1.5;
          }
          .c-card-p a:hover {
            color: #3b82f6;
          }
          @media (max-width: 640px) {
            .contact-cards-premium {
              grid-template-columns: 1fr;
            }
          }
        </style>
        <div class="contact-cards-premium">
          <div class="c-card-p">
            <div class="c-card-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);"><i class="fa-solid fa-phone"></i></div>
            <div><h4>Call Us</h4><a href="tel:+919342899904">+91 93428 99904</a></div>
          </div>
          <div class="c-card-p">
            <div class="c-card-icon" style="background: linear-gradient(135deg, #ec4899, #be185d);"><i class="fa-solid fa-envelope"></i></div>
            <div><h4>Email Us</h4><a href="mailto:info@bluestoneocs.com">info@bluestoneocs.com</a></div>
          </div>
          <div class="c-card-p">
            <div class="c-card-icon" style="background: linear-gradient(135deg, #14b8a6, #0d9488);"><i class="fa-regular fa-clock"></i></div>
            <div><h4>Working Hours</h4><p>Mon–Fri: 09:00 AM – 6:30 PM</p></div>
          </div>
          <div class="c-card-p">
            <div class="c-card-icon" style="background: linear-gradient(135deg, #22c55e, #16a34a);"><i class="fa-brands fa-whatsapp"></i></div>
            <div><h4>WhatsApp</h4><a href="https://wa.me/919342899904" target="_blank">Chat with us instantly</a></div>
          </div>
        </div>
        <a href="<?= SITE_MAP_LINK ?>" target="_blank" style="display:block;margin-top:2rem;padding:1.5rem;background:linear-gradient(135deg,rgba(14,165,233,.08),rgba(139,92,246,.08));border-radius:var(--radius);border:1px solid rgba(14,165,233,.15);text-decoration:none;color:inherit;transition:transform 0.3s ease,border-color 0.3s ease;" class="hover-scale-card">
          <h4 style="margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;"><i class="fa-solid fa-location-dot" style="color:var(--primary)"></i> Head Office – Coimbatore</h4>
          <p style="font-size:.875rem;color:var(--gray);line-height:1.7">Renaissance Terrace, NO.126L, 2nd Floor,<br>Opp. Bishop Appasamy College,<br>Coimbatore, Tamil Nadu - 641018</p>
        </a>

        <!-- Google Maps Embed -->
        <div style="margin-top:1.5rem;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow);border:1px solid rgba(0,0,0,0.06);height:280px;position:relative;">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3916.457619491774!2d76.97578759999999!3d11.004251499999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba8f79532fbd2a7%3A0xecfa1d86f9485eb7!2sBluestone%20Overseas%20Consultants!5e0!3m2!1sen!2sin!4v1779183697198!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
      <style>
        .contact-form-premium {
          background: #ffffff;
          padding: 3rem;
          border-radius: 24px;
          box-shadow: 0 20px 50px rgba(0,0,0,0.08);
          border: 1px solid rgba(0,0,0,0.04);
          position: relative;
          overflow: hidden;
        }
        .contact-form-premium::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 8px;
          background: linear-gradient(90deg, #3b82f6, #ec4899);
        }
        .contact-form-premium h3 {
          font-size: 2.2rem;
          margin-bottom: 0.5rem;
          color: #0f172a;
          font-weight: 800;
        }
        .contact-form-premium p {
          color: #64748b;
          margin-bottom: 2rem;
          font-size: 1.05rem;
        }
        .cf-group-p {
          margin-bottom: 1.5rem;
        }
        .cf-group-p label {
          display: block;
          font-size: 0.95rem;
          font-weight: 600;
          color: #334155;
          margin-bottom: 0.5rem;
        }
        .cf-group-p input,
        .cf-group-p select,
        .cf-group-p textarea {
          width: 100%;
          padding: 1rem 1.25rem;
          border-radius: 12px;
          border: 1px solid #cbd5e1;
          background: #f8fafc;
          font-size: 1rem;
          color: #1e293b;
          transition: all 0.3s ease;
          font-family: inherit;
        }
        .cf-group-p input:focus,
        .cf-group-p select:focus,
        .cf-group-p textarea:focus {
          outline: none;
          border-color: #3b82f6;
          background: #ffffff;
          box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        .cf-grid-p {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 1.5rem;
        }
        @media (max-width: 640px) {
          .cf-grid-p { grid-template-columns: 1fr; gap: 0; }
          .contact-form-premium { padding: 2.5rem 1.5rem; }
        }
        .btn-submit-premium {
          background: linear-gradient(135deg, #3b82f6, #2563eb);
          color: white;
          border: none;
          padding: 1.25rem 2rem;
          font-size: 1.1rem;
          font-weight: 700;
          border-radius: 12px;
          cursor: pointer;
          width: 100%;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 0.5rem;
          transition: transform 0.3s ease, box-shadow 0.3s ease;
          box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }
        .btn-submit-premium:hover {
          transform: translateY(-3px);
          box-shadow: 0 15px 30px rgba(59, 130, 246, 0.4);
        }
      </style>
      <div class="contact-form-premium animate-on-scroll">
        <h3>Send Us a <span class="text-gradient">Message</span></h3>
        <p>Fill in your details and we will get back to you within 24 hours.</p>
        <form id="contactForm" onsubmit="return handleFormSubmit(event)">
          <input type="hidden" name="form_type" value="contact">
          <div class="cf-grid-p">
            <div class="cf-group-p"><label>First Name *</label><input type="text" name="first_name" placeholder="John" required></div>
            <div class="cf-group-p"><label>Last Name *</label><input type="text" name="last_name" placeholder="Doe" required></div>
          </div>
          <div class="cf-grid-p">
            <div class="cf-group-p"><label>Email *</label><input type="email" name="email" placeholder="john@email.com" required></div>
            <div class="cf-group-p"><label>Phone *</label><input type="tel" name="phone" placeholder="+91 98765 43210" required></div>
          </div>
          <div class="cf-group-p"><label>Preferred Country</label>
            <select name="destination"><option value="">Select Country</option><option>USA</option><option>UK</option><option>Canada</option><option>Australia</option><option>Germany</option><option>Ireland</option><option>New Zealand</option><option>Singapore</option><option>Other</option></select>
          </div>
          <div class="cf-group-p"><label>Your Query</label>
            <textarea name="query" rows="4" placeholder="Tell us about your study abroad plans, your academic background, or any specific questions..."></textarea>
          </div>
          <button type="submit" class="btn-submit-premium">
            <i class="fa-solid fa-paper-plane"></i> Send Message
          </button>
        </form>
      </div>
    </div>
  </div>
</section>
<!-- BRANCHES GRID -->
<section class="section">
  <div class="container">
    <div class="section__header animate-on-scroll">
      <span class="section__tag">Our Locations</span>
      <h2 class="section__title">Find a Branch <span>Near You</span></h2>
      <div class="accent-bar"></div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem">
    <?php
    $all_branches = [];
    try {
        $stmt = $pdo->query("SELECT * FROM branches WHERE is_active = 1 ORDER BY id ASC");
        $all_branches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}

    $icons = ['fa-building', 'fa-map-location-dot', 'fa-city', 'fa-store', 'fa-location-crosshairs', 'fa-building-columns', 'fa-mountain-city', 'fa-tree-city'];
    $palettes = [
        ['#0ea5e9', '#2563eb', 'rgba(37, 99, 235, 0.05)', '#2563eb', 'rgba(37, 99, 235, 0.2)'], // Blue
        ['#f43f5e', '#e11d48', 'rgba(225, 29, 72, 0.05)', '#e11d48', 'rgba(225, 29, 72, 0.2)'], // Rose
        ['#f59e0b', '#d97706', 'rgba(217, 119, 6, 0.05)', '#d97706', 'rgba(217, 119, 6, 0.2)'], // Amber
        ['#10b981', '#059669', 'rgba(5, 150, 105, 0.05)', '#059669', 'rgba(5, 150, 105, 0.2)'], // Emerald
        ['#8b5cf6', '#6d28d9', 'rgba(109, 40, 217, 0.05)', '#6d28d9', 'rgba(109, 40, 217, 0.2)'], // Purple
        ['#06b6d4', '#0891b2', 'rgba(8, 145, 178, 0.05)', '#0891b2', 'rgba(8, 145, 178, 0.2)'], // Cyan
        ['#f97316', '#ea580c', 'rgba(234, 88, 12, 0.05)', '#ea580c', 'rgba(234, 88, 12, 0.2)'], // Orange
        ['#14b8a6', '#0f766e', 'rgba(15, 118, 110, 0.05)', '#0f766e', 'rgba(15, 118, 110, 0.2)'], // Teal
    ];

    if (empty($all_branches)) {
        // Fallback array simulating DB structure
        $all_branches = [
            ['city' => 'Coimbatore', 'icon' => 'fa-building', 'badge' => '(HQ)', 'address' => 'Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College, Coimbatore, TN 641018'],
            ['city' => 'Chennai', 'icon' => 'fa-city', 'badge' => '', 'address' => 'No.13, Velachery Main Road, Mailai Balaji Nagar, Pallikaranai, Chennai - 600100'],
            ['city' => 'Salem', 'icon' => 'fa-store', 'badge' => '', 'address' => '9.3/14, Vettukadu, Konganapuram PO, Edappadi TK, Salem, TN 637102'],
            ['city' => 'Erode', 'icon' => 'fa-location-crosshairs', 'badge' => '', 'address' => 'No1, Vairam Street, Municipal Colony, Near Arasan Eye Hospital, Erode - 638004, TN'],
            ['city' => 'Namakkal', 'icon' => 'fa-building-columns', 'badge' => '', 'address' => '53/17, Second Floor, Paramathi Main Road, S.P. Pudur, Namakkal - 637001'],
            ['city' => 'Tirunelveli', 'icon' => 'fa-map-location-dot', 'badge' => '', 'address' => 'No.160/5, First Floor, Apollo Pharmacy Upstairs, Thoothukudi Main Road, KTC Nagar, Tirunelveli - 627011'],
            ['city' => 'Nepal', 'icon' => 'fa-mountain-city', 'badge' => 'Intl', 'address' => 'MCVG+V9R Hongkong Bazzar, Bharatpur 44207, Nepal'],
            ['city' => 'Canada', 'icon' => 'fa-tree-city', 'badge' => 'Intl', 'address' => '30 Denton Ave Unit No:214, Toronto, ON M1L 4P2, Canada'],
        ];
    }

    foreach($all_branches as $index => $row): 
      $b = htmlspecialchars($row['city']);
      $db_icon = !empty($row['icon']) ? htmlspecialchars($row['icon']) : '';
      $icon = $db_icon ? $db_icon : $icons[$index % count($icons)];
      $address = !empty($row['address']) ? htmlspecialchars($row['address']) : 'Visit our '.$b.' branch.';
      $c = $palettes[$index % count($palettes)];
    ?>
      <div class="branch-card animate-on-scroll delay-<?= $index % 4 ?>" style="background: var(--white); border-radius: 20px; padding: 2.5rem 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04); position: relative; overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); display: flex; flex-direction: column; gap: 1rem; align-items: flex-start; z-index: 1;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.03)';">
        <!-- Decorative background shape -->
        <div style="position: absolute; top: 0; right: 0; width: 120px; height: 120px; background: linear-gradient(135deg, <?= $c[0] ?>, <?= $c[1] ?>); opacity: 0.05; border-radius: 50%; transform: translate(30%, -30%); z-index: -1;"></div>
        
        <div style="width: 60px; height: 60px; border-radius: 16px; background: linear-gradient(135deg, <?= $c[0] ?>, <?= $c[1] ?>); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 0.5rem; box-shadow: 0 10px 20px <?= $c[4] ?>;">
          <i class="fa-solid <?= $icon ?>"></i>
        </div>
        
        <h4 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.4rem; font-weight: 800; color: var(--dark); margin: 0; letter-spacing: -0.02em;"><?= $b ?> Branch <?= !empty($row['badge']) ? '<span style="font-size:0.8rem; color:'.$c[1].';">'.$row['badge'].'</span>' : '' ?></h4>
        
        <p style="font-size: 0.95rem; color: var(--gray); margin: 0; line-height: 1.6;"><i class="fa-solid fa-location-dot" style="margin-right:0.4rem; color:<?= $c[1] ?>;"></i> <?= $address ?></p>
        
        <div style="display: flex; gap: 1rem; margin-top: auto; padding-top: 1.5rem; width: 100%;">
          <a href="tel:+919342899904" style="flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1rem; background: <?= $c[2] ?>; color: <?= $c[3] ?>; font-weight: 700; font-size: 0.9rem; border-radius: 10px; transition: all 0.3s ease; text-decoration: none;" onmouseover="this.style.background='<?= $c[3] ?>'; this.style.color='white';" onmouseout="this.style.background='<?= $c[2] ?>'; this.style.color='<?= $c[3] ?>';">
            <i class="fa-solid fa-phone"></i> Call
          </a>
          <a href="branch.php?b=<?= strtolower(str_replace(' ', '-', $b)) ?>" style="flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; color: var(--dark); font-weight: 700; font-size: 0.9rem; border-radius: 10px; transition: all 0.3s ease; text-decoration: none;" onmouseover="this.style.borderColor='<?= $c[1] ?>'; this.style.color='<?= $c[1] ?>';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='var(--dark)';">
            Details <i class="fa-solid fa-arrow-right" style="font-size: 0.8rem;"></i>
          </a>
        </div>
      </div>
    <?php endforeach; ?>
    </div>
  </div>
</section>
</main>
<?php require_once 'includes/footer.php'; ?>
