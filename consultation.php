<?php
require_once 'includes/config.php';
$pageTitle = 'Get FREE Counselling Today! | Bluestone Overseas';

// Fetch countries and branches for dropdowns
$countries = [];
$branches = ['Coimbatore', 'Chennai', 'Salem', 'Erode', 'Namakkal', 'Tirunelveli']; // Hardcoded from footer
try {
    $stmt = $pdo->prepare("SELECT name FROM countries WHERE is_active = 1 ORDER BY name ASC");
    $stmt->execute();
    $countries = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <style>
    :root {
      --primary: #0d315c;
      --accent: #0ea5e9;
      --danger: #ef4444;
      --text: #334155;
      --text-light: #64748b;
      --bg: #f8fafc;
      --border: #e2e8f0;
      --input-bg: #ffffff;
      --radius: 8px;
    }
    
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Inter', sans-serif;
      color: #0f172a;
      background-color: #fff7ed; /* Light orange tint */
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    
    /* Top Bar - Full Width */
    .top-bar {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 5%;
      z-index: 100;
    }
    .top-bar img { 
      height: 150px; 
      transform: scale(1.5); 
      transform-origin: left center; 
      margin-top: -15px;
      margin-bottom: -15px;
    }
    .close-btn {
      width: 40px; height: 40px;
      border-radius: 50%;
      background: #ffffff;
      display: flex; align-items: center; justify-content: center;
      color: #333;
      font-size: 1.1rem;
      text-decoration: none;
      transition: all 0.2s;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    .close-btn:hover { transform: scale(1.05); box-shadow: 0 4px 15px rgba(0,0,0,0.12); }

    .landing-wrapper {
      width: 100%;
      max-width: 1400px;
      margin: 0 auto;
      flex: 1;
      display: grid;
      grid-template-columns: 1fr 1fr;
    }
    


    /* Left Side: Form Area */
    .form-area {
      padding: 0.5rem 5% 4rem 5%;
    }
    .form-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(2rem, 7vw, 2.6rem);
      font-weight: 800;
      color: #0f172a; /* Dark text */
      margin-bottom: 0.5rem;
      line-height: 1.2;
    }
    .highlight-yellow {
      color: #ea580c; /* Orange shade */
    }
    .form-title::after {
      content: '';
      display: block;
      width: 40px; height: 4px;
      background: #ea580c; /* Orange underline */
      margin-top: 10px;
      border-radius: 2px;
    }
    .form-desc {
      color: #475569; /* Slate text */
      margin-bottom: 2.5rem;
      line-height: 1.6;
      font-size: 1.15rem;
      max-width: 90%;
    }

    /* Form Elements */
    .c-form { display: flex; flex-direction: column; gap: 0.85rem; max-width: 500px; }
    .fg-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; }
    .fg { display: flex; flex-direction: column; gap: 0.3rem; }
    .fg label { font-size: 0.95rem; font-weight: 600; color: #5c4033; }
    .fg label span { color: var(--danger); }
    
    .c-input {
      padding: 0.7rem 0.9rem;
      border: 1px solid #cbd5e1; /* Darker border for light bg */
      border-radius: 4px; /* Sharper corners like IDP */
      font-family: inherit;
      font-size: 0.95rem;
      background: #ffffff;
      color: #0f172a;
      transition: all 0.3s;
    }
    .c-input:focus {
      outline: none;
      border-color: #ea580c;
      box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15);
    }
    
    .phone-group { display: grid; grid-template-columns: 70px 1fr; gap: 0; }
    .phone-group input:first-child { border-right: none; border-radius: 4px 0 0 4px; background: #f8fafc; }
    .phone-group input:last-child { border-radius: 0 4px 4px 0; }

    /* Checkboxes */
    .checkbox-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      margin-top: 0.25rem;
    }
    .c-check {
      display: flex;
      align-items: flex-start;
      gap: 0.5rem;
      cursor: pointer;
    }
    .c-check input {
      margin-top: 0.2rem;
      width: 14px; height: 14px;
      accent-color: #ea580c;
    }
    .c-check span {
      font-size: 0.85rem;
      color: #475569;
      line-height: 1.4;
    }
    .c-check a { color: #ea580c; font-weight: 600; text-decoration: underline; }
    
    .submit-btn {
      margin-top: 0.75rem;
      background: #ea580c; /* Orange shade */
      color: #ffffff;
      border: none;
      padding: 0.9rem 2rem;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 50px;
      cursor: pointer;
      width: fit-content;
      transition: all 0.3s;
    }
    .submit-btn:hover { background: #c2410c; transform: translateY(-2px); }

    /* Right Side: Graphic Area */
    .graphic-area {
      position: relative;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 0 2rem 2rem 2rem;
    }
    
    .graphic-img-wrap {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 550px;
    }
    
    .graphic-img {
      position: relative;
      z-index: 2;
      width: 100%;
      object-fit: cover;
      mix-blend-mode: multiply;
    }
    
    @media(max-width: 992px) {
      .landing-wrapper { grid-template-columns: 1fr; }
      .graphic-area { padding: 2rem 5% 0 5%; order: -1; }
      .form-area { padding: 2rem 5% 3rem 5%; }
      .fg-row { grid-template-columns: 1fr; }
      .top-bar { padding: 1rem 5%; }
      .top-bar img { height: 80px; transform: scale(1.1); margin: 0; }
      .close-btn { width: 35px; height: 35px; font-size: 1rem; }
      .submit-btn { width: 100%; text-align: center; font-size: 1.15rem; }
    }
    
    #formMsg { display: none; margin-top: 1rem; padding: 1rem; border-radius: 8px; font-weight: 500; font-size: 0.95rem; }
    #formMsg.success { display: block; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    #formMsg.error { display: block; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
  </style>
</head>
<body>

<!-- Top Bar -->
<div class="top-bar">
  <a href="index.php"><img src="assets/images/Logo_old.png" alt="Bluestone Overseas Logo"></a>
  <a href="javascript:history.back()" class="close-btn" aria-label="Close"><i class="fa-solid fa-times"></i></a>
</div>

<div class="landing-wrapper">
  
  <!-- Left Form Area (Desktop: Left, Mobile: Bottom) -->
  <div class="form-area">
    <h1 class="form-title"><span style="color: #ec4899;">Get FREE</span> <span style="color: #0ea5e9;">Counselling Today!</span></h1>
    <p class="form-desc">Enter your details and our expert will reach out to you to discuss your plans. By the way, all our services are free!</p>
    
    <form id="counsellingForm" class="c-form">
      <input type="hidden" name="form_type" value="enquiry">
      
      <div class="fg-row">
        <div class="fg">
          <label>First name<span>*</span></label>
          <input type="text" name="first_name" class="c-input" required>
        </div>
        <div class="fg">
          <label>Last name<span>*</span></label>
          <input type="text" name="last_name" class="c-input" required>
        </div>
      </div>
      
      <div class="fg-row">
        <div class="fg">
          <label>Email address<span>*</span></label>
          <input type="email" name="email" class="c-input" required>
        </div>
        <div class="fg">
          <label>Phone Number<span>*</span></label>
          <input type="tel" name="phone" class="c-input" required pattern="[0-9]{10,15}" title="Please enter a valid phone number (10-15 digits)">
        </div>
      </div>

      <div class="fg">
        <label>Your City<span>*</span></label>
        <input type="text" name="city" class="c-input" required>
      </div>

      <div class="fg-row">
        <div class="fg">
          <label>Preferred Study Destination<span>*</span></label>
          <select name="study_destination" class="c-input" required>
            <option value="">Select Country</option>
            <?php foreach($countries as $c): ?>
              <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
            <?php endforeach; ?>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="fg">
          <label>Preferred mode of counselling<span>*</span></label>
          <select name="counselling_mode" class="c-input" required>
            <option value="">Select</option>
            <option value="In-Person (Office Visit)">In-Person (Office Visit)</option>
            <option value="Virtual (Video Call)">Virtual (Video Call)</option>
            <option value="Phone Call">Phone Call</option>
          </select>
        </div>
      </div>

      <div class="fg-row">
        <div class="fg">
          <label>Preferred study level<span>*</span></label>
          <select name="study_level" class="c-input" required>
            <option value="">Select</option>
            <option value="Undergraduate (Bachelors)">Undergraduate (Bachelors)</option>
            <option value="Postgraduate (Masters)">Postgraduate (Masters)</option>
            <option value="Doctorate (PhD)">Doctorate (PhD)</option>
            <option value="Diploma / Certificate">Diploma / Certificate</option>
          </select>
        </div>
        <div class="fg">
          <label>How would you fund your education?<span>*</span></label>
          <select name="funding_mode" class="c-input" required>
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
      
      <button type="submit" class="submit-btn" id="submitBtn">Avail FREE Counselling</button>
      <div id="formMsg"></div>
    </form>
  </div>

  <!-- Right Graphic Area (Desktop: Right, Mobile: Top via CSS order) -->
  <div class="graphic-area">
    <div class="graphic-img-wrap">

      
      <!-- User image -->
      <img src="assets/images/img4.png" alt="Student Counselling" class="graphic-img">
    </div>
  </div>

</div>

<script>
document.getElementById('counsellingForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const form = this;
  const btn = document.getElementById('submitBtn');
  const msg = document.getElementById('formMsg');
  const originalBtnHtml = btn.innerHTML;
  
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
  btn.disabled = true;
  msg.className = '';
  msg.style.display = 'none';
  
  const formData = new FormData(form);
  
  fetch('submit-enquiry.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    msg.style.display = 'block';
    if(data.success) {
      msg.className = 'success';
      msg.innerHTML = '<i class="fa-solid fa-check-circle"></i> Thanks! Your counselling session request has been received. Our team will contact you shortly.';
      form.reset();
      btn.innerHTML = '<i class="fa-solid fa-check"></i> Requested';
    } else {
      msg.className = 'error';
      msg.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + (data.error || 'Failed to submit request.');
      btn.innerHTML = originalBtnHtml;
      btn.disabled = false;
    }
  })
  .catch(err => {
    msg.style.display = 'block';
    msg.className = 'error';
    msg.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Network error. Please try again.';
    btn.innerHTML = originalBtnHtml;
    btn.disabled = false;
  });
});
</script>

</body>
</html>
