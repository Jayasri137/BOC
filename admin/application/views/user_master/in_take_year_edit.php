<div class="content-wrapper">
    <div class="container-full">
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Edit Intake Year</h4>
                        </div>
                        <div class="box-body">
                            <form method="post" action="<?= base_url('master/edit_intake_year/' . $intake_year->id); ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="intake_year" class="control-label">Intake Year <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="intake_year" name="intake_year" 
                                                   value="<?= htmlspecialchars($intake_year->intake_year ?? ''); ?>" 
                                                   placeholder="e.g., 2024, 2025" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="control-label">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Active" <?= ($intake_year->status == 'Active') ? 'selected' : ''; ?>>Active</option>
                                                <option value="Pending" <?= ($intake_year->status == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Inactive" <?= ($intake_year->status == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="box-footer d-flex justify-content-between mt-3">
                                    <a href="<?= base_url('master/intake_year'); ?>" class="btn btn-danger">
                                        <i class="ti-trash"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti-save-alt"></i> Update Intake Year
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>