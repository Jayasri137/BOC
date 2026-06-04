<?php
require_once 'includes/config.php';
$university = isset($_GET['university']) ? trim($_GET['university']) : '';
$pageTitle = $university ? 'Apply to ' . htmlspecialchars($university) . ' | Bluestone Overseas' : 'Start Your Application | Bluestone Overseas';
$pageDesc = 'Start your university application process with Bluestone Overseas Consultants. Complete the enquiry form and our counsellors will assist you.';
require_once 'includes/header.php';
?>
<main>
  <!-- Beautiful Premium Header Section -->
  <section class="section" style="background: linear-gradient(135deg, #f8fafc 0%, #f0fdf4 50%, #f0f9ff 100%); padding: 4.5rem 0 3.5rem; position: relative; overflow: hidden;">
    <div class="container" style="position: relative; z-index: 2;">
      <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <span class="section__tag" style="background: rgba(22, 163, 74, 0.1); color: var(--success); font-weight: 700; padding: 0.4rem 1rem; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.78rem;">
          <?= $university ? 'Direct Partner Admission' : 'Study Abroad Application' ?>
        </span>
        <h1 class="section__title" style="margin-top: 1.25rem; font-size: clamp(2rem, 5vw, 3rem); line-height: 1.2; font-family: 'Plus Jakarta Sans', sans-serif;">
          <?= $university ? 'Start Your Application for <span>' . htmlspecialchars($university) . '</span>' : 'Begin Your <span>Global Education</span> Journey' ?>
        </h1>
        <p style="color: var(--gray); font-size: 1.05rem; line-height: 1.6; margin-top: 1rem; max-width: 650px; margin-left: auto; margin-right: auto;">
          Fill in your academic preferences and contact details below. Our study abroad experts will review your profile, secure your offer letter, and manage your visa processing.
        </p>
      </div>
    </div>
    
    <!-- Background elements to make it feel alive -->
    <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; border-radius: 50%; background: rgba(14, 165, 233, 0.05); filter: blur(50px); z-index: 1;"></div>
    <div style="position: absolute; bottom: -50px; left: -50px; width: 300px; height: 300px; border-radius: 50%; background: rgba(22, 163, 74, 0.05); filter: blur(50px); z-index: 1;"></div>
  </section>

  <!-- Application Form Section -->
  <section class="section" style="padding: 3rem 0 5rem; background: #ffffff;">
    <div class="container">
      <div class="grid grid--2 gap--4" style="align-items: start; grid-template-columns: 1fr 1.3fr;">
        
        <!-- Left Side Column: Information and Perks -->
        <div class="animate-on-scroll" style="position: sticky; top: 120px;">
          <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 20px; padding: 2.5rem; color: #ffffff; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.5rem; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 1.5rem; color: #ffffff; display: flex; align-items: center; gap: 0.5rem;">
              <i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Bluestone Application Portal
            </h3>
            <p style="color: #cbd5e1; font-size: 0.95rem; line-height: 1.6; margin-bottom: 2rem;">
              You are launching your official application. Once submitted, your profile is routed directly to our specialist admissions division.
            </p>
            
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
              <div style="display: flex; gap: 1rem; align-items: flex-start;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.06); display: grid; place-items: center; color: #38bdf8; font-size: 1.1rem; flex-shrink: 0;">
                  <i class="fa-solid fa-bolt"></i>
                </div>
                <div>
                  <h4 style="font-weight: 700; font-size: 0.95rem; margin-bottom: 0.25rem; color: #ffffff;">Fast-Track Offer Letters</h4>
                  <p style="font-size: 0.85rem; color: #94a3b8; line-height: 1.5;">Secured in as little as 48 hours for selected partner universities.</p>
                </div>
              </div>

              <div style="display: flex; gap: 1rem; align-items: flex-start;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.06); display: grid; place-items: center; color: #fbbf24; font-size: 1.1rem; flex-shrink: 0;">
                  <i class="fa-solid fa-percent"></i>
                </div>
                <div>
                  <h4 style="font-weight: 700; font-size: 0.95rem; margin-bottom: 0.25rem; color: #ffffff;">Fee Waivers &amp; Scholarships</h4>
                  <p style="font-size: 0.85rem; color: #94a3b8; line-height: 1.5;">Direct application gives you access to waived university application fees.</p>
                </div>
              </div>

              <div style="display: flex; gap: 1rem; align-items: flex-start;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.06); display: grid; place-items: center; color: #34d399; font-size: 1.1rem; flex-shrink: 0;">
                  <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                  <h4 style="font-weight: 700; font-size: 0.95rem; margin-bottom: 0.25rem; color: #ffffff;">98% Visa Success Rate</h4>
                  <p style="font-size: 0.85rem; color: #94a3b8; line-height: 1.5;">Professional filing and interview support from our specialized visa team.</p>
                </div>
              </div>
            </div>

            <?php if ($university): ?>
              <div style="margin-top: 2.5rem; padding: 1.25rem 1.5rem; background: rgba(34, 197, 94, 0.1); border: 1px dashed rgba(34, 197, 94, 0.3); border-radius: 12px; display: flex; gap: 0.75rem; align-items: center;">
                <span style="font-size: 1.5rem;">🎓</span>
                <div>
                  <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #4ade80; display: block; letter-spacing: 0.05em;">Selected Institution</span>
                  <strong style="color: #ffffff; font-size: 0.95rem;"><?= htmlspecialchars($university) ?></strong>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Right Side Column: Premium Form Card -->
        <div class="animate-on-scroll delay-1">
          <div class="contact-form-wrap" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 20px 45px rgba(0,0,0,0.04);">
            <h3 style="font-size: 1.5rem; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--dark); margin-bottom: 0.5rem;">Application Details</h3>
            <p style="color: var(--gray); font-size: 0.875rem; margin-bottom: 2rem;">Please fill in all details accurately. Fields marked with * are required.</p>
            
            <form id="enquiryPageForm" onsubmit="return handleFormSubmit(event)">
              <input type="hidden" name="form_type" value="enquiry">
              
              <?php if ($university): ?>
                <input type="hidden" name="university" value="<?= htmlspecialchars($university) ?>">
              <?php endif; ?>

              <!-- Name Row -->
              <div class="cf-grid-2">
                <div class="cf-group">
                  <label>First Name *</label>
                  <input type="text" name="first_name" placeholder="First name" required style="border-radius: 8px;">
                </div>
                <div class="cf-group">
                  <label>Last Name *</label>
                  <input type="text" name="last_name" placeholder="Last name" required style="border-radius: 8px;">
                </div>
              </div>

              <!-- Contact Row -->
              <div class="cf-grid-2">
                <div class="cf-group">
                  <label>Email Address *</label>
                  <input type="email" name="email" placeholder="example@email.com" required style="border-radius: 8px;">
                </div>
                <div class="cf-group">
                  <label>Mobile Number *</label>
                  <div style="display: flex; gap: 0.5rem;">
                    <input type="text" value="+91" readonly style="width: 55px; text-align: center; background: #e2e8f0; font-weight: 700; border-radius: 8px; border: 1.5px solid #e2e8f0;">
                    <input type="tel" name="phone" placeholder="10-digit mobile number" required style="flex: 1; border-radius: 8px;">
                  </div>
                </div>
              </div>

              <!-- Study Preferences Row -->
              <div class="cf-grid-2">
                <div class="cf-group">
                  <label>Preferred Destination *</label>
                  <select name="destination" required style="border-radius: 8px;">
                    <option value="" disabled selected>Select Destination</option>
                    <option value="Australia">Australia</option>
                    <option value="Canada">Canada</option>
                    <option value="UK">United Kingdom</option>
                    <option value="USA">United States</option>
                    <option value="Germany">Germany</option>
                    <option value="Ireland">Ireland</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="France">France</option>
                    <option value="Italy">Italy</option>
                    <option value="Sweden">Sweden</option>
                    <option value="South Korea">South Korea</option>
                    <option value="UAE">United Arab Emirates</option>
                    <option value="Netherlands">Netherlands</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Other">Other Country</option>
                  </select>
                </div>
                <div class="cf-group">
                  <label>Preferred Intake *</label>
                  <select name="start_date" required style="border-radius: 8px;">
                    <option value="" disabled selected>Select Intake</option>
                    <option value="Jan 2026">January 2026</option>
                    <option value="May 2026">May 2026</option>
                    <option value="Sept 2026">September 2026</option>
                    <option value="Jan 2027">January 2027</option>
                    <option value="Sept 2027">September 2027</option>
                  </select>
                </div>
              </div>

              <!-- Academic Level & Funding -->
              <div class="cf-grid-2">
                <div class="cf-group">
                  <label>Preferred Study Level *</label>
                  <select name="study_level" required style="border-radius: 8px;">
                    <option value="" disabled selected>Select Level</option>
                    <option value="Undergraduate">Undergraduate (Bachelors)</option>
                    <option value="Postgraduate">Postgraduate (Masters)</option>
                    <option value="MBA">MBA</option>
                    <option value="PhD">PhD / Doctorate</option>
                    <option value="Diploma">Diploma / Certificate</option>
                  </select>
                </div>
                <div class="cf-group">
                  <label>How will you fund? *</label>
                  <select name="funding_mode" required style="border-radius: 8px;">
                    <option value="" disabled selected>Select Funding</option>
                    <option value="Self-funded">Self-funded</option>
                    <option value="Student Loan">Student Loan</option>
                    <option value="Scholarship">Scholarship</option>
                    <option value="Parents/Guardian">Parents / Guardian Support</option>
                  </select>
                </div>
              </div>

              <!-- Counselling Mode -->
              <div class="cf-group">
                <label>Preferred Mode of Counselling *</label>
                <select name="counselling_mode" required style="border-radius: 8px;">
                  <option value="" disabled selected>Select Mode</option>
                  <option value="Virtual Counselling">Virtual Counselling (Online Video/Call)</option>
                  <option value="In-person">In-Person (Visit our office)</option>
                </select>
              </div>

              <!-- Message -->
              <div class="cf-group">
                <label>Your Message / Comments</label>
                <textarea name="message" rows="4" placeholder="Mention any specific course, GPA, test scores (IELTS/TOEFL/PTE) or questions you have..." style="border-radius: 8px; resize: vertical;"></textarea>
              </div>

              <!-- Submit Button -->
              <button type="submit" class="btn btn--primary btn--lg pulse-btn" style="width: 100%; justify-content: center; height: 54px; font-weight: 700; border-radius: 8px; font-size: 1rem; margin-top: 1.5rem;">
                <i class="fa-solid fa-paper-plane"></i> Submit Application Enquiry
              </button>
              
              <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 1.5rem; text-align: center; line-height: 1.4;">
                By submitting, you agree to our privacy policy and authorize Bluestone Overseas to contact you for admissions assistance.
              </p>
            </form>
          </div>
        </div>

      </div>
    </div>
  </section>
</main>
<?php require_once 'includes/footer.php'; ?>
