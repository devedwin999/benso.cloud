<?php

session_start();

# Insert Data 
function Insert($table, $data)
{

    global $mysqli;

    $fields = array_keys($data);
    $values = array_map(array($mysqli, 'real_escape_string'), array_values($data));
    
    $qry = "INSERT INTO $table(" . implode(",", $fields) . ") VALUES ('" . implode("','", $values) . "')";
    
    $ret = mysqli_query($mysqli, $qry) or die(mysqli_error($mysqli));

    if ($ret) {
        return true;
    } else {
        return false;
    }
}

// Update Data, Where clause is left optional
function Update($table_name, $form_data, $where_clause = '')
{
    global $mysqli;

    $whereSQL = '';
    if (!empty($where_clause)) {

        if (substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
            $whereSQL = " WHERE " . $where_clause;
        } else {
            $whereSQL = " " . trim($where_clause);
        }
    }
    $sql = "UPDATE " . $table_name . " SET ";

    $sets = array();
    foreach ($form_data as $column => $value) {
        $sets[] = "`" . $column . "` = '" . $value . "'";
    }
    $sql .= implode(', ', $sets);

    $sql .= $whereSQL;

    return mysqli_query($mysqli, $sql);
}

//Delete Data, the where clause is left optional incase the user wants to delete every row!
function Delete($table_name, $where_clause = '')
{
    global $mysqli;

    $whereSQL = '';
    if (!empty($where_clause)) {

        if (substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {

            $whereSQL = " WHERE " . $where_clause;
        } else {
            $whereSQL = " " . trim($where_clause);
        }
    }

    $sql = "DELETE FROM " . $table_name . $whereSQL;

    return mysqli_query($mysqli, $sql);
}


function get_country($country_id)
{
    global $mysqli;

    $qry_user = "SELECT country FROM master_country WHERE auto_number ='" . $country_id . "'";
    $query1 = mysqli_query($mysqli, $qry_user);
    $row_user = mysqli_fetch_array($query1);

    $num_rows1 = mysqli_num_rows($query1);

    if ($num_rows1 > 0) {
        return $row_user['country'];
    } else {
        return "";
    }
}

function get_state($state_id)
{
    global $mysqli;

    $qry_user = "SELECT state_name FROM states WHERE id ='" . $state_id . "'";
    $query1 = mysqli_query($mysqli, $qry_user);
    $row_user = mysqli_fetch_array($query1);

    $num_rows1 = mysqli_num_rows($query1);

    if ($num_rows1 > 0) {
        return $row_user['state_name'];
    } else {
        return "";
    }
}

function get_cities($cities_id)
{
    global $mysqli;

    $qry_user = "SELECT cities_name FROM cities WHERE id ='" . $cities_id . "'";
    $query1 = mysqli_query($mysqli, $qry_user);
    $row_user = mysqli_fetch_array($query1);

    $num_rows1 = mysqli_num_rows($query1);

    if ($num_rows1 > 0) {
        return $row_user['cities_name'];
    } else {
        return "";
    }
}

function get_stateCity($state_id)
{
    global $mysqli;

    $c = "SELECT * FROM cities WHERE state_id ='" . $state_id . "'";
    $cc = mysqli_query($mysqli, $c);
    $cty = array();
    while ($ccc = mysqli_fetch_array($cc)) {
        $cty[] = $ccc;
    }
    return $cty;
}

function get_company_detail($cid)
{
    global $mysqli;

    $qry_user = "SELECT * FROM company WHERE id='" . $cid . "'";
    $query1 = mysqli_query($mysqli, $qry_user);

    $num_rows1 = mysqli_fetch_array($query1);

    return $num_rows1;
}

function total_row_count($table, $where) {

    global $mysqli;

    $qry = "SELECT * FROM $table $where";
    $query = mysqli_query($mysqli, $qry);

    $num = mysqli_num_rows($query);

    return $num;
}


function select_dropdown($table, $fields, $order, $val, $where, $opt)
{
    global $mysqli;

    $asd = "SELECT $fields[0],$fields[1] FROM $table $where ORDER BY $order";
    $sdd = mysqli_query($mysqli, $asd);
    if (empty($opt)) {
        $mc = '<option value="">Select</option>';
    }
    while ($sadf = mysqli_fetch_array($sdd)) {
        if ($sadf[0] == $val)
            $vll = 'selected';
        else
            $vll = '';
        $mc .= '<option value="' . $sadf[0] . '" ' . $vll . '>' . $sadf[1] . '</option>';
    }

    return $mc;
}

function auto_complete($table, $searchField, $searchTerm)
{
    global $mysqli;

    //  and is_active='active'

    $qry = "SELECT a.* FROM ";
    $qry .= " bundle_details a ";
    $qry .= " LEFT JOIN cutting_barcode b ON b.id=a.cutting_barcode_id ";
    $qry .= " LEFT JOIN sales_order_detalis c ON c.id=b.style ";
    $qry .= " WHERE a.in_proseccing IS NULL ";
    if ($_SESSION['login_role'] != 1) {
        $qry .= " AND c.production_unit='" . $_SESSION['loginCompany'] . "'";
    }
    $qry .= " AND a.boundle_qr LIKE '%" . $searchTerm . "%' ";
    $qry .= " ORDER BY a.boundle_qr ASC ";

    $query = mysqli_query($mysqli, $qry);



    if (mysqli_num_rows($query) > 0) {
        $x = 0;
        while ($row = mysqli_fetch_array($query)) {

            foreach (array_keys($row) as $key) {
                if (gettype($key) != 'integer') {
                    if ($key == $searchField) {
                        $key1 = 'value';
                    } else {
                        $key1 = $key;
                    }
                    $nval[$x][$key1] = $row[$key];
                }
            }
            $x++;
        }
    }

    echo json_encode($nval);
}

function check_perm($perm_name, $groupId)
{
    global $mysqli;
    
    $num = mysqli_fetch_array(mysqli_query($mysqli, "SELECT value FROM user_permissions WHERE permission_name = '". $perm_name ."' AND user_group = '". $groupId ."'"));
    
    if($num['value']==1) {
        return 'checked';
    } else {
        return '';
    }
}

function action_denied()
{
    print '<div class="pd-20" style="text-align: center;"><span style="color:red;font-size: 50px;"><i class="icon-copy ion-alert-circled"></i></span></br> <h4 style="color: #0058ff;">Access Denied</h4> <br> 
        <p>You Do not Have a Permission to Access this Page.</p> <br> <a href="javascript:history.go(-1)" style="text-decoration: underline;color: #a686ff;">Go Back</a></div>';

    include('includes/end_scripts.php');
    include('modals.php');
}

function get_setting_val($ref)
{
    global $mysqli;
    
    $num = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM settings WHERE ref = '". $ref ."' "));
    
    return $num['value'];

} 


function select_dropdown_multiple($table, $fields, $order, $val, $where, $opt)
{
    global $mysqli;

    $asd = "SELECT $fields[0],$fields[1] FROM $table $where ORDER BY $order";
    $sdd = mysqli_query($mysqli, $asd);
    if (empty($opt)) {
        $mc = '<option value="">Select</option>';
    }
    while ($sadf = mysqli_fetch_array($sdd)) {
        if (in_array($sadf[0], explode(',', $val)))
            $vll = 'selected';
        else
            $vll = '';
        $mc .= '<option value="' . $sadf[0] . '" ' . $vll . '>' . $sadf[1] . '</option>';
    }

    return $mc;
}

function page_spinner() {
    $spin = '<div id="overlay"><a type="button" class="close cancel_spinner" style="position: relative;top: 10%;right: 2%;">×</a> <div class="cv-spinner"><span class="spinner"></span> <br><a class=""> Loading..</a></div></div>';
    
    echo $spin;
}

function select_dropdown_display($table, $field , $where)
{
    global $mysqli;
    
    $mc = '';
    
    $asd = "SELECT $field FROM $table $where";
    $sdd = mysqli_query($mysqli, $asd);
    while ($sadf = mysqli_fetch_array($sdd)) {
    $mc .= '<span class="border border-secondary rounded text-secondary">'. $sadf[$field] .'</span>&nbsp;';
    }

    return $mc;
}

function timeline_history($action_name, $table_name, $pid, $comment) 
{
    global $mysqli;
    
    $ins = mysqli_query($mysqli, "INSERT INTO timeline_history (user_id, action_name, table_name, primary_id, comment, created_unit) VALUES ('". $_SESSION['login_id'] ."', '". $action_name ."', '". $table_name ."', '". $pid ."', '". $comment ."', '". $_SESSION['loginCompany'] ."')");
}

// table sales_order_detalis
function bundle_qr($value)
{
    global $mysqli;
    
    $asd = "SELECT boundle_qr FROM bundle_details WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['boundle_qr'];
}

// table employee_detail
function emp_name($value)
{
    global $mysqli;
    
    $mc = array();
    
    $asd = "SELECT employee_name FROM employee_detail WHERE id IN (". $value .")";
    $sdd = mysqli_query($mysqli, $asd);
    while ($sadf = mysqli_fetch_array($sdd)) {
    $mc[] = $sadf['employee_name'];
    }

    return $mc;
}

// table sales_order
function sales_order_code($value)
{
    global $mysqli;
    
    $asd = "SELECT order_code FROM sales_order WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['order_code'];
}

// table sales_order_detalis
function sales_order_style($value)
{
    global $mysqli;
    
    $asd = "SELECT style_no FROM sales_order_detalis WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['style_no'];
}

// table mas_yarn
function approval_name($value)
{
    global $mysqli;
    
    $asd = "SELECT name FROM mas_approval WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['name'];
}

// table mas_yarn
function mas_yarn_name($value)
{
    global $mysqli;
    
    $asd = "SELECT yarn_name FROM mas_yarn WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['yarn_name'];
}

// table fabric
function fabric_name($value)
{
    global $mysqli;
    
    $asd = "SELECT fabric_name FROM fabric WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);    
    return $sadf['fabric_name'];
}

// table mas_currency
function currency_value($value)
{
    global $mysqli;
    
    $asd = "SELECT currency_value FROM mas_currency WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['currency_value'];
}

// table mas_currency
function mas_currency_name($value)
{
    global $mysqli;
    
    $asd = "SELECT currency_name FROM mas_currency WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['currency_name'];
}

// table selection_type
function selection_type_name($value)
{
    global $mysqli;
    
    $asd = "SELECT type_name FROM selection_type WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['type_name'];
}

// table color
function color_name($value)
{
    global $mysqli;
    
    $asd = "SELECT color_name FROM color WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['color_name'];
}

// table component
function component_name($value)
{
    global $mysqli;
    
    $asd = "SELECT component_name FROM mas_component WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['component_name'];
}

// table part
function part_name($value)
{
    global $mysqli;
    
    $asd = "SELECT part_name FROM part WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['part_name'];
}

// table department
function department_name($value)
{
    global $mysqli;
    
    $asd = "SELECT department_name FROM department WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['department_name'];
}

// table process
function process_name($value)
{
    global $mysqli;
    
    $asd = "SELECT process_name FROM process WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['process_name'];
}

// table variation_value
function variation_value($value)
{
    global $mysqli;
    
    $asd = "SELECT type FROM variation_value WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['type'];
}

// table orbidx_device
function orbidx_device_name($value)
{
    global $mysqli;
    
    $asd = "SELECT device FROM orbidx_device WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['device'];
}

// table mas_line
function line_name($value)
{
    global $mysqli;
    
    $asd = "SELECT line_name FROM mas_line WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['line_name'];
}

// table fabric_po
function fabric_po_ref($value)
{
    global $mysqli;
    
    $asd = "SELECT entry_number FROM fabric_po WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['entry_number'];
}

// table fabric_dc
function fabric_dc_ref($value)
{
    global $mysqli;
    
    $asd = "SELECT dc_number FROM fabric_dc WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['dc_number'];
}

// table employee_detail
function employee_name($value)
{
    global $mysqli;
    
    $asd = "SELECT employee_name FROM employee_detail WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['employee_name'];
}

// table employee_detail
function employee_name_from_code($value)
{
    global $mysqli;
    
    $asd = "SELECT employee_name FROM employee_detail WHERE employee_code = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['employee_name'];
}

// table brand
function brand_name($value)
{
    global $mysqli;
    
    $asd = "SELECT brand_name FROM brand WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['brand_name'];
}

// table mas_line
function mas_line_name($value)
{
    global $mysqli;
    
    $asd = "SELECT line_name FROM mas_line WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['line_name'];
}

// table stockgroup
function stockgroup_name($value)
{
    global $mysqli;
    
    $asd = "SELECT groupname FROM stockgroup WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['groupname'];
}

// table company
function company_code($value)
{
    global $mysqli;
    
    $asd = "SELECT company_code FROM company WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['company_code'];
}

// table company
function company_name($value)
{
    global $mysqli;
    
    $asd = "SELECT company_name FROM company WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['company_name'];
}

// table mas_pack
function pack_name($value)
{
    global $mysqli;
    
    $asd = "SELECT pack_name FROM mas_pack WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['pack_name'];
}

// table supplier
function supplier_name($value)
{
    global $mysqli;
    
    $asd = "SELECT supplier_name FROM supplier WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['supplier_name'];
}

function cutting_entry_number($value)
{
    global $mysqli;
    
    $asd = "SELECT entry_number FROM cutting_barcode WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['entry_number'];
}

function process_budget_rate($order_id, $process)
{
    global $mysqli;
    
    $asd = "SELECT rate FROM budget_process WHERE so_id = '". $order_id ."' AND process = '". $process ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['rate'] ? $sadf['rate'] : 0;
}

// table UOM
function uom_name($value)
{
    global $mysqli;
    
    $asd = "SELECT uom_name FROM mas_uom WHERE id = '". $value ."'";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['uom_name'];
}



function tot_cutting_qty_part($style, $sod_part, $date)
{
    global $mysqli;
    
    if($date=='today') {
        $type = " AND created_date LIKE '%". date('Y-m-d') ."%'";
    } else {
        $type = '';
    }
    $asd = "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE style_id = '". $style ."' AND sod_part = '". $sod_part ."' $type";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['pcs_per_bundle'] ? $sadf['pcs_per_bundle'] : '0';
}

function tot_sewing_in_part($style, $sod_part, $date)
{
    global $mysqli;
    
    if($date=='today') {
        $type = " AND in_sewing_date = '". date('Y-m-d') ."'";
    } else {
        $type = "";
    }
    $asd = "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE in_sewing = 'yes' AND style_id = '". $style ."' AND sod_part = '". $sod_part ."' $type";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['pcs_per_bundle'] ? $sadf['pcs_per_bundle'] : '0';
}

function tot_sewing_out_part($style, $sod_part, $date)
{
    global $mysqli;
    
    if($date=='today') {
        $type = " AND date = '". date('Y-m-d') ."'";
    } else {
        $type = "";
    }
    $asd = "SELECT sum(scanned_count) as scanned_count FROM orbidx_sewingout WHERE style_id = '". $style ."' AND sod_part = '". $sod_part ."' $type";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['scanned_count'] ? $sadf['scanned_count'] : '0';
}

function tot_checking_part($style, $sod_part, $date)
{
    global $mysqli;
    
    if($date=='today') {
        $type = " AND date = '". date('Y-m-d') ."'";
    } else {
        $type = "";
    }
    $asd = "SELECT sum(scanned_count) as scanned_count FROM orbidx_checking WHERE style_id = '". $style ."' AND sod_part = '". $sod_part ."' $type";
    $sdd = mysqli_query($mysqli, $asd);
    $sadf = mysqli_fetch_array($sdd);
    
    return $sadf['scanned_count'] ? $sadf['scanned_count'] : '0';
}


function inr_number_format($number) {
    $number = strrev((string)$number);
    
    $formatted_number = preg_replace("/(\d{3})(?=\d)(?!\d*\.)/", "$1,", $number);
    
    $formatted_number = strrev($formatted_number);
    
    return $formatted_number;
}

function viewImage($path, $width) {
    
    $not_found = 'src/logo/not-found.jpg';
    $image = file_exists($path) ? $path : $not_found;

    $img = '<a class="showImagePopup"><img src="'. $image .'" width="'. $width .'" style="border-radius: 50%;"></i></a>';
    
    return $img;
}

function time_calculator($value) {

    $hours = floor($value / 3600);
    $minutes = floor(($value / 60) % 60);
    $seconds = $value % 60;

    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
}

function time_calculator_new($value, $format) {

    if($format==1) {

        $tim = explode(':', $value);

        $hr = ($tim[0]>0) ? $tim[0].' Hour ' : '';
        $min = ($tim[1]>0) ? $tim[1].' Minute ' : '';
        $sec = ($tim[2]>0) ? $tim[2].' Second ' : '';

        return $hr.$min.$sec;
    }
}