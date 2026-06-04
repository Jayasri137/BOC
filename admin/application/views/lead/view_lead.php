<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
/* Your existing CSS styles here */
.enq-wrapper { padding: 30px 0; font-family: "Inter", "Segoe UI", Roboto, Arial, sans-serif; color: #1f2937; }
.enq-card { border: 1px solid #e6e9ee; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 24px rgba(15,23,42,0.06); background: #fff; }
.enq-header { background: linear-gradient(90deg, #0f62fe 0%, #33a1fd 100%); color: #fff; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; }
.enq-header h5 { margin: 0; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
.enq-header i { font-size: 1.2rem; }
.enq-body { background: #f7f9fc; padding: 32px 36px; }
.info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
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
.badge { font-size: 0.75rem; padding: 4px 8px; border-radius: 4px; }
.badge-open { background-color: #28a745; color: white; }
.badge-registered { background-color: #007bff; color: white; }
.badge-hot { background-color: #dc3545; color: white; }
.badge-warm { background-color: #fd7e14; color: white; }
.badge-cold { background-color: #6c757d; color: white; }
.badge-not-interested { background-color: #6c757d; color: white; }
@media (max-width: 1200px) { .info-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 767px) { .enq-body { padding: 20px; } .info-grid { grid-template-columns: 1fr; } .info-block, .message-box, .timestamp { padding: 14px; } }
</style>

<div class="content-wrapper enq-wrapper">
  <div class="container-full">
    <!-- Header -->
    <div class="content-header mb-3">
      <div class="d-flex align-items-center justify-content-between">
        <div class="me-auto p-3">
          <h3 class="page-title mb-1">View Lead</h3>
          <nav>
            <ol class="breadcrumb small text-muted mb-0">
              <li class="breadcrumb-item">
                <a href="#">
                  <i class="mdi mdi-home-outline"></i> Dashboard
                </a>
              </li>
              <li class="breadcrumb-item">
                <a href="<?php echo base_url('lead'); ?>">Leads</a>
              </li>
              <li class="breadcrumb-item active">View Lead</li>
            </ol>
          </nav>
        </div>
        <div class="box-controls pull-right">
          <a href="<?php echo base_url('lead'); ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-arrow-left"></i> Back to Leads
          </a>
          <!-- <a href="<?php echo base_url('lead/edit_lead/' . $lead->id); ?>" class="btn btn-warning btn-sm">
            <i class="fa fa-edit"></i> Edit Lead
          </a> -->
        </div>
      </div>
    </div>

  

    <!-- Enquiry Details -->
    <div class="container">
      <div class="enq-card">
        <div class="enq-header">
          <h5><i class="fa fa-user"></i> Lead Details</h5>
          <small>Name: <?php echo htmlspecialchars($lead->name ?? 'N/A'); ?></small>
        </div>

        <div class="enq-body">
          <div class="info-grid">
            <!-- Basic Information -->
            <div class="info-block">
              <span class="info-label">Full Name</span>
              <span class="info-value"><?php echo htmlspecialchars($lead->name ?? 'N/A'); ?></span>
            </div>  
            <div class="info-block">
              <span class="info-label">Mobile Number</span>
              <span class="info-value"><?php echo htmlspecialchars($lead->mobile ?? 'N/A'); ?></span>
            </div>
            <div class="info-block">
              <span class="info-label">Email</span>
              <span class="info-value">
                <?php if (!empty($lead->email)): ?>
                  <a href="mailto:<?php echo htmlspecialchars($lead->email); ?>" class="text-dark text-decoration-none">
                    <?php echo htmlspecialchars($lead->email); ?>
                  </a>
                <?php else: ?>
                  N/A
                <?php endif; ?>
              </span>
            </div>
            <div class="info-block">
              <span class="info-label">Country</span>
              <span class="info-value"><?php echo htmlspecialchars($lead->country ?? 'N/A'); ?></span>
            </div>
            
            <div class="info-block">
              <span class="info-label">University</span>
              <span class="info-value"><?php echo htmlspecialchars($lead->university ?? 'N/A'); ?></span>
            </div>
            <div class="info-block">
              <span class="info-label">Intake Year</span>
              <span class="info-value"><?php echo htmlspecialchars($lead->intake_year ?? 'N/A'); ?></span>
            </div>
            <div class="info-block">
              <span class="info-label">Assigned To</span>
              <span class="info-value"><?php echo htmlspecialchars($assigned_role_name); ?></span>
            </div>
            <div class="info-block">
              <span class="info-label">Status</span>
              <span class="info-value">
                <?php if (!empty($lead->status)): ?>
                  <span class="badge badge-<?php echo strtolower(str_replace(' ', '-', $lead->status)); ?>">
                    <?php echo htmlspecialchars($lead->status); ?>
                  </span>
                <?php else: ?>
                  <span class="badge badge-secondary">N/A</span>
                <?php endif; ?>
              </span>
            </div>
          </div>

          <!-- Academic Details -->
          <?php if (!empty($academic_data) && (isset($academic_data['sslc']['school']) || isset($academic_data['hsc']['school']) || isset($academic_data['bachelor']['school']))): ?>
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
                    <tr>
                      <td><strong>SSLC</strong></td>
                      <td><?php echo htmlspecialchars($academic_data['sslc']['school'] ?? 'N/A'); ?></td>
                      <td><?php echo htmlspecialchars($academic_data['sslc']['marks'] ?? 'N/A'); ?></td>
                      <td><?php echo htmlspecialchars($academic_data['sslc']['english_marks'] ?? 'N/A'); ?></td>
                      <td><?php echo htmlspecialchars($academic_data['sslc']['passed_out'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                      <td><strong>HSC</strong></td>
                      <td><?php echo htmlspecialchars($academic_data['hsc']['school'] ?? 'N/A'); ?></td>
                      <td><?php echo htmlspecialchars($academic_data['hsc']['marks'] ?? 'N/A'); ?></td>
                      <td><?php echo htmlspecialchars($academic_data['hsc']['english_marks'] ?? 'N/A'); ?></td>
                      <td><?php echo htmlspecialchars($academic_data['hsc']['passed_out'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                      <td><strong>Bachelor</strong></td>
                      <td><?php echo htmlspecialchars($academic_data['bachelor']['school'] ?? 'N/A'); ?></td>
                      <td><?php echo htmlspecialchars($academic_data['bachelor']['marks'] ?? 'N/A'); ?></td>
                      <td><?php echo htmlspecialchars($academic_data['bachelor']['english_marks'] ?? 'N/A'); ?></td>
                      <td><?php echo htmlspecialchars($academic_data['bachelor']['passed_out'] ?? 'N/A'); ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <!-- Employment Details -->
          <?php if (!empty($employment_data) && (isset($employment_data['employer']) || isset($employment_data['job_title']) || isset($employment_data['period']))): ?>
          <div class="mt-4">
            <div class="message-box">
              <h6>Employment Details</h6>
              <div class="info-grid">
                <div class="info-block">
                  <span class="info-label">Employer</span>
                  <span class="info-value"><?php echo htmlspecialchars($employment_data['employer'] ?? 'N/A'); ?></span>
                </div>
                <div class="info-block">
                  <span class="info-label">Job Title</span>
                  <span class="info-value"><?php echo htmlspecialchars($employment_data['job_title'] ?? 'N/A'); ?></span>
                </div>
                <div class="info-block">
                  <span class="info-label">Period</span>
                  <span class="info-value"><?php echo htmlspecialchars($employment_data['period'] ?? 'N/A'); ?></span>
                </div>
                <div class="info-block">
                  <span class="info-label">Total Experience</span>
                  <span class="info-value">
                    <?php 
                    if (!empty($employment_data['total_experience'])) {
                      echo htmlspecialchars($employment_data['total_experience']) . ' years';
                    } else {
                      echo 'N/A';
                    }
                    ?>
                  </span>
                </div>
                <div class="info-block">
                  <span class="info-label">IELTS Status</span>
                  <span class="info-value"><?php echo htmlspecialchars($employment_data['ielts_status'] ?? 'N/A'); ?></span>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <!-- Documents Section -->
          <?php if (!empty($documents)): ?>
          <div class="mt-4">
            <div class="message-box">
              <h6>Uploaded Documents</h6>
              <div class="table-responsive">
                <table class="table table-bordered">
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
                      <td><?php echo htmlspecialchars($doc->document_type); ?></td>
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
          </div>
          <?php else: ?>
          <div class="mt-4">
            <div class="alert alert-warning">
              <i class="fa fa-exclamation-triangle"></i> No documents uploaded for this lead.
            </div>
          </div>
          <?php endif; ?>

          <!-- Timestamps -->
          <div class="timestamp-row">
            <div class="timestamp">
              <span class="info-label">Created At</span>
              <div class="info-value">
                <i class="fa fa-calendar text-primary"></i> 
                <?php echo !empty($lead->created_at) ? date('Y-m-d ', strtotime($lead->created_at)) : 'N/A'; ?>
              </div>
            </div>
            <div class="timestamp">
              <span class="info-label">Updated At</span>
              <div class="info-value">
                <i class="fa fa-sync-alt text-success"></i> 
                <?php echo !empty($lead->updated_at) ? date('Y-m-d ', strtotime($lead->updated_at)) : 'N/A'; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>