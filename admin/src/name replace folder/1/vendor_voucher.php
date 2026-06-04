<?php include 'header.php'; ?>  
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Company Details</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"> Home</i></a></li>
                                <!-- <li class="breadcrumb-item" aria-current="page">Home</li> -->
                                <li class="breadcrumb-item active" aria-current="page">Data Tables</li>
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
        <a href="company_create.php" class="btn btn-primary">Add Company</a>  <!-- Anchor tag styled as a button -->
        <!-- <button id="customButton" class="">Create Company</button> -->
    </div>
</div>

			<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
					  <table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Name</th>
								<th>Position</th>
								<th>Office</th>
								<th>Age</th>
								<th>Start date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Tiger Nixon</td>
								<td>System Architect</td>
								<td>Edinburgh</td>
								<td>61</td>
								<td>2011/04/25</td>
								<td>
								<a href="company_edit.php" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
										<button type="button" class="waves-effect waves-light btn btn-dark mb-5">
											<i class="fa fa-edit"></i>
										</button>
									</a>

									<a href="view.php" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
										<button type="button" class="waves-effect waves-light btn btn-warning mb-5">
											<i class="fa fa-eye"></i>
										</button>
									</a>

									<a href="delete.php" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
										<button type="button" class="waves-effect waves-light btn btn-danger mb-5">
											<i class="fa fa-trash"></i>
										</button>
									</a>

								</td>
							</tr>
								
						</tbody>
						<tfoot>
							<!-- <tr>
								<th>Name</th>
								<th>Position</th>
								<th>Office</th>
								<th>Age</th>
								<th>Start date</th>
								<th>Salary</th>
							</tr> -->
						</tfoot>
					  </table>
					</div>
				</div>
				<!-- /.box-body -->
			  </div>
			  <!-- /.box -->
        </div>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->

<?php include 'footer.php'; ?>





