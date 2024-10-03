<?php 
include('includes/connection.php');
include('includes/function.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $api = $_REQUEST['api'];
    
    if($api == 'login') {
        
        $qry = "SELECT * FROM employee_detail WHERE Binary username ='" . $_POST['user_name'] . "' and Binary password ='" . $_POST['password'] . "' and company='" . $_POST['company'] . "' and is_active='active'";
    	$result = mysqli_query($mysqli, $qry);
    
    	if (mysqli_num_rows($result) > 0) {
    	    $data = ['result' => 'success', 'message' => 'Login Success!'];
    	} else {
    	    $data = ['result' => 'error', 'message' => 'Invalid Login!'];
    	}
    	
    	echo json_encode($data);
        
    } else if($api == 'unit_list') {
        
        $qry = mysqli_query($mysqli, "SELECT * FROM company WHERE company = 2");
        $result = mysqli_fetch_array($qry);
        
        echo json_encode($result);
        
    } else if($api == 'sewing_out_pcs_scan') {
        
        
        $device_user = $_POST['logUser'];
        $qr_code = $_POST['qr_code'];
        $process = 10;
        $scan_type = 'piecce';
        $scan_using = 'mobile';
        $line_id = 1;
        
        $qr = explode('-', $qr_code);
        
        $query = mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE id = ". $qr[0]);
        $bundle_det = mysqli_fetch_array($query);
        $num = mysqli_num_rows($query);
        
        $bundle_Id = $qr[0];
        $peceId = $qr[1];
        
        $order_id = $bundle_det['order_id'];
        $style_id = $bundle_det['style_id'];
        $sod_combo = $bundle_det['sod_combo'];
        $sod_part = $bundle_det['sod_part'];
        $sod_size = $bundle_det['sod_size'];
        $combo = $bundle_det['combo'];
        $part = $bundle_det['part'];
        $color = $bundle_det['color'];
        $variation_value = $bundle_det['variation_value'];
        
        if($num==0) {
           $response = [ 'result' => 'error', 'message' => 'Invalid QR Code!'];
        } else if(in_array($peceId, explode(',', $bundle_det['s_out_complete']))) {
           $response = [ 'result' => 'duplicate', 'message' => 'Piece Already Scanned in Sewing Out!'];
        } else if($bundle_det['pcs_per_bundle'] < $peceId) {
           $response = [ 'result' => 'error', 'message' => 'Piece Not in this Bundle!'];
        } else {
            
            $compp = array_filter(array_unique(array_merge(explode(',', $bundle_det['s_out_complete']), array(0 => $peceId))));
            $complete_sewing = ($bundle_det['pcs_per_bundle'] == count($compp)) ? "yes" : NULL;
            $new_array = array( 'complete_sewing' => $complete_sewing, 's_out_complete' => implode(',', $compp), 'tot_sewingout' => count($compp));
            
            $ins = Update('bundle_details', $new_array, ' WHERE id = '. $bundle_Id);
            
            $out_ins = [
                'order_id' => $order_id,
                'style_id' => $style_id,
                'sod_combo' => $sod_combo,
                'sod_part' => $sod_part,
                'sod_size' => $sod_size,
                'combo' => $combo,
                'part' => $part,
                'color' => $color,
                'process' => $process,
                'variation_value' => $variation_value,
                
                'scan_using' => $scan_using,
                'scan_type' => $scan_type,
                'device_user' => $device_user,
                'line' => $line_id,
                'bundle_details_id' => $bundle_Id,
                'piece_id' => $peceId,
                'qr_code' => $qr_code,
                'scanned_count' => 1,
                'date' => date('Y-m-d'),
            ];
            
            $dupp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM orbidx_sewingout WHERE scan_using='". $scan_using ."' AND scan_type='". $scan_type ."' AND line='". $line_id ."' AND device_user='". $device_user ."' AND bundle_details_id='". $bundle_Id ."' AND date = '". date('Y-m-d') ."'"));
            
            if($dupp['id']=="") {
                $ins = Insert('orbidx_sewingout', $out_ins);
            } else {
                $out_upd = [
                    'scanned_count' => count(array_filter(array_unique(array_merge(explode(',', $dupp['piece_id']), array(0 => $peceId))))),
                    'piece_id' => implode(',', array_filter(array_unique(array_merge(explode(',', $dupp['piece_id']), array(0 => $peceId))))),
                    'qr_code' => implode(',', array_filter(array_unique(array_merge(explode(',', $dupp['qr_code']), array(0 => $qr_code))))),
                ];
                $ins = Update('orbidx_sewingout', $out_upd, ' WHERE id = '. $dupp['id']);
            }
            if($ins) {
                $response = [ 'result' => 'success', 'message' => 'Success! Sewing Out Saved.'];
            } else {
                $response = [ 'result' => 'error', 'message' => 'Something went Wrong!'];
                
            }
        }
        
        echo json_encode($response);
      
    } else if($api == 'sewing_out_todaylist') {
        
        if(!isset($_POST['logUser'])) {
                $response = [ 'result' => 'error', 'message' => 'User Id Missing!'];
        } else {
            
            $sqll = mysqli_query($mysqli, "SELECT a.*, sum(a.scanned_count) as scanned_count_tot, b.item_image FROM orbidx_sewingout a LEFT JOIN sales_order_detalis b ON a.style_id = b.id 
                WHERE a.device_user='". $_POST['logUser'] ."' AND a.scan_using = 'mobile' AND a.date = '". date('Y-m-d') ."' GROUP BY order_id, style_id, combo, part, color ORDER BY id DESC");
            if(mysqli_num_rows($sqll)>0) {
                // $response['result'] = 'success';
                while($ress = mysqli_fetch_array($sqll)) {
                    $response['data'][] = [
                            'order_image' => 'https://benso.cloud/'.$ress['item_image'],
                            'bo' => sales_order_code($ress['order_id']),
                            'style' => sales_order_style($ress['style_id']),
                            'combo' => color_name($ress['combo']),
                            'part' => part_name($ress['part']),
                            'color' => color_name($ress['color']),
                            'scanned_count' => $ress['scanned_count_tot'],
                        ];
                }
            } else {
                $response = [ 'result' => 'success', 'message' => 'Scanning Not Started!'];
            }
        }
        
        echo json_encode($response);
        
    } else {
        
        $data = ['result' => 'error', 'message' => 'Invalid API!'];
        echo json_encode($data);
    }
    
}








