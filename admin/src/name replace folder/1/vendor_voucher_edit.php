<?php include 'header.php'; ?>  

<style>
    .required::after {
        content: " *";
        color: red;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Create Customer Category</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="customer_category.php"> <i class="mdi mdi-home-outline"> Customer Category</i></a></li>
                            <!-- <li class="breadcrumb-item"></li> -->
                            <li class="breadcrumb-item active">Create Customer Category</li>
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
                            <h4 class="box-title">Customer Category Details</h4>
                        </div>
                        <form class="form needs-validation" novalidate>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label required">Customer Category Name</label>
                                            <input type="text" class="form-control" placeholder="Customer_Category Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer d-flex justify-content-between">
                                <button type="reset" class="btn btn-warning">
                                    <i class="ti-trash"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
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

<script>
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<?php include 'footer.php'; ?>
