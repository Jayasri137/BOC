<div class="col-sm-12" style="margin-top:25px;padding-left:30px;">	
	<div class="row">
		<div class="btn-group btn-breadcrumb">
			<a href="<?= base_url(); ?>master/home" class="btn btn-primary"><i class="glyphicon glyphicon-home"></i></a>
			<a href="#" class="btn btn-info">User</a>
		</div>
	</div>
</div><!--end of col-sm-12-->

<div class="col-sm-12" style="margin-top:25px;">	
	<div class="row">

		<?php
		// Helper closure to test permission safely
		$has_view = function($idx) {
			if (!isset($GLOBALS['CI'])) {
				// fallback: try to use $this if in view scope
				$ci = (isset($this) ? $this : null);
			} else {
				$ci = $GLOBALS['CI'];
			}
			// prefer $this->sessionArray when available
			if (isset($ci) && isset($ci->sessionArray) && isset($ci->sessionArray[$idx]) && is_object($ci->sessionArray[$idx])) {
				return (isset($ci->sessionArray[$idx]->ur_view) && $ci->sessionArray[$idx]->ur_view == 1);
			}
			// fallback: attempt to read $this->sessionArray directly (view scope)
			if (isset($this) && isset($this->sessionArray) && isset($this->sessionArray[$idx]) && is_object($this->sessionArray[$idx])) {
				return (isset($this->sessionArray[$idx]->ur_view) && $this->sessionArray[$idx]->ur_view == 1);
			}
			return false;
		};
		?>

		<?php if ($has_view(1044)) : ?>
			<a href="<?= base_url(); ?>master/usergroup">
				<div class="col-sm-2">
					<img src="<?= base_url(); ?>assets/css/img/icon-97.png" class="img-responsive" alt="User Group">
				</div>
			</a>
		<?php endif; ?>

		<?php if ($has_view(1045)) : ?>
			<a href="<?= base_url(); ?>master/usermaster">
				<div class="col-sm-2">
					<img src="<?= base_url(); ?>assets/css/img/icon-98.png" class="img-responsive" alt="User Master">
				</div>
			</a>
		<?php endif; ?>

		<?php if ($has_view(1046)) : ?>
			<a href="<?= base_url(); ?>master/userrights">
				<div class="col-sm-2">
					<img src="<?= base_url(); ?>assets/css/img/icon-99.png" class="img-responsive" alt="User Rights">
				</div>
			</a>
		<?php endif; ?>

		<?php if ($has_view(1047)) : ?>
			<a href="<?= base_url(); ?>master/menumaster">
				<div class="col-sm-2">
					<img src="<?= base_url(); ?>assets/css/img/icon-100.png" class="img-responsive" alt="Menu Master">
				</div>
			</a>
		<?php endif; ?>

	</div>
</div>

</div><!--end of row-->			
</div><!--end of col-sm-12-->
</div><!--end of col-sm-12-->
