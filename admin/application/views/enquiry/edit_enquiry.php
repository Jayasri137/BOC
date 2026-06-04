<?php defined("BASEPATH") or exit("No direct script access allowed"); ?>
<?php // Get enquiry ID from URL


$enquiry_id = $this->uri->segment(3); // Fetch enquiry data
$enquiry_data = $this->db
  ->get_where(
    "enquiry",

    [
      "id" => $enquiry_id,
    ]
  )
  ->row(); // Fetch academic data if exists
$academic_data = $this->db
  ->get_where("enquiry_academic", ["enquiry_id" => $enquiry_id])
  ->result();
$academic_array = [];
foreach ($academic_data as $acad) {
  $academic_array[$acad->qualification_type] = [
    "school" => $acad->school,
    "marks" => $acad->marks,
    "english_marks" => $acad->english_marks,
    "passed_out" => $acad->passed_out,
  ];
} // Fetch employment data if exists
$employment_data = $this->db
  ->get_where("enquiry_employment", ["enquiry_id" => $enquiry_id])
  ->row(); // Fetch dropdown options
$enquiry_type = $this->db->get_where("enquiry_type", ["status" => "Active"]);
$country = $this->db->get("country");
$university = $this->db->get("university");
$intake_year = $this->db->get("intake_year"); // Get all universities with country relationship for client-side filtering
$all_universities = $this->db
  ->select("u.id, u.name, u.country_id, c.name as country_name")
  ->from("university u")
  ->join("country c", "c.id = u.country_id", "left")
  ->where("u.status", 1)
  ->order_by("u.name", "ASC")
  ->get()
  ->result();
// Convert to JavaScript-friendly format
$universities_js = [];
foreach ($all_universities as $uni) {
  $universities_js[] = [
    "id" => $uni->id,
    "name" => $uni->name,
    "country_id" => $uni->country_id,
    "country_name" => $uni->country_name,
  ];
}
?>

<div id="flash-data"
  data-success="<?= htmlspecialchars(
                  $this->session->flashdata("success") ?? ""
                ) ?>"
  data-error="<?= htmlspecialchars(
                $this->session->flashdata("error") ?? ""
              ) ?>"
  data-warning="<?= htmlspecialchars(
                  $this->session->flashdata("warning") ?? ""
                ) ?>">
