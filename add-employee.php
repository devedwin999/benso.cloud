<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");



if (isset($_POST['employee_id'])) {

    $agee = $_POST['age'] ? intval($_POST['age']) : (date('Y') - date('Y', strtotime($dob)));

    $data = array(
        'type' => $_POST['type'],
        'employee_name' => $_POST['employee_name'],
        'employee_code' => $_POST['employee_code'],
        'mobile' => $_POST['mobile'],
        'email' => $_POST['email'],
        'gender' => $_POST['gender'],
        'dob' => $_POST['dob'],
        'age' => $agee,
        'department' => $_POST['department'],
        'designation' => $_POST['designation'],
        'company' => $_POST['company'],

        'address1_com' => $_POST['address1_com'],
        'address2_com' => $_POST['address2_com'],
        'area_com' => $_POST['area_com'],
        'pincode_com' => $_POST['pincode_com'],
        'country_com' => $_POST['country_com'],
        'state_com' => $_POST['state_com'],
        'city_com' => $_POST['city_com'],

        'address1_per' => $_POST['address1_per'],
        'address2_per' => $_POST['address2_per'],
        'area_per' => $_POST['area_per'],
        'pincode_per' => $_POST['pincode_per'],
        'country_per' => $_POST['country_per'],
        'state_per' => $_POST['state_per'],
        'city_per' => $_POST['city_per'],

        'acc_holder_name' => $_POST['acc_holder_name'],
        'acc_num' => $_POST['acc_num'],
        'ifsc' => $_POST['ifsc'],
        'bank_name' => $_POST['bank_name'],
        'bank_branch' => $_POST['bank_branch'],

        'basic_salary' => $_POST['basic_salary'],
        'house_rent' => $_POST['house_rent'],
        'pf' => $_POST['pf'],
        'esi' => $_POST['esi'],
        'salary_total' => $_POST['salary_total'],

        'basic_salary_cmpl' => $_POST['basic_salary_cmpl'],
        'house_rent_cmpl' => $_POST['house_rent_cmpl'],
        'pf_cmpl' => $_POST['pf_cmpl'],
        'esi_cmpl' => $_POST['esi_cmpl'],
        'salary_total_cmpl' => $_POST['salary_total_cmpl'],

        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'user_group' => $_POST['user_group'],
        'task_remainder_level' => $_POST['task_remainder_level'],

        'is_cg' => $_POST['cost_generator'],
        'cg_name' => $_POST['cg_name'],

        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );
    
    if ($_POST['insType'] == 'edit') {
        Update('employee_detail', $data, ' WHERE id = ' . intval($_POST['updId']));
        timeline_history('Update', 'employee_detail', intval($_POST['updId']), 'Employee Detail Updated. Ref: ' . $employee_name);
        $_SESSION['msg'] = "updated";
        $ins_idd = intval($_POST['updId']);
    } else {
        $qry = Insert('employee_detail', $data);
        $ins_idd = mysqli_insert_id($mysqli);
        $_SESSION['msg'] = "saved";
        timeline_history('Insert', 'employee_detail', $ins_idd, 'Employee Created. Ref: ' . $employee_name);
    }
    
    function uploadFile($fileInputName, $ins_idd, $prefix) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == UPLOAD_ERR_OK) {
            if (!is_dir("uploads/employeeDet/" . $ins_idd . "/")) {
                mkdir("uploads/employeeDet/" . $ins_idd . "/", 0755, true);
            }

            $file = $_FILES[$fileInputName];
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = $prefix . '_' . rand(10000, 999999) . '.' . $extension;
            $uploadfile = 'uploads/employeeDet/' . $ins_idd . '/' . $newName;

            if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
                return $uploadfile;
            }
        }
        return null;
    }
    
    if ($_POST['insType'] == 'move') {
        $sqlly = mysqli_fetch_array(mysqli_query($mysqli, "SELECT aadhar_card, pan_card, license, employee_photo FROM employee_detail_temp WHERE id = " . intval($_POST['updId'])));
    } else {
        $sqlly = mysqli_fetch_array(mysqli_query($mysqli, "SELECT aadhar_card, pan_card, license, employee_photo FROM employee_detail WHERE id = " . $ins_idd));
    }
    
    $aadhar = uploadFile('aadhar_card', $ins_idd, 'aadhar') ?: $sqlly['aadhar_card'];
    $pan = uploadFile('pan_card', $ins_idd, 'pan') ?: $sqlly['pan_card'];
    $license = uploadFile('license', $ins_idd, 'license') ?: $sqlly['license'];
    $other_ = uploadFile('other_docs', $ins_idd, 'other') ?: null;
    $empl = uploadFile('employee_photo', $ins_idd, 'employee') ?: $sqlly['employee_photo'];
    
    $narr = array(
        'aadhar_card' => $aadhar,
        'pan_card' => $pan,
        'license' => $license,
        'other_docs' => $other_,
        'employee_photo' => $empl,
    );

    Update('employee_detail', $narr, ' WHERE id = ' . $ins_idd);
    header("Location:employee.php");
    exit;
}


