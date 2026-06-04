<div class="content-wrapper">
  <div class="container-full">

    <!-- Header -->
    <div class="content-header d-flex align-items-center justify-content-between">
      <div>
        <h3 class="page-title">Edit Lead</h3>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="#">
                <i class="mdi mdi-home-outline"></i> Dashboard
              </a>
            </li>
            <li class="breadcrumb-item">
              <a href="<?php echo base_url('lead'); ?>">Leads</a>
            </li>
            <li class="breadcrumb-item active">Edit Lead</li>
          </ol>
        </nav>
      </div>
      <div>
        <a href="<?php echo base_url('lead'); ?>" class="btn btn-primary btn-sm">
          <i class="fa fa-arrow-left"></i> Back to Leads
        </a>
      </div>
    </div>

  

    <!-- Form -->
    <div class="container border p-5 mt-4 mb-4 bg-white rounded shadow-sm">
      
      <!-- Navigation Tabs -->
      <div class="mb-4">
        <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-link active" id="nav-basic-tab" data-bs-toggle="tab" href="#nav-basic" role="tab" aria-controls="nav-basic" aria-selected="true">
              <i class="fa fa-user"></i> Basic Details
            </a>
            <a class="nav-link" id="nav-academic-tab" data-bs-toggle="tab" href="#nav-academic" role="tab" aria-controls="nav-academic" aria-selected="false">
              <i class="fa fa-graduation-cap"></i> Academic Details
            </a>
            <a class="nav-link" id="nav-employment-tab" data-bs-toggle="tab" href="#nav-employment" role="tab" aria-controls="nav-employment" aria-selected="false">
              <i class="fa fa-briefcase"></i> Employment Details
            </a>
            <a class="nav-link" id="nav-documents-tab" data-bs-toggle="tab" href="#nav-documents" role="tab" aria-controls="nav-documents" aria-selected="false">
              <i class="fa fa-file"></i> Documents
            </a>
          </div>
        </nav>
      </div>

      <form id="leadForm" method="post" action="<?php echo base_url('lead/update_lead/' . $lead->id); ?>" enctype="multipart/form-data" novalidate>
        
        <!-- Basic Details Tab -->
        <div class="tab-content" id="nav-tabContent">
          <div class="tab-pane fade show active" id="nav-basic" role="tabpanel" aria-labelledby="nav-basic-tab">
            <div class="row g-3">

              <!-- Name -->
              <div class="col-md-4">
                <div class="form-group">
                  <label class="required">Full Name</label>
                  <input type="text" name="name" class="form-control" placeholder="Enter Full Name" value="<?php echo htmlspecialchars($lead->name ?? ''); ?>" required>
                </div>
              </div>

              <!-- Email -->
              <div class="col-md-4">
                <div class="form-group">
                  <label class="required">Email</label>
                  <input type="email" name="email" class="form-control" placeholder="Enter Email" value="<?php echo htmlspecialchars($lead->email ?? ''); ?>" required>
                </div>
              </div>

              <!-- Country -->
              <div class="col-md-4">
                <div class="form-group">
                  <label>Country</label>
                  <select name="country" class="form-control">
                    <option value="">-- Select Country --</option>
                    <?php if (!empty($countries)): ?>
                      <?php foreach ($countries as $country): ?>
                        <option value="<?php echo htmlspecialchars($country->name); ?>" 
                          <?php echo ($lead->country == $country->name) ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($country->name); ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <!-- Mobile -->
              <div class="col-md-4">
                <div class="form-group">
                  <label class="required">Mobile</label>
                  <div class="input-group">
                    <span class="input-group-text">+91</span>
                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Enter Mobile" value="<?php echo htmlspecialchars($lead->mobile ?? ''); ?>" required>
                  </div>
                  <small id="mobileError" class="text-danger" style="display:none;">Invalid mobile number format</small>
                </div>
              </div>

              <!-- Intake Year -->
              <div class="col-md-4">
                <div class="form-group">
                  <label>Intake Year</label>
                  <select name="intake_year" class="form-control">
                    <option value="">-- Select Intake Year --</option>
                    <?php if (!empty($intake_years)): ?>
                      <?php foreach ($intake_years as $intake): ?>
                        <option value="<?php echo htmlspecialchars($intake->intake_year); ?>" 
                          <?php echo ($lead->intake_year == $intake->intake_year) ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($intake->intake_year); ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <!-- University -->
              <div class="col-md-4">
                <div class="form-group">
                  <label>University</label>
                  <select name="university" class="form-control">
                    <option value="">-- Select University --</option>
                    <?php if (!empty($universities)): ?>
                      <?php foreach ($universities as $university): ?>
                        <option value="<?php echo htmlspecialchars($university->name); ?>" 
                          <?php echo ($lead->university == $university->name) ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($university->name); ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

