<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

$data = array(

	'company_name' => filter_var($_POST['company_name'], FILTER_SANITIZE_STRING),

	'company_code' => filter_var($_POST['company_code'], FILTER_SANITIZE_STRING),

	'address1' => filter_var($_POST['address1'], FILTER_SANITIZE_STRING),

	'address2' => filter_var($_POST['address2'], FILTER_SANITIZE_STRING),

	'area' => filter_var($_POST['area'], FILTER_SANITIZE_STRING),

	'country' => filter_var($_POST['country'], FILTER_SANITIZE_STRING),

	'state' => filter_var($_POST['state'], FILTER_SANITIZE_STRING),

	'city' => filter_var($_POST['city'], FILTER_SANITIZE_STRING),

	'mobile' => filter_var($_POST['mobile_no'], FILTER_SANITIZE_STRING),

	'phone1' => filter_var($_POST['phone_no1'], FILTER_SANITIZE_STRING),

	'phone2' => filter_var($_POST['phone_no2'], FILTER_SANITIZE_STRING),

	'website' => filter_var($_POST['website'], FILTER_SANITIZE_STRING),

	'gst_no' => filter_var($_POST['gst_no'], FILTER_SANITIZE_STRING),

	'pan_no' => filter_var($_POST['pan_no'], FILTER_SANITIZE_STRING),

	'other' => filter_var($_POST['other_license'], FILTER_SANITIZE_STRING),

	'so_caption' => filter_var($_POST['so_caption'], FILTER_SANITIZE_STRING),

	'mail_id' => filter_var($_POST['mail_id'], FILTER_SANITIZE_STRING),

	'account_info' => filter_var($_POST['account_info'], FILTER_SANITIZE_STRING),

	'ex_info' => filter_var($_POST['ex_info'], FILTER_SANITIZE_STRING),

	'type' => filter_var($_POST['typee'], FILTER_SANITIZE_STRING),

	'in_time' => filter_var($_POST['in_time'], FILTER_SANITIZE_STRING),

	'out_time' => filter_var($_POST['out_time'], FILTER_SANITIZE_STRING),

	'working_hr' => filter_var($_POST['working_hr'], FILTER_SANITIZE_STRING),

	'created_date' => date('Y-m-d H:i:s')

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
	<title>BENSO GARMENTING -
		<?= $comp; ?>
	</title>

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
						<a class="btn btn-primary" href="view-company.php" style="float: right;"><i class="fa fa-list"
								aria-hidden="true"></i> Company List</a>
					</div>
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4">
								<?= $comp; ?>
							</h4>
							<p class="mb-30 text-danger">(*) Fields are Mandatory</p>
						</div>
					</div>
					<form id="add-company" method="post" autocomplete="off">
						<div class="row">
							<div class="col-md-4">
								<label class="col-form-label">Type <span class="text-danger">*</span></label>
								<div class="form-group">
									<select name="typee" id="typee" class="custom-select2 form-control">
										<option value="1">Concern (HO)</option>
										<option value="2">Unit</option>
									</select>
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Company Name <span class="text-danger">*</span></label>
								<div class="form-group">
									<input class="form-control d-cursor" type="text" name="company_name"
										id="company_name" placeholder="Company Name"
										value="<?= $sql['company_name'] ? $sql['company_name'] : ''; ?>">
								</div>
							</div>

							<?php
							if (isset($_GET['id'])) {
								$code = $sql['company_code'];
							} else {
								$qryz = mysqli_query($mysqli, "SELECT * FROM company WHERE company_code LIKE '%com-%' ORDER BY id DESC");
								$sqql = mysqli_fetch_array($qryz);
								$numm = mysqli_num_rows($qryz);
								if ($numm == 0) {
									$code = 'COM-1';
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
								<label class="col-form-label">Company Code <span class="text-danger">*</span></label>
								<div class="form-group">
									<input class="form-control" type="text" name="company_code" id="company_code"
										placeholder="Company Code" value="<?= $code; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Address 1</label>
								<div class="form-group">
									<input type="text" class="form-control" name="address1" id="address1"
										placeholder="Address 1"
										value="<?= $sql['address1'] ? $sql['address1'] : ''; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Address 2</label>
								<div class="form-group">
									<input type="text" class="form-control" name="address2" id="address2"
										placeholder="Address 2"
										value="<?= $sql['address2'] ? $sql['address2'] : ''; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Area</label>
								<div class="form-group">
									<input class="form-control" type="text" name="area" id="area" placeholder="Area"
										value="<?= $sql['area'] ? $sql['area'] : ''; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label for="">Country</label>
								<div class="form-group">
									<select name="country" id="country" class="custom-select2 form-control">
										<?= select_dropdown('master_country', array('auto_number', 'country'), 'country ASC', $sql['country'] ? $sql['country'] : 101, '', ''); ?>
									</select>
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">State</label>
								<div class="form-group">
									<select class="custom-select2 form-control" name="state" id="state">
										<option value="">Select State</option>
										<?php
										if (isset($_GET['id']) && !empty($sql['country'])) {
											$where = "country_id='" . $sql['country'] . "'";
										} else {
											$where = "country_id='101'";
										}

										$qryd = mysqli_query($mysqli, "SELECT * FROM states WHERE $where ORDER BY state_name ASC ");
										while ($stt = mysqli_fetch_array($qryd)) {
											if (isset($_GET['id']) && !empty($sql['state'])) {
												if ($stt['id'] == $sql['state']) {
													$sell = 'selected';
												} else {
													$sell = '';
												}
											} else {
												$sell = '';
											}
											print '<option value="' . $stt['id'] . '" ' . $sell . '>' . $stt['state_name'] . '</option>';
										}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">City</label>
								<div class="form-group">
									<select class="custom-select2 form-control" name="city" id="city">
										<option value="">Select City</option>
										<?php
										if (isset($_GET['id']) && !empty($sql['state'])) {
											$qryd1 = mysqli_query($mysqli, "SELECT * FROM cities WHERE state_id = '" . $sql['state'] . "' ORDER BY cities_name ASC");
											// } else {
											// 	$qryd1 = mysqli_query($mysqli, "SELECT * FROM cities ORDER BY cities_name ASC limit 1,10");
											// }
											while ($stt1 = mysqli_fetch_array($qryd1)) {
												if ($stt1['id'] == $sql['city']) {
													$citt = 'selected';
												} else {
													$citt = '';
												}
												print '<option value="' . $stt1['id'] . '" ' . $citt . '>' . $stt1['cities_name'] . '</option>';
											}
										} ?>
									</select>
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Mobile No</label>
								<div class="form-group">
									<input type="text" class="form-control" name="mobile_no" id="mobile_no"
										placeholder="Mobile No" value="<?= $sql['mobile'] ? $sql['mobile'] : ''; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Phone No 1</label>
								<div class="form-group">
									<input type="text" class="form-control" name="phone_no1" id="phone_no1"
										placeholder="Phone No 1" value="<?= $sql['phone1'] ? $sql['phone1'] : ''; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Phone No 2</label>
								<div class="form-group">
									<input class="form-control" type="text" name="phone_no2" id="phone_no2"
										placeholder="Phone No 2" value="<?= $sql['phone2'] ? $sql['phone2'] : ''; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Website</label>
								<div class="form-group">
									<input type="text" class="form-control" name="website" id="website"
										placeholder="Website" value="<?= $sql['website'] ? $sql['website'] : ''; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">GST No</label>
								<div class="form-group">
									<input type="text" class="form-control" name="gst_no" id="gst_no"
										placeholder="GST No" value="<?= $sql['gst_no'] ? $sql['gst_no'] : ''; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">PAN No</label>
								<div class="form-group">
									<input class="form-control" type="text" name="pan_no" id="pan_no"
										placeholder="PAN No" value="<?= $sql['pan_no'] ? $sql['pan_no'] : ''; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Other License</label>
								<div class="form-group">
									<input type="text" class="form-control" name="other_license" id="other_license"
										placeholder="Other License" value="<?= $sql['other'] ? $sql['other'] : ''; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">SO No Caption</label>
								<div class="form-group">
									<input type="text" class="form-control" name="so_caption" id="so_caption"
										placeholder="SO No Caption" value="<?= $sql['so_caption'] ? $sql['so_caption']
											: ''; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Mail Id</label>
								<div class="form-group">
									<input type="text" class="form-control" name="mail_id" id="mail_id"
										placeholder="Mail Id" value="<?= $sql['mail_id'] ? $sql['mail_id'] : ''; ?>">
								</div>
							</div>

							<div class="col-md-4">
								<label class="col-form-label">Account Info</label>
								<div class="form-group">
									<input type="text" class="form-control" name="account_info" id="account_info" placeholder="Account Info" value="<?= $sql['account_info'] ? $sql['account_info'] : ''; ?>">
								</div>
							</div>

							<div class="col-md-2">
								<label class="col-form-label fieldrequired">in Time</label>
								<div class="form-group">
									<input type="time" class="form-control" name="in_time" id="in_time" placeholder="in Time" value="<?= $sql['in_time'] ? $sql['in_time'] : ''; ?>" onchange="timeCalculating()" required>
								</div>
							</div>

							<div class="col-md-2">
								<label class="col-form-label fieldrequired">Out Time</label>
								<div class="form-group">
									<input type="time" class="form-control" name="out_time" id="out_time" placeholder="Out Time" value="<?= $sql['out_time'] ? $sql['out_time'] : ''; ?>" onchange="timeCalculating()" required>
								</div>
							</div>

							<div class="col-md-2">
								<label class="col-form-label fieldrequired">Working Hours</label>
								<div class="form-group">
									<input type="text" readonly class="form-control" name="working_hr" id="working_hr" placeholder="Working Hours" value="<?= $sql['working_hr'] ? $sql['working_hr'] : ''; ?>" required>
								</div>
							</div>
							
							<script>
							    function timeCalculating()
                                  {
                                    var time1 = $("#in_time").val();
                                    var time2 = $("#out_time").val();
                                    var time1 = time1.split(':');
                                    var time2 = time2.split(':');
                                    var hours1 = parseInt(time1[0], 10), 
                                    hours2 = parseInt(time2[0], 10),
                                    mins1 = parseInt(time1[1], 10),
                                    mins2 = parseInt(time2[1], 10);
                                    var hours = hours2 - hours1, mins = 0;
                                    if(hours < 0) hours = 24 + hours;
                                    if(mins2 >= mins1) {
                                        mins = mins2 - mins1;
                                    }
                                    else {
                                      mins = (mins2 + 60) - mins1;
                                      hours--;
                                    }
                                    if(mins < 9)
                                    {
                                      mins = '0'+mins;
                                    }
                                    if(hours < 9)
                                    {
                                      hours = '0'+hours;
                                    }
                                    $("#working_hr").val(hours+':'+mins);
                                  }
							</script>

							<div class="col-md-12">
								<label class="col-form-label">Export/Import Info</label>
								<div class="form-group">
									<textarea name="ex_info" id="ex_info" style="height: 100px;"
										placeholder="Export/Import Info"
										class="form-control"><?= $sql['ex_info'] ? $sql['ex_info'] : ''; ?></textarea>
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

    
    <script>
        $("#company_name").change(function() {
            var value = $("#company_name").val();
            
            validate_Duplication('company', 'company_name', value, 'company_name');
            // validate_Duplication(table, table_field, value, input_field, input_field_name)
        });
    </script>
</body>

</html>