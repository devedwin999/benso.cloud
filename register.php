<?php
include("includes/connection.php");
include("includes/function.php");

if(isset($_SESSION['login_id']))
{
    header('Location:dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>BENSO - Login Register</title>

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
	<link rel="stylesheet" type="text/css" href="src/plugins/jquery-steps/jquery.steps.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
	
	
	<style>
	    .select-role {
            margin-bottom: 25px;
            padding: 0px 20px;
        }
        
        .container {
            /*margin-right: 35%;*/
        }
        
        @media (max-width: 768px) {
            .steps {
                display:none !important;
            }
            
            .newpadCls {
                padding:10px !important;
            }
            
            .onlyMobileView {
                display:flex !important;
                display: flex !important;
                justify-content: space-around;
            }
        }
	</style>
</head>

<body class="login-page">
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="index.php">
					<h4>BENSO</h4>
				</a>
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="vendors/images/register-page-img.png" alt="">
					<br>
					
					<label class="col-form-label" style="font-size: 20px;color: green;font-weight: bold;">Register Tracking </label>
					<div class="form-group" style="display:flex">
					    <input type="number" class="form-control" name="track_num" id="track_num" placeholder="Enter Mobile Number" style="width:75%"> &nbsp;
					    <input type="button" class="btn btn-primary" onclick="trackMobile()" value="Track">
					</div>
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="register-box bg-white box-shadow border-radius-10">
						<div class="wizard-content">
							<form method="POST" id="empRegisterForm" action="ajax_action.php?register_employee" autocomplete="off" enctype="multipart/form-data" class="tab-wizard2 wizard-circle wizard">
								<h5>Basic Info</h5>
								<section>
									<div class="form-wrap max-width-600 mx-auto newpadCls">
										<div class="row">
										    <div class="col-md-12 onlyMobileView" style="color: orange;text-decoration: underline;text-align: center;display:none">
										        <span class="info">Basic Info</span>
										    </div>
                							<div class="col-md-6">
                								<label class="col-form-label">Employee Type <span class="text-danger">*</span></label>
                								<div class="form-group">
                								    <select class="form-control custom-select2" name="type" id="type" style="width:100%">
                								        <option value="user">Staff</option>
                								        <option value="employee">Worker</option>
                								    </select>
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
                								<div class="form-group">
                									<input class="form-control" type="text" name="employee_name" id="employee_name" placeholder="Employee Name">
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Employee Photo <span class="text-danger">*</span></label>
                								<div class="form-group">
                									<input class="form-control" type="file" name="employee_photo" id="employee_photo">
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Employee Code <span class="text-danger"></span></label>
                								<div class="form-group">
                									<input class="form-control d-cursor valid_employee_code" type="text" name="employee_code" id="employee_code" placeholder="Employee Code">
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">DOB <span class="text-danger">*</span></label>
                								<div class="form-group">
                									<input class="form-control" type="date" name="dob" id="dob" placeholder="Date Of Birth">
                								</div>
                							</div>
                        					 <!--date-picker-->
                        					<div class="col-md-6">
                        						<label class="col-form-label">Age <span class="text-danger">*</span></label>
                        						<div class="form-group">
                        							<input class="form-control" type="text" name="age" id="age" placeholder="Age Will be calculated Automatically" readonly style="background-color:#fff">
                        						</div>
                        					</div>
                        					
                        					<div class="col-md-6">
                        						<label class="col-form-label">Gender <span class="text-danger">*</span></label>
                        						<div class="form-group">
                        							<input style="min-width:20px !important" type="radio" name="gender" id="Male" value="Male"> <label for="Male">Male</label>
                        							<input style="min-width:20px !important" type="radio" name="gender" id="Female" value="Female"> <label for="Female">Female</label>
                        						</div>
                        					</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Email</label>
                								<div class="form-group">
                									<input class="form-control" type="email" name="email" id="email" placeholder="Email">
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Mobile Number <span class="text-danger">*</span></label>
                								<div class="form-group">
                									<input class="form-control" type="number" name="mobile" onkeyup="checkMax()" id="mobile" placeholder="Mobile Number">
                								</div>
                							</div>
                							
                							<script>
                							    function checkMax() {
                							        var a = $("#mobile").val().length;
                							        
                							    }
                							</script>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Department <span class="text-danger">*</span></label>
                								<div class="form-group">
                								    <select class="form-control custom-select2" name="department" id="department" style="width:100%">
                                                        <?= select_dropdown('department', array('id', 'department_name'), 'id ASC', '', '', ''); ?>
                								    </select>
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Designation <span class="text-danger">*</span></label>
                								<div class="form-group">
                								    <select class="form-control custom-select2" name="designation" id="designation" style="width:100%">
                                                        <?= select_dropdown('mas_designation', array('id', 'desig_name'), 'id ASC', '', '', ''); ?>
                								    </select>
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Working Place <span class="text-danger">*</span></label>
                								<div class="form-group">
                								    <select class="form-control custom-select2" name="company" id="company" style="width:100%">
                                                        <?= select_dropdown('company', array('id', 'company_name'), 'id ASC', '', '', ''); ?>
                								    </select>
                								</div>
                							</div>
                							
                							<div class="col-md-12 onlyMobileView" style="color: orange;text-decoration: underline;text-align: center;display:none">
										        <span class="info">Benso App Login Info</span>
										    </div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">User Name</label>
                								<div class="form-group">
                									<input class="form-control" type="text" name="username" id="username" placeholder="User Name">
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Password</label>
                								<div class="form-group">
                									<input class="form-control" type="text" name="password" id="password" placeholder="Password">
                								</div>
                							</div>
        							    </div>
        							    
									</div>
								</section>
								
								<h5>Address Info</h5>
								<section>
									<div class="form-wrap max-width-600 mx-auto newpadCls">
									
									    <div class="row">
									        <div class="col-md-12 onlyMobileView" style="color: orange;text-decoration: underline;text-align: center;display:none">
										        <span class="info">Address Info</span>
										    </div>
									        
        							        <div class="col-md-12"><h4 style="text-decoration:underline;">Communication Address</h4></div>
                							
                							<div class="col-md-6">
                                                <label class="col-form-label">Address 1</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="address1_com" id="address1_com" placeholder="Address 1">
                                                </div>
                                            </div>
                
                                            <div class="col-md-6">
                                                <label class="col-form-label">Address 2</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="address2_com" id="address2_com" placeholder="Address 2">
                                                </div>
                                            </div>
                
                                            <div class="col-md-6">
                                                <label class="col-form-label">Area</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="area_com" id="area_com" placeholder="Area">
                                                </div>
                                            </div>
                
                                            <div class="col-md-6">
                                                <label class="col-form-label">Pincode</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="pincode_com" id="pincode_com" placeholder="Pincode">
                                                </div>
                                            </div>
                
                                            <div class="col-md-6">
                                                <label for="">Country</label>
                                                <div class="form-group">
                                                    <select name="country_com" id="country_com" class="custom-select2 form-control" onchange="getState('_com')" style="width:100%">
                                                        <?= select_dropdown('master_country', array('auto_number', 'country'), 'country ASC', $sql['country'] ? $sql['country'] : 101, '', ''); ?>
                                                    </select>
                                                </div>
                                            </div>
                
                                            <div class="col-md-6">
                                                <label class="col-form-label">State</label>
                                                <div class="form-group">
                                                    <select class="custom-select2 form-control" name="state_com" id="state_com" onchange="getCity('_com')" style="width:100%">
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
                
                                            <div class="col-md-12">
                                                <label class="col-form-label">City</label>
                                                <div class="form-group">
                                                    <select class="custom-select2 form-control" name="city_com" id="city_com" style="width:100%">
                                                        <option value="">Select City</option>
                                                        <?php
                                                        if (isset($_GET['id']) && !empty($sql['state'])) {
                                                            $qryd1 = mysqli_query($mysqli, "SELECT * FROM cities WHERE state_id = '" . $sql['state'] . "' ORDER BY cities_name ASC");
                                                            
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
                                            
                                            <div class="col-md-12"><hr></div>
                                            
                                            <div class="col-md-12"><h4 style="text-decoration:underline;">Permanaent Address</h4></div>
                							
                							<div class="col-md-6">
                                                <label class="col-form-label">Address 1</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="address1_per" id="address1_per" placeholder="Address 1">
                                                </div>
                                            </div>
                
                                            <div class="col-md-6">
                                                <label class="col-form-label">Address 2</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="address2_per" id="address2_per" placeholder="Address 2">
                                                </div>
                                            </div>
                
                                            <div class="col-md-6">
                                                <label class="col-form-label">Area</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="area_per" id="area_per" placeholder="Area">
                                                </div>
                                            </div>
                
                                            <div class="col-md-6">
                                                <label class="col-form-label">Pincode</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="pincode_per" id="pincode_per" placeholder="Pincode">
                                                </div>
                                            </div>
                
                                            <div class="col-md-6">
                                                <label for="">Country</label>
                                                <div class="form-group">
                                                    <select name="country_per" id="country_per" class="custom-select2 form-control" onchange="getState('_per')" style="width:100%">
                                                        <?= select_dropdown('master_country', array('auto_number', 'country'), 'country ASC', $sql['country'] ? $sql['country'] : 101, '', ''); ?>
                                                    </select>
                                                </div>
                                            </div>
                
                                            <div class="col-md-6">
                                                <label class="col-form-label">State</label>
                                                <div class="form-group">
                                                    <select class="custom-select2 form-control" name="state_per" id="state_per" onchange="getCity('_per')" style="width:100%">
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
                
                                            <div class="col-md-12">
                                                <label class="col-form-label">City</label>
                                                <div class="form-group">
                                                    <select class="custom-select2 form-control" name="city_per" id="city_per" style="width:100%">
                                                        <option value="">Select City</option>
                                                        <?php
                                                        if (isset($_GET['id']) && !empty($sql['state'])) {
                                                            $qryd1 = mysqli_query($mysqli, "SELECT * FROM cities WHERE state_id = '" . $sql['state'] . "' ORDER BY cities_name ASC");
                                                            
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
                                            
        							    </div>
									    
									</div>
								</section>
								
								<h5>Proof Info</h5>
								<section>
									<div class="form-wrap max-width-600 mx-auto newpadCls">
										
										<div class="row">
										    
										    <div class="col-md-12 onlyMobileView" style="color: orange;text-decoration: underline;text-align: center;display:none">
										        <span class="info">Proof Info</span>
										    </div>
										    
                							<div class="col-md-6">
                								<label class="col-form-label">Aadhar Card <span class="text-danger">*</span></label>
                								<div class="form-group">
                									<input class="form-control" type="file" name="aadhar_card" id="aadhar_card">
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Pan Card</label>
                								<div class="form-group">
                									<input class="form-control" type="file" name="pan_card" id="pan_card">
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">License</label>
                								<div class="form-group">
                									<input class="form-control" type="file" name="license" id="license">
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Other Documents</label>
                								<div class="form-group">
                									<input class="form-control" type="file" name="other_docs" id="other_docs">
                								</div>
                							</div>
        							    </div>
        							    
									</div>
								</section>
								
								<h5>Bank Info</h5>
								<section>
									<div class="form-wrap max-width-600 mx-auto newpadCls">
									    
									    <div class="row">
									        
									        <div class="col-md-12 onlyMobileView" style="color: orange;text-decoration: underline;text-align: center;display:none">
										        <span class="info">Bank Info</span>
										    </div>
									        
                							<div class="col-md-6">
                								<label class="col-form-label">Account Holder Name</label>
                								<div class="form-group">
                									<input class="form-control" type="text" name="acc_holder_name" id="acc_holder_name" placeholder="Account Holder Name">
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Account Number</label>
                								<div class="form-group">
                									<input class="form-control" type="number" name="acc_num" id="acc_num" placeholder="Account Number">
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">IFSC Code</label>
                								<div class="form-group">
                									<input class="form-control" type="text" name="ifsc" id="ifsc" placeholder="IFSC Code">
                								</div>
                							</div>
                							
                							<div class="col-md-6">
                								<label class="col-form-label">Bank Name</label>
                								<div class="form-group">
                									<input class="form-control" type="text" name="bank_name" id="bank_name" placeholder="Bank Name">
                								</div>
                							</div>
                							
                							<div class="col-md-12">
                								<label class="col-form-label">Branch</label>
                								<div class="form-group">
                									<input class="form-control" type="text" name="bank_branch" id="bank_branch" placeholder="Branch">
                								</div>
                							</div>
                							
                							<div class="col-md-12" style="text-align:right">
                							    <button class="btn btn-outline-primary DummyUpdate d-none" type="button" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Updating...
                                                </button>
                							</div>
                							
        							    </div>
        							    
									</div>
								</section>
							</form>
							<div class="text-center">
							    <a href="index.php" style="padding-bottom: 10px;text-decoration: underline;">Go Back</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	<script>
		
		$(document).ready(function () {
			$('#employee_name').focus();
		});
		
	</script>
	<!-- js -->
		<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="src/plugins/jquery-steps/jquery.steps.js"></script>
	<script src="vendors/scripts/steps-setting.js"></script>
	
	<script src="src/plugins/sweetalert2/sweetalert2.all.js"></script>
    <script src="src/plugins/sweetalert2/sweet-alert.init.js"></script>
    
    <script src="quickaction.js"></script>
    
    <script>
        function trackMobile() {
            var num = $("#track_num").val();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?checkStatusOfRegisteredUser=1&mobile=' + num,
                success: function (msg) {
                    
                    var json = $.parseJSON(msg);
                    
                    swal(
                        {
                            title: ''+ json.title +'',
                            text: ''+ json.text + '',
                            type: ''+ json.type + '',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-success',
                        }
                    )
                }
            })
        }
    </script>
    



    <script>
		$(document).ready(function () {
		    var a = <?= $_SESSION['msg']; ?>;
		    
		    if(a!="") {
		        message_noload('success', "Successfully Registered", 2500);
		    }
		});
	</script>

<?php $_SESSION['msg'] = ""; ?>

    <script>
        $(".saveBtn").click(function() {
            var a  = $(this).text();
            
            if(a == 'Submit') {
                save_employee();
            }
        })

    </script>
    
    
    <script>
        function getState(cls) {
            var country = $("#country" + cls).val();

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?get_state=1&country=' + country,
                success: function (msg) {
                    $("#state" + cls).html(msg);
                }
            })
        }
    </script>

    <script>
        function getCity(cls) {
            var state = $("#state" + cls).val();

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?get_city=' + state,
                success: function (msg) {
                    $("#city" + cls).html(msg);
                }
            })
        }
    </script>
    
    
    <script>
        $("#dob").change(function() {
            dob = new Date($(this).val());
            var today = new Date();
            var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
            $('#age').val(age);
            
            // alert(age);
        })
    </script>
    
    <script>
        function save_employee() {
            
            if ($("#employee_name").val() == "") {
                $("#employee_name").focus();
                message_noload('warning', 'Employee Name Required In Basic Info!', 2000);
                return false;
            } else if ($("#employee_photo").val() == "") {
                $("#employee_photo").focus();
                message_noload('warning', 'Employee Photo Required In Basic Info!', 2000);
                return false;
            // } else if ($("#employee_code").val() == "") {
            //     $("#employee_code").focus();
            //     message_noload('warning', 'Employee Code Required In Basic Info!', 2000);
            //     return false;
            } else if ($("#mobile").val() == "") {
                $("#mobile").focus();
                message_noload('warning', 'Mobile Number Required In Basic Info!', 2000);
                return false;
            } else if ($("#department").val() == "") {
                $("#department").focus();
                message_noload('warning', 'Department Required In Basic Info!', 2000);
                return false;
            } else if ($("#designation").val() == "") {
                $("#designation").focus();
                message_noload('warning', 'Designation Required In Basic Info!', 2000);
                return false;
            } else if ($("#company").val() == "") {
                $("#company").focus();
                message_noload('warning', 'Working Place Required In Basic Info!', 2000);
                return false;
            } else if ($("#dob").val() == "") {
                $("#dob").focus();
                message_noload('warning', 'Date Of Birth Required In Basic Info!', 2000);
                return false;
            } else {
                
                var mobile = $("#mobile").val()
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action.php?validateMobile=1&mobile=' + mobile ,
                    
                    success: function (msg) {
                        
                        var json = $.parseJSON(msg);
                        if (json.numm > 0) {
                            message_noload('warning', 'Mobile Number Already Registered');
                            
                            return false;
                        } else {
                            $(".saveBtn").addClass('d-none');
                            $(".DummyUpdate").removeClass('d-none');
                            
                            $("#empRegisterForm").submit();
                        }
                    }
                })
            }
        }
        
    </script>


</body>

</html>