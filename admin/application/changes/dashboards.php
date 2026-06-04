<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <!-- FullCalendar CSS -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

  <?php
  
  $blog    = $this->db->query("SELECT * FROM blogs ORDER BY created_at DESC");
  $enquiry = $this->db->query("SELECT * FROM enquiry ORDER BY created_at DESC");


  $contact = $this->db->query("SELECT * FROM contact_enquiry ORDER BY created_at DESC");


  $total_enquiry = $enquiry ? $enquiry->num_rows() : 0;
  $today_enquiry = $this->db->query("SELECT * FROM enquiry WHERE DATE(created_at)=CURDATE()")->num_rows();
  $total_blogs   = $blog ? $blog->num_rows() : 0;
  $today_blogs   = $this->db->query("SELECT * FROM blogs WHERE DATE(created_at)=CURDATE()")->num_rows();

  $total_contact = $contact ? $contact->num_rows() : 0;
  $today_contact = $this->db->query("SELECT * FROM contact_enquiry WHERE DATE(created_at)=CURDATE()")->num_rows();

$web_enquiry = $this->db->query("SELECT * FROM web_enquiry ORDER BY created_at DESC");

$total_web_enquiry = $web_enquiry ? $web_enquiry->num_rows() : 0;
$today_web_enquiry = $this->db->query("SELECT * FROM web_enquiry WHERE DATE(created_at) = CURDATE()")->num_rows();


$total_liveclasses_q = $this->db->query("SELECT COUNT(*) AS c FROM liveclasses");
$total_liveclasses = ($total_liveclasses_q && $total_liveclasses_q->num_rows() > 0)
    ? (int) $total_liveclasses_q->row()->c
    : 0;


