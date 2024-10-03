<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");
?>

<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>BENSO GARMENTING - Company</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
		rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

	<!-- sweetalert -->
	<link rel="stylesheet" type="text/css" href="src/plugins/sweetalert2/sweetalert2.css">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
</head>

<body>

	<?php
	include('includes/header.php');
	include('includes/sidebar.php');
	?>

	<div class="main-container nw-cont">
		<?php
		if ($_SESSION['msg'] == 'updated') { ?>
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>Success!</strong> Company Details Updated.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
		<?php } else if ($_SESSION['msg'] == 'added') { ?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong>Success!</strong> Company Details Saved.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
		<?php }
		$_SESSION['msg'] = '';
		?>
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<div class="card-box mb-30">
				    
				    <?php if(MAS_CATEGORY!=1) { action_denied(); exit; } ?>
				    
					<div class="pd-20">
					    <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
					        <a class="btn btn-outline-info" href="mod_masters.php"><i class="fa fa-home" aria-hidden="true"></i> Masters</a>
					        <?php if(MAS_COMPANY_ADD ==1) { ?>
    						    <a class="btn btn-outline-primary" href="add-company.php" ><i class="fa fa-plus" aria-hidden="true"></i> New Company</a>&nbsp;
    						<?php } ?>
						</div>
						    
						<h4 class="text-blue h4">Manage Company
							<p class="mb-30 text-danger">
								<i class="icon-copy fa fa-info-circle" aria-hidden="true" title="Info"
									style="font-size: 15px;"></i> Click on the Status To change
							</p>
						</h4>
					</div>
					<div class="pb-20">
						<table class="table hover multiple-select-row data-table-export nowrap">
							<thead>
								<tr>
									<th class="table-plus datatable-nosort">S.No</th>
									<th>Type</th>
									<th>Company Name</th>
									<th>Company Code</th>
									<th>Mobile No</th>
									<th>GST No</th>
									<th>PAN No</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$qry = "SELECT * FROM company ORDER BY id DESC";
								$query = mysqli_query($mysqli, $qry);
								$x = 1;
								while ($sql = mysqli_fetch_array($query)) {
									?>
									<tr>
										<td>
											<?= $x; ?>
										</td>
										<td>
											<?php
											if ($sql['type'] == '1') {
												print '<span class="text-primary">Concern (HO)</span>';
											} else if ($sql['type'] == '2') {
												print '<span class="text-info">Unit</span>';
											}
											?>
										</td>
										<td>
											<?= $sql['company_name']; ?>
										</td>
										<td>
											<?= $sql['company_code']; ?>
										</td>
										<td>
											<?= $sql['mobile']; ?>
										</td>
										<td>
											<?= $sql['gst_no']; ?>
										</td>
										<td>
											<?= $sql['pan_no']; ?>
										</td>
										<td>
											<?php if ($sql['is_active'] == 'active') { ?>
												<span class="badge badge-success"
													onclick="changeStatus(<?= $sql['id']; ?>,'company','inactive')">Active</span>
											<?php } else { ?>
												<span class="badge badge-danger"
													onclick="changeStatus(<?= $sql['id']; ?>,'company','active')">Inactive</span>
											<?php } ?>
										</td>
										<td>
											<div class="dropdown">
												<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
													href="#" role="button" data-toggle="dropdown">
													<i class="dw dw-more"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
												    <?php if(MAS_COMPANY_VIEW ==1) { ?>
													    <a class="dropdown-item" onclick="getcompanydetails(<?= $sql['id']; ?>)"><i class="dw dw-eye"></i> View</a>
												    <?php } if(MAS_COMPANY_EDIT ==1) { ?>
													    <a class="dropdown-item" href="add-company.php?id=<?= $sql['id']; ?>"><i class="dw dw-edit2"></i> Edit</a>
												    <?php } if(MAS_COMPANY_DELETE ==1) { ?>
    														<a class="dropdown-item" onclick="delete_data(<?= $sql['id']; ?>, 'company')" href="#"><i class="dw dw-delete-3"></i> Delete</a>
													<?php } ?>
												</div>
											</div>
										</td>
									</tr>
									<?php $x++;
								} ?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- Export Datatable End -->
			</div>
			<?php include('includes/footer.php'); ?>
		</div>
	</div>

	<div class="modal fade bs-example-modal-lg" id="companydetailmodal" tabindex="-1" role="dialog"
		aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-top ">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Company Details</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body" id="companynodal">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		function getcompanydetails(id) {

			$.ajax({
				type: 'POST',
				url: 'ajax_search.php?getcompanydetails=' + id,
				success: function (msg) {
					$("#companynodal").html(msg);
				}
			})
			$("#companydetailmodal").modal('show');
		}
	</script>

	<!-- js -->
	<?php include('includes/end_scripts.php'); ?>

</body>

</html>