<?php
require_once 'includes/config.php';
$pageTitle = 'Free Study Abroad Consultation in Coimbatore | Bluestone Overseas';
$pageDesc = 'Book a free consultation with expert study abroad advisors for university selection, admissions, and visas.';
require_once 'includes/header.php';
?>
<main>
<div class="container" style="padding-top: 2rem; padding-bottom: 1rem;"><h1 class="section__title" style="text-align:center; margin:0; font-size: 2.2rem;">Free Overseas Education Consultation</h1></div>

  <section class="section">
    <div class="container">
      <div class="grid grid--2 gap--4">
        <div class="animate-on-scroll">
          <h2 class="section__title" style="text-align:left">Why Book a <span>Free Session?</span></h2>
          <ul class="consult-perks" style="margin-top:2rem">
            <li style="margin-bottom:1.5rem; display:flex; gap:1rem; align-items:flex-start">
              <i class="fa-solid fa-check-circle" style="color:var(--primary); font-size:1.25rem"></i>
              <div><h4 style="font-weight:700">Expert Profile Evaluation</h4><p style="font-size:.85rem; color:var(--gray)">We analyze your academics, scores, and interests to suggest the best path.</p></div>
            </li>
            <li style="margin-bottom:1.5rem; display:flex; gap:1rem; align-items:flex-start">
              <i class="fa-solid fa-check-circle" style="color:var(--primary); font-size:1.25rem"></i>
              <div><h4 style="font-weight:700">Country & Course Selection</h4><p style="font-size:.85rem; color:var(--gray)">Get a shortlisted list of universities that match your career goals.</p></div>
            </li>
            <li style="margin-bottom:1.5rem; display:flex; gap:1rem; align-items:flex-start">
              <i class="fa-solid fa-check-circle" style="color:var(--primary); font-size:1.25rem"></i>
              <div><h4 style="font-weight:700">Financial Planning</h4><p style="font-size:.85rem; color:var(--gray)">Understand the total cost and available scholarship options.</p></div>
            </li>
          </ul>
        </div>
        <div class="animate-on-scroll delay-1">
          <div class="wizard-container">
            <!-- Progress Bar -->
            <div class="wizard-progress">
              <div class="progress-bar" id="wizardProgress" style="width: 33.33%;"></div>
            </div>

            <!-- Step 1: Meeting Type -->
            <div class="wizard-step active" id="step1">
              <h3 class="wizard-title">How would you like to meet?</h3>
              <p class="wizard-subtitle">Choose your preferred consultation mode.</p>
              <div class="wizard-options">
                <div class="wizard-option" onclick="selectMeetingType('Online')">
                  <i class="fa-solid fa-laptop"></i>
                  <h4>Online Meeting</h4>
                  <p>Virtual video consultation</p>
                </div>
                <div class="wizard-option" onclick="selectMeetingType('Office')">
                  <i class="fa-solid fa-building"></i>
                  <h4>In-Office Visit</h4>
                  <p>Meet us at our branch</p>
                </div>
              </div>
            </div>

            <!-- Step 2: Country Selection (For Online) -->
            <div class="wizard-step" id="step2">
              <button class="btn-back" onclick="goToStep(1)"><i class="fa-solid fa-arrow-left"></i> Back</button>
              <h3 class="wizard-title">Where do you want to study?</h3>
              <p class="wizard-subtitle">Select your preferred study destination.</p>
              <div class="wizard-options wizard-options--grid">
                <div class="wizard-option" onclick="selectCountry('USA')"><span class="fi fi-us"></span> USA</div>
                <div class="wizard-option" onclick="selectCountry('UK')"><span class="fi fi-gb"></span> UK</div>
                <div class="wizard-option" onclick="selectCountry('Canada')"><span class="fi fi-ca"></span> Canada</div>
                <div class="wizard-option" onclick="selectCountry('Australia')"><span class="fi fi-au"></span> Australia</div>
                <div class="wizard-option" onclick="selectCountry('Germany')"><span class="fi fi-de"></span> Germany</div>
                <div class="wizard-option" onclick="selectCountry('Other')"><i class="fa-solid fa-globe"></i> Other</div>
              </div>
            </div>

            <!-- Step 3: Cal.com Embed (For Online) -->
            <div class="wizard-step" id="step3">
              <button class="btn-back" onclick="goToStep(2)"><i class="fa-solid fa-arrow-left"></i> Back</button>
              <h3 class="wizard-title">Select Date & Time</h3>
              <p class="wizard-subtitle">Book your session via our calendar.</p>
              <div id="cal-embed-container" style="width:100%;height:650px;overflow-y:auto;border-radius:12px;background:#fff;">
                <!-- Cal embed will load here -->
              </div>
            </div>
            
            <!-- Step Office: Traditional Form -->
            <div class="wizard-step" id="step-office">
              <button class="btn-back" onclick="goToStep(1)"><i class="fa-solid fa-arrow-left"></i> Back</button>
              <h3 class="wizard-title">Book In-Office Visit</h3>
              <p class="wizard-subtitle">Enter your details below to schedule a visit.</p>
              <div class="contact-form-wrap">
                <form id="consultationForm" onsubmit="return handleFormSubmit(event)">
                  <input type="hidden" name="form_type" value="enquiry">
                  <input type="hidden" name="counselling_mode" value="In-Office Visit">
                  <input type="hidden" name="funding_mode" value="Self-funded">
                  <div class="cf-grid-2">
                    <div class="cf-group"><label>First Name</label><input type="text" name="first_name" required></div>
                    <div class="cf-group"><label>Last Name</label><input type="text" name="last_name" required></div>
                  </div>
                  <div class="cf-group"><label>Email</label><input type="email" name="email" required></div>
                  <div class="cf-group"><label>Phone</label><input type="tel" name="phone" required></div>
                  <div class="cf-group"><label>Preferred Country</label>
                    <select name="destination"><option value="">Select Country</option><option>USA</option><option>UK</option><option>Canada</option><option>Australia</option><option>Germany</option><option>Other</option></select>
                  </div>
                  <button type="submit" class="btn btn--primary btn--lg" style="width:100%; justify-content:center; background:#ff1e1e; border:none; border-radius: 50px;">Book My Free Session</button>
                </form>
              </div>
            </div>
          </div>
          
          <!-- Cal.com Script Integration -->
          <script type="text/javascript">
            (function (C, A, L) { let p = function (a, ar) { a.q.push(ar); }; let d = C.document; C.Cal = C.Cal || function () { let cal = C.Cal; let ar = arguments; if (!cal.loaded) { cal.ns = {}; cal.q = cal.q || []; d.head.appendChild(d.createElement("script")).src = A; cal.loaded = true; } if (ar[0] === L) { const api = function () { p(api, arguments); }; const namespace = ar[1]; api.q = api.q || []; typeof namespace === "string" ? (cal.ns[namespace] = cal.ns[namespace] || api) : p(cal, ar); return; } p(cal, ar); }; })(window, "https://app.cal.com/embed/embed.js", "init");
            Cal("init", {origin:"https://cal.com"});

            let bookingData = { type: '', country: '' };
            let calInitialized = false;
            
            function selectMeetingType(type) {
              bookingData.type = type;
              if (type === 'Office') {
                  goToStep('office');
              } else {
                  goToStep(2);
              }
            }
            
            function selectCountry(country) {
              bookingData.country = country;
              goToStep(3);
              initCalCom();
            }
            
            function goToStep(step) {
              document.querySelectorAll('.wizard-step').forEach(el => el.classList.remove('active'));
              document.getElementById('step' + (step === 'office' ? '-office' : step)).classList.add('active');
              
              if (step === 'office' || step === 3) {
                  document.getElementById('wizardProgress').style.width = '100%';
              } else {
                  document.getElementById('wizardProgress').style.width = (step * 33.33) + '%';
              }
            }
            
            function initCalCom() {
              if (calInitialized) return;
              
              // ⚠️ IMPORTANT: Replace 'rick/get-rick-rolled' with your actual 'username/event-name' 
              const calLink = 'bluestone-overseas/30min'; 
              
              Cal("inline", {
                elementOrSelector:"#cal-embed-container",
                calLink: calLink,
                layout: "month_view"
              });
              
              Cal("ui", {"styles":{"branding":{"brandColor":"#dc2626"}},"hideEventTypeDetails":false,"layout":"month_view"});
              calInitialized = true;
            }
          </script>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