$today_liveclasses_q = $this->db->query("
    SELECT COUNT(*) AS c 
    FROM liveclasses 
    WHERE DATE(created_at) = CURDATE()
");
$today_liveclasses = ($today_liveclasses_q && $today_liveclasses_q->num_rows() > 0)
    ? (int) $today_liveclasses_q->row()->c
    : 0;


$active_liveclasses_q = $this->db->query("
    SELECT COUNT(*) AS c 
    FROM liveclasses 
    WHERE status = 1
");
$active_liveclasses = ($active_liveclasses_q && $active_liveclasses_q->num_rows() > 0)
    ? (int) $active_liveclasses_q->row()->c
    : 0;



  $lead_counts = [
      'Open' => (int) @$this->db->query("SELECT COUNT(*) AS c FROM lead WHERE status='Open'")->row()->c,
      'Registered' => (int) @$this->db->query("SELECT COUNT(*) AS c FROM lead WHERE status='Registered'")->row()->c,
      'Hot' => (int) @$this->db->query("SELECT COUNT(*) AS c FROM lead WHERE status='Hot'")->row()->c,
      'Warm' => (int) @$this->db->query("SELECT COUNT(*) AS c FROM lead WHERE status='Warm'")->row()->c,
      'Cold' => (int) @$this->db->query("SELECT COUNT(*) AS c FROM lead WHERE status='Cold'")->row()->c,
      'Not Interested' => (int) @$this->db->query("SELECT COUNT(*) AS c FROM lead WHERE status='Not Interested'")->row()->c,
  ];
  $total_leads = array_sum($lead_counts);

  // CSRF tokens if available (CodeIgniter)
  $csrf_name = '';
  $csrf_hash = '';
  if (isset($this->security) && method_exists($this->security, 'get_csrf_token_name')) {
    $csrf_name = $this->security->get_csrf_token_name();
    $csrf_hash = $this->security->get_csrf_hash();
  }
  ?>

  <style>
  /* Minimal inline styling to force the same layout look */
  .container-dashboard { padding: 18px; }
  .stat-card { border: 1px solid #e6e6e6; border-radius:6px; padding:18px; background:#fff; }
  .stat-title { color:#0d6efd; font-weight:600; font-size:14px; }
  .stat-value { font-size:22px; font-weight:700; margin-top:6px; }
  .small-stat { border:1px solid #e9ecef; border-radius:6px; padding:16px; background:#fff; text-align:center; }
  .lead-card { border:1px solid #e9ecef; border-radius:6px; padding: 28px; background:#fff; }
  .legend-row { display:flex; gap:12px; flex-wrap:wrap; margin-top:14px; }
  .legend-item { display:inline-flex; align-items:center; gap:8px; padding:6px 10px; border-radius:14px; background:#fff; border:1px solid #eee; font-size:13px; }
  .canvas-center-label { position:relative; display:flex; align-items:center; justify-content:center; height:320px; }
  .center-text { position:absolute; font-weight:700; font-size:16px; color:#007bff; }
  #calendar { background:#fff; border:1px solid #e9ecef; border-radius:6px; padding:12px; }
  @media (min-width:1200px){
    .top-grid .col-lg-6 { width:50%; display:inline-block; vertical-align:top; }
  }
  </style>

  <div class="content-wrapper">
    <div class="container-full container-dashboard">

      <!-- TOP GRID: 8 small cards (two columns, 4 rows) -->
      <div class="row top-grid">
        <?php
        // prepare an array to loop and render exactly 8 boxes
        $topBoxes = [
          ['title'=>'Total Enquiry','value'=>$total_enquiry,'link'=>base_url('enquiry/index')],
          ['title'=>'Today Enquiry','value'=>$today_enquiry,'link'=>base_url('enquiry/index')],
          ['title'=>'Total Web Enquiry','value'=>$total_web_enquiry,'link'=>base_url('webenquiry/index/index')],
          ['title'=>'Today Web Enquiry','value'=>$today_web_enquiry,'link'=>base_url('webenquiry/index/index')],
          ['title'=>'Total Contacts','value'=>$total_contact ,'link'=>'contact/index'],
          ['title'=>'Today Contacts','value'=>$today_contact,'link'=>'contact/index'],
          ['title'=>'Total Blogs','value'=>$total_blogs,'link'=>base_url('blog/index')],
          ['title'=>'Today Blogs','value'=>$today_blogs,'link'=>base_url('blog/index')],
        ];
        foreach ($topBoxes as $box):
        ?>
          <div class="col-lg-3 mb-3">
            <div class="stat-card">
              <div class="d-flex justify-content-between">
                <div>
                  <div class="stat-title"><?= htmlspecialchars($box['title']); ?></div>
                  <div class="stat-value"><?= htmlspecialchars($box['value']); ?></div>
                  <small class="text-muted">on <?= date('d F,Y'); ?></small><br>
                  <a href="<?= $box['link']; ?>" style="font-size:12px;">View History</a>
                </div>
                <div style="align-self:flex-start">
                  <!-- optional icon -->
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="#e9ecef" stroke-width="2"/></svg>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div> <!-- /.top-grid -->

      <!-- Live Classes Stats -->
<div class="row mt-3">
  <div class="col-lg-4 col-md-4 mb-3">
    <div class="small-stat">
      <div style="font-size:20px; font-weight:700;">
        <?= $today_liveclasses; ?>
      </div>
      <div style="color:#6c757d; margin-top:6px;">Today's Live Sessions</div>
    </div>
  </div>

  <div class="col-lg-4 col-md-4 mb-3">
    <div class="small-stat">
      <div style="font-size:20px; font-weight:700;">
        <?= $total_liveclasses; ?>
      </div>
      <div style="color:#6c757d; margin-top:6px;">Total Live Classes</div>
    </div>
  </div>

  <div class="col-lg-4 col-md-4 mb-3">
    <div class="small-stat">
      <div style="font-size:20px; font-weight:700; color:#28a745;">
        <?= $active_liveclasses; ?>
      </div>
      <div style="color:#6c757d; margin-top:6px;">Active Live Sessions</div>
    </div>
  </div>
</div>


      <!-- Lead status donut: wide card -->
      <div class="row mt-3">
        <div class="col-12">
          <div class="lead-card">
            <div style="text-align:center; margin-bottom:18px;">
              <h5 style="color:#0d6efd; margin:0;">Lead Status Distribution</h5>
              <small class="text-muted">Total Leads: <?= $total_leads; ?></small>
            </div>

            <div style="display:flex; gap:20px; align-items:center; justify-content:space-between; flex-wrap:wrap;">
              <!-- donut canvas centered -->
              <div style="flex:1; min-width:360px; display:flex; align-items:center; justify-content:center;">
                <div style="position:relative; width:360px; height:360px;">
                  <canvas id="donutChart" width="360" height="360"></canvas>
                  <div class="center-text" id="donutCenterLabel"><?= array_keys($lead_counts)[0] ?? 'Open'; ?></div>
                </div>
              </div>

              <!-- legend (right side) -->
              <div style="width:320px; max-width:38%; min-width:220px;">
                <div style="font-weight:700; margin-bottom:8px;">Legend</div>
                <div style="display:flex; flex-direction:column; gap:8px;">
                  <?php
                  $colors = ['#007bff','#28a745','#dc3545','#ffc107','#6c757d','#343a40'];
                  $i = 0;
                  foreach ($lead_counts as $label => $count):
                    $clr = $colors[$i % count($colors)];
                  ?>
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:8px; border-radius:6px; border:1px solid #f1f1f1; background:#fff;">
                      <div style="display:flex; align-items:center; gap:10px;">
                        <span style="width:14px;height:14px;background:<?= $clr; ?>;display:inline-block;border-radius:3px;"></span>
                        <span><?= htmlspecialchars($label); ?></span>
                      </div>
                      <div style="font-weight:700; color:#333;"><?= $count; ?></div>
                    </div>
                  <?php $i++; endforeach; ?>
                </div>

                <!-- small legend row under donut (like screenshot) -->
                <div style="margin-top:14px;">
                  <div class="legend-row">
                    <?php $i = 0; foreach ($lead_counts as $label => $count): ?>
                      <div class="legend-item"><span style="width:10px;height:10px;background:<?= $colors[$i % count($colors)]; ?>;display:inline-block;border-radius:2px"></span><?= ' ' . htmlspecialchars($label) . ': ' . $count; ?></div>
                    <?php $i++; endforeach; ?>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

  <div class="row mt-3">
  <div class="col-12">
    <div style="background:#fff; border:1px solid #e9ecef; border-radius:6px; padding:16px;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
        <h5 style="margin:0;">Calendar Notes</h5>
        <div>
          <button class="btn btn-sm btn-light" id="calToday">Today</button>
          <button class="btn btn-sm btn-light" id="calPrev">Back</button>
          <button class="btn btn-sm btn-light" id="calNext">Next</button>
        </div>
      </div>

      <div id="calendar" style="min-height:400px;"></div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="eventModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="eventForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="evt_id">
        <div class="mb-3">
          <label>Event Title</label>
          <input type="text" id="evt_title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Date</label>
          <input type="date" id="evt_date" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Description</label>
          <textarea id="evt_desc" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="deleteEventBtn" class="btn btn-danger me-auto" style="display:none;">Delete</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const calendarEl = document.getElementById('calendar');
  const modalEl = document.getElementById('eventModal');
  const modal = new bootstrap.Modal(modalEl);
  const eventForm = document.getElementById('eventForm');
  const deleteBtn = document.getElementById('deleteEventBtn');

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    selectable: true,
    events: '<?= base_url("calendar/get_events"); ?>',
    eventDisplay: 'block',

    select: function(info) {
      resetForm();
      document.querySelector('.modal-title').textContent = 'Add Event';
      document.getElementById('evt_date').value = info.startStr;
      deleteBtn.style.display = 'none';
      modal.show();
    },

    eventClick: function(info) {
      const ev = info.event;
      resetForm();
      document.querySelector('.modal-title').textContent = 'Edit Event';
      document.getElementById('evt_id').value = ev.id;
      document.getElementById('evt_title').value = ev.title;
      document.getElementById('evt_date').value = ev.startStr.split('T')[0];
      document.getElementById('evt_desc').value = ev.extendedProps.description || '';
      deleteBtn.style.display = 'inline-block';
      modal.show();
    }
  });

  calendar.render();

  // Navigation buttons
  document.getElementById('calToday').onclick = () => calendar.today();
  document.getElementById('calPrev').onclick = () => calendar.prev();
  document.getElementById('calNext').onclick = () => calendar.next();

  // Reset form
  function resetForm() {
    eventForm.reset();
    document.getElementById('evt_id').value = '';
  }

  // Save / Update Event
  eventForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('evt_id').value;
    const title = document.getElementById('evt_title').value.trim();
    const date = document.getElementById('evt_date').value;
    const description = document.getElementById('evt_desc').value;

    if (!title || !date) return alert('Title and date are required.');

    const url = id 
      ? '<?= base_url("calendar/edit_event"); ?>' 
      : '<?= base_url("calendar/add_event"); ?>';

    fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id, title, date, description })
    })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'success') {
        modal.hide();
        calendar.refetchEvents();
      } else {
        alert(res.message || 'Failed to save event');
      }
    });
  });

  // Delete event
  deleteBtn.addEventListener('click', function() {
    const id = document.getElementById('evt_id').value;
    if (!id) return;
    if (!confirm('Are you sure you want to delete this event?')) return;

    fetch('<?= base_url("calendar/delete_event"); ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id })
    })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'success') {
        modal.hide();
        calendar.refetchEvents();
      } else {
        alert(res.message || 'Failed to delete event');
      }
    });
  });
});
</script>

  <!-- JS: initialize chart -->
  <script>
  document.addEventListener('DOMContentLoaded', function(){
    // Chart.js donut
    const ctx = document.getElementById('donutChart').getContext('2d');
    const labels = <?= json_encode(array_keys($lead_counts)); ?>;
    const dataVals = <?= json_encode(array_values($lead_counts)); ?>;
    const bgColors = ['#007bff','#28a745','#dc3545','#ffc107','#6c757d','#343a40'];

    const donut = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: labels,
        datasets: [{
          data: dataVals,
          backgroundColor: bgColors,
          borderWidth: 2,
          cutout: '70%'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(ctx) {
                const v = ctx.raw || 0;
                const total = ctx.chart._metasets ? ctx.chart._metasets[ctx.datasetIndex].total : ctx.chart._metasets?.[0]?.total;
                return ctx.label + ': ' + v + (total ? ' (' + ((v/total*100)||0).toFixed(1) + '%)' : '');
              }
            }
          }
        },
        onHover: (evt, item) => {
          if (item && item.length) {
            const idx = item[0].index;
            const label = labels[idx];
            document.getElementById('donutCenterLabel').innerText = label;
          } else {
            document.getElementById('donutCenterLabel').innerText = labels[0] || '';
          }
        }
      }
    });
  });
  </script>