<!-- Assigned To (Role-based) -->
<div class="col-md-4">
    <div class="form-group">
        <label>Assigned To</label>
        <select name="assigned_to" id="assigned_to" class="form-control">
            <option value="">-- Select Role --</option>

            <?php if (!empty($roles)): ?>
                <?php foreach ($roles as $role): ?>

                    <?php 
                        // Skip Super Admin (1) and Admin (2)
                        if ($role->ug_id == 1 || $role->ug_id == 2) { 
                            continue;
                        }
                    ?>

                    <option value="<?php echo $role->ug_id; ?>"
                        <?php echo (isset($lead->assigned_to) && $lead->assigned_to == $role->ug_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($role->ug_name); ?>
                    </option>

                <?php endforeach; ?>
            <?php endif; ?>

        </select>
    </div>
</div>


              <!-- Status -->
              <div class="col-md-4">
                <div class="form-group">
                  <label class="required">Status</label>
                  <select name="status" class="form-control" required>
                    <option value="Open" <?php echo ($lead->status == 'Open') ? 'selected' : ''; ?>>Open</option>
                    <option value="Registered" <?php echo ($lead->status == 'Registered') ? 'selected' : ''; ?>>Registered</option>
                    <option value="Hot" <?php echo ($lead->status == 'Hot') ? 'selected' : ''; ?>>Hot</option>
                    <option value="Warm" <?php echo ($lead->status == 'Warm') ? 'selected' : ''; ?>>Warm</option>
                    <option value="Cold" <?php echo ($lead->status == 'Cold') ? 'selected' : ''; ?>>Cold</option>
                    <option value="Not Interested" <?php echo ($lead->status == 'Not Interested') ? 'selected' : ''; ?>>Not Interested</option>
                  </select>
                </div>
              </div>

            </div>
          </div>

          <!-- Academic Details Tab -->
          <div class="tab-pane fade" id="nav-academic" role="tabpanel" aria-labelledby="nav-academic-tab">
            <h5 class="mb-3">Academic Details</h5>
            <div class="table-responsive mb-3">
              <table class="table table-bordered">
                <thead class="bg-light">
                  <tr>
                    <th style="width:18%;">Qualification</th>
                    <th style="width:28%;">School / College</th>
                    <th style="width:18%;">Marks</th>
                    <th style="width:20%;">Percentage of English Marks</th>
                    <th style="width:16%;">Passed Out (Year)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><strong>SSLC</strong></td>
                    <td>
                      <input type="text" name="academic[sslc][school]" class="form-control" value="<?php echo htmlspecialchars($academic_data['sslc']['school'] ?? ''); ?>" placeholder="School/College name">
                    </td>
                    <td>
                      <input type="text" name="academic[sslc][marks]" class="form-control" value="<?php echo htmlspecialchars($academic_data['sslc']['marks'] ?? ''); ?>" placeholder="Marks/GPA">
                    </td>
                    <td>
                      <input type="text" name="academic[sslc][english_marks]" class="form-control" value="<?php echo htmlspecialchars($academic_data['sslc']['english_marks'] ?? ''); ?>" placeholder="English marks %">
                    </td>
                    <td>
                      <input type="text" name="academic[sslc][passed_out]" class="form-control" value="<?php echo htmlspecialchars($academic_data['sslc']['passed_out'] ?? ''); ?>" placeholder="Year">
                    </td>
                  </tr>
                  <tr>
                    <td><strong>HSC</strong></td>
                    <td>
                      <input type="text" name="academic[hsc][school]" class="form-control" value="<?php echo htmlspecialchars($academic_data['hsc']['school'] ?? ''); ?>" placeholder="School/College name">
                    </td>
                    <td>
                      <input type="text" name="academic[hsc][marks]" class="form-control" value="<?php echo htmlspecialchars($academic_data['hsc']['marks'] ?? ''); ?>" placeholder="Marks/GPA">
                    </td>
                    <td>
                      <input type="text" name="academic[hsc][english_marks]" class="form-control" value="<?php echo htmlspecialchars($academic_data['hsc']['english_marks'] ?? ''); ?>" placeholder="English marks %">
                    </td>
                    <td>
                      <input type="text" name="academic[hsc][passed_out]" class="form-control" value="<?php echo htmlspecialchars($academic_data['hsc']['passed_out'] ?? ''); ?>" placeholder="Year">
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Bachelor</strong></td>
                    <td>
                      <input type="text" name="academic[bachelor][school]" class="form-control" value="<?php echo htmlspecialchars($academic_data['bachelor']['school'] ?? ''); ?>" placeholder="School/College name">
                    </td>
                    <td>
                      <input type="text" name="academic[bachelor][marks]" class="form-control" value="<?php echo htmlspecialchars($academic_data['bachelor']['marks'] ?? ''); ?>" placeholder="Marks/GPA">
                    </td>
                    <td>
                      <input type="text" name="academic[bachelor][english_marks]" class="form-control" value="<?php echo htmlspecialchars($academic_data['bachelor']['english_marks'] ?? ''); ?>" placeholder="English marks %">
                    </td>
                    <td>
                      <input type="text" name="academic[bachelor][passed_out]" class="form-control" value="<?php echo htmlspecialchars($academic_data['bachelor']['passed_out'] ?? ''); ?>" placeholder="Year">
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Employment Details Tab -->
          <div class="tab-pane fade" id="nav-employment" role="tabpanel" aria-labelledby="nav-employment-tab">
            <h5 class="mb-3">Employment Details</h5>
            <div class="row g-3 mb-3">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Employer</label>
                  <input type="text" name="employment[employer]" class="form-control" placeholder="Employer name" value="<?php echo htmlspecialchars($employment_data['employer'] ?? ''); ?>">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Job Title</label>
                  <input type="text" name="employment[job_title]" class="form-control" placeholder="Job title / designation" value="<?php echo htmlspecialchars($employment_data['job_title'] ?? ''); ?>">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Period</label>
                  <input type="text" name="employment[period]" class="form-control" placeholder="Jan 2020 - Dec 2022" value="<?php echo htmlspecialchars($employment_data['period'] ?? ''); ?>">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Total Experience (Years)</label>
                  <input type="number" name="employment[total_experience]" min="0" step="0.1" class="form-control" placeholder="2.5" value="<?php echo htmlspecialchars($employment_data['total_experience'] ?? ''); ?>">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>IELTS Status</label>
                  <select name="employment[ielts_status]" class="form-control">
                    <option value="">Select</option>
                    <option value="Not Attempted" <?php echo (isset($employment_data['ielts_status']) && $employment_data['ielts_status'] == 'Not Attempted') ? 'selected' : ''; ?>>Not Attempted</option>
                    <option value="Preparing" <?php echo (isset($employment_data['ielts_status']) && $employment_data['ielts_status'] == 'Preparing') ? 'selected' : ''; ?>>Preparing</option>
                    <option value="Attempted" <?php echo (isset($employment_data['ielts_status']) && $employment_data['ielts_status'] == 'Attempted') ? 'selected' : ''; ?>>Attempted</option>
                    <option value="Passed" <?php echo (isset($employment_data['ielts_status']) && $employment_data['ielts_status'] == 'Passed') ? 'selected' : ''; ?>>Passed</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- Documents Tab -->
          <div class="tab-pane fade" id="nav-documents" role="tabpanel" aria-labelledby="nav-documents-tab">
            <div class="row">
              <div class="col-12">
                <!-- Header Section -->
                <div class="text-center mb-4">
                  <h4 class="text-primary mb-2">Document Upload</h4>
                  <p class="text-muted">Upload all required documents for the application process</p>
                </div>

                <!-- Upload Guidelines -->
                <div class="alert alert-info mb-4">
                  <div class="d-flex align-items-center">
                    <i class="fa fa-info-circle me-2"></i>
                    <div>
                      <strong>Upload Guidelines:</strong> Supported formats: PDF, JPG, JPEG, PNG • Maximum file size: 5 MB per document
                    </div>
                  </div>
                </div>

                <!-- Existing Documents -->
                <?php if (!empty($documents)): ?>
                <div class="mb-4">
                  <h5 class="mb-3">Existing Documents</h5>
                  <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                      <thead class="bg-light">
                        <tr>
                          <th>Document Type</th>
                          <th>File Name</th>
                          <th>Uploaded At</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($documents as $doc): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($document_types[$doc->document_type] ?? $doc->document_type); ?></td>
                          <td><?php echo htmlspecialchars($doc->original_name); ?></td>
                          <td><?php echo date('M j, Y H:i', strtotime($doc->uploaded_at)); ?></td>
                          <td>
                            <a href="<?php echo base_url('lead/download_document/' . $doc->id); ?>" class="btn btn-success btn-sm" title="Download">
                              <i class="fa fa-download"></i>
                            </a>
                            <a href="<?php echo base_url('lead/delete_document/' . $doc->id); ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this document?')">
                              <i class="fa fa-trash"></i>
                            </a>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <?php endif; ?>

                <!-- Documents Grid -->
                <div class="row g-3">
                  <!-- Column 1 -->
                  <div class="col-xl-3 col-lg-6">
                    <div class="form-group">
                      <label>Resume</label>
                      <input type="file" name="documents[resume]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Passport</label>
                      <input type="file" name="documents[passport]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>10th Marksheet</label>
                      <input type="file" name="documents[tenth]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>12th Marksheet</label>
                      <input type="file" name="documents[twelfth]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Diploma</label>
                      <input type="file" name="documents[diploma]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Semester 1</label>
                      <input type="file" name="documents[sem1]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                  </div>

                  <!-- Column 2 -->
                  <div class="col-xl-3 col-lg-6">
                    <div class="form-group">
                      <label>Semester 2</label>
                      <input type="file" name="documents[sem2]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Semester 3</label>
                      <input type="file" name="documents[sem3]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Semester 4</label>
                      <input type="file" name="documents[sem4]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Semester 5</label>
                      <input type="file" name="documents[sem5]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Semester 6</label>
                      <input type="file" name="documents[sem6]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Semester 7</label>
                      <input type="file" name="documents[sem7]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                  </div>

                  <!-- Column 3 -->
                  <div class="col-xl-3 col-lg-6">
                    <div class="form-group">
                      <label>Semester 8</label>
                      <input type="file" name="documents[sem8]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Consolidated Marksheet</label>
                      <input type="file" name="documents[consolidated]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Provisional Certificate</label>
                      <input type="file" name="documents[provisional]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Degree Certificate</label>
                      <input type="file" name="documents[degree]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>LOR (Letter of Recommendation)</label>
                      <input type="file" name="documents[lor]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>MOI (Medium of Instruction)</label>
                      <input type="file" name="documents[moi]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                  </div>

                  <!-- Column 4 -->
                  <div class="col-xl-3 col-lg-6">
                    <div class="form-group">
                      <label>SOP (Statement of Purpose)</label>
                      <input type="file" name="documents[sop]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>IELTS Scorecard</label>
                      <input type="file" name="documents[ielts]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Offer Letter</label>
                      <input type="file" name="documents[offer_letter]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>CAS Letter</label>
                      <input type="file" name="documents[cas]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Medical Certificate</label>
                      <input type="file" name="documents[medical]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Bank Cover Letter</label>
                      <input type="file" name="documents[bank_cover]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Bank Statement</label>
                      <input type="file" name="documents[bank_statement]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-group">
                      <label>Other Documents</label>
                      <input type="file" name="documents[others]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Buttons -->
        <div class="box-footer d-flex justify-content-between mt-4">
          <a href="<?php echo base_url('lead'); ?>" class="btn btn-danger">
            <i class="ti-trash"></i> Cancel
          </a>
          <button type="submit" class="btn btn-success">
            <i class="ti-save-alt"></i> Update Lead
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById('leadForm');
  const mobileInput = document.getElementById('mobile');
  const mobileError = document.getElementById('mobileError');

  // Mobile validation
  if (mobileInput) {
    mobileInput.addEventListener('input', function() {
      validateMobileNumber();
    });

    mobileInput.addEventListener('blur', function() {
      validateMobileNumber();
    });
  }

  function validateMobileNumber() {
    const number = mobileInput.value.trim().replace(/\s+/g, '');
    const regex = /^[6-9]\d{9}$/; // Indian mobile format
    
    if (number && !regex.test(number)) {
      mobileError.style.display = 'block';
      return false;
    } else {
      mobileError.style.display = 'none';
      return true;
    }
  }

  // File size validation for documents
  const fileInputs = form.querySelectorAll('input[type="file"]');
  fileInputs.forEach(input => {
    input.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const fileSize = file.size / 1024 / 1024; // MB
        if (fileSize > 5) {
          alert('File size must be less than 5 MB');
          this.value = '';
        }
        
        // Check file type
        const fileType = file.type.toLowerCase();
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(fileType)) {
          alert('Please upload only PDF, JPG, JPEG, or PNG files');
          this.value = '';
        }
      }
    });
  });

  // Form submission validation
  if (form) {
    form.addEventListener('submit', function (e) {
      let isValid = true;

      // Check basic form validity
      if (!form.checkValidity()) {
        isValid = false;
        // Show the first tab with errors
        const firstInvalidField = form.querySelector(':invalid');
        if (firstInvalidField) {
          const tabPane = firstInvalidField.closest('.tab-pane');
          if (tabPane) {
            const tabId = tabPane.id.replace('nav-', '') + '-tab';
            const tab = document.getElementById(tabId);
            if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
              bootstrap.Tab.getOrCreateInstance(tab).show();
            }
          }
          firstInvalidField.focus();
        }
      }

      // Validate mobile number
      if (!validateMobileNumber()) {
        isValid = false;
        if (mobileError) {
          mobileError.style.display = 'block';
        }
      }

      if (!isValid) {
        e.preventDefault();
        alert('Please fix the errors in the form before submitting.');
      }
    });
  }
});
</script>