<?php

$session = isset($session) ? $session : $this->session->userdata('company1');
$isSuperAdmin = isset($session['is_admin']) && $session['is_admin'] == true;
$group_id = isset($group_id) ? (int)$group_id : 0;
$user_groups = isset($user_groups) ? $user_groups : [];
$menus = isset($menus) ? $menus : [];
$user_rights = isset($user_rights) ? $user_rights : [];


$selectedGroup = null;
foreach ($user_groups as $g) {
    if ((int)$g->ug_id === $group_id) { $selectedGroup = $g; break; }
}


$hiddenAddEditIds = [16, 1, 9];


?>
<style>

input[type="checkbox"] {
    -webkit-appearance: checkbox !important;
    appearance: checkbox !important;
    display: inline-block !important;
    width: 18px !important;
    height: 18px !important;
    margin: 0 !important;
    vertical-align: middle !important;
    outline: none !important;
    position: relative !important;
}
</style>

<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">User Rights Edit</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('master/userrights') ?>"><i class="mdi mdi-home-outline"></i> User Rights</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Edit User Rights</h4>
                        </div>

                        <?php if (!$selectedGroup): ?>
                            <div class="p-3">
                                <div class="alert alert-warning">No user group selected or group not found.</div>
                                <a href="<?= base_url('master/userrights') ?>" class="btn btn-sm btn-secondary">Back</a>
                            </div>
                        <?php else: ?>

                        <?php
                        
                        $canEdit = false;
                        if ($isSuperAdmin) {
                            $canEdit = true;
                        } else {
                          
                            $this->load->model('Master_model', 'master', true);
                            $authForCurrent = $this->master->getUserAuthentication($session['user_group_id'] ?? null);
                            if (isset($authForCurrent[1050]) && isset($authForCurrent[1050]->ur_edit) && $authForCurrent[1050]->ur_edit == 1) {
                                $canEdit = true;
                            }
                        }
                        ?>

                        <form id="submitForm" name="addform" action="<?= base_url('master/usersrightsedit/' . $group_id) ?>" method="POST" novalidate>
                            <input type="hidden" name="group_id" value="<?= $group_id ?>" />

                            <div class="box-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">User Group</label>
                                        <select class="form-select" disabled>
                                            <?php foreach ($user_groups as $g): ?>
                                                <option value="<?= $g->ug_id ?>" <?= ($g->ug_id == $group_id) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($g->ug_name) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width:70px">S.No</th>
                                                    <th>Menu Name</th>
                                                    <th style="width:140px">Session</th>
                                                    <th style="width:110px" class="text-center">View</th>
                                                    <th style="width:110px" class="text-center">Add</th>
                                                    <th style="width:110px" class="text-center">Edit</th>
                                                    <!-- <th style="width:110px" class="text-center">Delete</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $sno = 1;
                                                foreach ($menus as $m):
                                                    $mid = (int)$m->mm_id;
                                                    $r = isset($user_rights[$mid]) ? $user_rights[$mid] : null;
                                                    $viewChecked = $r && isset($r->ur_view) && $r->ur_view == 1;
                                                    $addChecked  = $r && isset($r->ur_add)  && $r->ur_add == 1;
                                                    $editChecked = $r && isset($r->ur_edit) && $r->ur_edit == 1;
                                                    $deleteChecked = $r && (isset($r->ur_delete) ? $r->ur_delete == 1 : false);
                                                    $controlDisabled = $canEdit ? false : true;
                                                    
                                                    $viewId = 'items_'.$mid.'_view';
                                                    $addId  = 'items_'.$mid.'_add';
                                                    $editId = 'items_'.$mid.'_edit';
                                                    $delId  = 'items_'.$mid.'_delete';

                                                  
                                                    $hideAddEdit = in_array($mid, $hiddenAddEditIds, true);
                                                ?>
                                                    <tr>
                                                        <td class="text-center"><?= $sno ?></td>
                                                        <td>
                                                            <?= htmlspecialchars($m->mm_name) ?>
                                                            <input type="hidden" name="items[<?= $mid ?>][menuId]" value="<?= $mid ?>" />
                                                            <input type="hidden" name="items[<?= $mid ?>][menuName]" value="<?= htmlspecialchars($m->mm_name) ?>" />
                                                        </td>
                                                        <td class="text-center"><?= htmlspecialchars($m->mm_session_number ?? '') ?></td>

                                                        <td class="text-center">
                                                            <div>
                                                                <input type="checkbox"
                                                                    name="items[<?= $mid ?>][view]"
                                                                    value="1"
                                                                    id="<?= $viewId ?>"
                                                                    <?= $viewChecked ? 'checked' : '' ?>
                                                                    <?= $controlDisabled ? 'disabled' : '' ?>
                                                                />
                                                                <label for="<?= $viewId ?>" style="display:inline-block; margin-left:6px; cursor:<?= $controlDisabled ? 'not-allowed' : 'pointer' ?>;"></label>
                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <?php if ($hideAddEdit): ?>
                                                                <span>-</span>
                                                            <?php else: ?>
                                                                <div>
                                                                    <input type="checkbox"
                                                                        name="items[<?= $mid ?>][add]"
                                                                        value="1"
                                                                        id="<?= $addId ?>"
                                                                        <?= $addChecked ? 'checked' : '' ?>
                                                                        <?= $controlDisabled ? 'disabled' : '' ?>
                                                                    />
                                                                    <label for="<?= $addId ?>" style="display:inline-block; margin-left:6px; cursor:<?= $controlDisabled ? 'not-allowed' : 'pointer' ?>;"></label>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td class="text-center">
                                                            <?php if ($hideAddEdit): ?>
                                                                <span>-</span>
                                                            <?php else: ?>
                                                                <div>
                                                                    <input type="checkbox"
                                                                        name="items[<?= $mid ?>][edit]"
                                                                        value="1"
                                                                        id="<?= $editId ?>"
                                                                        <?= $editChecked ? 'checked' : '' ?>
                                                                        <?= $controlDisabled ? 'disabled' : '' ?>
                                                                    />
                                                                    <label for="<?= $editId ?>" style="display:inline-block; margin-left:6px; cursor:<?= $controlDisabled ? 'not-allowed' : 'pointer' ?>;"></label>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>

                                                        <!-- <td class="text-center">
                                                            <div>
                                                                <input type="checkbox"
                                                                    name="items[<?= $mid ?>][delete]"
                                                                    value="1"
                                                                    id="<?= $delId ?>"
                                                                    <?= $deleteChecked ? 'checked' : '' ?>
                                                                    <?= $controlDisabled ? 'disabled' : '' ?>
                                                                />
                                                                <label for="<?= $delId ?>" style="display:inline-block; margin-left:6px; cursor:<?= $controlDisabled ? 'not-allowed' : 'pointer' ?>;"></label>
                                                            </div>
                                                        </td> -->
                                                    </tr>
                                                <?php $sno++; endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer d-flex justify-content-between">
                                <a href="<?= base_url('master/userrights') ?>" class="btn btn-warning"><i class="ti-arrow-left"></i> Back</a>

                                <?php if ($canEdit): ?>
                                    <button type="submit" id="submit" class="btn btn-primary"><i class="ti-save-alt"></i> Save</button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-secondary" disabled>You don't have permission to modify</button>
                                <?php endif; ?>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
