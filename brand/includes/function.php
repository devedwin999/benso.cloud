<?php

session_start();

# Insert Data 
function Insert($table, $data)
{

    global $mysqli;

    $fields = array_keys($data);
    $values = array_map(array($mysqli, 'real_escape_string'), array_values($data));

    $ret = mysqli_query($mysqli, "INSERT INTO $table(" . implode(",", $fields) . ") VALUES ('" . implode("','", $values) . "');") or die(mysqli_error($mysqli));

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
    
    $num = mysqli_fetch_array(mysqli_query($mysqli, "SELECT count(id) as cnt FROM user_permissions WHERE permission_name = '". $perm_name ."' AND user_group = '". $groupId ."'"));
    
    if($num['cnt']==1) {
        return 'checked';
    } else {
        return '';
    }
} 

function action_denied()
{
    print '<div class="pd-20" style="text-align: center;"><span style="color:red;font-size: 50px;"><i class="icon-copy ion-alert-circled"></i></span></br> <h4 style="color: #0058ff;">Access Denied</h4> <br> 
        <p>You Do not Have a Permission to Access this Page.</p> <br> <a href="javascript:history.go(-1)" style="text-decoration: underline;color: #a686ff;">Go Back</a></div>';
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



































