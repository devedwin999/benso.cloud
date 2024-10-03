<?php 
include('../includes/connection.php');
include('../includes/function.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $api = $_REQUEST['api'];
    
    if($api == 'login') {
        
        $qry = "SELECT * FROM employee_detail WHERE Binary username ='" . $_POST['user_name'] . "' and Binary password ='" . $_POST['password'] . "' and company='" . $_POST['company'] . "' and is_active='active'";
    	$result = mysqli_query($mysqli, $qry);
    
    	if (mysqli_num_rows($result) > 0) {
    	    
    	    $result = mysqli_fetch_array($result);
    	    $data = [
            	        result => 'success',
            	        message => 'Login Success!',
            	        user_id => $result['id'],
            	        username => $result['employee_name'],
            	        user_photo => $base_url.$result['employee_photo'],
            	        department => department_name($result['department']),
            	        company_code => company_code($_POST['company'])
        	        ];
    	} else {
    	    $data = ['result' => 'error', 'message' => 'Invalid Login!'];
    	}
    	
    	echo json_encode($data);
        
    } else if($api == 'company_list') {
        
        $result = array();
        $qry = mysqli_query($mysqli, "SELECT id, company_name, company_code  FROM company WHERE 1 ORDER BY id ASC");
        while($result = mysqli_fetch_array($qry)) {
            $result1['data'][] = array(id => $result['id'], company_name => $result['company_name'], company_code => $result['company_code']);
        }
        
        echo json_encode($result1);
        
    } else if($api == 'dashboard_data') {
        
        $logUser = $_POST['logUser'];
        $date = $_POST['date'];
        
        $emp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT department, employee_code FROM employee_detail WHERE id = '". $logUser ."'"));
        
        $toout = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.date, sum(a.scanned_count) as scanned_count_tot FROM orbidx_sewingout a 
                WHERE a.device_user='". $logUser ."' AND a.scan_using = 'mobile' AND date='". $date ."' GROUP BY a.date ORDER BY id DESC"));
                
        $chhout = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.date, sum(a.scanned_count) as scanned_count_tot FROM orbidx_checking a 
                WHERE a.device_user='". $logUser ."' AND a.scan_using = 'mobile' AND date='". $date ."' GROUP BY a.date ORDER BY id DESC"));
                
        $comPss = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.date, sum(a.scanned_count) as scanned_count_tot FROM orbidx_component_process a 
                WHERE a.device_user='". $logUser ."' AND date='". $date ."' GROUP BY a.date ORDER BY id DESC"));
        
        
        $data['plan_qty'] = '0';
        $data['output_qty']= ($toout['scanned_count_tot'] + $chhout['scanned_count_tot'] + $comPss['scanned_count_tot']);
        $data['plan_cost'] = '0.00';
        $data['output_cost'] = '0.00';
        
        echo json_encode($data);
        
    } else if($api == 'company_logo') {
        
        print $base_url.get_setting_val('APPLICATION_LOGO');
        
    } else if($api == 'sw_process') {
        
        $dept = 5;
        
        $sel = mysqli_query($mysqli, "SELECT process_name, id FROM `process` WHERE `department` = '". $dept ."'");
        if(mysqli_num_rows($sel)>0) {
            while($result = mysqli_fetch_array($sel)) {
                $data['option'][] = array( id => $result['id'], process_name => $result['process_name']);
            }
        } else {
            $data['option'][] = '';
        }
        
        echo json_encode($data);
        
    } else if($api == 'sewing_out_pcs_scan') {
        
        if(isset($_POST['logUser']) && isset($_POST['qr_code']) && isset($_POST['process']) && isset($_POST['logUnit'])) {
            
            $logUnit = $_POST['logUnit'];
            $device_user = $_POST['logUser'];
            $qr_code = $_POST['qr_code'];
            $process = $_POST['process'];
            $scan_type = 'piece';
            $scan_using = 'mobile';
            
            $qr = explode('-', $qr_code);
            
            $query = mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE TRIM(id) = '". $qr[0] ."' OR TRIM(boundle_qr) = '". $_POST['qr_code'] ."'");
            $bundle_det = mysqli_fetch_array($query);
            $num = mysqli_num_rows($query);
            
            $bun_or_pcs = ($bundle_det['id'] == $qr[0]) ? 'Piece' : 'Bundle';
            
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
            
            $ps = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE style_id = '". $style_id ."' AND budget_for = 'Production Budget' AND process = '". $process ."' "));
            
            $rt = mysqli_query($mysqli, "SELECT * FROM pcs_sewingout WHERE order_id = '". $order_id ."' AND style_id = '". $style_id ."' AND process_id = '". $process ."'");
            $pcs_sewingout_num = mysqli_num_rows($rt);
            $pcs_sewingout = mysqli_fetch_array($rt);
            
            
            if($num==0) {
               $response = [ 'result' => 'error', 'message' => 'Invalid QR Code!'];
            } else if($ps['id'] == '') {
               $response = [ 'result' => 'error', 'message' => process_name($process).' not allowed to scan this Style'];
               
            } else if($ps['scanning_type'] != $bun_or_pcs) {
               $response = [ 'result' => 'error', 'message' => process_name($process).' allowed only '. $ps['scanning_type'] .' scan for this Style'];
               
            } else if($ps['scanning_type'] == 'Bundle' && $bun_or_pcs == 'Bundle' && in_array($bundle_det['id'] . '-1', json_decode($pcs_sewingout['scanned_pcs']))) {
               $response = [ 'result' => 'error', 'message' => $bun_or_pcs .' Already Scanned in '. process_name($process).'!'];
                
            } else if($pcs_sewingout_num > 0 && in_array($qr_code, json_decode($pcs_sewingout['scanned_pcs']))) {
               $response = [ 'result' => 'duplicate', 'message' => $bun_or_pcs .' Already Scanned in '. process_name($process).'!'];
               
            } else if($bundle_det['pcs_per_bundle'] < $peceId) {
               $response = [ 'result' => 'error', 'message' => 'Piece Not in this Bundle!'];
               
            } else {
                if($ps['scanning_type'] == 'Piece') {
                    
                    $ins_array = array(0 => $qr_code);
                    $scanned_count = ($pcs_sewingout_num==0) ? '1' : '';
                    
                } else if($ps['scanning_type'] == 'Bundle') {
                    
                    $ff = $bundle_det['id'];
                    $oo = range(1, $bundle_det['pcs_per_bundle']);
                    $ins_array = array_map(fn($opp) => "$ff-$opp", $oo);
                    
                    $scanned_count = ($pcs_sewingout_num==0) ? $bundle_det['pcs_per_bundle'] : '';
                }
                        
                if($pcs_sewingout_num==0) {
                    
                    $ard = array('order_id' => $order_id, 'style_id' => $style_id, 'process_id' => $process, 'scanned_pcs' => json_encode($ins_array));
                    $ins = Insert('pcs_sewingout', $ard);
                } else {
                    $compp = array_filter(array_unique(array_merge(json_decode($pcs_sewingout['scanned_pcs']), $ins_array)));
                    $ard = array('scanned_pcs' => json_encode($compp));
                    $ins = Update('pcs_sewingout', $ard, ' WHERE id = '. $pcs_sewingout['id']);
                }
                
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
                    'logUnit' => $logUnit,
                    'qr_code' => json_encode($ins_array),
                    'scanned_count' => $scanned_count,
                    'date' => date('Y-m-d'),
                ];
                
                $dupp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM orbidx_sewingout 
                        WHERE device_user='". $device_user ."' AND order_id = '". $order_id ."' AND sod_part='". $sod_part ."' AND variation_value='". $variation_value ."' AND process = '". $process ."' AND scan_using='". $scan_using ."' AND date = '". date('Y-m-d') ."'"));
                
                if($dupp['id']=="") {
                    $ins = Insert('orbidx_sewingout', $out_ins);
                } else {
                    $out_upd = [
                        'scanned_count' => count(array_filter(array_unique(array_merge(json_decode($dupp['qr_code']), $ins_array)))),
                        'qr_code' => json_encode(array_filter(array_unique(array_merge(json_decode($dupp['qr_code']), $ins_array)))),
                    ];
                    $ins = Update('orbidx_sewingout', $out_upd, ' WHERE id = '. $dupp['id']);
                }
                if($ins) {
                    $response = [ 'result' => 'success', 'message' => 'Success! '. process_name($process) .' Saved.'];
                } else {
                    $response = [ 'result' => 'error', 'message' => 'Something went Wrong!'];
                }
            }
        } else {
           $response = [ 'result' => 'error', 'message' => 'Parameters Missing!'];
        }
        echo json_encode($response);
        
    } else if($api == 'sewing_out_todaylist') {
        
        if(!isset($_POST['logUser'])) {
                $response = [ 'result' => 'error', 'message' => 'User Id Missing!'];
        } else {
            
            $date = $_POST['date'];
            
            $sqll = mysqli_query($mysqli, "SELECT a.*, sum(a.scanned_count) as scanned_count_tot, b.item_image FROM orbidx_sewingout a LEFT JOIN sales_order_detalis b ON a.style_id = b.id 
                WHERE a.device_user='". $_POST['logUser'] ."' AND a.scan_using = 'mobile' AND a.date = '". $date ."' GROUP BY order_id, style_id, combo, part, color, process ORDER BY id DESC");
            if(mysqli_num_rows($sqll)>0) {
                // $response['result'] = 'success';
                while($ress = mysqli_fetch_array($sqll)) {
                    $response['result'] = 'success';
                    $response['data'][] = [
                            'order_image' => $base_url.$ress['item_image'],
                            'bo' => sales_order_code($ress['order_id']),
                            'style' => sales_order_style($ress['style_id']),
                            'combo' => color_name($ress['combo']),
                            'part' => part_name($ress['part']),
                            'color' => color_name($ress['color']),
                            'process' => process_name($ress['process']),
                            'scanned_count' => $ress['scanned_count_tot'],
                        ];
                }
            } else {
                $response['result'] = 'success';
                $response['data'] = [];
            }
        }
        
        echo json_encode($response);
    } else if($api == 'sewing_out_totallist') {
        
        if(!isset($_POST['logUser'])) {
                $response = [ 'result' => 'error', 'message' => 'User Id Missing!'];
        } else {
            
            $sqll = mysqli_query($mysqli, "SELECT a.date, sum(a.scanned_count) as scanned_count_tot FROM orbidx_sewingout a 
                WHERE a.device_user='". $_POST['logUser'] ."' AND a.scan_using = 'mobile' GROUP BY a.date ORDER BY id DESC");
            if(mysqli_num_rows($sqll)>0) {
                
                while($ress = mysqli_fetch_array($sqll)) {
                    $response['data'][] = [
                            'date' => $ress['date'],
                            'scanned_count' => $ress['scanned_count_tot'],
                        ];
                }
            } else {
                $response['result'] = 'success';
                $response['data'] = [];
            }
        }
        
        echo json_encode($response);
    } else if($api == 'checking_process') {
        
        $dept = 6;
        
        $sel = mysqli_query($mysqli, "SELECT process_name, id FROM `process` WHERE `department` = '". $dept ."'");
        if(mysqli_num_rows($sel)>0) {
            while($result = mysqli_fetch_array($sel)) {
                $data['option'][] = array( id => $result['id'], process_name => $result['process_name']);
            }
        } else {
            $data['option'][] = '';
        }
        
        echo json_encode($data);
    } else if($api == 'checking_pcs_scan') {
        
        if(isset($_POST['logUser']) && isset($_POST['qr_code']) && isset($_POST['process']) && isset($_POST['mode']) && isset($_POST['logUnit'])) {
        
            $logUnit = $_POST['logUnit'];
            $device_user = $_POST['logUser'];
            $qr_code = $_POST['qr_code'];
            $process = $_POST['process'];
            $scan_type = 'piece';
            $scan_using = 'mobile';
            
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
            
            $rt = mysqli_query($mysqli, "SELECT * FROM pcs_checkingout WHERE order_id = '". $order_id ."' AND style_id = '". $style_id ."' AND process_id = '". $process ."'");
            $pcs_checkingout_num = mysqli_num_rows($rt);
            $pcs_checkingout = mysqli_fetch_array($rt);
            
            
            $rt = mysqli_query($mysqli, "SELECT * FROM pcs_checkingout WHERE order_id = '". $order_id ."' AND style_id = '". $style_id ."' AND process_id = '". $process ."'");
            $pcs_checkingout_num = mysqli_num_rows($rt);
            $pcs_checkingout = mysqli_fetch_array($rt);
            
            if($num==0) {
               $response = [ 'result' => 'error', 'message' => 'Invalid QR Code!'];
            } else if($pcs_checkingout_num > 0 && in_array($qr_code, json_decode($pcs_checkingout['scanned_pcs']))) {
               $response = [ 'result' => 'duplicate', 'message' => 'Piece Already Scanned in '. process_name($process)];
            } else if($bundle_det['pcs_per_bundle'] < $peceId) {
               $response = [ 'result' => 'error', 'message' => 'Piece Not in this Bundle!'];
            } else {
                
                // 1 => Good; 2 => Rework; 3 => Rejection
                
                if($_POST['mode']==1) {
                    
                    $compp = array_filter(array_unique(array_merge(explode(',', $bundle_det['ch_good_pcs']), array(0 => $peceId))));
                    $diff = array_diff(explode(',', $bundle_det['tot_checking']), array(0 => $peceId));
                    $new_array = array('ch_good_pcs' => implode(',', $compp), 'tot_checking' => implode(',', $diff));
                    $msg = 'OK Piece';
                } else if($_POST['mode']==2) {
                    
                    $compp = array_filter(array_unique(array_merge(explode(',', $bundle_det['ch_rework_pcs']), array(0 => $peceId))));
                    $diff = array_diff(explode(',', $bundle_det['tot_checking']), array(0 => $peceId));
                    $new_array = array('ch_rework_pcs' => implode(',', $compp), 'tot_checking' => implode(',', $diff));
                    $msg = 'Rework Piece';
                } else if($_POST['mode']==3) {
                    
                    $compp = array_filter(array_unique(array_merge(explode(',', $bundle_det['ch_reject_pcs']), array(0 => $peceId))));
                    $diff = array_diff(explode(',', $bundle_det['tot_checking']), array(0 => $peceId));
                    $new_array = array('ch_reject_pcs' => implode(',', $compp), 'tot_checking' => implode(',', $diff));
                    $msg = 'Rejection Piece';
                }
                
                // $ins = Update('bundle_details', $new_array, ' WHERE id = '. $bundle_Id);
                
                if($pcs_checkingout_num==0) {
                    
                    $ard = array('order_id' => $order_id, 'style_id' => $style_id, 'process_id' => $process, 'scanned_pcs' => json_encode(array(0 => $qr_code)));
                    $ins = Insert('pcs_checkingout', $ard);
                } else {
                    $compp = array_filter(array_unique(array_merge(json_decode($pcs_checkingout['scanned_pcs']), array(0 => $qr_code))));
                    $ard = array('scanned_pcs' => json_encode($compp));
                    $ins = Update('pcs_checkingout', $ard, ' WHERE id = '. $pcs_checkingout['id']);
                }
                
                $md = [ 1 => 'PASSED', 2 => 'REWORK', 3 => 'FAILED'];
                
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
                    
                    'mode' => $md[$_POST['mode']],
                    
                    'scan_using' => $scan_using,
                    'scan_type' => $scan_type,
                    'device_user' => $device_user,
                    'logUnit' => $logUnit,
                    'qr_code' => json_encode(array(0 => $qr_code)),
                    'scanned_count' => 1,
                    'date' => date('Y-m-d'),
                ];
                
                $dupp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM orbidx_checking 
                    WHERE device_user='". $device_user ."' AND order_id = '". $order_id ."' AND sod_part='". $sod_part ."' AND variation_value='". $variation_value ."' AND process = '". $process ."' 
                    AND mode='". $md[$_POST['mode']] ."' AND scan_using='". $scan_using ."' AND date = '". date('Y-m-d') ."'"));
                    // WHERE scan_type='". $scan_type ."' AND mode='". $md[$_POST['mode']] ."' AND device_user='". $device_user ."' AND bundle_details_id='". $bundle_Id ."' AND date = '". date('Y-m-d') ."'"));
                
                if($dupp['id']=="") {
                    $ins = Insert('orbidx_checking', $out_ins); 
                } else {
                    $out_upd = [
                        'scanned_count' => count(array_filter(array_unique(array_merge(json_decode($dupp['qr_code']), array(0 => $qr_code))))),
                        // 'piece_id' => implode(',', array_filter(array_unique(array_merge(explode(',', $dupp['piece_id']), array(0 => $peceId))))),
                        'qr_code' => json_encode(array_filter(array_unique(array_merge(json_decode($dupp['qr_code']), array(0 => $qr_code))))),
                    ];
                    $ins = Update('orbidx_checking', $out_upd, ' WHERE id = '. $dupp['id']);
                }
                
                if($ins) {
                    $response = [ 'result' => 'success', 'message' => process_name($process) .' '. $msg.' Saved.'];
                } else {
                    $response = [ 'result' => 'error', 'message' => 'Something went Wrong!'];
                    
                }
            }
        
        } else {
           $response = [ 'result' => 'error', 'message' => 'Parameters Missing!'];
        }
        
        echo json_encode($response);
      
    } else if($api == 'checking_todaylist') {
        
        if(!isset($_POST['logUser'])) {
                $response = [ 'result' => 'error', 'message' => 'User Id Missing!'];
        } else {
            
            $date = $_POST['date'];
            
            $sqll = mysqli_query($mysqli, "SELECT a.*, sum(a.scanned_count) as scanned_count_tot, b.item_image FROM orbidx_checking a LEFT JOIN sales_order_detalis b ON a.style_id = b.id 
                WHERE a.device_user='". $_POST['logUser'] ."' AND a.scan_using = 'mobile' AND a.date = '". $date ."' GROUP BY order_id, style_id, combo, part, color, process, mode ORDER BY id DESC");
            if(mysqli_num_rows($sqll)>0) {
                // $response['result'] = 'success';
                while($ress = mysqli_fetch_array($sqll)) {
                    $response['result'] = 'success';
                    
                    $md = [ 'PASSED' => '1', 'REWORK' => '2', 'FAILED' => '3'];
                    
                    $response['data'][] = [
                            'order_image' => $base_url.$ress['item_image'],
                            'bo' => sales_order_code($ress['order_id']),
                            'style' => sales_order_style($ress['style_id']),
                            'combo' => color_name($ress['combo']),
                            'part' => part_name($ress['part']),
                            'color' => color_name($ress['color']),
                            'process' => process_name($ress['process']),
                            'scanned_count' => $ress['scanned_count_tot'],
                            'mode' => $md[$ress['mode']],
                        ];
                }
            } else {
                $response['result'] = 'success';
                $response['data'] = [];
            }
        }
        
        echo json_encode($response);
        
    } else if($api == 'checking_totallist') {
        
        if(!isset($_POST['logUser'])) {
                $response = [ 'result' => 'error', 'message' => 'User Id Missing!'];
        } else {
            
            $sqll = mysqli_query($mysqli, "SELECT a.date, sum(a.scanned_count) as scanned_count_tot FROM orbidx_checking a 
                WHERE a.device_user='". $_POST['logUser'] ."' AND a.scan_using = 'mobile' GROUP BY a.date ORDER BY id DESC");
            if(mysqli_num_rows($sqll)>0) {
                
                while($ress = mysqli_fetch_array($sqll)) {
                    $response['data'][] = [
                            'date' => $ress['date'],
                            'scanned_count' => $ress['scanned_count_tot'],
                        ];
                }
            } else {
                $response['result'] = 'success';
                $response['data'] = [];
            }
        }
        
        echo json_encode($response);
        
    } else if($api == 'component_process') {
        
        $dept = 2;
        
        $sel = mysqli_query($mysqli, "SELECT process_name, id FROM `process` WHERE `department` = '". $dept ."'");
        if(mysqli_num_rows($sel)>0) {
            while($result = mysqli_fetch_array($sel)) {
                $data['option'][] = array( id => $result['id'], process_name => $result['process_name']);
            }
        } else {
            $data['option'][] = '';
        }
        
        echo json_encode($data);
        
    } else if($api == 'component_pcs_scan') {
        
        
        if(isset($_POST['logUser']) && isset($_POST['qr_code']) && isset($_POST['process']) && isset($_POST['logUnit'])) {
            
            $logUnit = $_POST['logUnit'];
            $device_user = $_POST['logUser'];
            $qr_code = $_POST['qr_code'];
            $process = $_POST['process'];
            $scan_type = 'piece';
            $scan_using = 'mobile';
            
            $qr = explode('-', $qr_code);
            
            $query = mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE TRIM(id) = '". $qr[0] ."' OR TRIM(boundle_qr) = '". $_POST['qr_code'] ."'");
            $bundle_det = mysqli_fetch_array($query);
            $num = mysqli_num_rows($query);
            
            $bun_or_pcs = ($bundle_det['id'] == $qr[0]) ? 'Piece' : 'Bundle';
            
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
            
            $ps = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE style_id = '". $style_id ."' AND budget_for = 'Production Budget' AND process = '". $process ."' "));
            
            $rt = mysqli_query($mysqli, "SELECT * FROM pcs_component_process WHERE order_id = '". $order_id ."' AND style_id = '". $style_id ."' AND process_id = '". $process ."'");
            $pcs_component_process_num = mysqli_num_rows($rt);
            $pcs_component_process = mysqli_fetch_array($rt);
            
            if($num==0) {
               $response = [ 'result' => 'error', 'message' => 'Invalid QR Code!'];
            } else if($ps['id'] == '') {
               $response = [ 'result' => 'error', 'message' => process_name($process).' not allowed to scan this Style'];
               
            } else if($ps['scanning_type'] != $bun_or_pcs) {
               $response = [ 'result' => 'error', 'message' => process_name($process).' allowed only '. $ps['scanning_type'] .' scan for this Style'];
               
            } else if($ps['scanning_type'] == 'Bundle' && $bun_or_pcs == 'Bundle' && in_array($bundle_det['id'] . '-1', json_decode($pcs_component_process['scanned_pcs']))) {
               $response = [ 'result' => 'error', 'message' => $bun_or_pcs .' Already Scanned in '. process_name($process).'!'];
                
            } else if($pcs_component_process_num > 0 && in_array($qr_code, json_decode($pcs_component_process['scanned_pcs']))) {
               $response = [ 'result' => 'duplicate', 'message' => $bun_or_pcs .' Already Scanned in '. process_name($process).'!'];
            } else if($bundle_det['pcs_per_bundle'] < $peceId) {
               $response = [ 'result' => 'error', 'message' => 'Piece Not in this Bundle!'];
            } else {
                
                if($ps['scanning_type'] == 'Piece') {
                    
                    $ins_array = array(0 => $qr_code);
                    $scanned_count = ($pcs_component_process_num==0) ? '1' : '';
                    
                } else if($ps['scanning_type'] == 'Bundle') {
                    
                    $ff = $bundle_det['id'];
                    $oo = range(1, $bundle_det['pcs_per_bundle']);
                    $ins_array = array_map(fn($opp) => "$ff-$opp", $oo);
                    
                    $scanned_count = ($pcs_component_process_num==0) ? $bundle_det['pcs_per_bundle'] : '';
                }
                
                if($pcs_component_process_num==0) {
                    $ard = array('order_id' => $order_id, 'style_id' => $style_id, 'process_id' => $process, 'scanned_pcs' => json_encode($ins_array));
                    $ins = Insert('pcs_component_process', $ard);
                } else {
                    $compp = array_filter(array_unique(array_merge(json_decode($pcs_component_process['scanned_pcs']), $ins_array)));
                    $ard = array('scanned_pcs' => json_encode($compp));
                    $ins = Update('pcs_component_process', $ard, ' WHERE id = '. $pcs_component_process['id']);
                }
                
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
                    'logUnit' => $logUnit,
                    'qr_code' => json_encode($ins_array),
                    'scanned_count' => $scanned_count,
                    'date' => date('Y-m-d'),
                ];
                
                $dupp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM orbidx_component_process 
                        WHERE device_user='". $device_user ."' AND order_id = '". $order_id ."' AND sod_part='". $sod_part ."' AND variation_value='". $variation_value ."' AND process = '". $process ."' AND scan_using='". $scan_using ."' AND date = '". date('Y-m-d') ."'"));
                
                if($dupp['id']=="") {
                    $ins = Insert('orbidx_component_process', $out_ins);
                } else {
                    $out_upd = [
                        'scanned_count' => count(array_filter(array_unique(array_merge(json_decode($dupp['qr_code']), $ins_array)))),
                        'qr_code' => json_encode(array_filter(array_unique(array_merge(json_decode($dupp['qr_code']), $ins_array)))),
                    ];
                    $ins = Update('orbidx_component_process', $out_upd, ' WHERE id = '. $dupp['id']);
                }
                if($ins) {
                    $response = [ 'result' => 'success', 'message' => 'Success! '. process_name($process) .' Saved.'];
                } else {
                    $response = [ 'result' => 'error', 'message' => 'Something went Wrong!'];
                    
                }
            }
        } else {
           $response = [ 'result' => 'error', 'message' => 'Parameters Missing!'];
        }
        echo json_encode($response);
        
    } else if($api == 'component_todaylist') {
        
        if(!isset($_POST['logUser'])) {
                $response = [ 'result' => 'error', 'message' => 'User Id Missing!'];
        } else {
            
            $date = $_POST['date'];
            
            $sqll = mysqli_query($mysqli, "SELECT a.*, sum(a.scanned_count) as scanned_count_tot, b.item_image FROM orbidx_component_process a LEFT JOIN sales_order_detalis b ON a.style_id = b.id 
                WHERE a.device_user='". $_POST['logUser'] ."' AND a.date = '". $date ."' GROUP BY order_id, style_id, combo, part, color, process ORDER BY id DESC");
            if(mysqli_num_rows($sqll)>0) {
                // $response['result'] = 'success';
                while($ress = mysqli_fetch_array($sqll)) {
                    $response['result'] = 'success';
                    $response['data'][] = [
                            'order_image' => $base_url.$ress['item_image'],
                            'bo' => sales_order_code($ress['order_id']),
                            'style' => sales_order_style($ress['style_id']),
                            'combo' => color_name($ress['combo']),
                            'part' => part_name($ress['part']),
                            'color' => color_name($ress['color']),
                            'process' => process_name($ress['process']),
                            'scanned_count' => $ress['scanned_count_tot'],
                        ];
                }
            } else {
                $response['result'] = 'success';
                $response['data'] = [];
            }
        }
        
        echo json_encode($response);
        
    } else if($api == 'component_totallist') {
        
        if(!isset($_POST['logUser'])) {
                $response = [ 'result' => 'error', 'message' => 'User Id Missing!'];
        } else {
            
            $sqll = mysqli_query($mysqli, "SELECT a.date, sum(a.scanned_count) as scanned_count_tot FROM orbidx_component_process a 
                WHERE a.device_user='". $_POST['logUser'] ."' GROUP BY a.date ORDER BY id DESC");
            if(mysqli_num_rows($sqll)>0) {
                
                while($ress = mysqli_fetch_array($sqll)) {
                    $response['data'][] = [
                            'date' => $ress['date'],
                            'scanned_count' => $ress['scanned_count_tot'],
                        ];
                }
            } else {
                $response['result'] = 'success';
                $response['data'] = [];
            }
        }
        
        echo json_encode($response);
    } else {
        
        $data = ['result' => 'error', 'message' => 'Invalid API!'];
        echo json_encode($data);
    }
}