</div>
<div class="content-wrapper">
  <div class="container-full">

    <!-- Header -->
    <div class="content-header d-flex align-items-center justify-content-between">
      <div>
        <h3 class="page-title">Edit Enquiry</h3>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="<?= base_url("dashboard") ?>">
                <i class="mdi mdi-home-outline"></i> Dashboard
              </a>
            </li>
            <li class="breadcrumb-item">Edit Enquiry</li>
          </ol>
        </nav>
      </div>
      <div>
        <a href="<?= base_url("enquiry") ?>" class="btn btn-primary btn-sm">
          <i class="fa fa-arrow-left"></i> Back
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
          </div>
        </nav>
      </div>

      <form id="enquiryForm" action="<?= base_url(
                                        "enquiry/update_enquiry/" . $enquiry_id
                                      ) ?>" method="post" novalidate>

        <!-- Basic Details Tab -->
        <div class="tab-content" id="nav-tabContent">
          <div class="tab-pane fade show active" id="nav-basic" role="tabpanel" aria-labelledby="nav-basic-tab">
            <div class="row g-3">

              <!-- Full Name -->
              <div class="col-md-3">
                <div class="form-group">
                  <label class="required">Full Name</label>
                  <input type="text" name="first_name" class="form-control" placeholder="Enter Full Name"
                    value="<?= htmlspecialchars(
                              $enquiry_data->first_name ?? ""
                            ) ?>" required>
                </div>
              </div>

              <!-- Email -->
              <div class="col-md-3">
                <div class="form-group">
                  <label class="required">Email</label>
                  <input type="email" name="email" class="form-control" placeholder="Enter Email"
                    value="<?= htmlspecialchars(
                              $enquiry_data->email ?? ""
                            ) ?>" required>
                </div>
              </div>

              <!-- Country -->
              <div class="col-md-3">
                <div class="form-group">
                  <label class="required">Country</label>
                  <select id="countrySelect" name="country_id" class="form-control" required>
                    <option value="">-- Select Country --</option>
                    <?php if ($country && $country->num_rows() > 0): ?>
                      <?php foreach ($country->result() as $c): ?>
                        <option
                          value="<?= $c->id ?>"
                          data-code="<?= htmlspecialchars(
                                        $c->country_code ?? ""
                                      ) ?>"
                          <?= $enquiry_data->country_id == $c->id
                            ? "selected"
                            : "" ?>>
                          <?= htmlspecialchars($c->name ?? "-") ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <!-- Mobile -->
              <div class="col-md-3">
                <div class="form-group">
                  <label class="required">Mobile</label>
                  <div class="input-group">
                    <span class="input-group-text" id="countryCodeLabel">
                      <?php
                      // Set initial country code
                      $country_code = "+--";
                      if ($enquiry_data->country_id) {
                        $country_row = $this->db
                          ->get_where("country", [
                            "id" => $enquiry_data->country_id,
                          ])
                          ->row();
                        $country_code = $country_row->country_code ?? "+--";
                      }
                      echo $country_code;
                      ?>
                    </span>
                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Enter Mobile"
                      value="<?= htmlspecialchars(
                                $enquiry_data->mobile ?? ""
                              ) ?>" required>
                  </div>
                  <small id="mobileError" class="text-danger" style="display:none;">Invalid mobile number format</small>
                </div>
              </div>

              <!-- Gender -->
              <div class="col-md-3">
                <div class="form-group">
                  <label>Gender</label>
                  <select name="gender" class="form-control">
                    <option value="">Select Gender</option>
                    <option value="Male" <?= $enquiry_data->gender == "Male"
                                            ? "selected"
                                            : "" ?>>Male</option>
                    <option value="Female" <?= $enquiry_data->gender == "Female"
                                              ? "selected"
                                              : "" ?>>Female</option>
                    <option value="Other" <?= $enquiry_data->gender == "Other"
                                            ? "selected"
                                            : "" ?>>Other</option>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label class="">Source of Enquiry</label>
                  <select name="enquiry_type_id" class="form-control">
                    <option value="">Select Type</option>
                    <?php if (
                      $enquiry_type &&
                      $enquiry_type->num_rows() > 0
                    ): ?>
                      <?php foreach ($enquiry_type->result() as $etype): ?>
                        <option value="<?= $etype->id ?>"
                          <?= $enquiry_data->enquiry_type_id == $etype->id
                            ? "selected"
                            : "" ?>>
                          <?= htmlspecialchars($etype->enquiry_type) ?>
                        </option>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <option value="">No active enquiry types</option>
                    <?php endif; ?>
                  </select>
                </div>
              </div>
              <!-- In edit_enquiry.php, find the Role section and update it: -->
              <div class="col-md-3">
                <div class="form-group">
                  <label class="required">Role</label>
                  <select name="user_role_id" class="form-control" required>
                    <option value="">Select Role</option>
                    <?php
                    $ci = &get_instance();
                    $roles = $ci->db
                      ->query(
                        "SELECT ug_id, ug_name FROM user_group WHERE ug_id != 1 ORDER BY ug_id DESC"
                      )
                      ->result();
                    if ($roles && count($roles) > 0):
                      foreach ($roles as $role):
                        $selected =
                          isset($enquiry_data->user_role_id) &&
                          $enquiry_data->user_role_id == $role->ug_id
                          ? "selected"
                          : ""; ?>
                        <option value="<?= $role->ug_id ?>" <?= $selected ?>>
                          <?= htmlspecialchars($role->ug_name) ?>
                        </option>
                      <?php
                      endforeach; ?>
                    <?php
                    else:
                    ?>
                      <option value="">No roles found</option>
                    <?php
                    endif;
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="required">User Name</label>
                  <input type="text"
                    id="user_name"
                    name="user_name"
                    class="form-control"
                    value="<?= isset($enquiry_data->user_name)
                              ? htmlspecialchars($enquiry_data->user_name)
                              : "" ?>"
                    required>
                </div>
              </div>


              <!-- Intake Year -->
              <div class="col-md-3">
                <div class="form-group">
                  <label class="required">Intake Year</label>
                  <select id="intakeSelect" name="intake_year_id" class="form-control" required>
                    <option value="">-- Select Intake Year --</option>
                    <?php if ($intake_year && $intake_year->num_rows() > 0): ?>
                      <?php foreach ($intake_year->result() as $iy): ?>
                        <option value="<?= $iy->id ?>"
                          <?= $enquiry_data->intake_year_id == $iy->id
                            ? "selected"
                            : "" ?>>
                          <?= htmlspecialchars($iy->intake_year ?? "-") ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <!-- University -->
              <div class="col-md-3">
                <div class="form-group">
                  <label>University</label>
                  <select id="universitySelect" name="university_id" class="form-control">
                    <option value="">-- Select University --</option>
                  </select>
                </div>
              </div>


              <!-- Remarks -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Remarks</label>
                  <textarea name="remarks" class="form-control" rows="2" placeholder="Enter Remarks"><?= htmlspecialchars(
                                                                                                        $enquiry_data->remarks ?? ""
                                                                                                      ) ?></textarea>
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
                  <?php $quals = [
                    "sslc" => "SSLC",
                    "hsc" => "HSC",
                    "diploma" => "Diploma",
                    "bachelor" => "Bachelor",
                    "master" => "Master",
                    "other" => "Other",
                  ]; ?>
                  <?php foreach ($quals as $key => $label): ?>
                    <tr>
                      <td><strong><?= $label ?></strong></td>
                      <td>
                        <input type="text" name="academic[<?= $key ?>][school]" class="form-control"
                          value="<?= htmlspecialchars(
                                    $academic_array[$key]["school"] ?? ""
                                  ) ?>" placeholder="School/College name">
                      </td>
                      <td>
                        <input type="text" name="academic[<?= $key ?>][marks]" class="form-control"
                          value="<?= htmlspecialchars(
                                    $academic_array[$key]["marks"] ?? ""
                                  ) ?>" placeholder="Marks/GPA">
                      </td>
                      <td>
                        <input type="text" name="academic[<?= $key ?>][english_marks]" class="form-control"
                          value="<?= htmlspecialchars(
                                    $academic_array[$key]["english_marks"] ?? ""
                                  ) ?>" placeholder="English marks %">
                      </td>
                      <td>
                        <input type="text" name="academic[<?= $key ?>][passed_out]" class="form-control"
                          value="<?= htmlspecialchars(
                                    $academic_array[$key]["passed_out"] ?? ""
                                  ) ?>" placeholder="Year">
                      </td>
                    </tr>
                  <?php endforeach; ?>
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
                  <input type="text" name="employment[employer]" class="form-control" placeholder="Employer name"
                    value="<?= htmlspecialchars(
                              $employment_data->employer ?? ""
                            ) ?>">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Job Title</label>
                  <input type="text" name="employment[job_title]" class="form-control" placeholder="Job title / designation"
                    value="<?= htmlspecialchars(
                              $employment_data->job_title ?? ""
                            ) ?>">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Period</label>
                  <input type="text" name="employment[period]" class="form-control" placeholder="Jan 2020 - Dec 2022"
                    value="<?= htmlspecialchars(
                              $employment_data->period ?? ""
                            ) ?>">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Total Experience (Years)</label>
                  <input type="number" name="employment[total_experience]" min="0" step="0.1" class="form-control" placeholder="2.5"
                    value="<?= htmlspecialchars(
                              $employment_data->total_experience ?? ""
                            ) ?>">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>IELTS Status</label>
                  <select name="employment[ielts_status]" class="form-control">
                    <option value="">Select</option>
                    <option value="Not Attempted" <?= isset(
                                                    $employment_data->ielts_status
                                                  ) && $employment_data->ielts_status == "Not Attempted"
                                                    ? "selected"
                                                    : "" ?>>Not Attempted</option>
                    <option value="Preparing" <?= isset(
                                                $employment_data->ielts_status
                                              ) && $employment_data->ielts_status == "Preparing"
                                                ? "selected"
                                                : "" ?>>Preparing</option>
                    <option value="Attempted" <?= isset(
                                                $employment_data->ielts_status
                                              ) && $employment_data->ielts_status == "Attempted"
                                                ? "selected"
                                                : "" ?>>Attempted</option>
                    <option value="Passed" <?= isset(
                                              $employment_data->ielts_status
                                            ) && $employment_data->ielts_status == "Passed"
                                              ? "selected"
                                              : "" ?>>Passed</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Buttons -->
        <div class="box-footer d-flex justify-content-between mt-4">
          <a href="<?= base_url("enquiry") ?>" class="btn btn-danger">
            <i class="ti-trash"></i> Cancel
          </a>
          <button type="submit" class="btn btn-success">
            <i class="ti-save-alt"></i> Update Enquiry
          </button>
        </div>

      </form>
    </div>
  </div>
