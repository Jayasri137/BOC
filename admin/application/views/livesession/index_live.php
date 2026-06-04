<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php

if (!isset($liveclasses)) {
    $liveclasses = $this->db->order_by('created_at', 'DESC')->get('liveclasses');
}


$ci = &get_instance();
$company1 = $ci->session->userdata('company1') ?? [];
$user_group_id = is_array($company1) && isset($company1['user_group_id']) ? (int)$company1['user_group_id'] : 0;
$is_admin_flag = isset($company1['is_admin']) && $company1['is_admin'] == true;

function has_permission_by_menu_name($menuNames, $action = 'view', $user_group_id = 0, $is_admin_flag = false, $ci_obj = null) {
    if ($is_admin_flag) return true;
    if (!$ci_obj) $ci_obj = &get_instance();
    if (!is_array($menuNames)) $menuNames = [$menuNames];
    if ($user_group_id <= 0) return false;

    $col = 'ur_view';
    if (strtolower($action) === 'add')  $col = 'ur_add';
    if (strtolower($action) === 'edit') $col = 'ur_edit';

    $ci_obj->db->select("ur.{$col} as perm");
    $ci_obj->db->from('user_rights ur');
    $ci_obj->db->join('menu_master mm', 'ur.ur_menu_master_id = mm.mm_id', 'inner');
    $ci_obj->db->where('ur.ur_user_group_id', $user_group_id);
    $ci_obj->db->where('ur.ur_status', 1);

    $ci_obj->db->group_start();
    $first = true;
    foreach ($menuNames as $m) {
        
        if ($first) { $ci_obj->db->like('mm.mm_name', $m); $first = false; }
        else { $ci_obj->db->or_like('mm.mm_name', $m); }
    }
    $ci_obj->db->group_end();
    $ci_obj->db->limit(1);

    $row = $ci_obj->db->get()->row();
    if (!$row) return false;
    return ((int)$row->perm) === 1;
}


$liveMenuNames = ['Live Session', 'Live Classes'];


$canAddLive  = has_permission_by_menu_name($liveMenuNames, 'add',  $user_group_id, $is_admin_flag, $ci);
$canEditLive = has_permission_by_menu_name($liveMenuNames, 'edit', $user_group_id, $is_admin_flag, $ci);
?>

<div class="content-wrapper">
  <div class="container-full">

    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">Live Session</h3>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url('Dashboard'); ?>">
                  <i class="mdi mdi-home-outline"></i> Dashboard
                </a>
              </li>
              <li class="breadcrumb-item active">Live Session</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>


    <div class="col-12">
      <div class="box">
        <div class="box-header d-flex justify-content-between align-items-center">
          <h4 class="box-title">Live Session List</h4>
          <div>
            <?php if ($canAddLive): ?>
              <a href="<?= site_url('livesession/create'); ?>" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Live
              </a>
            <?php else: ?>

              <button class="btn btn-primary" disabled title="You don't have permission to add live sessions">
                <i class="fa fa-plus"></i> Add Live
              </button>
            <?php endif; ?>
          </div>
        </div>

        <div class="box-body">
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Title</th>
                  <th>URL / Link</th>
                  <th>Status</th>
                  <th>Created Date</th>
                  <th>Actions</th>
                </tr>
              </thead>

              <tbody>
                <?php
                $sno = 1;


                $rows = [];
                if ($liveclasses) {
                    if (is_object($liveclasses) && method_exists($liveclasses, 'result')) {
                        $rows = $liveclasses->result();
                    } elseif (is_array($liveclasses)) {
                        $rows = $liveclasses;
                    }
                }

                if (!empty($rows)):
                    foreach ($rows as $row):
                        $id      = (int)($row->id ?? 0);
                        $title   = htmlspecialchars($row->title ?? '-', ENT_QUOTES, 'UTF-8');
                        $urlRaw  = trim((string)($row->url ?? ''));
                        $status  = ((int)($row->status ?? 0) === 1)
                                   ? '<span class="badge bg-success">Active</span>'
                                   : '<span class="badge bg-danger">Inactive</span>';
                        $created = !empty($row->created_at) ? date('d-M-Y', strtotime($row->created_at)) : '-';

                     
                        if ($urlRaw !== '') {
                            $href = preg_match('#^https?://#i', $urlRaw) ? $urlRaw : '//' . ltrim($urlRaw, '/');
                            $linkHtml = '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars($urlRaw, ENT_QUOTES, 'UTF-8') . '</a>';
                        } else {
                            $linkHtml = '<span>-</span>';
                        }
                ?>
                <tr>
                  <td class="text-center"><?= $sno++; ?></td>
                  <td class="text-start"><?= $title; ?></td>
                  <td class="text-start"><?= $linkHtml; ?></td>
                  <td class="text-center"><?= $status; ?></td>
                  <td class="text-center"><?= $created; ?></td>
                  <td class="text-center">
                    <?php if ($canEditLive): ?>
                      <a href="<?= site_url('livesession/edit_live/'.$id); ?>" class="btn btn-dark btn-sm" title="Edit">
                        <i class="fa fa-edit"></i>
                      </a>
                      <?php else: ?>
                      <button class="btn btn-secondary btn-sm" disabled title="No permission"><i class="fa fa-edit"></i></button>
                    <?php endif; ?>
                    <?php if ($canEditLive): ?>
                      <a href="<?= site_url('livesession/delete_live/'.$id); ?>"
                         class="btn btn-danger btn-sm"
                         onclick="return confirm('Are you sure you want to delete this live session?');"
                         title="Delete">
                        <i class="fa fa-trash"></i>
                      </a>
                    <?php else: ?>
                      <button class="btn btn-secondary btn-sm" disabled title="No permission"><i class="fa fa-trash"></i></button>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php
                    endforeach;
                else:
                  echo '<tr><td colspan="6" class="text-center">No live sessions found.</td></tr>';
                endif;
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div id="global-toast" class="global-toast hidden" role="status" aria-live="polite">
  <div class="gt-body">
    <div class="gt-icon" id="gt-icon"></div>
    <div class="gt-content">
      <div id="gt-title" class="gt-title"></div>
      <div id="gt-progress" class="gt-progress">
        <div id="gt-progress-bar" class="gt-progress-bar"></div>
      </div>
    </div>
  </div>
