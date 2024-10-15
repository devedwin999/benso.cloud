<?php
include("includes/connection.php");
include("includes/function.php");
include("includes/perm.php");

if (!isset($_SESSION['login_id'])) {
	header('Location:index.php');
}

if (isset($_REQUEST['get_city'])) {

    $city = get_stateCity($_REQUEST['get_city']);
    $dd = '<option value="">Select City</option>';
    foreach ($city as $cty) {
        $dd .= '<option value="' . $cty['id'] . '"> ' . $cty['cities_name'] . '</option>';
    }
    echo $dd;
} else if(isset($_REQUEST['get_state'])) {

    $msq = mysqli_query($mysqli, "SELECT * FROM states WHERE country_id=" . $_REQUEST['country']);
    $dd = '<option value="">Select State</option>';
    while ($arry = mysqli_fetch_array($msq)) {
        $dd .= '<option value="' . $arry['id'] . '"> ' . $arry['state_name'] . '</option>';
    }
    echo $dd;
} else if(isset($_REQUEST['getcompanydetails'])) {

    $det = get_company_detail($_REQUEST['getcompanydetails']);
    print '<div class="row">';
    print '<div class="col-md-6">';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Username </label> <div class="col-sm-7">: ' . $det['username'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Company Name </label> <div class="col-sm-7">: ' . $det['company_name'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Address 1 </label> <div class="col-sm-7">: ' . $det['address1'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Area </label> <div class="col-sm-7">: ' . $det['area'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">City </label> <div class="col-sm-7">: ' . $det['city'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Phone 1 </label> <div class="col-sm-7">: ' . $det['phone1'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Website </label> <div class="col-sm-7">: ' . $det['website'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">PAN Number </label> <div class="col-sm-7">: ' . $det['pan_num'] . '</span> </div></div>';
    print '</div>';

    print '<div class="col-md-6">';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Password </label> <div class="col-sm-7">: ' . $det['company_code'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Company Code </label> <div class="col-sm-7">: ' . $det['company_code'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Address 2 </label> <div class="col-sm-7">: ' . $det['address2'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">State </label> <div class="col-sm-7">: ' . $det['state'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Mobile </label> <div class="col-sm-7">: ' . $det['mobile'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Phone 2 </label> <div class="col-sm-7">: ' . $det['phone2'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">GST Number </label> <div class="col-sm-7">: ' . $det['gst_no'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Other License </label> <div class="col-sm-7">: ' . $det['other'] . '</span> </div></div>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getcategory'])) {

    $ass = "SELECT * FROM category WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    $sqli = mysqli_query($mysqli, "SELECT * FROM process ORDER BY process_name ASC");
    while ($pcs = mysqli_fetch_array($sqli)) {

        $c = ($pcs['category'] == $_REQUEST['id']) ? 'selected' : '';
        $data['process'][] = '<option value="' . $pcs['id'] . '" ' . $c . '>' . $pcs['process_name'] . '</option>';
    }

    $sqli1 = mysqli_query($mysqli, "SELECT * FROM sub_process ORDER BY sub_process_name ASC");
    while ($pcs1 = mysqli_fetch_array($sqli1)) {

        if ($pcs1['category'] == $_REQUEST['id']) {
            $c1 = 'selected';
        } else {
            $c1 = '';
        }

        $data['sub_process'][] = '<option value="' . $pcs1['id'] . '" ' . $c1 . '>' . $pcs1['sub_process_name'] . '</option>';
    }

    $data['category_name'][] = $asd['category_name'];
    $data['category_id'][] = $asd['id'];
    $data['old_pic'][] = $asd['image'];

    echo json_encode($data);

} else if(isset($_REQUEST['getbrandedit'])) {

    $ass = "SELECT * FROM brand WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label>Brand Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_brand_name" id="edit_brand_name" placeholder="Brand Name" value="' . $asd['brand_name'] . '" required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Brand Code <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_brand_code" id="edit_brand_code" placeholder="Brand Code" value="' . $asd['brand_code'] . '" required>';
    print '<input type="hidden" name="edit_brand_id" id="edit_brand_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Brand Code <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select name="approvals[]" id="approvals" class="form-control custom-select2" style="width:100%" required multiple>';
    print select_dropdown_multiple('mas_approval', array('id', 'name'), 'id ASC', '', '', '1');
    print '</select>';
    print '<input type="hidden" name="edit_brand_id" id="edit_brand_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>User Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_user_name" id="edit_user_name" placeholder="User Name" value="' . $asd['username'] . '" required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Password <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_password" id="edit_password" placeholder="Password" value="' . $asd['password'] . '" required>';
    print '</div>';
    print '</div>';
    
} else if(isset($_REQUEST['gettaskedit'])) {

    $ass = "SELECT * FROM mas_task WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    // print '<div class="col-md-12">';
    // print '<label>Task Name <span class="text-danger">*</span></label>';
    // print '<div class="form-group">';
    // print '<input type="text" class="form-control d-cursor" name="edit_task_name" id="edit_task_name" placeholder="Task Name" value="' . $asd['task_name'] . '" required>';
    // print '<input type="hidden" name="edit_task_id" id="edit_task_id" value="' . $asd['id'] . '" required>';
    // print '</div>';
    // print '</div>';
    print '<div class="col-md-12">';
    print '<label>Process Type<span class="text-danger"></span></label>';
    print '<div class="form-group">';
    print '<select class="form-control custom-select2" name="edit_task_type" id="edit_task_type" style="width:100%">';
    $arr = array(
        '' => 'Select',
        'production_task' => 'Production Process',
        'fabric_task' => 'Fabric Process',
        'store_task' => 'Store',
        'other_task' => 'Other'
    );
    foreach($arr as $key => $value) {
        $sel = ($asd['task_type'] == $key) ? 'selected' : '';
        print '<option value="'. $key .'" '. $sel .'>'. $value .'</option>';
    }
    print '</select>';
    print '</div>';
    print '</div>';
    print '<div class="col-md-12">';
    print '<label>Process Type<span class="text-danger"></span></label>';
    print '<div class="form-group">';
    if($asd['task_type']=="production_task"){
        print '<select class="custom-select2 form-control" name="ed_process" id="ed_process" style="width:100%;">';
        print select_dropdown_multiple('process', array('id', 'process_name'), 'process_name ASC', $asd['task_process_id'], 'WHERE process_type="Production"', '');
        print '</select>';
    } 
    else if($asd['task_type'] == "fabric_task"){
        print '<select class="custom-select2 form-control" name="fb_process" id="fb_process" style="width:100%;">';
        print select_dropdown_multiple('process', array('id', 'process_name'), 'process_name ASC', $asd['task_process_id'], 'WHERE process_type="Fabric"', '');
        print '</select>';
    }
    else if($asd['task_type'] == "store_task"){
        print '<select class="custom-select2 form-control" name="fb_process" id="fb_process" style="width:100%;">';
        print select_dropdown_multiple('mas_accessories', array('id', 'acc_name'), 'acc_name ASC', $asd['task_process_id'], '', '');
        print '</select>';
    } else if($asd['task_type'] == 'other_task') {
        print '<label>Task Name <span class="text-danger">*</span></label>';
       print '<div class="form-group">';
      print '<input type="text" class="form-control d-cursor" name="edit_task_name" id="edit_task_name" placeholder="Task Name" value="' . $asd['task_name'] . '" required>';

    }
    print '<input type="hidden" name="edit_task_id" id="edit_task_id" value="' . $asd['id'] . '" required>';
    print '</div>';
    print '</div>';
    print '</div>';
    print '<div class="col-md-6">';
    print '<label>Daily Followup Duration<span class="text-danger"></span></label>';
    print '<div class="form-group">';
    print '<input type="number" class="form-control d-cursor" name="edit_daily_followup_task" id="edit_daily_followup_task" value="' . $asd['daily_followup_task'] . '" required>';
    print '</div>';
    print '</div>';

    $s1 = ($asd['daily_followup_duration_task'] == 'minute') ? 'selected' : '';
    print '<div class="col-md-6">';
    print '<label>Hour / Minute<span class="text-danger"></span></label>';
    print '<div class="form-group">';
    print '<select class="form-control custom-select2" name="edit_daily_followup_duration_task" id="edit_daily_followup_duration_task" style="width:80%;">';
    print '<option value="hour">Hours</option>';
    print '<option value="minute" '. $s1 .'>Minutes</option>';
    print '</select>';
    print '</div>';
    print '</div>';
    
    print '<div class="col-md-6">';
    print '<label>End Followup Duration<span class="text-danger"></span></label>';
    print '<div class="form-group">';
    print '<input type="number" class="form-control d-cursor" name="edit_end_followup_task" id="edit_end_followup_task" value="' . $asd['end_followup_task'] . '" required>';
    print '</div>';
    print '</div>';

    $s1 = ($asd['end_followup_duration_task'] == 'minute') ? 'selected' : '';
    print '<div class="col-md-6">';
    print '<label>Hour / Minute<span class="text-danger"></span></label>';
    print '<div class="form-group">';
    print '<select class="form-control custom-select2" name="edit_end_followup_duration_task" id="edit_end_followup_duration_task" style="width:80%;">';
    print '<option value="hour">Hours</option>';
    print '<option value="minute" '. $s1 .'>Minutes</option>';
    print '</select>';
    print '</div>';
    print '</div>';
    
} else if(isset($_REQUEST['getdefectedit'])) {

    $ass = "SELECT * FROM mas_defect WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label>Defect Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_defect_name" id="edit_defect_name" placeholder="Defect Name" value="' . $asd['defect_name'] . '" required>';
    print '<input type="hidden" name="edit_defect_id" id="edit_defect_id" value="' . $asd['id'] . '" required>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getcurrencyedit'])) {

    $ass = "SELECT * FROM mas_currency WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label>Currency Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_currency_name" id="edit_currency_name" placeholder="Currency Name" value="' . $asd['currency_name'] . '" required>';
    print '<input type="hidden" name="edit_currency_id" id="edit_currency_id" value="' . $asd['id'] . '" required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Currency Value <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_currency_value" id="edit_currency_value" placeholder="Currency Value" value="' . $asd['currency_value'] . '" required>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getdesignationedit'])) {

    $ass = "SELECT * FROM mas_designation WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label>Designation Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_desig_name" id="edit_desig_name" placeholder="Designation Name" value="' . $asd['desig_name'] . '" required>';
    print '<input type="hidden" name="edit_desig_id" id="edit_desig_id" value="' . $asd['id'] . '" required>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getcomponentedit'])) {

    $ass = "SELECT * FROM mas_component WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label>Component Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_comp_name" id="edit_comp_name" placeholder="Component Name" value="' . $asd['component_name'] . '" required>';
    print '<input type="hidden" name="edit_comp_id" id="edit_comp_id" value="' . $asd['id'] . '" required>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getAccessoriesedit'])) {

    $ass = "SELECT * FROM mas_accessories WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-4">';
    print '<label>Accessories Type <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="form-control custom-select2 sel2" name="edit_acc_type" id="edit_acc_type" style="width:100%" required>';
    print select_dropdown('mas_accessories_type', array('id', 'type_name'), 'id ASC', $asd['acc_type'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-4">';
    print '<label>Accessories Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_acc_name" id="edit_acc_name" placeholder="Accessories Name" value="' . $asd['acc_name'] . '" required>';
    print '<input type="hidden" name="edit_id" id="edit_id" value="' . $asd['id'] . '" required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-4">';
    print '<label class="fieldrequired">Excess Percetage</label>';
    print '<div class="form-group">';
    print '<input type="number" class="form-control" name="edit_excess" id="edit_excess" placeholder="Excess Percetage" value="' . $asd['excess'] . '" required>';
    print '</div>';
    print '</div>';
    print '<div class="col-md-12"><hr></div>';

    print '<div class="col-md-3">';
    print '<label class="fieldrequired">Purchase UOM</label>';
    print '<div class="form-group">';
    print '<select onchange="edit_uom_valid()" name="edit_purchase_uom" id="edit_purchase_uom" class="form-control custom-select2 sel2" style="width:100%" required>';
    print select_dropdown('mas_uom', array('id', 'uom_name'), 'uom_name ASC', $asd['purchase_uom'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-3">';
    print '<label class="fieldrequired">Consumption UOM</label>';
    print '<div class="form-group">';
    print '<select onchange="edit_uom_valid()" name="edit_consumption_uom" id="edit_consumption_uom" class="form-control custom-select2 sel2" style="width:100%" required>';
    print select_dropdown('mas_uom', array('id', 'uom_name'), 'uom_name ASC', $asd['consumption_uom'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-3">';
    print '<label class="fieldrequired">Purchase Unit</label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_purchase_unit" id="edit_purchase_unit" placeholder="Purchase Unit" value="1" readonly required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-3">';
    print '<label class="fieldrequired">Purchase UOM Qty</label>';
    print '<div class="form-group">';
    $roy = ($asd['purchase_uom'] == $asd['consumption_uom']) ? 'readonly' : '';
    print '<input type="number" class="form-control" name="edit_uom_qty" id="edit_uom_qty" placeholder="Purchase UOM Qty" value="' . $asd['uom_qty'] . '" '. $roy .' required>';
    print '</div>';
    print '</div>';


} else if(isset($_REQUEST['getAccessoriesTypeedit'])) {

    $ass = "SELECT * FROM mas_accessories_type WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label>Accessories Type Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_type_name" id="edit_type_name" placeholder="Accessories Type Name" value="' . $asd['type_name'] . '" required>';
    print '<input type="hidden" name="edit_id" id="edit_id" value="' . $asd['id'] . '" required>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getApprovaledit'])) {

    $ass = "SELECT * FROM mas_approval WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label>Approval Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_approval_name" id="edit_approval_name" placeholder="Approval Name" value="' . $asd['name'] . '" required>';
    print '<input type="hidden" name="edit_approval_id" id="edit_approval_id" value="' . $asd['id'] . '" required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Approval Department <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="form-control custom-select2" name="edit_app_dpt" id="edit_app_dpt" style="width:100%" required>';
    print select_dropdown('department', array('id', 'department_name'), 'department_name ASC', $asd['department'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-6">';
    print '<label>Daily Followup Duration<span class="text-danger"></span></label>';
    print '<div class="form-group">';
    print '<input type="number" class="form-control d-cursor" name="edit_daily_followup" id="edit_daily_followup" value="' . $asd['daily_followup'] . '" required>';
    print '</div>';
    print '</div>';

    $s1 = ($asd['daily_followup_duration'] == 'minute') ? 'selected' : '';
    print '<div class="col-md-6">';
    print '<label>Hour / Minute<span class="text-danger"></span></label>';
    print '<div class="form-group">';
    print '<select class="form-control custom-select2" name="edit_daily_followup_duration" id="edit_daily_followup_duration" style="width:80%;">';
    print '<option value="hour">Hours</option>';
    print '<option value="minute" '. $s1 .'>Minutes</option>';
    print '</select>';
    print '</div>';
    print '</div>';
    
    print '<div class="col-md-6">';
    print '<label>End Followup Duration<span class="text-danger"></span></label>';
    print '<div class="form-group">';
    print '<input type="number" class="form-control d-cursor" name="edit_end_followup" id="edit_end_followup" value="' . $asd['end_followup'] . '" required>';
    print '</div>';
    print '</div>';

    $s1 = ($asd['end_followup_duration'] == 'minute') ? 'selected' : '';
    print '<div class="col-md-6">';
    print '<label>Hour / Minute<span class="text-danger"></span></label>';
    print '<div class="form-group">';
    print '<select class="form-control custom-select2" name="edit_end_followup_duration" id="edit_end_followup_duration" style="width:80%;">';
    print '<option value="hour">Hours</option>';
    print '<option value="minute" '. $s1 .'>Minutes</option>';
    print '</select>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getpartedit'])) {

    $ass = "SELECT * FROM part WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label>Part Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_part_name" id="edit_part_name" placeholder="Part Name" value="' . $asd['part_name'] . '" required>';
    print '<input type="hidden" name="edit_part_id" id="edit_part_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getpunitedit'])) {

    $ass = "SELECT * FROM production_unit WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label>Production Unit Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_full_name" id="edit_full_name" placeholder="Production Unit Name" value="' . $asd['full_name'] . '" required>';
    print '<input type="hidden" name="edit_full_id" id="edit_full_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getcoloredit'])) {

    $ass = "SELECT * FROM color WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label>Color Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_color_name" id="edit_color_name" placeholder="Color Name" value="' . $asd['color_name'] . '" required>';
    print '<input type="hidden" name="edit_color_id" id="edit_color_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getfabricedit'])) {

    $ass = "SELECT * FROM fabric WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Fabric Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_fabric_name" id="edit_fabric_name" placeholder="Fabric Name" value="' . $asd['fabric_name'] . '" required>';
    print '<input type="hidden" name="edit_fabric_id" id="edit_fabric_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Fabric Code <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_fabric_code" id="edit_fabric_code" placeholder="Fabric Name" value="' . $asd['fabric_code'] . '" required>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getLineedit'])) {

    $ass = "SELECT * FROM mas_line WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Line Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="ed_line_name" id="ed_line_name" placeholder="Line Name" value="' . $asd['line_name'] . '" required>';
    print '<input type="hidden" name="edit_id" id="edit_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Process <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="custom-select2 form-control" name="ed_process" id="ed_process" style="width:100%;" multiple>';
    print select_dropdown_multiple('process', array('id', 'process_name'), 'process_name ASC', $asd['process'], '', '`');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Pay Type <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="custom-select2 form-control" name="ed_pay_type" id="ed_pay_type" style="width:100%;">';
    $op2 = ($asd['pay_type']==2) ? 'selected' : '';
    print '<option value="1">Shift</option><option value="2" '. $op2 .'>Pcs Rate</option>';
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Cost Generators <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="custom-select2 form-control" name="ed_cost_generator[]" id="ed_cost_generator" style="width:100%;" multiple>';
    print select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $asd['cost_generator'], 'WHERE is_active="active" AND is_cg = "Yes"', '1');
    print '</select>';
    print '</div>';
    print '</div>';


} else if(isset($_REQUEST['getPackedit'])) {

    $ass = "SELECT * FROM mas_pack WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Pack Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_pack_name" id="edit_pack_name" placeholder="Pack Name" value="' . $asd['pack_name'] . '" required>';
    print '<input type="hidden" name="edit_id" id="edit_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';


} else if(isset($_REQUEST['getYarnedit'])) {

    $ass = "SELECT * FROM mas_yarn WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Yarn Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_yarn_name" id="edit_yarn_name" placeholder="Yarn Name" value="' . $asd['yarn_name'] . '" required>';
    print '<input type="hidden" name="edit_id" id="edit_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Yarn Code <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_yarn_code" id="edit_yarn_code" placeholder="Yarn Name" value="' . $asd['yarn_code'] . '" required>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getuomedit'])) {

    $ass = "SELECT * FROM mas_uom WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>UOM Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_uom_name" id="edit_uom_name" placeholder="UOM Name" value="' . $asd['uom_name'] . '" required>';
    print '<input type="hidden" name="edit_id" id="edit_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getstyleedit'])) {

    $ass = "SELECT * FROM style WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label>Style Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_style_name" id="edit_style_name" placeholder="Style Name" value="' . $asd['style_name'] . '" required>';
    print '<input type="hidden" name="edit_style_id" id="edit_style_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Style Code <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_style_code" id="edit_style_code" placeholder="Style Name" value="' . $asd['style_code'] . '" required>';
    print '<input type="hidden" name="edit_style_id" id="edit_style_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getselection_typeedit'])) {

    $ass = "SELECT * FROM selection_type WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label>Type Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_type_name" id="edit_type_name" placeholder="Type Name" value="' . $asd['type_name'] . '" required>';
    print '<input type="hidden" name="edit_type_id" id="edit_type_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getmerchandedit'])) {

    $ass = "SELECT * FROM merchand_detail WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Merchandiser Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="custom-select2 form-control" name="edit_merchand_name" id="edit_merchand_name" style="width:100%;" required>';
    print select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $asd['merchand_name'], ' WHERE is_active="active"', '');
    print '</select>';
    print '<input type="hidden" name="edit_merchand_id" id="edit_merchand_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Merchandiser Code </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_merchand_code" id="edit_merchand_code" placeholder="Merchandiser Code" value="' . $asd['merchand_code'] . '" required>';
    print '</div>';
    print '</div>';
    
    print '<div class="col-md-12">';
    print '<label>Buyer <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="custom-select2 form-control" name="edit_merch_brand[]" id="edit_merch_brand" style="width:100%;" required multiple>';
    print select_dropdown_multiple('brand', array('id', 'brand_name'), 'brand_name ASC', $asd['merch_brand'], '', '`');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Mail Id </label>';
    print '<div class="form-group">';
    print '<input type="email" class="form-control" name="mailid_edit" id="mailid_edit" placeholder="Mobile Number" value="' . $asd['mailid'] . '" required>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getemployeeedit'])) {

    $ass = "SELECT * FROM employee_detail WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Employee Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_employee_name" id="edit_employee_name" placeholder="Employee Name" value="' . $asd['employee_name'] . '" required>';
    print '<input type="hidden" name="edit_employee_id" id="edit_employee_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Employee Code </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_employee_code" id="edit_employee_code" placeholder="Employee Code" value="' . $asd['employee_code'] . '" >';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Mobile Number </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="mobile_edit" id="mobile_edit" placeholder="Mobile Number" value="' . $asd['mobile'] . '" >';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Sub contract bill name </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="subBill_edit" id="subBill_edit" placeholder="Sub contract bill name" value="' . $asd['sub_billname'] . '" >';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Employee Status </label>';
    print '<div class="form-group">';
    print '<select name="process_edit" id="process_edit" class="form-control custom-select2">';
    print select_dropdown('process', array('id', 'process_name'), 'process_name ASC', $asd['process'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Unit </label>';
    print '<div class="form-group">';
    print '<select name="unitt_edit" id="unitt_edit" class="form-control custom-select2">';
    print select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $asd['company'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>User Name </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="uname_edit" id="uname_edit" placeholder="User Name" value="' . $asd['username'] . '" >';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Password </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="password_edit" id="password_edit" placeholder="Password" value="' . $asd['password'] . '" >';
    print '</div>';
    print '</div>';
    
    print '<div class="col-md-12">';
    print '<label>User Group </label>';
    print '<div class="form-group">';
    print '<select name="user_group_edit" id="user_group_edit" class="form-control custom-select2">';
    print select_dropdown('user_group', array('id', 'group_name'), 'group_name ASC', $asd['user_group'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getUseredit'])) {

    $ass = "SELECT * FROM employee_detail WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>User Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_employee_name" id="edit_employee_name" placeholder="Employee Name" value="' . $asd['employee_name'] . '" required>';
    print '<input type="hidden" name="edit_employee_id" id="edit_employee_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12 d-none">';
    print '<label>Employee Code </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_employee_code" id="edit_employee_code" placeholder="Employee Code" value="' . $asd['employee_code'] . '" >';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Mobile Number </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="mobile_edit" id="mobile_edit" placeholder="Mobile Number" value="' . $asd['mobile'] . '" >';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12 d-none">';
    print '<label>Sub contract bill name </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="subBill_edit" id="subBill_edit" placeholder="Sub contract bill name" value="' . $asd['sub_billname'] . '" >';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12 d-none">';
    print '<label>Employee Status </label>';
    print '<div class="form-group">';
    print '<select name="process_edit" id="process_edit" class="form-control custom-select2">';
    print select_dropdown('process', array('id', 'process_name'), 'process_name ASC', $asd['process'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Company </label>';
    print '<div class="form-group">';
    print '<select name="unitt_edit" id="unitt_edit" class="form-control custom-select2">';
    print select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $asd['company'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>User Name </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="uname_edit" id="uname_edit" placeholder="User Name" value="' . $asd['username'] . '" >';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Password </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="password_edit" id="password_edit" placeholder="Password" value="' . $asd['password'] . '" >';
    print '</div>';
    print '</div>';
    
    print '<div class="col-md-12">';
    print '<label>User Group </label>';
    print '<div class="form-group">';
    print '<select name="user_group_edit" id="user_group_edit" class="form-control custom-select2">';
    print select_dropdown('user_group', array('id', 'group_name'), 'group_name ASC', $asd['user_group'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getmachineedit'])) {

    $ass = "SELECT * FROM machine WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Machine Code <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_machine_code" id="edit_machine_code" placeholder="Machine Code" value="' . $asd['machine_code'] . '" required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Machine Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_machine_name" id="edit_machine_name" placeholder="Machine Name" value="' . $asd['machine_name'] . '" required>';
    print '<input type="hidden" name="edit_machine_id" id="edit_machine_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getdepartmentedit'])) {

    $ass = "SELECT * FROM department WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Department Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_department_name" id="edit_department_name" placeholder="Department Name" value="' . $asd['department_name'] . '" required>';
    print '<input type="hidden" name="edit_department_id" id="edit_department_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';
} else if(isset($_REQUEST['getprocessedit'])) {

    $ass = "SELECT * FROM process WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Select Department <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="form-control d-cursor" name="edit_department" id="edit_department" required="">';
    print select_dropdown('department', array('id', 'department_name'), 'department_name ASC', $asd['department'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Process Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_process_name" id="edit_process_name" placeholder="Process Name" value="' . $asd['process_name'] . '" required>';
    print '<input type="hidden" name="edit_process_id" id="edit_process_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Process Code </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_process_code" id="edit_process_code" placeholder="Process Code" value="' . $asd['process_code'] . '" required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Process Price </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_process_price" id="edit_process_price" placeholder="Process Price" value="' . $asd['process_price'] . '" required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Process Type </label>';
    print '<div class="form-group">';
    print '<select name="edit_process_type" id="edit_process_type" class="form-control custom-select2" style="width:100%">';
    if($asd['process_type']=='Production') { $pro = 'selected'; $fab = ''; } else if($asd['process_type']=='Fabric') { $pro = ''; $fab = 'selected'; }
    print '<option value="production" '. $pro .'>Production Process</option>';
    print '<option value="Fabric" '. $fab .'>Fabric Process</option>';
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12 d-none ">';
    print '<label>Fabric Type </label>';
    print '<div class="form-group">';
    print '<select name="edit_process_type_name" id="edit_process_type_name" class="form-control custom-select2" style="width:100%">';
    if($asd['process_type_name']=='Solid') { $Solid = 'selected'; $yd = ''; $Melange = ''; } else if($asd['process_type_name']=='Y/D') { $Solid = ''; $Melange = ''; $yd = 'selected'; } else if($asd['process_type_name']=='Melange') { $Solid = ''; $yd = ''; $Melange = 'selected'; }
    print '<option value="">Select</option>';
    print '<option value="Solid" '. $Solid .'>Solid</option>';
    print '<option value="Y/D" '. $yd .'>Y/D</option>';
    print '<option value="Melange" '. $Melange .'>Melange</option>';
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Budget Type </label>';
    print '<div class="form-group">';
    print '<select name="edit_budget_type" id="edit_budget_type" class="form-control custom-select2" style="width:100%">';
    print '<option value="">Select</option>';
    $Arr = array('Fabric', 'Yarn', 'Dyeing Color', 'AOP Design');
    foreach($Arr as $Arr) {
        $op = ($asd['budget_type']==$Arr) ? 'selected' : '';
        print '<option value="'. $Arr .'" '. $op .'>'. $Arr .'</option>';
    }
    print '</select>';
    print '</div>';
    print '</div>';
    
    print '<div class="col-md-12">';
    print '<label>Quality Approval </label>';
    print '<div class="form-group">';
    $gb = ($asd['qc_approval'] == 'yes') ? "selected" : "";
    print '<select name="edit_qc_app" id="edit_qc_app" class="form-control custom-select2" style="width:100%">';
    print '<option value="no">No</option>';
    print '<option value="yes" ' . $gb . '>Yes</option>';
    print '</select>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getsub_processedit'])) {

    $ass = "SELECT * FROM sub_process WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Select Department <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="form-control d-cursor" name="edit_department" id="edit_department" required="">';
    print select_dropdown('department', array('id', 'department_name'), 'department_name ASC', $asd['department'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Select Process  <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="form-control d-cursor" name="edit_process_id" id="edit_process_id" required="">';
    print select_dropdown('process', array('id', 'process_name'), 'process_name ASC', $asd['process_id'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Sub Process Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_process_name" id="edit_process_name" placeholder="Sub Process Name" value="' . $asd['sub_process_name'] . '" required>';
    print '<input type="hidden" name="edit_process_id" id="sub_process_name" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Sub Process Code </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_process_code" id="edit_process_code" placeholder="Sub Process Code" value="' . $asd['sub_process_code'] . '" required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Price </label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_price" id="edit_price" placeholder="Price" value="' . $asd['price'] . '" required>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getcustomerdetails'])) {

    $ass = "SELECT a.*, b.brand_name FROM customer a LEFT JOIN brand b ON a.brand=b.id WHERE a.id='" . $_REQUEST['getcustomerdetails'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $det = mysqli_fetch_array($asss);

    print '<div class="row">';
    print '<div class="col-md-6">';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Customer Name </label> <div class="col-sm-7">: ' . $det['customer_name'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Address 1 </label> <div class="col-sm-7">: ' . $det['address1'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Area </label> <div class="col-sm-7">: ' . $det['area'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">City </label> <div class="col-sm-7">: ' . $det['city'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Phone 1 </label> <div class="col-sm-7">: ' . $det['phone1'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Email Id </label> <div class="col-sm-7">: ' . $det['emailid'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Brand </label> <div class="col-sm-7">: ' . $det['brand_name'] . '</span> </div></div>';

    print '</div>';

    print '<div class="col-md-6">';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Customer Code </label> <div class="col-sm-7">: ' . $det['customer_code'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Address 2 </label> <div class="col-sm-7">: ' . $det['address2'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">State </label> <div class="col-sm-7">: ' . $det['state'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Mobile </label> <div class="col-sm-7">: ' . $det['mobile'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Phone 2 </label> <div class="col-sm-7">: ' . $det['phone2'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">GST Number </label> <div class="col-sm-7">: ' . $det['gst_no'] . '</span> </div></div>';

    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getsupplierdetails'])) {

    $ass = "SELECT * FROM supplier WHERE id='" . $_REQUEST['getsupplierdetails'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $det = mysqli_fetch_array($asss);

    print '<div class="row">';
    print '<div class="col-md-6">';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Supplier Name </label> <div class="col-sm-7">: ' . $det['supplier_name'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Address 1 </label> <div class="col-sm-7">: ' . $det['address1'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Area </label> <div class="col-sm-7">: ' . $det['area'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">City </label> <div class="col-sm-7">: ' . $det['city'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Phone 1 </label> <div class="col-sm-7">: ' . $det['phone1'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Email Id </label> <div class="col-sm-7">: ' . $det['emailid'] . '</span> </div></div>';

    print '</div>';

    print '<div class="col-md-6">';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Supplier Code </label> <div class="col-sm-7">: ' . $det['supplier_code'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Address 2 </label> <div class="col-sm-7">: ' . $det['address2'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">State </label> <div class="col-sm-7">: ' . $det['state'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Mobile </label> <div class="col-sm-7">: ' . $det['mobile'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">Phone 2 </label> <div class="col-sm-7">: ' . $det['phone2'] . '</span> </div></div>';
    print '<div class="form-group row"> <label for="staticEmail" class="col-sm-5 col-form-label">GST Number </label> <div class="col-sm-7">: ' . $det['gst_no'] . '</span> </div></div>';

    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['gettaxmainedit'])) {

    $ass = "SELECT * FROM tax_main WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Tax Full Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_full_name" id="edit_full_name" placeholder="Tax Full Name" value="' . $asd['full_name'] . '" required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Tax Short Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_short_name" id="edit_short_name" placeholder="Tax Short Name" value="' . $asd['short_name'] . '" required>';
    print '<input type="hidden" name="edit_tax_main_id" id="edit_tax_main_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['gettax_subedit'])) {

    $ass = "SELECT * FROM tax_sub WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Tax <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select name="edit_tax_main" id="edit_tax_main" class="custom-select2 form-control" style="width:100%" required>';
    print select_dropdown('tax_main', array('id', 'full_name'), 'full_name ASC', $asd['tax_main'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Tax Full Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_full_name" id="edit_full_name" placeholder="Tax Full Name" value="' . $asd['full_name'] . '" required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Percentage <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_percentage" id="edit_percentage" placeholder="Tax Percentage" value="' . $asd['percentage'] . '" required>';
    print '<input type="hidden" name="tax_sub_id" id="tax_sub_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getunitedit'])) {

    $ass = "SELECT * FROM unit WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Unit Full Name<span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_full_name" id="edit_full_name" placeholder="Unit Full Name" value="' . $asd['full_name'] . '" required>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Unit Short Name</label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_short_name" id="edit_short_name" placeholder="Unit Short Name" value="' . $asd['short_name'] . '">';
    print '<input type="hidden" name="edit_unit_id" id="edit_unit_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Part Count<span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="number" class="form-control" name="edit_part_count" id="edit_part_count" placeholder="Part Count" value="' . $asd['part_count'] . '" required>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getexp_subedit'])) {

    $ass = "SELECT * FROM expense_sub WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Expense Full Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="form-control" name="edit_full_name" id="edit_full_name" required="">';
    print select_dropdown('expense_main', array('id', 'expense_name'), 'expense_name ASC', $asd['full_name'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Expense Sub <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_expense_sub" id="edit_expense_sub" placeholder="Expense Sub" value="' . $asd['expense_sub'] . '" required>';
    print '<input type="hidden" name="edit_expense_id" id="edit_expense_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['getvariationlist'])) {

    $ass = "SELECT * FROM variation_value WHERE variation_id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $x = 1;
    while ($asd = mysqli_fetch_array($asss)) {

        print '<div class="col-md-12">';
        print '<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $x . '.&nbsp;' . $asd['type'] . '</label>';
        print '</div>';
        $x++;
    }
} else if(isset($_REQUEST['getvariationedit'])) {

    $aa = "SELECT * FROM variation WHERE id='" . $_REQUEST['id'] . "'";
    $ss = mysqli_query($mysqli, $aa);
    $cc = mysqli_fetch_array($ss);

    $x = 1;

    print '<div class="col-md-12">';
    print '<label>Variation Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_variation_name" id="edit_variation_name" placeholder="Expense Sub" value="' . $cc['variation_name'] . '" required>';
    print '<input type="hidden" name="edit_variation_id" id="edit_variation_id" value="' . $cc['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12 d-none">';
    print '<label>Variation Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="form-control" name="edit_style_id" id="edit_style_id" >';
    print select_dropdown('style', array('id', 'style_name'), 'style_name ASC', $cc['style_id'], '', '');
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Variation Value </label>';

    $ass = "SELECT * FROM variation_value WHERE variation_id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    while ($asd = mysqli_fetch_array($asss)) {
        $eq = $asd['id'] . ", 'variation_value'";
        print '<div class="form-group d-flex">';
        print '<input type="hidden" name="editcountlist" id="editcountlist" value="1">';
        print '<input type="hidden" name="v_valueId[]" id="v_valueId" value="' . $asd['id'] . '">';
        print '<input type="text" class="form-control" name="edit_variation_value[]" id="edit_variation_value" placeholder="Variation Value" value="' . $asd['type'] . '" style="width:90%"> <button type="button" class="btn btn-danger" onclick="delete_data(' . $eq . ')"><i class="fa fa-trash"></i> </button>';
        print '</div>';
    }
    print '</div>';

    print '<div class="col-md-12">';
    print '<div class="form-group d-flex">';
    print '<input type="hidden" name="editcountlist" id="editcountlist" value="1">';
    print '<input type="hidden" name="v_valueId[]" id="v_valueId" value="">';
    print '<input type="text" class="form-control" name="edit_variation_value[]" id="edit_variation_value" placeholder="Variation Value" value="' . $asd['expense_sub'] . '" style="width:90%">';
    print '<button type="button" class="btn btn-secondary" onclick="addmoreedit()"><i
    class="fa fa-plus"></i> </button>';
    print '</div>';

    print '<div id="moredivedit"></div>';
    print '</div>';

} else if(isset($_REQUEST['getvariationmod'])) {

    $yui = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM unit WHERE id='" . $_REQUEST['id'] . "'"));

    $data['name'][] = '<label for="">Part Name : </label>';
    $data['name'][] = '<input type="hidden" id="unitTemp_id" value="' . $_REQUEST['id'] . '">';

    $asdd = select_dropdown('part', array('id', 'part_name'), 'part_name ASC', '', 'WHERE is_active="active"', '');
    // $sle = '<div class="d-flex"><select name="pack_name[]" id="pack_name' . $n . '" class="custom-select2 form-control" style="width:85%">' . $asdd . '</select> <i style="padding:12px" class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" onclick="showPartModal(' . $n . ')"></i></div></br>';
    for ($m = 0; $m < $yui['part_count']; $m++) {
        $lm = $m + 1;
        
        if(MAS_PART_ADD==1) {
            $parr = '<i style="padding:12px" class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" onclick="showPartModal(' . $m . ')"></i>';
        } else {
            $parr = '';
        }

        // $data['name'][] = $sle;
        $data['name'][] = '<div class="d-flex"><select name="part_name1[]" id="part_name' . $m . '" class="custom-select2 form-control partName dup_part1" onchange="validate_part(1)" style="width:85%">' . $asdd . '</select> '. $parr .'</div></br>';
    }

    $opp = select_dropdown('color', array('id', 'color_name'), 'color_name ASC', '', 'WHERE is_active="active"', '');
    $data['color'][] = '<label for="">Part Color : </label><br />';
    for ($n = 0; $n < $yui['part_count']; $n++) {
        if(MAS_PART_ADD==1) {
            $clrr = '<i style="padding:12px" class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" onclick="showColorModal(' . $n . ')"></i>';
        } else {
            $clrr = '';
        }
        $data['color'][] = '<div class="d-flex"><select name="part_color1[]" id="part_color' . $n . '" class="form-control custom-select2 colorName" style="width:85%">' . $opp . '</select> '. $clrr .'</div></br>';
    }

    echo json_encode($data);

} else if(isset($_REQUEST['itemrow'])) {

    $ass = "SELECT a.*, b.item_name, b.item_code FROM sales_order_detalis a LEFT JOIN itemlist b ON a.item_id=b.id WHERE sales_order_id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $x = 1;
    while ($asd = mysqli_fetch_array($asss)) {

        print '<tr>';
        print '<td>' . $asd['item_code'] . '</td>';
        print '</tr>';
        $x++;
    }
    
} else if(isset($_REQUEST['getedit_mod'])) {

    $mv = "SELECT * FROM sales_order_detalis WHERE id='" . $_REQUEST['id'] . "' ";
    $uy = mysqli_query($mysqli, $mv);

    $hp = mysqli_fetch_array($uy);
    
    print '<div class="row" id="" style="padding: 10px;border: 1px solid #ededed;"><table class="table table-bordered">';
    
        $qy = mysqli_query($mysqli, "SELECT * FROM sod_combo WHERE sales_order_detail_id = '". $_REQUEST['id'] ."'");
        
        $ss = 1;
        while($res = mysqli_fetch_array($qy)) {
            
            print '<tr style="border-top:2px solid gray; border-right:2px solid gray; border-left:2px solid gray;"><td colspan="2">
                <label>Combo :</label>
                <input type="hidden" value="'. $res['id'] .'" name="edit_combo_id[]">
                <input type="hidden" value="'. $ss .'" name="edit_combo_tempid[]">
                <select class="form-control custom-select2 select22 compNm" name="edit_combo'. $ss .'" id="" style="width:100%">
                    '. select_dropdown('color', array('id', 'color_name'), 'color_name ASC', $res['combo_id'], '', '') .'
                </select>
            </td>
            <td><label>Pack Type :</label>
                <select class="form-control custom-select2 select22 packNm" name="edit_pack'. $ss .'" id="" style="width:100%">
                    '. select_dropdown('mas_pack', array('id', 'pack_name'), 'pack_name ASC', $res['pack_id'], '', '') .'
                </select>
            </td></tr>';
            
            print '<tr style="border-right:2px solid gray; border-left:2px solid gray;background-color: #efefef;"><th>Part</th><th colspan="2">Color</th></tr>';
            
            $sab = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sod_combo = '". $res['id'] ."'");
            while($view = mysqli_fetch_array($sab)) {
                
                print '<tr style="border-right:2px solid gray;border-left:2px solid gray;">
                        <td>
                            <input type="hidden" value="'. $ss .'" name="edit_part_tempid[]">
                            <input type="hidden" value="'. $view['id'] .'" name="edit_part_id[]">
                            <select class="form-control custom-select2 select22 " name="edit_part[]" id="" style="width:100%">
                                '. select_dropdown('part', array('id', 'part_name'), 'part_name ASC', $view['part_id'], '', '') .'
                            </select>
                        </td>
                        <td colspan="2">
                            <select class="form-control custom-select2 select22 " name="edit_color[]" id="" style="width:100%">
                                '. select_dropdown('color', array('id', 'color_name'), 'color_name ASC', $view['color_id'], '', '') .'
                            </select>
                        </td></tr>';
            }
            
            print '<tr style="border-right:2px solid gray; border-left:2px solid gray;background-color: #efefef;"><th>Size</th><th>Qty</th><th>Excess %</th></tr>';
            
            $sab1 = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = '". $res['id'] ."'");
            
            $n = mysqli_num_rows($sab1);
            $i = 1;
            $ca = 0;
            while($view1 = mysqli_fetch_array($sab1)) {
                $ca += $view1['size_qty'];
                print '<tr style="border-right:2px solid gray;border-left:2px solid gray;"><td>'. variation_value($view1['variation_value']) .'</td>
                        <td>
                        <input type="hidden" value="'. $ss .'" name="edit_size_tempid[]">
                        <input type="hidden" value="'. $view1['id'] .'" name="edit_size_id[]">
                        <input type="text" name="edit_qty[]" id="" placeholder="Qty" class="form-control mw-200" value="'. $view1['size_qty'] .'"></td>
                        <td><input type="text" name="edit_excess[]" id="" placeholder="Excess %" class="form-control mw-200" value="'. $view1['excess_per'] .'"></td></tr>';
            $i++; }
            
            print '<tr style="border-right:2px solid gray;border-left:2px solid gray;border-bottom:2px solid gray;"><th>Total</td> <td>'. $ca .'</td> <td></td></tr>';
        $ss++; }
    
    print '</table></div>';
    
} else if(isset($_REQUEST['getviewdetails'])) {

    $mv = "SELECT * FROM sales_order_detalis WHERE id='" . $_REQUEST['id'] . "' ";
    $uy = mysqli_query($mysqli, $mv);

    $hp = mysqli_fetch_array($uy);
    
    print '<div class="row" id="" style="padding: 10px;border: 1px solid #ededed;"><table class="table table-bordered">';
    
        $qy = mysqli_query($mysqli, "SELECT * FROM sod_combo WHERE sales_order_detail_id = '". $_REQUEST['id'] ."'");
        
        while($res = mysqli_fetch_array($qy)) {
            
            print '<tr style="border-top:2px solid gray; border-right:2px solid gray; border-left:2px solid gray;"><th colspan="2">Combo : '. color_name($res['combo_id']) .'</th><th>Pack Type : '. pack_name($res['pack_id']) .'</th></tr>';
            
            print '<tr style="border-right:2px solid gray; border-left:2px solid gray;background-color: #efefef;"><th>Part</th><th colspan="2">Color</th></tr>';
            
            $sab = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sod_combo = '". $res['id'] ."'");
            while($view = mysqli_fetch_array($sab)) {
                
                print '<tr style="border-right:2px solid gray;border-left:2px solid gray;"><td>'. part_name($view['part_id']) .'</td><td colspan="2">'. color_name($view['color_id']) .'</td></tr>';
            }
            
            print '<tr style="border-right:2px solid gray; border-left:2px solid gray;background-color: #efefef;"><th>Size</th><th>Qty</th><th>Excess %</th></tr>';
            
            $sab1 = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = '". $res['id'] ."'");
            
            $n = mysqli_num_rows($sab1);
            $i = 1;
            $ca = 0;
            while($view1 = mysqli_fetch_array($sab1)) {
                $ca += $view1['size_qty'];
                print '<tr style="border-right:2px solid gray;border-left:2px solid gray;"><td>'. variation_value($view1['variation_value']) .'</td> <td>'. $view1['size_qty'] .'</td> <td>'. $view1['excess_per'] .'</td></tr>';
            $i++; }
            
            print '<tr style="border-right:2px solid gray;border-left:2px solid gray;border-bottom:2px solid gray;"><th>Total</td> <td>'. $ca .'</td> <td></td></tr>';
        }
    
    print '</table></div>';

    // echo json_encode($data);

} else if(isset($_REQUEST['showimage'])) {

    print '<img src="' . $_REQUEST['img'] . '" alt="" style="height: 300px;">';

} else if(isset($_REQUEST['showimage_qr'])) {

    print '<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $_REQUEST['img'] . '&choe=UTF-8" title="' . $_REQUEST['img'] . '" style="width:height: 300px;"/>';

} else if(isset($_REQUEST['getStyleNo'])) {

    if (!empty($_REQUEST['id'])) {
        
        $ass = "SELECT id, style_no FROM sales_order_detalis WHERE sales_order_id='" . $_REQUEST['id'] . "'";
        $asss = mysqli_query($mysqli, $ass);
        $x = 1;
        $data['option'][] = '<option value="">Select Style</otion>';
        while ($asd = mysqli_fetch_array($asss)) {
            
            $bnj = ($_REQUEST['oldstyle'] == $asd['id']) ? 'selected' : '';
            
            $data['option'][] = '<option value="' . $asd['id'] . '" ' . $bnj . '>' . $asd['style_no'] . '</otion>';
        }
    }
    
    echo json_encode($data);

} else if(isset($_REQUEST['get_new_lay'])) {
    
    $sqql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM process_planing WHERE style_id = '". $_REQUEST['styleList'] ."' AND process_id = 1"));
    
    if($sqql['plan_type']=='Full' && $sqql['process_type']=='unit' && $sqql['processing_unit_id']==$logUnit) {
        
        $bbk = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = '". $_REQUEST['combo_id'] ."'");
        
        $ky = 0;
        while($roww = mysqli_fetch_array($bbk)) {
            
            $exs = $roww['size_qty'] + round(($roww['size_qty'] / 100) * $roww['excess_per']);
            $smm = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE sod_size = '". $roww['id'] ."' AND sod_part = '". $_REQUEST['sod_part_id'] ."'"));
            $to_bno = mysqli_fetch_array(mysqli_query($mysqli, "SELECT to_bno FROM cutting_barcode WHERE sod_part = '". $_REQUEST['sod_part_id'] ."' ORDER BY id DESC"));
            
            $tooo = $to_bno['to_bno'];
            $mmq = $smm['pcs_per_bundle'] ? $smm['pcs_per_bundle'] : 0;
            
            $oc = $ky .", '". variation_value($roww['variation_value']) ."', '". $mmq ."',". $roww['variation_value'] .",". $roww['size_qty'] .",". $exs .",". $roww['id'];
            
            $data['lay_div'][] = '<tr id="trOf' . $ky . '">
                <td>
                    <input type="hidden" name="sod_size_id[]" id="sod_size" value="'. $roww['id'] .'">
                    <input type="hidden" name="variation_value[]" id="variation_value" value="'. $roww['variation_value'] .'">
                    <input type="hidden" name="" id="maxQtty' . $ky . '" value="'. $mmq .'">
                    '. variation_value($roww['variation_value']) .'</td>
                <td>' . $exs . ' <i class="icon-copy fa fa-list-alt float-right" aria-hidden="true" onclick="referenceModal(' . $mmq . ',' . $exs . ',' . $ky . ')"></i></td>
                <td class="text-center"><input type="hidden" name="order_qty[]" id="order_qty' . $ky . '" class="form-control" value="' . ($exs) . '">' . $mmq . '</td>
                <td><input type="number" name="cutting_qty[]" id="cutting_qty' . $ky . '" class="form-control totcutting' . $ky . '" data-key="' . $ky . '" onkeyup="pcsss(' . $ky . ')" value="0" readonly></td>
                <td><input type="number" name="pcs_per_bundle[]" id="pcs_per_bundle' . $ky . '" class="form-control pcs_per_bundle" onkeyup="pcsss(' . $ky . ')" placeholder="Pcs Per Bundle" required></td>
                <td><input type="number" name="noOfbundle[]" id="noOfbundle' . $ky . '" class="form-control noOfbundle" onkeyup="pcsss(' . $ky . ')" value="" placeholder="No Of Bundle"></td>
                <td><input type="number" readonly name="frombundleno[]" id="frombundleno' . $ky . '" data-id="' . $ky . '" class="form-control frombundleno" value="' . ($tooo + 1) . '" ></td>
                <td style="display:flex"><input type="number" readonly name="tobundleno[]" id="tobundleno' . $ky . '" class="form-control tobundleno" onkeyup="verify_max(' . $ky . ')" value="' . ($tooo) . '"  style="width:80%"> 
                    <span class="border-success rounded"> <i class="icon-copy fa fa-plus" aria-hidden="true" id="addIcon' . $ky . '" onclick="trOf(' . $oc . ')" title="Clone" style="padding: 9px;border: 1px solid #ccc;"></i></span>
                </td>
                
        </tr>';
            $ky++;
        }
        
    } else if($sqql['plan_type']=='Partial' && $sqql['partial_type']=='part') {
        
        $yql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM cutting_partial_planning WHERE sod_part = '". $_REQUEST['sod_part_id'] ."'"));
        
        if($yql['plan_for']=='Supplier') {
            
            
            $data['err'][] = 1;
            $data['notif'][] = 'info';
            if($yql['plan_for_to']<=0) {
                $data['message'][] = 'This Combo planning not completed';
            } else {
                $data['message'][] = 'This Combo Part planned for the supplier '. supplier_name($yql['plan_for_to']);
            }
        } else if($yql['plan_for']=='Unit' && $yql['plan_for_to']!= $logUnit) {
            
            $data['err'][] = 1;
            $data['notif'][] = 'info';
            if($yql['plan_for_to']<=0) {
                $data['message'][] = 'This Combo planning not completed';
            } else {
                $data['message'][] = 'This Combo Part planned for '. company_name($yql['plan_for_to']);
            }
        } else if($yql['plan_for']=='Unit' && $yql['plan_for_to']== $logUnit) {
            
            $bbk = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = '". $_REQUEST['combo_id'] ."'");
            
            $ky = 0;
            while($roww = mysqli_fetch_array($bbk)) {
                
                $exs = $roww['size_qty'] + round(($roww['size_qty'] / 100) * $roww['excess_per']);
                $smm = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE sod_size = '". $roww['id'] ."' AND sod_part = '". $_REQUEST['sod_part_id'] ."'"));
                $to_bno = mysqli_fetch_array(mysqli_query($mysqli, "SELECT to_bno FROM cutting_barcode WHERE sod_part = '". $_REQUEST['sod_part_id'] ."' ORDER BY id DESC"));
                
                $tooo = $to_bno['to_bno'];
                $mmq = $smm['pcs_per_bundle'] ? $smm['pcs_per_bundle'] : 0;
                
                $oc = $ky .", '". variation_value($roww['variation_value']) ."', '". $mmq ."',". $roww['variation_value'] .",". $roww['size_qty'] .",". $exs .",". $roww['id'];
                
                $data['lay_div'][] = '<tr id="trOf' . $ky . '">
                    <td>
                        <input type="hidden" name="sod_size_id[]" id="sod_size" value="'. $roww['id'] .'">
                        <input type="hidden" name="variation_value[]" id="variation_value" value="'. $roww['variation_value'] .'">
                        <input type="hidden" name="" id="maxQtty' . $ky . '" value="'. $mmq .'">
                        '. variation_value($roww['variation_value']) .'</td>
                    <td><input type="hidden" name="order_qty[]" id="order_qty' . $ky . '" class="form-control" value="' . ($exs) . '">' . $mmq . '</td>
                    <td>' . $exs . ' <i class="icon-copy fa fa-list-alt float-right" aria-hidden="true" onclick="referenceModal(' . $mmq . ',' . $exs . ',' . $ky . ')"></i></td>
                    <td><input type="number" name="cutting_qty[]" id="cutting_qty' . $ky . '" class="form-control totcutting' . $ky . '" data-key="' . $ky . '" onkeyup="pcsss(' . $ky . ')" value="0" readonly></td>
                    <td><input type="number" name="pcs_per_bundle[]" id="pcs_per_bundle' . $ky . '" class="form-control pcs_per_bundle" onkeyup="pcsss(' . $ky . ')" placeholder="Pcs Per Bundle" required></td>
                    <td><input type="number" name="noOfbundle[]" id="noOfbundle' . $ky . '" class="form-control noOfbundle" onkeyup="pcsss(' . $ky . ')" value="" placeholder="No Of Bundle"></td>
                    <td><input type="number" readonly name="frombundleno[]" id="frombundleno' . $ky . '" data-id="' . $ky . '" class="form-control frombundleno" value="' . ($tooo + 1) . '" ></td>
                    <td style="display:flex"><input type="number" readonly name="tobundleno[]" id="tobundleno' . $ky . '" class="form-control tobundleno" onkeyup="verify_max(' . $ky . ')" value="' . ($tooo) . '"  style="width:80%"> 
                        <span class="border-success rounded"> <i class="icon-copy fa fa-plus" aria-hidden="true" id="addIcon' . $ky . '" onclick="trOf(' . $oc . ')" title="Clone" style="padding: 9px;border: 1px solid #ccc;"></i></span>
                    </td>
                    
            </tr>';
                $ky++;
            }
            
            $data['err'][] = 0;
            $data['notif'][] = '';
            $data['message'][] = '';
        }
        
    } else if($sqql['plan_type']=='Partial' && $sqql['partial_type']=='size') {
            
        // $bbk = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = '". $_REQUEST['combo_id'] ."'");
        
        $hbk = "SELECT a.* ";
        $hbk .= " FROM sod_size a ";
        $hbk .= " LEFT JOIN cutting_partial_planning b ON a.id = b.sod_size ";
        $hbk .= " WHERE a.sod_combo = '". $_REQUEST['combo_id'] ."' AND b.sod_part = '". $_REQUEST['sod_part_id'] ."' AND b.plan_for = 'Unit' AND plan_for_to = '". $logUnit ."' ORDER BY b.id ASC ";
        
        $bbk = mysqli_query($mysqli, $hbk);
        if(mysqli_num_rows($bbk)>0) {
            $ky = 0;
            while($roww = mysqli_fetch_array($bbk)) {
                
                $exs = $roww['size_qty'] + round(($roww['size_qty'] / 100) * $roww['excess_per']);
                $smm = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE sod_size = '". $roww['id'] ."' AND sod_part = '". $_REQUEST['sod_part_id'] ."'"));
                $to_bno = mysqli_fetch_array(mysqli_query($mysqli, "SELECT to_bno FROM cutting_barcode WHERE sod_part = '". $_REQUEST['sod_part_id'] ."' ORDER BY id DESC"));
                
                $tooo = $to_bno['to_bno'];
                $mmq = $smm['pcs_per_bundle'] ? $smm['pcs_per_bundle'] : 0;
                
                $oc = $ky .", '". variation_value($roww['variation_value']) ."', '". $mmq ."',". $roww['variation_value'] .",". $roww['size_qty'] .",". $exs .",". $roww['id'];
                
                $data['lay_div'][] = '<tr id="trOf' . $ky . '">
                    <td>
                        <input type="hidden" name="sod_size_id[]" id="sod_size" value="'. $roww['id'] .'">
                        <input type="hidden" name="variation_value[]" id="variation_value" value="'. $roww['variation_value'] .'">
                        <input type="hidden" name="" id="maxQtty' . $ky . '" value="'. $mmq .'">
                        '. variation_value($roww['variation_value']) .'</td>
                    <td><input type="hidden" name="order_qty[]" id="order_qty' . $ky . '" class="form-control" value="' . ($exs) . '">' . $mmq . '</td>
                    <td>' . $exs . ' <i class="icon-copy fa fa-list-alt float-right" aria-hidden="true" onclick="referenceModal(' . $mmq . ',' . $exs . ',' . $ky . ')"></i></td>
                    <td><input type="number" name="cutting_qty[]" id="cutting_qty' . $ky . '" class="form-control totcutting' . $ky . '" data-key="' . $ky . '" onkeyup="pcsss(' . $ky . ')" value="0" readonly></td>
                    <td><input type="number" name="pcs_per_bundle[]" id="pcs_per_bundle' . $ky . '" class="form-control pcs_per_bundle" onkeyup="pcsss(' . $ky . ')" placeholder="Pcs Per Bundle" required></td>
                    <td><input type="number" name="noOfbundle[]" id="noOfbundle' . $ky . '" class="form-control noOfbundle" onkeyup="pcsss(' . $ky . ')" value="" placeholder="No Of Bundle"></td>
                    <td><input type="number" readonly name="frombundleno[]" id="frombundleno' . $ky . '" data-id="' . $ky . '" class="form-control frombundleno" value="' . ($tooo + 1) . '" ></td>
                    <td style="display:flex"><input type="number" readonly name="tobundleno[]" id="tobundleno' . $ky . '" class="form-control tobundleno" onkeyup="verify_max(' . $ky . ')" value="' . ($tooo) . '"  style="width:80%"> 
                        <span class="border-success rounded"> <i class="icon-copy fa fa-plus" aria-hidden="true" id="addIcon' . $ky . '" onclick="trOf(' . $oc . ')" title="Clone" style="padding: 9px;border: 1px solid #ccc;"></i></span>
                    </td>
                    
            </tr>';
                $ky++;
            }
            $data['err'][] = 0;
            $data['notif'][] = '';
            $data['message'][] = '';
        } else {
            $data['err'][] = 1;
            $data['notif'][] = 'info';
            $data['message'][] = 'No size assigned for '. company_name($logUnit) .'!';
        }
    }
    
    $h = "SELECT * FROM cutting_barcode WHERE style = '". $_REQUEST['styleList'] ."' AND sod_part = '". $_REQUEST['sod_part_id'] ."' ORDER BY id DESC";
    
    $qyy = mysqli_query($mysqli, $h);
    $num = mysqli_num_rows($qyy);
    $row = mysqli_fetch_array($qyy);
    
    $data['lay_ref'][] = $num+1;
    
    
    
    if ($num > 0) {
        $bno = $row['to_bno'];
    } else {
        $bno = 0;
    }
    $bno = $bno + 1;
    
    
    // foreach (json_decode($asd['size_detail']) as $ky => $val) {
    //     $ex1 = explode(',,', $val);
    //     $ex2 = explode('=', $ex1[0]);
    //     $qtty = explode('=', $ex1[1]);
    //     $exss = explode('=', $ex1[2]);
        
    //     $var = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM variation_value WHERE id = '" . $ex2[1] . "' "));
        
    //     $sqql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE cutting_barcode_id = '" . $_REQUEST['cutting_barcode_id'] . "' ORDER BY id DESC"));
        
    //     $summ = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as sumQty FROM bundle_details WHERE cutting_barcode_id = '" . $_REQUEST['cutting_barcode_id'] . "' AND variation_value='" . $ex2[1] . "' ORDER BY id DESC"));
        
    //     $mmq = $summ['sumQty'] ? $summ['sumQty'] : 0;
        
    //     $tooo = $sqql['bundle_number'] ? $sqql['bundle_number'] : 0;
        
    //     $exs = $qtty[1] + round(($qtty[1] / 100) * $exss[1]);
        
    //     $oc = $ky . ", '" . $var['type'] . "', '" . $mmq . "'," . $var['id'] . "," . $qtty[1] . "," . $exs;
        
    //     $data['html'][] = '<tr id="trOf' . $ky . '">
    //             <td>
    //             <input type="hidden" name="variation_value[]" id="variation_value" value="' . $var['id'] . '">
    //             <input type="hidden" name="" id="maxQtty' . $ky . '" value="' . $mmq . '">
    //             ' . $var['type'] . '</td>
                
    //             <td><input type="hidden" name="order_qty[]" id="order_qty' . $ky . '" class="form-control" value="' . ($exs) . '">
    //             ' . $mmq . ' 
    //             </td>
                
    //             <td>' . $exs . '
    //             <i class="icon-copy fa fa-list-alt" aria-hidden="true" onclick="referenceModal(' . $mmq . ',' . $exs . ',' . $ky . ')"></i>
    //             </td>
                
    //             <td><input type="number" name="cutting_qty[]" id="cutting_qty' . $ky . '" class="form-control totcutting' . $ky . '" data-key="' . $ky . '" onkeyup="pcsss(' . $ky . ')" value="0" readonly></td>
    //             <td><input type="number" name="pcs_per_bundle[]" id="pcs_per_bundle' . $ky . '" class="form-control pcs_per_bundle" onkeyup="pcsss(' . $ky . ')" value="" required></td>
                
    //             <td><input type="number" name="noOfbundle[]" id="noOfbundle' . $ky . '" class="form-control noOfbundle" onkeyup="pcsss(' . $ky . ')" value=""  ></td>
                
    //             <td><input type="number" readonly name="frombundleno[]" id="frombundleno' . $ky . '" data-id="' . $ky . '" class="form-control frombundleno" value="' . ($tooo + 1) . '" ></td>
    //             <td style="display:flex"><input type="number" readonly name="tobundleno[]" id="tobundleno' . $ky . '" class="form-control tobundleno" onkeyup="verify_max(' . $ky . ')" value="' . ($tooo) . '"  style="width:80%"> 
                
    //           <span class="border-success rounded"> <i class="icon-copy fa fa-plus" aria-hidden="true" id="addIcon' . $ky . '" onclick="trOf(' . $oc . ')" title="Clone" style="padding: 9px;border: 1px solid #ccc;"></i></span>
               
    //             </td>
                
    //     </tr>';
        
    //     $bno++;
    // }
    
    echo json_encode($data);

} else if(isset($_REQUEST['get_combo_details'])) {

    if (!empty($_REQUEST['combo_id'])) {
        
        $ass = "SELECT id, part_id, color_id, combo_id FROM sod_part WHERE sod_combo = '". $_REQUEST['combo_id'] ."' ORDER BY id ASC";
        $asss = mysqli_query($mysqli, $ass);
        $x = 1;
        $data['option'][] = '<option value="">select</otion>';
        while ($asd = mysqli_fetch_array($asss)) {
            
            // $bnj = ($_REQUEST['oldstyle'] == $asd['id']) ? 'selected' : '';
            
            $data['option'][] = '<option value="' . $asd['id'] . '">' . part_name($asd['part_id']) . ' || ' . color_name($asd['color_id']) . '</otion>';
        }
    }
    
    echo json_encode($data);

} else if(isset($_REQUEST['get_combo_names'])) {

    if (!empty($_REQUEST['id'])) {
        
        $fh = mysqli_query($mysqli, "SELECT * FROM process_planing WHERE style_id='" . $_REQUEST['id'] . "' AND process_id = 1");
        $numm = mysqli_num_rows($fh);
        $result = mysqli_fetch_array($fh);
        
        if($numm>0) {
            $plan_type = $result['plan_type'];
            
            if($plan_type=='Full' && $result['process_type']=='unit' && $result['processing_unit_id']==$logUnit) {
                
                $ass = "SELECT id, combo_id FROM sod_combo WHERE sales_order_detail_id='" . $_REQUEST['id'] . "' ORDER BY id ASC";
                
                $asss = mysqli_query($mysqli, $ass);
                $x = 1;
                $data['option'][] = '<option value="">Select Combo</otion>';
                while ($asd = mysqli_fetch_array($asss)) {
                    
                    $data['option'][] = '<option value="' . $asd['id'] . '">' . color_name($asd['combo_id']) . '</otion>';
                }
                
                $data['err'][] = 0;
                $data['notif'][] = 'success';
                $data['message'][] = '';
            } else if($plan_type=='Full' && $result['process_type']=='unit' && $result['processing_unit_id']!=$logUnit) {
                
                $data['err'][] = 1;
                $data['notif'][] = 'info';
                $data['message'][] = 'This Style Planned for '. company_name($result['processing_unit_id']) .'!';
                
            } else if($plan_type=='Full' && $result['process_type']=='supplier') {
                $data['err'][] = 1;
                $data['notif'][] = 'info';
                $data['message'][] = 'This Style Planned for the supplier '. supplier_name($result['supplier_id']) .'!';
                
            } else if($plan_type=='Partial') {
                
                $ass = "SELECT id, combo_id FROM sod_combo WHERE sales_order_detail_id='" . $_REQUEST['id'] . "' ORDER BY id ASC";
                
                $asss = mysqli_query($mysqli, $ass);
                $x = 1;
                $data['option'][] = '<option value="">Select Combo</otion>';
                while ($asd = mysqli_fetch_array($asss)) {
                    
                    $data['option'][] = '<option value="' . $asd['id'] . '">' . color_name($asd['combo_id']) . '</otion>';
                }
                
                $data['err'][] = 0;
                $data['notif'][] = 'success';
                $data['message'][] = '';
                
                // $qry = "SELECT * FROM cutting_partial_planning WHERE plan_for='Unit' AND plan_for_to='". $logUnit ."' AND type = 'combo_part' AND process_planing_id = '". $result['id'] ."'";
            }   
            
            
            
        } else {
            $data['err'][] = 1;
            $data['notif'][] = 'info';
            $data['message'][] = 'Production Planning Not Started!';
        }
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getStyleNoforProcess'])) {

    if (!empty($_REQUEST['id'])) {
        $ass = "SELECT a.* FROM sales_order_detalis a WHERE a.sales_order_id='" . $_REQUEST['id'] . "' ";
        $asss = mysqli_query($mysqli, $ass);
        $x = 1;
        print '<option value="">Select</otion>';
        while ($asd = mysqli_fetch_array($asss)) {
            
            // $bnj = ($_REQUEST['oldstyle'] == $asd['style_id']) ? 'selected' : '';
            
            print '<option value="' . $asd['id'] . '" >' . $asd['style_no'] . '</otion>';
        }
    }

} else if(isset($_REQUEST['getPartNoforProcess'])) {

    if (!empty($_REQUEST['id'])) {

        $ass = "SELECT a.* FROM sales_order_detalis a WHERE a.id='" . $_REQUEST['id'] . "'";
        $asd = mysqli_fetch_array(mysqli_query($mysqli, $ass));

        $col = json_decode($asd['part_detail']);
        print '<option value="">Select</option>';
        foreach ($col as $key => $vyl) {
            $aa1 = explode(',,', $vyl);
            $pp1 = explode('=', $aa1[0]);
            $cc1 = explode('=', $aa1[1]);

            $iup = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM part WHERE id=" . $pp1[1]));
            $colR = mysqli_fetch_array(mysqli_query($mysqli, "SELECT color_name, id FROM color WHERE id=" . $cc1[1]));

            print '<option value="' . $iup['id'].' - '.$colR['id'] . '" >' . $iup['part_name'] . ' - '. $colR['color_name'] .'</option>';

        }
        exit;
    }

} else if(isset($_REQUEST['getPartNoforProcess'])) {

    // $iom = mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE in_proseccing IS NULL AND boundle_qr IS NOT NULL");

    // while ($fetch = mysqli_fetch_array($iom)) {
    //     $v = (in_array($fetch['id'], explode(',', $sql['boundle_id']))) ? 'selected' : '';
    //     print '<option value="' . $fetch['id'] . '" ' . $v . '>' . $fetch['boundle_qr'] . '</option>';
    // }


} else if(isset($_REQUEST['getbundleNoProcess'])) {
    
    $rv = explode(' - ',$_REQUEST['prtno']);
    
    if($_REQUEST['type']=='sewing_input') {
        $iom = mysqli_query($mysqli, "SELECT a.id,a.bundle_number FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
        WHERE a.boundle_qr IS NOT NULL AND b.order_id='" . $_REQUEST['ordno'] . "' AND b.style='" . $_REQUEST['stylno'] . "' AND b.part='" . $rv[0] . "' AND b.color = '". $rv[1] ."' AND (a.in_proseccing IS NULL OR (a.in_proseccing='yes' AND a.complete_processing='yes')) AND in_sewing IS NULL ");
    } else if($_REQUEST['type']=='process_outward') {
        $iom = mysqli_query($mysqli, "SELECT a.id,a.bundle_number FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
        WHERE ((a.in_proseccing IS NULL AND a.complete_processing IS NULL) OR (a.in_proseccing = 'yes' AND a.complete_processing = 'yes')) AND a.boundle_qr IS NOT NULL AND b.order_id='" . $_REQUEST['ordno'] . "' AND b.style='" . $_REQUEST['stylno'] . "' AND b.part='" . $rv[0] . "' AND b.color = '". $rv[1] ."'");
    } else if($_REQUEST['type']=='sewing_output') {
        $iom = mysqli_query($mysqli, "SELECT a.id,a.bundle_number,pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
        WHERE a.in_sewing = 'yes' and complete_sewing IS NULL AND b.order_id='" . $_REQUEST['ordno'] . "' AND b.style='" . $_REQUEST['stylno'] . "' AND b.part='" . $rv[0] . "' AND b.color = '". $rv[1] ."'");
    } else if($_REQUEST['type']=='checking_list') {
        $iom = mysqli_query($mysqli, "SELECT a.id,a.bundle_number FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
        WHERE a.complete_sewing = 'yes' AND checking_complete IS NULL AND b.order_id='" . $_REQUEST['ordno'] . "' AND b.style='" . $_REQUEST['stylno'] . "' AND b.part='" . $rv[0] . "' AND b.color = '". $rv[1] ."'");
    }
    
    print '<option value="select_all">Select All</option>';
    if($_REQUEST['type']=='sewing_output') {
        while ($fetch = mysqli_fetch_array($iom)) {
            
            if($_REQUEST['scanType']=='bundle') {
                print '<option value="' . $fetch['id'] . '" '. $_REQUEST['selected'] .'>' . $fetch['bundle_number'] . '</option>';
            } else if($_REQUEST['scanType']=='piece') {
                print '<optgroup label="Bundle No : '. $fetch['bundle_number'] .'">';
                for($p=1; $p <= $fetch['pcs_per_bundle']; $p++) {
                    print '<option value="' . $fetch['id'] .'-'. $p .'">'. $fetch['bundle_number'] .'-'. $p .'</option>';
                }
                print '</optgroup>';
            }
        } 
    } else {
        while ($fetch = mysqli_fetch_array($iom)) {
            // $v = (in_array($fetch['id'], explode(',', $sql['boundle_id']))) ? 'selected' : '';
            print '<option value="' . $fetch['id'] . '" '. $_REQUEST['selected'] .'>' . $fetch['bundle_number'] . '</option>';
        }
    }

} else if(isset($_REQUEST['getPartColor'])) {
    $ass = "SELECT a.* FROM sales_order_detalis a WHERE a.id='" . $_REQUEST['id'] . "'";
    $asd = mysqli_fetch_array(mysqli_query($mysqli, $ass));

    $col = json_decode($asd['part_detail']);

    $part_key = explode('--', $_REQUEST['part']);

    $aa1 = explode(',,', $col[$part_key[1]]);
    $aa12 = explode('=', $aa1[1]);

    $iup = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM color WHERE id=" . $aa12[1]));

    $data['color'][] = '<option value="' . $aa12[1] . '" selected>' . $iup['color_name'] . '</option>';

    $qryz = mysqli_query($mysqli, "SELECT * FROM cutting_barcode WHERE ref_id LIKE '%REF-%' ORDER BY id DESC");
    $sqql = mysqli_fetch_array($qryz);
    $numm = mysqli_num_rows($qryz);
    if ($numm == 0) {
        $code = 'REF-1';
    } else {
        $ex = explode('-', $sqql['ref_id']);

        $value = $ex[1];
        $intValue = (int) $value;
        $newValue = $intValue + 1;
        // $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);

        $code = $ex[0] . '-' . $newValue;
    }

    $val = array(
        'date' => $_REQUEST['date'],
        'order_id' => $_REQUEST['order_id'],
        'ref_id' => $code,
        'style' => $_REQUEST['styleList'],
        'part' => $part_key[0],
        'color' => $aa12[1],
    );

    $rww = mysqli_query($mysqli, "SELECT * FROM cutting_barcode WHERE order_id='" . $_REQUEST['order_id'] . "' AND style='" . $_REQUEST['styleList'] . "' AND part='" . $part_key[0] . "' AND color='" . $aa12[1] . "'");
    $sqll = mysqli_num_rows($rww);
    $ftch = mysqli_fetch_array($rww);

    if ($sqll == 0) {
        $qry = Insert('cutting_barcode', $val);
        $data['inid'][] = mysqli_insert_id($mysqli);
    } else {
        $data['inid'][] = $ftch['id'];
    }
    echo json_encode($data);

} else if(isset($_REQUEST['getBundledet'])) {
    
    if($_REQUEST['id'] != 'null') {
        $ass = "SELECT a.* FROM sales_order_detalis a WHERE a.id='" . $_REQUEST['id'] . "'";
        $asd = mysqli_fetch_array(mysqli_query($mysqli, $ass));
        $x = 1;
        
        $data['part'][] = '<option value="">Select</option>';
        
        foreach (json_decode($asd['part_detail']) as $key => $value) {
    
    
            $aa1 = explode(',,', $value);
            $aa12 = explode('=', $aa1[0]);
    
            $sqp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM part WHERE id=" . $aa12[1]));
            
            if($_REQUEST['cutting_barcode_id']!="") {
                $ctng = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM cutting_barcode WHERE id=" . $_REQUEST['cutting_barcode_id']));
                $ioo = ($aa12[1] == $ctng['part']) ? 'selected' : '';
            } else {
                $ioo = '';
            }
            
            $data['part'][] = '<option value="' . $aa12[1] . '--' . $key . '" ' . $ioo . '>' . $sqp['part_name'] . '</option>';
        }
        
        if($_REQUEST['cutting_barcode_id']!= "") {
            
        $bnoss = mysqli_fetch_array(mysqli_query($mysqli, "SELECT bundle_number as bno FROM bundle_details WHERE cutting_barcode_id = '" . $_REQUEST['cutting_barcode_id'] . "' ORDER BY id DESC"));
            $bno = $bnoss['bno'];
        } else {
            $bno = 0;
        }
        $bno = $bno + 1;
    // print $bno; exit;
        foreach (json_decode($asd['size_detail']) as $ky => $val) {
            $ex1 = explode(',,', $val);
            $ex2 = explode('=', $ex1[0]);
            $qtty = explode('=', $ex1[1]);
            $exss = explode('=', $ex1[2]);
    
            $var = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM variation_value WHERE id = '" . $ex2[1] . "' "));
    
            $sqql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE cutting_barcode_id = '" . $_REQUEST['cutting_barcode_id'] . "' ORDER BY id DESC"));
    
            $summ = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as sumQty FROM bundle_details WHERE cutting_barcode_id = '" . $_REQUEST['cutting_barcode_id'] . "' AND variation_value='" . $ex2[1] . "' ORDER BY id DESC"));
    
            $mmq = $summ['sumQty'] ? $summ['sumQty'] : 0;
    
            $tooo = $sqql['bundle_number'] ? $sqql['bundle_number'] : 0;
    
            $exs = $qtty[1] + round(($qtty[1] / 100) * $exss[1]);
    
            $oc = $ky . ", '" . $var['type'] . "', '" . $mmq . "'," . $var['id'] . "," . $qtty[1] . "," . $exs;
    
            $data['html'][] = '<tr id="trOf' . $ky . '">
                    <td>
                    <input type="hidden" name="variation_value[]" id="variation_value" value="' . $var['id'] . '">
                    <input type="hidden" name="" id="maxQtty' . $ky . '" value="' . $mmq . '">
                    ' . $var['type'] . '</td>
    
                    <td><input type="hidden" name="order_qty[]" id="order_qty' . $ky . '" class="form-control" value="' . ($exs) . '">
                    ' . $mmq . ' 
                    </td>
    
                    <td>' . $exs . '
                    <i class="icon-copy fa fa-list-alt" aria-hidden="true" onclick="referenceModal(' . $mmq . ',' . $exs . ',' . $ky . ')"></i>
                    </td>
    
                    <td><input type="number" name="cutting_qty[]" id="cutting_qty' . $ky . '" class="form-control totcutting' . $ky . '" data-key="' . $ky . '" onkeyup="pcsss(' . $ky . ')" value="0" readonly></td>
                    <td><input type="number" name="pcs_per_bundle[]" id="pcs_per_bundle' . $ky . '" class="form-control pcs_per_bundle" onkeyup="pcsss(' . $ky . ')" value="" required></td>
    
                    <td><input type="number" name="noOfbundle[]" id="noOfbundle' . $ky . '" class="form-control noOfbundle" onkeyup="pcsss(' . $ky . ')" value=""  ></td>
    
                    <td><input type="number" readonly name="frombundleno[]" id="frombundleno' . $ky . '" data-id="' . $ky . '" class="form-control frombundleno" value="' . ($tooo + 1) . '" ></td>
                    <td style="display:flex"><input type="number" readonly name="tobundleno[]" id="tobundleno' . $ky . '" class="form-control tobundleno" onkeyup="verify_max(' . $ky . ')" value="' . ($tooo) . '"  style="width:80%"> 
    
                   <span class="border-success rounded"> <i class="icon-copy fa fa-plus" aria-hidden="true" id="addIcon' . $ky . '" onclick="trOf(' . $oc . ')" title="Clone" style="padding: 9px;border: 1px solid #ccc;"></i></span>
                   
                    </td>
    
            </tr>';
    
            $bno++;
        }
        
    } else {
        $data['part'][] = '';
    }
    // print_r($data['part']);exit;
    echo json_encode($data);

} else if(isset($_REQUEST['rateHistory'])) {

    $data['div'][] = '<label>New Rate : </label>';
    $data['div'][] = '<input type="text" class="form-control" name="upd_price" id="upd_price" placeholder="New Rate">';
    $data['div'][] = '<input type="hidden" name="sub_process_idd" id="sub_process_idd" value="' . $_REQUEST['id'] . '">';

    $qry = mysqli_query($mysqli, "SELECT * FROM sp_rate_history WHERE sub_process='" . $_REQUEST['id'] . "' ORDER BY id DESC");
    if (mysqli_num_rows($qry) > 0) {
        while ($mys = mysqli_fetch_array($qry)) {
            $data['tbody'][] = '<tr>';
            $data['tbody'][] = '<td>' . $mys['old_price'] . '</td>';
            $data['tbody'][] = '<td>' . $mys['new_price'] . '</td>';
            $data['tbody'][] = '<td>' . $mys['created_date'] . '</td>';
            $data['tbody'][] = '</tr>';
        }
    } else {
        $data['tbody'][] = '';
    }
    echo json_encode($data);

} else if(isset($_REQUEST['sorderfile'])) {

    $nkm = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order WHERE id=" . $_REQUEST['id']));

    if ($nkm['order_image'] != "") {
        foreach (explode(',', $nkm['order_image']) as $img) {
            print '<tr>';
            print '<td>' . $img . '</td>';
            print '<td><a href="download.php?f=uploads/so_img/' . $nkm['order_code'] . '/' . $img . '" target="_blank"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Click</a></td>';
            print '</tr>';
        }
    }
} else if(isset($_REQUEST['addProcess'])) {

    $asd = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM order_process WHERE budget_id='" . $_REQUEST['id'] . "' AND budget_type = '" . $_REQUEST['table'] . "'"));

    print '<div class="col-md-12">';
    print '<label for="">Type :</label>';
    print '<input type="hidden" name="budget_id" id="budget_id" value="' . $_REQUEST['id'] . '">';
    print '<input type="hidden" name="budget_type" id="budget_type" value="' . $_REQUEST['table'] . '">';
    print '<input type="hidden" name="process_name" id="process_name" value="' . $_REQUEST['process'] . '">';
    print '<input type="hidden" name="so_id" id="so_id" value="' . $_REQUEST['so_id'] . '">';
    print '<input type="hidden" name="order_process_id" id="order_process_id" value="' . $asd['id'] . '">';
    $ttyp = array(
        'Inward' => 'Inward Process',
        'Outward' => 'Outward Process',
    );
    print '<select name="type" id="type" class="form-control">';
    print '<option value="">Select</option>';
    foreach ($ttyp as $key => $value) {

        $c = ($asd['type'] == $key) ? 'selected' : '';

        print '<option value="' . $key . '" ' . $c . '>' . $value . '</option>';
    }
    print '</select>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label for="">Supplier List :</label>';
    print '<select name="supplier_name" id="supplier_name" class="form-control">';
    print select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $asd['supplier'], '', '');
    print '</select>';
    print '</div>';

    print '<div class="col-md-12 d-none">';
    print '<label for="">Process :</label>';
    print '</div>';

} else if(isset($_REQUEST['validateQR'])) {
    
    
    ////////////////////////////////////////////////////////////////////////////
    //
    if($_REQUEST['type']=='sewing_input') {
        $fild = "SELECT * FROM bundle_details WHERE boundle_qr = '" . $_REQUEST['qr'] . "' AND in_sewing IS NULL AND ((in_proseccing = 'yes' AND complete_processing = 'yes') OR (in_proseccing IS NULL AND complete_processing IS NULL))"; 
    } else if($_REQUEST['type']=='sewing_output') {
        $fild = "SELECT * FROM bundle_details WHERE in_sewing = 'yes' AND complete_sewing IS NULL AND boundle_qr = '" . $_REQUEST['qr'] . "'"; 
    } else if($_REQUEST['type']=='process_outward') {
        $fild = "SELECT * FROM bundle_details WHERE order_id = '". $_REQUEST['order_id'] ."' AND ((in_proseccing IS NULL AND complete_processing IS NULL) OR (in_proseccing = 'yes' AND complete_processing = 'yes')) AND ((in_sewing='yes' AND complete_sewing='yes') OR (in_sewing IS NULL AND complete_sewing IS NULL)) AND boundle_qr = '" . $_REQUEST['qr'] . "'"; 
    } else if($_REQUEST['type']=='checking_list') {
        $fild = "SELECT * FROM bundle_details WHERE complete_sewing = 'yes' AND checking_complete IS NULL AND boundle_qr = '" . $_REQUEST['qr'] . "'"; 
    }
    
    // if($_REQUEST['type']=='process_outward') {
    //     $tyuu = "SELECT * FROM bundle_details WHERE order_id = '". $_REQUEST['order_id'] ."' AND ((in_proseccing IS NULL AND complete_processing IS NULL) OR (in_proseccing = 'yes' AND complete_processing = 'yes')) AND boundle_qr = '" . $_REQUEST['qr'] . "'";
    //     print 'poda i am';
    // } else {
        $tyuu = mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE boundle_qr = '" . $_REQUEST['qr'] . "'");
    // }
    
    // $HB = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE boundle_qr = '". $_REQUEST['qr'] ."'"));
    
    
    $notii = mysqli_fetch_array($tyuu);
    
    $num_notii = mysqli_num_rows($tyuu);
    
    
    if($notii['order_id']!=$_REQUEST['order_id']) {
        $message = 'Bundle Not in this BO. ('. $_REQUEST['qr'] .')';
    
    } else if($num_notii==0) {
        $message = 'Invalid QR';
    } else if($notii['checking_complete']=='yes') {
        $message = 'Checking Completed. ('. $notii['boundle_qr'] .')';
    
    } else if($notii['in_proseccing']=='yes' && $notii['complete_processing']=='yes' && $notii['in_sewing']=='yes' && $notii['complete_sewing']=='yes' ) {
        $message = 'Bundle Sewing Completed. ('. $notii['boundle_qr'] .')';
    
    } else if($notii['in_proseccing']=='yes' && $notii['complete_processing']=='yes' && $notii['in_sewing']=='yes' && $notii['complete_sewing']==NULL ) {
        $message = 'Bundle In Sewing. ('. $notii['boundle_qr'] .')';
    
    } else if($notii['in_proseccing']=='yes' && $notii['complete_processing']=='yes' && $notii['in_sewing']==NULL && $notii['complete_sewing']==NULL ) {
        if($_REQUEST['type']=='sewing_output') {
            $nm  = 'Not in Sewing.';
        } else {
            $nm  = '';   
        }
        $message = 'Bundle Inwarded. '. $nm .' ('. $notii['boundle_qr'] .')';
    
    } else if($notii['in_proseccing']=='yes' && ( $notii['complete_processing']==NULL || $notii['in_sewing']==NULL || $notii['complete_sewing']==NULL )) {
        if($_REQUEST['type']=='process_outward') {
            $message = 'Bundle Already Scanned. ('. $notii['boundle_qr'] .')';
        } else {
            $message = 'Bundle In Processing. ('. $notii['boundle_qr'] .')';
        }
    } else if($notii['in_proseccing']==NULL && ( $notii['complete_processing']==NULL && $notii['in_sewing']=='yes' && $notii['complete_sewing']==NULL )) {
        if($_REQUEST['type']=='sewing_input') {
            $message = 'Already Scanned. ('. $notii['boundle_qr'] .')';
        } else {
            $message = 'Bundle In Sewing. ('. $notii['boundle_qr'] .')';
        }
    } else if($notii['in_proseccing']==NULL && $notii['complete_processing']==NULL && $notii['in_sewing']==NULL && $notii['complete_sewing']==NULL ) {
        $message = 'Bundle not in Process or Sewing. ('. $notii['boundle_qr'] .')';
    
    } else {
        if($_REQUEST['type']=='sewing_output') {
            if($notii['complete_sewing']=='yes') {
                $message = 'Sewing Completed. ('. $_REQUEST['qr'] .')';
            } else {
                $message = 'Sewing Not Completed. ('. $_REQUEST['qr'] .')';
            }
        } else if($_REQUEST['type']=='sewing_input') {
            if($notii['in_sewing']=='yes') {
                $message = 'Already Scanned. ('. $_REQUEST['qr'] .')';
            }
        } else {
            $message = 'Already Scanned or Invalid ('. $_REQUEST['qr'] .')';
        }
    }
    //
    ////////////////////////////////////////////////////////////////////////////
    
    
    
    
    
    
    
    // if($_REQUEST['type']=='sewing_input') {
    //     $fild = "SELECT * FROM bundle_details WHERE in_sewing IS NULL AND boundle_qr = '" . $_REQUEST['qr'] . "'";
    // } else if($_REQUEST['type']=='sewing_output') {
    //     $fild = "SELECT * FROM bundle_details WHERE in_sewing = 'yes' AND complete_sewing IS NULL AND boundle_qr = '" . $_REQUEST['qr'] . "'";
    // } else if($_REQUEST['type']=='process_outward') {
    //     $fild = "SELECT * FROM bundle_details WHERE in_proseccing IS NULL AND boundle_qr = '" . $_REQUEST['qr'] . "'";
    // }

    $jn = mysqli_query($mysqli, $fild);
    $hn = mysqli_fetch_array($jn);
    if (mysqli_num_rows($jn) == 0) {
        $data['result'][] = 'notFound';
        $data['message'][] = $message;
    } else {
        $data['result'][] = $hn['id'];
    }

    echo json_encode($data);

} else if(isset($_REQUEST['validateQR_piece'])) {
    
    if($_REQUEST['type']=='sewing_output') {
        $exp = explode('-', $_REQUEST['qr']);
        $fild = "SELECT * FROM bundle_details WHERE id = '" . $exp[0] . "'";

        $jn = mysqli_query($mysqli, $fild);
        $hn = mysqli_fetch_array($jn);
        
        if($hn['id']!=$exp[0] || $exp[1]>500) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Invalid Barcode. ('. $_REQUEST['qr'] .')';
            
        } else if($hn['order_id']!=$_REQUEST['order_id']) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Not in This BO. ('. $_REQUEST['qr'] .')';
            
        } else if($hn['in_sewing']!='yes') {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Not in Sewing. ('. $_REQUEST['qr'] .')';
            
        } else if(in_array($exp[1], explode(',', $hn['s_out_complete']))) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Already Scanned. ('. $_REQUEST['qr'] .')';
            
        } else {
            $data['result'][] = $hn['id'].'-'.$exp[1];
        }
        
    } else if($_REQUEST['type']=='checking_list') {
        
        $exp = explode('-', $_REQUEST['qr']);
        $fild = "SELECT * FROM bundle_details WHERE id = '" . $exp[0] . "'";
        
        $jn = mysqli_query($mysqli, $fild);
        
        $hn = mysqli_fetch_array($jn);
        
        if($hn['id']!=$exp[0] || $exp[1]>500) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Invalid Barcode. ('. $_REQUEST['qr'] .')';
            
        } else if($hn['order_id']!=$_REQUEST['order_id']) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Not in This BO. ('. $_REQUEST['qr'] .')';
            
        } else if($hn['in_sewing']!='yes') {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Not in Sewing. ('. $_REQUEST['qr'] .')';
            
        } else if($hn['ch_missing_pcs'] != NULL && array_search($exp[1], explode(',', $hn['ch_missing_pcs'])) === false || $hn['ch_missing_pcs'] == "") {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Already Scanned. ('. $_REQUEST['qr'] .')';
            
        } else if(get_setting_val('STBPCSCAN')=='YES' && in_array($exp[1], explode(',', $hn['s_out_complete']))) {
            $data['result'][] = $hn['id'].'-'.$exp[1];
            
        } else if(get_setting_val('STBPCSCAN')=='NO') {
            $data['result'][] = $hn['id'].'-'.$exp[1];
            
        } else {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Not Complete Sewing. ('. $_REQUEST['qr'] .')';
        }
        
    } else if($_REQUEST['type']=='rework_entry') {
        
        $exp = explode('-', $_REQUEST['qr']);
        $fild = "SELECT * FROM bundle_details WHERE id = '" . $exp[0] . "'";
        
        $jn = mysqli_query($mysqli, $fild);
        
        $hn = mysqli_fetch_array($jn);
        
        $cnB = mysqli_query($mysqli, "SELECT * FROM checking_output WHERE bundle_id = '" . $exp[0] . "' AND checking_type = '". $_REQUEST['rework_stage'] ."'");
        
        $newArray = array();
        while($bnIn = mysqli_fetch_array($cnB)) {
            
            $newArray = array_merge($newArray, explode(',', $bnIn['pieces']));
        }
        
        if($hn['id']!=$exp[0] || $exp[1]>500) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Invalid Barcode. ('. $_REQUEST['qr'] .')';
            
        } else if($hn['order_id']!=$_REQUEST['order_id']) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Not in This BO. ('. $_REQUEST['qr'] .')';
            
        } else if(!in_array($exp[1], $newArray)) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Not in This Rework Stage. ('. $_REQUEST['qr'] .')';
            
        } else {
            $data['result'][] = $hn['id'].'-'.$exp[1];
            
        }
    } else if($_REQUEST['type']=='ironing_entry') {
        
        $exp = explode('-', $_REQUEST['qr']);
        $fild = "SELECT * FROM bundle_details WHERE id = '" . $exp[0] . "'";
        
        $jn = mysqli_query($mysqli, $fild);
        
        $hn = mysqli_fetch_array($jn);
        
        if($hn['id']!=$exp[0] || $exp[1]>500) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Invalid Barcode. ('. $_REQUEST['qr'] .')';
            
        } else if($hn['order_id']!=$_REQUEST['order_id']) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Not in This BO. ('. $_REQUEST['qr'] .')';
            
        } else if(!in_array($exp[1], explode(',', $hn['tot_ironing']))) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Already Scanned. ('. $_REQUEST['qr'] .')';
            
        } else if(get_setting_val('STBPCSCAN')=='YES' && in_array($exp[1], explode(',', $hn['ch_good_pcs'])) && in_array($exp[1], explode(',', $hn['tot_ironing']))) {
            $data['result'][] = $hn['id'].'-'.$exp[1];
            
        } else if(get_setting_val('STBPCSCAN')=='NO') {
            $data['result'][] = $hn['id'].'-'.$exp[1];
            
        } else {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Checking Not Completed. ('. $_REQUEST['qr'] .')';
            
        }
    } else if($_REQUEST['type']=='packing_entry') {
        
        $exp = explode('-', $_REQUEST['qr']);
        $fild = "SELECT * FROM bundle_details WHERE id = '" . $exp[0] . "'";
        
        $jn = mysqli_query($mysqli, $fild);
        
        $hn = mysqli_fetch_array($jn);
        
        if($hn['id']!=$exp[0] || $exp[1]>500) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Invalid Barcode. ('. $_REQUEST['qr'] .')';
            
        } else if($hn['order_id']!=$_REQUEST['order_id']) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Not in This BO. ('. $_REQUEST['qr'] .')';
            
        } else if(!in_array($exp[1], explode(',', $hn['tot_packing']))) {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Piece Already Scanned. ('. $_REQUEST['qr'] .')';
            
        } else if(get_setting_val('STBPCSCAN')=='YES' && in_array($exp[1], explode(',', $hn['ironing_complete'])) && in_array($exp[1], explode(',', $hn['tot_packing']))) {
            $data['result'][] = $hn['id'].'-'.$exp[1];
            
        } else if(get_setting_val('STBPCSCAN')=='NO') {
            $data['result'][] = $hn['id'].'-'.$exp[1];
            
        } else {
            $data['result'][] = 'notFound';
            $data['message'][] = 'Ironing Not Completed. ('. $_REQUEST['qr'] .')';
            
        }
    }
    
    echo json_encode($data);

} else if(isset($_REQUEST['getboundle_details'])) {
    
    
    // echo '<pre>', print_r($_POST, 1); exit;

    if ($_REQUEST['head'] == "") {
        if ($_REQUEST['multibundle'] != "" || $_REQUEST['scanner'] != "") {

            if ($_REQUEST['scanner'] == "") {
                $bnl = $_REQUEST['multibundle'];
            } else {
                $bnl = array(0 => $_REQUEST['scanner']);
            }

            if ($_REQUEST['bundle_in'] != "") {
                $ioo = array_merge(explode(',', $_REQUEST['bundle_in']), $bnl);
                $smg = implode(',', array_unique($ioo));
            } else {
                $ioo = $bnl;
                $smg = implode(',', array_unique($ioo));
            }

            $datas['ress'] = 1;
        }

        foreach ($ioo as $ky => $valu) {
            $iop = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.id, a.bundle_number, b.order_id, b.style, b.part, b.color FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id WHERE a.id='" . $valu . "'"));
            $track[] = $iop['order_id'] . '->' . $iop['style'] . '->' . $iop['part'] . '->' . $iop['color'] . '->' . $iop['id'] . '->' . $iop['bundle_number'];
        }
        
        $track_n = json_encode($track);

    } else {
        $datas['ress'] = 0;
        $smg = NULL;
        $track_n = NULL;
    }
    if ($_REQUEST['process_type'] == "process_outward") {
        if ($_REQUEST['p_type'] == 'Unit') {
            $field = array(
                'production_unit' => $_REQUEST['production_unit']
            );
        } else if($_REQUEST['p_type'] == 'Outward') {
            $field = array(
                'supplier_id' => $_REQUEST['supplier_id']
            );
        } else {
            $field = array();
        }
        
        $d1 = array(
            'p_type' => $_REQUEST['p_type'],
            'process_id' => $_REQUEST['process_id'],
            'scanning_using' => $_REQUEST['scanning_using'],
            'order_id' => $_REQUEST['order_id'],
            'entry_date' => date('Y-m-d'),
            'processing_code' => $_REQUEST['processing_code'],
            'boundle_id' => $smg,
            'bundle_track' => $track_n,
            'type' => $_REQUEST['process_type'],
            'created_by' => $logUser,
            'created_unit' => $logUnit,
            'created_date' => date('Y-m-d H:i:s')
        );

        $data = array_merge($d1, $field);
        
    } else if($_REQUEST['process_type'] == "sewing_input") {

        $data = array(
            'processing_code' => $_REQUEST['processing_code'],
            'order_id' => $_REQUEST['order_id'],
            'scanning_using' => $_REQUEST['scanning_using'],
            'entry_date' => $_REQUEST['entry_date'],
            'input_type' => $_REQUEST['input_type'],
            'assigned_emp' => $_REQUEST['line'],
            'boundle_id' => $smg,
            'bundle_track' => $track_n,
            'type' => $_REQUEST['process_type'],
            'created_by' => $logUser,
            'created_unit' => $logUnit,
            'created_date' => date('Y-m-d H:i:s')
        );
        
    } else if($_REQUEST['process_type'] == "sewing_output") {

        $data = array(
            'processing_code' => $_REQUEST['processing_code'],
            'scanning_using' => $_REQUEST['scanning_using'],
            'order_id' => $_REQUEST['order_id'],
            'entry_date' => $_REQUEST['entry_date'],
            'boundle_id' => $smg,
            'bundle_track' => $track_n,
            'type' => $_REQUEST['process_type'],
            'scanning_type' => $_REQUEST['scanType'],
            'created_by' => $logUser,
            'created_unit' => $logUnit,
            'created_date' => date('Y-m-d H:i:s')
        );
    } else if($_REQUEST['process_type'] == "checking_list") {

        $data = array(
            'processing_code' => $_REQUEST['processing_code'],
            'scanning_using' => $_REQUEST['scanning_using'],
            'scanning_for' => $_REQUEST['scanning_for'],
            'order_id' => $_REQUEST['order_id'],
            'entry_date' => $_REQUEST['entry_date'],
            'assigned_emp' => $_REQUEST['employee'],
            'boundle_id' => $smg,
            'bundle_track' => $track_n,
            'type' => $_REQUEST['process_type'],
            'scanning_type' => $_REQUEST['scanType'],
            'created_by' => $logUser,
            'created_unit' => $logUnit,
            'created_date' => date('Y-m-d H:i:s')
        );
    }



    if ($_REQUEST['processing_id'] == "") {
        Insert('processing_list', $data);
        $inid = mysqli_insert_id($mysqli);
    } else {
        Update('processing_list', $data, " WHERE id = '" . $_REQUEST['processing_id'] . "'");
        $inid = $_REQUEST['processing_id'];
    }

    if ($_REQUEST['process_type'] == "process_outward") {
        if(!empty($bnl)) {
            for ($m = 0; $m < count($bnl); $m++) {
                mysqli_query($mysqli, "UPDATE bundle_details SET `in_proseccing`='yes', `complete_processing`= NULL, in_proseccing_id='". $inid ."', `in_proseccing_date`='".date('Y-m-d H:i:s')."' WHERE `id`='" . $bnl[$m] . "'");
            }
        }
    } else if($_REQUEST['process_type'] == "sewing_input") {

        if(!empty($bnl)) {
            for ($m = 0; $m < count($bnl); $m++) {
                
                $pcs_per_bundle = array();
                $nnvb = mysqli_fetch_array(mysqli_query($mysqli, "SELECT pcs_per_bundle FROM bundle_details WHERE id = ". $bnl[$m]));
                for($mk=1; $mk<= $nnvb['pcs_per_bundle']; $mk++) {
                    $pcs_per_bundle = array_merge($pcs_per_bundle, explode(',', $mk));
                }
                
                mysqli_query($mysqli, "UPDATE bundle_details SET in_sewing='yes', line='". $_REQUEST['line'] ."', in_sewing_id='". $inid ."', in_sewing_date='".date('Y-m-d H:i:s')."', ch_missing_pcs = '". implode(',', $pcs_per_bundle) ."', tot_ironing = '". implode(',', $pcs_per_bundle) ."', tot_packing = '". implode(',', $pcs_per_bundle) ."' WHERE id='" . $bnl[$m] . "'");
            }
        }
        
    } else if($_REQUEST['process_type'] == "sewing_output") {
        for ($m = 0; $m < count($bnl); $m++) {
            $as = mysqli_fetch_array(mysqli_query($mysqli, "SELECT pcs_per_bundle FROM bundle_details WHERE id = '". $bnl[$m] ."'"));
            

            mysqli_query($mysqli, "UPDATE bundle_details SET complete_sewing='yes', s_out_complete = '". implode(', ', range(1, $as['pcs_per_bundle'])) ."', comp_sewing_date='".date('Y-m-d H:i:s')."' WHERE id='" . $bnl[$m] . "'");
        }
        
    } else if($_REQUEST['process_type'] == "checking_list") {
        for ($m = 0; $m < count($bnl); $m++) {
            
            $ppb = mysqli_fetch_array(mysqli_query($mysqli, "SELECT pcs_per_bundle FROM bundle_details WHERE id='" . $bnl[$m] . "'"));
            
            if($_REQUEST['scan_type']=='bundle_scan') {
                $GP1 = $_REQUEST['goodsPcs'][$m];
                $RP1 = $_REQUEST['rejectPcs'][$m];
                $Rw1 = $_REQUEST['reworkPcs'][$m];
            } else {
                $GP1 = $ppb['pcs_per_bundle'];
                $RP1 = 0;
                $Rw1 = 0;
            }
            
            for($q11=0; $q11 < $GP1; $q11++) {
                $ch_good_pcs[$m][] = $q11+1;
            }
            
            for($q12=0; $q12 < $RP1; $q12++) {
                $ch_reject_pcs[$m][] = $GP1 + $q12+1;
            }
            
            for($q13=0; $q13 < $RW1; $q13++) {
                $ch_rework_pcs[$m][] = $RP1 + $GP1 + $q13+1;
            }
            
            $balNCe = $RP1 + $GP1 + $RW1;
            $rejCNT = $_REQUEST['totalPcs'][$m] - $balNCe;
            
            for($q14=0; $q14 < $rejCNT; $q14++) {
                $ch_missing_pcs[$m][] = $balNCe + $q14+1;
            }
            
            
            $updta = array(
                'checking_complete' => 'yes',
                'checking_id' => $inid,
                'ch_good_pcs' => implode(',', $ch_good_pcs[$m]),
                'ch_missing_pcs' => implode(',', $ch_missing_pcs[$m]),
                'ch_reject_pcs' => implode(',', $ch_reject_pcs[$m]),
                'ch_rework_pcs' => implode(',', $ch_rework_pcs[$m]),
                'ch_rework_stage' => $_REQUEST['reworkStagePcs'][$m],
                'checking_employee' => $_REQUEST['employee'],
                'checking_date' => date('Y-m-d H:i:s'),
                );
                
            Update('bundle_details', $updta, " WHERE id = '" . $bnl[$m] . "'");
        }
        
    }
    
    
    $datas['inid'] = $inid;
    $datas['bundle_in'] = $smg;
    

    echo json_encode($datas);

} else if(isset($_REQUEST['getProcessingDet'])) {

    $sqql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $_REQUEST['id']));

    foreach (explode(',', $sqql['boundle_id']) as $key => $value) {

        $qry = "SELECT a.*, b.order_code, c.style_no, d.color_name, e.type ";
        $qry .= "FROM bundle_details a ";
        $qry .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
        $qry .= "LEFT JOIN sales_order_detalis c ON b.style=c.id ";
        $qry .= "LEFT JOIN color d ON b.color=d.id ";
        $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
        $qry .= "WHERE a.id='" . $value . "' ";

        $sql = mysqli_fetch_array(mysqli_query($mysqli, $qry));

        $x = $key + 1;
        print '<tr>';
        print '<td>' . $x . '</td>';
        print '<td>' . $sql['order_code'] . '</td>';
        print '<td>' . $sql['style_no'] . '</td>';
        print '<td>' . $sql['color_name'] . '</td>';
        print '<td>' . $sql['type'] . '</td>';
        print '<td>' . $sql['bundle_number'] . '</td>';
        print '<td>' . $sql['pcs_per_bundle'] . '</td>';
        print '<td>' . $sql['boundle_qr'] . '</td>';
        print '</tr>';
    }
} else if(isset($_REQUEST['getsizeRange'])) {

    $ass = "SELECT * FROM variation_value WHERE variation_id=" . $_REQUEST['id'];
    $asss = mysqli_query($mysqli, $ass);
    $x = 1;
    
    $iid = $_REQUEST['iid'];
    
    $temp_id = $_REQUEST['temp_id'];
    if(mysqli_num_rows($asss)>0) {
        while ($asd = mysqli_fetch_array($asss)) {

            print '<tr id="trid' . $asd['id'].$temp_id . '">';
            print '<td>' . $asd['type'] . '</td>';
            print '<td>:</td>';
            print '<td><input type="hidden" name="variation_value_id'. $iid .'[]" value="' . $asd['id'] . '"> <input type="hidden" name="variation_value_id_name[]" value="">';
            print '<input type="number" class="form-control varvalue" onkeyup="varvalue(' . $x . ')" name="varvalue'. $iid .'[]" id="varvalue' . $x . '" value="" placeholder="Qty"></td>';
            print '<td><input type="number" name="excess_per'. $iid .'[]" id="excess_per' . $x . '" value="' . $_REQUEST['excess'] . '" class="form-control" placeholder="Excess %"></td>';
            print '<td class="removeclone"><i class="icon-copy fa fa-trash-o" aria-hidden="true" onclick="removeRow(' . $asd['id'].$temp_id . ')" title="Remove"></i></td>';
            print '</tr>';
            $x++;
        }
    } else {
        print '<tr><td colspan="4" class="text-center">No result found!</td></tr>';
    }
} else if(isset($_REQUEST['getSubProcess'])) {
    $opt = '';
    foreach (explode(',', $_REQUEST['id']) as $key => $val) {
        $myqs = mysqli_query($mysqli, "SELECT * FROM sub_process WHERE process_id=" . $val);
        while ($fth = mysqli_fetch_array($myqs)) {
            $opt .= '<option value="' . $fth['id'] . '" selected>' . $fth['sub_process_name'] . '</option>';
        }
    }
    print $opt;

} else if(isset($_REQUEST['qrPrintDet'])) {

    $sql = "SELECT a.*, b.order_code, c.style_no, d.color_name, e.type ";
    $sql .= "FROM bundle_details a ";
    $sql .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
    $sql .= "LEFT JOIN sales_order_detalis c ON b.style=c.id ";
    $sql .= "LEFT JOIN color d ON b.color=d.id ";
    $sql .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
    $sql .= "WHERE a.id='" . $_REQUEST['id'] . "' ";

    $res = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_array($res);

    $data['so_num'][] = $result['order_code'];
    $data['style_no'][] = $result['style_no'];
    $data['color_name'][] = $result['color_name'];
    $data['type'][] = $result['type'];
    $data['no_of_bundle'][] = $result['bundle_number'];
    $data['boundle_qr'][] = $result['boundle_qr'];

    echo json_encode($data);

} else if(isset($_REQUEST['pices_listt'])) {

    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE id=" . $_REQUEST['id']));

            // <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $sql['boundle_qr'] .'-'. $p . '&choe=UTF-8" title="' . $sql['boundle_qr'] .'-'. $p . '"/>
            // <label>QR : ' . substr($sql['boundle_qr'] .'-'. $p, -15) . '</label>
if($sql['boundle_qr']!="") {
    for ($p = 1; $p <= $sql['pcs_per_bundle']; $p++) {
        $data['body'][] = '<div class="col-md-3" style="text-align: center;">
        
                            <img src="https://barcode.tec-it.com/barcode.ashx?data=' . $sql['boundle_qr'] .'-'. $p . '&choe=UTF-8" title="' . $sql['boundle_qr'] .'-'. $p . '"/>

                            </div>';
    }
} else {
    $data['body'][] = '<div class="col-md-12" style="text-align: center;">Not Generated</div>';
}

    echo json_encode($data);

} else if(isset($_REQUEST['getInward'])) {
    
    $ress = mysqli_query($mysqli, "SELECT boundle_id FROM processing_list WHERE id=" . $_REQUEST['id']);
    $sql = mysqli_fetch_array($ress);
    if(mysqli_num_rows($ress)>0) {
        if (!empty($sql['boundle_id'])) {
            foreach (explode(',', $sql['boundle_id']) as $key => $value) {
                
                $qry = "SELECT a.* ";
                $qry .= "FROM bundle_details a ";
                $qry .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
                $qry .= "WHERE a.id='" . $value . "' ";
                $sql = mysqli_fetch_array(mysqli_query($mysqli, $qry));
                
                $x = $key + 1;
                print '<tr>';
                print '<td style="color: #007cff;"> 
                <input type="checkbox" class="d-none" checked> 
                
                <a class="chkk" onclick="showPices(' . $value . ')"><i class="icon-copy ion-chevron-right d-none" id="iconn' . $value . '" ></i> ' . $sql['boundle_qr'] . '</a> 
                <input type="hidden" id="show_pieces' . $value . '">
                <input type="hidden" value="' . $sql['boundle_qr'] . '" name="box_value[]" disabled>
                <input type="hidden" value="' . $value . '" name="bundle_id[]">
                 </td>';
                print '<td>' . $sql['bundle_number'] . '</td>';
                print '<td>' . $sql['pcs_per_bundle'] . '</td>';
                print '<td>' . sales_order_code($sql['order_id']) . '</td>';
                print '<td>' . $sql['ref_id'] . '</td>';
                print '<td>' . sales_order_style($sql['style_id']) . '</td>';
                print '<td>' . color_name($sql['color']) . '</td>';
                print '<td>' . variation_value($sql['variation_value']) . '</td>';
                print '</tr>';
            }
        } else {
            print '<tr><td colspan="8" class="text-center">No Data Found</td></tr>';
        }
    } else {
        print '<tr><td colspan="8" class="text-center">No Data Found</td></tr>';
    }
} else if(isset($_REQUEST['getChecking'])) {
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $_REQUEST['id']));
    if (!empty($sql['boundle_id'])) {
        foreach (explode(',', $sql['boundle_id']) as $key => $value) {
            $qry = "SELECT a.*, b.order_code, b.ref_id, c.style_no, d.color_name, e.type ";
            $qry .= "FROM bundle_details a ";
            $qry .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
            $qry .= "LEFT JOIN sales_order_detalis c ON b.style=c.id ";
            $qry .= "LEFT JOIN color d ON b.color=d.id ";
            $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
            $qry .= "WHERE a.id='" . $value . "' ";

            $sql = mysqli_fetch_array(mysqli_query($mysqli, $qry));

            $nql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM checking_list WHERE bundle_id ='" . $value . "'"));

            $x = $key + 1;
            print '<tr>';
            print '<td style="color: #007cff;"> 
            <input type="checkbox" class="d-none" checked> 
            
            <a class="chkk" onclick="showPices(' . $value . ')"><i class="icon-copy ion-chevron-right" id="iconn' . $value . '" ></i> ' . $sql['boundle_qr'] . '</a> 
            <input type="hidden" id="show_pieces' . $value . '">
            <input type="hidden" id="show_pieces' . $value . '">
            <input type="hidden" value="' . $nql['id'] . '" name="saved_id[]">
            <input type="hidden" value="' . $value . '" name="bundle_id[]">
             </td>';
            print '<td>' . $sql['bundle_number'] . '</td>';
            print '<td>' . $sql['pcs_per_bundle'] . '</td>';
            print '<td><input type="text" name="good_pcs[]" id="good_pcs" class="form-control" value="' . $nql['good_pcs'] . '"></td>';
            print '<td><input type="text" name="rework_pcs[]" id="rework_pcs" class="form-control" value="' . $nql['rework_pcs'] . '"></td>';
            print '<td><input type="text" name="rejection_pcs[]" id="rejection_pcs" class="form-control" value="' . $nql['rejection_pcs'] . '"></td>';
            print '<td><input type="text" name="rework_stage[]" id="rework_stage" class="form-control" value="' . $nql['rework_stage'] . '"></td>';
            print '</tr>';

            print '<tr id="trcBoxx' . $value . '" style="display:none;background-color: #f4f4f4;"> <td colspan="8"> <div class="row">';

            $nsel = mysqli_query($mysqli, "SELECT * FROM inwarded_bundle WHERE bundle_id=" . $value);
            // $nmm = 
            $numm = mysqli_num_rows($nsel);
            $fthh = mysqli_fetch_array($nsel);

            $inarr = explode(',', $fthh['pieces_qr']);

            print '<input type="hidden" value="' . $fthh['id'] . '" name="saved_id[]">';
            print '<div class="col-md-12" style="color:red;"><input type="checkbox" id="ncbox' . $sql['bundle_number'] . '" onchange="ncbox(' . $sql['bundle_number'] . ')"> <label for="ncbox' . $sql['bundle_number'] . '"> Check All</label> </br></div>';

            for ($i = 1; $i <= $sql['pcs_per_bundle']; $i++) {
                if ($numm > 0) {
                    if (in_array($sql['boundle_qr'] . $i, $inarr)) {
                        $ch = 'checked';
                    } else if($_REQUEST['readonly'] == true) {
                        $ch = 'disabled';
                    } else {
                        $ch = '';
                    }
                } else {
                    $ch = 'checked';
                }
                print '<div class="col-md-2"><input type="checkbox" class="ncbox' . $sql['bundle_number'] . '" name="' . $sql['boundle_qr'] . '[]" value="' . $sql['boundle_qr'] . $i . '" ' . $ch . ' > <label class="">' . $sql['boundle_qr'] . $i . '</label> </div>';
            }

            print '</div> </td> </tr>';
        }
    }
} else if(isset($_REQUEST['getSubprocessDet'])) {

    $myqs = mysqli_query($mysqli, "SELECT a.*, b.process_name FROM sub_process a LEFT JOIN process b ON a.process_id=b.id WHERE a.process_id=" . $_REQUEST['id']);
    if (mysqli_num_rows($myqs) > 0) {
        while ($fth = mysqli_fetch_array($myqs)) {
            $data['table'][] = '<tr> <td>' . $fth['process_name'] . '</td> <td>' . $fth['sub_process_name'] . '</td> <td>' . $fth['price'] . '</td> </tr>';
        }
    } else {
        $data['table'][] = '<tr><td colspan="3" style="text-align:center">Subprocess Not Found</td></tr>';
    }
    echo json_encode($data);

} else if(isset($_REQUEST['inhouseProcess'])) {

    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $_REQUEST['id']));
    if (!empty($sql['boundle_id'])) {
        foreach (explode(',', $sql['boundle_id']) as $key => $value) {
            $qry = "SELECT a.*, b.order_code, b.ref_id, c.style_no, d.color_name, e.type ";
            $qry .= "FROM bundle_details a ";
            $qry .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
            $qry .= "LEFT JOIN sales_order_detalis c ON b.style=c.id ";
            $qry .= "LEFT JOIN color d ON b.color=d.id ";
            $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
            $qry .= "WHERE a.id='" . $value . "' ";

            $sql = mysqli_fetch_array(mysqli_query($mysqli, $qry));

            $olp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM inhouse_process WHERE outward_id='" . $_REQUEST['id'] . "' AND bundle_id='" . $value . "' AND date='" . date('Y-m-d') . "'"));

            $x = $key + 1;
            print '<tr>';
            print '<td style="color: #007cff;"> 
            <input type="checkbox" class="d-none" checked> 
            
            <a class="chkk" onclick="showPices(' . $value . ')"><i class="icon-copy ion-chevron-right" id="iconn' . $value . '" ></i> ' . $sql['boundle_qr'] . '</a> 
            <input type="hidden" id="show_pieces' . $value . '">
            <input type="hidden" value="' . $olp['id'] . '" name="dailyId[]">
            <input type="hidden" value="' . $value . '" name="bundle_id[]">
             </td>';
            print '<td>' . $sql['bundle_number'] . '</td>';
            print '<td>' . $sql['pcs_per_bundle'] . '</td>';
            print '<td>' . $sql['type'] . '</td>';
            print '<td><select class="form-control custom-select2" name="employee[]" id="employee' . $value . '">';
            print select_dropdown('process', array('id', 'process_name'), 'process_name ASC', $asd['process'], '', '');
            print '</td>';
            print '<td><input type="number" class="form-control" name="completed_qty[]" id="completed" value="' . ($olp['completed_qty']) . '"></td>';
            print '<td></td>';
            print '<td></td>';
            print '</tr>';

            print '<tr id="trcBoxx' . $value . '" style="display:none;background-color: #f4f4f4;"> <td colspan="8"> <div class="row">';

            $nsel = mysqli_query($mysqli, "SELECT * FROM inwarded_bundle WHERE bundle_id=" . $value);
            // $nmm = 
            $numm = mysqli_num_rows($nsel);
            $fthh = mysqli_fetch_array($nsel);

            $inarr = explode(',', $fthh['pieces_qr']);

            print '<input type="hidden" value="' . $fthh['id'] . '" name="saved_id[]">';
            print '<div class="col-md-12" style="color:red;"><input type="checkbox" id="ncbox' . $sql['bundle_number'] . '" onchange="ncbox(' . $sql['bundle_number'] . ')"> <label for="ncbox' . $sql['bundle_number'] . '"> Check All</label> </br></div>';

            for ($i = 1; $i <= $sql['pcs_per_bundle']; $i++) {
                if ($numm > 0) {
                    if (in_array($sql['boundle_qr'] . $i, $inarr)) {
                        $ch = 'checked';
                    } else if($_REQUEST['readonly'] == true) {
                        $ch = 'disabled';
                    } else {
                        $ch = '';
                    }
                } else {
                    $ch = 'checked';
                }
                print '<div class="col-md-2"><input type="checkbox" class="ncbox' . $sql['bundle_number'] . '" name="' . $sql['boundle_qr'] . '[]" value="' . $sql['boundle_qr'] . $i . '" ' . $ch . ' > <label class="">' . $sql['boundle_qr'] . $i . '</label> </div>';
            }

            print '</div> </td> </tr>';
        }
    }
} else if(isset($_REQUEST['dailyCompletye'])) {

    $uil = mysqli_query($mysqli, "SELECT a.*,b.employee_name FROM inhouse_process a LEFT JOIN employee_detail b ON a.employee=b.id WHERE a.bundle_id='" . $_REQUEST['id'] . "'");
    while ($iop = mysqli_fetch_array($uil)) {
        $data['tbody'][] = '<tr> <td>' . date('d-m-y', strtotime($iop['date'])) . '</td> <td>' . $iop['employee_name'] . '</td> <td>' . $iop['completed_qty'] . '</td> </tr>';
    }

    echo json_encode($data);
    
} else if(isset($_REQUEST['pieces_wise_scanned'])) {
    
    $mli = mysqli_fetch_array(mysqli_query($mysqli, "SELECT boundle_id,piece_scanned FROM processing_list WHERE id=" . $_REQUEST['id']));

    $datas['cntt'] = count(array_filter(explode(',', $mli['boundle_id'])));
    $k=0;
    foreach (explode(',', $mli['boundle_id']) as $qrr) {
        if ($qrr != "") {
            $qry = "SELECT a.*, b.order_code, b.ref_id, c.style_no, d.color_name, e.type, f.part_name, g.employee_name ";
            $qry .= "FROM bundle_details a ";
            $qry .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
            $qry .= "LEFT JOIN sales_order_detalis c ON b.style=c.id ";
            $qry .= "LEFT JOIN color d ON b.color=d.id ";
            $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
            $qry .= "LEFT JOIN part f ON b.part=f.id ";
            $qry .= "LEFT JOIN employee_detail g ON a.line=g.id ";
            $qry .= "WHERE a.id='" . $qrr . "' ";

            $res = mysqli_query($mysqli, $qry);

            $sql = mysqli_fetch_array($res);
            

            $inarr = explode(',', $mli['piece_scanned']);

            $cbbx = '';
            for ($i = 1; $i <= $sql['pcs_per_bundle']; $i++) {
                if ($sql['s_out_complete'] != NULL) {
                    if (in_array(($sql['id'].'-'.$i), $inarr)) {
                        $ch = 'checked';
                        
                        $pics[$k][] = $i;
                    } 
                    // else if($_REQUEST['readonly'] == true) {
                    //     $ch = 'disabled';
                    // }
                    else {
                        $ch = '';
                    }
                } else {
                    $ch = 'checked';
                }
                
                $cbbx .= '<div class="col-md-2" style="pointer-events: none;"><input type="checkbox" class="ncbox' . $sql['bundle_number'] . '" name="' . $sql['boundle_qr'] . '[]" value="'. $i . '" ' . $ch . '> <label class="">' . $sql['boundle_qr'] . $i . '</label> </div>';
            }

            $datas['tbl_bdy'][] = '<tr id="tbTr' . $qrr . '">
                    <td> <input type="hidden" id="" name="boundle_id[]" value="' . $sql['id'] . '"> ' . $sql['order_code'] . '</td>
                    <td>' . $sql['ref_id'] . '</td> 
                    <td>' . $sql['style_no'] . '</td>
                    <td>' . $sql['part_name'] . '</td>
                    <td>' . $sql['color_name'] . '</td>
                    <td>' . $sql['type'] . '</td>
                    <td>' . $sql['bundle_number'] . '</td>
                    <td>
                        <a class="chkk" onclick="showPices(' . $qrr . ')" style="color: #006dff;"><i class="icon-copy ion-chevron-right" id="iconn' . $qrr . '" ></i> ' . $sql['pcs_per_bundle'] . '</a> 
                            <input type="hidden" id="show_pieces' . $qrr . '">
                            <input type="hidden" value="' . $sql['boundle_qr'] . '" name="box_value[]">
                            <input type="hidden" value="' . $qrr . '" name="bundle_id[]">
                    </td>
                    <td>' . $sql['employee_name'] . '</td>
                    <td>' . $sql['boundle_qr'] . '</td>
                    <td>' . implode(' | ', $pics[$k]) . '</td>
                    <td><i class="icon-copy fa fa-trash-o" aria-hidden="true" onclick="removeRow(' . $qrr . ')" title="Remove"></i></td>
                </tr>
                
                <tr id="trcBoxx' . $qrr . '" style="display:none;background-color: #f4f4f4;"> <td colspan="11"> <div class="row"> '.$cbbx.' </div> </td> </tr>
            ';
        }
    $k++; }

    echo json_encode($datas);

} else if(isset($_REQUEST['pieces_wise_scanned_rework'])) {
    
    $mli = mysqli_fetch_array(mysqli_query($mysqli, "SELECT boundle_id,piece_scanned FROM processing_list WHERE id=" . $_REQUEST['id']));

    $datas['cntt'] = count(array_filter(explode(',', $mli['boundle_id'])));
    $k=0;
    foreach (explode(',', $mli['boundle_id']) as $qrr) {
        if ($qrr != "") {
            $qry = "SELECT a.*, b.order_code, b.ref_id, c.style_no, d.color_name, e.type, f.part_name, g.employee_name ";
            $qry .= "FROM bundle_details a ";
            $qry .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
            $qry .= "LEFT JOIN sales_order_detalis c ON b.style=c.id ";
            $qry .= "LEFT JOIN color d ON b.color=d.id ";
            $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
            $qry .= "LEFT JOIN part f ON b.part=f.id ";
            $qry .= "LEFT JOIN employee_detail g ON a.line=g.id ";
            $qry .= "WHERE a.id='" . $qrr . "' ";

            $res = mysqli_query($mysqli, $qry);

            $sql = mysqli_fetch_array($res);
            

            $inarr = explode(',', $mli['piece_scanned']);

            $cbbx = '';
            for ($i = 1; $i <= $sql['pcs_per_bundle']; $i++) {
                if ($sql['s_out_complete'] != NULL) {
                    if (in_array(($sql['id'].'-'.$i), $inarr)) {
                        $ch = 'checked';
                        
                        $pics[$k][] = $i;
                    } 
                    // else if($_REQUEST['readonly'] == true) {
                    //     $ch = 'disabled';
                    // }
                    else {
                        $ch = '';
                    }
                } else {
                    $ch = 'checked';
                }
                
                $cbbx .= '<div class="col-md-2" style="pointer-events: none;"><input type="checkbox" class="ncbox' . $sql['bundle_number'] . '" name="' . $sql['boundle_qr'] . '[]" value="'. $i . '" ' . $ch . '> <label class="">' . $sql['boundle_qr'] . $i . '</label> </div>';
            }

            $datas['tbl_bdy'][] = '<tr id="tbTr' . $qrr . '">
                    <td> <input type="hidden" id="" name="boundle_id[]" value="' . $sql['id'] . '"> ' . $sql['order_code'] . '</td>
                    <td>' . $sql['style_no'] . '</td>
                    <td>' . $sql['part_name'] . '</td>
                    <td>' . $sql['color_name'] . '</td>
                    <td>' . $sql['type'] . '</td>
                    <td>' . $sql['bundle_number'] . '</td>
                    <td>' . $sql['pcs_per_bundle'] . '</td>
                    <td>' . $sql['boundle_qr'] . '</td>
                    <td>' . implode(' | ', $pics[$k]) . '</td>
                    <td class="d-none"><i class="icon-copy fa fa-trash-o" aria-hidden="true" onclick="removeRow(' . $qrr . ')" title="Remove"></i>-</td>
                </tr>
                
                <tr id="trcBoxx' . $qrr . '" style="display:none;background-color: #f4f4f4;"> <td colspan="11"> <div class="row"> '.$cbbx.' </div> </td> </tr>
            ';
        }
    $k++; }

    echo json_encode($datas);

} else if(isset($_REQUEST['getboundle_details_with_Pieces'])) {
    
    $mli = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $_REQUEST['id']));

    $datas['cntt'] = count(array_filter(explode(',', $mli['boundle_id'])));

    foreach (explode(',', $mli['boundle_id']) as $qrr) {
        if ($qrr != "") {
            $qry = "SELECT a.*, b.order_code, b.ref_id, c.style_no, d.color_name, e.type, f.part_name, g.employee_name ";
            $qry .= "FROM bundle_details a ";
            $qry .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
            $qry .= "LEFT JOIN sales_order_detalis c ON b.style=c.id ";
            $qry .= "LEFT JOIN color d ON b.color=d.id ";
            $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
            $qry .= "LEFT JOIN part f ON b.part=f.id ";
            $qry .= "LEFT JOIN employee_detail g ON a.line=g.id ";
            $qry .= "WHERE a.id='" . $qrr . "' ";

            $res = mysqli_query($mysqli, $qry);

            $sql = mysqli_fetch_array($res);
            

            $inarr = explode(',', $sql['s_out_complete']);

            $cbbx = '';
            for ($i = 1; $i <= $sql['pcs_per_bundle']; $i++) {
                if ($sql['s_out_complete'] != NULL) {
                    if (in_array($i, $inarr)) {
                        $ch = 'checked';
                    } 
                    // else if($_REQUEST['readonly'] == true) {
                    //     $ch = 'disabled';
                    // }
                    else {
                        $ch = '';
                    }
                } else {
                    $ch = 'checked';
                }
                
                $cbbx .= '<div class="col-md-2"><input type="checkbox" class="ncbox' . $sql['bundle_number'] . '" name="' . $sql['boundle_qr'] . '[]" value="'. $i . '" ' . $ch . ' > <label class="">' . $sql['boundle_qr'] . $i . '</label> </div>';
            }

            $datas['tbl_bdy'][] = '<tr id="tbTr' . $qrr . '">
                    <td> <input type="hidden" id="" name="boundle_id[]" value="' . $sql['id'] . '"> ' . $sql['order_code'] . '</td>
                    <td>' . $sql['ref_id'] . '</td> 
                    <td>' . $sql['style_no'] . '</td>
                    <td>' . $sql['part_name'] . '</td>
                    <td>' . $sql['color_name'] . '</td>
                    <td>' . $sql['type'] . '</td>
                    <td>' . $sql['bundle_number'] . '</td>
                    <td>
                        <a class="chkk" onclick="showPices(' . $qrr . ')" style="color: #006dff;"><i class="icon-copy ion-chevron-right" id="iconn' . $qrr . '" ></i> ' . $sql['pcs_per_bundle'] . '</a> 
                            <input type="hidden" id="show_pieces' . $qrr . '">
                            <input type="hidden" value="' . $sql['boundle_qr'] . '" name="box_value[]">
                            <input type="hidden" value="' . $qrr . '" name="bundle_id[]">
                    </td>
                    <td>' . $sql['employee_name'] . '</td>
                    <td>' . $sql['boundle_qr'] . '</td>
                    <td><i class="icon-copy fa fa-trash-o" aria-hidden="true" onclick="removeRow(' . $qrr . ')" title="Remove"></i></td>
                </tr>
                
                <tr id="trcBoxx' . $qrr . '" style="display:none;background-color: #f4f4f4;"> <td colspan="11"> <div class="row"> '.$cbbx.' </div> </td> </tr>
            ';
        }
    }

    echo json_encode($datas);

} else if(isset($_REQUEST['getboundle_details_withOut_Pieces'])) {
    
    $mli = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $_REQUEST['id']));

    $datas['cntt'] = count(explode(',', $mli['boundle_id']));

    $asort = explode(',', $mli['boundle_id']);
    krsort($asort);
    foreach ($asort as $qrr) {
        if ($qrr != "") {
            $qry = "SELECT a.*, b.order_code, b.ref_id, c.style_no, d.color_name, e.type, f.part_name ";
            $qry .= "FROM bundle_details a ";
            $qry .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
            $qry .= "LEFT JOIN sales_order_detalis c ON b.style=c.id ";
            $qry .= "LEFT JOIN color d ON b.color=d.id ";
            $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
            $qry .= "LEFT JOIN part f ON b.part=f.id ";
            $qry .= "WHERE a.id='" . $qrr . "' ";

            $res = mysqli_query($mysqli, $qry);

            $sql = mysqli_fetch_array($res);

            $datas['tbl_bdy'][] = '<tr id="tbTr' . $qrr . '"><td> <input type="hidden" id="" name="boundle_id[]" value="' . $sql['id'] . '"> ' . $sql['order_code'] . '</td> <td>' . $sql['ref_id'] . '</td> <td>' . $sql['style_no'] . '</td> <td>' . $sql['part_name'] . '</td> <td>' . $sql['color_name'] . '</td> <td>' . $sql['type'] . '</td> <td>' . $sql['bundle_number'] . '</td> <td>' . $sql['pcs_per_bundle'] . '</td> <td>' . $sql['boundle_qr'] . '</td> <td><i class="icon-copy fa fa-trash-o" aria-hidden="true" onclick="removeRow(' . $qrr . ')" title="Remove"></i></td></tr>';
        }
    }

    echo json_encode($datas);

} else if(isset($_REQUEST['checked_pieces_details'])) {
    
    $mli = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $_REQUEST['id']));

    $datas['cntt'] = count(explode(',', $mli['boundle_id']));
    
    $ecs = explode(',', $mli['boundle_id']);
    
    if(count(array_filter($ecs)) > 0){
        foreach ($ecs as $qrr) {
            if ($qrr != "") {
                $qry = "SELECT a.*, b.order_code, b.ref_id, c.style_no, d.color_name, e.type, f.part_name, g.employee_name ";
                $qry .= "FROM bundle_details a ";
                $qry .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
                $qry .= "LEFT JOIN sales_order_detalis c ON b.style=c.id ";
                $qry .= "LEFT JOIN color d ON b.color=d.id ";
                $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
                $qry .= "LEFT JOIN part f ON b.part=f.id ";
                $qry .= "LEFT JOIN employee_detail g ON a.line=g.id ";
                $qry .= "WHERE a.id='" . $qrr . "' ";
    
                $res = mysqli_query($mysqli, $qry);
                $sql = mysqli_fetch_array($res);
                
                $q1 = ($sql['ch_good_pcs'] != "") ? $sql['ch_good_pcs'] :'-';
                $q2 = ($sql['ch_reject_pcs'] != "") ? $sql['ch_reject_pcs'] :'-';
                $q3 = ($sql['ch_rework_pcs'] != "") ? $sql['ch_rework_pcs'] :'-';
                $q4 = ($sql['ch_rework_stage'] != "") ? $sql['ch_rework_stage'] : '-';
                $q5 = ($sql['ch_missing_pcs'] != "") ? $sql['ch_missing_pcs'] :'-';
                
                $Gpcs = array();
                $k_ = mysqli_query($mysqli, "SELECT * FROM checking_output WHERE processing_list_id = '". $_REQUEST['id'] ."' AND bundle_id = '". $qrr ."' AND checking_type = 1");
                while($dv = mysqli_fetch_array($k_)) {
                    $Gpcs = array_merge($Gpcs, explode(',', $dv['pieces']));
                }
                
                $RJpcs = array();
                $k_ = mysqli_query($mysqli, "SELECT * FROM checking_output WHERE processing_list_id = '". $_REQUEST['id'] ."' AND bundle_id = '". $qrr ."' AND checking_type = 6");
                while($dv = mysqli_fetch_array($k_)) {
                    $RJpcs = array_merge($RJpcs, explode(',', $dv['pieces']));
                }
                
                $RWpcs = array();
                $k_ = mysqli_query($mysqli, "SELECT * FROM checking_output WHERE processing_list_id = '". $_REQUEST['id'] ."' AND bundle_id = '". $qrr ."' AND checking_type NOT IN (1,6)");
                while($dv = mysqli_fetch_array($k_)) {
                    $RWpcs = array_merge($RWpcs, explode(',', $dv['pieces']));
                }
                
                $q1 = implode(',', $Gpcs);
                $q2 = implode(',', $RJpcs);
                $q3 = implode(',', $RWpcs);
                $datas['tbl_bdy'][] = '<tr id="tbTr' . $qrr . '">
                        <td> <input type="hidden" id="" name="boundle_id[]" value="' . $sql['id'] . '"> ' . $sql['order_code'] . '</td>
                        <td>' . $sql['style_no'] . '</td>
                        <td>' . $sql['part_name'] . '</td>
                        <td>' . $sql['color_name'] . '</td>
                        <td>' . $sql['type'] . '</td>
                        <td>' . $sql['bundle_number'] . ' - '. $sql['boundle_qr'] .'</td>
                        <td>' . $sql['pcs_per_bundle'] .' <input type="hidden" class="" id="tot_ppce'. $qrr .'" value="'. $sql['pcs_per_bundle'] .'"></td>
                        <td><span class="hideoo'.$qrr.'">'. $q1 .'</span> <input class="form-control tmp-hide inpoo'. $qrr .'" id="g_Qty'. $qrr .'" value="'. $q1 .'"> </td>
                        <td><span class="hideoo'.$qrr.'">'. $q2 .'</span> <input class="form-control tmp-hide inpoo'. $qrr .'" id="rej_Qty'. $qrr .'" value="'. $q2 .'"> </td>
                        <td><span class="hideoo'.$qrr.'">'. $q3 .'</span> <input class="form-control tmp-hide inpoo'. $qrr .'" id="rwrk_Qty'. $qrr .'" value="'. $q3 .'"> </td>
                        <td class="text-danger" style="max-width: 100px;word-wrap: break-word;">'. $q5 .'</td>
                        <td><i class="icon-copy fa fa-trash-o" aria-hidden="true" onclick="removeChecking(' . $_REQUEST['id']. ', '. $sql['id'] .')" title="Remove"></i></tr>';
            }
        }
    } else {
        $datas['tbl_bdy'][] = '<tr><td class="text-center" colspan="12">Nothing Found</td></tr>';
    }
    
    
    $hn = mysqli_query($mysqli, "SELECT * FROM mas_checking ORDER BY id ASC");
    while($chk = mysqli_fetch_array($hn)) {
        $ct = 0;
        $iqq = mysqli_query($mysqli, "SELECT * FROM checking_output WHERE processing_list_id = '". $_REQUEST['id'] ."' AND checking_type = '". $chk['id'] ."'");
        while($g = mysqli_fetch_array($iqq)) {
            $ct += count(explode(',', $g['pieces']));
        }
        
        $datas['ch_typ'][] = 'ch_typ'.$chk['id'] .'=='. $ct;
    }
    
    
    echo json_encode($datas);

} else if(isset($_REQUEST['getBundle_checking_details'])) {
    
    $un = explode(',', $_REQUEST['bundleId']);
    // print_r(explode(',', $_REQUEST['bundleId']));
    for($i=0; $i<count($un); $i++)
    {
        $sqql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE id='".$un[$i]."'"));
        
        print '<tr> <td>'.$sqql['boundle_qr'].' </td> <td><input type="hidden" name="totalPcs[]" class="ToalCount'. $i .'" value="'.$sqql['pcs_per_bundle'].'"> '.$sqql['pcs_per_bundle'].' </td> <td><input type="number" onchange="CH('. $i .')" name="goodsPcs[]" class="form-control MaxCount'. $i .'" value="0"></td> <td><input type="number" onchange="CH('. $i .')"  name="rejectPcs[]" class="form-control MaxCount'. $i .'" value="0"></td>  
                    <td><input type="number" onchange="CH('. $i .')"  name="reworkPcs[]" class="form-control MaxCount'. $i .'" value="0"></td> <td><input type="text" name="reworkStagePcs[]" class="form-control" value=""></td> </tr>';
    }
    
    // echo json_encode($data);
} else if(isset($_REQUEST['manual_pieces_entry'])) {
    
    
    $data = array(
        'entry_number' => $_REQUEST['processing_code'],
        'entry_date' => $_REQUEST['entry_date'],
        'assigned_emp' => $_REQUEST['employee'],
        'scanning_using' => $_REQUEST['scanning_using'],
        'order_id' => $_REQUEST['order_id'],
        'created_by' => $logUser,
        'created_unit' => $logUnit
    );
        
        
        if ($_REQUEST['processing_id'] == "") {
            Insert($_REQUEST['table'], $data);
            $inid = mysqli_insert_id($mysqli);
            $datas['ress'] = 0;
        } else {
            Update($_REQUEST['table'], $data, " WHERE id = '" . $_REQUEST['processing_id'] . "'");
            $inid = $_REQUEST['processing_id'];
            $datas['ress'] = 1;
        }
        
        $expp = explode('-', $_REQUEST['qr']);
        
        $bdl = $expp[0];
        $pcs = $expp[1];
        
        
        if($_REQUEST['head']=='qr') {
            
            $bundle = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.id, a.boundle_qr, a.variation_value, a.pcs_per_bundle, a.order_id, a.style, a.part, a.color, a.tot_ironing, a.ironed_pieces, a.tot_packing, a.packed_pieces FROM bundle_details a WHERE a.id='". $bdl ."'"));
            
            $total_qty = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_detalis WHERE id='". $bundle['style'] ."'"));
            
            if($_REQUEST['table']=='ironing') {
                
                // for($m=1; $m<=$bundle['pcs_per_bundle']; $m++) {
                //     $bdl[] = $m;
                // }
                
            $iron = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM ironing_detail WHERE bundleId = '". $bundle['id'] ."' AND ironing_id='". $inid ."'"));
                
                if($iron['piece_ironed'] == "") {
                    $piece_ironed = array(0 => $pcs);
                } else {
                    $piece_ironed = array_merge(explode(',', $iron['piece_ironed']), array(0 => $pcs));
                }
                
                
                $narr = array(
                    'ironing_id'        => $inid,
                    'order_id'          => $bundle['order_id'],
                    'style_id'          => $bundle['style'],
                    'part_id'           => $bundle['part'],
                    'color'             => $bundle['color'],
                    'order_qty'         => $total_qty['total_qty'],
                    'variation_value'   => $bundle['variation_value'],
                    'bundleId'          => $bundle['id'],
                    'piece_ironed'      => implode(',', $piece_ironed),
                    'ironing_qty'       => count($piece_ironed),
                    'entry_date' => $_REQUEST['entry_date'],
                );
                
                if($iron['id']=="") {
                    $indd = Insert('ironing_detail', $narr);
                } else {
                    $indd = Update('ironing_detail', $narr, " WHERE id = '" . $iron['id'] . "'");
                }
                
                if($indd) {
                    $tot_ironing = explode(',', $bundle['tot_ironing']);
                    
                    $key = array_search($pcs, $tot_ironing);
                    
                    if ($key !== false) {
                        unset($tot_ironing[$key]);
                    }
                    
                    if($bundle['ironed_pieces'] == "") {
                        $ironed_pieces = array(0 => $pcs);
                    } else {
                        $ironed_pieces = array_merge(explode(',', $bundle['ironed_pieces']), array(0 => $pcs));
                    }
                    
                    
                    $irdata = array(
                        'tot_ironing' => implode(',', $tot_ironing),
                        'ironed_pieces' => implode(',', $ironed_pieces),
                    );
                    
                    
                    Update('bundle_details', $irdata, " WHERE id = '" . $bundle['id'] . "'");
                }
                
            } else if($_REQUEST['table']=='packing') {
            
            $pack = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM packing_detail WHERE bundleId = '". $bundle['id'] ."' AND packing_id='". $inid ."'"));
                
                if($pack['piece_packed'] == "") {
                    $piece_packed = array(0 => $pcs);
                } else {
                    $piece_packed = array_merge(explode(',', $pack['piece_packed']), array(0 => $pcs));
                }
                
                
                $narr = array(
                    'packing_id'        => $inid,
                    'order_id'          => $bundle['order_id'],
                    'style_id'          => $bundle['style'],
                    'part_id'           => $bundle['part'],
                    'color'             => $bundle['color'],
                    'order_qty'         => $total_qty['total_qty'],
                    'variation_value'   => $bundle['variation_value'],
                    'bundleId'          => $bundle['id'],
                    'piece_packed'      => implode(',', $piece_packed),
                    'packing_qty'       => count($piece_packed),
                    'entry_date' => $_REQUEST['entry_date'],
                    
                );
                
                if($pack['id']=="") {
                    $indd = Insert('packing_detail', $narr);
                } else {
                    $indd = Update('packing_detail', $narr, " WHERE id = '" . $pack['id'] . "'");
                }
                
                if($indd) {
                    $tot_packing = explode(',', $bundle['tot_packing']);
                    
                    $key = array_search($pcs, $tot_packing);
                    
                    if ($key !== false) {
                        unset($tot_packing[$key]);
                    }
                    
                    if($bundle['packed_pieces'] == "") {
                        $packed_pieces = array(0 => $pcs);
                    } else {
                        $packed_pieces = array_merge(explode(',', $bundle['packed_pieces']), array(0 => $pcs));
                    }
                    
                    
                    $irdata = array(
                        'tot_packing' => implode(',', $tot_packing),
                        'packed_pieces' => implode(',', $packed_pieces),
                    );
                    
                    
                    Update('bundle_details', $irdata, " WHERE id = '" . $bundle['id'] . "'");
                }
                
            }
            
            
        } else if($_REQUEST['head']=='manual') {
        
        
            for($l=0; $l<count($_REQUEST['vvalue']); $l++) {
                
                if($_REQUEST['table']=='ironing') {
                    $ty = mysqli_query($mysqli, "SELECT * FROM ironing_detail WHERE variation_value='". $_REQUEST['vvalue'][$l] ."' AND ironing_id='". $inid ."' AND order_id='". $_REQUEST['order_id'] ."' AND style_id='". $_REQUEST['style_num'] ."' AND part_id='". $_REQUEST['partNum'] ."'");
                
                    $uiop = mysqli_num_rows($ty);
                    $fdc = mysqli_fetch_array($ty);
                
                    $narr = array(
                        'ironing_id' => $inid,
                        'order_id' => $_REQUEST['order_id'],
                        'style_id' => $_REQUEST['style_num'],
                        'part_id' => $_REQUEST['partNum'],
                        'order_qty' => $_REQUEST['or_q'][$l],
                        'cutting_qty' => $_REQUEST['ch_q'][$l],
                        'variation_value' => $_REQUEST['vvalue'][$l],
                        'ironing_qty' => $_REQUEST['ironing_qty'][$l],
                        );
                        
                    if($uiop==0) {
                        Insert('ironing_detail', $narr);
                    } else {
                        Update('ironing_detail', $narr, " WHERE id = '" . $fdc['id'] . "'");
                    }
                } else if($_REQUEST['table']=='packing') {
                    $ty = mysqli_query($mysqli, "SELECT * FROM packing_detail WHERE variation_value='". $_REQUEST['vvalue'][$l] ."' AND packing_id='". $inid ."' AND order_id='". $_REQUEST['order_id'] ."' AND style_id='". $_REQUEST['style_num'] ."' AND part_id='". $_REQUEST['partNum'] ."'");
                
                    $uiop = mysqli_num_rows($ty);
                    $fdc = mysqli_fetch_array($ty);
                
                    $narr = array(
                        'packing_id' => $inid,
                        'order_id' => $_REQUEST['order_id'],
                        'style_id' => $_REQUEST['style_num'],
                        'part_id' => $_REQUEST['partNum'],
                        'order_qty' => $_REQUEST['or_q'][$l],
                        'cutting_qty' => $_REQUEST['ch_q'][$l],
                        'ironing_qty' => $_REQUEST['iro_q'][$l],
                        'variation_value' => $_REQUEST['vvalue'][$l],
                        'packing_qty' => $_REQUEST['packing_qty'][$l],
                        );
                        
                    if($uiop==0) {
                        Insert('packing_detail', $narr);
                    } else {
                        Update('packing_detail', $narr, " WHERE id = '" . $fdc['id'] . "'");
                    }
                }
            }

        }


    $datas['inid'] = $inid;
    $datas['bundle_in'] = $smg; 
    
    
    echo json_encode($datas);
    
} else if(isset($_REQUEST['GetnewPartSize'])) {
    
    $yf = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.size_detail,a.style_no,b.order_code FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id=b.id WHERE a.sales_order_id = '". $_REQUEST['ordno'] ."' AND a.id = '". $_REQUEST['stylno'] ."'"));
    $prt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT part_name FROM part WHERE id = '". $_REQUEST['prtno'] ."'"));
    
    $sdet = json_decode($yf['size_detail']);
    
    for($p=0; $p<count($sdet); $p++)
    {
        $q1 = explode(',,', $sdet[$p]);
        $q2 = explode('=', $q1[0]);
        $q3 = explode('=', $q1[1]);
        
        $vvalue = mysqli_fetch_array(mysqli_query($mysqli, "SELECT type FROM variation_value WHERE id = '". $q2[1] ."'"));
        
        $chq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.ch_good_pcs) as ch_sum FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id WHERE a.variation_value = '". $q2[1] ."' AND b.order_id = '". $_REQUEST['ordno'] ."' AND b.style = '". $_REQUEST['stylno'] ."' AND b.part = '". $_REQUEST['prtno'] ."'"));
        
        
        $cqy = $chq['ch_sum'] ? $chq['ch_sum'] : 0;
        
        if($_REQUEST['type']=='ironing')
        {
            $irnTtl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(ironing_qty) as irnTtl FROM ironing_detail WHERE ironing_id < '". $_REQUEST['ironing_id'] ."' AND variation_value = '". $q2[1] ."' AND order_id = '". $_REQUEST['ordno'] ."' AND style_id = '". $_REQUEST['stylno'] ."' AND part_id = '". $_REQUEST['prtno'] ."'"));
            $fld = '<td>'. $irnTtl['irnTtl'] .'</td> <td>
                        <input type="hidden" name="vvalue[]" value="'. $q2[1] .'"> <input type="hidden" name="or_q[]" value="'. $q3[1] .'"> <input type="hidden" name="ch_q[]" value="'. $chq['ch_sum'] .'"> 
                        <input class="form-control" name="ironing_qty[]" data-max="'. $chq['ch_sum'] .'" data-has="'. $irnTtl['irnTtl'] .'" onkeyup="calMax('. $q2[1] .')" id="ironing_inp'. $q2[1] .'" style="width: 50%;"></td>';
            
        } else if($_REQUEST['type']=='packing') {
            $irron = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(ironing_qty) as irnTtl FROM ironing_detail WHERE variation_value = '". $q2[1] ."' AND order_id = '". $_REQUEST['ordno'] ."' AND style_id = '". $_REQUEST['stylno'] ."' AND part_id = '". $_REQUEST['prtno'] ."'"));
            
            $irnTtl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(packing_qty) as irnTtl FROM packing_detail WHERE packing_id < '". $_REQUEST['packing_id'] ."' AND variation_value = '". $q2[1] ."' AND order_id = '". $_REQUEST['ordno'] ."' AND style_id = '". $_REQUEST['stylno'] ."' AND part_id = '". $_REQUEST['prtno'] ."'"));
            
            $totPak = $irnTtl['irnTtl'] ? $irnTtl['irnTtl'] :0;
            $fld = '<td>'. $irron['irnTtl'] .'</td> <td> '. $totPak .'</td>
                        <td>
                        <input type="hidden" name="vvalue[]" value="'. $q2[1] .'"> <input type="hidden" name="iro_q[]" value="'. $irron['irnTtl'] .'"> <input type="hidden" name="or_q[]" value="'. $q3[1] .'"> <input type="hidden" name="ch_q[]" value="'. $chq['ch_sum'] .'"> 
                        <input class="form-control" name="packing_qty[]" data-max="'. $irron['irnTtl'] .'" data-has="'. $totPak .'" onkeyup="calMax('. $q2[1] .')" id="packing_inp'. $q2[1] .'" style="width: 50%;"></td>
                        ';
        }
        
        
        $json['tbody'][] = '<tr> <td>'. $vvalue['type'] .'</td> <td>'. $q3[1] .'</td> <td>'. $cqy .'</td>  '. $fld .'    </tr>';
    }
    
    $json['style'] = $yf['style_no'];
    $json['ordercode'] = $yf['order_code'];
    $json['part'] = $prt['part_name'];
    
    echo json_encode($json);
    
} else if(isset($_REQUEST['ironing_get_details'])) {
    $bbbi = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM ironing WHERE id='". $_REQUEST['id'] ."'"));
    
    $qry = "SELECT a.*, b.order_code, c.style_no, c.total_qty, d.part_name, sum(a.ironing_qty) as totCtng  ";
    $qry .= " FROM ironing_detail a ";
    $qry .= " LEFT JOIN sales_order b ON a.order_id=b.id ";
    $qry .= " LEFT JOIN sales_order_detalis c ON a.style_id=c.id ";
    $qry .= " LEFT JOIN part d ON a.part_id=d.id ";
    // $qry .= "LEFT JOIN color d ON b.color=d.id ";
    // $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
    $qry .= " WHERE a.ironing_id='". $_REQUEST['id'] ."' ";
    $qry .= " GROUP BY a.order_id,a.style_id ORDER BY a.id DESC";
    
    $qw = mysqli_query($mysqli, $qry);
    while($tbl = mysqli_fetch_array($qw)) {
    
        $json['tbody'][] = '<tr> <td>'. $tbl['order_code'] .'</td> <td>'. $tbl['style_no'] .'</td> <td>'. $tbl['part_name'] .'</td> <td>'. $tbl['totCtng'] .'</td> <td><i class="icon-copy fa fa-eye" aria-hidden="true" onclick="checkItoningSize('. $tbl['order_id'] .','. $tbl['style_id'] .','. $tbl['part_id'] .')"></i></td> </tr>';
    
    }
    
    echo json_encode($json);
} else if(isset($_REQUEST['packing_get_details'])) {
    $bbbi = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM packing WHERE id='". $_REQUEST['id'] ."'"));
    
    $qry = "SELECT a.*, b.order_code, c.style_no, c.total_qty, d.part_name, sum(a.packing_qty) as totCtng  ";
    $qry .= " FROM packing_detail a ";
    $qry .= " LEFT JOIN sales_order b ON a.order_id=b.id ";
    $qry .= " LEFT JOIN sales_order_detalis c ON a.style_id=c.id ";
    $qry .= " LEFT JOIN part d ON a.part_id=d.id ";
    // $qry .= "LEFT JOIN color d ON b.color=d.id ";
    // $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
    $qry .= " WHERE a.packing_id='". $_REQUEST['id'] ."' ";
    $qry .= " GROUP BY a.order_id,a.style_id ORDER BY a.id DESC";
    
    $qw = mysqli_query($mysqli, $qry);
    while($tbl = mysqli_fetch_array($qw)) {
    
        $json['tbody'][] = '<tr> <td>'. $tbl['order_code'] .'</td> <td>'. $tbl['style_no'] .'</td> <td>'. $tbl['part_name'] .'</td> <td>'. $tbl['totCtng'] .'</td> <td><i class="icon-copy fa fa-eye" aria-hidden="true" onclick="checkItoningSize('. $tbl['order_id'] .','. $tbl['style_id'] .','. $tbl['part_id'] .')"></i></td> </tr>';
    
    }
    
    echo json_encode($json);
} else if(isset($_REQUEST['viewAddedOddee'])) {
    
    $qry = "SELECT a.*, b.order_code, c.style_no, c.total_qty, d.part_name  ";
    $qry .= " FROM ironing_detail a ";
    $qry .= " LEFT JOIN sales_order b ON a.order_id=b.id ";
    $qry .= " LEFT JOIN sales_order_detalis c ON a.style_id=c.id ";
    $qry .= " LEFT JOIN part d ON a.part_id=d.id ";
    $qry .= " WHERE a.ironing_id = '". $_REQUEST['ironing_id'] ."' AND a.order_id='". $_REQUEST['ordno'] ."' AND a.style_id='". $_REQUEST['stylno'] ."' AND a.part_id='". $_REQUEST['prtno'] ."'  ";
    $qry .= " ORDER BY a.id DESC";
    
    $qw = mysqli_query($mysqli, $qry);
    while($tbl = mysqli_fetch_array($qw)) {

        $irnTtl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(ironing_qty) as irnTtl FROM ironing_detail
        WHERE ironing_id < '". $_REQUEST['ironing_id'] ."' AND variation_value = '". $tbl['variation_value'] ."' AND order_id = '". $_REQUEST['ordno'] ."' AND style_id = '". $_REQUEST['stylno'] ."' AND part_id = '". $_REQUEST['prtno'] ."'"));
        
        $bc = mysqli_fetch_array(mysqli_query($mysqli, "SELECT type FROM variation_value WHERE id= '".$tbl['variation_value']."'"));
        
        $json['style'] = $tbl['style_no'];
        $json['ordercode'] = $tbl['order_code'];
        $json['part'] = $tbl['part_name'];
    
        $ji = $irnTtl['irnTtl'] ? $irnTtl['irnTtl'] : 0;
        $json['tbody'][] = '<tr> <td>'. $bc['type'] .'</td> <td>'. $tbl['order_qty'] .'</td> <td class="d-none">'. $tbl['cutting_qty'] .'</td> <td>'. $ji .'</td> <td>'. $tbl['ironing_qty'] .'</td> </tr>';
    
    }
    
    
    
    echo json_encode($json);
    
} else if(isset($_REQUEST['viewAddedpackingg'])) {
    
    $qry = "SELECT a.*, b.order_code, c.style_no, c.total_qty, d.part_name  ";
    $qry .= " FROM packing_detail a ";
    $qry .= " LEFT JOIN sales_order b ON a.order_id=b.id ";
    $qry .= " LEFT JOIN sales_order_detalis c ON a.style_id=c.id ";
    $qry .= " LEFT JOIN part d ON a.part_id=d.id ";
    $qry .= " WHERE a.packing_id = '". $_REQUEST['packing_id'] ."' AND a.order_id='". $_REQUEST['ordno'] ."' AND a.style_id='". $_REQUEST['stylno'] ."' AND a.part_id='". $_REQUEST['prtno'] ."'  ";
    $qry .= " ORDER BY a.id DESC";
    
    $qw = mysqli_query($mysqli, $qry);
    while($tbl = mysqli_fetch_array($qw)) {
        
        $irnTtl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(packing_qty) as irnTtl FROM packing_detail
        WHERE packing_id < '". $_REQUEST['packing_id'] ."' AND variation_value = '". $tbl['variation_value'] ."' AND order_id = '". $_REQUEST['ordno'] ."' AND style_id = '". $_REQUEST['stylno'] ."' AND part_id = '". $_REQUEST['prtno'] ."'"));
        
        $bc = mysqli_fetch_array(mysqli_query($mysqli, "SELECT type FROM variation_value WHERE id= '".$tbl['variation_value']."'"));
        
        $json['style'] = $tbl['style_no'];
        $json['ordercode'] = $tbl['order_code'];
        $json['part'] = $tbl['part_name'];
    
        $hv = $irnTtl['irnTtl'] ? $irnTtl['irnTtl'] : 0;
        $json['tbody'][] = '<tr> <td>'. $bc['type'] .'</td> <td>'. $tbl['order_qty'] .'</td> <td class="d-none">'. $tbl['cutting_qty'] .'</td> <td class="d-none">'. $tbl['ironing_qty'] .'</td> <td class="d-none">'. $hv .'</td> <td>'. $tbl['packing_qty'] .'</td> </tr>';
    
    }
    
    
    
    echo json_encode($json);
    
} else if(isset($_REQUEST['PieceScanning_'])) {
    
        // foreach ($ioo as $ky => $valu) {
        //     $iop = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.id, a.bundle_number, b.order_id, b.style, b.part, b.color FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id WHERE a.id='" . $valu . "'"));
        //     $track[] = $iop['order_id'] . '->' . $iop['style'] . '->' . $iop['part'] . '->' . $iop['color'] . '->' . $iop['id'] . '->' . $iop['bundle_number'];
        // }
        
        // $track_n = json_encode($track);
         if($_REQUEST['head'] == 'manual') {
            $mult =$_REQUEST['multibundle'];
        } else if($_REQUEST['head'] == 'qr') {
            $mult = $_REQUEST['qr'];
        }
        

        $bnl = $mult;
        $bb = explode('-', $bnl);
        
        $ub = mysqli_fetch_array(mysqli_query($mysqli, "SELECT boundle_id,piece_scanned FROM processing_list WHERE id=".$_REQUEST['processing_id']));
        
        if($ub['boundle_id']=="")
        {
            $bu_id = $bb[0];
        } else {
            $uiop = explode(',', $ub['boundle_id']);
            $nbndl = array(0 => $bb[0]);
            
            $bu_id = implode(',', array_unique(array_merge($uiop, $nbndl)));
        }
        
        if($ub['piece_scanned']=="")
        {
            $bu_id3 = $bnl;
        } else {
            $uiop3 = explode(',', $ub['piece_scanned']);
            $nbndl3 = array(0 => $bnl);
            
            $bu_id3 = implode(',', array_unique(array_merge($uiop3, $nbndl3)));
        }


    if ($_REQUEST['process_type'] == "sewing_output") {

        $data = array(
            // 'processing_code' => $_REQUEST['processing_code'],
            'entry_date' => $_REQUEST['entry_date'],
            'boundle_id' => $bu_id,
            'piece_scanned' => $bu_id3,
            // 'bundle_track' => $track_n,
            // 'type' => $_REQUEST['process_type'],
            // 'scanning_type' => $_REQUEST['scanType'],
            // 'created_unit' => $_SESSION['loginCompany'],
            // 'created_date' => date('Y-m-d H:i:s')
        );
    } else if($_REQUEST['process_type'] == "checking_list") {

        $data = array(
            'entry_date' => $_REQUEST['entry_date'],
            'boundle_id' => $bu_id,
            'piece_scanned' => $bu_id3,
        );
    } else if($_REQUEST['process_type'] == "rework_entry") {

        $data = array(
            'entry_date' => $_REQUEST['entry_date'],
            'boundle_id' => $bu_id,
            'piece_scanned' => $bu_id3,
        );
    }


    Update('processing_list', $data, " WHERE id = '" . $_REQUEST['processing_id'] . "'");
    
    $inid = $_REQUEST['processing_id'];
    
    $ub1 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE id=".$bb[0]));
        
    if ($_REQUEST['process_type'] == "sewing_output") {
        if($ub1['s_out_complete']=="")
        {
            $bu_id1 = $bb[1];
        } else {
            $uiop1 = explode(',', $ub1['s_out_complete']);
            $nbndl1 = array(0 => $bb[1]);
            
            $bu_id1 = implode(',', array_unique(array_merge($uiop1, $nbndl1)));
        }
        
        // $complete_sewing = () ? 'yes' : NULL;
        
        if($ub1['pcs_per_bundle']==count(explode(',', $bu_id1)))
        {
            $complete_sewing = 'yes';
            mysqli_query($mysqli, "UPDATE bundle_details SET s_out_complete='". $bu_id1 ."', complete_sewing = 'yes', comp_sewing_date='".date('Y-m-d H:i:s')."' WHERE id='" . $bb[0] . "'");
        }
            
        mysqli_query($mysqli, "UPDATE bundle_details SET s_out_complete='". $bu_id1 ."', comp_sewing_date='".date('Y-m-d H:i:s')."' WHERE id='" . $bb[0] . "'");
        
    } else if($_REQUEST['process_type'] == "checking_list") {
        
        if($_REQUEST['pieces_type']==1) {
            $field = 'ch_good_pcs';
            
        } else if($_REQUEST['pieces_type']==6) {
            $field = 'ch_reject_pcs';
            
        } else {
            $field = 'ch_rework_pcs';
        }
        
        if($ub1[$field]=="")
        {
            $bu_id1 = $bb[1];
        } else {
            $uiop1 = explode(',', $ub1[$field]);
            $nbndl1 = array(0 => $bb[1]);
            
            $bu_id1 = implode(',', array_unique(array_merge($uiop1, $nbndl1)));
        }
        sort($bu_id1);
        
        $tot_good_pcs = count(explode(',', $bu_id1));

        $ty = mysqli_query($mysqli, "UPDATE bundle_details SET checking_date = '". date('Y-m-d') ."' AND tot_good_pcs = '". $tot_good_pcs ."' AND ". $field ."='". $bu_id1 ."' WHERE id='" . $bb[0] . "'");
        
        $hin = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM checking_output WHERE processing_list_id = '". $_REQUEST['processing_id'] ."' AND bundle_id = '". $bb[0] ."' AND checking_type = '". $_REQUEST['pieces_type'] ."'"));
        
        if($hin['pieces']=="") {
            $fff = $bb[1];
        } else {
            $gi = explode(',', $hin['pieces']);
            $gi1 = array(0 => $bb[1]);
            $fff = implode(',', array_unique(array_merge($gi, $gi1)));
        }
        
        
            $s_arr = array(
                'processing_list_id' => $_REQUEST['processing_id'],
                'bundle_id' => $bb[0],
                'checking_type' => $_REQUEST['pieces_type'],
                'pieces' => $fff,
            );
            
        if($hin['id']=="") {
            Insert('checking_output', $s_arr);
        } else {
            Update('checking_output', $s_arr, 'WHERE id ='. $hin['id']);
        }
        
        if($ty) {
            $swl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT ch_good_pcs, ch_reject_pcs, ch_rework_pcs, pcs_per_bundle FROM bundle_details WHERE id=".$bb[0]));
            
            if($swl['ch_good_pcs']=="") { $z1 = array(); } else { $z1 = explode(',', $swl['ch_good_pcs']); }
            if($swl['ch_reject_pcs']=="") { $z2 = array(); } else { $z2 = explode(',', $swl['ch_reject_pcs']); }
            if($swl['ch_rework_pcs']=="") { $z3 = array(); } else { $z3 = explode(',', $swl['ch_rework_pcs']); }
            
            $totP = array_merge($z1, $z2, $z3);
            
            for($op = 1; $op <= $swl['pcs_per_bundle']; $op++) {
                if(in_array($op, $totP)) {
                    $already_in[] = $op;
                } else {
                    $already_Notin[] = $op;
                }
            }
            
            mysqli_query($mysqli, "UPDATE bundle_details SET ch_missing_pcs ='". implode(',',$already_Notin) ."' WHERE id='" . $bb[0] . "'");
        }
    } else if($_REQUEST['process_type'] == "rework_entry") {
        
        $swl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT ch_missing_pcs, ch_rework_pcs FROM bundle_details WHERE id=".$bb[0]));
        
        $pArr = array(0 => $bb[1]);
        
        $rewrk = array_diff(explode(',', $swl['ch_rework_pcs']), $pArr);
        $msngk = array_merge(explode(',', $swl['ch_missing_pcs']), $pArr);
        
        $narY = array(
            'ch_rework_pcs' => implode(',', array_filter(array_unique($rewrk))),
            'ch_missing_pcs' => implode(',', array_filter(array_unique($msngk))),
        );
        
        Update('bundle_details', $narY, 'WHERE id = '. $bb[0]);
            
        // mysqli_query($mysqli, "UPDATE bundle_details SET ch_missing_pcs ='". implode(',',$already_Notin) ."' WHERE id='" . $bb[0] . "'");
        
        $swl2 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT id,pieces,completed_pcs FROM checking_output WHERE checking_type = '". $_REQUEST['rework_stage'] ."' AND bundle_id='". $bb[0] ."' ORDER BY id DESC"));
        
        
        $chAr = array(0 => $bb[1]);
        
        
        $pieces = array_diff(explode(',', $swl2['pieces']), $chAr);
        $chPcs = array_merge(explode(',', $swl2['completed_pcs']), $chAr);
        
        $narYew = array(
            'pieces' => implode(',', array_filter(array_unique($pieces))),
            'completed_pcs' => implode(',', array_filter(array_unique($chPcs))),
        );
        
        Update('checking_output', $narYew, 'WHERE id = '. $swl2['id']);
    }


    
    $datas['inid'] = $inid;
    $datas['bundle_in'] = $smg;
    
    echo json_encode($datas);

} else if(isset($_REQUEST['validateBundleQR_'])) {
    if($_REQUEST['type']=='ironig') {
        $swl = "SELECT * FROM bundle_details WHERE checking_complete = 'yes' AND boundle_qr='". $_REQUEST['qr'] ."'"; 
        $message = "Bundle Not Completed Checking";
    }
    
 
        $jn = mysqli_query($mysqli, $swl);
        $hn = mysqli_fetch_array($jn);
        
        if (mysqli_num_rows($jn) == 0) {
            $data['result'][] = 'notFound';
            $data['message'][] = $message;
        } else {
            $data['result'][] = $hn['id'];
        }
    
        echo json_encode($data);
        
} else if(isset($_REQUEST['ValidateOrderId'])) {
    
    $nji = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM sales_order WHERE order_code = '". $_REQUEST['ValidateOrderId'] ."'"));
    
    if($nji == 0) {
        $data['msg'][] = 0;
    } else {
        $data['msg'][] = 1;
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getBrand_Approval'])) {
    
    $ugb = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM brand WHERE id = '". $_REQUEST['brand'] ."'"));
    
    $nji = mysqli_query($mysqli, "SELECT id,name FROM mas_approval WHERE id IN (". $ugb['approvals'] .")");
    
    while($qqow = mysqli_fetch_array($nji)) {
        $data['select'][] = '<option value="'. $qqow['id'] .'" selected>'. $qqow['name'] .'</option>';
    }
        
    $tmpp = mysqli_query($mysqli, "SELECT * FROM time_management_template WHERE brand LIKE  '%". $_REQUEST['brand'] .",%' OR brand LIKE  '%,". $_REQUEST['brand'] ."%' OR brand LIKE  '%,". $_REQUEST['brand'] .",%'   ");
    
    $data['temp_name'][] = '<option value="">Select</option>';
    
    while($tmppLate = mysqli_fetch_array($tmpp)) {
        $data['temp_name'][] = '<option value="'. $tmppLate['id'] .'">'. $tmppLate['temp_name'] .'</option>';
    }
        
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getYarn_name'])) {
    
    $nji = mysqli_fetch_array(mysqli_query($mysqli, "SELECT yarn_name FROM mas_yarn WHERE id = '". $_REQUEST['getYarn_name'] ."'"));
    
    $color = mysqli_fetch_array(mysqli_query($mysqli, "SELECT color_name FROM color WHERE id = '". $_REQUEST['colorName'] ."'"));
    
    $data['yarn_name'][] = $nji['yarn_name'];
    $data['color_name'][] = $color['color_name'];

    echo json_encode($data);
    
} else if(isset($_REQUEST['getProces_name'])) {
    
    $nji = mysqli_fetch_array(mysqli_query($mysqli, "SELECT process_name FROM process WHERE id = '". $_REQUEST['getProces_name'] ."'"));
    
    $data['process_name'][] = $nji['process_name'];

    echo json_encode($data);
    
} else if(isset($_REQUEST['getDefaultProcess'])) {
    
    $xx = $_REQUEST['temp_id'];
    
    $def = mysqli_fetch_array(mysqli_query($mysqli, "SELECT value FROM settings WHERE ref = '". $_REQUEST['ref'] ."'"));
    
    $sql = mysqli_query($mysqli, "SELECT id,process_name FROM process WHERE id IN (". $def['value'] .")");
    while($nji = mysqli_fetch_array($sql)) {
        $data['trVal'][] = '<tr id="procesAddedtr'. $xx.$nji['id'] .'"><td style="width: 35%;"><input type="checkbox" id="processOrder'. $xx.$nji['id'] .'" class="processOrderC'. $xx .'" onclick="processOrderCK('. $xx.','.$nji['id'] .')">'. $nji['process_name'] .'</td><td id="processOrderTD'. $xx.$nji['id'] .'" class="processOrderxx'. $xx .'"  style="text-align:center;">-</td><td style="width: 35%;"><input type="hidden" id="processOrderTDInp'. $xx.$nji['id'] .'" class="processOrderTDInpC'. $xx .'" name="ProcessOrder[]">  <input type="hidden" value="'. $nji['id'] .'" name="ProcessId[]">  <input type="number" name="lossPer[]" class="form-control lossPer'. $xx .'" placeholder="Loss %"></td>
                                <td style="width: 10%;"><a onclick="deleteProcess('. $xx.$nji['id'] .')" class="border border-secondary rounded text-secondary"><i class="fa fa-trash"></i></a></tr>';
    }
    
    $data['trVal'][] = '<tr id="process_Fixtr'. $xx .'"> <td style="width: 35%;"> 
            <select class="custom-select2 form-control" name="" id="process_list'. $xx .'" style="width:100%">
                '. select_dropdown('process', array('id', 'process_name'), 'process_name ASC', '', 'WHERE process_type="Fabric"', '') .'
            </select> </td><td style="width: 20%;"></td>
        <td style="width: 35%;">
            <input type="number" name="" id="loss_per'. $xx .'" class="form-control loss_perClass11'. $xx .'" placeholder="Loss %">
            <input type="hidden" name="" id="process_count'. $xx .'" value="0" class="form-control">
        </td>
        <td style="width: 10%;"><a onclick="saveProcessDet('. $xx .')" class="border border-secondary rounded text-secondary "><i class="fa fa-plus processPlus'. $xx .'"></i></a></td></tr>';
    
    echo json_encode($data);
} else if(isset($_REQUEST['GetAddedcomponent_det'])) {
    // $def = mysqli_fetch_array(mysqli_query($mysqli, "SELECT component_detail FROM sales_order_fabric_program WHERE id = '". $_REQUEST['id'] ."'"));
    
    $def = mysqli_query($mysqli, "SELECT * FROM sales_order_fabric_components WHERE fabric_program_id = '". $_REQUEST['id'] ."'");
    
    while($rows = mysqli_fetch_array($def)) {
    
    // foreach(json_decode($def['component_detail']) as $value) {
        $exp = explode('--', $value);
        
        $vval = mysqli_fetch_array(mysqli_query($mysqli, "SELECT type FROM variation_value WHERE id = '". $exp[0] ."'"));
        
        // $data['tbody'][] = '<tr><td>'. $vval['type'] .'--'.$_REQUEST['id'] .'</td> <td>'. $exp[1] .'</td> <td>'. ($exp[1] + (($exp[2]/100)*$exp[1])) .'</td> <td>'. $exp[3] .'</td> <td>'. $exp[4] .'</td> <td>'. $exp[5] .'</td></tr>';
        $data['tbody'][] = '<tr><td>'. variation_value($rows['variation_value']) .'</td> <td>'. $rows['order_qty'] .'</td> <td>'. ($rows['order_qty'] + (($rows['excess']/100)*$rows['order_qty'])) .'</td> <td>'. $rows['finishing_dia'] .'</td> <td>'. $rows['piece_wt'] .'</td> <td>'. $rows['req_wt'] .'</td></tr>';
        
        $oq[] = $rows['order_qty'];
        $exq[] = ($rows['order_qty'] + (($rows['excess']/100)*$rows['order_qty']));
        $sum[] = $rows['req_wt'];
    }
    
    $data['tbody'][] = '<tr><td>Total</td> <td>'. array_sum($oq) .'</td> <td>'. array_sum($oq) .'</td> <td style="text-align:right" colspan="2">In-House Wt</td> <td>'. array_sum($sum) .'</td></tr>';
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getBudCategory_process'])) {
    
    $nmmm = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE style_id='" . $_REQUEST['id'] . "' AND budget_for='Production Budget'"));
    
    if($nmmm>0) {
        $usql = mysqli_query($mysqli, "SELECT b.id, b.process_name, a.bud_type, a.id as nidd FROM budget_process a LEFT JOIN process b ON a.process=b.id WHERE a.budget_for='Production Budget' AND a.style_id=" . $_REQUEST['id']);
    } else {
        $usql = mysqli_query($mysqli, "SELECT * FROM process WHERE category=" . $_REQUEST['catId']);
    }
    
    if(mysqli_num_rows($usql)>0) {
        while ($row = mysqli_fetch_array($usql)) {
    
            $fth = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE style_id='" . $_REQUEST['id'] . "' AND process='" . $row['id'] . "'"));
    
            $p_sum = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(price) as sum FROM sub_process WHERE process_id='" . $row['id'] . "'"));
        
            $subtd = '';
            
            $myqs = mysqli_query($mysqli, "SELECT a.*, b.process_name FROM sub_process a LEFT JOIN process b ON a.process_id=b.id WHERE a.process_id=" . $row['id']);
            if (mysqli_num_rows($myqs) > 0) {
                while ($fth = mysqli_fetch_array($myqs)) {
                    $sl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_subprocess WHERE style_id='" . $_REQUEST['id'] . "' AND subprocess='" . $fth['id'] . "' "));
    
                    $pic = $sl['price'] ? $sl['price'] : $fth['price'];
    
                    $subtd .= '<tr> <td>' . $fth['process_name'] . '</td> <td>' . $fth['sub_process_name'] . '</td> <td>
                    <input type="text" name="sub_price[]" class="form-control subprss' . $row['id'] . '" value="' . $pic . '" onkeyup="autocalculation(' . $row['id'] . ')" >
                    
                    <input type="hidden" name="sub_id[]" class="form-control" value="' . $fth['id'] . '">
                    <input type="hidden" name="pro_id[]" class="form-control" value="' . $fth['process_id'] . '">
                    
                    <input type="hidden" name="budget_subprocess_id[]" class="form-control" value="' . $sl['id'] . '">
                    </td> </tr>';
                }
            } else {
                $subtd .= '<tr> <td style="text-align:center;" colspan="3">Sub Process Not Found</td> </tr>';
            }
            
            $ppice = $fth['rate'] ? $fth['rate'] : $p_sum['sum'];
            
            if($row['nidd']=="") {
                $del_icon = '<i class="icon-copy fa fa-trash" aria-hidden="true" onclick="removeRow('. $row['id'] .')"></i>';
            } else {
                $ddii = $row['nidd']. ", 'budget_process'";
                $del_icon = '<i class="icon-copy fa fa-trash" aria-hidden="true" onclick="delete_data('. $ddii .')"></i>';
            }
            
            if($row['bud_type']=='combo') {
                $compp = 'selected';
                $all = $compp_ptt = '';
            } else if($row['bud_type']=='combo_part') {
                $compp_ptt = 'selected';
                $all = $compp = '';
            } else {
                $all = 'selected';
                $compp_ptt = $compp = '';
            }
            
            print '<tr id="tr'. $row['id'] .'">
                        <td>
                            <input type="hidden" name="budget_process[]" id="" value="'. $row['nidd'] .'">
                            <input type="hidden" name="process_id[]" id="" value="'. $row['id'] .'">
                            <input type="hidden" value="'. $_REQUEST['catId'] .'" name="category_id">
                            '. $row['process_name'] .'
                            <i class="icon-copy fa fa-eye" data-toggle="modal" data-target="#showprocessModal'. $row['id'] .'" data-type="edit" aria-hidden="true" style="float: right;font-size: 20px;" title="Sub Process List"></i>
                            
                            <div class="modal fade" id="showprocessModal'. $row['id'] .'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Sub Process List</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <div class="row">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Process</th>
                                                            <th>Operation</th>
                                                            <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>'. $subtd .'</tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <select class="form-control custom-select2 trigger_type" onchange="change_type('. $row['id'] .')" name="bud_type[]" id="bud_type'. $row['id'] .'" style="width: 100%;">
                                <option value="all" '. $all .'>All</option>
                                <option value="combo" '. $compp .'>Combo Wise</option>
                                <option value="combo_part" '. $compp_ptt .'>Combo & Part Wise</option>
                            </select>
                        </td>
                        <td class="all_'. $row['id'] .'"><input type="text" name="prate[]" id="prate'. $row['id'] .'" class="form-control" value="'. $ppice .'" placeholder="Rate"></td>
                        <td class="all_'. $row['id'] .'"><input type="text" name="revised_rate[]" id="" class="form-control" value="'. $fth['revised_rate'] .'" placeholder="Revised Rate"></td>
                        <td class="all_'. $row['id'] .'"><input type="text" name="rework_rate[]" id="" class="form-control" value="'. $fth['rework_rate'] .'" placeholder="Rework Rate"></td>
                        <td class="all_'. $row['id'] .'">'. $del_icon .'</td>
                    </tr>
                    <tr class="d-none"><td colspan="6"><table class="table table-bordered" style="background: #f7f7f7;" id="type_tr'. $row['id'] .'"><thead style="background: #e5e5e5d6;"></thead><tbody></tbody></table></td></tr>
                    ';
        }
    } else {
        print '<tr> <td style="text-align:center;" colspan="5">Process Not Found</td> </tr>';
    }
    
} else if(isset($_REQUEST['getBudgetStatus'])) {
    
    $tot_prod = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE budget_for = 'Production Budget' AND so_id=" . $_REQUEST['id']));

    $app_prod = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE budget_for = 'Production Budget' AND is_approved='true' AND so_id=" . $_REQUEST['id']));

    if ($tot_prod == 0) {
        $pbud = '<span class="border border-danger rounded text-danger">Budget Not Created</span>';
        $bud = 0;
    } else if($tot_prod > 0 && $app_prod == 0) {
        $pbud = '<span class="border border-warning rounded text-warning">Budget Not Reviewed</span>';
        $bud = 2;
    } else if($tot_prod == $app_prod) {
        $pbud = '<span class="border border-success rounded text-success">Budget Approved</span>';
        $bud = 1;
    } else if($tot_prod >= $app_prod) {
        $pbud = '<span class="border border-info rounded text-info">Budget Partially Approved</span>';
        $bud = 1;
    }
    
    $tot_fabr = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE budget_for = 'Fabric Budget' AND so_id=" . $_REQUEST['id']));

    $app_fabr = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE budget_for = 'Fabric Budget' AND is_approved='true' AND so_id=" . $_REQUEST['id']));

    if ($tot_fabr == 0) {
        $fbud = '<span class="border border-danger rounded text-danger">Budget Not Created</span>';
    } else if($tot_fabr > 0 && $app_fabr == 0) {
        $fbud = '<span class="border border-warning rounded text-warning">Budget Not Reviewed</span>';
    } else if($tot_fabr == $app_fabr) {
        $fbud = '<span class="border border-success rounded text-success">Budget Approved</span>';
    } else if($tot_fabr >= $app_fabr) {
        $fbud = '<span class="border border-info rounded text-info">Budget Partially Approved</span>';
    }
    
    $tot_acce = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE budget_for = 'Accessories Budget' AND so_id=" . $_REQUEST['id']));

    $app_acce = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE budget_for = 'Accessories Budget' AND is_approved='true' AND so_id=" . $_REQUEST['id']));

    if ($tot_acce == 0) {
        $accb = '<span class="border border-danger rounded text-danger">Budget Not Created</span>';
    } else if($tot_acce > 0 && $app_acce == 0) {
        $accb = '<span class="border border-warning rounded text-warning">Budget Not Reviewed</span>';
    } else if($tot_acce == $app_acce) {
        $accb = '<span class="border border-success rounded text-success">Budget Approved</span>';
    } else if($tot_acce >= $app_acce) {
        $accb = '<span class="border border-info rounded text-info">Budget Partially Approved</span>';
    }
    
    
    
    $data['response'][] = '<tr><td>Production Budget</td> <td>'. $pbud .'</td></tr><tr><td>Fabric Budget</td> <td>'. $fbud .'</td><tr><td>Accessories Budget</td> <td>'. $accb .'</td></tr>';
    
    echo json_encode($data);
} else if(isset($_REQUEST['showTimeTemplate'])) {
    
    $arr = array(
        'so_approval' => 'SO Approval',
        'fab_program' => 'Fabric Program',
        'access_program' => 'Accessories Program',
        'budget' => 'Budget',
        'budget_approval' => 'Budget Approval',
    );
    
    $uip = mysqli_query($mysqli, "SELECT a.*, b.department_name FROM time_management_template_det a LEFT JOIN department b ON a.res_dept = b.id WHERE a.temp_id = '". $_REQUEST['id'] ."'");
    
    if($_REQUEST['typ'] == 'view') {
        
        while($row = mysqli_fetch_array($uip)) {
            $rq = ($row['calculation_type']=='asc') ? 'Order Date' : 'Delivery Date';
            $data['tbody'][] = '<tr><td>'. $arr[$row['activity']] .'</td> <td>'. $rq .'</td> <td>'. $row['start_day'] .'</td> <td>'. $row['end_day'] .'</td> <td>'. $row['department_name'] .'</td> </tr>';
        }
    } else if($_REQUEST['typ'] == 'edit') {
        
        while($row = mysqli_fetch_array($uip)) {
            $rq = ($row['calculation_type']=='desc') ? 'selected' : '';
            $data['tbody'][] = '<tr><td><input type="hidden" name="insId[]" value="'. $row['id'] .'"> <input type="hidden" name="tempId" value="'. $_REQUEST['id'] .'"> '. $arr[$row['activity']] .'</td> <td><select class="form-control custom-select2" name="calculation_type[]"> <option value="asc" >Order Date</option> <option value="desc" '. $rq .'>Delivery Date</option> </option> </td> <td><input type="number" value="'. $row['start_day'] .'" class="form-control e_startDt" name="start_day[]"></td> <td><input type="number" value="'. $row['end_day'] .'" class="form-control e_endDt" name="end_day[]"></td> <td><select class="form-control custom-select2 e_respDept" name="res_dept[]"> '. select_dropdown('department', array('id', 'department_name'), 'department_name ASC', $row['res_dept'], '', '') .'</td> </tr>';
        }
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getAccessoriesTyp'])) {
    
    $uip = mysqli_query($mysqli, "SELECT a.* FROM mas_accessories a WHERE a.acc_type = '". $_REQUEST['type'] ."'");
    
    $data['acc_name'][] = '<option value="">Select</option>';
    while($row = mysqli_fetch_array($uip)) {
        $data['acc_name'][] = '<option value="'. $row['id'] .'">'. $row['acc_name'] .'</option>';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getAccessDetails'])) {
    
    $sizeWise = $_REQUEST['sizeWise'];
    $colorWise = $_REQUEST['colorWise'];
    
    $sty = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.id, a.size_detail, a.part_detail FROM sales_order_detalis a WHERE a.id = '". $_REQUEST['id'] ."'"));
    
    $size = json_decode($sty['size_detail']);
    $part = json_decode($sty['part_detail']);
    
    if($colorWise == 'no' && $sizeWise == 'no') {
        
        $data['thead'][] = '<tr><th>Req</th><th>Pcs</th></tr>';
        
        $inrd = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_accessories_det a WHERE a.program_id = '". $_REQUEST['pid'] ."' AND variation_value IS NULL AND color IS NULL"));
        
        $pcs = $inrd['det_pcs'] ? $inrd['det_pcs'] : '';
        $req = $inrd['det_req'] ? $inrd['det_req'] : '';
        
        $data['tbody'][] = '<tr><td><input type="number" class="form-control" name="det_req[]" placeholder="Req" value="'. $req .'"></td><td><input type="number" class="form-control" name="det_pcs[]" placeholder="Pcs" value="'. $pcs .'"></td></tr>';
        
    } else if($colorWise == 'no' && $sizeWise == 'yes') {
        $data['thead'][] = '<tr><th>Size</th><th>Req</th><th>Pcs</th></tr>';

        $qry = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sales_order_detail_id = '". $_REQUEST['id'] ."' GROUP BY variation_value ");
        while($ress = mysqli_fetch_array($qry)) {
        
        // foreach($size as $size_n) {
        //     $iyd = explode(',,', $size_n);
        //     $ex_a = explode('=', $iyd[0]);
        //     $yy = mysqli_fetch_array(mysqli_query($mysqli, "SELECT type FROM variation_value WHERE id='" . $ex_a[1] . "'"));
            
            $inrd = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_accessories_det a WHERE a.program_id = '". $_REQUEST['pid'] ."' AND variation_value = '". $ress['variation_value'] ."' AND color IS NULL"));
        
            $pcs = $inrd['det_pcs'] ? $inrd['det_pcs'] : '';
            $req = $inrd['det_req'] ? $inrd['det_req'] : '';
                
            $data['tbody'][] = '<tr><td><input type="hidden" name="sod_size[]" value="'. $ress['id'] .'"> <input type="hidden" value="'. $ress['variation_value'] .'" name="sizee[]">'. variation_value($ress['variation_value']) .'</td><td><input type="number" class="form-control" name="det_req[]" placeholder="Req" value="'. $req .'"></td><td><input type="number" class="form-control" name="det_pcs[]" placeholder="Pcs" value="'. $pcs .'"></td></tr>';
        }
        
    } else if($colorWise == 'yes' && $sizeWise == 'no') {
        
        $data['thead'][] = '<tr><th>Color</th><th>Req</th><th>Pcs</th></tr>';

        $qry = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sales_order_detail_id = '". $_REQUEST['id'] ."' GROUP BY color_id ");
        while($ress = mysqli_fetch_array($qry)) {
        
        // foreach($part as $part_n) {
        //     $pid = explode(',,', $part_n);
        //     $pxA = explode('=', $pid[1]);
        //     $clr = mysqli_fetch_array(mysqli_query($mysqli, "SELECT color_name FROM color WHERE id='" . $pxA[1] . "'"));
            $inrd = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_accessories_det a WHERE a.program_id = '". $_REQUEST['pid'] ."' AND color = '". $ress['color_id'] ."' AND variation_value IS NULL"));
        
            $pcs = $inrd['det_pcs'] ? $inrd['det_pcs'] : '';
            $req = $inrd['det_req'] ? $inrd['det_req'] : '';
            
            $data['tbody'][] = '<tr><td><input type="hidden" name="sod_size[]" value="'. $ress['id'] .'"> <input type="hidden" value="'. $ress['color_id'] .'" name="colorr[]">'. color_name($ress['color_id']) .'</td><td><input type="number" class="form-control" name="det_req[]" placeholder="Req" value="'. $req .'"></td><td><input type="number" class="form-control" name="det_pcs[]" placeholder="Pcs" value="'. $pcs .'"></td></tr>';
        }
        
    } else if($colorWise == 'yes' && $sizeWise == 'yes') {
        
        $data['thead'][] = '<tr><th>Size</th><th>Material Color</th><th>Req</th><th>Pcs</th></tr>';

        $qry = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sales_order_detail_id = '". $_REQUEST['id'] ."' GROUP BY variation_value ");
        while($ress = mysqli_fetch_array($qry)) {
        // foreach($size as $size_n) {
        //     $iyd = explode(',,', $size_n);
        //     $ex_a = explode('=', $iyd[0]);
        //     $yy = mysqli_fetch_array(mysqli_query($mysqli, "SELECT type FROM variation_value WHERE id='" . $ex_a[1] . "'"));
            $inrd = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_accessories_det a WHERE a.program_id = '". $_REQUEST['pid'] ."' AND variation_value = '". $ress['variation_value'] ."' AND color IS NOT NULL"));
        
            $pcs = $inrd['det_pcs'] ? $inrd['det_pcs'] : '';
            $req = $inrd['det_req'] ? $inrd['det_req'] : '';
                
            $data['tbody'][] = '<tr><td><input type="hidden" name="sod_size[]" value="'. $ress['id'] .'"> <input type="hidden" value="'. $ress['variation_value'] .'" name="sizee[]">'. variation_value($ress['variation_value']) .'</td><td><select class="form-control custom-select2 sel2" name="mat_color[]"> '. select_dropdown('color', array('id', 'color_name'), 'id ASC', $inrd['color'], '', '') .'</select></td><td><input type="number" class="form-control" name="det_req[]" placeholder="Req" value="'. $req .'"></td><td><input type="number" class="form-control" name="det_pcs[]" placeholder="Pcs" value="'. $pcs .'"></td></tr>';
        }
    } else {
        $data['thead'][] = '';
        $data['tbody'][] = '';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['GetAddedaccDet'])) {    
    
    $re = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_accessories_program WHERE id = '". $_REQUEST['id'] ."'"));
    
    $colorWise = $re['color_wise'];
    $sizeWise = $re['size_wise'];
    
    $sty = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.id, a.size_detail, a.part_detail FROM sales_order_detalis a WHERE a.id = '". $_REQUEST['id'] ."'"));
    
    $det = mysqli_query($mysqli, "SELECT * FROM sales_order_accessories_det WHERE program_id = '". $_REQUEST['id'] ."'");
    
    if($colorWise == 'no' && $sizeWise == 'no') {
        
        $data['thead'][] = '<tr><th>Req</th><th>Pcs</th></tr>';
        
        $cols = 2;
        
        while($ab = mysqli_fetch_array($det)) {
            $data['tbody'][] = '<tr><td>'. $ab['det_req'] .'</td><td>'. $ab['det_pcs'] .'</td></tr>';
        }
    } else if($colorWise == 'no' && $sizeWise == 'yes') {
        $data['thead'][] = '<tr><th>Size</th><th>Req</th><th>Pcs</th></tr>';
        
        $cols = 3;
        
        while($ab = mysqli_fetch_array($det)) {
            $re = mysqli_fetch_array(mysqli_query($mysqli, "SELECT type FROM variation_value WHERE id = '". $ab['variation_value'] ."'"));
            
            $data['tbody'][] = '<tr><td>'. $re['type'] .'</td><td>'. $ab['det_req'] .'</td><td>'. $ab['det_pcs'] .'</td></tr>';
        }
        
    } else if($colorWise == 'yes' && $sizeWise == 'no') {
        
        $data['thead'][] = '<tr><th>Color</th><th>Req</th><th>Pcs</th></tr>';
        
        $cols = 3;
        
        while($ab = mysqli_fetch_array($det)) {
            $re = mysqli_fetch_array(mysqli_query($mysqli, "SELECT color_name FROM color WHERE id = '". $ab['color'] ."'"));
            
            $data['tbody'][] = '<tr><td>'. $re['color_name'] .'</td><td>'. $ab['det_req'] .'</td><td>'. $ab['det_pcs'] .'</td></tr>';
        }
        
    } else if($colorWise == 'yes' && $sizeWise == 'yes') {
        
        $data['thead'][] = '<tr><th>Size</th><th>Material Color</th><th>Req</th><th>Pcs</th></tr>';
        
        
        $cols = 4;
        
        while($ab = mysqli_fetch_array($det)) {
            $re = mysqli_fetch_array(mysqli_query($mysqli, "SELECT type FROM variation_value WHERE id = '". $ab['variation_value'] ."'"));
            $clr = mysqli_fetch_array(mysqli_query($mysqli, "SELECT color_name FROM color WHERE id = '". $ab['color'] ."'"));
            
            $data['tbody'][] = '<tr><td>'. $re['type'] .'</td><td>'. $clr['color_name'] .'</td><td>'. $ab['det_req'] .'</td><td>'. $ab['det_pcs'] .'</td></tr>';
        }
    } else {
        $data['thead'][] = '';
        $data['tbody'][] = '';
    }
    
    
    if(mysqli_num_rows($det)==0) {
        $data['tbody'][] = '<tr><td style="text-align:center" colspan="'. $cols .'">Nothing Found</td></tr>';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['checkStatusOfRegisteredUser'])) {
    
    $qr = mysqli_query($mysqli, "SELECT * FROM employee_detail_temp WHERE mobile = '". $_REQUEST['mobile'] ."'");
    
    $num = mysqli_num_rows($qr);
    
    $sqql = mysqli_fetch_array($qr);


    if($num==0) {
        $data['title'][] = "Check Mobile Number!";
        $data['text'][] = "This Mobile Number Not yet Registered!";
        $data['type'][] = "error";
    } else if($sqql['is_approved'] == 'yes') {
        $data['title'][] = "Approved!";
        $data['text'][] = "You can Login Benso App!";
        $data['type'][] = "success";
    } else if($sqql['is_approved'] == 'no') {
        $data['title'][] = "Rejected!";
        $data['text'][] = $sqql['approved_notes'];
        $data['type'][] = "warning";
    } else if($sqql['is_approved'] == NULL) {
        $data['title'][] = "Not Yet Reviewed!";
        $data['text'][] = "Your Request Waiting for Review!";
        $data['type'][] = "info";
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getMultiBoStyle'])) {

    $qr = mysqli_query($mysqli, "SELECT id, style_no,sales_order_id FROM sales_order_detalis WHERE sales_order_id IN (". $_REQUEST['id'] .") ");
    
    while($sqql = mysqli_fetch_array($qr)) {
        
        $data['option'][] = '<option value="'. $sqql['id'] .'">'. $sqql['style_no'] .'</option>';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getMultiBoStylePart'])) {

    $qr = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sales_order_detail_id IN (". $_REQUEST['id'] .") ");
    
    while($sqql = mysqli_fetch_array($qr)) {
        $data['option'][] = '<option value="'. $sqql['id'] .'">'. part_name($sqql['part_id']) .' | '. color_name($sqql['color_id']) .'</option>';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['orderWiseProductionReport'])) {
    
    $order_id = $_REQUEST['order_id'];
    
    $style_id = $_REQUEST['style'];
    
    $part_id = $_REQUEST['part'];
    // $qr = mysqli_query($mysqli, "SELECT id, style_no, sales_order_id, part_detail FROM sales_order_detalis WHERE id IN (". $_REQUEST['id'] .") ");
    
    // while($sqql = mysqli_fetch_array($qr)) {
    
    if($style_id == "" && $order_id != "") {
        
        $qr1 = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sales_order_id IN (". $order_id .") ");
        
        while($resq = mysqli_fetch_array($qr1)) {
            
            $orderId = $resq['sales_order_id'];
            $style = $resq['sales_order_detail_id'];
            $part = $resq['part_id'];
            $color = $resq['color_id'];
            
            $sod_part = $resq['id'];
            
            $sty_dett = mysqli_fetch_array(mysqli_query($mysqli, "SELECT item_image, delivery_date FROM sales_order_detalis WHERE id = '". $style ."'"));
        ?>
            <div class="pb-20">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td style="min-width: 100px;"><img src="uploads/so_img/<?= sales_order_code($orderId) .'/'. $sty_dett['item_image']; ?>" style="widtd: 50px;height: 50px;"></td>
                            <td class="text-right" style="font-weight: bold;padding-left: 10px;">BO :</td>
                            <td><?= sales_order_code($orderId); ?></td>
                            <td class="text-right" style="font-weight: bold;padding-left: 10px;">Style :</td>
                            <td><?= sales_order_style($style); ?></td>
                            <td class="text-right" style="font-weight: bold;padding-left: 10px;">Part :</td>
                            <td><?= part_name($part); ?></td>
                            <td class="text-right" style="font-weight: bold;padding-left: 10px;">Color :</td>
                            <td><?= color_name($color); ?></td>
                            <td class="text-right" style="font-weight: bold;padding-left: 10px;">Delivery Date :</td>
                            <td><?= date('d-m-y', strtotime($sty_dett['delivery_date'])); ?></td>
                        </tr>
                    </thead>
                </table>
                
                <table class="table table-striped table-bordered" border="1" style="border-collapse: collapse; width:100%" >
                    <thead>
                        <tr style="font-weight: bold;">
                            <td style="font-weight: bold;">Size</td>
                            <?php
                                $td_size = $td_order_qty = $td_cutPlan = $td_cut_qty = $td_cut_bal = $td_sewingIn = $td_sewingOut = $td_Ch_good = $td_Ch_rework = $td_Ch_rejection = $td_Ch_ironPack = '';
                                $td_size1 = $td_order_qty1 = $td_cutPlan1 = $td_cut_qty1 = $td_cut_bal1 = $td_sewingIn1 = $td_sewingOut1 = $td_Ch_good1 = $td_Ch_rework1 = $td_Ch_rejection1 = $td_Ch_ironPack1 = 0;
                                
                                $ff = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = '". $resq['sod_combo'] ."'");
                                
                                while($sod_siz = mysqli_fetch_array($ff)) {
                                    
                                    // $ex1 = explode(',,', $size_detail);
                                    $vval = $sod_siz['variation_value'];
                                    $oqty = $sod_siz['size_qty'];
                                    $excess = $sod_siz['excess_per'];
                                    
                                    $sod_size = $sod_siz['id'];
                                    $WHERE = "a.sod_part = '". $sod_part ."' AND a.sod_size = '". $sod_size ."'";
                                    
                                    $cutting_ = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a WHERE $WHERE"));
                                    
                                    $sewingIn = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a WHERE a.in_sewing = 'yes' AND $WHERE"));
                                    
                                    $sewingOut = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a WHERE a.complete_sewing = 'yes' AND $WHERE"));
                                    
                                    $td_size .= '<td  style="font-weight: bold;">'. variation_value($vval) .'</td>';
                                    $td_order_qty .= '<td>'. $oqty .'</td>';
                                    $td_cutPlan .= '<td>'. $exss_qtyy = round((($excess/100) * $oqty) + $oqty) .'</td>';
                                    $td_cut_qty .= '<td>'. $cutting_['pcs_per_bundle'] .'</td>';
                                    $td_cut_bal .= '<td>'. $cut_bal = ($exss_qtyy - $cutting_['pcs_per_bundle']) .'</td>';
                                    $td_sewingIn .= '<td>'. $sewingIn['pcs_per_bundle'] .'</td>';
                                    $td_sewingOut .= '<td>'. $sewingOut['pcs_per_bundle'] .'</td>';
                                    $td_Ch_good .= '<td></td>';
                                    $td_Ch_rework .= '<td></td>';
                                    $td_Ch_rejection .= '<td></td>';
                                    $td_Ch_ironPack .= '<td></td>';
                                    
                                    
                                    $td_order_qty1 += $oqty;
                                    $td_cutPlan1 += $exss_qtyy;
                                    $td_cut_qty1 += $cutting_['pcs_per_bundle'];
                                    $td_cut_bal1 += $cut_bal;
                                    $td_sewingIn1 += $sewingIn['pcs_per_bundle'];
                                    $td_sewingOut1 += $sewingOut['pcs_per_bundle'];
                                }
                                
                                echo $td_size;
                            ?>
                            <td style="font-weight: bold;">Total</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Order Qty</td>
                            <?= $td_order_qty; ?>
                            <td style="font-weight: bold;"><?= $td_order_qty1; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Cut Plan Qty</td>
                            <?= $td_cutPlan; ?>
                            <td style="font-weight: bold;"><?= $td_cutPlan1; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Cutting Qty</td>
                            <?= $td_cut_qty; ?>
                            <td style="font-weight: bold;"><?= $td_cut_qty1; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Cut-Balance</td>
                            <?= $td_cut_bal; ?>
                            <td style="font-weight: bold;"><?= $td_cut_bal1; ?></td>
                        </tr>
                        <?php
                            // $gkm  = mysqli_query($mysqli, "SELECT a.process, b.process_name FROM budget_process a LEFT JOIN process b ON a.process = b.id WHERE b.is_default IS NULL AND a.so_id = ". $orderId1);
                            // $m=1;
                            // while($pss = mysqli_fetch_array($gkm)) {
                        ?>
                            <!--<tr>-->
                                <!--<td style="font-weight: bold;"><?= $pss['process_name']; ?> Out / In</td>-->
                                
                                <?php
                                
                                    // $sel = mysqli_query($mysqli, "SELECT a.boundle_id, a.is_inwarded FROM processing_list a WHERE a.order_id = '". $orderId1 ."' AND a.type = 'process_outward' AND a.process_id = '". $pss['process'] ."'");
                                    // while($fetch = mysqli_fetch_array($sel)) {
                                        
                                    //     foreach(explode(',', $fetch['boundle_id']) as $uio) {
                                    //         $narr[$m][] = $fetch['is_inwarded'];
                                    //     }
                                        
                                    //     $boundle_id[$m][] = $fetch['boundle_id'];
                                        
                                        
                                    // }
                                    
                                    // $pss_out = $pss_in = 0;
                                    
                                    // foreach(json_decode($sty_dett['size_detail']) as $newSize) {
                                        
                                    //     $ex1 = explode(',,', $newSize);
                            
                                    //     $vval = explode('=', $ex1[0]);
                                        
                                    //     print '<td>';
                                        
                                    //         $pcs_per_bundle = 0;
                                    //         $pcs_per_bundle_imward = 0;
                                            
                                    //         foreach(explode(',', implode(',', $boundle_id[$m])) as $key => $ass) {
                                    
                                    //             $sel = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.pcs_per_bundle FROM bundle_details a WHERE a.order_id = '". $orderId1 ."' AND a.style = '". $style ."' AND a.part = '". $part ."' AND a.color = '". $color ."' AND a.variation_value = '". $vval[1] ."' AND a.id =". $ass));
                                                
                                    //             $pcs_per_bundle += $sel['pcs_per_bundle'];
                                                
                                    //             if($narr[$m][$key]==1) {
                                    //                 $pcs_per_bundle_imward += $sel['pcs_per_bundle'];
                                    //             } else {
                                    //                 $pcs_per_bundle_imward += 0;
                                    //             }
                                    //         }
                                    //     print $pcs_per_bundle .' / '. $pcs_per_bundle_imward;
                                        
                                    //     $pss_out += $pcs_per_bundle;
                                    //     $pss_in += $pcs_per_bundle_imward;
                                        
                                    //     print '</td>';
                                    // }
                                ?>
                                <!--<td><?= $pss_out.' / '.$pss_in ?></td>-->
                            <!--</tr>-->
                        <?php //$m++; } ?>
                        <tr>
                            <td style="font-weight: bold;">Sewing In</td>
                            <?= $td_sewingIn; ?>
                            <td style="font-weight: bold;"><?= $td_sewingIn1; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Sewing Out</td>
                            <?= $td_sewingOut; ?>
                            <td style="font-weight: bold;"><?= $td_sewingOut1; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Checking Good</td>
                            <?= $td_Ch_good; ?>
                            <td style="font-weight: bold;"><?= $td_sewingOut1; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Checking Rework</td>
                            <?= $td_Ch_rework; ?>
                            <td style="font-weight: bold;"><?= $td_sewingOut1; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Checking Rejection</td>
                            <?= $td_Ch_rejection; ?>
                            <td style="font-weight: bold;"><?= $td_sewingOut1; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Iron&Pack</td>
                            <?= $td_Ch_ironPack; ?>
                            <td style="font-weight: bold;"><?= $td_sewingOut1; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Shipped Qty</td>
                            <?= $td_Ch_ironPack; ?>
                            <td style="font-weight: bold;"><?= $td_sewingOut1; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Shipped Balance</td>
                            <?= $td_Ch_ironPack; ?>
                            <td style="font-weight: bold;"><?= $td_sewingOut1; ?></td>
                        </tr>
                    </thead>
                </table>
            </div>
        
        <?php
        }
    }

} else if(isset($_REQUEST['saveReworkHead'])) {
    
     $data = array(
            'processing_code' => $_REQUEST['processing_code'],
            'scanning_type' => 'piece',
            'scanning_using' => $_REQUEST['scanning_using'],
            'rework_stage' => $_REQUEST['rework_stage'],
            'order_id' => $_REQUEST['order_id'],
            'entry_date' => $_REQUEST['entry_date'],
            'assigned_emp' => $_REQUEST['employee'],
            'type' => $_REQUEST['process_type'],
            'created_by' => $logUser,
            'created_unit' => $logUnit
        );
        
    $ress = Insert('processing_list', $data);
    $inid = mysqli_insert_id($mysqli);
    
    if($ress) {
        $data['ress'][] = 0;
        $data['inid'][] = $inid;
    } else {
        $data['ress'][] = 1;
    }
    echo json_encode($data);
} else if(isset($_REQUEST['getQC_status'])) {
    
    
    $type = $_REQUEST['type'];
    $tab = $_REQUEST['tab'];
    $id = $_REQUEST['id'];
    
    
    $p_list = mysqli_fetch_array(mysqli_query($mysqli, "SELECT id, processing_code, boundle_id, order_id FROM processing_list WHERE id = ". $_REQUEST['id']));

    $s_order = mysqli_fetch_array(mysqli_query($mysqli, "SELECT order_code FROM sales_order WHERE id = ". $p_list['order_id']));
    
    if($type=='total') {
        
        if($tab==1) {
            $hn = mysqli_fetch_array(mysqli_query($mysqli, "SELECT id, sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE id IN (". $p_list['boundle_id'] .")"));
            $QtY = $hn['pcs_per_bundle'];
            $rId = $hn['id'];
            
        } else if($tab==2) {
            $hn = mysqli_query($mysqli, "SELECT id, s_out_complete FROM bundle_details WHERE id IN (". $p_list['boundle_id'] .")");
            $ar = array();
            while($row = mysqli_fetch_array($hn)) {
                $ar = array_merge($ar, explode(',', $row['s_out_complete']));
            }
            $QtY = count($ar);
            $rId = $row['id'];
            
        } else if($tab==3) {
            $hn = mysqli_query($mysqli, "SELECT id, ch_good_pcs FROM bundle_details WHERE id IN (". $p_list['boundle_id'] .")");
            $ar1 = array();
            while($row = mysqli_fetch_array($hn)) {
                $ar1 = array_merge($ar1, explode(',', $row['ch_good_pcs']));
            }
            $QtY = count($ar1);
            $rId = $row['id'];
            
        }
        
        $data['body'][] = '
            <table class="table">
                <thead>
                    <tr>
                        <th>BO</th>
                        <th>Qty</th>
                        <th>Approved</th>
                        <th>Critical</th>
                        <th>Major</th>
                        <th>Minor</th>
                        <th style="min-width:300px;">Defect</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>'. $s_order['order_code'] .'</td>
                        <td>'. $QtY .'
                            <input type="hidden" name="order_id" value="'. $p_list['order_id'] .'">
                            <input type="hidden" name="prodessing_list_id" value="'. $p_list['id'] .'">
                            <input type="hidden" name="ref_num" value="'. $p_list['processing_code'] .'">
                            <input type="hidden" name="process_qty[]" id="max_q'.$tab.$id.$rId.'" value="'. $QtY .'">
                            <input type="hidden" name="temp_id[]" value="'.$tab.$id.$rId.'">
                        </td>
                        <td><input type="number" id="app_q'.$tab.$id.$rId.'" class="form-control sub_t'.$tab.$id.$rId.'" name="approved[]" onkeyup="qcValicate('.$tab.$id.$rId.')" placeholder="Approved Qty"></td>
                        <td><input type="number" id="maj_q'.$tab.$id.$rId.'" class="form-control sub_t'.$tab.$id.$rId.'" name="critical[]" onkeyup="qcValicate('.$tab.$id.$rId.')" placeholder="Critical"></td>
                        <td><input type="number" id="cri_q'.$tab.$id.$rId.'" class="form-control sub_t'.$tab.$id.$rId.'" name="major[]" onkeyup="qcValicate('.$tab.$id.$rId.')" placeholder="Major"></td>
                        <td><input type="number" id="min_q'.$tab.$id.$rId.'" class="form-control sub_t'.$tab.$id.$rId.'" name="minor[]" onkeyup="qcValicate('.$tab.$id.$rId.')" placeholder="Minor"></td>
                        <td><select name="defect'.$tab.$id.$rId.'[]" id="def_q'.$tab.$id.$rId.'" class="form-control custom-select2" multiple style="width:100%"> '. select_dropdown('mas_defect', array('id', 'defect_name'), 'defect_name ASC', '', '', '`') .' </select></td>
                    </tr>
                </tbody>
            </table>
        ';
    } else if($type=='part_color') {
        
        
        $sqll = "SELECT a.id, sum(a.pcs_per_bundle) as pcs_per_bundle, b.part_name, c.color_name, a.part, a.color ";
        $sqll .= " FROM bundle_details a ";
        $sqll .= " LEFT JOIN part b ON a.part = b.id ";
        $sqll .= " LEFT JOIN color c ON a.color = c.id ";
        $sqll .= " WHERE a.id IN (". $p_list['boundle_id'] .") GROUP BY a.part, a.color ";
        // print $sqll;
        $hn = mysqli_query($mysqli, $sqll);
        
        $tr = "";
        $x = 0;
        while($iop = mysqli_fetch_array($hn)) {
            
            if($tab==1) {
                // $hn = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE id IN (". $p_list['boundle_id'] .")"));
                $QtY = $iop['pcs_per_bundle'];
                
            } else if($tab==2) {
                
                $hn2 = mysqli_query($mysqli, "SELECT s_out_complete FROM bundle_details WHERE id IN (". $p_list['boundle_id'] .") AND part = '". $iop['part'] ."' AND color = '". $iop['color'] ."' ");
                $ar[$x] = array();
                while($row = mysqli_fetch_array($hn2)) {
                    $ar[$x] = array_merge($ar[$x], explode(',', $row['s_out_complete']));
                }
                $QtY = count($ar[$x]);
                
            } else if($tab==3) {
                $hn3 = mysqli_query($mysqli, "SELECT ch_good_pcs FROM bundle_details WHERE id IN (". $p_list['boundle_id'] .") AND part = '". $iop['part'] ."' AND color = '". $iop['color'] ."'");
                $ar1 = array();
                while($row = mysqli_fetch_array($hn3)) {
                    $ar1 = array_merge($ar1, explode(',', $row['ch_good_pcs']));
                }
                $QtY = count($ar1);
                
            }
            
            $rId = $iop['id'];
            
            $tr .= '<tr><td>'. $s_order['order_code'] .'</td> <td>'. $iop['part_name'] .'</td> <td>'. $iop['color_name'] .'</td>
                        <td>'. $QtY .'
                            
                            <input type="hidden" name="part[]" value="'. $iop['part'] .'">
                            <input type="hidden" name="color[]" value="'. $iop['color'] .'">
                            <input type="hidden" name="process_qty[]" id="max_q'.$tab.$id.$rId.'" value="'. $QtY .'">
                            <input type="hidden" name="temp_id[]" value="'.$tab.$id.$rId.'">
                        </td>
                        <td><input type="number" id="app_q'.$tab.$id.$rId.'" class="form-control sub_t'.$tab.$id.$rId.'" name="approved[]" onkeyup="qcValicate('.$tab.$id.$rId.')" placeholder="Approved Qty"></td>
                        <td><input type="number" id="maj_q'.$tab.$id.$rId.'" class="form-control sub_t'.$tab.$id.$rId.'" name="critical[]" onkeyup="qcValicate('.$tab.$id.$rId.')" placeholder="Critical"></td>
                        <td><input type="number" id="cri_q'.$tab.$id.$rId.'" class="form-control sub_t'.$tab.$id.$rId.'" name="major[]" onkeyup="qcValicate('.$tab.$id.$rId.')" placeholder="Major"></td>
                        <td><input type="number" id="min_q'.$tab.$id.$rId.'" class="form-control sub_t'.$tab.$id.$rId.'" name="minor[]" onkeyup="qcValicate('.$tab.$id.$rId.')" placeholder="Minor"></td>
                        <td><select name="defect'.$tab.$id.$rId.'[]" id="def_q'.$tab.$id.$rId.'" class="form-control custom-select2" multiple style="width:100%"> '. select_dropdown('mas_defect', array('id', 'defect_name'), 'defect_name ASC', '', '', '`') .' </select></td>
                    </tr>';
            $x++;
        }
        
        $data['body'][] = '
            <table class="table">
                <thead>
                    <tr>
                        <th>BO</th>
                        <th>Part</th>
                        <th>Color</th>
                        <th>Qty</th>
                        <th>Approved</th>
                        <th>Critical</th>
                        <th>Major</th>
                        <th>Minor
                            <input type="hidden" name="order_id" value="'. $p_list['order_id'] .'">
                            <input type="hidden" name="prodessing_list_id" value="'. $p_list['id'] .'">
                            <input type="hidden" name="ref_num" value="'. $p_list['processing_code'] .'">
                        </th>
                        <th style="min-width:300px;">Defect</th>
                    </tr>
                </thead>
                <tbody>'. $tr .'</tbody>
            </table>
        ';
    } else if($type=='size') {
        
        
        $sqll = "SELECT a.id, sum(a.pcs_per_bundle) as pcs_per_bundle, b.part_name, c.color_name, d.type, a.part, a.color, a.variation_value ";
        $sqll .= " FROM bundle_details a ";
        $sqll .= " LEFT JOIN part b ON a.part = b.id ";
        $sqll .= " LEFT JOIN color c ON a.color = c.id ";
        $sqll .= " LEFT JOIN variation_value d ON a.variation_value = d.id ";
        $sqll .= " WHERE a.id IN (". $p_list['boundle_id'] .") GROUP BY a.part, a.color, a.variation_value ";
        // print $sqll;
        $hn = mysqli_query($mysqli, $sqll);
        
        $tr = "";
        
        while($iop = mysqli_fetch_array($hn)) {
            
            
            if($tab==1) {
                // $hn = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE id IN (". $p_list['boundle_id'] .")"));
                $QtY = $iop['pcs_per_bundle'];
                
            } else if($tab==2) {
                
                $hn4 = mysqli_query($mysqli, "SELECT s_out_complete FROM bundle_details WHERE id IN (". $p_list['boundle_id'] .") AND part = '". $iop['part'] ."' AND color = '". $iop['color'] ."' AND variation_value = '". $iop['variation_value'] ."' ");
                $ar[$x] = array();
                while($row = mysqli_fetch_array($hn4)) {
                    $ar[$x] = array_merge($ar[$x], explode(',', $row['s_out_complete']));
                }
                $QtY = count($ar[$x]);
                
            } else if($tab==3) {
                $hn5 = mysqli_query($mysqli, "SELECT ch_good_pcs FROM bundle_details WHERE id IN (". $p_list['boundle_id'] .") AND part = '". $iop['part'] ."' AND color = '". $iop['color'] ."' AND variation_value = '". $iop['variation_value'] ."'");
                $ar1 = array();
                while($row = mysqli_fetch_array($hn5)) {
                    $ar1 = array_merge($ar1, explode(',', $row['ch_good_pcs']));
                }
                $QtY = count($ar1);
                
            }
            
            $rId = $iop['id'];
            
            $tr .= '<tr><td>'. $s_order['order_code'] .'</td> <td>'. $iop['part_name'] .'</td> <td>'. $iop['color_name'] .'</td> <td>'. $iop['type'] .'</td>
                        <td>'. $QtY .'
                            
                            <input type="hidden" name="part[]" value="'. $iop['part'] .'">
                            <input type="hidden" name="color[]" value="'. $iop['color'] .'">
                            <input type="hidden" name="variation_value[]" value="'. $iop['variation_value'] .'">
                            <input type="hidden" name="process_qty[]" id="max_q'.$tab.$id.$rId.'" value="'. $QtY .'">
                            <input type="hidden" name="temp_id[]" value="'.$tab.$id.$rId.'">
                        </td>
                        <td><input type="number" id="app_q'.$tab.$id.$rId.'" class="form-control sub_t'.$tab.$id.$rId.'" name="approved[]" onkeyup="qcValicate('.$tab.$id.$rId.')" placeholder="Approved Qty"></td>
                        <td><input type="number" id="maj_q'.$tab.$id.$rId.'" class="form-control sub_t'.$tab.$id.$rId.'" name="critical[]" onkeyup="qcValicate('.$tab.$id.$rId.')" placeholder="Critical"></td>
                        <td><input type="number" id="cri_q'.$tab.$id.$rId.'" class="form-control sub_t'.$tab.$id.$rId.'" name="major[]" onkeyup="qcValicate('.$tab.$id.$rId.')" placeholder="Major"></td>
                        <td><input type="number" id="min_q'.$tab.$id.$rId.'" class="form-control sub_t'.$tab.$id.$rId.'" name="minor[]" onkeyup="qcValicate('.$tab.$id.$rId.')" placeholder="Minor"></td>
                        <td><select name="defect'.$tab.$id.$rId.'[]" id="def_q'.$tab.$id.$rId.'" class="form-control custom-select2" multiple style="width:100%"> '. select_dropdown('mas_defect', array('id', 'defect_name'), 'defect_name ASC', '', '', '`') .' </select></td>
                    </tr>';
        }
        
        $data['body'][] = '
            <table class="table">
                <thead>
                    <tr>
                        <th>BO</th>
                        <th>Part</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>Qty</th>
                        <th>Approved</th>
                        <th>Critical</th>
                        <th>Major</th>
                        <th>Minor
                            <input type="hidden" name="order_id" value="'. $p_list['order_id'] .'">
                            <input type="hidden" name="prodessing_list_id" value="'. $p_list['id'] .'">
                            <input type="hidden" name="ref_num" value="'. $p_list['processing_code'] .'">
                        </th>
                        <th style="min-width:300px;">Defect</th>
                    </tr>
                </thead>
                <tbody>'. $tr .'</tbody>
            </table>
        ';
    } else {
        $data['body'][] = '';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getBill_receiptEdit'])) {
    
    $array = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM bill_receipt WHERE id = ". $_REQUEST['id']));
    
    function removeNumericKeys($array) {
        return array_filter($array, function($key) {
            return !is_numeric($key);
        }, ARRAY_FILTER_USE_KEY);
    }
    
    $result = removeNumericKeys($array);
    
    if($array['bill_type']=="CostGenerate") {
        $result['supplier_n'][] = select_dropdown('employee_detail', array('id', 'cg_name'), 'cg_name ASC', $array['supplier'], ' WHERE is_cg="yes"', '');
    } else {
        $result['supplier_n'][] = select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $array['supplier'], '', '');
    }
    
    $result['type_n'][] = '<option value="Fabric">Fabric</option> <option value="Store">Store</option> <option value="Production">Production</option> <option value="CostGenerate">Cost Generate</option>'; 
    
    echo json_encode($result);
    
} else if(isset($_REQUEST['getApprovedBudProcess'])) {
    
    // $array = mysqli_query($mysqli, "SELECT b.id, b.process_name FROM budget_process a LEFT JOIN process b ON a.process = b.id WHERE a.so_id IN (". $_REQUEST['id'] .") AND a.is_approved = 'true' AND b.department IN (1,3,7,8,9,22,21) GROUP BY a.process ");
    $array = mysqli_query($mysqli, "SELECT b.id, b.process_name FROM budget_process a LEFT JOIN process b ON a.process = b.id WHERE a.style_id IN (". $_REQUEST['id'] .") AND a.is_approved = 'true' GROUP BY a.process ");
    
    while($row = mysqli_fetch_array($array)) {
        $result['option'][] = '<option value="'. $row['id'] .'">'. $row['process_name'] .'</option>';
    }
    
    echo json_encode($result);
    
} else if(isset($_REQUEST['startCost_Generation'])) {
    
    if(isset($_REQUEST['prtN'])) {
        $foreach = $_REQUEST['prtN'];
    } else if(!isset($_REQUEST['prtN']) && isset($_REQUEST['styleNum'])) {
        $sele = mysqli_query($mysqli, "SELECT id FROM sod_part WHERE sales_order_detail_id IN (". implode(',', $_REQUEST['styleNum']) .")");
        $foreach = array();
        while($roww = mysqli_fetch_array($sele)) {
            $foreach[] = $roww['id'];
        }
        
    }
    
    $process_id = $_REQUEST['process_name'];
    
    foreach($foreach as $key => $val) {
        
        $sod_p_tot = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sod_part WHERE id= '". $val ."' "));
        
        $order_id = $sod_p_tot['sales_order_id'];
        $style_id = $sod_p_tot['sales_order_detail_id'];
        $sod_combo = $sod_p_tot['sod_combo'];
        $sod_part = $sod_p_tot['id'];
        
        $part_id = $sod_p_tot['part_id'];
        $color_id = $sod_p_tot['color_id'];
        
        $sod = mysqli_fetch_array(mysqli_query($mysqli, "SELECT total_qty FROM sales_order_detalis WHERE id= '". $style_id ."' "));
        $m = 1;
        foreach($process_id as $process) {
            
            $emp = $_POST['employee'];
            
            foreach($emp as $emp_loyee) {
                
                $pss = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.id, a.rate, b.process_name, b.department, b.id as process_id FROM budget_process a LEFT JOIN process b ON a.process = b.id 
                            WHERE a.process = '". $process ."' AND a.style_id = '". $style_id ."' AND a.is_approved = 'true'"));

                $department = $pss['department'];
                
                $al_added = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.bill_qty) as bill_qty FROM cost_generation_det a 
                            WHERE a.sod_part = '". $sod_part ."' AND a.process = '". $pss['process_id'] ."'"));
                
                if(get_setting_val('COST_GEN_CHK')=='ORD_QTY') {
                    $max_bill = $sod['total_qty'];
                    
                } else if(get_setting_val('COST_GEN_CHK')=='PRO_QTY') {
                    
                    if($process == 1 && $department == 5) {
                        // $process == 1 (cutting); $department == 5 (sewing output department)

                            if(get_setting_val('COST_GEN_CUTTING') == 'SCAN_OUT') {

                                $summ = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_sewingout WHERE sod_part = '". $sod_part ."' AND process = '". $process ."' AND device_user = '". $emp_loyee ."'"));
                                $max_bill = $summ['scanned_count'] ? $summ['scanned_count'] : 0;

                            } else if(get_setting_val('COST_GEN_CUTTING') == 'CUTT_OUT') {
                                
                                $ctng = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a
                                        WHERE a.order_id = '". $order_id ."' AND a.style_id = '". $style_id ."' AND a.sod_part = '". $sod_part ."' AND a.created_by = '". $emp_loyee ."'"));
                                $max_bill = $ctng['pcs_per_bundle'] ? $ctng['pcs_per_bundle'] : 0;
                            }
                            
                        } else if($department == 5 && $process != 1) {
                            // $department == 5 (sewing output department)
                            $summ = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_sewingout WHERE sod_part = '". $sod_part ."' AND process = '". $process ."' AND device_user = '". $emp_loyee ."'"));
                            $max_bill = $summ['scanned_count'] ? $summ['scanned_count'] : 0;
                            
                        } else if($department == 6) {
                            // $department == 6 (Checking department)
                            $summ = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_checking WHERE sod_part = '". $sod_part ."' AND process = '". $process ."' AND device_user = '". $emp_loyee ."'"));
                            $max_bill = $summ['scanned_count'] ? $summ['scanned_count'] : 0;
                            
                        } else if($department == 2) {
                            // $department == 2 (component process department)
                            $summ = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_component_process WHERE sod_part = '". $sod_part ."' AND process = '". $process ."' AND device_user = '". $emp_loyee ."'"));
                            $max_bill = $summ['scanned_count'] ? $summ['scanned_count'] : 0;

                        }
                    }
                    
                    $billable = $max_bill - $al_added['bill_qty'];

                    $tmp_id = rand(1000, 9999);
                    $result['tbody'][] = '<tr><td>'. sales_order_code($order_id) .'</td> <td>'. sales_order_style($style_id) .'</td> <td>'. part_name($part_id) .' | '. color_name($color_id) .'</td> <td>'. $pss['process_name'] .'</td><td>'. employee_name($emp_loyee)  .'</td><td>'. $billable  .'</td> 
                                                <td>
                                                    <input type="hidden" name="cg_id[]" value="">
                                                    <input type="hidden" name="max_qty[]" id="max_qty'. $tmp_id .'" value="'. $billable .'">
                                                    <label for="bill_qty'. $tmp_id .'"></label> 
                                                    <input type="number" name="bill_qty[]" id="bill_qty'. $tmp_id .'" onkeyup="validate_billQty('. $tmp_id .')" class="form-control zero_valid" placeholder="Bill Qty" required>
                                                </td>
                                                <td><input type="hidden" name="max_rate[]" id="max_rate'. $tmp_id .'" value="'. $pss['rate'] .'"> <label for="bill_rate'. $tmp_id .'"></label> <input type="text" name="bill_rate[]" id="bill_rate'. $tmp_id .'" onkeyup="validate_billRate('. $tmp_id .')" class="form-control number_input zero_valid" placeholder="Rate" required></td>
                                                <td><input type="hidden" name="order_basic[]" value="'. $sod_part .'-'. $pss['process_id'] .'"> <label for="bill_amount'. $tmp_id .'"></label> <input type="text" name="bill_amount[]" id="bill_amount'. $tmp_id .'" class="form-control zero_valid" placeholder="Amount" required readonly></td>
                                            </tr>';
            }
        $m++; }
    }
    echo json_encode($result);

} else if(isset($_REQUEST['not_receipted_Cost'])) {
    
    $array = mysqli_query($mysqli, "SELECT b.id, b.cg_name FROM cost_generation a LEFT JOIN employee_detail b ON a.employee = b.id WHERE a.is_receipted = 'no' GROUP BY a.employee ");
    
    $result['option'][] = '<option value="">Select</option>';
    while($row = mysqli_fetch_array($array)) {
        $result['option'][] = '<option value="'. $row['id'] .'">'. $row['cg_name'] .'</option>';
    }
    
    echo json_encode($result);
    
} else if(isset($_REQUEST['getNotreceipted_bill'])) {
    
    $array = mysqli_query($mysqli, "SELECT a.id, a.entry_number FROM cost_generation a WHERE a.is_receipted = 'no' AND a.employee=". $_REQUEST['id']);
    
    $result['option'][] = '<option value="">Select</option>';
    while($row = mysqli_fetch_array($array)) {
        $result['option'][] = '<option value="'. $row['id'] .'">'. $row['entry_number'] .'</option>';
    }
    
    echo json_encode($result);
    
} else if(isset($_REQUEST['getNotreceipted_bill_detail'])) {
    
    $row = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.id, a.entry_date, a.entry_number, sum(b.bill_amount) as bill_amount FROM cost_generation a LEFT JOIN cost_generation_det b ON a.id = b.cost_generation_id WHERE a.id=". $_REQUEST['id']));
    
    $result['bill_amount'][] = $row['bill_amount'];
    $result['entry_date'][] = $row['entry_date'];
    $result['entry_number'][] = $row['entry_number'];
    
    echo json_encode($result);
    
} else if(isset($_REQUEST['get_supplier_unpaid_names'])) {
    
    if($_REQUEST['type']=='CostGenerate') {
        $array = mysqli_query($mysqli, "SELECT b.id, b.employee_name FROM bill_receipt a LEFT JOIN employee_detail b ON a.supplier = b.id WHERE a.approval_status != 'paid' AND a.approval_status='approved' GROUP BY a.supplier ");
        
        $result['option'][] = '<option value="">Select</option>';
        while($row = mysqli_fetch_array($array)) {
            $result['option'][] = '<option value="'. $row['id'] .'">'. $row['employee_name'] .'</option>';
        }
        
    } else {
        $result['option'][] = select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', '', '', '');
    }
    echo json_encode($result);
    
} else if(isset($_REQUEST['get_supplier_unpaid_bills'])) {
    
    $array = mysqli_query($mysqli, "SELECT a.bill_number, a.id, a.entry_number, a.cost_id, a.bill_amount FROM bill_receipt a WHERE a.payment_status != 'paid' AND a.approval_status='approved' AND bill_type ='". $_REQUEST['type'] ."' AND supplier=". $_REQUEST['id']);
    
    while($row = mysqli_fetch_array($array)) {
        $outstand[] = $row['bill_amount'];
        $result['option'][] = '<option value="'. $row['id'] .'">'. $row['bill_number'] .'</option>';
    }
    
    $result['outstand'][] = array_sum($outstand);
    
    echo json_encode($result); 
    
} else if(isset($_REQUEST['calculate_bill_receipt_Total'])) {
    
    $array = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.bill_amount) as bill_amount FROM bill_receipt a WHERE a.id IN (". $_REQUEST['id'] .")"));
    
    $result['bill_amount'][] = $array['bill_amount'];
    
    echo json_encode($result);
    
} else if(isset($_REQUEST['getInhouse_completed'])) {
    
    $qry = "SELECT a.id, a.completed_qty, b.boundle_qr, b.bundle_number, c.style_no, d.part_name, e.color_name, f.type ";
    $qry .= " FROM inhouse_process a ";
    $qry .= " LEFT JOIN bundle_details b ON b.id = a.bundle_id ";
    // $qry .= " LEFT JOIN sales_order c ON c.id = b.order_id ";
    $qry .= " LEFT JOIN sales_order_detalis c ON c.id = b.style ";
    $qry .= " LEFT JOIN part d ON d.id = b.part ";
    $qry .= " LEFT JOIN color e ON e.id = b.color ";
    $qry .= " LEFT JOIN variation_value f ON f.id = b.variation_value ";
    $qry .= " WHERE a.processing_id = '". $_REQUEST['id'] ."' AND a.daily_status_id='". $_REQUEST['sid'] ."' ORDER BY a.id DESC ";
    
    
    $temp = mysqli_query($mysqli, $qry); 
    
    $mp = 0;
    while($row = mysqli_fetch_array($temp)) {
        $oi= $row['id'] .", 'inhouse_process'";
    $mp++;
        $data['tbody'][] = '<tr><td>'. $mp .'</td><td>'. $row['style_no'] .'</td><td>'. $row['part_name'] .'</td><td>'. $row['color_name'] .'</td>
        <td>'. $row['type'] .'</td><td>'. $row['bundle_number'] .'</td><td>'. $row['completed_qty'] .'</td><td>'. $row['boundle_qr'] .'</td>
        <td><a class="border border-secondary rounded text-secondary" onclick="delete_data('. $oi .')"><i class="fa fa-trash"></i></a></td></tr>'; 
    }
    $data['cntt'][] = $mp;
    
    echo json_encode($data);
    
    
} else if(isset($_REQUEST['validate_budgetApprove'])) {
    
    $qry = "SELECT * ";
    $qry .= " FROM budget_process a ";
    $qry .= " WHERE a.style_id = '". $_REQUEST['id'] ."' AND budget_for = 'Fabric Budget' ";
    
    $temp = mysqli_query($mysqli, $qry);
    
    $row = mysqli_fetch_array($temp);
    
    $data['approve'][] = $row['is_approved'];
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getFab_puechase_stage'])) {
    
    $qry = "SELECT a.process_id, b.process_name ";
    $qry .= " FROM sales_order_fabric_components_process a ";
    $qry .= " LEFT JOIN process b ON b.id = a.process_id ";
    $qry .= " WHERE a.sales_order_detalis_id = '". $_REQUEST['id'] ."' GROUP BY a.process_id ORDER BY a.process_order ASC";
    // print $qry;
    $temp = mysqli_query($mysqli, $qry); 
    
    $data['po_stage'][] = '<option value="">Select</option>';
    
    $mp = 0;
    while($row = mysqli_fetch_array($temp)) {
        $data['po_stage'][] = '<option value="'. $row['process_id'] .'">'. $row['process_name'] .'</option>';
    }
    
    
    echo json_encode($data);
    
    
} else if(isset($_REQUEST['getFab_puechase_material_Name'])) {
    
    $gum = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM process WHERE id=". $_REQUEST['po_stage']));
    
    if($gum['budget_type'] == 'Yarn') {
        $qry1 = "SELECT a.id, a.yarn, d.yarn_name, e.color_name, sum(a.req_yarn_wt) as req_yarn_wt ";
        $qry1 .= " FROM fabprogram_print_yarn a ";
        $qry1 .= " LEFT JOIN sales_order_detalis b ON b.id = a.sales_order_detalis_id ";
        $qry1 .= " LEFT JOIN sales_order c ON c.id = b.sales_order_id ";
        $qry1 .= " LEFT JOIN mas_yarn d ON d.id = a.yarn ";
        $qry1 .= " LEFT JOIN color e ON e.id = a.color ";
        $qry1 .= " WHERE a.process_id = '". $gum['id'] ."' AND c.id = '". $_REQUEST['order_id'] ."' GROUP BY a.yarn, a.color ";
        // print $qry1;
        $temp1 = mysqli_query($mysqli, $qry1);
        
        $data['material_name'][] = '<option value="">Select</option>';
        
        
        while($row1 = mysqli_fetch_array($temp1)) {
            $data['material_name'][] = '<option value="'. $row1['id'] .'" data-val="'. $row1['req_yarn_wt'] .'">'. $row1['yarn_name'] .' - '. $row1['color_name'] .'</option>';
        }
        
    } else if($gum['budget_type'] == 'Fabric') {
        
        $mpo = "SELECT a.id, d.fabric_name, a.dia_wt, a.yarn_mixing, sum(a.req_wtt) as req_wttNN ";
        $mpo .= " FROM fabprogram_print a ";
        $mpo .= " LEFT JOIN sales_order_detalis b ON b.id = a.sales_order_detalis_id ";
        $mpo .= " LEFT JOIN sales_order c ON c.id = b.sales_order_id ";
        $mpo .= " LEFT JOIN fabric d ON d.id = a.fabric_id ";
        $mpo .= " WHERE c.id = '". $_REQUEST['order_id'] ."' AND a.process_id = '". $gum['id'] ."' GROUP BY a.fabric_id, a.yarn_mixing, a.dia_wt";
        
        $temp1 = mysqli_query($mysqli, $mpo);
        
        $data['material_name'][] = '<option value="">Select</option>';
        
        $i=0;
        while($row1 = mysqli_fetch_array($temp1)) {
            
            $js = json_decode($row1['yarn_mixing']);
            // print_r($js);
            foreach($js as $val) {
                // print 1;
                $exp = explode('=', $val);
                $yarn = mysqli_fetch_array(mysqli_query($mysqli, "SELECT yarn_name FROM mas_yarn WHERE id=". $exp[0]));
                $color = mysqli_fetch_array(mysqli_query($mysqli, "SELECT color_name FROM color WHERE id=". $exp[1]));
             
                $opp[$i][] = $yarn['yarn_name'].' - '.$color['color_name'].' - '. $exp[2] .'%';
            }
            
            
            $data['material_name'][] = '<option value="'. $row1['id'] .'" data-val="'. $row1['req_wttNN'] .'">'. $row1['fabric_name'] .'; '. implode(',', $opp[$i]) .'; Dia: '. $row1['dia_wt'] .'.</option>';
            
        $i++; }
        
        
    } else {
        $data['material_name'][] = '';
        
    }
    
    echo json_encode($data);
    
    
} else if(isset($_REQUEST['getFab_PO_material_color'])) {
    
    $qry1 = "SELECT a.color, d.color_name ";
    $qry1 .= " FROM fabprogram_print_yarn a ";
    $qry1 .= " LEFT JOIN sales_order_detalis b ON b.id = a.sales_order_detalis_id ";
    $qry1 .= " LEFT JOIN sales_order c ON c.id = b.sales_order_id ";
    $qry1 .= " LEFT JOIN color d ON d.id = a.color ";
    $qry1 .= " WHERE c.id = '". $_REQUEST['order_id'] ."' AND a.yarn = '". $_REQUEST['yarn'] ."' GROUP BY a.color ";
    // print $qry1;
    $temp1 = mysqli_query($mysqli, $qry1); 
    
    $data['color'][] = '<option value="">Select</option>';
    
    
    while($row1 = mysqli_fetch_array($temp1)) {
        $data['color'][] = '<option value="'. $row1['color'] .'">'. $row1['color_name'] .'</option>';
    }
    
    echo json_encode($data);
    
    
} else if(isset($_REQUEST['getFab_PO_material_Value'])) {
    
    $qry1 = "SELECT (a.req_yarn_wt) as req_yarn_wt ";
    $qry1 .= " FROM fabprogram_print_yarn a ";
    $qry1 .= " LEFT JOIN sales_order_detalis b ON b.id = a.sales_order_detalis_id ";
    $qry1 .= " LEFT JOIN sales_order c ON c.id = b.sales_order_id ";
    $qry1 .= " LEFT JOIN color d ON d.id = a.color ";
    $qry1 .= " WHERE c.id = '". $_REQUEST['order_id'] ."' AND a.yarn = '". $_REQUEST['yarn'] ."' AND a.color = '". $_REQUEST['color'] ."' GROUP BY a.color ";
    // print $qry1;
    $temp1 = mysqli_query($mysqli, $qry1); 
    
    $row1 = mysqli_fetch_array($temp1);
    
    $data['value'] = $row1['req_yarn_wt'];
    
    echo json_encode($data);
   
// } else if(isset($_REQUEST['getAdded_fabricPO'])) { 
    
//     $qry1 = "SELECT a.*, b.order_code, c.process_name, d.yarn_name, e.color_name ";
//     $qry1 .= " FROM fabric_po_det a ";
//     $qry1 .= " LEFT JOIN sales_order b ON b.id = a.order_id ";
//     $qry1 .= " LEFT JOIN process c ON c.id = a.po_stage ";
//     $qry1 .= " LEFT JOIN mas_yarn d ON d.id = a.material_name ";
//     $qry1 .= " LEFT JOIN color e ON e.id = a.color_ref ";
//     $qry1 .= " WHERE a.fab_po = '". $_REQUEST['id'] ."' ";
    
//     // print $qry1;
//     $temp1 = mysqli_query($mysqli, $qry1); 
    
//     while($row = mysqli_fetch_array($temp1)) {
        
        
//         $data['tbody'][] = '<tr><td>'. $row['order_code'] .'</td><td>'. $row['process_name'] .'</td><td>'. $row['yarn_name'] .'</td>
//         <td class="d-none">'. $row['color_name'] .'</td>
//         <td>'. $row['bag_roll'] .'</td>
//         <td>'. $row['po_balance'] .'</td>
//         <td>'. $row['po_qty_wt'] .'</td>
//         <td>'. $row['rate'] .'</td>
//         <td>'. $row['tax_per'] .'</td>
//         <td>'. $row['amount'] .'</td>
//         </tr>';
//     }
    
//     echo json_encode($data);
    
    
} else if(isset($_REQUEST['addmoreQrydetail'])) {
    
    $row = $_REQUEST['row'];
    
    $data['tbody'][] = '<tr id="tr'. $row .'">
                            <td style="min-width: 150px;max-width:300px">
                                <select class="custom-select2 form-control" name="newProcess[]" id="newProcess_s2'. $row .'" style="width:100%"> 
                                    '. select_dropdown('process', array('id', 'process_name'), 'process_name DESC', '', '', '') .'
                                </select>
                            </td>
                            <td><input type="text" name="rate1[]" class="form-control" placeholder="Rate"></td>
                            <td><input type="text" name="rate2[]" class="form-control" placeholder="Revised Rate"></td>
                            <td><input type="text" name="rate3[]" class="form-control" placeholder="Rework Rate"></td>
                            <td><i class="icon-copy fa fa-trash" aria-hidden="true" onclick="removeRow('. $row .')"></i></td>
                        </tr>';
                        
    echo json_encode($data);
}








// timeline_history('Insert', 'employee_detail_temp', $_REQUEST['id'], 'Employee Request Rejected.');





?>