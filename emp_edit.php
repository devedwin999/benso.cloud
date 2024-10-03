<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if($_GET['type']=='employee_edit') {
$sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM employee_detail WHERE id= ". $_GET['id']));
?>

    <div class="tab">
    	<ul class="nav nav-tabs" role="tablist">
    		<li class="nav-item">
    			<a class="nav-link active text-blue" data-toggle="tab" href="#basicInfo" role="tab" aria-selected="true">Basic Info</a>
    		</li>
    		<li class="nav-item">
    			<a class="nav-link text-blue" data-toggle="tab" href="#addressInfo" role="tab" aria-selected="false">Address Info</a>
    		</li>
    		<li class="nav-item">
    			<a class="nav-link text-blue" data-toggle="tab" href="#proofInfo" role="tab" aria-selected="false">Proof Info</a>
    		</li>
    		<li class="nav-item">
    			<a class="nav-link text-blue" data-toggle="tab" href="#bankInfo" role="tab" aria-selected="false">Bank Info</a>
    		</li>
    		<li class="nav-item">
    			<a class="nav-link text-blue" data-toggle="tab" href="#salaryInfo" role="tab" aria-selected="false">Salary Info</a>
    		</li>
    		<li class="nav-item">
    			<a class="nav-link text-blue" data-toggle="tab" href="#loginInfo" role="tab" aria-selected="false">Benso App Login Info</a>
    		</li>
    		
    	</ul>
    	<div class="tab-content">
    		<div class="tab-pane fade show active" id="basicInfo" role="tabpanel">
    			<div class="pd-20">
    			    <div class="row">
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Employee Type <span class="text-danger">*</span></label>
    						<div class="form-group">
    						    <select class="form-control select2 custom-select2" name="type" id="type" style="width:100%">
    						        <option value="user">Staff</option>
    						        <option value="employee">Worker</option>
    						    </select>
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="employee_name" id="employee_name" placeholder="Employee Name" value="<?= $sql['employee_name'] ? $sql['employee_name'] : ''; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Employee Photo <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input class="form-control" type="file" name="employee_photo" id="employee_photo" placeholder="Employee Photo" value="<?= $sql['employee_photo'] ? $sql['employee_photo'] : ''; ?>">
    							<input class="form-control" type="hidden" name="employee_photo_old" id="employee_photo_old" placeholder="Employee Photo" value="<?= $sql['employee_photo'] ? $sql['employee_photo'] : ''; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Employee Code <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input class="form-control d-cursor" type="text" name="employee_code" id="employee_code" placeholder="Employee Code" value="<?= $sql['employee_code'] ? $sql['employee_code'] : ''; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">DOB <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input class="form-control date-picker" type="date" name="dob" id="dob" placeholder="Date Of Birth" value="<?= $sql['dob'] ? $sql['dob'] : ''; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Age <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="age" id="age" placeholder="Age Will be calculated Automatically." value="<?= $sql['age'] ? $sql['age'] : ''; ?>" readonly style="background-color:#fff">
    						</div>
    					</div>
                        					
    					<div class="col-md-6">
    						<label class="col-form-label">Gender <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input style="min-width:20px !important" type="radio" name="gender" id="Male" value="Male" <?= ($sql['gender']=='Male') ? 'checked' : ''; ?>> <label for="Male">Male</label>
    							<input style="min-width:20px !important" type="radio" name="gender" id="Female" value="Female" <?= ($sql['gender']=='Female') ? 'checked' : ''; ?>> <label for="Female">Female</label>
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Email</label>
    						<div class="form-group">
    							<input class="form-control" type="email" name="email" id="email" placeholder="Email" value="<?= $sql['email'] ? $sql['email'] : ''; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Mobile Number <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="mobile" id="mobile" placeholder="Mobile Number" value="<?= $sql['mobile'] ? $sql['mobile'] : ''; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Department <span class="text-danger">*</span></label>
    						<div class="form-group">
    						    <select class="form-control select2 custom-select2" name="department" id="department" style="width:100%">
                                    <?= select_dropdown('department', array('id', 'department_name'), 'id ASC', $sql['department'], '', ''); ?>
    						    </select>
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Designation <span class="text-danger">*</span></label>
    						<div class="form-group">
    						    <select class="form-control select2 custom-select2" name="designation" id="designation" style="width:100%">
                                    <?= select_dropdown('mas_designation', array('id', 'desig_name'), 'id ASC', $sql['designation'], '', ''); ?>
    						    </select>
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Working Place <span class="text-danger">*</span></label>
    						<div class="form-group">
    						    <select class="form-control select2 custom-select2" name="company" id="company" style="width:100%">
                                    <?= select_dropdown('company', array('id', 'company_name'), 'id ASC', $sql['company'], '', ''); ?>
    						    </select>
    						</div>
    					</div>
    
    			    </div>
    			</div>
    		</div>
    		
    		<div class="tab-pane fade" id="addressInfo" role="tabpanel">
    			<div class="pd-20">
    			    <div class="row">
    			        
    			        <div class="col-md-12"><h4 style="text-decoration:underline;">Communication Address</h4></div>
    					
    					<div class="col-md-6">
                            <label class="col-form-label">Address 1</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="address1_com" id="address1_com" placeholder="Address 1" value="<?= $sql['address1_com']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">Address 2</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="address2_com" id="address2_com" placeholder="Address 2" value="<?= $sql['address2_com']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">Area</label>
                            <div class="form-group">
                                <input class="form-control" type="text" name="area_com" id="area_com" placeholder="Area" value="<?= $sql['area_com']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">Pincode</label>
                            <div class="form-group">
                                <input class="form-control" type="text" name="pincode_com" id="pincode_com" placeholder="Pincode" value="<?= $sql['pincode_com']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label for="">Country</label>
                            <div class="form-group">
                                <select name="country_com" id="country_com" class="custom-select2 form-control" onchange="getState('_com')" style="width:100%">
                                    <?= select_dropdown('master_country', array('auto_number', 'country'), 'country ASC', $sql['country_com'] ? $sql['country_com'] : 101, '', ''); ?>
                                </select>
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">State</label>
                            <div class="form-group">
                                <select class="custom-select2 form-control" name="state_com" id="state_com" onchange="getCity('_com')" style="width:100%">
                                    <option value="">Select State</option>
                                    <?php
                                    if (isset($_GET['id']) && !empty($sql['country_com'])) {
                                        $where = "country_id='" . $sql['country_com'] . "'";
                                    } else {
                                        $where = "country_id='101'";
                                    }
    
                                    $qryd = mysqli_query($mysqli, "SELECT * FROM states WHERE $where ORDER BY state_name ASC ");
                                    while ($stt = mysqli_fetch_array($qryd)) {
                                        if (isset($_GET['id']) && !empty($sql['state_com'])) {
                                            if ($stt['id'] == $sql['state_com']) {
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
                                    if (isset($_GET['id']) && !empty($sql['state_com'])) {
                                        $qryd1 = mysqli_query($mysqli, "SELECT * FROM cities WHERE state_id = '" . $sql['state_com'] . "' ORDER BY cities_name ASC");
                                        
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
                                <input type="text" class="form-control" name="address1_per" id="address1_per" placeholder="Address 1" value="<?= $sql['address1_per']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">Address 2</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="address2_per" id="address2_per" placeholder="Address 2" value="<?= $sql['address2_per']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">Area</label>
                            <div class="form-group">
                                <input class="form-control" type="text" name="area_per" id="area_per" placeholder="Area" value="<?= $sql['area_per']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">Pincode</label>
                            <div class="form-group">
                                <input class="form-control" type="text" name="pincode_per" id="pincode_per" placeholder="Pincode" value="<?= $sql['pincode_per']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label for="">Country</label>
                            <div class="form-group">
                                <select name="country_per" id="country_per" class="custom-select2 form-control" onchange="getState('_per')" style="width:100%">
                                    <?= select_dropdown('master_country', array('auto_number', 'country'), 'country ASC', $sql['country_per'] ? $sql['country_per'] : 101, '', ''); ?>
                                </select>
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">State</label>
                            <div class="form-group">
                                <select class="custom-select2 form-control" name="state_per" id="state_per" onchange="getCity('_per')" style="width:100%">
                                    <option value="">Select State</option>
                                    <?php
                                    if (isset($_GET['id']) && !empty($sql['country_per'])) {
                                        $where = "country_id='" . $sql['country_per'] . "'";
                                    } else {
                                        $where = "country_id='101'";
                                    }
    
                                    $qryd = mysqli_query($mysqli, "SELECT * FROM states WHERE $where ORDER BY state_name ASC ");
                                    while ($stt = mysqli_fetch_array($qryd)) {
                                        if (isset($_GET['id']) && !empty($sql['state_per'])) {
                                            if ($stt['id'] == $sql['state_per']) {
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
                                    if (isset($_GET['id']) && !empty($sql['state_per'])) {
                                        $qryd1 = mysqli_query($mysqli, "SELECT * FROM cities WHERE state_id = '" . $sql['state_per'] . "' ORDER BY cities_name ASC");
                                        
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
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Aadhar Card</label>
    						<div class="form-group">
    							<input class="form-control" type="file" name="aadhar_card" id="aadhar_card" value="<?= $sql['aadhar_card']; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Pan Card</label>
    						<div class="form-group">
    							<input class="form-control" type="file" name="pan_card" id="pan_card" value="<?= $sql['pan_card']; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">License</label>
    						<div class="form-group">
    							<input class="form-control" type="file" name="license" id="license" value="<?= $sql['license']; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Other Documents</label>
    						<div class="form-group">
    							<input class="form-control" type="file" name="other_docs" id="other_docs" value="<?= $sql['other_docs']; ?>">
    						</div>
    					</div>
    
    			    </div>
    			</div>
    		</div>
    		
    		<div class="tab-pane fade" id="bankInfo" role="tabpanel">
    			<div class="pd-20">
    			    <div class="row">
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Account Holder Name</label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="acc_holder_name" id="acc_holder_name" value="<?= $sql['acc_holder_name']; ?>" placeholder="Account Holder Name">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Account Number</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="acc_num" id="acc_num" value="<?= $sql['acc_num']; ?>" placeholder="Account Number">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">IFSC Code</label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="ifsc" id="ifsc" value="<?= $sql['ifsc']; ?>" placeholder="IFSC Code">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Bank Name</label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="bank_name" id="bank_name" value="<?= $sql['bank_name']; ?>" placeholder="Bank Name">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Branch</label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="bank_branch" id="bank_branch" value="<?= $sql['bank_branch']; ?>" placeholder="Branch">
    						</div>
    					</div>
    
    			    </div>
    			</div>
    		</div>
    		
    		<div class="tab-pane fade" id="salaryInfo" role="tabpanel">
    			<div class="pd-20">
    			    <div class="row">
    			        
    			        <div class="col-md-12"><h4 style="text-decoration:underline;">Actual Salary Info</h4></div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Basic Salary</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="basic_salary" id="basic_salary" value="<?= $sql['basic_salary']; ?>" placeholder="Basic Salary">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">House Rent Allowance</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="house_rent" id="house_rent" value="<?= $sql['house_rent']; ?>" placeholder="House Rent Allowance">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">PF</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="pf" id="pf" value="<?= $sql['pf']; ?>" placeholder="PF">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">ESI</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="esi" id="esi" value="<?= $sql['esi']; ?>" placeholder="ESI">
    						</div>
    					</div>
    					
    					<div class="col-md-12">
    						<label class="col-form-label">Total Salary</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="salary_total" id="salary_total" value="<?= $sql['salary_total']; ?>" placeholder="Total Salary">
    						</div>
    					</div>
    			        
    			        <div class="col-md-12"><h4 style="text-decoration:underline;">Compliance Salary Info</h4></div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Basic Salary</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="basic_salary_cmpl" id="basic_salary_cmpl" value="<?= $sql['basic_salary_cmpl']; ?>" placeholder="Basic Salary">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">House Rent Allowance</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="house_rent_cmpl" id="house_rent_cmpl" value="<?= $sql['house_rent_cmpl']; ?>" placeholder="House Rent Allowance">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">PF</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="pf_cmpl" id="pf_cmpl" value="<?= $sql['pf_cmpl']; ?>" placeholder="PF">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">ESI</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="esi_cmpl" id="esi_cmpl" value="<?= $sql['esi_cmpl']; ?>" placeholder="ESI">
    						</div>
    					</div>
    					
    					<div class="col-md-12">
    						<label class="col-form-label">Total Salary</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="salary_total_cmpl" id="salary_total_cmpl" value="<?= $sql['salary_total_cmpl']; ?>" placeholder="Total Salary">
    						</div>
    					</div>
    					
    			    </div>
    			</div>
    		</div>
    		
    		<div class="tab-pane fade" id="loginInfo" role="tabpanel">
    			<div class="pd-20">
    			    
    			    <?php if(UP_EMPLOYEE_LOGIN_INFO == 1) { ?>
        			    <div class="row">
        					
        					<div class="col-md-6">
        						<label class="col-form-label">User Name</label>
        						<div class="form-group">
        							<input class="form-control" type="text" name="username" id="username" value="<?= $sql['username']; ?>" placeholder="User Name">
        						</div>
        					</div>
        					
        					<div class="col-md-6">
        						<label class="col-form-label">Password</label>
        						<div class="form-group">
        							<input class="form-control" type="text" name="password" id="password" value="<?= $sql['password']; ?>" placeholder="Password">
        						</div>
        					</div>
        					
        					<div class="col-md-6">
        						<label class="col-form-label">App Permission</label>
        						<div class="form-group">
        						    <select class="form-control custom-select2" name="user_group" id="user_group" style="width:100%">
                                        <?= select_dropdown('user_group', array('id', 'group_name'), 'id ASC', $sql['user_group'], '', ''); ?>
        						    </select>
        						</div>
        					</div>
        					
        					<div class="col-md-6">
        						<label class="col-form-label">Remainder Category</label>
        						<div class="form-group">
        						    <select class="form-control custom-select2" name="task_remainder_level" id="task_remainder_level" style="width:100%">
        						        <option value="A">Follow Ups</option>
        						        <option value="B" <?= ($sql['task_remainder_level'] == 'B') ? 'selected' : ''; ?>>Supervisor</option>
        						        <option value="C" <?= ($sql['task_remainder_level'] == 'C') ? 'selected' : ''; ?>>Manager</option>
        						        <option value="D" <?= ($sql['task_remainder_level'] == 'D') ? 'selected' : ''; ?>>Management</option>
        						    </select>
        						</div>
        					</div>
                        					
        					<div class="col-md-3">
        						<label class="col-form-label">Is Cost Generator </label>
        						<div class="form-group">
        							<input style="min-width:20px !important" type="radio" name="cost_generator" id="cg_yes" value="Yes" <?= ($sql['is_cg'] == 'Yes') ? 'checked' : ''; ?> onclick="show_cgName('yes')"> <label for="cg_yes">Yes</label>
        							<input style="min-width:20px !important" type="radio" name="cost_generator" id="cg_no" value="No" <?= ($sql['is_cg'] == 'No') ? 'checked' : ''; ?> onclick="show_cgName('no')"> <label for="cg_no">No</label>
        						</div>
        					</div>
        					
        					<div class="col-md-9 cg_nameDiv <?= ($sql['is_cg'] == 'Yes') ? '' : 'd-none'; ?>">
        						<label class="col-form-label">Cost Generating Name </label>
        						<div class="form-group">
    								<input class="form-control" type="text" name="cg_name" id="cg_name" value="<?= $sql['cg_name']; ?>" placeholder="Cost Generating Name">
        						</div>
        					</div>
        					
        			    </div>
    			    <?php } else { ?>
    			    
    			    <input type="hidden" name="username" id="username" value="<?= $sql['username']; ?>">
    			    <input type="hidden" name="password" id="password" value="<?= $sql['password']; ?>">
    			    
    			    <input type="hidden" name="user_group" id="user_group" value="<?= $sql['user_group']; ?>">
    			    <input type="hidden" name="task_remainder_level" id="task_remainder_level" value="<?= $sql['task_remainder_level']; ?>">
    			    <input type="hidden" name="cost_generator" id="cost_generator" value="<?= $sql['is_cg']; ?>">
    			    <input type="hidden" name="cg_name" id="cg_name" value="<?= $sql['cg_name']; ?>">
    			    
    			    <?php action_denied(); exit; } ?>
    			    
    			</div>
    		</div>
    		
    	</div>
    </div>
<?php }

if($_GET['type']=='reg_edit') {
$sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM employee_detail_temp WHERE id= ". $_GET['id']));
?>

    <div class="pd-20 col-md-12">
    	<div class="profile-photo">
    		<!--<a href="modal" data-toggle="modal" data-target="#modal" class="edit-avatar"><i class="fa fa-pencil"></i></a>-->
    		<img src="<?= $sql['employee_photo']; ?>" alt="" class="avatar-photo">
    		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    			<div class="modal-dialog modal-dialog-centered" role="document">
    				<div class="modal-content">
    					<div class="modal-body pd-5">
    						<div class="img-container">
    							<img id="image" src="<?= $sql['employee_photo']; ?>" alt="Picture">
    						</div>
    					</div>
    					<div class="modal-footer">
    						<input type="submit" value="Update" class="btn btn-primary">
    						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    	<br>
    	<h5 class="text-center h5 mb-0"><?= $sql['employee_name']; ?></h5>
    	
    </div>

    <div class="tab">
    	<ul class="nav nav-tabs" role="tablist">
    		<li class="nav-item">
    			<a class="nav-link active text-blue" data-toggle="tab" href="#basicInfo" role="tab" aria-selected="true">Basic Info</a>
    		</li>
    		<li class="nav-item">
    			<a class="nav-link text-blue" data-toggle="tab" href="#addressInfo" role="tab" aria-selected="false">Address Info</a>
    		</li>
    		<li class="nav-item">
    			<a class="nav-link text-blue" data-toggle="tab" href="#proofInfo" role="tab" aria-selected="false">Proof Info</a>
    		</li>
    		<li class="nav-item">
    			<a class="nav-link text-blue" data-toggle="tab" href="#bankInfo" role="tab" aria-selected="false">Bank Info</a>
    		</li>
    		<li class="nav-item">
    			<a class="nav-link text-blue" data-toggle="tab" href="#salaryInfo" role="tab" aria-selected="false">Salary Info</a>
    		</li>
    		<li class="nav-item">
    			<a class="nav-link text-blue" data-toggle="tab" href="#loginInfo" role="tab" aria-selected="false">Benso App Login Info</a>
    		</li>
    		
    	</ul>
    	<div class="tab-content">
    		<div class="tab-pane fade show active" id="basicInfo" role="tabpanel">
    			<div class="pd-20">
    			    <div class="row">
    					
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
    							<input class="form-control" type="text" name="employee_name" id="employee_name" placeholder="Employee Name" value="<?= $sql['employee_name'] ? $sql['employee_name'] : ''; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Employee Photo <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input class="form-control" type="file" name="employee_photo" id="employee_photo" placeholder="Employee Photo" value="<?= $sql['employee_photo'] ? $sql['employee_photo'] : ''; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Employee Code</label>
    						<div class="form-group">
    							<input class="form-control d-cursor" type="text" name="employee_code" id="employee_code" placeholder="Employee Code" value="<?= $sql['employee_code'] ? $sql['employee_code'] : ''; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">DOB <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input class="form-control date-picker" type="date" name="dob" id="dob" placeholder="Date Of Birth" value="<?= $sql['dob'] ? $sql['dob'] : ''; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Age <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="age" id="age" placeholder="Age Will be calculated Automatically." value="<?= $sql['age'] ? $sql['age'] : ''; ?>" readonly style="background-color:#fff">
    						</div>
    					</div>
                        					
    					<div class="col-md-6">
    						<label class="col-form-label">Gender <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input style="min-width:20px !important" type="radio" name="gender" id="Male" value="Male" <?= ($sql['gender']=='Male') ? 'checked' : ''; ?>> <label for="Male">Male</label>
    							<input style="min-width:20px !important" type="radio" name="gender" id="Female" value="Female" <?= ($sql['gender']=='Female') ? 'checked' : ''; ?>> <label for="Female">Female</label>
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Email</label>
    						<div class="form-group">
    							<input class="form-control" type="email" name="email" id="email" placeholder="Email" value="<?= $sql['email'] ? $sql['email'] : ''; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Mobile Number <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="mobile" id="mobile" placeholder="Mobile Number" value="<?= $sql['mobile'] ? $sql['mobile'] : ''; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Department <span class="text-danger">*</span></label>
    						<div class="form-group">
    						    <select class="form-control custom-select2" name="department" id="department" style="width:100%">
                                    <?= select_dropdown('department', array('id', 'department_name'), 'id ASC', $sql['department'], '', ''); ?>
    						    </select>
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Designation <span class="text-danger">*</span></label>
    						<div class="form-group">
    						    <select class="form-control custom-select2" name="designation" id="designation" style="width:100%">
                                    <?= select_dropdown('mas_designation', array('id', 'desig_name'), 'id ASC', $sql['designation'], '', ''); ?>
    						    </select>
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Working Place <span class="text-danger">*</span></label>
    						<div class="form-group">
    						    <select class="form-control custom-select2" name="company" id="company" style="width:100%">
                                    <?= select_dropdown('company', array('id', 'company_name'), 'id ASC', $sql['company'], '', ''); ?>
    						    </select>
    						</div>
    					</div>
    
    			    </div>
    			</div>
    		</div>
    		
    		<div class="tab-pane fade" id="addressInfo" role="tabpanel">
    			<div class="pd-20">
    			    <div class="row">
    			        
    			        <div class="col-md-12"><h4 style="text-decoration:underline;">Communication Address</h4></div>
    					
    					<div class="col-md-6">
                            <label class="col-form-label">Address 1</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="address1_com" id="address1_com" placeholder="Address 1" value="<?= $sql['address1_com']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">Address 2</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="address2_com" id="address2_com" placeholder="Address 2" value="<?= $sql['address2_com']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">Area</label>
                            <div class="form-group">
                                <input class="form-control" type="text" name="area_com" id="area_com" placeholder="Area" value="<?= $sql['area_com']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">Pincode</label>
                            <div class="form-group">
                                <input class="form-control" type="text" name="pincode_com" id="pincode_com" placeholder="Pincode" value="<?= $sql['pincode_com']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label for="">Country</label>
                            <div class="form-group">
                                <select name="country_com" id="country_com" class="custom-select2 form-control" onchange="getState('_com')" style="width:100%">
                                    <?= select_dropdown('master_country', array('auto_number', 'country'), 'country ASC', $sql['country_com'] ? $sql['country_com'] : 101, '', ''); ?>
                                </select>
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">State</label>
                            <div class="form-group">
                                <select class="custom-select2 form-control" name="state_com" id="state_com" onchange="getCity('_com')" style="width:100%">
                                    <option value="">Select State</option>
                                    <?php
                                    if (isset($_GET['id']) && !empty($sql['country_com'])) {
                                        $where = "country_id='" . $sql['country_com'] . "'";
                                    } else {
                                        $where = "country_id='101'";
                                    }
    
                                    $qryd = mysqli_query($mysqli, "SELECT * FROM states WHERE $where ORDER BY state_name ASC ");
                                    while ($stt = mysqli_fetch_array($qryd)) {
                                        if (isset($_GET['id']) && !empty($sql['state_com'])) {
                                            if ($stt['id'] == $sql['state_com']) {
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
                                    if (isset($_GET['id']) && !empty($sql['state_com'])) {
                                        $qryd1 = mysqli_query($mysqli, "SELECT * FROM cities WHERE state_id = '" . $sql['state_com'] . "' ORDER BY cities_name ASC");
                                        
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
                                <input type="text" class="form-control" name="address1_per" id="address1_per" placeholder="Address 1" value="<?= $sql['address1_per']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">Address 2</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="address2_per" id="address2_per" placeholder="Address 2" value="<?= $sql['address2_per']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">Area</label>
                            <div class="form-group">
                                <input class="form-control" type="text" name="area_per" id="area_per" placeholder="Area" value="<?= $sql['area_per']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">Pincode</label>
                            <div class="form-group">
                                <input class="form-control" type="text" name="pincode_per" id="pincode_per" placeholder="Pincode" value="<?= $sql['pincode_per']; ?>">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label for="">Country</label>
                            <div class="form-group">
                                <select name="country_per" id="country_per" class="custom-select2 form-control" onchange="getState('_per')" style="width:100%">
                                    <?= select_dropdown('master_country', array('auto_number', 'country'), 'country ASC', $sql['country_per'] ? $sql['country_per'] : 101, '', ''); ?>
                                </select>
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <label class="col-form-label">State</label>
                            <div class="form-group">
                                <select class="custom-select2 form-control" name="state_per" id="state_per" onchange="getCity('_per')" style="width:100%">
                                    <option value="">Select State</option>
                                    <?php
                                    if (isset($_GET['id']) && !empty($sql['country_per'])) {
                                        $where = "country_id='" . $sql['country_per'] . "'";
                                    } else {
                                        $where = "country_id='101'";
                                    }
    
                                    $qryd = mysqli_query($mysqli, "SELECT * FROM states WHERE $where ORDER BY state_name ASC ");
                                    while ($stt = mysqli_fetch_array($qryd)) {
                                        if (isset($_GET['id']) && !empty($sql['state_per'])) {
                                            if ($stt['id'] == $sql['state_per']) {
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
                                    if (isset($_GET['id']) && !empty($sql['state_per'])) {
                                        $qryd1 = mysqli_query($mysqli, "SELECT * FROM cities WHERE state_id = '" . $sql['state_per'] . "' ORDER BY cities_name ASC");
                                        
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
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Aadhar Card <span class="text-danger">*</span></label>
    						<div class="form-group">
    							<input class="form-control" type="file" name="aadhar_card" id="aadhar_card" value="<?= $sql['aadhar_card']; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Pan Card</label>
    						<div class="form-group">
    							<input class="form-control" type="file" name="pan_card" id="pan_card" value="<?= $sql['pan_card']; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">License</label>
    						<div class="form-group">
    							<input class="form-control" type="file" name="license" id="license" value="<?= $sql['license']; ?>">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Other Documents</label>
    						<div class="form-group">
    							<input class="form-control" type="file" name="other_docs" id="other_docs" value="<?= $sql['other_docs']; ?>">
    						</div>
    					</div>
    
    			    </div>
    			</div>
    		</div>
    		
    		<div class="tab-pane fade" id="bankInfo" role="tabpanel">
    			<div class="pd-20">
    			    <div class="row">
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Account Holder Name</label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="acc_holder_name" id="acc_holder_name" value="<?= $sql['acc_holder_name']; ?>" placeholder="Account Holder Name">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Account Number</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="acc_num" id="acc_num" value="<?= $sql['acc_num']; ?>" placeholder="Account Number">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">IFSC Code</label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="ifsc" id="ifsc" value="<?= $sql['ifsc']; ?>" placeholder="IFSC Code">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Bank Name</label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="bank_name" id="bank_name" value="<?= $sql['bank_name']; ?>" placeholder="Bank Name">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Branch</label>
    						<div class="form-group">
    							<input class="form-control" type="text" name="bank_branch" id="bank_branch" value="<?= $sql['bank_branch']; ?>" placeholder="Branch">
    						</div>
    					</div>
    
    			    </div>
    			</div>
    		</div>
    		
    		<div class="tab-pane fade" id="salaryInfo" role="tabpanel">
    			<div class="pd-20">
    			    <div class="row">
    			        
    			        <div class="col-md-12"><h4 style="text-decoration:underline;">Actual Salary Info</h4></div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Basic Salary</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="basic_salary" id="basic_salary" value="<?= $sql['basic_salary']; ?>" placeholder="Basic Salary">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">House Rent Allowance</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="house_rent" id="house_rent" value="<?= $sql['house_rent']; ?>" placeholder="House Rent Allowance">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">PF</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="pf" id="pf" value="<?= $sql['pf']; ?>" placeholder="PF">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">ESI</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="esi" id="esi" value="<?= $sql['esi']; ?>" placeholder="ESI">
    						</div>
    					</div>
    					
    					<div class="col-md-12">
    						<label class="col-form-label">Total Salary</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="salary_total" id="salary_total" value="<?= $sql['salary_total']; ?>" placeholder="Total Salary">
    						</div>
    					</div>
    			        
    			        <div class="col-md-12"><h4 style="text-decoration:underline;">Compliance Salary Info</h4></div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">Basic Salary</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="basic_salary_cmpl" id="basic_salary_cmpl" value="<?= $sql['basic_salary_cmpl']; ?>" placeholder="Basic Salary">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">House Rent Allowance</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="house_rent_cmpl" id="house_rent_cmpl" value="<?= $sql['house_rent_cmpl']; ?>" placeholder="House Rent Allowance">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">PF</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="pf_cmpl" id="pf_cmpl" value="<?= $sql['pf_cmpl']; ?>" placeholder="PF">
    						</div>
    					</div>
    					
    					<div class="col-md-6">
    						<label class="col-form-label">ESI</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="esi_cmpl" id="esi_cmpl" value="<?= $sql['esi_cmpl']; ?>" placeholder="ESI">
    						</div>
    					</div>
    					
    					<div class="col-md-12">
    						<label class="col-form-label">Total Salary</label>
    						<div class="form-group">
    							<input class="form-control" type="number" name="salary_total_cmpl" id="salary_total_cmpl" value="<?= $sql['salary_total_cmpl']; ?>" placeholder="Total Salary">
    						</div>
    					</div>
    					
    			    </div>
    			</div>
    		</div>
    		
    		<div class="tab-pane fade" id="loginInfo" role="tabpanel">
    			<div class="pd-20">
    			    
    			    <?php if(UP_EMPLOYEE_LOGIN_INFO == 1) { ?>
        			    <div class="row">
        					
        					<div class="col-md-6">
        						<label class="col-form-label">User Name</label>
        						<div class="form-group">
        							<input class="form-control" type="text" name="username" id="username" value="<?= $sql['username']; ?>" placeholder="User Name">
        						</div>
        					</div>
        					
        					<div class="col-md-6">
        						<label class="col-form-label">Password</label>
        						<div class="form-group">
        							<input class="form-control" type="text" name="password" id="password" value="<?= $sql['password']; ?>" placeholder="Password">
        						</div>
        					</div>
        					
        					<div class="col-md-6">
        						<label class="col-form-label">App Permission</label>
        						<div class="form-group">
        						    <select class="form-control custom-select2" name="user_group" id="user_group" style="width:100%">
                                        <?= select_dropdown('user_group', array('id', 'group_name'), 'id ASC', $sql['user_group'], '', ''); ?>
        						    </select>
        						</div>
        					</div>
        					
        					<div class="col-md-6">
        						<label class="col-form-label">Remainder Category</label>
        						<div class="form-group">
        						    <select class="form-control custom-select2" name="task_remainder_level" id="task_remainder_level" style="width:100%">
        						        <option value="A">Follow Ups</option>
        						        <option value="B">Supervisor</option>
        						        <option value="C">Manager</option>
        						        <option value="D">Management</option>
        						    </select>
        						</div>
        					</div>
        					
        			    </div>
    			    <?php } else { action_denied(); exit; } ?>
    			</div>
    		</div>
    	</div>
<?php } ?>