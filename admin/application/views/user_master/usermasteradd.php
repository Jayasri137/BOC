<?php $customerId = $session['customer_id'];?>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Create User</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?=base_url();?>master/usermaster"> <i class="mdi mdi-home-outline">
                                        User</i></a></li>
                            <!-- <li class="breadcrumb-item"></li> -->
                            <li class="breadcrumb-item active">Create User</li>
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
                            <h4 class="box-title">User Details</h4>
                        </div>
                        <form class="form needs-validation" id="submitForm" name="addform" action="<?php echo base_url();?>master/usermasteradd" method="POST" novalidate>
                            <div class="box-body">
                                <div class="row">

                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label required">User Group</label>
											<select required="" name="usergroup"  id="usergroup" class="form-select">
												<option value="">Select</option>
												<?php 
												$cat=$this->db->query("select * from user_group where ug_customer_id='$customerId' and  ug_status=1");    
												foreach($cat->result() as $row){  
																		?>
												<option value="<?php echo $row->ug_id;?>"><?php echo $row->ug_name;?></option>
												<?php } ?>
											</select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label required">User Name</label>
                                            <input type="text" class="form-control" name="username"  onblur="CheckUserName(this.value,'<?php echo "0";?>')" id="cat" placeholder="User Name" required>
                                        </div>
                                    </div>
                                     <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label required">Password</label>
                                            <div class="input-group">
                                                <input type="password" name="password1" id="password1" onblur="CheckPassword(this)" class="form-control" placeholder="Password" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="togglePasswordWithTimeout('password1', this)" style="cursor:pointer">
                                                        <i class="fa fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label required">Confirm Password</label>
                                            <div class="input-group">
                                                <input type="password" name="password2" id="password2" onblur="CheckPassword(this)" class="form-control" placeholder="Confirm Password" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="togglePasswordWithTimeout('password2', this)">
                                                        <i class="fa fa-eye" style="cursor:pointer"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label ">Status</label>
                                            <select class="form-select" name="status" id="status">
												<option value="0">Active</option>
												<option value="1">In-Active</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="box-footer d-flex justify-content-between">
                                <button type="reset" class="btn btn-warning">
                                    <i class="ti-trash"></i> Cancel
                                </button>
                                <button type="submit" id="btnSubmit"  value="Submit" class="btn btn-primary">
                                    <i class="ti-save-alt"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

 <!--password show-->
<script>
function togglePasswordWithTimeout(inputId, element) {
    const input = document.getElementById(inputId);
    const icon = element.querySelector('i');
    
    // Clear any existing timeout for this input
    if (input.timeoutId) {
        clearTimeout(input.timeoutId);
    }
    
    if (input.type === "password") {
        // Show password
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        
        // Set timeout to hide after 5 seconds
        input.timeoutId = setTimeout(() => {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }, 2000);
    } else {
        // Hide password immediately
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
<script>
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<script>
function CheckPassword(element)
{
	var password1=$('#password1').val();
	var password2=$('#password2').val();
	if(password1!=password2)
		$('#password2').val('');
}
$(document).on('click','#submit',function (){
			$('#submitForm').valid();
		});
</script>

	