if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$comp = 'Edit Employee';
	$sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM company WHERE id=" . $id));
} else {
	$comp = 'Add Employee';
	$id = '';
}

?>
<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>BENSO GARMENTING - <?= $comp; ?></title>

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
						<a class="btn btn-outline-primary" href="employee.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Employee List</a>
					</div>
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4"><?= $comp; ?></h4>
							<p class="mb-30 text-danger">(*) Fields are Mandatory</p>
						</div>
					</div>
                    
                    <form method="post" id="employeeForm" autocomplete="off" enctype="multipart/form-data">
                    <!-- <form method="post" id="employeeForm" autocomplete="off" onsubmit="return validation()" enctype="multipart/form-data"> -->
                        <input type="hidden" name="insType" id="insType" value="add">
                        <input type="hidden" name="employee_id" id="employee_id" value="<?= $_GET['id'] ? $_GET['id'] : ''; ?>">
                        <div class="modal-body">
                            <div class="tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active text-blue" data-toggle="tab" href="#basicInfo" role="tab"
                                            aria-selected="true">Basic Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-blue" data-toggle="tab" href="#addressInfo" role="tab"
                                            aria-selected="false">Address Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-blue" data-toggle="tab" href="#proofInfo" role="tab"
                                            aria-selected="false">Proof Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-blue" data-toggle="tab" href="#bankInfo" role="tab"
                                            aria-selected="false">Bank Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-blue" data-toggle="tab" href="#salaryInfo" role="tab"
                                            aria-selected="false">Salary Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-blue" data-toggle="tab" href="#loginInfo" role="tab"
                                            aria-selected="false">Benso App Login Info</a>
                                    </li>

                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="basicInfo" role="tabpanel">
                                        <div class="pd-20">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Employee Type <span
                                                            class="text-danger">*</span></label>
                                                    <div class="form-group">
                                                        <select class="form-control custom-select2" name="type" id="type"
                                                            style="width:100%">
                                                            <option value="user">Staff</option>
                                                            <option value="employee">Worker</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label fieldrequired">Employee Name</label>
                                                    <div class="form-group">
                                                        <input class="form-control d-cursor" type="text" name="employee_name" required id="employee_name" placeholder="Employee Name">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label fieldrequired">Employee Photo</label>
                                                    <div class="form-group row image_head_tag">
                                                        <div class="col-md-6">
                                                            <input class="form-control imagefield" accept="image/*" type="file" name="employee_photo" id="employee_photo" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small class="imagename" data-width="50">Accept Images Only</small>
                                                        </div>
                                                        <input class="form-control" type="hidden" name="employee_photo_old" id="employee_photo_old">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label fieldrequired">Employee Code</label>
                                                    <div class="form-group">
                                                        <input class="form-control valid_employee_code" type="text" required name="employee_code" id="employee_code" placeholder="Employee Code">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label fieldrequired">DOB </label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="date" name="dob" id="dob" required placeholder="Date Of Birth">
                                                    </div>
                                                </div>
                                                <!--date-picker-->
                                                <div class="col-md-3">
                                                    <label class="col-form-label fieldrequired">Age</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="age" id="age" placeholder="Age Will be calculated Automatically" readonly required style="background-color:#fff">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label fieldrequired">Gender</label>
                                                    <div class="form-group">
                                                        <input style="min-width:20px !important" type="radio" name="gender" id="Male" value="Male" checked> <label for="Male">Male</label>
                                                        <input style="min-width:20px !important" type="radio" name="gender" id="Female" value="Female"> <label for="Female">Female</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Email</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="email" name="email" id="email" placeholder="Email">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label fieldrequired">Mobile Number</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="mobile" id="mobile" required placeholder="Mobile Number">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label fieldrequired">Department</label>
                                                    <div class="form-group">
                                                        <select class="form-control custom-select2" name="department" required id="department" style="width:100%">
                                                            <?= select_dropdown('department', array('id', 'department_name'), 'id ASC', '', '', ''); ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label fieldrequired">Designation</label>
                                                    <div class="form-group">
                                                        <select class="form-control custom-select2" name="designation" required id="designation" style="width:100%">
                                                            <?= select_dropdown('mas_designation', array('id', 'desig_name'), 'id ASC', '', '', ''); ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label fieldrequired">Working Place</label>
                                                    <div class="form-group">
                                                        <select class="form-control custom-select2" name="company" id="company" required style="width:100%">
                                                            <?= select_dropdown('company', array('id', 'company_name'), 'id ASC', '', '', ''); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="addressInfo" role="tabpanel">
                                        <div class="pd-20">
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <h4 style="text-decoration:underline;">Communication Address</h4>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Address 1</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="address1_com"
                                                            id="address1_com" placeholder="Address 1">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Address 2</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="address2_com"
                                                            id="address2_com" placeholder="Address 2">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Area</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="area_com" id="area_com"
                                                            placeholder="Area">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Pincode</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="pincode_com"
                                                            id="pincode_com" placeholder="Pincode">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="">Country</label>
                                                    <div class="form-group">
                                                        <select name="country_com" id="country_com"
                                                            class="custom-select2 form-control" onchange="getState('_com')"
                                                            style="width:100%">
                                                            <?= select_dropdown('master_country', array('auto_number', 'country'), 'country ASC', $sql['country'] ? $sql['country'] : 101, '', ''); ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">State</label>
                                                    <div class="form-group">
                                                        <select class="custom-select2 form-control" name="state_com"
                                                            id="state_com" onchange="getCity('_com')" style="width:100%">
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

                                                <div class="col-md-3">
                                                    <label class="col-form-label">City</label>
                                                    <div class="form-group">
                                                        <select class="custom-select2 form-control" name="city_com"
                                                            id="city_com" style="width:100%">
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

                                                <div class="col-md-12">
                                                    <hr>
                                                </div>

                                                <div class="col-md-12">
                                                    <h4 style="text-decoration:underline;">Permanaent Address</h4>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Address 1</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="address1_per"
                                                            id="address1_per" placeholder="Address 1">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Address 2</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="address2_per"
                                                            id="address2_per" placeholder="Address 2">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Area</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="area_per" id="area_per"
                                                            placeholder="Area">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Pincode</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="pincode_per"
                                                            id="pincode_per" placeholder="Pincode">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="">Country</label>
                                                    <div class="form-group">
                                                        <select name="country_per" id="country_per"
                                                            class="custom-select2 form-control" onchange="getState('_per')"
                                                            style="width:100%">
                                                            <?= select_dropdown('master_country', array('auto_number', 'country'), 'country ASC', $sql['country'] ? $sql['country'] : 101, '', ''); ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">State</label>
                                                    <div class="form-group">
                                                        <select class="custom-select2 form-control" name="state_per"
                                                            id="state_per" onchange="getCity('_per')" style="width:100%">
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
                                                        <select class="custom-select2 form-control" name="city_per"
                                                            id="city_per" style="width:100%">
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
                                    </div>

                                    <div class="tab-pane fade" id="proofInfo" role="tabpanel">
                                        <div class="pd-20">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Aadhar Card</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="file" name="aadhar_card" id="aadhar_card">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Pan Card</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="file" name="pan_card" id="pan_card">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">License</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="file" name="license" id="license">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Other Documents</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="file" name="other_docs" id="other_docs">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="bankInfo" role="tabpanel">
                                        <div class="pd-20">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Account Holder Name</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="acc_holder_name" id="acc_holder_name" placeholder="Account Holder Name">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Account Number</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="number" name="acc_num" id="acc_num" placeholder="Account Number">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">IFSC Code</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="ifsc" id="ifsc" placeholder="IFSC Code">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Bank Name</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="bank_name" id="bank_name" placeholder="Bank Name">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Branch</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="bank_branch" id="bank_branch" placeholder="Branch">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="salaryInfo" role="tabpanel">
                                        <div class="pd-20">
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <h4 style="text-decoration:underline;">Actual Salary Info</h4>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Basic Salary</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="number" name="basic_salary" id="basic_salary" placeholder="Basic Salary">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">House Rent Allowance</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="number" name="house_rent" id="house_rent" placeholder="House Rent Allowance">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">PF</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="number" name="pf" id="pf" placeholder="PF">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">ESI</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="number" name="esi" id="esi" placeholder="ESI">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="col-form-label">Total Salary</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="number" name="salary_total" id="salary_total" placeholder="Total Salary">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <h4 style="text-decoration:underline;">Compliance Salary Info</h4>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Basic Salary</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="number" name="basic_salary_cmpl" id="basic_salary_cmpl" placeholder="Basic Salary">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">House Rent Allowance</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="number" name="house_rent_cmpl" id="house_rent_cmpl" placeholder="House Rent Allowance">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">PF</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="number" name="pf_cmpl" id="pf_cmpl" placeholder="PF">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">ESI</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="number" name="esi_cmpl" id="esi_cmpl" placeholder="ESI">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="col-form-label">Total Salary</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="number" name="salary_total_cmpl" id="salary_total_cmpl" placeholder="Total Salary">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="loginInfo" role="tabpanel">
                                        <div class="pd-20">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label class="col-form-label">User Name</label> <label class="uname_found_msg"></label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="username" id="username" placeholder="User Name">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Password</label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="password" id="password" placeholder="Password">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">App Permission</label>
                                                    <div class="form-group">
                                                        <select class="form-control custom-select2" name="user_group" id="user_group" style="width:100%">
                                                            <?= select_dropdown('user_group', array('id', 'group_name'), 'id ASC', 3, '', '`'); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <label class="col-form-label">Remainder Category</label>
                                                    <div class="form-group">
                                                        <select class="form-control custom-select2" name="task_remainder_level"
                                                            id="task_remainder_level" style="width:100%">
                                                            <option value="A">Follow Ups</option>
                                                            <option value="B">Supervisor</option>
                                                            <option value="C">Manager</option>
                                                            <option value="D">Management</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="col-form-label">Is Cost Generator </label>
                                                    <div class="form-group">
                                                        <input style="min-width:20px !important" type="radio" name="cost_generator" id="cg_yes" value="Yes" onclick="show_cgName('yes')"> <label for="cg_yes">Yes</label>
                                                        <input style="min-width:20px !important" type="radio" name="cost_generator" id="cg_no" value="No" checked onclick="show_cgName('no')"> <label for="cg_no">No</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-9 cg_nameDiv d-none">
                                                    <label class="col-form-label">Cost Generating Name </label>
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="cg_name" id="cg_name" placeholder="Cost Generating Name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                            <div class="spinner-border m-5 d-none spinCls" role="status">
                                <span class="visually-hidden"></span>
                            </div>
                            <button type="button" onclick="validation()" class="btn btn-outline-primary"><i class="fa-save fa"></i> Save Employee</button>
                            <!-- <button type="button" onclick="save_employee('add')" class="btn btn-outline-primary scbtn"><i class="fa-save fa"></i> Save Employee</button> -->
                        </div>
                    </form>
				</div>
				<!-- Default Basic Forms End -->
			</div>
			<?php include('modals.php'); include('includes/footer.php'); ?>
		</div>
	</div>
	<?php include('includes/end_scripts.php'); ?>
</body>

<script>
    function validation() {

        // var uname = $("#username").val();
        var data = {
            uname : $("#username").val(),
            emp_id : $("#emp_id").val(),
            employee_code : $("#employee_code").val(),
            mobile : $("#mobile").val(),
        };
        var a = required_validation('employeeForm');

        if(a==0) {
            $.post('ajax_search2.php?validate_username', data, function(msg){

                var j = $.parseJSON(msg);

                if(j.duplicate >0) {
                    message_noload('info', ''+ j.fields + ' - Already Found!', 1500);
                    return false;   
                } else if(j.duplicate == 0) {
                    $("#employeeForm").submit();
                }
            });
        }
        // return false;
    }
</script>

</html>