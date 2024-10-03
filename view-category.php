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
	<title>BENSO GARMENTING - Category</title>

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
		if ($_SESSION['msg'] == 'saved') { ?>
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>Success!</strong> Category Added.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
		<?php } else if ($_SESSION['msg'] == 'updated') { ?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong>Success!</strong> Category Updated.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
		<?php } else if ($_SESSION['msg'] == 'error') { ?>
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
						<strong>Error!</strong> Something Went Wrong!.
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
				    
				    <?php if(MAS_COMPANY!=1) { action_denied(); exit; } ?>
				    
					<div class="pd-20">
						<a class="btn btn-primary showmodal" href="javascript:void(0)" style="float: right;">+ Add
							New</a>
						<h4 class="text-blue h4">Manage Category
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
									<th>Category Name</th>
									<th style="width:20%">Image</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$qry = "SELECT * FROM category ORDER BY id DESC";
								$query = mysqli_query($mysqli, $qry);
								$x = 1;
								while ($sql = mysqli_fetch_array($query)) {
									?>
									<tr>
										<td>
											<?= $x; ?>
										</td>
										<td>
											<?= $sql['category_name']; ?>
										</td>
										<td>
											<input type="hidden" value="uploads/category/<?= $sql['image'] ?>" name=""
												id="dsff<?= $sql['id']; ?>">
											<a data-img="" onclick="showimage(<?= $sql['id']; ?>)">
												<img src="uploads/category/<?= $sql['image'] ?>" alt="" width="50px">
											</a>
										</td>
										<td>
											<?php if ($sql['is_active'] == 'active') { ?>
												<span class="badge badge-success"
													onclick="changeStatus(<?= $sql['id']; ?>,'category','inactive')">Active</span>
											<?php } else { ?>
												<span class="badge badge-danger"
													onclick="changeStatus(<?= $sql['id']; ?>,'category','active')">Inactive</span>
											<?php } ?>
										</td>
										<td>
											<div class="dropdown">
												<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
													href="#" role="button" data-toggle="dropdown">
													<i class="dw dw-more"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
													<!-- <a class="dropdown-item" onclick="getcompanydetails(<?= $sql['id']; ?>)"><i class="dw dw-eye"></i> View</a> -->
													<a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>"
														href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
													<?php if ($sql['can_delete'] == 'yes') { ?>
														<form id="delete-company" method="post" autocomplete="off">
															<input type="hidden">
															<a class="dropdown-item"
																onclick="delete_data(<?= $sql['id']; ?>, 'category')"
																href="#"><i class="dw dw-delete-3"></i> Delete</a>
														</form>
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

				<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
					aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="myLargeModalLabel">Add Category</h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							</div>
							<form action="ajax_action.php?save_category" method="post" autocomplete="off"
								enctype="multipart/form-data">

								<div class="modal-body">
									<div class="row">
										<div class="col-md-12">
											<label>Category Name <span class="text-danger">*</span></label>
											<div class="form-group">
												<input type="text" class="form-control d-cursor" name="category_name"
													id="category_name" placeholder="Category Name" required>
											</div>
										</div>

										<div class="col-md-12">
											<label>Process </label>
											<div class="form-group">
												<select name="process_list[]" id="process_list"
													class="custom-select2 form-control" style="width: 100% !important;"
													multiple="multiple">
													<?= select_dropdown('process', array('id', ' process_name '), ' process_name  ASC', '', '', ''); ?>
												</select>
											</div>
										</div>

										<div class="col-md-12">
											<label>Sub Process </label>
											<div class="form-group">
												<select name="sub_process_list[]" id="sub_process_list"
													class="custom-select2 form-control" style="width: 100% !important;"
													multiple="multiple">
													<?= select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', '', '', ''); ?>
												</select>
											</div>
										</div>

										<div class="col-md-12">
											<label>Category Image</label>
											<div class="form-group">
												<input type="file" name="category_image" id="category_image">
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-success">Submit</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
					aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="myLargeModalLabel">Edit Category</h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							</div>
							<form action="ajax_action.php?edit_category" method="post" autocomplete="off"
								enctype="multipart/form-data">

								<div class="modal-body">
									<div class="row" id="editmodaldetail____">

										<div class="col-md-12">
											<label>Category Name <span class="text-danger">*</span></label>
											<div class="form-group">
												<input type="text" class="form-control d-cursor" name="category_name"
													id="category_name_edit" placeholder="Category Name" required>
											</div>
										</div>

										<div class="col-md-12">
											<label>Process </label>
											<div class="form-group">
												<select name="process_list[]" id="process_list_edit"
													class="custom-select2 form-control" style="width: 100% !important;"
													multiple="multiple">
													<?= select_dropdown('process', array('id', ' process_name '), ' process_name  ASC', '', '', ''); ?>
												</select>
											</div>
										</div>

										<div class="col-md-12">
											<label>Sub Process </label>
											<div class="form-group">
												<select name="sub_process_list[]" id="sub_process_list_edit"
													class="custom-select2 form-control" style="width: 100% !important;"
													multiple="multiple">
													<?= select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', '', '', ''); ?>
												</select>
											</div>
										</div>

										<div class="col-md-12">
											<label>Category Image</label>
											<div class="form-group">
												<input type="file" name="category_image" id="category_image">
												<input type="hidden" name="category_id" id="category_id" value="">
												<input type="hidden" name="old_pic" id="old_pic" value="">
											</div>
										</div>

									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-success">Submit</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="modal fade" id="image-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
					aria-hidden="true">
					<div class="modal-dialog modal-dialog-top">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="myLargeModalLabel">Image View</h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							</div>
							<form method="post" id="var_modalform" enctype="multipart/form-data">

								<div class="modal-body">
									<div class="row" id="img_space"></div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								</div>
							</form>
						</div>
					</div>
				</div>

			</div>
			<?php include('includes/footer.php'); ?>
		</div>
	</div>
	<!-- js -->
	<?php include('includes/end_scripts.php'); ?>

	<script>
		$("#process_list").change(function () {
			var id = $(this).val();

			$.ajax({
				type: 'POST',
				url: 'ajax_search.php?getSubProcess=1&id=' + id,
				success: function (msg) {

					$("#sub_process_list").html(msg);
				}
			})
		})

		$("#process_list_edit").change(function () {
			var id = $(this).val();

			$.ajax({
				type: 'POST',
				url: 'ajax_search.php?getSubProcess=1&id=' + id,
				success: function (msg) {

					$("#sub_process_list_edit").html(msg);
				}
			})
		})
	</script>

	<script>
		$('#add-modal').on('shown.bs.modal', function () {
			$('#category_name').focus();
		})
		$('#edit-modal').on('shown.bs.modal', function () {
			$('#category_name').focus();
		})


		$(".showmodal").click(function () {
			$("#add-modal").modal('show');
		})
	</script>

	<script>
		$(".editmodal").click(function () {

			var id = $(this).attr('data-id');

			$.ajax({
				type: 'POST',
				url: 'ajax_search.php?getcategory=1&id=' + id,
				success: function (msg) {
					var json = $.parseJSON(msg);

					$("#category_name_edit").val(json.category_name);
					$("#category_id").val(json.category_id);
					$("#old_pic").val(json.old_pic);
					$("#process_list_edit").html(json.process);
					$("#sub_process_list_edit").html(json.sub_process);
				}
			})

			$("#edit-modal").modal('show');
		})
	</script>

</html>