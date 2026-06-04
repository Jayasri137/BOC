<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
/* Clean UI style similar to your web enquiry view */
.enq-wrapper { padding: 30px 0; font-family: "Inter", "Segoe UI", Roboto, Arial, sans-serif; color: #1f2937; }
.enq-card { border: 1px solid #e6e9ee; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 24px rgba(15,23,42,0.06); background: #fff; margin-bottom: 20px; }
.enq-header { background: linear-gradient(90deg, #0f62fe 0%, #33a1fd 100%); color: #fff; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; }
.enq-body { background: #f7f9fc; padding: 20px; }
.info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; }
.info-block { background: #fff; border: 1px solid #eef2f6; border-radius: 10px; padding: 12px 14px; }
.info-label { display:block; font-size:0.78rem; color:#6b7280; margin-bottom:6px; font-weight:600; text-transform:uppercase; }
.info-value { font-size:0.95rem; color:#111827; word-break:break-word; }
/* Full width message block */
.full-width-block { grid-column: 1 / -1; }
.alert-small { margin-top: 18px; }
@media(max-width:640px){ .info-grid { grid-template-columns: 1fr; } }
</style>

<?php
// ---------------------------------------------
// Fetch record by ID (from URI / GET / variable)
// ---------------------------------------------
$id = null;

// 1. From controller variable
if (isset($enquiry) && !empty($enquiry)) {
    if (is_object($enquiry) && isset($enquiry->id)) {
        $id = (int)$enquiry->id;
    } elseif (is_array($enquiry) && isset($enquiry['id'])) {
        $id = (int)$enquiry['id'];
    }
}

// 2. From URI (segment 3 or 4)
if (empty($id)) {
    $seg3 = (int)$this->uri->segment(3);
    $seg4 = (int)$this->uri->segment(4);
    if ($seg3 > 0) $id = $seg3;
    elseif ($seg4 > 0) $id = $seg4;
}

// 3. From GET parameter
if (empty($id)) {
    $get_id = $this->input->get('id', true);
    if (!empty($get_id) && is_numeric($get_id)) {
        $id = (int)$get_id;
    }
}

// 4. Fetch record safely from contact_enquiry table
$contact = null;
if (!empty($id)) {
    $contact = $this->db->get_where('contact_enquiry', ['id' => $id])->row();
}
?>

<div class="content-wrapper enq-wrapper">
  <div class="container-full">
    <div class="content-header mb-3">
      <div class="d-flex align-items-center justify-content-between">
        <div class="me-auto p-3">
          <h3 class="page-title mb-1">Contact Enquiry Details</h3>
          <div class="small text-muted">View individual contact enquiry</div>
        </div>
        <div>
          <a href="<?= base_url('contact'); ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-arrow-left"></i> Back to List
          </a>
        </div>
      </div>
    </div>

    <div class="container">
      <?php if (!empty($contact)): ?>
        <?php
          $id        = (int)$contact->id;
          $name      = $contact->name ?? '-';
          $email     = $contact->email ?? '-';
          $phone     = $contact->phone ?? '-';
          $subject   = $contact->subject ?? '-';
          $message   = $contact->message ?? '-';
          $created   = !empty($contact->created_at) ? date('d-M-Y', strtotime($contact->created_at)) : '-';
        ?>

        <div class="enq-card">
          <div class="enq-header">
            <div>
              <h5 style="margin:0; font-size:1rem;">
                <i class="fa fa-envelope"></i> <?= htmlspecialchars($name ?: '—'); ?>
              </h5>
              <div style="font-size:0.85rem; margin-top:6px; opacity:0.9;">
                <?= htmlspecialchars($email !== '-' ? $email : 'No email'); ?>
              </div>
            </div>
            <div>
              <span style="font-size:0.85rem;">Enquiry ID: <strong>#<?= $id; ?></strong></span>
            </div>
          </div>

          <div class="enq-body">
            <div class="info-grid">
              <div class="info-block">
                <span class="info-label">Name</span>
                <span class="info-value"><?= htmlspecialchars($name ?: '-'); ?></span>
              </div>

              <div class="info-block">
                <span class="info-label">Email</span>
                <span class="info-value">
                  <?php if (!empty($email) && $email !== '-'): ?>
                    <a href="mailto:<?= htmlspecialchars($email); ?>"><?= htmlspecialchars($email); ?></a>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </span>
              </div>

              <div class="info-block">
                <span class="info-label">Phone</span>
                <span class="info-value"><?= htmlspecialchars($phone ?: '-'); ?></span>
              </div>

              

              <div class="info-block">
                <span class="info-label">Created At</span>
                <div class="info-value">
                  <i class="fa fa-calendar text-primary"></i> <?= htmlspecialchars($created); ?>
                </div>
              </div>
<div class="info-block full-width-block">
                <span class="info-label">Subject</span>
                <span class="info-value"><?= htmlspecialchars($subject ?: '-'); ?></span>
              </div>
              <!-- Full width message block -->
              <div class="info-block full-width-block">
                <span class="info-label">Message</span>
                <span class="info-value"><?= nl2br(htmlspecialchars($message ?: '-')); ?></span>
              </div>
            </div>
          </div>
        </div>

      <?php else: ?>
        <div class="alert alert-warning text-center mt-3 alert-small">
          <i class="fa fa-exclamation-triangle me-2"></i>
          No contact enquiry found for the provided ID.
          <?php if (empty($id)): ?>
            <div class="small text-muted">(No ID provided in the URL or request.)</div>
          <?php else: ?>
            <div class="small text-muted">Tried to fetch ID: <?= htmlspecialchars($id); ?></div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>