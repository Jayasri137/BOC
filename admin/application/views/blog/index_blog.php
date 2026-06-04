<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $blog = $this->db->query("SELECT * FROM blogs ORDER BY created_at DESC"); ?>

<?php
// permission helper (view-level)
// get session
$ci = &get_instance();
$company1 = $ci->session->userdata('company1') ?? [];
$user_group_id = is_array($company1) && isset($company1['user_group_id']) ? (int)$company1['user_group_id'] : 0;
$is_admin_flag = isset($company1['is_admin']) && $company1['is_admin'] == true;

function has_permission_by_menu_name($menuNames, $action = 'view', $user_group_id = 0, $is_admin_flag = false, $ci_obj = null)
{
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

$menuNames = ['Blog'];
$canAdd = has_permission_by_menu_name($menuNames, 'add', $user_group_id, $is_admin_flag, $ci);
$canEdit = has_permission_by_menu_name($menuNames, 'edit', $user_group_id, $is_admin_flag, $ci);
?>

<div class="content-wrapper">
  <div class="container-full">
    <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h3 class="page-title">Blog</h3>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="<?= base_url() ?>Dashboard">
                    <i class="mdi mdi-home-outline"></i> Dashboard
                  </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Blog</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="col-12">
      <div class="box">
        <div class="box-header d-flex justify-content-between align-items-center">
          <h4 class="box-title"></h4>
          <div>
            <?php if ($canAdd): ?>
              <a href="<?= base_url() ?>blog/create_blog" class="btn btn-primary">Add Blog</a>
            <?php else: ?>
              <button class="btn btn-primary" disabled title="You don't have permission to add blogs">Add Blog</button>
            <?php endif; ?>
          </div>
        </div>

        <div class="box-body">
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Author</th>
                  <th>Image</th>
                  <th>Title</th>
                  <th>Created Date</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>

              <tbody>
                <?php
                $sno = 1;
                if ($blog && $blog->num_rows() > 0) :
                    foreach ($blog->result() as $row) :
                        $id = (int)$row->id;
                        $author = htmlspecialchars($row->author_name);
                        $title = htmlspecialchars($row->blog_title);
                        $created = htmlspecialchars($row->created_at);
                        $status = ((int)$row->status === 1)
                                  ? '<span class="badge bg-success">Active</span>'
                                  : '<span class="badge bg-danger">Inactive</span>';

                        // actions: show only if canEdit
                        $actions = '';
                        if ($canEdit) {
                            $actions  = '<a href="' . base_url('blog/edit_blog/' . $row->id) . '" data-bs-toggle="tooltip" title="Edit">
                                <button type="button" class="waves-effect waves-light btn btn-dark mb-5">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </a>';
                            $actions .= '<a href="' . base_url("blog/delete_blog/{$id}") . '" 
                                data-bs-toggle="tooltip" 
                                title="Delete" 
                                onclick="return confirm(\'Are you sure you want to delete this blog?\');">
                                <button type="button" class="waves-effect waves-light btn btn-danger mb-5">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </a>';
                        } else {
                            $actions = '<button class="btn btn-secondary btn-sm" disabled title="No permission"><i class="fa fa-edit"></i></button> <button class="btn btn-secondary btn-sm" disabled title="No permission"><i class="fa fa-trash"></i></button>';
                        }
                ?>
                <tr>
                  <td class="text-center"><?= $sno++; ?></td>
                  <td class="text-start"><?= $author; ?></td>
                  <td class="text-center">
                    <?php if (!empty($row->blog_image)): ?>
                        <img src="<?= base_url($row->blog_image) ?>" width="80" height="60" alt="<?= htmlspecialchars($row->blog_title) ?>">
                    <?php else: ?>
                        <img src="<?= base_url('assets/images/no-image.png') ?>" width="80" height="60" alt="No Image">
                    <?php endif; ?>
                  </td>
                  <td class="text-start"><?= $title; ?></td>
                  <td class="text-start"><?= $created; ?></td>
                  <td class="text-center"><?= $status; ?></td>
                  <td class="text-center"><?= $actions; ?></td>
                </tr>
                <?php
                    endforeach;
                else:
                    echo '<tr><td colspan="7" class="text-center">No blogs found.</td></tr>';
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


<!-- Toast notification -->
<div id="global-toast" class="global-toast hidden" role="status" aria-live="polite">
  <div class="gt-body">
    <div class="gt-icon" id="gt-icon"></div>
    <div class="gt-content">
      <div id="gt-title" class="gt-title"></div>
      <div id="gt-progress" class="gt-progress"><div id="gt-progress-bar" class="gt-progress-bar"></div></div>
    </div>
  </div>
</div>

<style>
/* Toast styles (minimal) */
.global-toast {
  position: fixed;
  top: 20px;
  right: 20px;
  min-width: 260px;
  z-index: 20000;
  border-radius: 10px;
  background: #fff;
  box-shadow: 0 8px 24px rgba(10,20,40,0.12);
  overflow: hidden;
  opacity: 0;
  transform: translateY(-8px);
  transition: all 240ms ease;
}
.global-toast.show { opacity: 1; transform: translateY(0); }
.gt-body { display:flex; gap:12px; padding:14px; align-items:center; }
.gt-icon { width:44px; height:44px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; }
.gt-content { flex:1; }
.gt-title { font-weight:600; color:#0f1724; margin-bottom:6px; }
.gt-progress { height:4px; background:rgba(0,0,0,0.06); border-radius:999px; overflow:hidden; }
.gt-progress-bar { height:100%; transform-origin:left; transform:scaleX(1); transition:transform linear; background:rgba(255,255,255,0.9); }
.gt-success { background: linear-gradient(90deg,#10b981,#06b6d4); }
.gt-error { background: linear-gradient(90deg,#ef4444,#f97316); }
.gt-warning { background: linear-gradient(90deg,#f59e0b,#f97316); }
.hidden { display:none; }
@media(max-width:420px){ .global-toast{ right:12px; left:12px; } }

/* Let the table expand and use the page scrollbar instead of an inner one */
.table-responsive {
  overflow: visible !important;
  max-height: none !important;
  height: auto !important;
}

/* Prevent parent containers from clipping the expanded table */
.box-body, .box, .col-12, .container-full, .content-wrapper {
  overflow: visible !important;
  max-height: none !important;
  height: auto !important;
}

/* Avoid forcing a fixed table width that can cause horizontal inner scrollbars */
#example1 {
  table-layout: auto !important;
  width: 100% !important;
}

/* Keep small-screen usability: only allow inner horizontal scroll on tiny viewports */
@media (max-width: 640px) {
  .table-responsive { overflow-x: auto !important; }
  #example1 { table-layout: auto; }
}
</style>

<script>
(function(){
  // Toast: show flashdata messages
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
      success: '<img src="../assets/images/check-mark.png" width="20" height="20" alt="Success" />',
      error: '<img src="../assets/images/check-mark.png" width="20" height="20" alt="Error" />',
      warning: '<img src="../assets/images/check-mark.png" width="20" height="20" alt="Warning" />'
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

<!-- Initialize client-side DataTable -->
<script>
$(document).ready(function() {
    $('#example1').DataTable({
        pageLength: 10,
        order: [[4, 'desc']], // Created Date column index is 4
        columnDefs: [
            { targets: 0, className: "text-center" },       // S.No
            { targets: 1, className: "text-start" },        // Author
            { targets: 2, className: "text-center" },       // Image
            { targets: 3, className: "text-start" },        // Title
            { targets: 4, className: "text-start" },        // Created Date
            { targets: 5, className: "text-center" },       // Status
            { targets: 6, className: "text-center" }        // Actions
        ],
        language: {
            searchPlaceholder: "Search..."
        }
    });
});
</script>