</div>

<style>
.global-toast { position: fixed; top:20px; right:20px; min-width:260px; z-index:20000; border-radius:10px; background:#fff; box-shadow:0 8px 24px rgba(10,20,40,0.12); overflow:hidden; opacity:0; transform:translateY(-8px); transition:all 240ms ease; }
.global-toast.show { opacity:1; transform:translateY(0); }
.gt-body { display:flex; gap:12px; padding:14px; align-items:center; }
.gt-icon{ width:44px; height:44px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; }
.gt-content{ flex:1; }
.gt-title{ font-weight:600; color:#0f1724; margin-bottom:6px; }
.gt-progress{ height:4px; background:rgba(0,0,0,0.06); border-radius:999px; overflow:hidden; }
.gt-progress-bar{ height:100%; transform-origin:left; transform:scaleX(1); transition:transform linear; background:rgba(255,255,255,0.9); }
.gt-success{ background: linear-gradient(90deg,#10b981,#06b6d4); } .gt-error{ background: linear-gradient(90deg,#ef4444,#f97316); } .gt-warning{ background: linear-gradient(90deg,#f59e0b,#f97316); }
.hidden{ display:none; }
.table-responsive { overflow: visible !important; }
.box-body, .box, .col-12, .container-full, .content-wrapper { overflow: visible !important; }
#example1 { table-layout: auto !important; width: 100% !important; }
@media (max-width:640px){ .table-responsive { overflow-x:auto !important; } }
</style>

<script>
(function(){
  var holder = document.getElementById('flash-data');
  if (!holder) return;
  var success = holder.getAttribute('data-success') || '';
  var error = holder.getAttribute('data-error') || '';
  var warning = holder.getAttribute('data-warning') || '';
  var message = '', type = '';
  if (success) { message = success; type = 'success'; }
  else if (error) { message = error; type = 'error'; }
  else if (warning) { message = warning; type = 'warning'; }
  if (!message) return;

  var toast = document.getElementById('global-toast');
  var icon = document.getElementById('gt-icon');
  var title = document.getElementById('gt-title');
  var progress = document.getElementById('gt-progress-bar');

  var icons = {
    success: '<svg width="20" height="20" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    error: '<svg width="20" height="20" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    warning: '<svg width="20" height="20" viewBox="0 0 24 24"><path d="M12 8v4M12 16h.01" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
  };

  var classes = { success: 'gt-success', error: 'gt-error', warning: 'gt-warning' };

  icon.className = 'gt-icon ' + (classes[type] || '');
  icon.innerHTML = icons[type] || '';
  title.textContent = message;

  toast.classList.remove('hidden');
  void toast.offsetWidth;
  toast.classList.add('show');

  var duration = 3200;
  var start = Date.now();
  function tick() {
    var elapsed = Date.now() - start;
    var pct = Math.max(0, 1 - elapsed / duration);
    progress.style.transform = 'scaleX(' + pct + ')';
    if (elapsed < duration) requestAnimationFrame(tick);
    else { toast.classList.remove('show'); setTimeout(function(){ toast.classList.add('hidden'); }, 260); }
  }
  progress.style.transformOrigin = 'left center';
  tick();

  toast.addEventListener('click', function(){ toast.classList.remove('show'); setTimeout(function(){ toast.classList.add('hidden'); }, 200); });
})();
</script>

<!-- Initialize DataTable -->
<script>
$(document).ready(function() {
  $('#example1').DataTable({
    pageLength: 10,
    order: [[4, 'desc']], // sort by Created Date (zero-indexed column 4)
    columnDefs: [
      { targets: 0, className: "text-center" },
      { targets: 1, className: "text-start" },
      { targets: 2, className: "text-start" },
      { targets: 3, className: "text-center" },
      { targets: 4, className: "text-center" },
      { targets: 5, className: "text-center" }
    ],
    language: { searchPlaceholder: "Search..." }
  });
});
</script>