</div>


<script>
  // Store all universities data in JavaScript variable
  const allUniversities = <?= json_encode($universities_js) ?>;

  // Store the initially selected university ID for pre-selection
  const initialSelectedUniversityId = <?= $enquiry_data->university_id ?? 0 ?>;

  document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('enquiryForm');
    const countrySelect = document.getElementById('countrySelect');
    const codeLabel = document.getElementById('countryCodeLabel');
    const mobileInput = document.getElementById('mobile');
    const mobileError = document.getElementById('mobileError');
    const universitySelect = document.getElementById('universitySelect');

    // Initialize university data structure
    const universitiesByCountry = {};

    // Organize universities by country_id
    allUniversities.forEach(function(university) {
      const countryId = university.country_id;
      if (!universitiesByCountry[countryId]) {
        universitiesByCountry[countryId] = [];
      }
      universitiesByCountry[countryId].push(university);
    });

    // Update country code prefix dynamically
    if (countrySelect) {
      countrySelect.addEventListener('change', function() {
        const code = this.options[this.selectedIndex].dataset.code || '+--';
        if (codeLabel) codeLabel.textContent = code;

        // Load universities for selected country
        loadUniversitiesByCountry(this.value);
      });
    }

    // Function to load universities based on selected country
    function loadUniversitiesByCountry(countryId) {
      universitySelect.innerHTML = '<option value="">-- Select University --</option>';

      // No country selected ? show all & make required
      if (!countryId) {
        universitySelect.disabled = false;
        universitySelect.required = true;
        showAllUniversities();
        return;
      }

      const countryUniversities = universitiesByCountry[countryId] || [];

      // ? NO universities ? disable + remove required
      if (countryUniversities.length === 0) {
        universitySelect.innerHTML =
          '<option value="">No universities found for selected country</option>';
        universitySelect.disabled = true;
        universitySelect.required = false;
        universitySelect.classList.remove('is-invalid');
        return;
      }

      // ? Universities exist ? enable + required
      universitySelect.disabled = false;
      universitySelect.required = true;

      countryUniversities.forEach(function(university) {
        const option = document.createElement('option');
        option.value = university.id;
        option.textContent = university.name;

        if (university.id == initialSelectedUniversityId) {
          option.selected = true;
        }

        universitySelect.appendChild(option);
      });
    }


    // Function to show all universities (when no country is selected)
    function showAllUniversities() {
      universitySelect.innerHTML = '<option value="">-- Select University --</option>';

      if (allUniversities.length === 0) {
        universitySelect.innerHTML = '<option value="">No universities available</option>';
        return;
      }

      // Group universities by country name for better organization
      const universitiesByCountryName = {};

      allUniversities.forEach(function(university) {
        const countryName = university.country_name || 'Unknown Country';
        if (!universitiesByCountryName[countryName]) {
          universitiesByCountryName[countryName] = [];
        }
        universitiesByCountryName[countryName].push(university);
      });

      // Add optgroups for each country
      Object.keys(universitiesByCountryName).sort().forEach(function(countryName) {
        const optgroup = document.createElement('optgroup');
        optgroup.label = countryName;

        universitiesByCountryName[countryName].forEach(function(university) {
          const option = document.createElement('option');
          option.value = university.id;
          option.textContent = university.name;

          // Pre-select if this is the initially selected university
          if (university.id == initialSelectedUniversityId) {
            option.selected = true;
          }

          optgroup.appendChild(option);
        });

        universitySelect.appendChild(optgroup);
      });
    }

    // Initialize universities based on current country selection
    const currentCountryId = countrySelect ? countrySelect.value : null;
    if (currentCountryId) {
      loadUniversitiesByCountry(currentCountryId);
    } else {
      showAllUniversities();
    }

    // Basic mobile validation based on country code
    if (mobileInput) {
      mobileInput.addEventListener('input', function() {
        const code = codeLabel ? codeLabel.textContent.trim() : '+--';
        const number = mobileInput.value.trim();
        let regex;

        if (code === '+91') { // India
          regex = /^[6-9]\d{9}$/;
        } else if (code === '+1') { // USA/Canada
          regex = /^[2-9]\d{9}$/;
        } else if (code === '+44') { // UK
          regex = /^\d{10}$/;
        } else { // Default for others
          regex = /^\d{6,15}$/;
        }

        if (!regex.test(number)) {
          mobileError.style.display = 'block';
        } else {
          mobileError.style.display = 'none';
        }
      });
    }

    // Validate current tab before proceeding
    function validateCurrentTab(tabId) {
      const currentTab = document.getElementById(tabId);
      const requiredFields = currentTab.querySelectorAll('[required]');
      let isValid = true;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          isValid = false;
          field.classList.add('is-invalid');
        } else {
          field.classList.remove('is-invalid');
        }
      });

      if (!isValid) {
        alert('Please fill all required fields in the current section before proceeding.');
      }

      return isValid;
    }

    // Final form validation
    if (form) {
      form.addEventListener('submit', function(e) {
        // Validate university selection - ONLY if university field is enabled and required
        if (universitySelect.required && universitySelect.value === '') {
          e.preventDefault();
          universitySelect.classList.add('is-invalid');
          universitySelect.focus();
          alert('Please select a university.');
          return;
        } else {
          universitySelect.classList.remove('is-invalid');
        }

        // Validate mobile number format
        if (mobileError && mobileError.style.display === 'block') {
          e.preventDefault();
          mobileInput.focus();
          alert('Please enter a valid mobile number based on country code.');
          return;
        }

        if (!form.checkValidity()) {
          e.preventDefault();
          // Show the first tab with errors
          const firstInvalidField = form.querySelector(':invalid');
          if (firstInvalidField) {
            const tabPane = firstInvalidField.closest('.tab-pane');
            if (tabPane) {
              const tabId = tabPane.id.replace('nav-', '') + '-tab';
              const tab = document.getElementById(tabId);
              const bsTab = bootstrap.Tab.getOrCreateInstance(tab);
              bsTab.show();
            }
            firstInvalidField.focus();
          }
          form.reportValidity();
        }
      });
    }

    // Add validation to university select on change
    if (universitySelect) {
      universitySelect.addEventListener('change', function() {
        if (this.value !== '') {
          this.classList.remove('is-invalid');
        }
      });
    }
  });
</script>