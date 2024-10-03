<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

$data = array(

// 	'company_name' => filter_var($_POST['company_name'], FILTER_SANITIZE_STRING),

// 	'company_code' => filter_var($_POST['company_code'], FILTER_SANITIZE_STRING),

// 	'address1' => filter_var($_POST['address1'], FILTER_SANITIZE_STRING),

// 	'address2' => filter_var($_POST['address2'], FILTER_SANITIZE_STRING),

// 	'area' => filter_var($_POST['area'], FILTER_SANITIZE_STRING),

// 	'country' => filter_var($_POST['country'], FILTER_SANITIZE_STRING),

// 	'state' => filter_var($_POST['state'], FILTER_SANITIZE_STRING),

// 	'city' => filter_var($_POST['city'], FILTER_SANITIZE_STRING),

// 	'mobile' => filter_var($_POST['mobile_no'], FILTER_SANITIZE_STRING),

// 	'phone1' => filter_var($_POST['phone_no1'], FILTER_SANITIZE_STRING),

// 	'phone2' => filter_var($_POST['phone_no2'], FILTER_SANITIZE_STRING),

// 	'website' => filter_var($_POST['website'], FILTER_SANITIZE_STRING),

// 	'gst_no' => filter_var($_POST['gst_no'], FILTER_SANITIZE_STRING),

// 	'pan_no' => filter_var($_POST['pan_no'], FILTER_SANITIZE_STRING),

// 	'other' => filter_var($_POST['other_license'], FILTER_SANITIZE_STRING),

// 	'so_caption' => filter_var($_POST['so_caption'], FILTER_SANITIZE_STRING),

// 	'mail_id' => filter_var($_POST['mail_id'], FILTER_SANITIZE_STRING),

// 	'account_info' => filter_var($_POST['account_info'], FILTER_SANITIZE_STRING),

// 	'ex_info' => filter_var($_POST['ex_info'], FILTER_SANITIZE_STRING),

// 	'type' => filter_var($_POST['typee'], FILTER_SANITIZE_STRING),

// 	'in_time' => filter_var($_POST['in_time'], FILTER_SANITIZE_STRING),

// 	'out_time' => filter_var($_POST['out_time'], FILTER_SANITIZE_STRING),

// 	'working_hr' => filter_var($_POST['working_hr'], FILTER_SANITIZE_STRING),

// 	'created_date' => date('Y-m-d H:i:s')

);

if (isset($_POST['addCompany']) && isset($_GET['id'])) {
	$qry = Update('company', $data, " WHERE id = '" . $_GET['id'] . "'");

	$_SESSION['msg'] = "updated";

	header("Location:view-company.php");

	exit;
} else if (isset($_POST['addCompany']) && !isset($_GET['id'])) {

	$qry = Insert('company', $data);

	$_SESSION['msg'] = "added";

	header("Location:view-company.php");

	exit;
}


if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$comp = 'Edit Company';
	$sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM company WHERE id=" . $id));
} else {
	$comp = 'Add Company';
	$id = '';
}

?>
<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>BENSO GARMENTING - Add Production Bill </title>

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

	<style>
		.custom-select2 {
			width: 100% !important;
		}
	</style>
</head>

<body>

	<?php include('includes/header.php'); ?>

	<?php include('includes/sidebar.php'); ?>

	<div class="main-container nw-cont">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">

				<!-- Default Basic Forms Start -->
				<div class="pd-20 card-box mb-30">
				    
				    <?php if(MAS_COMPANY_ADD!=1) { action_denied(); exit; } ?>
				    
					<div class="pd-20">
						<a class="btn btn-outline-primary" href="bill_passing.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Production Bill List</a>
					</div>
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4">Production Bill Passing</h4>
							<p class="mb-30 text-danger">(*) Fields are Mandatory</p>
						</div>
					</div>
					<form id="add-bill" method="post" autocomplete="off">
						<div class="row">
							<?php
							if (isset($_GET['id'])) {
								$code = $sql['company_code'];
							} else {
								$qryz = mysqli_query($mysqli, "SELECT * FROM company WHERE company_code LIKE '%PBP-%' ORDER BY id DESC");
								$sqql = mysqli_fetch_array($qryz);
								$numm = mysqli_num_rows($qryz);
								if ($numm == 0) {
									$code = 'PBP-1';
								} else {
									$ex = explode('-', $sqql['company_code']);

									$value = $ex[1];
									$intValue = (int) $value;
									$newValue = $intValue + 1;
									// $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);
							
									$code = $ex[0] . '-' . $newValue;
								}
							}
							?>
							<div class="col-md-4">
								<label class="col-form-label">Entry Number <span class="text-danger">*</span></label>
								<div class="form-group">
									<input class="form-control d-cursor" type="text" name="entry_number" id="entry_number" placeholder="Entry Number" value="<?= $code; ?>" readonly>
								</div>
							</div>


							<div class="col-md-4">
								<label class="col-form-label">Entry Date <span class="text-danger">*</span></label>
								<div class="form-group">
									<input class="form-control" type="date" name="entry_date" id="entry_date"value="<?= date('Y-m-d'); ?>" >
								</div>
							</div>
							
						</div>
						<div class=" row">
							<div class="col-md-12">
								<div class="form-group">
									<input type="submit" class="btn btn-success button-right" name="addCompany"
										value="Submit">
									<a class="btn btn-secondary button-right" href="view-company.php">Cancel</a>
								</div>
							</div>
						</div>
					</form>
				</div>
				<!-- Default Basic Forms End -->

			</div>

			<?php include('includes/footer.php'); ?>

		</div>
	</div>
	<!-- js -->
	<?php include('includes/end_scripts.php'); ?>

	<script>
		$("#country").change(function () {
			var country = $("#country").val();

			$.ajax({
				type: 'POST',
				url: 'ajax_search.php?get_state=1&country=' + country,
				success: function (msg) {
					$("#state").html(msg);
				}
			})
		})
	</script>

	<script>
		$("#state").change(function () {
			var state = $("#state").val();

			$.ajax({
				type: 'POST',
				url: 'ajax_search.php?get_city=' + state,
				success: function (msg) {
					$("#city").html(msg);
				}
			})
		})
	</script>

	<script type="text/javascript">
		$(function () {
			$('#add-company').validate({
				errorClass: "help-block",
				rules: {
					company_name: {
						required: true
					},
					company_code: {
						required: true
					}
				},
				errorPlacement: function (label, element) {
					label.addClass('mt-2 text-danger');
					label.insertAfter(element);
				},
				highlight: function (element, errorClass) {
					$(element).parent().addClass('has-danger')
					$(element).addClass('form-control-danger')
				}
			});
		});
	</script>

</body>

</html>