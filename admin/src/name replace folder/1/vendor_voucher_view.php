<?php include 'header.php'; ?>  

<style>
    .content-wrapper {
        padding: 20px;
    }
    .form-group {
        display: flex;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding: 8px 0;
        font-size: 16px;
    }
    .form-label {
        font-weight: bold;
        min-width: 200px; /* Ensures labels are aligned */
        flex: 1;
        text-align: right; /* Aligns text to the right */
        padding-right: 10px; /* Adds spacing between label and colon */
        position: relative;
    }
    .form-label::after {
        content: ":"; /* Adds a colon dynamically */
        position: absolute;
        right: 0;
    }
    .form-value {
        flex: 2;
        text-align: left;
        padding-left: 20px; /* Space between colon and value */
    }
    .box {
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Customer Category Details</h3>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="customer_category.php"> <i class="mdi mdi-home-outline"></i> Customer Category</a></li>
                            <li class="breadcrumb-item active">Customer_Category Details</li>
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
                            <h4 class="box-title">Customer Category Information</h4>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-12">
                                    <div class="form-group"><span class="form-label">Customer Category Name</span> <span class="form-value">XYZ Pvt Ltd</span></div>
                               </div>

                                <!-- Right Column -->
                                <!-- <div class="col-md-6">
                               </div> -->
                            </div>
                        </div>
                        <div class="box-footer d-flex justify-content-end">
                            <a href="customer_category_edit.php" class="btn btn-primary">
                                <i class="ti-pencil"></i> Edit
                            </a>
                        </div>   
                    </div>        
                </div>  
            </div>
        </section>
    </div>
</div>

<?php include 'footer.php'; ?>
