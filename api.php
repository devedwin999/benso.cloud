<?php 
include('includes/connection.php');
include('includes/function.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
//  echo '<pre>', print_r($_REQUEST, 1); exit;
    
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if ($data !== null) {
        
// Modes : 1. PASSED 2. REWORK 3. FAILED
        
        if (isset($data['device_id']) && isset($data['user_id']) && isset($data['qr_code'])) {
            
            $data = [
                'device_id' => $data['device_id'],
                'user_id' => $data['user_id'],
                'qr_code' => $data['qr_code'],
                'mode' => $data['mode'],
            ];
            
            $qr = explode('-', $data['qr_code']);
            
            $qyy = "SELECT a.* ";
            $qyy .= " FROM orbidx_device a ";
            $qyy .= " WHERE a.device = '". $data['device_id'] ."'";
            $query_new = mysqli_query($mysqli, $qyy);
            $device_det = mysqli_fetch_array($query_new);
            
            $scan_type = $device_det['scan_type'];
            $line_id = $device_det['line'];
            $process = $device_det['process'];
            $device_user = $data['user_id'];
            
            if($scan_type == 'piece') {
                
                $qry = "SELECT * FROM bundle_details WHERE id = ". $qr[0];
                $query = mysqli_query($mysqli, $qry);
                $bundle_det = mysqli_fetch_array($query);
                $num = mysqli_num_rows($query);
                
                $bundle_Id = $qr[0];
                $peceId = $qr[1];
                $qr_code = $data['qr_code'];
            } else if($scan_type == 'bundle') {
                
                $qry = "SELECT * FROM bundle_details WHERE boundle_qr = '". $data['qr_code'] . "'";
                $query = mysqli_query($mysqli, $qry);
                $bundle_det = mysqli_fetch_array($query);
                $num = mysqli_num_rows($query);
                
                $bundle_Id = $bundle_det['id'];
                $peceId = '';
                $qr_code = $data['qr_code'];
            }
            
            $order_id = $bundle_det['order_id'];
            $style_id = $bundle_det['style_id'];
            $sod_combo = $bundle_det['sod_combo'];
            $sod_part = $bundle_det['sod_part'];
            $sod_size = $bundle_det['sod_size'];
            $combo = $bundle_det['combo'];
            $part = $bundle_det['part'];
            $color = $bundle_det['color'];
            $variation_value = $bundle_det['variation_value'];
            if($num==1) {
                 //sewing output
                if($device_det['department'] == 5) {
                    
                    if($scan_type == 'piece') {
                        
                        if(in_array($peceId, explode(',', $bundle_det['s_out_complete']))) {
                           $response = [ 'error' => false, 'result' => false, 'is_duplicate' => true, 'message' => 'Piece Already Scanned in Sewing Out!'];
                        } else if($bundle_det['pcs_per_bundle'] < $peceId) {
                           $response = [ 'error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Piece Not in this Bundle!'];
                        } else {
                            
                            $compp = array_filter(array_unique(array_merge(explode(',', $bundle_det['s_out_complete']), array(0 => $peceId))));
                            $complete_sewing = ($bundle_det['pcs_per_bundle'] == count($compp)) ? "yes" : NULL;
                            $new_array = array( 'complete_sewing' => $complete_sewing, 's_out_complete' => implode(',', $compp), 'tot_sewingout' => count($compp));
                            
                            $ins = Update('bundle_details', $new_array, ' WHERE id = '. $bundle_Id);
                            // $ins = mysqli_query($mysqli, "UPDATE bundle_details SET complete_sewing= $complete_sewing , s_out_complete= '" . implode(',', $compp) . "' WHERE id=" . $bundle_Id);
                            
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
                                
                                'scan_type' => $scan_type,
                                'device_user' => $device_user,
                                'line' => $line_id,
                                'bundle_details_id' => $bundle_Id,
                                'piece_id' => $peceId,
                                'qr_code' => $data['qr_code'],
                                'device_name' => $data['device_id'],
                                'scanned_count' => 1,
                                'date' => date('Y-m-d'),
                            ];
                            
                            $dupp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM orbidx_sewingout WHERE scan_type='". $scan_type ."' AND line='". $line_id ."' AND device_user='". $device_user ."' AND bundle_details_id='". $bundle_Id ."' AND date = '". date('Y-m-d') ."'"));
                            
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
                                $response = [ 'error' => false, 'result' => true, 'is_duplicate' => false, 'message' => 'Success! Sewing Out Saved.'];
                            } else {
                                $response = [ 'error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Something went Wrong!'];
                                
                            }
                        }
                        
                    } else if($scan_type == 'bundle') {
                        
                        if($bundle_det['complete_sewing']=='yes') {
                           $response = [ 'error' => false, 'result' => false, 'is_duplicate' => true, 'message' => 'Bundle Already Scanned in Sewing Out!'];
                        } else {
                            
                            $tot_piece = range(1, $bundle_det['pcs_per_bundle']);
                            $added_pcs = explode(',', $bundle_det['s_out_complete']);
                            
                            $nw_pcs = array_merge($tot_piece, $added_pcs);
                                
                            $new_array = array( 'complete_sewing' => 'yes', 's_out_complete' => implode(',', array_filter(array_unique($nw_pcs))));
                                
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
                                
                                'scan_type' => $scan_type,
                                'line' => $line_id,
                                'device_user' => $device_user,
                                'bundle_details_id' => $bundle_Id,
                                'piece_id' => implode(',', array_filter(array_unique($nw_pcs))),
                                'scanned_count' => count(array_filter(array_unique($nw_pcs))),
                                'qr_code' => $data['qr_code'],
                                'device_name' => $data['device_id'],
                                'date' => date('Y-m-d'),
                            ];
                                
                            $dupp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM orbidx_sewingout WHERE scan_type='". $scan_type ."' AND line='". $line_id ."'  AND device_user='". $device_user ."' AND bundle_details_id='". $bundle_Id ."' AND date = '". date('Y-m-d') ."'"));
                                
                            if($dupp['id']=="") {
                                $ins = Insert('orbidx_sewingout', $out_ins);
                            } else {
                                $out_upd = [
                                    'piece_id' => implode(',', array_filter(array_unique($nw_pcs))),
                                    'scanned_count' => count(array_filter(array_unique($nw_pcs))),
                                    'qr_code' => $data['qr_code'],
                                ];
                                $ins = Update('orbidx_sewingout', $out_upd, ' WHERE id = '. $dupp['id']);
                            }
                            
                            if($ins) {
                                $response = [ 'error' => false, 'result' => true, 'is_duplicate' => false, 'message' => 'Success! Sewing Out Saved.'];
                            } else {
                                $response = [ 'error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Something went Wrong!'];
                                
                            }
                        }
                    }
                }
                // checking
                else if($device_det['department'] == 6) {
                    
                    if($scan_type == 'piece') {
                        
                        if(!in_array($peceId, explode(',', $bundle_det['tot_checking']))) {
                           $response = [ 'error' => false, 'result' => false, 'is_duplicate' => true, 'message' => 'Checking Already Completed!'];
                        } else if($bundle_det['pcs_per_bundle'] < $peceId) {
                           $response = [ 'error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Piece Not in this Bundle!'];
                        } else {
                            
                            if($data['mode']=='PASSED') {
                                
                                $compp = array_filter(array_unique(array_merge(explode(',', $bundle_det['ch_good_pcs']), array(0 => $peceId))));
                                $diff = array_diff(explode(',', $bundle_det['tot_checking']), array(0 => $peceId));
                                $new_array = array('ch_good_pcs' => implode(',', $compp), 'tot_checking' => implode(',', $diff));
                                $msg = 'OK PCS';
                            } else if($data['mode']=='REWORK') {
                                
                                $compp = array_filter(array_unique(array_merge(explode(',', $bundle_det['ch_rework_pcs']), array(0 => $peceId))));
                                $diff = array_diff(explode(',', $bundle_det['tot_checking']), array(0 => $peceId));
                                $new_array = array('ch_rework_pcs' => implode(',', $compp), 'tot_checking' => implode(',', $diff));
                                $msg = 'Rework PCS';
                            } else if($data['mode']=='FAILED') {
                                
                                $compp = array_filter(array_unique(array_merge(explode(',', $bundle_det['ch_reject_pcs']), array(0 => $peceId))));
                                $diff = array_diff(explode(',', $bundle_det['tot_checking']), array(0 => $peceId));
                                $new_array = array('ch_reject_pcs' => implode(',', $compp), 'tot_checking' => implode(',', $diff));
                                $msg = 'Rejection PCS';
                            }
                            
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
                                'variation_value' => $variation_value,
                                
                                'mode' => $data['mode'],
                                
                                'line' => $line_id,
                                'scan_type' => $scan_type,
                                'device_user' => $device_user,
                                'bundle_details_id' => $bundle_Id,
                                'piece_id' => $peceId,
                                'qr_code' => $data['qr_code'],
                                'device_name' => $data['device_id'],
                                'scanned_count' => 1,
                                'date' => date('Y-m-d'),
                            ];
                            
                            $dupp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM orbidx_checking 
                                WHERE scan_type='". $scan_type ."' AND mode='". $data['mode'] ."' AND device_user='". $device_user ."' AND bundle_details_id='". $bundle_Id ."' AND date = '". date('Y-m-d') ."'"));
                            
                            if($dupp['id']=="") {
                                $ins = Insert('orbidx_checking', $out_ins); 
                            } else {
                                $out_upd = [
                                    'scanned_count' => count(array_filter(array_unique(array_merge(explode(',', $dupp['piece_id']), array(0 => $peceId))))),
                                    'piece_id' => implode(',', array_filter(array_unique(array_merge(explode(',', $dupp['piece_id']), array(0 => $peceId))))),
                                    'qr_code' => implode(',', array_filter(array_unique(array_merge(explode(',', $dupp['qr_code']), array(0 => $qr_code))))),
                                ];
                                $ins = Update('orbidx_checking', $out_upd, ' WHERE id = '. $dupp['id']);
                            }
                            
                            if($ins) {
                                $response = ['error' => false, 'result' => true, 'is_duplicate' => false, 'message' => $msg];
                            } else {
                                $response = ['error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Something went Wrong!'];
                                
                            }
                        }
                        
                    } else {
                        $response = ['error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Scanning Not Allowed!'];
                    }
                }
                // component process
                else if($device_det['department'] == 2) {
                    
                    if($scan_type == 'piece') {
                        
                        $peceId_new = $device_det['department'].'-'.$device_det['process'].'-'.$data['qr_code'];
                        
                        if(in_array($peceId_new, explode(',', $bundle_det['component_process']))) {
                           $response = [ 'error' => false, 'result' => false, 'is_duplicate' => true, 'message' => 'Process Already Completed!'];
                        } else if($bundle_det['pcs_per_bundle'] < $peceId) {
                           $response = [ 'error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Piece Not in this Bundle!'];
                        } else {
                            
                            $compp = array_filter(array_unique(array_merge(explode(',', $bundle_det['component_process']), array(0 => $peceId_new))));
                            $new_array = array('component_process' => implode(',', $compp));
                            
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
                                'variation_value' => $variation_value,
                                
                                'department' => $device_det['department'],
                                'process' => $device_det['process'],
                                
                                'line' => $line_id,
                                'scan_type' => $scan_type,
                                'device_user' => $device_user,
                                'bundle_details_id' => $bundle_Id,
                                'piece_id' => $peceId_new,
                                'qr_code' => $data['qr_code'],
                                'device_name' => $data['device_id'],
                                'scanned_count' => 1,
                                'date' => date('Y-m-d'),
                            ];
                            
                            $dupp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM orbidx_component_process 
                                WHERE scan_type='". $scan_type ."' AND process='". $device_det['process'] ."' AND department='". $device_det['department'] ."' AND device_user='". $device_user ."' AND bundle_details_id='". $bundle_Id ."' AND date = '". date('Y-m-d') ."'"));
                            
                            if($dupp['id']=="") {
                                $ins = Insert('orbidx_component_process', $out_ins); 
                            } else {
                                $out_upd = [
                                    'scanned_count' => count(array_filter(array_unique(array_merge(explode(',', $dupp['piece_id']), array(0 => $peceId_new))))),
                                    'piece_id' => implode(',', array_filter(array_unique(array_merge(explode(',', $dupp['piece_id']), array(0 => $peceId_new))))),
                                    'qr_code' => implode(',', array_filter(array_unique(array_merge(explode(',', $dupp['qr_code']), array(0 => $qr_code))))),
                                ];
                                $ins = Update('orbidx_component_process', $out_upd, ' WHERE id = '. $dupp['id']);
                            }
                            
                            if($ins) {
                                $response = ['error' => false, 'result' => true, 'is_duplicate' => false, 'message' => 'Process Saved!'];
                            } else {
                                $response = ['error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Something went Wrong!'];
                                
                            } 
                        }
                        
                    } else {
                        $response = ['error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Scanning Not Allowed!'];
                    }
                }
                // garment process
                else if($device_det['department'] == 3) {
                    
                    if($scan_type == 'piece') {
                        
                        $peceId_new = $device_det['department'].'-'.$device_det['process'].'-'.$data['qr_code'];
                        
                        if(in_array($peceId_new, explode(',', $bundle_det['garment_process']))) {
                           $response = [ 'error' => false, 'result' => false, 'is_duplicate' => true, 'message' => 'Process Already Completed!'];
                        } else if($bundle_det['pcs_per_bundle'] < $peceId) {
                           $response = [ 'error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Piece Not in this Bundle!'];
                        } else {
                            
                            $compp = array_filter(array_unique(array_merge(explode(',', $bundle_det['garment_process']), array(0 => $peceId_new))));
                            $new_array = array('garment_process' => implode(',', $compp));
                            
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
                                'variation_value' => $variation_value,
                                
                                'department' => $device_det['department'],
                                'process' => $device_det['process'],
                                
                                'line' => $line_id,
                                'scan_type' => $scan_type,
                                'device_user' => $device_user,
                                'bundle_details_id' => $bundle_Id,
                                'piece_id' => $peceId_new,
                                'qr_code' => $data['qr_code'],
                                'device_name' => $data['device_id'],
                                'scanned_count' => 1,
                                'date' => date('Y-m-d'),
                            ];
                            
                            $dupp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM orbidx_garment_process 
                                WHERE scan_type='". $scan_type ."' AND process='". $device_det['process'] ."' AND department='". $device_det['department'] ."' AND device_user='". $device_user ."' AND bundle_details_id='". $bundle_Id ."' AND date = '". date('Y-m-d') ."'"));
                            
                            if($dupp['id']=="") {
                                $ins = Insert('orbidx_garment_process', $out_ins); 
                            } else {
                                $out_upd = [
                                    'scanned_count' => count(array_filter(array_unique(array_merge(explode(',', $dupp['piece_id']), array(0 => $peceId_new))))),
                                    'piece_id' => implode(',', array_filter(array_unique(array_merge(explode(',', $dupp['piece_id']), array(0 => $peceId_new))))),
                                    'qr_code' => implode(',', array_filter(array_unique(array_merge(explode(',', $dupp['qr_code']), array(0 => $qr_code))))),
                                ];
                                $ins = Update('orbidx_garment_process', $out_upd, ' WHERE id = '. $dupp['id']);
                            }
                            
                            if($ins) {
                                $response = ['error' => false, 'result' => true, 'is_duplicate' => false, 'message' => 'Process Saved!'];
                            } else {
                                $response = ['error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Something went Wrong!'];
                                
                            } 
                        }
                        
                    } else {
                        $response = ['error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Scanning Not Allowed!'];
                    }
                } else {
                    $response = ['error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Invalid Process or error in configuration!'];
                }
                    
            } else {
                $response = ['error' => true, 'result' => false, 'is_duplicate' => false, 'message' => 'Invalid Barcode!'];
            }
            
            
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } else {
            
            http_response_code(400);
            echo json_encode(['error' => 'Missing parameters']);
        }
    } else {
        
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON data']);
    }
} else {
    
    http_response_code(405);
    echo json_encode(['error' => 'Only POST method is allowed']);
}