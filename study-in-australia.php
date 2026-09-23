<?php
require_once 'includes/config.php';
$pageTitle = 'Study in Australia | Bluestone Overseas';
$pageDesc = 'Get expert guidance to study in Australia. Discover top universities, courses, scholarships, visa processes, and the cost of living for Indian students.';
require_once 'includes/header.php';
?>
<main>

  <!-- 1. Hero Section -->
  <section class="section" style="position: relative; overflow: hidden; padding-top: 4rem; padding-bottom: 5rem; background: linear-gradient(135deg, #f8fafc, #eff6ff);">
    <div style="position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(25, 53, 118, 0.1) 0%, transparent 70%); border-radius: 50%; z-index: 1;"></div>
    
    <div class="container" style="position: relative; z-index: 2;">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem; align-items: center;">
        
        <!-- Text Side -->
        <div class="animate-on-scroll">
          <span class="section__tag" style="background: rgba(25, 53, 118, 0.1); color: var(--dark); border: 1px solid rgba(25, 53, 118, 0.2);">Study Abroad</span>
          <h1 style="font-size: clamp(2rem, 5vw, 3.5rem); line-height: 1.15; margin-bottom: 1.5rem; color: var(--dark); font-weight: 900;">
            Study in <span style="color: #ec4899;">Australia</span>
          </h1>
          <p style="color: #334155; font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">
            Your Gateway to Global Excellence and an Unbeatable Lifestyle.
          </p>
          <p style="color: var(--gray); font-size: 1.1rem; line-height: 1.7; margin-bottom: 2.5rem; max-width: 600px;">
            Ranked among the top 3 countries globally for education, Australia is home to some of the world's leading universities, incredible post-study work opportunities, and vibrant, multicultural cities.
          </p>
          <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="consultation.php" class="btn btn--primary btn--lg pulse-btn" style="background: var(--dark); border-color: var(--dark);">Book Free Consultation <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i></a>
          </div>
        </div>

        <!-- Image Side -->
        <div class="animate-on-scroll delay-1" style="position: relative;">
          <img src="assets/images/3d_australia_new.png" alt="Study in Australia" style="width: 100%; height: auto; display: block; max-width: 500px; margin: 0 auto; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.15));" onerror="this.src='assets/images/australia-hero.jpg';">
        </div>

      </div>
    </div>
  </section>

  <!-- 2. Quick Facts -->
  <section class="section" style="background-color: var(--dark); padding: 3rem 0;">
    <div class="container">
      <div style="display: flex; flex-wrap: wrap; justify-content: space-around; gap: 2rem; text-align: center; color: white;">
        <div class="animate-on-scroll">
            <div style="font-size: 2.5rem; font-weight: 900; color: #38bdf8; margin-bottom: 0.5rem;">43+</div>
            <div style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Universities</div>
        </div>
        <div class="animate-on-scroll delay-1">
            <div style="font-size: 2.5rem; font-weight: 900; color: #facc15; margin-bottom: 0.5rem;">Up to 6</div>
            <div style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Years PSW Visa*</div>
        </div>
        <div class="animate-on-scroll delay-2">
            <div style="font-size: 2.5rem; font-weight: 900; color: #ec4899; margin-bottom: 0.5rem;">48 Hrs</div>
            <div style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Part-time Work/Fortnight</div>
        </div>
        <div class="animate-on-scroll delay-3">
            <div style="font-size: 2.5rem; font-weight: 900; color: #10b981; margin-bottom: 0.5rem;">Top 100</div>
            <div style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Global Rankings</div>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. Why Study in Australia -->
  <section class="section" style="background-color: #ffffff; padding: 5rem 0;">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <h2 class="section__title" style="color: var(--dark);">Why Study in <span>Australia?</span></h2>
        <p class="section__subtitle" style="max-width: 700px; margin: 0 auto;">Discover why over half a million international students choose Australia as their study destination every year.</p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 4rem;">
        
        <div class="animate-on-scroll" style="background: #f8fafc; padding: 2rem; border-radius: 20px; border: 1px solid #e2e8f0; transition: transform 0.3s ease;">
            <div style="width: 60px; height: 60px; background: #e0f2fe; color: #0284c7; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h4 style="font-size: 1.25rem; font-weight: 800; color: var(--dark); margin-bottom: 1rem;">World-Class Education</h4>
            <p style="color: #475569; line-height: 1.6;">Australia boasts highly ranked universities and internationally recognized degrees, particularly renowned in research, technology, and science.</p>
        </div>

        <div class="animate-on-scroll delay-1" style="background: #f8fafc; padding: 2rem; border-radius: 20px; border: 1px solid #e2e8f0; transition: transform 0.3s ease;">
            <div style="width: 60px; height: 60px; background: #fef3c7; color: #d97706; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <h4 style="font-size: 1.25rem; font-weight: 800; color: var(--dark); margin-bottom: 1rem;">Post-Study Work Rights</h4>
            <p style="color: #475569; line-height: 1.6;">International graduates can benefit from a Post-Study Work (PSW) visa allowing them to stay and work for up to 6 years, especially in regional areas.</p>
        </div>

        <div class="animate-on-scroll delay-2" style="background: #f8fafc; padding: 2rem; border-radius: 20px; border: 1px solid #e2e8f0; transition: transform 0.3s ease;">
            <div style="width: 60px; height: 60px; background: #fce7f3; color: #be185d; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-sun"></i>
            </div>
            <h4 style="font-size: 1.25rem; font-weight: 800; color: var(--dark); margin-bottom: 1rem;">Unbeatable Lifestyle</h4>
            <p style="color: #475569; line-height: 1.6;">From stunning beaches to vibrant multicultural cities, Australia offers a safe, welcoming, and high-quality standard of living for students.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- 4. Top Universities -->
  <section class="section" style="background-color: #f1f5f9; padding: 5rem 0;">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <h2 class="section__title" style="color: var(--dark);">Top Universities & <span>Destinations</span></h2>
        <p class="section__subtitle" style="max-width: 700px; margin: 0 auto;">Australia is home to the prestigious 'Group of Eight' (Go8) and many other highly ranked institutions across top student cities like Melbourne, Sydney, and Brisbane.</p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 3rem;">
        
        <div class="animate-on-scroll" style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center;">
            <h4 style="font-size: 1.2rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">Group of Eight (Go8)</h4>
            <p style="color: #64748b; font-size: 0.95rem; line-height: 1.5;">Australia's leading research-intensive universities, consistently ranked in the global top 100.</p>
        </div>
        
        <div class="animate-on-scroll delay-1" style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center;">
            <h4 style="font-size: 1.2rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">Regional Universities</h4>
            <p style="color: #64748b; font-size: 0.95rem; line-height: 1.5;">Study in regional areas for a relaxed lifestyle, lower costs, and extended post-study work visa options.</p>
        </div>
        
        <div class="animate-on-scroll delay-2" style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center;">
            <h4 style="font-size: 1.2rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">Technology Networks (ATN)</h4>
            <p style="color: #64748b; font-size: 0.95rem; line-height: 1.5;">Industry-focused universities that deliver practical, hands-on learning and strong graduate employability.</p>
        </div>

      </div>
      <div class="text-center animate-on-scroll" style="margin-top: 3rem;">
        <a href="universities.php" class="btn btn--outline" style="border-color: var(--dark); color: var(--dark);">Explore Partner Universities</a>
      </div>
    </div>
  </section>

  <!-- 5. Cost & Scholarships -->
  <section class="section" style="background-color: #ffffff; padding: 5rem 0;">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 4rem; align-items: flex-start;">
        
        <!-- Cost of Studying -->
        <div class="animate-on-scroll">
            <h2 class="section__title" style="font-size: 2rem; color: var(--dark); margin-bottom: 1.5rem;">Cost of Studying & <span>Living</span></h2>
            <p style="color: #475569; margin-bottom: 2rem; line-height: 1.6;">Understanding the financial requirements is a crucial step. Here is an estimated breakdown of tuition and living expenses.</p>
            
            <div style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--dark); color: white;">
                            <th style="padding: 1rem; font-weight: 600;">Study Level / Expense</th>
                            <th style="padding: 1rem; font-weight: 600;">Estimated Cost (AUD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 1rem; color: #1e293b; font-weight: 600;">Undergraduate (Bachelors)</td>
                            <td style="padding: 1rem; color: #475569;">$20,000 - $45,000 / year</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 1rem; color: #1e293b; font-weight: 600;">Postgraduate (Masters)</td>
                            <td style="padding: 1rem; color: #475569;">$22,000 - $50,000 / year</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 1rem; color: #1e293b; font-weight: 600;">Doctoral Degree (PhD)</td>
                            <td style="padding: 1rem; color: #475569;">$18,000 - $42,000 / year</td>
                        </tr>
                        <tr style="background: #f1f5f9;">
                            <td style="padding: 1rem; color: #1e293b; font-weight: 600;">Annual Living Costs</td>
                            <td style="padding: 1rem; color: #475569;">$24,000 - $29,000 / year</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p style="font-size: 0.85rem; color: #64748b; margin-top: 1rem;">* Medical and veterinary courses may cost significantly more. Living costs vary by city and lifestyle.</p>
        </div>

        <!-- Scholarships -->
        <div class="animate-on-scroll delay-1">
            <h2 class="section__title" style="font-size: 2rem; color: var(--dark); margin-bottom: 1.5rem;">Scholarships for <span>Indian Students</span></h2>
            <p style="color: #475569; margin-bottom: 2rem; line-height: 1.6;">The Australian Government and universities offer numerous scholarships to help international students fund their education.</p>
            
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                <li style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; display: flex; align-items: flex-start; gap: 1rem; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                    <div style="color: #ec4899; font-size: 1.5rem; margin-top: 2px;"><i class="fa-solid fa-award"></i></div>
                    <div>
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--dark); margin-bottom: 0.25rem;">Australia Awards</h4>
                        <p style="font-size: 0.95rem; color: #64748b; margin: 0;">Fully-funded scholarships provided by the government covering full tuition, travel, and living expenses.</p>
                    </div>
                </li>
                <li style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; display: flex; align-items: flex-start; gap: 1rem; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                    <div style="color: #0ea5e9; font-size: 1.5rem; margin-top: 2px;"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                    <div>
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--dark); margin-bottom: 0.25rem;">Destination Australia</h4>
                        <p style="font-size: 0.95rem; color: #64748b; margin: 0;">Scholarships valued up to $15,000 per year to support students studying in regional Australia.</p>
                    </div>
                </li>
                <li style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; display: flex; align-items: flex-start; gap: 1rem; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                    <div style="color: #10b981; font-size: 1.5rem; margin-top: 2px;"><i class="fa-solid fa-building-columns"></i></div>
                    <div>
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--dark); margin-bottom: 0.25rem;">University Specific Scholarships</h4>
                        <p style="font-size: 0.95rem; color: #64748b; margin: 0;">Merit-based scholarships ranging from 10% to 100% tuition fee waivers offered directly by institutions.</p>
                    </div>
                </li>
            </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- 6. Student Visa Process -->
  <section class="section" style="background-color: var(--dark); padding: 5rem 0; color: white;">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <h2 class="section__title" style="color: white;">Australian Student <span>Visa (Subclass 500)</span></h2>
        <p class="section__subtitle" style="max-width: 700px; margin: 0 auto; color: #cbd5e1;">Navigating the visa process is critical. Bluestone Overseas provides expert assistance to ensure a smooth application.</p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 4rem;">
        
        <div class="animate-on-scroll" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; padding: 2rem; text-align: center;">
            <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-weight: 900; font-size: 1.25rem; color: #38bdf8;">1</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">Secure an Offer</h4>
            <p style="font-size: 0.9rem; color: #cbd5e1; line-height: 1.5;">Apply to a university and receive an unconditional Letter of Offer.</p>
        </div>

        <div class="animate-on-scroll delay-1" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; padding: 2rem; text-align: center;">
            <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-weight: 900; font-size: 1.25rem; color: #ec4899;">2</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">Clear GTE/GS Requirement</h4>
            <p style="font-size: 0.9rem; color: #cbd5e1; line-height: 1.5;">Demonstrate your genuine intent to study through the Genuine Student (GS) assessment.</p>
        </div>

        <div class="animate-on-scroll delay-2" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; padding: 2rem; text-align: center;">
            <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-weight: 900; font-size: 1.25rem; color: #facc15;">3</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">Receive CoE</h4>
            <p style="font-size: 0.9rem; color: #cbd5e1; line-height: 1.5;">Pay tuition deposits and arrange OSHC to get your Confirmation of Enrolment.</p>
        </div>

        <div class="animate-on-scroll delay-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; padding: 2rem; text-align: center;">
            <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-weight: 900; font-size: 1.25rem; color: #10b981;">4</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">Lodge Visa</h4>
            <p style="font-size: 0.9rem; color: #cbd5e1; line-height: 1.5;">Submit your visa application with medicals and biometrics.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- 7. FAQs -->
  <section class="section" style="background-color: #f8fafc; padding: 5rem 0;">
    <div class="container">
      <div class="text-center animate-on-scroll">
        <h2 class="section__title" style="color: var(--dark);">Frequently Asked <span>Questions</span></h2>
      </div>

      <div style="max-width: 800px; margin: 3rem auto 0; display: flex; flex-direction: column; gap: 1rem;">
        
        <details style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; cursor: pointer;" class="animate-on-scroll">
            <summary style="font-size: 1.1rem; font-weight: 700; color: var(--dark); outline: none;">How much can I earn while studying in Australia?</summary>
            <p style="margin-top: 1rem; color: #475569; font-size: 0.95rem; line-height: 1.6;">International students are permitted to work up to 48 hours per fortnight while their course is in session, and unlimited hours during scheduled course breaks.</p>
        </details>
        
        <details style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; cursor: pointer;" class="animate-on-scroll delay-1">
            <summary style="font-size: 1.1rem; font-weight: 700; color: var(--dark); outline: none;">What is the 485 post-study work stream visa?</summary>
            <p style="margin-top: 1rem; color: #475569; font-size: 0.95rem; line-height: 1.6;">The Temporary Graduate visa (subclass 485) allows international students who have recently graduated from an Australian institution to stay, work, and gain practical experience in Australia.</p>
        </details>
        
        <details style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; cursor: pointer;" class="animate-on-scroll delay-2">
            <summary style="font-size: 1.1rem; font-weight: 700; color: var(--dark); outline: none;">Why study in Regional Australia?</summary>
            <p style="margin-top: 1rem; color: #475569; font-size: 0.95rem; line-height: 1.6;">Studying in a regional area provides access to a relaxed lifestyle, lower living costs, smaller class sizes, and potentially an extra 1-2 years of post-study work rights on your graduate visa.</p>
        </details>

      </div>
    </div>
  </section>

  <!-- 8. Consultation CTA (Reused from existing components) -->
  <section class="section contact-section" style="background: #ffffff; padding: 5rem 0;">
    <div class="container">
      <div style="background: #14b8a6; border-radius: 30px; padding: 4rem; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(20, 184, 166, 0.25);">
        <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>
        <div style="position: absolute; bottom: -100px; left: 20%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none;"></div>

        <div style="position: relative; z-index: 1;">
          <div class="section__header animate-on-scroll" style="text-align: center; margin-bottom: 3rem;">
            <span class="section__tag" style="background: rgba(255,255,255,0.2); color: #fff;">Begin Your Journey</span>
            <h2 class="section__title" style="color: #fff;">Get a Free <span style="color: #FDE047; background: none; -webkit-text-fill-color: initial;">Consultation</span></h2>
            <p class="section__subtitle" style="color: rgba(255,255,255,0.9);">Reach out to our Australia study experts today to discuss your university options.</p>
          </div>
          
          <div style="max-width: 600px; margin: 0 auto;" class="animate-on-scroll delay-1">
            <form id="contactHomeForm" onsubmit="return handleFormSubmit(event)" style="background: #ffffff; padding: 2rem; border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,0.15);">
                <input type="hidden" name="form_type" value="contact">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div><label style="display:block;font-size:0.9rem;font-weight:600;margin-bottom:5px;">First Name *</label><input type="text" name="first_name" style="width:100%;padding:10px;border:1px solid #e2e8f0;border-radius:6px;" required></div>
                    <div><label style="display:block;font-size:0.9rem;font-weight:600;margin-bottom:5px;">Last Name *</label><input type="text" name="last_name" style="width:100%;padding:10px;border:1px solid #e2e8f0;border-radius:6px;" required></div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div><label style="display:block;font-size:0.9rem;font-weight:600;margin-bottom:5px;">Email *</label><input type="email" name="email" style="width:100%;padding:10px;border:1px solid #e2e8f0;border-radius:6px;" required></div>
                    <div><label style="display:block;font-size:0.9rem;font-weight:600;margin-bottom:5px;">Phone *</label><input type="tel" name="phone" style="width:100%;padding:10px;border:1px solid #e2e8f0;border-radius:6px;" required></div>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display:block;font-size:0.9rem;font-weight:600;margin-bottom:5px;">Study Destination</label>
                    <select name="destination" style="width:100%;padding:10px;border:1px solid #e2e8f0;border-radius:6px;">
                        <option value="Australia" selected>Australia</option>
                    </select>
                </div>
                <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;background:var(--dark);border-color:var(--dark);">
                    Submit Request
                </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>
<?php require_once 'includes/footer.php'; ?>
                <div style="margin-bottom: 2rem;">
                    <label style="display:block;font-size:0.9rem;font-weight:700;color: #0f172a;margin-bottom:8px;">Study Destination</label>
                    <select name="destination" style="width:100%;padding:12px;border:1px solid #e2e8f0;border-radius:10px;background:#f8fafc;outline:none;">
                        <option value="Australia" selected>Australia</option>
                    </select>
                </div>
                <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;background:linear-gradient(135deg, #0f172a, #1e293b);border:none;padding:1.2rem;font-size:1.1rem;border-radius:12px;">
                    Submit Request
                </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>
<?php require_once 'includes/footer.php'; ?>