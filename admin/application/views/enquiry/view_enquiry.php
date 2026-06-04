<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
/* PROFESSIONAL ENQUIRY DETAILS UI (single-column layout) */

.enq-wrapper { padding: 30px 0; font-family: "Inter", "Segoe UI", Roboto, Arial, sans-serif; color: #1f2937; }
.enq-card { border: 1px solid #e6e9ee; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 24px rgba(15,23,42,0.06); background: #fff; }
.enq-header { background: linear-gradient(90deg, #0f62fe 0%, #33a1fd 100%); color: #fff; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; }
.enq-header h5 { margin: 0; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
.enq-header i { font-size: 1.2rem; }

.enq-body { background: #f7f9fc; padding: 32px 36px; }
.info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
.info-block { background: #fff; border: 1px solid #eef2f6; border-radius: 10px; padding: 16px 18px; box-shadow: 0 2px 8px rgba(15,23,42,0.03); transition: all 0.3s ease; }
.info-block:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(15,23,42,0.06); }
.info-label { display: block; font-size: 0.82rem; color: #6b7280; margin-bottom: 6px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.02em; }
.info-value { font-size: 1rem; color: #111827; word-break: break-word; }

.message-box { background: #fff; border: 1px solid #eef2f6; border-radius: 10px; padding: 20px 24px; box-shadow: 0 2px 8px rgba(15,23,42,0.03); }
.message-box h6 { color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.82rem; margin-bottom: 10px; letter-spacing: 0.02em; }
.message-content { font-size: 1rem; color: #0f1724; white-space: pre-wrap; line-height: 1.6; }

.timestamp-row { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px; }
.timestamp { flex: 1; background: #fff; border: 1px solid #eef2f6; border-radius: 10px; padding: 16px; box-shadow: 0 2px 8px rgba(15,23,42,0.03); }
.timestamp .info-label { margin-bottom: 4px; }
.timestamp i { margin-right: 8px; }

.alert-warning { border-radius: 8px; }

/* responsive adjustments */
@media (max-width: 767px) {
  .enq-body { padding: 20px; }
  .info-block, .message-box, .timestamp { padding: 14px; }
}
</style>

<div class="content-wrapper enq-wrapper">
  <div class="container-full">
    <!-- Header -->
    <div class="content-header mb-3">
      <div class="d-flex align-items-center justify-content-between">
        <div class="me-auto p-3">
          <h3 class="page-title mb-1">View Enquiry</h3>
          <nav>
            <ol class="breadcrumb small text-muted mb-0">
              <li class="breadcrumb-item">
                <a href="<?= base_url('dashboard'); ?>">
                  <i class="mdi mdi-home-outline"></i> Dashboard
                </a>
              </li>
              <li class="breadcrumb-item active">Enquiry</li>
            </ol>
          </nav>
        </div>
        <div class="box-controls pull-right">
          <a href="<?= base_url('enquiry'); ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
      </div>
    </div>

    <!-- Enquiry Details -->
    <div class="container">
      <div class="enq-card">
        <div class="enq-header">
          <h5><i class="fa fa-envelope"></i> Enquiry Details</h5>
          <small>Name: <?= !empty($enquiry->first_name) ? htmlspecialchars($enquiry->first_name) : '—'; ?></small>
        </div>

        <div class="enq-body">
          <?php if (!empty($enquiry)) : ?>
            <div class="info-grid">
              <!-- Basic Information -->
              <div class="info-block">
                <span class="info-label">Full Name</span>
                <span class="info-value"><?= htmlspecialchars($enquiry->first_name . ' ' . ($enquiry->last_name ?? '')); ?></span>
              </div>  
              <div class="info-block">
                <span class="info-label">Mobile Number</span>
                <span class="info-value"><?= htmlspecialchars($enquiry->mobile ?? '-'); ?></span>
              </div>
              <div class="info-block">
                <span class="info-label">Email</span>
                <span class="info-value">
                  <a href="mailto:<?= htmlspecialchars($enquiry->email); ?>" class="text-dark text-decoration-none">
                    <?= htmlspecialchars($enquiry->email ?? '-'); ?>
                  </a>
                </span>
              </div>
              <div class="info-block">
                <span class="info-label">Gender</span>
                <span class="info-value"><?= htmlspecialchars($enquiry->gender ?? '-'); ?></span>
              </div>
              
              <!-- Country, University, Intake Year -->
              <?php 
              // Fetch related data
              $country_data = $this->db->get_where('country', ['id' => $enquiry->country_id])->row();
              $university_data = $this->db->get_where('university', ['id' => $enquiry->university_id])->row();
              $intake_year_data = $this->db->get_where('intake_year', ['id' => $enquiry->intake_year_id])->row();
              $enquiry_type_data = $this->db->get_where('enquiry_type', ['id' => $enquiry->enquiry_type_id])->row();
              ?>
              
              <div class="info-block">
                <span class="info-label">Country</span>
                <span class="info-value"><?= htmlspecialchars($country_data->name ?? '-'); ?></span>
              </div>
              <div class="info-block">
                <span class="info-label">University</span>
                <span class="info-value"><?= htmlspecialchars($university_data->name ?? '-'); ?></span>
              </div>
              <div class="info-block">
                <span class="info-label">Intake Year</span>
                <span class="info-value"><?= htmlspecialchars($intake_year_data->intake_year ?? '-'); ?></span>
              </div>
              <div class="info-block">
                <span class="info-label">Enquiry Type</span>
                <span class="info-value"><?= htmlspecialchars($enquiry_type_data->enquiry_type ?? '-'); ?></span>
              </div>
              
              <!-- Additional Information -->
              <!-- <div class="info-block">
                <span class="info-label">Branch</span>
                <span class="info-value"><?= htmlspecialchars($enquiry->branch ?? '-'); ?></span>
              </div>
              <div class="info-block">
                <span class="info-label">Counsellor</span>
                <span class="info-value"><?= htmlspecialchars($enquiry->counsellor ?? '-'); ?></span>
              </div>
              <div class="info-block">
                <span class="info-label">Source of Enquiry</span>
                <span class="info-value"><?= htmlspecialchars($enquiry->source_of_enquiry ?? '-'); ?></span>
              </div> -->
              <div class="info-block">
                <span class="info-label">Status</span>
                <span class="info-value">
                  <?php if($enquiry->status == 'moved_to_lead'): ?>
                    <span class="badge bg-success">Moved to Lead</span>
                  <?php else: ?>
                    <span class="badge bg-primary">Active</span>
                  <?php endif; ?>
                </span>
              </div>
            </div>

            <!-- Remarks Section -->
            <?php if (!empty($enquiry->remarks)): ?>
            <div class="mt-4">
              <div class="message-box">
                <h6>Remarks</h6>
                <div class="message-content"><?= nl2br(htmlspecialchars($enquiry->remarks)); ?></div>
              </div>
            </div>
            <?php endif; ?>

            <!-- Academic Details -->
            <?php if (!empty($enquiry->academic_data)): ?>
            <div class="mt-4">
              <div class="message-box">
                <h6>Academic Details</h6>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead class="bg-light">
                      <tr>
                        <th>Qualification</th>
                        <th>School/College</th>
                        <th>Marks</th>
                        <th>English Marks</th>
                        <th>Passed Out</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $quals = ['sslc'=>'SSLC','hsc'=>'HSC','diploma'=>'Diploma','bachelor'=>'Bachelor','master'=>'Master','other'=>'Other']; ?>
                      <?php foreach ($quals as $key => $label): ?>
                        <?php if (!empty($enquiry->academic_data[$key]['school']) || !empty($enquiry->academic_data[$key]['marks'])): ?>
                          <tr>
                            <td><strong><?= $label ?></strong></td>
                            <td><?= htmlspecialchars($enquiry->academic_data[$key]['school'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($enquiry->academic_data[$key]['marks'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($enquiry->academic_data[$key]['english_marks'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($enquiry->academic_data[$key]['passed_out'] ?? '-') ?></td>
                          </tr>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <!-- Employment Details -->
            <?php if (!empty($enquiry->employment_data)): ?>
            <div class="mt-4">
              <div class="message-box">
                <h6>Employment Details</h6>
                <div class="info-grid">
                  <div class="info-block">
                    <span class="info-label">Employer</span>
                    <span class="info-value"><?= htmlspecialchars($enquiry->employment_data->employer ?? '-'); ?></span>
                  </div>
                  <div class="info-block">
                    <span class="info-label">Job Title</span>
                    <span class="info-value"><?= htmlspecialchars($enquiry->employment_data->job_title ?? '-'); ?></span>
                  </div>
                  <div class="info-block">
                    <span class="info-label">Period</span>
                    <span class="info-value"><?= htmlspecialchars($enquiry->employment_data->period ?? '-'); ?></span>
                  </div>
                  <div class="info-block">
                    <span class="info-label">Total Experience</span>
                    <span class="info-value"><?= htmlspecialchars($enquiry->employment_data->total_experience ?? '-'); ?> years</span>
                  </div>
                  <div class="info-block">
                    <span class="info-label">IELTS Status</span>
                    <span class="info-value"><?= htmlspecialchars($enquiry->employment_data->ielts_status ?? '-'); ?></span>
                  </div>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <!-- Timestamps -->
            <div class="timestamp-row">
              <div class="timestamp">
                <span class="info-label">Created At</span>
                <div class="info-value">
                  <i class="fa fa-calendar text-primary"></i> <?= htmlspecialchars($enquiry->created_at); ?>
                </div>
              </div>
              <div class="timestamp">
                <span class="info-label">Updated At</span>
                <div class="info-value">
                  <i class="fa fa-sync-alt text-success"></i> <?= htmlspecialchars($enquiry->updated_at); ?>
                </div>
              </div>
            </div>
          <?php else : ?>
            <div class="alert alert-warning text-center mt-3">
              <i class="fa fa-exclamation-triangle me-2"></i> No enquiry details found.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>