<?php
include("includes/connection.php");
include("includes/function.php");

if (isset($_POST['addCompany'])) {

	$data = array(

		'company_name'  =>  filter_var($_POST['company_name'], FILTER_SANITIZE_STRING),

		'company_code'  =>  filter_var($_POST['company_code'], FILTER_SANITIZE_STRING),

		'address1'  =>  filter_var($_POST['address1'], FILTER_SANITIZE_STRING),

		'address2'  =>  filter_var($_POST['address2'], FILTER_SANITIZE_STRING),

		'area'  =>  filter_var($_POST['area'], FILTER_SANITIZE_STRING),

		'state'  =>  filter_var($_POST['state'], FILTER_SANITIZE_STRING),

		'city'  =>  filter_var($_POST['city'], FILTER_SANITIZE_STRING),

		'mobile'  =>  filter_var($_POST['mobile_no'], FILTER_SANITIZE_STRING),

		'phone1'  =>  filter_var($_POST['phone_no1'], FILTER_SANITIZE_STRING),

		'phone2'  =>  filter_var($_POST['phone_no2'], FILTER_SANITIZE_STRING),

		'website'  =>  filter_var($_POST['website'], FILTER_SANITIZE_STRING),

		'gst_no'  =>  filter_var($_POST['gst_no'], FILTER_SANITIZE_STRING),

		'pan_no'  =>  filter_var($_POST['pan_no'], FILTER_SANITIZE_STRING),

		'other'  =>  filter_var($_POST['other_license'], FILTER_SANITIZE_STRING),

		'created_date' => date('Y-m-d H:i:s')

	);

	$qry = Insert('company', $data);

	$_SESSION['msg'] = "5";

	header("Location:view-company.php");

	exit;
}

?>
<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>BENSO - Add Company</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
</head>

<body>

	<?php include('includes/header.php'); ?>

	<?php include('includes/sidebar.php'); ?>

	<div class="main-container nw-cont">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">

				<!-- Default Basic Forms Start -->
				<div class="pd-20 card-box mb-30">
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4">Add Company</h4>
							<p class="mb-30 text-danger">(*) Fields are Mandatory</p>
						</div>
					</div>
					<form id="add-company" method="post" autocomplete="off">
						<div class="row">
							<div class="col-md-4">
								<label class="col-form-label">Company Name <span class="text-danger">*</span></label>
								<div class="form-group">
									<input class="form-control" type="text" name="company_name" id="company_name" placeholder="Company Name">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Company Code <span class="text-danger">*</span></label>
								<div class="form-group">
									<input class="form-control" type="text" name="company_code" id="company_code" placeholder="Company Code">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Address 1</label>
								<div class="form-group">
									<input type="text" class="form-control" name="address1" id="address1" placeholder="Address 1">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Address 2</label>
								<div class="form-group">
									<input type="text" class="form-control" name="address2" id="address2" placeholder="Address 2"></textarea>
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Area</label>
								<div class="form-group">
									<input class="form-control" type="text" name="area" id="area" placeholder="Area">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">State</label>
								<div class="form-group">
									<select class="form-control" name="state" id="state">
										<option value="">Select State</option>
									</select>
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">City</label>
								<div class="form-group">
									<select class="form-control" name="city" id="city">
										<option value="">Select City</option>
									</select>
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Mobile No</label>
								<div class="form-group">
									<input type="text" class="form-control" name="mobile_no" id="mobile_no" placeholder="Mobile No">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Phone No 1</label>
								<div class="form-group">
									<input type="text" class="form-control" name="phone_no1" id="phone_no1" placeholder="Phone No 1">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Phone No 2</label>
								<div class="form-group">
									<input class="form-control" type="text" name="phone_no2" id="phone_no2" placeholder="Phone No 2">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Website</label>
								<div class="form-group">
									<input type="text" class="form-control" name="website" id="website" placeholder="Website">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">GST No</label>
								<div class="form-group">
									<input type="text" class="form-control" name="gst_no" id="gst_no" placeholder="GST No">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">PAN No</label>
								<div class="form-group">
									<input class="form-control" type="text" name="pan_no" id="pan_no" placeholder="PAN No">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Other License</label>
								<div class="form-group">
									<input type="text" class="form-control" name="other_license" id="other_license" placeholder="Other License">
								</div>
							</div>

						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input type="submit" class="btn btn-success button-right" name="addCompany" value="Submit">
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
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			// Set the default cursor focus to a specific field, e.g., field2
			$('#company_name').focus();
		});

		$(function() {
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
				errorPlacement: function(label, element) {
					label.addClass('mt-2 text-danger');
					label.insertAfter(element);
				},
				highlight: function(element, errorClass) {
					$(element).parent().addClass('has-danger')
					$(element).addClass('form-control-danger')
				}
			});
		});
	</script>

</body>

</html>