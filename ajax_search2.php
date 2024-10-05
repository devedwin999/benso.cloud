<?php
include("includes/connection.php");
include("includes/function.php");

if (!isset($_SESSION['login_id'])) {
	header('Location:index.php');
}

if(isset($_REQUEST['validate_Duplication'])) {
    
    $cnt = mysqli_num_rows(mysqli_query($mysqli, "SELECT ". $_REQUEST['field'] ." FROM ". $_REQUEST['table'] ." WHERE ". $_REQUEST['field'] ." = '". $_REQUEST['value'] ."' "));
    
    $data['count'][] =  $cnt;
    
    echo json_encode($data);
    
} else if (isset($_REQUEST['get_bundleTrack'])) {
    
    $qry = " SELECT a.*, b.order_code, c.style_no, d.part_name, f.color_name, g.type ";
    $qry .= " FROM bundle_details a ";
    $qry .= " LEFT JOIN sales_order b ON a.order_id = b.id ";
    $qry .= " LEFT JOIN sales_order_detalis c ON a.style = c.id ";
    $qry .= " LEFT JOIN part d ON a.part = d.id ";
    $qry .= " LEFT JOIN color f ON a.color = f.id ";
    $qry .= " LEFT JOIN variation_value g ON a.variation_value = g.id ";
    $qry .= " WHERE a.boundle_qr='". $_REQUEST['qr'] ."' ";
    
    $row = mysqli_fetch_array(mysqli_query($mysqli, $qry));

    
    if($row['id']=="") {
        $data['err'][] = 'yes';
        $data['title'][] = 'Bundle Not Found!';
        $data['message'][] = 'Enter a Valid QR.';
        
    } else if(strtotime($row['created_date']) < strtotime('2024-02-09')) {
        $data['err'][] = 'yes';
        $data['title'][] = 'Unable to Track!';
        $data['message'][] = 'Given QR is not Trackable.';
    
    } else {
        
        $data['err'][] = 'no';
        
        
        $insewing = $row['in_sewing'] ? $row['in_sewing'] : 'No';
        
        if($insewing=='yes') {
            $insewing_date = date('d-M, Y', strtotime($row['in_sewing_date']));
            $line_sw = implode(',', emp_name($row['line']));
            $pcd = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=". $row['in_sewing_id']));
            $sw_reff = $pcd['processing_code'];
            $sw_byy = implode(',', emp_name($pcd['created_by']));
            $comsewing = $row['complete_sewing'] ? $row['complete_sewing'] : 'No';
            $comsewing_date = date('d-M, Y', strtotime($row['comp_sewing_date']));
        } else {
            $insewing_date = '-';
            $line_sw = '-';
            $sw_reff = '-';
            $sw_byy = '-';
            $comsewing = '-';
            $comsewing_date = '-';
        }
        
        $ioop = '';
        $nq = "SELECT a.boundle_id, a.processing_code, a.entry_date, a.p_type, a.supplier_id, a.production_unit, a.is_inhouse, a.complete_inhouse, a.is_inwarded, a.dc_date, a.dc_num, b.process_name";
        $nq .= " FROM processing_list a ";
        $nq .= " LEFT JOIN process b ON a.process_id=b.id ";
       
        $nq .= " WHERE a.type = 'process_outward' AND a.order_id=". $row['order_id'];
        
        $psng = mysqli_query($mysqli, $nq);
        
        if(mysqli_num_rows($psng)>0) {
            while($ps_ = mysqli_fetch_array($psng)) {
                
                
                if(in_array($row['id'], explode(',', $ps_['boundle_id']))) {
                    $ioop .= '<tr><td colspan="2">Process : '. $ps_['process_name'] .'</td></tr>';
                    $ioop .= '<tr><td>Process Out DC No</td> <td>'. $ps_['processing_code'] .'</td></tr>';
                    $ioop .= '<tr><td>Process Out Date</td> <td>'. $ps_['entry_date'] .'</td></tr>';
                    
                    if($ps_['p_type'] == 'Outward') {
                        $com = mysqli_fetch_array(mysqli_query($mysqli, "SELECT supplier_name FROM supplier WHERE id=". $ps_['supplier_id']));
                        $ioop .= '<tr><td>Process Out Supplier</td> <td>'. $com['supplier_name'] .'</td></tr>';
                    } else if($ps_['p_type'] == 'Unit') {
                        $com = mysqli_fetch_array(mysqli_query($mysqli, "SELECT company_name FROM company WHERE id=". $ps_['production_unit']));
                        $ioop .= '<tr><td>Process Out Unit</td> <td>'. $com['company_name'] .'</td></tr>';
                    
                        $iih = $ps_['is_inhouse'] ? $ps_['is_inhouse'] : '';
                        $iihS = $ps_['complete_inhouse'] ? $ps_['complete_inhouse'] : '';
                        $ioop .= '<tr><td>In-house Approval</td> <td>'. $iih .'</td></tr>';
                        $ioop .= '<tr><td>In-house Status</td> <td>'. $iihS .'</td></tr>';
                    }
                    
                    $innW = ($ps_['is_inwarded']==1) ? 'Yes' : 'No';
                    $innW_dt = ($ps_['is_inwarded']==1) ? $ps_['dc_num'] : '-';
                    $innW_DC = ($ps_['is_inwarded']==1) ? date('d-M, Y', strtotime($row['dc_date'])) : '-';
                    
                    $ioop .= '<tr><td>Is Inwarded</td> <td>'. $innW .'</td></tr>';
                    
                    $ioop .= '<tr><td>Inward Date</td> <td>'. $innW_DC .'</td></tr>';
                    $ioop .= '<tr><td>Inward DC No</td> <td>'. $innW_dt .'</td></tr>';
                }
            }
        } else {
            $ioop .= '<tr><td colspan="2" class="text-cen">No Process Outwarded.</td></tr>';
        }
        $data['content'][] = '<div class="row">
        <div class="col-md-6">
            <div class="card card-box">
                <div class="card-header">General Info</div>
                <div class="card-body over-y-auto">
                    <blockquote class="blockquote mb-0">
                        <table class="table table-bordered">
                            <tr><td>BO</td><td>'. $row['order_code'] .'</td></tr>
                            <tr><td>Style</td><td>'. $row['style_no'] .'</td></tr>
                            <tr><td>Part</td><td>'. $row['part_name'] .'</td></tr>
                            <tr><td>Color</td><td>'. $row['color_name'] .'</td></tr>
                            <tr><td>Size</td><td>'. $row['type'] .'</td></tr>
                            <tr><td>Bundle Number</td><td>'. $row['bundle_number'] .'</td></tr>
                            <tr><td>Bundle Qty</td><td>'. $row['pcs_per_bundle'] .'</td></tr>
                        </table>
                    </blockquote>
                </div>
            </div>
            <br>
            <div class="card card-box">
                <div class="card-header">Cutting Info</div>
                <div class="card-body over-y-auto">
                    <blockquote class="blockquote mb-0">
                        <table class="table table-bordered">
                            <tr><td>Cutting Entry Number</td><td>'. $row['entry_num'] .'</td></tr>
                            <tr><td>Cutting Date</td><td>'. date('d-M, Y', strtotime($row['created_date'])) .'</td></tr>
                            <tr><td>Cutting Lay Number</td><td>'. $row['lay_length'] .'</td></tr>
                        </table>
                    </blockquote>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-box">
                <div class="card-header">Process Out & In</div>
                <div class="card-body over-y-auto">
                    <blockquote class="blockquote mb-0">
                        <table class="table table-bordered">'. $ioop .'</table>
                    </blockquote>
                </div>
            </div>
            <br>
            <div class="card card-box">
                <div class="card-header">Sewing Info</div>
                <div class="card-body over-y-auto">
                    <blockquote class="blockquote mb-0">
                        <table class="table table-bordered">
                            <tr><td>In Sewing</td><td>'. $insewing .'</td></tr>
                            <tr><td>Sewing In Date</td><td>'. $insewing_date .'</td></tr>
                            <tr><td>Line</td><td>'. $line_sw .'</td></tr>
                            <tr><td>In Sewing DC Ref</td><td>'. $sw_reff .'</td></tr>
                            <tr><td>In Sewing By</td><td>'. $sw_byy .'</td></tr>
                            <tr><td>Complete Sewing</td><td>'. $comsewing .'</td></tr>
                            <tr><td>Complete Sewing Date</td><td>'. $comsewing_date .'</td></tr>
                        </table>
                    </blockquote>
                </div>
            </div>
        </div>
            </div>';
    }
    
    timeline_history('Insert', 'bundle_details', $row['id'], 'Bundle Details tracked. Ref QR: ' .$_REQUEST['qr']);
    
    
    
    echo json_encode($data);

} else if(isset($_REQUEST['getPO_for_fabric_Preceipt'])) {

    $qry1 = "SELECT * ";
    $qry1 .= " FROM fabric_po a ";
    
    $qry1 .= " WHERE a.supplier = '". $_REQUEST['supplier'] ."' AND receipt_complete='No' ";
    // print $qry1;
    $temp1 = mysqli_query($mysqli, $qry1); 
    
    
    if(mysqli_num_rows($temp1)>0) {
            $data['po_num'][] = '<option value="">-- select --</option>';
        while($row1 = mysqli_fetch_array($temp1)) {
            $data['po_num'][] = '<option value="'. $row1['id'] .'">'. $row1['entry_number'] .'</option>';
        }
    } else {
            $data['po_num'][] = '';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getBO_for_fabric_Preceipt'])) {

    $qry1 = "SELECT a.stock_bo, a.order_id, a.style_id ";
    $qry1 .= " FROM fabric_po_det a ";
    
    $qry1 .= " WHERE a.fab_po = '". $_REQUEST['po_num'] ."' GROUP BY a.order_id ";
    // print $qry1;
    $temp1 = mysqli_query($mysqli, $qry1); 
    
    
    if(mysqli_num_rows($temp1)>0) {
            $data['order_id'][] = '<option value="">-- select --</option>';
        while($row1 = mysqli_fetch_array($temp1)) {
            
            if($row1['stock_bo'] == 'bo') {
                $data['order_id'][] = '<option value="'. $row1['order_id'] .'">'. sales_order_code($row1['order_id']) .' | '. sales_order_style($row1['style_id']) .'</option>';
            } else if($row1['stock_bo'] == 'stock') {
                $data['order_id'][] = '<option value="'. $row1['order_id'] .'">'. stockgroup_name($row1['order_id']) .'</option>';
            }
        }
    } else {
            $data['order_id'][] = '';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getFab_Preceipt_dett'])) {

    $qry1 = "SELECT a.*, d.budget_type, e.yarn_id, e.fabric_id, e.color as color_id, e.dia_size, e.yarn_mixing, e.id as fab_req ";
    $qry1 .= " FROM fabric_po_det a ";
    $qry1 .= " LEFT JOIN fabric_po b ON b.id = a.fab_po ";
    $qry1 .= " LEFT JOIN sales_order c ON c.id = a.order_id ";
    $qry1 .= " LEFT JOIN process d ON d.id = a.po_stage ";
    $qry1 .= " LEFT JOIN fabric_requirements e ON e.id = a.material_name ";
    
    $qry1 .= " WHERE a.fab_po = '". $_REQUEST['po_num'] ."' AND a.order_id = '". $_REQUEST['id'] ."'";
    $temp1 = mysqli_query($mysqli, $qry1); 
    
    if(mysqli_num_rows($temp1)>0) {
        $data['material_name'][] ="<option value=''>-- select --</option>";
        while($row1 = mysqli_fetch_array($temp1)) {
            
            $received_wt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(received_qty) as received_qty FROM fabric_po_receipt_det WHERE fabric_po_det = ". $row1['id']));
            $bbl = ($row1['po_qty_wt'] - $received_wt['received_qty']);
            if($row1['stock_bo']=='bo') {
                if($row1['budget_type'] == 'Yarn') {
                    $td3 = mas_yarn_name($row1['yarn_id']);
                // } else if($row1['budget_type'] == 'Fabric') {
                } else {
                    $opp = '';
                    foreach(json_decode($row1['yarn_mixing']) as $expp) {
                        $exp = explode('=', $expp);
                        $opp .= " | ". mas_yarn_name($exp[0]) .' - '. color_name($exp[1]) .' - '. $exp[2] .'%';
                    }
                    $td3 = fabric_name($row1['fabric_id']) .'| '. $opp .' | Dia: '. $row1['dia_size'] .'.';
                // } else { $td3 = '';
                }
                
                $data['material_name'][] = '<option value="'. $row1['id'] .'" data-fab_req="'. $row1['fab_req'] .'" data-qwt="'. $row1['po_qty_wt'] .'" data-bal="'. $bbl .'" data-bag="'. $row1['bag_roll'] .'">'. $td3 .'</option>';
                
            } else if($row1['stock_bo']=='stock') {
                if(in_array($row1['po_stage'], array('19', '20', '21'))) {
                    $data['material_name'][] = '<option value="'. $row1['id'] .'" data-fab_req="'. $row1['material_name'] .'" data-qwt="'. $row1['po_qty_wt'] .'" data-bal="'. $bbl .'" data-bag="'. $row1['bag_roll'] .'">'. mas_yarn_name($row1['material_name']) .'</option>';
                } else {
                    $fth = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM mas_stockitem WHERE id = '". $row1['material_name'] ."'"));
                    $opp = '';
                    foreach(json_decode($fth['yarn_mixing']) as $expp) {
                        $exp = explode('=', $expp);
                        $opp .= " | ". mas_yarn_name($exp[0]) .' - '. color_name($exp[1]) .' - '. $exp[2] .'% Dia : '. $row1['stock_dia'];
                    }
                    $data['material_name'][] = '<option value="'. $fth['id'] .'" data-fab_req="'. $row1['material_name'] .'" data-qwt="'. $row1['po_qty_wt'] .'" data-bal="'. $bbl .'" data-bag="'. $row1['bag_roll'] .'">'. fabric_name($fth['fabric_name']) . $opp .'</option>';
                }
            }
        }
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['to_process_dc'])) {
    
    $qry1 = "SELECT a.so_id, a.style_id ";
    $qry1 .= " FROM budget_process a ";
    $qry1 .= " LEFT JOIN sales_order b ON b.id = a.so_id ";
    
    $qry1 .= " WHERE a.budget_for = 'Fabric Budget' AND a.is_approved = 'true' AND a.process='". $_REQUEST['to_process'] ."' GROUP BY b.id ORDER BY a.style_id asc";
    
    $temp1 = mysqli_query($mysqli, $qry1); 
    
    
    if(mysqli_num_rows($temp1)>0) {
            $data['order_id'][] = '<option value="">-- select --</option>';
        while($row1 = mysqli_fetch_array($temp1)) {
            $data['order_id'][] = '<option value="'. $row1['style_id'] .'">'. sales_order_code($row1['so_id']) .' | '. sales_order_style($row1['style_id']) .'</option>';
        }
    } else {
            $data['order_id'][] = '';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['pOut_plan_detail'])) {
    
    
    $fth = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_requirements WHERE id = ". $_REQUEST['material_name']));
    $process = mysqli_fetch_array(mysqli_query($mysqli, "SELECT budget_type FROM process WHERE id = ". $_REQUEST['to_process']));
    
    // 22 knitting process
    if($_REQUEST['to_process']==22) {
        
        $data['mod_thead'][] = '<tr> <th>Yarn</th> <th>Mixing %</th> <th class="d-none">DC Balance</th> <th>Stock Qty</th> <th>Bag / Roll</th> <th>DC QTY/Wt</th> </tr>';
        
        foreach(json_decode($fth['yarn_mixing']) as $yarn) {
            
            $exp = explode('=', $yarn);
            
            $prev = mysqli_fetch_array(mysqli_query($mysqli, "SELECT id, req_wt FROM fabric_requirements WHERE style_id = '". $_REQUEST['order_id'] ."' AND process_order = '". ($fth['process_order']-1) ."' AND yarn_id = '". $exp[0] ."'"));
                
            $stk = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(stock_qty) as stock_qty FROM fabric_stock WHERE stock_status = 'in_stock' AND fabric_requirements = ". $prev['id']));
            
            $stock = ($stk['stock_qty'] ? $stk['stock_qty'] : 0);
            
            $need_qtyy = ($_REQUEST['output_wt']/100) * $exp[2];
            
            $bgg = ($stock<$need_qtyy) ? '#dc3545' : '#e9ecef';
            $crrl = ($stock<$need_qtyy) ? '#ffffff' : '#000000';
            $data['mod_tbody'][] = '<tr><td><input type="hidden" class="form-control" name="yarn_id[]" value="'. $exp[0] .'"><input type="hidden" class="form-control" name="requirements_from[]" value="'. $prev['id'] .'">'. mas_yarn_name($exp[0]) .'</td>
                                        <td><input type="hidden" class="form-control" name="mixing_per[]" value="'. $exp[2] .'">'. $exp[2] .'%</td> 
                                        <td class="d-none"><input type="text" class="form-control" placeholder="Dc Balance" name="dc_balance[]" value="'. $stock .'" readonly></td>
                                        <td><input type="text" class="form-control stocks" placeholder="Stock" name="stock[]" value="'. $stock .'" readonly></td>
                                        <td><input type="number" class="form-control bg_roll" placeholder="Bag / Roll" name="bag_roll[]" required></td>
                                        <td><input type="text" class="form-control reqqs" placeholder="DC QTY/Wt" name="dc_qty_wt[]" value="'. $need_qtyy .'" style="background-color: '. $bgg .'; color: '. $crrl .';" readonly>
                                            <input type="hidden" class="form-control" name="group_type" value="yarn">
                                        </td></tr>';
        }
        
    // } else if($process['budget_type']=='Dyeing Color' || $process['budget_type'] == 'Fabric') {
    } else {
        
        $data['mod_thead'][] = '<tr> <th>#</th> <th>Fabric</th> <th>Dying Color</th> <th class="d-none">DC Balance</th> <th>Stock Qty</th> <th>Bag / Roll</th> <th>DC QTY/Wt</th> </tr>';
            
            // $prev = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(dc_inward_wt) as dc_inward_wt FROM fabric_dc_det WHERE order_id = '". $_REQUEST['order_id'] ."' AND process_order = '". ($fth['process_order']-1) ."' AND fabric_id = '". $_REQUEST['fabric_id'] ."'"));
            // $ooutP = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(output_wt) as output_wt FROM fabric_dc_det WHERE order_id = '". $_REQUEST['order_id'] ."'  AND process_order = '". $fth['process_order'] ."' AND fabric_id = '". $_REQUEST['fabric_id'] ."'"));
            
            // $receipt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(received_qty) as received_qty FROM fabric_po_receipt_det WHERE fabric_requirements = ". $_REQUEST['material_name']));
            
            // $stock = (($receipt['received_qty'] + $prev['dc_inward_wt']) - $ooutP['output_wt']);
            
            $reqMnt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_requirements WHERE id=". $_REQUEST['material_name']));
            
            $prev = mysqli_fetch_array(mysqli_query($mysqli, "SELECT id, req_wt FROM fabric_requirements 
                    WHERE style_id = '". $_REQUEST['order_id'] ."' AND process_order = '". ($fth['process_order']-1) ."' AND fabric_id = '". $reqMnt['fabric_id'] ."' AND yarn_mixing = '". $reqMnt['yarn_mixing'] ."' AND dia_size = '". $reqMnt['dia_size'] ."'"));
            
            $stk = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(stock_qty) as stock_qty FROM fabric_stock WHERE stock_status = 'in_stock' AND fabric_requirements = ". $prev['id']));

            $stock = $stk['stock_qty'] ? $stk['stock_qty'] : 0;
            
            $data['mod_tbody'][] = '<tr><td>'. $prev['id'] .'</td><td>'. fabric_name($_REQUEST['fabric_id']) .'</td>
                                        <td>
                                            <input type="hidden" class="form-control" name="requirements_from[]" value="'. $prev['id'] .'">
                                            <input type="hidden" name="color_id[]" value="'. $reqMnt['color'] .'"> '. color_name($reqMnt['color']) . '
                                        </td> 
                                        <td class="d-none"><input type="text" class="form-control" placeholder="Dc Balance" name="dc_balance[]" value="'. $_REQUEST['temp_dcBal'] .'" readonly></td>
                                        <td><input type="text" class="form-control stocks" placeholder="Stock" name="stock[]" value="'. $stock .'" readonly></td>
                                        <td><input type="text" class="form-control" placeholder="Bag / Roll" name="bag_roll[]"></td>
                                        <td><input type="text" class="form-control reqqs" placeholder="DC QTY/Wt" name="dc_qty_wt[]" value="'. $_REQUEST['output_wt'] .'" readonly>
                                            <input type="hidden" class="form-control" name="group_type" value="fabric">
                                        </td></tr>';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['MaterialFor_fabDC'])) {
    
    if($_REQUEST['style_id']!="") {
        $pss = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM process WHERE id= ". $_REQUEST['process_id']));
        $reqq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT process_order FROM fabric_requirements WHERE process_id= ". $_REQUEST['process_id']));
        
        $qrr = "SELECT a.* ";
        $qrr .= " FROM fabric_requirements a ";
        $qrr .= " WHERE a.style_id = '". $_REQUEST['style_id'] ."' AND a.process_id = '". $_REQUEST['process_id'] ."' ";
        
        $res = mysqli_query($mysqli, $qrr);
        
        $data['material'][] = '<option value="">-- select --</option>';
        while($row = mysqli_fetch_array($res)) {
            
            $ooutP = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(received_qty) as received_qty FROM fabric_stock WHERE fabric_requirements = ". $row['id']));
            
            $js = json_decode($row['yarn_mixing']);
            $opp = '';
            foreach($js as $val) {
                $exp = explode('=', $val);
                $clr = ($exp[1] != "") ? ' - '.color_name($exp[1]) : '';
                $opp .= " | ". mas_yarn_name($exp[0]). $clr .' - '. $exp[2] .'%';
            }
            
        // 22 knitting process
        if($pss['id']!='22') {
            $then = " | Color: ". color_name($row['color']) ." | Dia: ". $row['dia_size'];
        } else {
            $then = '';
        }
            $data['material'][] = '<option value="'. $row['id'] .'" data-fabric="'. $row['fabric_id'] .'" data-wtt="'. ($row['req_wt'] - $ooutP['received_qty']) .'">#'. $row['id'] .'=> '. fabric_name($row['fabric_id']) . $opp . $then .'</option>';
        }
    } else {
        $data['material'][] = '';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['not_Inwarded_fabDc'])) {
    
    $g = mysqli_query($mysqli, "SELECT b.id, b.dc_number, b.process FROM fabric_dc_det a LEFT JOIN fabric_dc b ON a.fabric_dc_id=b.id WHERE a.complete_inward='no' AND b.supplier= '". $_REQUEST['supplier'] ."' GROUP BY a.fabric_dc_id ORDER BY a.id DESC");
    
    $data['process'][] = '<option value="" data-process="">-- select --</option>';
    while($res = mysqli_fetch_array($g)) {
        $data['process'][] = '<option value="'. $res['id'] .'" data-process="'. $res['process'] .'">'. $res['dc_number'] .' ('. process_name($res['process']) .')</option>';
    }

    echo json_encode($data);
    
} else if(isset($_REQUEST['getFab_puechase_material_Name'])) {
    
    if($_REQUEST['stock_bo']=='bo') {
        
        $qry = "SELECT * ";
        $qry .= " FROM fabric_requirements a ";
        $qry .= " WHERE a.style_id = '". $_REQUEST['style_id'] ."' AND a.process_id = '". $_REQUEST['process_id'] ."' ";
        
        $rs = mysqli_query($mysqli, $qry);
        
        $data['material_name'][] = '<option value="">-- select --</option>';
        
        while($row = mysqli_fetch_array($rs)) {
            
            // $already = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(po_qty_wt) as po_qty_wt FROM fabric_po_det WHERE material_name = '". $row['id'] ."'"));
            $already = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(received_qty) as received_qty FROM fabric_stock WHERE stock_bo = 'bo' AND fabric_requirements = '". $row['id'] ."'"));
                
            $req_wt = $row['req_wt'] - $already['received_qty'];
            
            // $fabrica = mysqli_fetch_array(mysqli_query($mysqli, "SELECT rate FROM budget_process WHERE process = '". $_REQUEST['process_id'] ."' AND yarn_id = '". $row['yarn_id'] ."'"));
            $fabrica = mysqli_fetch_array(mysqli_query($mysqli, "SELECT rate FROM budget_process WHERE requirement_id = '". $row['id'] ."'"));
            
            if($row['fabric_id'] == NULL) {
                
                $data['material_name'][] = '<option value="'. $row['id'] .'" data-req="'. $req_wt .'" data-budamt="'. ($fabrica['rate'] ? $fabrica['rate'] : 0) .'">'. mas_yarn_name($row['yarn_id']) .'</option>';
                
            } else if($row['yarn_id'] == NULL) {
                
                $opp = '';
                foreach(json_decode($row['yarn_mixing']) as $expp) {
                    $exp = explode('=', $expp);
                    
                    $opp .= " | ". mas_yarn_name($exp[0]) .' - '. color_name($exp[1]) .' - '. $exp[2] .'%';
                }
                
                $data['material_name'][] = '<option value="'. $row['id'] .'" data-req="'. $req_wt .'" data-budamt="'. ($fabrica['rate'] ? $fabrica['rate'] : 0) .'">'. fabric_name($row['fabric_id']) . $opp .' | Dia: '. $row['dia_size'] .'</option>';
            }
        }
        
    } else if($_REQUEST['stock_bo'] == 'stock') {
        
        $data['material_name'][] = '<option value="">Select</option>';
        
        if(in_array($_REQUEST['process_id'], array('19', '20', '21'))) {
            $fth = mysqli_query($mysqli, "SELECT * FROM mas_yarn ORDER BY yarn_name ASC");
            while($row = mysqli_fetch_array($fth)) {
                $data['material_name'][] = '<option value="'. $row['id'] .'" data-req="" data-budamt="">'. $row['yarn_name'] .'</option>';
            }
        } else {
            $fth = mysqli_query($mysqli, "SELECT * FROM mas_stockitem ORDER BY id DESC");
            while($row = mysqli_fetch_array($fth)) {
                
                $opp = '';
                foreach(json_decode($row['yarn_mixing']) as $expp) {
                    $exp = explode('=', $expp);
                    
                    $opp .= " | ". mas_yarn_name($exp[0]) .' - '. color_name($exp[1]) .' - '. $exp[2] .'%';
                }
                
                $data['material_name'][] = '<option value="'. $row['id'] .'" data-req="" data-budamt="">'. fabric_name($row['fabric_name']) . $opp .'</option>';
            }
        }
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['getTaskTypes'])) {
    
    if($_REQUEST['value']=="production_task") {
            
        $qry = "SELECT * ";
        $qry .= " FROM process a ";
        $qry .= " WHERE a.process_type = 'Production' ";
        $query = mysqli_query($mysqli, $qry);        
        $data['optionn'][] = '<option value="">-- select --</option>';
        while($result = mysqli_fetch_array($query)) {
            $data['optionn'][] = '<option value="'. $result['id'] .'">'. $result['process_name'] .'</option>';
        }
            
    } else if($_REQUEST['value']=='fabric_task') {
        $qry = "SELECT * ";
        $qry .= " FROM process a ";
        $qry .="  WHERE a.process_type = 'Fabric' ";
        $query = mysqli_query($mysqli, $qry);
        $data['optionn'][] = '<option>-- select -- </option>';
         while($result = mysqli_fetch_array($query)) {
            $data['optionn'][] = '<option value="'. $result['id'] .'">'. $result['process_name'] .'</option>';
        }
            
    } else if($_REQUEST['value']=='store_task') {
        $qry1 = "SELECT * ";
        $qry1 .= " FROM mas_accessories_type ";
        $query = mysqli_query($mysqli, $qry1);
        $data['optionn'][] = '<option>-- select -- </option>';
         while($result = mysqli_fetch_array($query)) {
            $data['optionn'][] = '<option value="'. $result['id'] .'">'. $result['type_name'] .'</option>';
        }
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['material_needs'])) {
    
    
} else if (isset($_REQUEST['purchase_ordertest'])) {

    if ($_REQUEST['value'] == 'fabric_po') {
        $qry1 = "SELECT * ";
        $qry1 .= " FROM fabric_po ORDER BY id DESC";
        $query = mysqli_query($mysqli, $qry1);

        $data['optionn'][] = '<option value="">-- select -- </option>';
        while ($result = mysqli_fetch_array($query)) {
            $data['optionn'][] = '<option value="' . $result['id'] . '">' . $result['entry_number'] . '</option>';
        }
    } else if ($_REQUEST['value'] == 'fabric_dc') {
        $qry1 = "SELECT * ";
        $qry1 .= " FROM fabric_dc ORDER BY id DESC";
        $query = mysqli_query($mysqli, $qry1);

        $data['optionn'][] = '<option value="">-- select -- </option>';
        while ($result = mysqli_fetch_array($query)) {
            $data['optionn'][] = '<option value="' . $result['id'] . '">' . $result['dc_number'] . '</option>';
        }
    }

    echo json_encode($data);
    
} else if (isset($_REQUEST['getCancel_details'])) {

    if($_REQUEST['id']!="") {
        if($_REQUEST['type']=='fabric_po') {
            
            $qry1 = "SELECT a.*, b.yarn_id, b.fabric_id ";
            $qry1 .= " FROM fabric_po_det a";
            $qry1 .= " LEFT JOIN fabric_requirements b ON b.id = a.material_name ";
            $qry1 .= " WHERE a.fab_po = '". $_REQUEST['id'] ."' ";
            $query = mysqli_query($mysqli, $qry1);
            
            while ($result = mysqli_fetch_array($query)) {
                
                if($result['po_stage']==26) {
                    $matt = mas_yarn_name($result['yarn_id']);
                } else {
                    $matt = fabric_name($result['fabric_id']);
                }
                
                if($_REQUEST['opdc_canceltype']=='full') {
                    $pricc = '<input type="hidden" name="cancel_qty[]" id="cancel_qty" value="'. $result['po_qty_wt'] .'">'. $result['po_qty_wt'];
                } else {
                    $pricc = '<input type="text" name="cancel_qty[]" id="cancel_qty" class="form-control" value="'. $result['po_qty_wt'] .'">';
                }
                
                $data['tbody'][] = '<tr>
                                        <td>
                                            <input type="hidden" name="podc_cancel_det_id[]" id="podc_cancel_det" value="">
                                            <input type="hidden" name="cancel_id[]" id="cancel_id" value="'. $result['id'] .'">
                                            '. sales_order_code($result['order_id']) .' - '. $result['id']. '</td>
                                        <td>'. process_name($result['po_stage']) .'</td>
                                        <td>'. $matt .'</td>
                                        <td>'. $pricc .'</td>
                                    </tr>';
            }
        } else if($_REQUEST['type']=='fabric_dc') {
            
            $qry1 = "SELECT a.*, b.yarn_id, b.fabric_id ";
            $qry1 .= " FROM fabric_dc_det a";
            $qry1 .= " LEFT JOIN fabric_requirements b ON b.id = a.fab_req_id ";
            $qry1 .= " WHERE a.fabric_dc_id = '". $_REQUEST['id'] ."' ";
            $query = mysqli_query($mysqli, $qry1);
            
            while ($result = mysqli_fetch_array($query)) {
                if($result['process_id']==26) {
                    $matt = mas_yarn_name($result['yarn_id']);
                } else {
                    $matt = fabric_name($result['fabric_id']);
                }
                
                if($_REQUEST['opdc_canceltype']=='full') {
                    $pricc = '<input type="hidden" name="cancel_qty[]" id="cancel_qty" value="'. $result['dc_qty_wt'] .'">'. $result['dc_qty_wt'];
                } else {
                    $pricc = '<input type="text" name="cancel_qty[]" id="cancel_qty" class="form-control" value="'. $result['dc_qty_wt'] .'">';
                }
                
                $data['tbody'][] = '<tr>
                                        <td>
                                            <input type="hidden" name="podc_cancel_det_id[]" id="podc_cancel_det" value="">
                                            <input type="hidden" name="cancel_id[]" id="cancel_id" value="'. $result['id'] .'">
                                            '. sales_order_code($result['order_id']) .' - '. $result['id']. '</td>
                                        <td>'. process_name($result['process_id']) .'</td>
                                        <td>'. $matt .'</td>
                                        <td>'. $pricc .'</td>
                                    </tr>';
            }
        }
    } else {
        $data['tbody'][] = '';
    }

    echo json_encode($data);

} else if (isset($_REQUEST['showAttendance'])) {

    $gs = mysqli_query($mysqli, "SELECT * FROM attendance WHERE employee_id = '". $_REQUEST['employee'] ."' AND date = '". $_REQUEST['date'] ."' ORDER BY id ASC");
    $x = 1;
    $totalTime = 0;      
    while($resu = mysqli_fetch_array($gs)) {
        $timeA = $resu['out_time'];
        $timeB = $resu['in_time'];
        $timeDiff = abs($timeB - $timeA);
        $hours = floor($timeDiff / 3600);
        $minutes = floor(($timeDiff % 3600) / 60);
        $seconds = $timeDiff % 60;
        
        $hours = ($hours<10) ? '0'.$hours : $hours;
        $minutes = ($minutes<10) ? '0'.$minutes : $minutes;
        $seconds = ($seconds<10) ? '0'.$seconds : $seconds;
        $hoursDiff = ($resu['out_time'] != NULL) ? $hours .':'. $minutes .':'. $seconds : '-';
        $outt = ($resu['out_time'] != NULL) ? date('h:i:s A', $resu['out_time']) : '-';
        $data['tbody'][] = '<tr><td>'. $x .'</td> <td>'. date('h:i:s A', $resu['in_time']) .'</td> <td>'. $outt .'</td> <td>'. $hoursDiff.'</td></tr>'; 
        $totalTime += ($resu['out_time'] != NULL) ? $timeDiff : '0';
        $x++; 
    }
    
    $hours1 = floor($totalTime / 3600);
    $minutes1 = floor(($totalTime % 3600) / 60);
    $seconds1 = $totalTime % 60;
    $hours1 = ($hours1<10) ? '0'.$hours1 : $hours1;
    $minutes1 = ($minutes1<10) ? '0'.$minutes1 : $minutes1;
    $seconds1 = ($seconds1<10) ? '0'.$seconds1 : $seconds1;
    $data['tbody'][] = '<tr><td colspan="3"><b>Total Working Hours</b></td><td><b>'. $hours1.':'.$minutes1.':'.$seconds1 .'</b></td></tr>';
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['get_all_process'])) {
    
    $data['process_list'][] = select_dropdown('process', array('id', 'process_name'), 'process_name ASC', '', ' WHERE process_type="Fabric"', '');
    
    echo json_encode($data);
    
} else if (isset($_REQUEST['get_stock_item_edit'])) {

    $ass = "SELECT * FROM mas_stockitem WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $sql = mysqli_fetch_array($asss);

    ?>
        <div class="col-md-12">
            <input type="hidden" name="mas_stockitem_id" name="mas_stockitem_id" value="<?= $_REQUEST['id']; ?>">
            <label for="edit_fabric_type">Fabric Type <span class="text-danger">*</span></label>
            <div class="form-group">
                <select class="form-control custom-select2" name="edit_fabric_type" id="edit_fabric_type" style="width:100%" required>
                    <?php
                        $type = array('FAB_SOLID' => 'Solid', 'FAB_YANDD' => 'Y/D', 'FAB_MELANGE' => 'Melange');
                        foreach($type as $key => $val) {
                            $ss = ($sql['fabric_type'] == $key) ? 'selected' : '';
                            print '<option value="'. $key .'" '. $ss .'>'. $val .'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        
        <div class="col-md-12">
            <label for="edit_fabric_name">Fabric Name <span class="text-danger">*</span></label>
            <div class="form-group">
                <select class="form-control custom-select2" name="edit_fabric_name" id="edit_fabric_name" style="width:100%" required>
                    <?= select_dropdown('fabric', array('id', 'fabric_name'), 'fabric_name ASC', $sql['fabric_name'], '', ''); ?>
                </select>
            </div>
        </div>
        
        <div class="col-md-12">
            <label for="edit_gsm">GSM <span class="text-danger">*</span></label>
            <div class="form-group">
                <input type="text" class="form-control" name="edit_gsm" id="edit_gsm" value="<?= $sql['gsm'] ? $sql['gsm'] : ''; ?>" placeholder="GSM" required>
            </div>
        </div>
        
        <div class="col-md-12">
            <label for="dying_color">Dying Color</label>
            <div class="form-group">
                <select class="form-control custom-select2" name="edit_dying_color" id="edit_dying_color" style="width:100%">
                    <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', $sql['dying_color'], '', ''); ?>
                </select>
            </div>
        </div>
        
        <div class="col-md-12">
            <label>AOP Name</label>
            <div class="form-group">
                <input type="text" class="form-control" name="edit_aop_name" id="edit_aop_name" value="<?= $sql['aop_name'] ? $sql['aop_name'] : ''; ?>" placeholder="AOP Name">
            </div>
        </div>
        
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Yarn Name</th>
                        <th>Mising %</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=0;
                        foreach(json_decode($sql['yarn_mixing']) as $exp) {
                            $exp = explode('=', $exp);
                            print '<tr id="tr11'. $i .'"><td><input type="hidden" name="edit_yarn_name[]" value="'. $exp[0] .'"><input type="hidden" name="edit_mixing_percentage[]" value="'. $exp[2] .'">'. mas_yarn_name($exp[0]) .'</td><td>'. $exp[2] .'</td><td><a class="border border-secondary rounded text-secondary" onclick="deleteRow(11'. $i .')"><i class="fa fa-trash"></i></a></td></tr>'; $i++;
                        }
                    ?>
                    <tr id="edit_tbody_tr">
                        <td>
                            <select class="form-control custom-select2" name="edit_yarn_name[]" id="edit_yarn_name" style="width:100%">
                                <?= select_dropdown('mas_yarn', array('id', 'yarn_name'), 'yarn_name ASC', '', '', ''); ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="edit_mixing_percentage[]" id="edit_mixing_percentage" class="form-control mw-200" placeholder="Mixing %">
                        </td>
                        <td>
                            <a class="border border-secondary rounded text-secondary" onclick="add_edit()"><i class="fa fa-plus"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    
    <?php

} else if (isset($_REQUEST['getbankedit'])) {

    $ass = "SELECT * FROM mas_bank WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);


    print '<div class="col-md-12">';
    print '<label> Bank Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_bank_name" id="edit_bank_name" placeholder="Bank Name" value="' . $asd['bank_name'] . '" required>';
    print '<input type="hidden" name="edit_bank_id" id="edit_bank_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Account No <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_account_no" id="edit_account_no" placeholder="Account No" value="' . $asd['account_no'] . '" required>';
    print '<input type="hidden" name="edit_bank_id" id="edit_bank_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Bank IFSC <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_bank_ifsc" id="edit_bank_ifsc" placeholder="Bank IFSC" value="' . $asd['bank_ifsc'] . '" required>';
    print '<input type="hidden" name="edit_bank_id" id="edit_bank_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label> Bank Branch <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_bank_branch" id="edit_bank_branch" placeholder="Bank Branch" value="' . $asd['bank_branch'] . '" required>';
    print '<input type="hidden" name="edit_bank_id" id="edit_bank_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Other <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control" name="edit_bank_other" id="edit_bank_other" placeholder="Other" value="' . $asd['bank_other'] . '" required>';
    print '<input type="hidden" name="edit_bank_id" id="edit_bank_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

} else if (isset($_REQUEST['getnoteedit'])) {

    $ass = "SELECT * FROM mas_notes WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Notes Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="form-control" name="edit_note_name" id="edit_note_name" required="" style="width:100%">';
        $ls = ($asd['note_name'] == 'Fabric') ? 'selected':'';
    print '<option value="Fabric" '. $ls .'>Fabric</option>';
        $ad = ($asd['note_name'] == 'Store') ? 'selected':'';
    print '<option value="Store" '. $ad .'>Store</option>';
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Invoice <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="form-control" name="edit_invoice_note" id="edit_invoice_note" required="" style="width:100%">';   
    $ls1 = ($asd['edit_invoice_note'] == 'DC') ? 'selected':'';
    print '<option value="DC" '. $ls1 .'>DC</option>';
        $ad1 = ($asd['edit_invoice_note'] == 'PO') ? 'selected':'';
    print '<option value="PO" '. $ad1 .'>PO</option>';
    print '</select>';
    print '</div>';
    print '</div>';
 

    print '<div class="col-md-12">';
    print '<label>Notes <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_note_name_multiple" id="edit_note_name_multiple" placeholder="Expense Name" value="' . $asd['note_name_multiple'] . '" required>';
    print '<input type="hidden" name="edit_note_id" id="edit_note_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

} else if (isset($_REQUEST['getexpenseMainedit'])) {

    $ass = "SELECT * FROM expense_main WHERE id='" . $_REQUEST['id'] . "'";
    $asss = mysqli_query($mysqli, $ass);
    $asd = mysqli_fetch_array($asss);

    print '<div class="col-md-12">';
    print '<label>Expense Name <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<input type="text" class="form-control d-cursor" name="edit_expense_name" id="edit_expense_name" placeholder="Expense Name" value="' . $asd['expense_name'] . '" required>';
    print '<input type="hidden" name="edit_expense_id" id="edit_expense_id" value="' . $asd['id'] . '">';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Type <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="form-control" name="edit_type_exp" id="edit_type_exp" required="">';
        $ls = ($asd['type_exp'] == 'Less') ? 'selected':'';
    print '<option value="Less" '. $ls .'>Less</option>';
        $ad = ($asd['type_exp'] == 'Add') ? 'selected':'';  
    print '<option value="Add" '. $ad .'>Add</option>';
    print '</select>';
    print '</div>';
    print '</div>';

    print '<div class="col-md-12">';
    print '<label>Type Value <span class="text-danger">*</span></label>';
    print '<div class="form-group">';
    print '<select class="form-control" name="edit_type_vp" id="edit_type_vp" required="">';   
    $ls1 = ($asd['type_vp'] == 'Value') ? 'selected':'';
    print '<option value="Value" '. $ls1 .'>Value</option>';
        $ad1 = ($asd['type_vp'] == 'Percentage') ? 'selected':'';
    print '<option value="Percentage" '. $ad1 .'>Percentage</option>';
    print '</select>';
    print '</div>';
    print '</div>';

} else if(isset($_REQUEST['total_pieces_toprint'])) {

    $dta = base64_decode($_REQUEST['data']);
    
    $hjh = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE id IN (". $dta .")"));
    
    $data['pcs'][] = $hjh['pcs_per_bundle'];
    
    echo json_encode($data);

} else if(isset($_REQUEST['get_processinglist'])) {
    
    $opp = "SELECT a.id, a.processing_code, a.order_id ";
    $opp .= " FROM processing_list a ";
    $opp .= " WHERE ";
    
    if($_REQUEST['type']=="" || $_REQUEST['value']=="") {
        
        $opp .= ' type="process_outward" AND ((input_type="Unit" AND is_inwarded IS NULL AND complete_inhouse="completed" ' . $wh .' ) OR (is_inwarded IS NULL AND input_type="Unit" AND created_unit='.$_SESSION['loginCompany'].')) ';
    } else if($_REQUEST['type']=='Unit') {
        if ($_SESSION['login_role'] != 1) {
            $wh = ' AND created_unit="' . $_SESSION['loginCompany'] . '"';
        } else {
            $wh = '';
        }
        $opp .= ' type="process_outward" AND input_type="Unit" AND assigned_emp = "'. $_REQUEST['value'] .'" AND is_inwarded IS NULL AND complete_inhouse="completed" ' . $wh .'';
        
    } else if($_REQUEST['type']=='Employee') {
        $opp .= ' type="process_outward" AND is_inwarded IS NULL AND input_type="Employee" AND assigned_emp = "'. $_REQUEST['value'] .'" AND created_unit='.$_SESSION['loginCompany'].'';
        
    } else if($_REQUEST['type']=='Supplier') {
        $opp .= ' type="process_outward" AND is_inwarded IS NULL AND input_type="Supplier" AND assigned_emp = "'. $_REQUEST['value'] .'" AND created_unit='.$_SESSION['loginCompany'].'';
    }
    
    $opp .= " ORDER BY id DESC";
        
    $qyyy = mysqli_query($mysqli, $opp);
    
    if(mysqli_num_rows($qyyy) > 0) {
        $data['option'][] = '<option value="">Select</option>';
        while($pss = mysqli_fetch_array($qyyy)) {
            $vss = ($sql['id'] == $pss['id']) ? 'selected' : '';
            $data['option'][] = '<option value="'. $pss['id'] .'" '. $vss .'>BO : '. sales_order_code($pss['order_id']) .' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DC : '. $pss['processing_code'] .'</option>';
        }
    } else {
        $data['option'][] = '';
    }
    echo json_encode($data);
    
} else if(isset($_REQUEST['input_type_for_si'])) {
    
    if($_REQUEST['input_type']=='Line') {
        
        $data['label'][] = 'Line';
        $data['option'][] = '<option value="">Select Line</option>';
        $data['option'][] = select_dropdown('mas_line', array('id', 'line_name'), 'line_name ASC', '', '', '`');
    } else if($_REQUEST['input_type']=='Employee') {
        
        $data['label'][] = 'Employee';
        $data['option'][] = '<option value="">Select Employee</option>';
        $data['option'][] = select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', '`');
    } else if($_REQUEST['input_type']=='Unit') {
        
        $data['label'][] = 'Unit';
        $data['option'][] = '<option value="">Select Unit</option>';
        $data['option'][] = select_dropdown('company', array('id', 'company_name'), 'company_name ASC', '', ' WHERE type = 2', '`');
    } else {
        $data['option'][] = '';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['partial_prod_planning_details'])) {
    
    // $qryy = "SELECT * ";
    // $qryy .= " FROM sales_order_detalis a ";
    // $qryy .= " WHERE id = '". $_REQUEST['style_id'] ."' ";
    
    // $query = mysqli_query($mysqli, $qryy);
    
    
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['add_new_combo'])) {
    
    $yui = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM unit WHERE id='" . $_REQUEST['unitt'] . "'"));
    
    $temp_combo = $_REQUEST['temp_combo'];
    
    $ck = "'compNm', 'Combo Name Already Selected!'";
    
    print '
        <div class="row" id="trid'. $temp_combo .'121" style="padding: 10px;border: 1px solid #d3cfcf;margin-top: 10px;">
        <div class="col-md-12 text-center"><h5>Combo '. $temp_combo .'</h5><br></div>
            <div class="col-md-6">
                <p class="text-left">Combo name : </p>
                <select class="form-control custom-select2 select22 compNm" onchange="duplicate_check('. $ck .')" name="combo_name'. $temp_combo .'" id="" style="width:100%">
                    '. select_dropdown('color', array('id', 'color_name'), 'color_name ASC', '', '', '') .'
                </select>
                <input type="hidden" name="combo_id[]" value="'. $temp_combo .'">
            </div>
            <div class="col-md-6">
                <p>Pack Type : <a class="btn btn-outline-danger" style="float:right;padding-top: 0px;" onclick="removeRow('. $temp_combo .'121)"><i class="fa fa-trash"></i></a><br></p>
                <select class="form-control custom-select2 select22 packNm" name="pack_name'. $temp_combo .'" id="" style="width:100%">
                    '. select_dropdown('mas_pack', array('id', 'pack_name'), 'pack_name ASC', '', '', '') .'
                </select>
            </div>
            <div class="col-md-12"><hr></div>
            
            <div class="col-md-6">
                <label>Part Name :</label>';
            
            for ($n = 0; $n < $yui['part_count']; $n++) {
                print '<div class="d-flex"><select class="form-control custom-select2 select22 partName dup_part'. $temp_combo .'" onchange="validate_part('. $temp_combo .')" name="part_name'. $temp_combo .'[]" id="" style="width:100%">'. select_dropdown('part', array('id', 'part_name'), 'part_name ASC', '', '', '') .'</select></div><br>';
            }
            
    print  '</div><div class="col-md-6">
                <label>Color Name :</label>';
            
            for ($n = 0; $n < $yui['part_count']; $n++) {
                print '<div class="d-flex"><select class="form-control custom-select2 select22 colorName" name="part_color'. $temp_combo .'[]" id="" style="width:100%">'. select_dropdown('color', array('id', 'color_name'), 'color_name ASC', '', '', '') .'</select></div><br>';
            }
            
    print  '</div><div class="col-md-12"><hr>
                <label class="">Size Range :</label>
                <select class="form-control custom-select2 select22 sizz_rang" name="variation_name'. $temp_combo .'" id="variation_name'. $temp_combo .'" onchange="v_name('. $temp_combo .')" style="width:100%">
                    '. select_dropdown('variation', array('id', 'variation_name'), 'variation_name ASC', '', 'WHERE is_active="active"', '') .'
                </select>
                <input type="hidden" name="size_range_id" id="size_range_id'. $temp_combo .'">
            </div><div class="col-md-12" style="overflow-y: auto;"><br>
                <table class="table">
                    <thead style="background-color: #d7d7d7;">
                        <tr>
                            <td colspan="2">Size</td>
                            <td>Qty</td>
                            <td>Excess %</td>
                            <td class="removeclone"> <i class="icon-copy fa fa-plus-circle" aria-hidden="true" onclick="addmoreQrydetail('. $temp_combo .')"></i>
                                <input type="hidden" id="new_sizeCount'. $temp_combo .'" value="'. ($temp_combo*200) .'">
                            </td>
                        </tr>
                    </thead>
                    <tbody id="getsizeRange'. $temp_combo .'"></tbody>
                </table>
            </div>
        </div>';
        
} else if(isset($_REQUEST['search_soc_size'])) {
    
    $id = $_REQUEST['id'];
    
    $parrt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sod_part WHERE id = '". $id ."'"));
    
    $kb = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = '". $parrt['sod_combo'] ."'");
    $data['option'][] = '<option value="">Select</option>';
    while($opt = mysqli_fetch_array($kb)) {
        
        $plan_qty = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(plan_qty) as plan_qty FROM line_planning WHERE sod_part = '". $id ."' AND sod_size = '". $opt['id'] ."'"));
        
        $qtyy = $opt['size_qty'] + round(($opt['size_qty'] / 100) * $opt['excess_per']);
        
        $data['option'][] = '<option value="'. $opt['id'] .'" data-qty="'. ($qtyy - $plan_qty['plan_qty']) .'">'. variation_value($opt['variation_value']) .'</option>';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['get_combo_details_for_style'])) {
    
    $id = $_REQUEST['id'];
    
    $kb = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sales_order_detail_id = '". $id ."'");
    $data['option'][] = '<option value="">Select</option>';
    while($opt = mysqli_fetch_array($kb)) {
        
        $data['option'][] = '<option value="'. $opt['id'] .'" >'. color_name($opt['combo_id']) .' | '. part_name($opt['part_id']) .' | '. color_name($opt['color_id']) .'</option>';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['get_bundle_for_swInput'])) {
    
    $id = $_REQUEST['id'];
    
    $kb = mysqli_query($mysqli, "SELECT variation_value, id, bundle_number FROM bundle_details WHERE sod_part = '". $id ."' AND in_sewing IS NULL AND boundle_qr IS NOT NULL");
    $data['option'][] = '<option value="all">Select All</option>';
    while($opt = mysqli_fetch_array($kb)) {
        
        $data['option'][] = '<option value="'. $opt['id'] .'" >'. variation_value($opt['variation_value']) .' - '. $opt['bundle_number'] .'</option>';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['get_bundle_for_pout'])) {
    
    $id = $_REQUEST['id'];
    
    $kb = mysqli_query($mysqli, "SELECT variation_value, id, bundle_number FROM bundle_details 
                                WHERE sod_part = '". $id ."' AND ((in_proseccing IS NULL AND complete_processing IS NULL) OR (in_proseccing = 'yes' AND complete_processing = 'yes')) AND boundle_qr IS NOT NULL ");
    $data['option'][] = '<option value="all">Select All</option>';
    while($opt = mysqli_fetch_array($kb)) {
        
        $data['option'][] = '<option value="'. $opt['id'] .'" >'. variation_value($opt['variation_value']) .' - '. $opt['bundle_number'] .'</option>';
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['get_temp_bundle'])) {
    
    $bundles = $_REQUEST['bundles'];
    $already_scanned = $_REQUEST['already_scanned'];
    
    $merge = array_unique(array_filter(array_merge(explode(',', $already_scanned), explode(',', $bundles))));
    
    $new_arr = array();
    foreach($merge as $bundle) {
        
        if($bundle!='all') {
            
            $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE id = ". $bundle));
            $qrr = $sql['id'];
            
            $data['tbody'][] = '<tr id="tbTr' . $qrr . '"><td> <input type="hidden" class="bundles" name="boundle_id[]" value="' . $sql['id'] . '"> ' . sales_order_code($sql['order_id']) . '</td>
                <td>' . cutting_entry_number($sql['cutting_barcode_id']) . '</td> <td>' . sales_order_style($sql['style_id']) . '</td> <td>' . color_name($sql['combo']) . '</td> <td>' . part_name($sql['part']) . '</td> <td>' . color_name($sql['color']) . '</td>
                <td>' . variation_value($sql['variation_value']) . '</td> <td>' . $sql['bundle_number'] . '</td> <td>' . $sql['pcs_per_bundle'] . '</td> <td>' . $sql['boundle_qr'] . '</td>
                <td><i class="icon-copy fa fa-trash-o" aria-hidden="true" onclick="removeRow(' . $qrr . ')" title="Remove"></i></td></tr>';
            
            array_push($new_arr, $bundle);
        }
    }
    
    $newarray = array_unique(array_filter(array_merge(explode(',', $already_scanned), $new_arr)));
    
    $data['bdl_count'][] = count($newarray);
    $data['old_array'][] = implode(',', $newarray);
        
    echo json_encode($data);
    
} else if(isset($_REQUEST['get_brand_wip'])) {
    
    $pp=1;
    $unit_plan_brand = $line_plan_brand = $cutting_brand = 0;
    
    $ytt = "SELECT a.id, a.delivery_date, (a.total_excess * c.part_count) as total_excess, a.sales_order_id, b.brand ";
    $ytt .= " FROM sales_order_detalis a ";
    $ytt .= " LEFT JOIN sales_order b ON a.sales_order_id = b.id ";
    $ytt .= " LEFT JOIN unit c ON a.unit_id = c.id ";
    $ytt .= " WHERE b.brand='". $_REQUEST['brand'] ."' AND b.is_approved = 'approved' AND a.is_dispatch IS NULL ";
    
    $innn = mysqli_query($mysqli, $ytt);
    $nmmm = mysqli_num_rows($innn);
    $data['count'][] = $nmmm;
    if($nmmm>0) {
    while($row = mysqli_fetch_array($innn)) {
        
        $ppf = mysqli_query($mysqli, "SELECT a.*, b.total_excess FROM process_planing a LEFT JOIN sales_order_detalis b ON a.style_id = b.id WHERE a.style_id = '". $row['id'] ."' AND a.process_id = 1");
        $unit_plan_brand = $line_plan_brand = $cutting_brand = 0;
        while($fet = mysqli_fetch_array($ppf)) {
            if($fet['plan_type']=='Partial') {
                
                $ptl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(combo_part_qty) as combo_part_qty FROM cutting_partial_planning WHERE process_planing_id = '". $fet['id']. "'"));
                $unit_plan_brand += $ptl['combo_part_qty'];
            } else {
                $unit_plan_brand += $fet['total_excess'];
            }
        }
        
        
        $ck = mysqli_query($mysqli, "SELECT id, plan_qty, planning_type FROM line_planning WHERE style_id = '". $row['id'] ."'");
        while($line = mysqli_fetch_array($ck)) {
            if($line['planning_type'] == 'Full') {
                $line_plan_brand += $line['plan_qty'];
            } else {
                $ck1 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(order_qty) as order_qty FROM line_planning_size WHERE line_planning_id =  '". $line['id'] ."'"));
                $line_plan_brand += $ck1['order_qty'];
            }
        }
        
        $ctt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE style_id = '". $row['id'] ."'"));
        $cutting_brand += $ctt['pcs_per_bundle'];
        
        $unit_plan_brand = $unit_plan_brand ? $unit_plan_brand : 0;
        $line_plan_brand = $line_plan_brand ? $line_plan_brand : 0;
        $cutting_brand = $cutting_brand ? $cutting_brand : 0;
        
        $data['html'][] = '<tr>
                                <td>'. sales_order_code($row['sales_order_id']) .'</td>
                                <td>'. sales_order_style($row['id']) .'</td>
                                <td>'. date('d-m-Y', strtotime($row['delivery_date'])) .'</td>
                                <td>'. number_format($row['total_excess']) .'</td>
                                <td>'. $unit_plan_brand .'</td>
                                <td>'. $line_plan_brand .'</td>
                                <td>'. $cutting_brand .'</td>
                            </tr>';
    $pp++; } }
    echo json_encode($data);
    
} else if(isset($_REQUEST['get_unit_wip'])) {
    
    $pp=1;
    $unit_plan_unit = $line_plan_unit = $cutting_unit = 0;
    
    $ytt = "SELECT a.id, a.delivery_date, (a.total_excess * c.part_count) as total_excess, a.sales_order_id, b.brand, b.created_unit ";
    $ytt .= " FROM sales_order_detalis a ";
    $ytt .= " LEFT JOIN sales_order b ON a.sales_order_id = b.id ";
    $ytt .= " LEFT JOIN unit c ON a.unit_id = c.id ";
    $ytt .= " WHERE b.created_unit='". $_REQUEST['unit'] ."' AND b.is_approved = 'approved' AND a.is_dispatch IS NULL ";
    
    $innn = mysqli_query($mysqli, $ytt);
    $nmmm = mysqli_num_rows($innn);
    $data['count'][] = $nmmm;
    if($nmmm>0) {
    while($row = mysqli_fetch_array($innn)) {
        
        $ppf = mysqli_query($mysqli, "SELECT a.*, b.total_excess FROM process_planing a LEFT JOIN sales_order_detalis b ON a.style_id = b.id WHERE a.style_id = '". $row['id'] ."' AND a.process_id = 1");
        $unit_plan_unit = $line_plan_unit = $cutting_unit = 0;
        while($fet = mysqli_fetch_array($ppf)) {
            if($fet['plan_type']=='Partial') {
                
                $ptl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(combo_part_qty) as combo_part_qty FROM cutting_partial_planning WHERE process_planing_id = '". $fet['id']. "'"));
                $unit_plan_unit += $ptl['combo_part_qty'];
            } else {
                $unit_plan_unit += $fet['total_excess'];
            }
        }
        
        
        $ck = mysqli_query($mysqli, "SELECT id, plan_qty, planning_type FROM line_planning WHERE style_id = '". $row['id'] ."'");
        while($line = mysqli_fetch_array($ck)) {
            if($line['planning_type'] == 'Full') {
                $line_plan_unit += $line['plan_qty'];
            } else {
                $ck1 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(order_qty) as order_qty FROM line_planning_size WHERE line_planning_id =  '". $line['id'] ."'"));
                $line_plan_unit += $ck1['order_qty'];
            }
        }
        
        $ctt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE style_id = '". $row['id'] ."'"));
        $cutting_unit += $ctt['pcs_per_bundle'];
        
        $unit_plan_unit = $unit_plan_unit ? $unit_plan_unit : 0;
        $line_plan_unit = $line_plan_unit ? $line_plan_unit : 0;
        $cutting_unit = $cutting_unit ? $cutting_unit : 0;
        
        $data['html'][] = '<tr>
                                <td>'. brand_name($row['brand']) .'</td>
                                <td>'. sales_order_code($row['sales_order_id']) .'</td>
                                <td>'. sales_order_style($row['id']) .'</td>
                                <td>'. date('d-m-Y', strtotime($row['delivery_date'])) .'</td>
                                <td>'. number_format($row['total_excess']) .'</td>
                                <td>'. $unit_plan_unit .'</td>
                                <td>'. $line_plan_unit .'</td>
                                <td>'. $cutting_unit .'</td>
                            </tr>';
    $pp++; } }
    echo json_encode($data);

} else if(isset($_REQUEST['get_daily_prodiction_status'])) {
    
    $filter_date = $_REQUEST['filter_date'];

    $ctng = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE date = '". $filter_date ."'"));
    $sw_InT = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE in_sewing='yes' AND in_sewing_date LIKE '%" . $filter_date . "%'"));
    $sw_TOT = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_sewingout WHERE date = '" . $filter_date . "'"));
    $printng = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_component_process WHERE process = 6 AND date = '" . $filter_date . "'"));
    $c_good = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_checking WHERE mode = 'PASSED' AND date = '" . $filter_date . "'"));
    $c_rwk = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_checking WHERE mode = 'REWORK' AND date = '" . $filter_date . "'"));
    $c_rejtk = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_checking WHERE mode = 'FAILED' AND date = '" . $filter_date . "'"));
    
    $gg = $c_good['scanned_count'] ? $c_good['scanned_count'] : 0;
    $rw = $c_rwk['scanned_count'] ? $c_rwk['scanned_count'] : 0;
    $rj = $c_rejtk['scanned_count'] ? $c_rejtk['scanned_count'] : 0;
    
    $chng = '<span class="text-success" style="font-size:15px;">Good&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - '. $gg .'</span><br><span class="text-warning" style="font-size:15px;">Rework&nbsp;&nbsp;&nbsp;&nbsp; - '. $rw .'</span><br><span class="text-danger" style="font-size:15px;">Rejection - '. $rj .'</span>';
    $data['cutting_out'][] = $ctng['pcs_per_bundle'] ? $ctng['pcs_per_bundle'] : 0;
    $data['sewing_inp'][] = $sw_InT['pcs_per_bundle'] ? $sw_InT['pcs_per_bundle'] : 0;
    $data['sewing_out'][] = $sw_TOT['scanned_count'] ? $sw_TOT['scanned_count'] : 0;
    $data['printing_out'][] = $printng['scanned_count'] ? $printng['scanned_count'] : 0;
    $data['checking_out'][] = $chng;
    
    echo json_encode($data); 

} else if(isset($_REQUEST['get_daily_prodiction_status_details'])) {

    $type = $_REQUEST['type'];
    $filter_date = $_REQUEST['filter_date'];
    
    if($type == 'cutting') {

        $cutting_based = $_POST['cutting_based'];

        if($cutting_based == 'order') {

            $data['thead'][] = '<tr><th>BO NO</th><th>Style</th><th>Style Image</th><th>Combo</th><th>Part</th><th>Color</th><th>Unit</th><th>Order Qty</th><th>Today Cutting Qty</th><th>Total Cutting Qty</th><th>Cutting Percentage</th></tr>';

            $fetch = mysqli_query($mysqli, "SELECT * FROM cutting_barcode WHERE created_date LIKE '%". $filter_date ."%' GROUP BY style");
            if(mysqli_num_rows($fetch)>0) {
                while($row = mysqli_fetch_array($fetch)) {
                    
                    $ord_q = mysqli_fetch_array(mysqli_query($mysqli, "SELECT total_excess, item_image FROM sales_order_detalis WHERE id = ". $row['style']));
                    $tdy_ctng = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE date = '". $filter_date ."' AND sod_part = ". $row['sod_part']));
                    $tot_ctng = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE sod_part = ". $row['sod_part']));
                    $data['tbody'][] = '
                        <tr>
                            <td>'. sales_order_code($row['order_id']) .'</td>
                            <td>'. sales_order_style($row['style']) .'</td>
                            <td>'. viewImage($ord_q['item_image'], 30) .'</td>
                            <td>'. color_name($row['combo_id']) .'</td>
                            <td>'. part_name($row['part_id']) .'</td>
                            <td>'. color_name($row['color_id']) .'</td>
                            <td>'. company_code($row['created_unit']) .'</td>
                            <td>'. $ord_q['total_excess'] .'</td>
                            <td>'. $tdy_ctng['pcs_per_bundle'] .'</td>
                            <td>'. $tot_ctng['pcs_per_bundle'] .'</td>
                            <td>'. round(($tot_ctng['pcs_per_bundle']/($ord_q['total_excess'] ? $ord_q['total_excess'] : 1))*100) .'%</td>
                        </tr>
                    ';
                }
            } else {
                $data['tbody'][] = '<tr><td class="text-center" colspan="11">-- No Data Found --</td></tr>';
            }
        } else if($cutting_based == 'unit') {
            $data['thead'][] = '<tr><td>Unit</td><td>Cutting Qty</td><td>Detail</td></tr>';
            
            $fetch = mysqli_query($mysqli, "SELECT created_unit FROM cutting_barcode WHERE created_date LIKE '%". $filter_date ."%' GROUP BY created_unit");
            if(mysqli_num_rows($fetch)>0) {
                while($row = mysqli_fetch_array($fetch)) {

                    $thead = '<table class="table table-bordered"><tr><th>BO</th><th>Style</th><th>Style Image</th><th>Combo</th><th>Part</th><th>Color</th><th>Cutting Qty</th></tr>';
                    $tbody = '';
                    $fetch_new = mysqli_query($mysqli, "SELECT * FROM cutting_barcode WHERE created_date LIKE '%". $filter_date ."%'  AND created_unit = '". $row['created_unit'] ."' GROUP BY sod_part");
                    while($row_new = mysqli_fetch_array($fetch_new)) {
                        $tdy__ = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE date = '". $filter_date ."' AND sod_part = ". $row_new['sod_part']));
                        $ord_q__ = mysqli_fetch_array(mysqli_query($mysqli, "SELECT total_excess, item_image FROM sales_order_detalis WHERE id = ". $row_new['style']));
                        $tbody .= '<tr>
                                    <td>'. sales_order_code($row_new['order_id']) .'</td>
                                    <td>'. sales_order_style($row_new['style']) .'</td>
                                    <td>'. viewImage($ord_q__['item_image'], 30) .'</td>
                                    <td>'. color_name($row_new['combo_id']) .'</td>
                                    <td>'. part_name($row_new['part_id']) .'</td>
                                    <td>'. color_name($row_new['color_id']) .'</td>
                                    <td>'. $tdy__['pcs_per_bundle'] .'</td>
                                </tr>';
                    }
                    $tfoot = '</table>';
                    $tdy_ctng = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE date = '". $filter_date ."' AND created_unit = ". $row['created_unit']));
                    $data['tbody'][] = '
                        <tr>
                            <td>'. company_code($row['created_unit']) .'</td>
                            <td>'. $tdy_ctng['pcs_per_bundle'] .'</td>
                            <td>'. $thead.$tbody.$tfoot .'</td>
                        </tr>
                    ';
                }
            } else {
                $data['tbody'][] = '<tr><td class="text-center" colspan="11">-- No Data Found --</td></tr>';
            }
        }
        
    } else if($type == 'printing') {
        $fetch = mysqli_query($mysqli, "SELECT * FROM orbidx_component_process WHERE process = 6 AND date = '". $filter_date ."' GROUP BY sod_part");
        if(mysqli_num_rows($fetch)>0) {
            while($row = mysqli_fetch_array($fetch)) {
                
                $ord_q = mysqli_fetch_array(mysqli_query($mysqli, "SELECT total_excess, item_image FROM sales_order_detalis WHERE id = ". $row['style_id']));
                $tdy = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_component_process WHERE process = 6 AND date = '". $filter_date ."' AND sod_part = ". $row['sod_part']));
                $tot = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_component_process WHERE process = 6 AND sod_part = ". $row['sod_part']));
                
                $data['tbody'][] = '
                    <tr>
                        <td>'. sales_order_code($row['order_id']) .'</td>
                        <td>'. sales_order_style($row['style_id']) .'</td>
                        <td>'. viewImage($ord_q['item_image'], 30) .'</td>
                        <td>'. color_name($row['combo']) .'</td>
                        <td>'. part_name($row['part']) .'</td>
                        <td>'. color_name($row['color']) .'</td>
                        <td>'. $ord_q['total_excess'] .'</td>
                        <td>'. $tdy['scanned_count'] .'</td>
                        <td>'. $tot['scanned_count'] .'</td>
                        <td>'. round(($tot['scanned_count']/($ord_q['total_excess'] ? $ord_q['total_excess'] : 1))*100) .'%</td>
                    </tr>
                ';
            }
        } else {
            $data['tbody'][] = '<tr><td class="text-center" colspan="11">-- No Data Found --</td></tr>';
        }
        
    } else if($type == 'sewingInp') {
        $fetch = mysqli_query($mysqli, "SELECT *, sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE in_sewing = 'yes' AND in_sewing_date = '". $filter_date ."' GROUP BY sod_part");
        if(mysqli_num_rows($fetch)>0) {
            while($row = mysqli_fetch_array($fetch)) {
                
                $ord_q = mysqli_fetch_array(mysqli_query($mysqli, "SELECT total_excess, item_image FROM sales_order_detalis WHERE id = ". $row['style_id']));
                $tot_ctng = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE sod_part = ". $row['sod_part']));
                $tot_swinp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE in_sewing = 'yes' AND sod_part = ". $row['sod_part']));
                
                $data['tbody'][] = '
                    <tr>
                        <td>'. sales_order_code($row['order_id']) .'</td>
                        <td>'. sales_order_style($row['style_id']) .'</td>
                        <td>'. viewImage($ord_q['item_image'], 30) .'</td>
                        <td>'. color_name($row['combo']) .'</td>
                        <td>'. part_name($row['part']) .'</td>
                        <td>'. color_name($row['color']) .'</td>
                        <td>'. $ord_q['total_excess'] .'</td>
                        <td>'. $tot_ctng['pcs_per_bundle'] .'</td>
                        <td>'. $row['pcs_per_bundle'] .'</td>
                        <td>'. $tot_swinp['pcs_per_bundle'] .'</td>
                        <td>'. round(($tot_swinp['pcs_per_bundle']/($ord_q['total_excess'] ? $ord_q['total_excess'] : 1))*100) .'%</td>
                        <td>'. round(($tot_ctng['pcs_per_bundle']/($ord_q['total_excess'] ? $ord_q['total_excess'] : 1))*100) .'%</td>
                    </tr>
                ';
            }
        } else {
            $data['tbody'][] = '<tr><td class="text-center" colspan="11">-- No Data Found --</td></tr>';
        }
        
    } else if($type == 'sewingOut') {
        $fetch = mysqli_query($mysqli, "SELECT *, sum(scanned_count) as scanned_count FROM orbidx_sewingout WHERE date = '". $filter_date ."' GROUP BY sod_part");
        if(mysqli_num_rows($fetch)>0) {
            while($row = mysqli_fetch_array($fetch)) {
                
                $ord_q = mysqli_fetch_array(mysqli_query($mysqli, "SELECT total_qty, total_excess, item_image FROM sales_order_detalis WHERE id = ". $row['style_id']));
                $tot = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_sewingout WHERE sod_part = ". $row['sod_part']));
                
                $data['tbody'][] = '
                    <tr>
                        <td>'. sales_order_code($row['order_id']) .'</td>
                        <td>'. sales_order_style($row['style_id']) .'</td>
                        <td>'. viewImage($ord_q['item_image'], 30) .'</td>
                        <td>'. color_name($row['combo']) .' | '. part_name($row['part']) .'</td>
                        <td>'. color_name($row['color']) .'</td>
                        <td>'. $ord_q['total_qty'] .'</td>
                        <td>'. $ord_q['total_excess'] .'</td>
                        <td>'. date('d-m-Y', strtotime($row['date'])) .'</td>
                        <td>'. company_code($row['logUnit']) .'</td>
                        <td>'. $row['scanned_count'] .'</td>
                        <td>'. $tot['scanned_count'] .'</td>
                        <td>'. round(($tot['scanned_count']/($ord_q['total_excess'] ? $ord_q['total_excess'] : 1))*100) .'%</td>
                    </tr>
                ';
            }
        } else {
            $data['tbody'][] = '<tr><td class="text-center" colspan="12">-- No Data Found --</td></tr>';
        }
        
        
    } else if($type == 'checking') {
        
    // this is core query
    //     $fetch = mysqli_query($mysqli, "SELECT *, sum(scanned_count) as scanned_count FROM orbidx_checking WHERE date = '". $filter_date ."' GROUP BY sod_part");
    //     if(mysqli_num_rows($fetch)>0) {
    //         while($row = mysqli_fetch_array($fetch)) {
                
    //             $ord_q = mysqli_fetch_array(mysqli_query($mysqli, "SELECT total_excess, item_image FROM sales_order_detalis WHERE id = ". $row['style_id']));
    //             $tot = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_sewingout WHERE sod_part = ". $row['sod_part']));
                
    //             $tdy_good = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_checking WHERE mode = 'PASSED' AND sod_part = '". $row['sod_part'] ."' AND  date = '". $filter_date ."'"));
    //             $tot_good = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_checking WHERE mode = 'PASSED' AND sod_part = '". $row['sod_part'] ."'"));
                
    //             $tdy_rew = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_checking WHERE mode = 'REWORK' AND sod_part = '". $row['sod_part'] ."' AND  date = '". $filter_date ."'"));
    //             $tot_rew = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_checking WHERE mode = 'REWORK' AND sod_part = '". $row['sod_part'] ."'"));
                
    //             $tdy_rej = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_checking WHERE mode = 'FAILED' AND sod_part = '". $row['sod_part'] ."' AND  date = '". $filter_date ."'"));
    //             $tot_rej = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_checking WHERE mode = 'FAILED' AND sod_part = '". $row['sod_part'] ."'"));
                
    //             $tot_chk = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_checking WHERE sod_part = '". $row['sod_part'] ."'"));
                
    //             $data['tbody'][] = '
    //                 <tr>
    //                     <td>'. sales_order_code($row['order_id']) .' </td>
    //                     <td>'. sales_order_style($row['style_id']) .'</td>
    //                     <td>'. viewImage($ord_q['item_image'], 30) .'</td>
    //                     <td>'. color_name($row['combo']) .'</td>
    //                     <td>'. part_name($row['part']) .'</td>
    //                     <td>'. color_name($row['color']) .'</td>
    //                     <td>'. $ord_q['total_excess'] .'</td>
    //                     <td>'. $row['scanned_count'] .'</td>
    //                     <td>'. $tot['scanned_count'] .'</td>
    //                     <td>'. $tdy_good['scanned_count'] .'</td>
    //                     <td>'. $tot_good['scanned_count'] .'</td>
    //                     <td>'. $tdy_rew['scanned_count'] .'</td>
    //                     <td>'. $tot_rew['scanned_count'] .'</td>
    //                     <td>'. $tdy_rej['scanned_count'] .'</td>
    //                     <td>'. $tot_rej['scanned_count'] .'</td>
    //                     <td>'. $row['scanned_count'] .'</td>
    //                     <td>'. $tot_chk['scanned_count'] .'</td>
    //                 </tr>
    //             ';
    //         }
    //     } else {
    //         $data['tbody'][] = '<tr><td class="text-center" colspan="11">-- No Data Found --</td></tr>';
    //     }
    
    
        $fetch = mysqli_query($mysqli, "SELECT *, SUM(scanned_count) as scanned_count FROM orbidx_checking WHERE date = '". $filter_date ."' GROUP BY sod_part, logUnit");
        if(mysqli_num_rows($fetch) > 0) {
            while($row = mysqli_fetch_array($fetch)) {
                // Fetching all necessary data in one query
                $query = "SELECT total_excess, item_image FROM sales_order_detalis WHERE id = ". $row['style_id'] .";
                          SELECT SUM(scanned_count) as sew_scanned_count FROM orbidx_sewingout WHERE sod_part = ". $row['sod_part'] .";
                          SELECT SUM(scanned_count) as tdy_good FROM orbidx_checking WHERE mode = 'PASSED' AND sod_part = '". $row['sod_part'] ."' AND date = '". $filter_date ."';
                          SELECT SUM(scanned_count) as tot_good FROM orbidx_checking WHERE mode = 'PASSED' AND sod_part = '". $row['sod_part'] ."';
                          SELECT SUM(scanned_count) as tdy_rew FROM orbidx_checking WHERE mode = 'REWORK' AND sod_part = '". $row['sod_part'] ."' AND date = '". $filter_date ."';
                          SELECT SUM(scanned_count) as tot_rew FROM orbidx_checking WHERE mode = 'REWORK' AND sod_part = '". $row['sod_part'] ."';
                          SELECT SUM(scanned_count) as tdy_rej FROM orbidx_checking WHERE mode = 'FAILED' AND sod_part = '". $row['sod_part'] ."' AND date = '". $filter_date ."';
                          SELECT SUM(scanned_count) as tot_rej FROM orbidx_checking WHERE mode = 'FAILED' AND sod_part = '". $row['sod_part'] ."';
                          SELECT SUM(scanned_count) as tot_chk FROM orbidx_checking WHERE sod_part = '". $row['sod_part'] ."';";
                        
                if (mysqli_multi_query($mysqli, $query)) {
                    do {
                        if ($result = mysqli_store_result($mysqli)) {
                            $row_data = mysqli_fetch_assoc($result);
                            
                            // Gather all fetched data into one array
                            $fetched_data[] = $row_data;
                            
                            mysqli_free_result($result);
                        }
                    } while (mysqli_next_result($mysqli));
                }
                    
                // Construct HTML row
                $data['tbody'][] = '
                    <tr>
                        <td>'. sales_order_code($row['order_id']) .'</td>
                        <td>'. sales_order_style($row['style_id']) .'</td>
                        <td>'. viewImage($fetched_data[0]['item_image'], 30) .'</td>
                        <td>'. color_name($row['combo']) .'</td>
                        <td>'. part_name($row['part']) .'</td>
                        <td>'. color_name($row['color']) .'</td>
                        <td>'. $fetched_data[0]['total_excess'] .'</td>
                        <td>'. company_code($row['logUnit']) .'</td>
                        <td class="d-none">'. $row['scanned_count'] .'</td>
                        <td class="d-none">'. $fetched_data[1]['sew_scanned_count'] .'</td>
                        <td>'. $fetched_data[2]['tdy_good'] .'</td>
                        <td>'. $fetched_data[3]['tot_good'] .'</td>
                        <td>'. $fetched_data[4]['tdy_rew'] .'</td>
                        <td>'. $fetched_data[5]['tot_rew'] .'</td>
                        <td>'. $fetched_data[6]['tdy_rej'] .'</td>
                        <td>'. $fetched_data[7]['tot_rej'] .'</td>
                        <td>'. $row['scanned_count'] .'</td>
                        <td>'. $fetched_data[8]['tot_chk'] .'</td>
                    </tr>
                ';
            }
        } else {
            $data['tbody'][] = '<tr><td class="text-center" colspan="17">-- No Data Found --</td></tr>';
        }
    }
    
    echo json_encode($data);
    
} else if(isset($_REQUEST['get_emp_dash_details'])) {
    
    // $emp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT department, employee_code FROM employee_detail WHERE id = '". $logUser ."'"));
    
    // // 5 sewing out department
    // if($emp['department']==5) {
    //     $qy = "SELECT SUM(CASE WHEN a.planning_type = 'Full' THEN a.plan_qty ELSE b.plan_qty END) AS plan_qty ";
    //     $qy .= " FROM line_planning a ";
    //     $qy .= " LEFT JOIN line_planning_size b ON b.line_planning_id = a.id ";
    //     $qy .= " LEFT JOIN sales_order_detalis c ON a.style_id = c.id ";
    //     $qy .= " WHERE c.is_dispatch IS NULL AND (";
    //     $qy .= " (a.planning_type = 'Full' AND a.assign_type = 'employee' AND a.assign_to = '". $logUser ."') ";
    //     $qy .= " OR ";
    //     $qy .= " (a.planning_type = 'Partial' AND b.assign_type = 'employee' AND b.assign_to = '". $logUser ."') ";
    //     $qy .= " )";
    //     $plan_qty = mysqli_fetch_array(mysqli_query($mysqli, $qy));
        
    //     $sw_out = mysqli_fetch_array(mysqli_query($mysqli, "SELECT SUM(scanned_count) as scanned_count FROM orbidx_sewingout WHERE device_user = '". $emp['employee_code'] ."' and date = '". date('Y-m-d') ."'"));
    //     $sw_out_tot = mysqli_fetch_array(mysqli_query($mysqli, "SELECT SUM(scanned_count) as scanned_count FROM orbidx_sewingout WHERE device_user = '". $emp['employee_code'] ."'"));
        
    //     $sin = "SELECT sum(b.pcs_per_bundle) as pcs_per_bundle ";
    //     $sin .= " FROM processing_list a ";
    //     $sin .= " LEFT JOIN bundle_details b ON FIND_IN_SET(b.id, a.boundle_id) ";
    //     $sin .= " WHERE a.input_type = 'Employee' AND a.assigned_emp = '". $logUser ."' ";
    //     $sw_inp = mysqli_fetch_array(mysqli_query($mysqli, $sin));
        
    //     $resp['plan_qty'][] = $plan_qty['plan_qty'] ? $plan_qty['plan_qty'] : 0;
    //     $resp['output_today'][] = $sw_out['scanned_count'] ? $sw_out['scanned_count'] : 0;
    //     $resp['tot_plan_qty'][] = $plan_qty['plan_qty'] ? $plan_qty['plan_qty'] : 0;
    //     $resp['sw_out_tot'][] = $sw_out_tot['scanned_count'] ? $sw_out_tot['scanned_count'] : 0;
    //     $resp['sw_inp'][] = $sw_inp['pcs_per_bundle'] ? $sw_inp['pcs_per_bundle'] : 0;
    //     $resp['out_balance'][] = ($sw_inp['pcs_per_bundle'] ? $sw_inp['pcs_per_bundle'] : 0) - ($sw_out_tot['scanned_count'] ? $sw_out_tot['scanned_count'] : 0);
    // }
    
    // echo json_encode($resp);
    
    $line_ids = array();
    $lines = mysqli_query($mysqli, "SELECT id FROM mas_line WHERE FIND_IN_SET(". $logUser .", cost_generator)");
    while($res = mysqli_fetch_array($lines)) {
        $line_ids[] = $res['id'];
    }
    
    $line_ids = $line_ids ? implode(',', $line_ids) : '';

$emp_query = mysqli_query($mysqli, "SELECT department, employee_code FROM employee_detail WHERE id = '". $logUser ."'");
$emp = mysqli_fetch_array($emp_query);

    if ($emp['department'] == 5) {
        $plan_qty_query = mysqli_query($mysqli, "
            SELECT 
                SUM(CASE WHEN a.planning_type = 'Full' THEN a.plan_qty ELSE b.plan_qty END) AS plan_qty 
            FROM 
                line_planning a 
            LEFT JOIN 
                line_planning_size b ON b.line_planning_id = a.id 
            LEFT JOIN 
                sales_order_detalis c ON a.style_id = c.id 
            WHERE 
                c.is_dispatch IS NULL AND (
                    (a.planning_type = 'Full' AND a.assign_type = 'line' AND a.assign_to IN (". $line_ids .")) OR 
                    (a.planning_type = 'Partial' AND b.assign_type = 'line' AND b.assign_to IN (". $line_ids ."))
                )
        ");
        
        $plan_qty_row = mysqli_fetch_array($plan_qty_query);
        $plan_qty = $plan_qty_row['plan_qty'] ? $plan_qty_row['plan_qty'] : 0;
        
        $sw_out_query = mysqli_query($mysqli, "SELECT SUM(scanned_count) as scanned_count FROM orbidx_sewingout WHERE line IN (". $line_ids .") and date = '". date('Y-m-d') ."'");
        $sw_out_row = mysqli_fetch_array($sw_out_query);
        $out_qty = $sw_out_row['scanned_count'] ? $sw_out_row['scanned_count'] : 0;
        
        $sw_out_tot_query = mysqli_query($mysqli, "SELECT SUM(scanned_count) as scanned_count FROM orbidx_sewingout WHERE line IN (". $line_ids .")");
        $sw_out_tot_row = mysqli_fetch_array($sw_out_tot_query);
        $out_qty_tot = $sw_out_tot_row['scanned_count'] ? $sw_out_tot_row['scanned_count'] : 0;
        
        $sin_query = mysqli_query($mysqli, "
            SELECT 
                SUM(b.pcs_per_bundle) as pcs_per_bundle 
            FROM 
                processing_list a 
            LEFT JOIN 
                bundle_details b ON FIND_IN_SET(b.id, a.boundle_id) 
            WHERE 
                a.input_type = 'Employee' AND a.assigned_emp = '". $logUser ."'
        ");
        $sw_inp_row = mysqli_fetch_array($sin_query);
        $tot_Inp = $sw_inp_row['pcs_per_bundle'] ? $sw_inp_row['pcs_per_bundle'] : 0;
        $tott_bal = ($sw_inp ? $sw_inp : 0) - ($sw_out_tot ? $sw_out_tot : 0);
    
    // 1 cutting department
    } else if ($emp['department'] == 1) {
        
        $plan_qty_query = mysqli_query($mysqli, "
            SELECT 
                SUM(CASE WHEN a.plan_type = 'Full' THEN c.total_excess ELSE b.combo_part_qty END) AS plan_qty 
            FROM 
                process_planing a 
            LEFT JOIN 
                cutting_partial_planning b ON b.process_planing_id = a.id 
            LEFT JOIN 
                sales_order_detalis c ON a.style_id = c.id 
            WHERE 
                c.is_dispatch IS NULL AND (
                    (a.plan_type = 'Full' AND a.process_type = 'unit' AND a.processing_unit_id = '". $logUnit ."') OR 
                    (a.plan_type != 'Full' AND b.plan_for = 'Unit' AND b.plan_for_to = '". $logUnit ."')
                )
        ");
        $plan_qty_row = mysqli_fetch_array($plan_qty_query);
        
        $tdy_out = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN sales_order_detalis b ON a.style_id = b.id WHERE b.is_dispatch IS NULL AND a.created_unit = '". $logUnit ."' AND a.date = '". date('Y-m-d') ."' "));
        $tot_out = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN sales_order_detalis b ON a.style_id = b.id WHERE b.is_dispatch IS NULL AND a.created_unit = '". $logUnit ."' "));
        
        $plan_qty = $plan_qty_row['plan_qty'] ? $plan_qty_row['plan_qty'] : 0;
        $out_qty = $tdy_out['pcs_per_bundle'] ? $tdy_out['pcs_per_bundle'] : 0;
        $out_qty_tot = $tot_out['pcs_per_bundle'] ? $tot_out['pcs_per_bundle'] : 0;
        $tot_Inp = 0;
        $tott_bal = $plan_qty-$out_qty_tot;
        
        // 2 COMPONENT PROCESS
    } else if ($emp['department'] == 2) {
        
        
        $out_row = mysqli_fetch_array(mysqli_query($mysqli, "SELECT SUM(scanned_count) as scanned_count FROM orbidx_component_process WHERE line IN (". $line_ids .") and date = '". date('Y-m-d') ."'"));
        
        $out_tot_row = mysqli_fetch_array(mysqli_query($mysqli, "SELECT SUM(scanned_count) as scanned_count FROM orbidx_component_process WHERE line IN (". $line_ids .")"));
        
        
        $plan_qty = 0;
        $out_qty = $out_row['scanned_count'] ? $out_row['scanned_count'] : 0;
        $out_qty_tot = $out_tot_row['scanned_count'] ? $out_tot_row['scanned_count'] : 0;
        $tot_Inp = 0;
        $tott_bal = 0;
        
        
        // 3 GARMENT PROCESS
    } else if ($emp['department'] == 3) {
        
        
        $out_row = mysqli_fetch_array(mysqli_query($mysqli, "SELECT SUM(scanned_count) as scanned_count FROM orbidx_garment_process WHERE line IN (". $line_ids .") and date = '". date('Y-m-d') ."'"));
        
        $out_tot_row = mysqli_fetch_array(mysqli_query($mysqli, "SELECT SUM(scanned_count) as scanned_count FROM orbidx_garment_process WHERE line IN (". $line_ids .")"));
        
        
        $plan_qty = 0;
        $out_qty = $out_row['scanned_count'] ? $out_row['scanned_count'] : 0;
        $out_qty_tot = $out_tot_row['scanned_count'] ? $out_tot_row['scanned_count'] : 0;
        $tot_Inp = 0;
        $tott_bal = 0;
        
    } else {
        
        $plan_qty = 0;
        $out_qty = 0;
        $out_qty_tot = 0;
        $tot_Inp = 0;
        $tott_bal = 0;
    }
    
    $resp = [
        'plan_qty' => $plan_qty,
        'output_today' => $out_qty,
        'tot_plan_qty' => $plan_qty,
        'sw_out_tot' => $out_qty_tot,
        'sw_inp' => $tot_Inp,
        'out_balance' => $tott_bal,
    ];

    echo json_encode($resp);


} else if(isset($_REQUEST['fetch_prod_bill_passing'])) {
    
    $cg_id = $_REQUEST['bill_number'];
    
    $qry = "SELECT a.*, c.id as cid, c.total_qty, f.department ";
    $qry .= " FROM cost_generation_det a";
    $qry .= " LEFT JOIN sales_order_detalis c ON a.style = c.id";
    $qry .= " LEFT JOIN process f ON a.process = f.id";
    $qry .= " WHERE a.cost_generation_id = '". $cg_id ."'";
    
    $hbv = mysqli_query($mysqli, $qry);
    
    while($row = mysqli_fetch_array($hbv)) {
            
        $prod_qty = 0;
        
        $already = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.bill_qty) as bill_qty FROM cost_generation_det a 
                    WHERE a.sod_part = '". $row['sod_part'] ."' AND a.process = '". $row['process'] ."' AND a.id != ". $row['id']));
?>
    <tr>
        <td><input type="hidden" name="cost_generation_det[]" value="<?= $row['id']; ?>"> <?= sales_order_code($row['order_id']); ?></td>
        <td><?= sales_order_style($row['style']); ?></td>
        <td><?= part_name($row['part']); ?> | <?= color_name($row['color']); ?></td>
        <td><?= process_name($row['process']); ?></td>
        <!-- <td><? //= $pqq1[] = $prod_qty; ?></td> -->
        <!-- <td>-</td> -->
        <td><?= $pqq3[] = $row['total_qty'] ?></td>
        <td class="d-none"><?= $pqq4[] = $already['bill_qty'] ? $already['bill_qty'] : 0; ?></td>
        <td class="d-none"><?= $pqq5[] = ($row['total_qty'] - (($already['bill_qty']-$row['bill_qty']) + $row['bill_qty'] )) ?></td>
        <td><?= $pqq6[] = $row['max_rate'] ?></td>
        
        <?php if(!isset($_GET['typ'])) { ?>
            <td><input class="form-control" id="b_rate<?= $row['id']; ?>" name="n_bill_rate[]" type="text" placeholder="Bill Rate" value="<?= $pqq7[] = $row['n_bill_rate'] ? $row['n_bill_rate'] : $row['bill_rate']; ?>"></td>
            <td><input class="form-control" id="b_qtty<?= $row['id']; ?>" name="n_bill_qty[]" type="number" onkeyup="checkDebit(<?= $row['id']; ?>)" placeholder="This Bill Qty" data-val="<?= $row['bill_qty']; ?>" value="<?= $bq[] = $row['n_bill_qty'] ? $row['n_bill_qty'] : $row['bill_qty']; ?>"></td>
            <td><input class="form-control" id="b_amtt<?= $row['id']; ?>" name="n_bill_amount[]" type="text" placeholder="This Bill Value" value="<?= $ba[] = $row['n_bill_amount'] ? $row['n_bill_amount'] : $row['bill_amount']; ?>"></td>
            <td><input class="form-control" id="d_qtty<?= $row['id']; ?>" name="debit_qty[]" type="number" placeholder="Debit Qty" value="<?= $row['debit_qty']; ?>"></td>
            <td><input class="form-control" id="d_valu<?= $row['id']; ?>" name="debit_amount[]" type="text" placeholder="Debit Value"  value="<?= $row['debit_amount']; ?>"></td>
        <?php } else { ?>
            <td><?= $pqq7[] = $row['n_bill_rate'] ? $row['n_bill_rate'] : $row['bill_rate']; ?></td>
            <td><?= $bq[] = $row['n_bill_qty'] ? $row['n_bill_qty'] : $row['bill_qty']; ?></td>
            <td><?= $ba[] = $row['n_bill_amount'] ? $row['n_bill_amount'] : $row['bill_amount']; ?></td>
            <td><?= $row['debit_qty']; ?></td>
            <td><?= $row['debit_amount']; ?></td>
        <?php } ?>
    </tr>
<?php }

} else if(isset($_REQUEST['budget_vs_actual'])) {
    
    $type = $_REQUEST['type'];
        
        $fth = mysqli_query($mysqli, "SELECT * FROM sales_order a WHERE a.is_dispatch IS NULL ORDER BY id DESC ");
        
        while($row = mysqli_fetch_array($fth)) {
            $h = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(rate) as rate FROM budget_process WHERE budget_for = '$type Budget' AND so_id = ". $row['id']));
            
            if($type == 'Production') {
                $cutting = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE order_id = ". $row['id']));
                $running = $cutting['pcs_per_bundle'] * process_budget_rate($row['id'], 1);
            } else {
                $running = 0;
            }
            $resp['tbody'][] = '<tr><td><a class="custom_a" data-bo="'. $row['id'] .'" data-type="'. $type .'" onclick="fun(this)"><i class="icon-copy ion-chevron-right"></i> '. sales_order_code($row['id']) .'</a></td>
                                    <td>'. brand_name($row['brand']) .'</td> <td>'. $row['order_qty'] .'</td> <td style="text-align: right">'. number_format(($h['rate']*$row['order_qty']),2) .'</td> <td>'. number_format($running, 2) .'</td> <td>-</td> </tr> 
                                <tr class="new_tr'. $row['id'] .' d-none"><td colspan="6"> <table class="table"><thead><tr><th>Process Name</th> <th>Order Qty</th> <th>Budget Rate</th> <th>Budget Cost</th> <th>Running Cost</th> <th>Actual Cost</th></tr></thead>
                                <tbody id="new_tbody'. $row['id'] .'"></tbody></table> </td></tr>';
        }
        
    echo json_encode($resp);

} else if(isset($_REQUEST['budget_actual_bo'])) {
    
    $bo = $_REQUEST['bo'];
    $type = $_REQUEST['type'];
    
    $oq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT order_qty, order_code FROM sales_order WHERE id = ". $bo));
    
    $fth = mysqli_query($mysqli, "SELECT * FROM budget_process WHERE budget_for = '". $type ." Budget' AND so_id = ". $bo);
    
    if(mysqli_num_rows($fth)>0) {
        while($row = mysqli_fetch_array($fth)) {
            
            $process = $row['process'];
            
            if($process == 1) { // CUTTING
                $cutting = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE order_id = ". $bo));
                
                $runnning = $cutting['pcs_per_bundle'] * process_budget_rate($bo, 1);
            } else if($process == 9) { // POWER TABLE
                $cutting = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_sewingout WHERE process = 9 AND order_id = ". $bo));
                
                $runnning = $cutting['scanned_count'] * process_budget_rate($bo, 9);
            } else if($process == 10) { // SINGER
                $cutting = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(scanned_count) as scanned_count FROM orbidx_sewingout WHERE process = 10 AND order_id = ". $bo));
                
                $runnning = $cutting['scanned_count'] * process_budget_rate($bo, 10);
            } else {
                $runnning = 0;
            }
            $resp['new_tr'][] = '<tr><td>'. process_name($row['process']) .'</td> <td>'. $oq['order_qty'] .'</td> <td>'. $row['rate'] .'</td>  <td>'. number_format(($oq['order_qty']*$row['rate']),2) .'</td> <td>'. number_format($runnning, 2) .'</td> <td>-</td> </tr>';
        }
    } else {
        $resp['new_tr'][] = '<tr><td colspan="6">'. $type .' Budget Not Created for '. $oq['order_code'] .'</td> </tr>';
    }
    
    echo json_encode($resp);
    
} else if(isset($_REQUEST['show_cmtbudget_type'])) {
    
    $type = $_REQUEST['type'];
    $process_id = $_REQUEST['process_id'];
    
    if($type == "combo") {
        
        $resp['thead'][] = '<tr><th>Sl.No</th><th>Combo</th><th>Rate</th><th>Revised Rate</th><th>Rework Rate</th></tr>';
        
        $qry = mysqli_query($mysqli, "SELECT * FROM sod_combo WHERE sales_order_detail_id = ". $_REQUEST['style_id']);
        $p=1;
        while($row = mysqli_fetch_array($qry)) {
            
            $mq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process_partial WHERE sod_combo = '". $row['id'] ."' AND process = '". $process_id ."' AND style_id = ". $_REQUEST['style_id']));
            $resp['tbody'][] = '<tr>
                                    <td><input class="form-control" type="hidden" name="partial_id'. $process_id .'[]" value="'. $mq['id'] .'" > '. $p .'</td>
                                    <td><input class="form-control" type="hidden" name="sod_combo'. $process_id .'[]" value="'. $row['id'] .'" > '. color_name($row['combo_id']) .'</td>
                                    <td><input type="text" name="combo_rate'. $process_id .'[]" id="" class="form-control" placeholder="Combo Rate" value="'. $mq['rate'] .'"></td>
                                    <td><input type="text" name="combo_revised'. $process_id .'[]" id="" class="form-control" placeholder="Combo Reviced Rate" value="'. $mq['revised_rate'] .'"></td>
                                    <td><input type="text" name="combo_rework'. $process_id .'[]" id="" class="form-control" placeholder="Combo Rework Rate" value="'. $mq['rework_rate'] .'"></td>
                                </tr>';
            $p++;
        }
    } else if($type == 'combo_part') {
        
        $resp['thead'][] = '<tr><th>Sl.No</th><th>Combo</th><th>Part | Color</th><th>Rate</th><th>Revised Rate</th><th>Rework Rate</th></tr>';
        
        $qry = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sales_order_detail_id = ". $_REQUEST['style_id']);
        $p=1;
        while($row = mysqli_fetch_array($qry)) {
            $mq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process_partial WHERE sod_part = '". $row['id'] ."' AND process = '". $process_id ."' AND style_id = ". $_REQUEST['style_id']));
            $resp['tbody'][] = '<tr>
                                    <td><input class="form-control" type="hidden" name="partial_id'. $process_id .'[]" value="'. $mq['id'] .'" > '. $p .'</td>
                                    <td><input class="form-control" type="hidden" name="sod_part'. $process_id .'[]" value="'. $row['id'] .'" > '. color_name($row['combo_id']) .'</td>
                                    <td>'. part_name($row['part_id']) .' | '. color_name($row['color_id']) .'</td>
                                    <td><input type="text" name="part_rate'. $process_id .'[]" id="" class="form-control" placeholder="Combo Rate" value="'. $mq['rate'] .'"></td>
                                    <td><input type="text" name="part_revised'. $process_id .'[]" id="" class="form-control" placeholder="Combo Reviced Rate" value="'. $mq['revised_rate'] .'"></td>
                                    <td><input type="text" name="part_rework'. $process_id .'[]" id="" class="form-control" placeholder="Combo Rework Rate" value="'. $mq['rework_rate'] .'"></td>
                                </tr>';
            $p++;
        }
    }
    
    echo json_encode($resp);

} else if(isset($_REQUEST['validate_username'])) {

    $uname = $_POST['uname'];

    if($uname!="") {
        $fetch = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM employee_detail WHERE username = '". $uname ."'"));
        $data['found'] = $fetch;
    } else {
        $data['found'] = 0;
    }

    echo json_encode($data);

} else if(isset($_REQUEST['scanned_pcs_list'])) {
    
    $id = $_POST['id'];
    $from = $_POST['from'];

    $table = ($from == 'sewing') ? 'orbidx_sewingout' : (($from == 'checking') ? 'orbidx_checking' : 'orbidx_component_process');

    $row = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM $table WHERE id = '". $id ."'"));
    $array = json_decode($row['qr_code']);

    $grouped = [];

    foreach ($array as $item) {
        
        list($prefix, $suffix) = explode('-', $item);
        
        if (!isset($grouped[$prefix])) {
            $grouped[$prefix] = [];
        }
        $grouped[$prefix][] = $suffix;
    }

    $q = 1;
    foreach ($grouped as $prefix => $suffixes) {

        $bnum = mysqli_fetch_array(mysqli_query($mysqli, "SELECT bundle_number FROM bundle_details WHERE id = ". $prefix));
        sort($suffixes);
        $suffixes_count = count($suffixes);
        $suffixesString = implode(', ', $suffixes);
        $data['tbody'][] = "<tr><td>{$q}</td><td>{$bnum['bundle_number']}</td><td>{$suffixes_count}</td><td>{$suffixesString}</td></tr>";
        $q++;
    }

    echo json_encode($data);
}
















// timeline_history('Insert', 'employee_detail_temp', $_REQUEST['id'], 'Employee Request Rejected.');
?>