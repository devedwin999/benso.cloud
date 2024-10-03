<?php
include ("includes/connection.php");
include ("includes/function.php");

if (!isset($_SESSION['login_id'])) {
    header('Location:index.php');
}

if (isset($_REQUEST['save_PO_expense'])) {

    $arr = array(
        'fabric_po' => $_REQUEST['fabric_po'],
        'expense_amount' => $_REQUEST['expense_amount'],
        'expense_name' => $_REQUEST['expense_name'],
        'created_by' => $logUser,
    );

    $ins = Insert('fabric_po_expense', $arr);
    $inis = mysqli_insert_id($mysqli);

    if ($ins) {
        $data['result'][] = 0;
        timeline_history('Insert', 'fabric_po_expense', $inis, 'Expense Added for Fabric PO. Ref: ' . $_REQUEST['entry_number']);
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['saveGrandTotal'])) {

    $ins = Update('fabric_po', array('grand_total' => ($_REQUEST['grand_total'] + $_REQUEST['expense_amount']), 'ship_to' => $_REQUEST['ship_to']), ' WHERE id = ' . $_REQUEST['fabric_po']);

    if ($ins) {
        $data['result'][] = 0;

    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['fab_opening_edit'])) {

    $arr = array(
        'bag_roll' => $_REQUEST['e_bag_roll'],
        'opening_qty' => $_REQUEST['e_opening_qty'],
    );

    $ins = Update('fabric_opening_det', $arr, ' WHERE id =' . $_REQUEST['e_id']);

    if ($ins) {
        $data['result'][] = 0;
        timeline_history('Insert', 'fabric_opening_det', $_REQUEST['e_id'], 'Fabric Stock Opening Updated. Ref: ' . $_REQUEST['entry_number']);
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);


} else if (isset($_REQUEST['save_PO_expense_edit'])) {

    $arr = array(
        'amount' => $_REQUEST['e_amount'],
        'tax_per' => $_REQUEST['e_tax_per'],
        'rate' => $_REQUEST['e_rate'],
        'po_qty_wt' => $_REQUEST['e_po_qty_wt'],
    );

    $ins = Update('fabric_po_det', $arr, ' WHERE id =' . $_REQUEST['e_id']);

    if ($ins) {
        $data['result'][] = 0;
        timeline_history('Insert', 'fabric_po_det', $_REQUEST['e_id'], 'Fabric PO Updated. Ref: ' . $_REQUEST['entry_number']);
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);


} else if (isset($_REQUEST['save_PO_receipt_edit'])) {

    $arr = array(
        'received_bag' => $_REQUEST['received_bag'],
        'received_qty' => $_REQUEST['received_qty'],
    );

    $ins = Update('fabric_po_receipt_det', $arr, ' WHERE id =' . $_REQUEST['id']);
    $ins = Update('fabric_stock', $arr, ' WHERE stock_from = "fabric_po_receipt_det" AND stock_id =' . $_REQUEST['id']);

    if ($ins) {
        $data['result'][] = 0;
        timeline_history('Insert', 'fabric_po_receipt_det', $_REQUEST['id'], 'Fabric Purchasae Receipt Received Qty/Wt and Bag/Roll Updated. Ref: ' . $_REQUEST['entry_number']);
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['addFabric_PO_receipt'])) {

    if ($_REQUEST['po_receipt'] == "") {

        $arr = array(
            'grn_number' => $_REQUEST['grn_number'],
            'grn_date' => $_REQUEST['grn_date'],
            'supplier' => $_REQUEST['supplier'],
            'sup_dc_number' => $_REQUEST['sup_dc_number'],
            'sup_dc_date' => $_REQUEST['sup_dc_date'],
            'created_by' => $logUser,
            'created_unit' => $logUnit,
        );

        $ins = Insert('fabric_po_receipt', $arr);
        $po_receipt = mysqli_insert_id($mysqli);
        timeline_history('Insert', 'fabric_po_receipt', $po_receipt, 'Fabric PO Receipt Created. Ref: ' . $_REQUEST['grn_number']);
    } else {
        $arr = array(
            'grn_date' => $_REQUEST['grn_date'],
            'supplier' => $_REQUEST['supplier'],
            'sup_dc_number' => $_REQUEST['sup_dc_number'],
            'sup_dc_date' => $_REQUEST['sup_dc_date'],
        );

        $ins = Update('fabric_po_receipt', $arr, 'WHERE id=' . $_REQUEST['po_receipt']);
        $po_receipt = $_REQUEST['po_receipt'];
    }

    $reqq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_requirements WHERE id = " . $_REQUEST['fab_req']));

    $narr = array(
        'fabric_po_receipt' => $po_receipt,
        'fabric_po_det' => $_REQUEST['material_name'],
        'fabric_requirements' => $_REQUEST['fab_req'],
        'po_stage' => $reqq['process_id'],
        'bag_roll' => $_REQUEST['bag_roll'],
        'po_qty_wt' => $_REQUEST['po_qty_wt'],
        'received_bal' => $_REQUEST['received_bal'],
        'received_bag' => $_REQUEST['received_bag'],
        'received_qty' => $_REQUEST['received_qty'],
    );

    $ins = Insert('fabric_po_receipt_det', $narr);
    $detId = mysqli_insert_id($mysqli);

    $farr = mysqli_fetch_array(mysqli_query($mysqli, "SELECT po_qty_wt, received_bal, received_qty, stock_bo FROM fabric_po_det WHERE id=" . $_REQUEST['material_name']));
    
    timeline_history('Insert', 'fabric_po_receipt_det', $detId, 'Fabric Purchase Receipt List Added. Ref: ' . $_REQUEST['grn_number']);

    $yfab = ($reqq['yarn_id'] != NULL) ? 'yarn_id' : 'fabric_id';
    $yfab_val = ($reqq['yarn_id'] != NULL) ? $reqq['yarn_id'] : $reqq['fabric_id'];

    $stok = array(
        'fabric_requirements' => $reqq['id'],
        'order_id' => $reqq['order_id'],
        'style_id' => $reqq['style_id'],
        'process_id' => $reqq['process_id'],
        'process_order' => $reqq['process_order'],
        'stock_bo' => $farr['stock_bo'],
        $yfab => $yfab_val,

        'stock_from' => 'fabric_po_receipt_det',
        'stock_id' => $detId,
        'entry_grn_number' => $_REQUEST['grn_number'],
        'req_bag_roll' => $_REQUEST['bag_roll'],
        'req_qty' => $_REQUEST['po_qty_wt'],
        'received_bag' => $_REQUEST['received_bag'],
        'received_qty' => $_REQUEST['received_qty'],  
        'stock_qty' => $_REQUEST['received_qty'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );
    $ins = Insert('fabric_stock', $stok);

    $rQ = $farr['received_qty'] + $_REQUEST['received_qty'];
    $rB = $farr['po_qty_wt'] - $rQ;
    $rC = ($rB <= 0) ? 'Yes' : 'No';
    $aryy = array(
        'received_qty' => $rQ,
        'received_bal' => $rB,
        'complete_receipt' => $rC,
    );
    $ins = Update('fabric_po_det', $aryy, ' WHERE id=' . $_REQUEST['material_name']);

    if ($ins) {

        $data['po_receipt'][] = $po_receipt;
        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['delete_fab_opening'])) {
    
    $pp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT b.entry_number FROM fabric_opening_det a LEFT JOIN fabric_opening b ON a.fabric_opening_id=b.id WHERE a.id=" . $_REQUEST['id']));
    $enum = $pp['entry_number'];

    $ins = Delete('fabric_opening_det', ' WHERE id =' . $_REQUEST['id']);
    $ins = Delete('fabric_stock', ' WHERE stock_from = "fabric_opening_det" AND stock_id =' . $_REQUEST['id']);
    

    if ($ins) {
        $data['result'][] = 0;
        timeline_history('Delete', 'fabric_opening_det', $_REQUEST['id'], 'Fabric Purchase Receipt List Deleted. DC No: ' . $enum);
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['delete_fab_receipt'])) {

    $ee = mysqli_fetch_array(mysqli_query($mysqli, "SELECT received_qty, fabric_po_det, fabric_po_receipt FROM fabric_po_receipt_det WHERE id=" . $_REQUEST['id']));
    $rt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT received_bal, received_qty FROM fabric_po_det WHERE id=" . $ee['fabric_po_det']));

    $arr = array(
        'received_bal' => ($rt['received_bal'] + $ee['received_qty']),
        'received_qty' => ($rt['received_qty'] - $ee['received_qty']),
    );

    $ins = Update('fabric_po_det', $arr, ' WHERE id =' . $ee['fabric_po_det']);

    if ($ins) {
        $ins = Delete('fabric_po_receipt_det', ' WHERE id =' . $_REQUEST['id']);
        $ins = Delete('fabric_stock', ' WHERE stock_from = "fabric_po_receipt_det" AND stock_id =' . $_REQUEST['id']);
    }

    $pp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT grn_number FROM fabric_po_receipt WHERE id=" . $ee['fabric_po_receipt']));

    if ($ins) {
        $data['result'][] = 0;
        timeline_history('Delete', 'fabric_po_receipt_det', $_REQUEST['id'], 'Fabric Purchase Receipt List Deleted. DC No: ' . $pp['grn_number']);
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);


} else if (isset($_REQUEST['addFabric_DC_list'])) {

    $arr = array(
        'dc_number' => $_REQUEST['dc_number'],
        'dc_date' => $_REQUEST['entry_date'],
        'supplier' => $_REQUEST['supplier'],
        'process' => $_REQUEST['to_process'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    if ($_REQUEST['dc_idd'] == "") {
        $ins = Insert('fabric_dc', $arr);
        $inid = mysqli_insert_id($mysqli);
        timeline_history('Insert', 'fabric_dc', $inid, 'Fabric DC Inserted. Ref: ' . $_REQUEST['dc_number']);
    } else {
        $inid = $_REQUEST['dc_idd'];
    }

    $porder = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_requirements WHERE id = " . $_REQUEST['material_name']));

    if ($_REQUEST['group_type'] == 'yarn') {

        for ($m = 0; $m < count($_REQUEST['yarn_id']); $m++) {

            $li_arr = array(

                'fabric_dc_id' => $inid,
                'order_id' => $porder['order_id'],
                'style_id' => $_REQUEST['order_id'],
                'fab_req_id' => $_REQUEST['material_name'],
                'fabric_id' => $_REQUEST['fabric_id'],
                'output_wt' => $_REQUEST['output_wt'],
                'process_id' => $_REQUEST['to_process'],
                'process_order' => $porder['process_order'],
                'yarn_id' => $_REQUEST['yarn_id'][$m],
                'mixing_per' => $_REQUEST['mixing_per'][$m],
                'dc_balance' => $_REQUEST['dc_balance'][$m],
                'stock' => $_REQUEST['stock'][$m],
                'bag_roll' => $_REQUEST['bag_roll'][$m],
                'dc_qty_wt' => $_REQUEST['dc_qty_wt'][$m],
            );

            $ins = Insert('fabric_dc_det', $li_arr);
            $inid3 = mysqli_insert_id($mysqli);
            timeline_history('Insert', 'fabric_dc_det', $inid3, 'Fabric DC Detail Inserted. Ref: ' . $_REQUEST['dc_number']);

            $inw_qty = $_REQUEST['dc_qty_wt'][$m];

            $hk = mysqli_query($mysqli, "SELECT id,stock_qty FROM fabric_stock WHERE fabric_requirements = '" . $_REQUEST['requirements_from'][$m] . "'");
            while ($rows = mysqli_fetch_array($hk)) {
                if ($rows['stock_qty'] > 0 && $rows['stock_qty'] <= $inw_qty) {
                    $new_stock_qty = 0;
                    $stock_status = 'out_of_stock';
                } else if ($rows['stock_qty'] > 0 && $rows['stock_qty'] >= $inw_qty) {
                    $new_stock_qty = $rows['stock_qty'] - $inw_qty;
                    $stock_status = 'in_stock';
                }

                mysqli_query($mysqli, "UPDATE fabric_stock SET stock_qty = '" . $new_stock_qty . "', stock_status = '$stock_status' WHERE id = '" . $rows['id'] . "'");

                $inw_qty -= $new_stock_qty;

                if ($inw_qty <= 0) {
                    break;
                }
            }

            $reqq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_requirements WHERE id = " . $_REQUEST['material_name']));
            $yfab = ($reqq['yarn_id'] != NULL) ? 'yarn_id' : 'fabric_id';
            $yfab_val = ($reqq['yarn_id'] != NULL) ? $reqq['yarn_id'] : $reqq['fabric_id'];

            $stockk = array(
                'fabric_requirements' => $reqq['id'],
                'order_id' => $reqq['order_id'],
                'style_id' => $_REQUEST['order_id'],
                'process_id' => $reqq['process_id'],
                'process_order' => $reqq['process_order'],
                'req_qty' => $reqq['req_wt'],
                $yfab => $yfab_val,
                'stock_status' => 'supplier_stock',

                'stock_bo' => 'bo',

                'supplier' => $_REQUEST['supplier'],
                'requirements_from' => $_REQUEST['requirements_from'][$m],
                'stock_from' => 'fabric_dc_det',
                'stock_id' => $inid3,
                'entry_grn_number' => $_REQUEST['dc_number'],
                'received_bag' => $_REQUEST['bag_roll'][$m],
                'received_qty' => $_REQUEST['dc_qty_wt'][$m],
                'stock_qty' => $_REQUEST['dc_qty_wt'][$m],
                'created_by' => $logUser,
                'created_unit' => $logUnit
            );

            $ins = Insert('fabric_stock', $stockk);
        }

    } else if ($_REQUEST['group_type'] == 'fabric') {
        
        for ($m = 0; $m < count($_REQUEST['color_id']); $m++) {

            $li_arr = array(

                'fabric_dc_id' => $inid,
                'order_id' => $porder['order_id'],
                'style_id' => $_REQUEST['order_id'],
                'fab_req_id' => $_REQUEST['material_name'],
                'fabric_id' => $_REQUEST['fabric_id'],
                'output_wt' => $_REQUEST['output_wt'],
                'process_id' => $_REQUEST['to_process'],
                'process_order' => $porder['process_order'],
                'color_id' => $_REQUEST['color_id'][$m],
                'dc_balance' => $_REQUEST['dc_balance'][$m],
                'stock' => $_REQUEST['stock'][$m],
                'bag_roll' => $_REQUEST['bag_roll'][$m],
                'dc_qty_wt' => $_REQUEST['dc_qty_wt'][$m],
            );
            
            $ins = Insert('fabric_dc_det', $li_arr);
            
            $inid3 = mysqli_insert_id($mysqli);
            timeline_history('Insert', 'fabric_dc_det', $inid3, 'Fabric DC Detail Inserted. Ref: ' . $_REQUEST['dc_number']);


            $inw_qty = $_REQUEST['dc_qty_wt'][$m];

            // $hk = mysqli_query($mysqli, "SELECT id,stock_qty FROM fabric_stock WHERE fabric_requirements = '" . $_REQUEST['requirements_from'][$m] . "'");
            // while ($rows = mysqli_fetch_array($hk)) {
            //     if ($rows['stock_qty'] > 0 && $rows['stock_qty'] <= $inw_qty) {
            //         $new_stock_qty = 0;
            //         $stock_status = 'out_of_stock';
            //     } else if ($rows['stock_qty'] > 0 && $rows['stock_qty'] >= $inw_qty) {
            //         $new_stock_qty = $rows['stock_qty'] - $inw_qty;
            //         $stock_status = 'in_stock';
            //     }

            //     mysqli_query($mysqli, "UPDATE fabric_stock SET stock_qty = '" . $new_stock_qty . "', stock_status = '$stock_status' WHERE id = '" . $rows['id'] . "'");

            //     $inw_qty -= $new_stock_qty;

            //     if ($inw_qty <= 0) {
            //         break;
            //     }
            // }

            $hk = mysqli_query($mysqli, "SELECT id, stock_qty FROM fabric_stock WHERE fabric_requirements = '" . $_REQUEST['requirements_from'][$m] . "' AND stock_status = 'in_stock' ORDER BY id ASC");
            while ($rows = mysqli_fetch_array($hk)) {
                
                $stock_qty = $rows['stock_qty'];
                $new_stock_qty = $stock_qty;
                $stock_status = 'in_stock';

                
                if ($stock_qty > 0) {
                    if ($stock_qty <= $inw_qty) {
                        $new_stock_qty = 0;
                        $stock_status = 'out_of_stock';
                    } else {
                        $new_stock_qty = $stock_qty - $inw_qty;
                        $inw_qty = 0;
                    }
                    
                    mysqli_query($mysqli, "UPDATE fabric_stock SET stock_qty = '$new_stock_qty', stock_status = '$stock_status' WHERE id = '" . $rows['id'] . "'");
                    
                    if ($stock_qty <= $inw_qty) {
                        $inw_qty -= $stock_qty;
                    } else {
                        $inw_qty = 0;
                        break;
                    }
                }
                
                if ($inw_qty <= 0) {
                    break;
                }
            }

            $reqq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_requirements WHERE id = " . $_REQUEST['material_name']));
            $yfab = ($reqq['yarn_id'] != NULL) ? 'yarn_id' : 'fabric_id';
            $yfab_val = ($reqq['yarn_id'] != NULL) ? $reqq['yarn_id'] : $reqq['fabric_id'];

            $stockk = array(
                'fabric_requirements' => $reqq['id'],
                'order_id' => $porder['order_id'],
                'style_id' => $_REQUEST['order_id'],
                'process_id' => $reqq['process_id'],
                'process_order' => $reqq['process_order'],
                'req_qty' => $reqq['req_wt'],
                $yfab => $yfab_val,
                'stock_status' => 'supplier_stock',

                'supplier' => $_REQUEST['supplier'],
                'requirements_from' => $_REQUEST['requirements_from'][$m],
                'stock_from' => 'fabric_dc_det',
                'stock_id' => $inid3,
                'entry_grn_number' => $_REQUEST['dc_number'],
                'received_bag' => $_REQUEST['bag_roll'][$m],
                'received_qty' => $_REQUEST['dc_qty_wt'][$m],
                'stock_qty' => $_REQUEST['dc_qty_wt'][$m],
                'created_by' => $logUser,
                'created_unit' => $logUnit
            );

            $ins = Insert('fabric_stock', $stockk);
            // }
        }
    }



    if ($ins) {
        $data['result'][] = 0;
        $data['dc_idd'][] = $inid;
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['insert_inwarded_dc'])) {


    $fabric_dc = $_POST['fabric_dc'];
    
    $row = mysqli_query($mysqli, "SELECT *, sum(dc_qty_wt) as dc_qty_wt FROM fabric_dc_det WHERE fabric_dc_id = '". $fabric_dc ."' GROUP BY fab_req_id");
    while($result = mysqli_fetch_array($row)) {

        if($result['process_id']==22) { $compnt = fabric_name($result['fabric_id']); }
        else { $compnt = fabric_name($result['fabric_id']) .' || '. color_name($result['color_id']); }

        $in_stock = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(received_qty) as received_qty FROM fabric_stock WHERE fabric_requirements = '". $result['fab_req_id'] ."' AND stock_from = 'fabric_dc_inw_det'"));

        $max = ($result['dc_qty_wt'] - $in_stock['received_qty']);

        $data['tbody'][] = '<tr>
                                <td>'. sales_order_code($result['order_id']) .' | '. sales_order_style($result['style_id']) .'</td>
                                <td>'. $compnt .'</td>
                                <td>'. $result['dc_qty_wt'] .'</td>
                                <td><input type="text" name="inw_bag_roll[]" placeholder="Bag/ Roll" class="form-control number_input mw-200" required></td>
                                <td><input type="text" name="inw_qty[]" placeholder="Inward Qty" class="form-control number_input mw-200 inw_qty" onkeyup="validate_maxqty(this)" data-max="'. $max .'" data-tid="'. $result['id'] .'" required></td>
                                <td>
                                    <input type="hidden" name="dc_qty[]" value="'. $result['dc_qty_wt'] .'">
                                    <input type="hidden" name="fabric_dc_det_id[]" value="'. $result['id'] .'">
                                    <input type="hidden" name="balance_qty[]" id="balance_qty'. $result['id'] .'" value="'. $max .'">
                                    <span id="balance'. $result['id'] .'">'. $max .'</span>
                                </td>
                            </tr>';
    }



    // $arry = array(

    //     'entry_number' => $_REQUEST['entry_number'],
    //     'entry_date' => $_REQUEST['entry_date'],
    //     'supplier' => $_REQUEST['supplier'],
    //     'supplier_dc' => $_REQUEST['supplier_dc'],
    //     'supplier_dc_date' => $_REQUEST['supplier_dc_date'],
    //     'fabric_dc' => $_REQUEST['fabric_dc'],
    //     'inward_type' => $_REQUEST['inward_type'],
    //     'inward_process' => $_REQUEST['inward_process'] ? implode(',', $_REQUEST['inward_process']) : '',
    // );

    // $ins = Insert('fabric_dc_inw', $arry);

    // $inid = mysqli_insert_id($mysqli);
    // timeline_history('Insert', 'fabric_dc_inw', mysqli_insert_id($mysqli), 'Fabric DC Inwarded. Ref: ' . $_REQUEST['entry_number']);



    // if ($ins) {
    //     $data['inid'][] = $inid;
    //     $data['result'][] = 0;
    // } else {
    //     $data['result'][] = 1;
    // }

    echo json_encode($data);

} else if (isset($_REQUEST['save_dcInward_list'])) {

    foreach ($_REQUEST['process_id'] as $process) {


        for ($m = 0; $m < count($_REQUEST['fabric_dc_det_id' . $process]); $m++) {

            $arry = array(

                'process_id' => $process,
                'grn_number' => $_REQUEST['grn_number' . $process],
                'supp_dc_number' => $_REQUEST['supp_dc_number' . $process],
                'supplier' => $_REQUEST['supplier'],

                'fabric_dc_inw' => $_REQUEST['fabric_dc_inw'],
                'fabric_dc_det_id' => $_REQUEST['fabric_dc_det_id' . $process][$m],
                'dc_qty' => $_REQUEST['dc_qty' . $process][$m],
                'inw_qty' => $_REQUEST['inw_qty' . $process][$m],
                'balance_qty' => $_REQUEST['balance_qty' . $process][$m],
            );

            if ($_REQUEST['fabric_dc_inw_det_id' . $process][$m] == "") {
                $ins = Insert('fabric_dc_inw_det', $arry);
                $inid3 = mysqli_insert_id($mysqli);
                // timeline_history('Insert', 'fabric_dc_inw_det', mysqli_insert_id($mysqli), 'Fabric Inwarded DC Detail Inserted. Ref: '. $_REQUEST['dc_number']);
            } else {
                $ins = Update('fabric_dc_inw_det', $arry, 'WHERE id =' . $_REQUEST['fabric_dc_inw_det_id' . $process][$m]);
                $inid3 = $_REQUEST['fabric_dc_inw_det_id' . $process][$m];
                // timeline_history('Update', 'fabric_dc_inw_det', $_REQUEST['fabric_dc_inw_det_id'][$m], 'Fabric Inwarded DC Detail Inserted. Ref: '. $_REQUEST['dc_number']);
            }


            $inw_qty = $_REQUEST['inw_qty' . $process][$m];

            $hk = mysqli_query($mysqli, "SELECT id,stock_qty FROM fabric_stock WHERE fabric_requirements = '" . $_REQUEST['fab_req_id' . $process][$m] . "' AND stock_status = 'supplier_stock'");
            while ($rows = mysqli_fetch_array($hk)) {
                if ($rows['stock_qty'] > 0 && $rows['stock_qty'] <= $inw_qty) {
                    $new_stock_qty = 0;
                    $stock_status = 'out_of_stock';
                } else if ($rows['stock_qty'] > 0 && $rows['stock_qty'] >= $inw_qty) {
                    $new_stock_qty = $rows['stock_qty'] - $inw_qty;
                    $stock_status = 'supplier_stock';
                }

                mysqli_query($mysqli, "UPDATE fabric_stock SET stock_qty = '" . $new_stock_qty . "', stock_status = '$stock_status' WHERE id = '" . $rows['id'] . "'");

                $inw_qty -= $new_stock_qty;

                if ($inw_qty <= 0) {
                    break;
                }
            }

            $reqq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_requirements WHERE id = " . $_REQUEST['fab_req_id' . $process][$m]));
            $yfab = ($reqq['yarn_id'] != NULL) ? 'yarn_id' : 'fabric_id';
            $yfab_val = ($reqq['yarn_id'] != NULL) ? $reqq['yarn_id'] : $reqq['fabric_id'];

            $stockk = array(
                'fabric_requirements' => $reqq['id'],
                'order_id' => $reqq['order_id'],
                'process_id' => $reqq['process_id'],
                'process_order' => $reqq['process_order'],
                'req_qty' => $reqq['req_wt'],
                $yfab => $yfab_val,
                'stock_status' => 'in_stock',

                'supplier' => $_REQUEST['supplier'],
                'stock_from' => 'fabric_dc_inw_det',
                'stock_id' => $inid3,
                'entry_grn_number' => $_REQUEST['entry_number'],
                // 'received_bag' => $_REQUEST['bag_roll'][$m],
                'received_qty' => $_REQUEST['inw_qty' . $process][$m],
                'stock_qty' => $_REQUEST['inw_qty' . $process][$m],
                'created_by' => $logUser,
                'created_unit' => $logUnit
            );

            $ins = Insert('fabric_stock', $stockk);
        }
    }



    if ($ins) {
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_device'])) {


    $mysqli_ = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM orbidx_device WHERE device = '" . $_REQUEST['device'] . "'"));

    if ($mysqli_ == 0) {
        $array = array(
            'device' => $_REQUEST['device'],
            'department' => $_REQUEST['department'],
            'process' => $_REQUEST['process'],
            'scan_type' => $_REQUEST['scan_type'],
            'line' => $_REQUEST['line'],
            'created_by' => $logUser,
            'created_unit' => $logUnit,
        );

        $ins = Insert('orbidx_device', $array);

        timeline_history('Insert', 'orbidx_device', mysqli_insert_id($mysqli), 'ORBIDX Device Added. Ref: ' . $_REQUEST['device']);

        if ($ins) {
            $data['result'][] = 0;
            $data['error'][] = 0;
        } else {
            $data['result'][] = 1;
            $data['error'][] = 1;
        }
    } else {
        $data['error'][] = 2; // already added
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_device_edit'])) {


    $mysqli_ = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM orbidx_device WHERE device = '" . $_REQUEST['device'] . "'"));

    if ($mysqli_ == 0) {
        $array = array(
            'department' => $_REQUEST['department'],
            'process' => $_REQUEST['process'],
            'scan_type' => $_REQUEST['scan_type'],
            'line' => $_REQUEST['line'],
        );

        $ins = Update('orbidx_device', $array, 'WHERE id = ' . $_REQUEST['id']);

        timeline_history('Update', 'orbidx_device', mysqli_insert_id($mysqli), 'ORBIDX Device Updated. Ref: ' . orbidx_device_name($_REQUEST['id']));

        if ($ins) {
            $data['result'][] = 0;
            $data['error'][] = 0;
        } else {
            $data['result'][] = 1;
            $data['error'][] = 1;
        }
    } else {
        $data['error'][] = 2; // already added
    }

    echo json_encode($data);

} else if (isset($_REQUEST['delete_poList'])) {

    $del = Delete('fabric_po', 'WHERE id = ' . $_REQUEST['id']);

    $sqll = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM fabric_po_det WHERE fab_po = " . $_REQUEST['id']));
    if ($del && $sqll > 0) {
        $del = Delete('fabric_po_det', 'WHERE fab_po = ' . $_REQUEST['id']);
    }

    if ($del) {
        timeline_history('Delete', 'fabric_po', $_REQUEST['id'], 'Fabric Purchase Order Deleted. Ref: ' . fabric_po_ref($_REQUEST['id']) . '');
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['delete_poreceipt'])) {

    $del = Delete('fabric_po_receipt', 'WHERE id = ' . $_REQUEST['id']);

    $sqll = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM fabric_po_receipt_det WHERE fabric_po_receipt = " . $_REQUEST['id']));
    if ($del && $sqll > 0) {
        $del = Delete('fabric_po_receipt_det', 'WHERE fabric_po_receipt = ' . $_REQUEST['id']);
    }

    if ($del) {
        // timeline_history('Delete', 'fabric_po_receipt', $_REQUEST['id'], 'Fabric Purchase Order Deleted. Ref: '. fabric_po_ref1111($_REQUEST['id']).'');
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['delete_po_outdc'])) {

    $del = Delete('fabric_dc', 'WHERE id = ' . $_REQUEST['id']);

    $sqll = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM  fabric_dc_det WHERE fabric_dc_id = " . $_REQUEST['id']));
    if ($del && $sqll > 0) {
        $del = Delete(' fabric_dc_det', 'WHERE fabric_dc_id = ' . $_REQUEST['id']);
    }

    if ($del) {
        // timeline_history('Delete', 'fabric_dc', $_REQUEST['id'], 'Fabric Purchase Order Deleted. Ref: '. fabric_po_ref1111($_REQUEST['id']).'');
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['copyPart'])) {

    $kb = mysqli_query($mysqli, "SELECT * FROM sales_order_fabric_program WHERE sod_part = '" . $_REQUEST['from_part'] . "' ");

    $to_partt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sod_part where id = " . $_REQUEST['to_part']));

    if (mysqli_num_rows($kb) > 0) {
        while ($prog = mysqli_fetch_array($kb)) {
            $ins = array(
                'sales_order_id' => $prog['sales_order_id'],
                'sales_order_detalis_id' => $_REQUEST['style'],

                'sod_part' => $to_partt['id'],
                'sod_combo' => $to_partt['sod_combo'],
                'combo_id' => $to_partt['combo_id'],
                'part_id' => $to_partt['part_id'],
                'part_color' => $to_partt['color_id'],

                'fabric_type' => $prog['fabric_type'],
                'fabric' => $prog['fabric'],
                'gsm' => $prog['gsm'],
                'dyeing_color' => $prog['dyeing_color'],
                'aop' => $prog['aop'],
                'aop_name' => $prog['aop_name'],
                'aop_image' => $prog['aop_image'],
                'component' => $prog['component'],
                'component_detail' => NULL,
                'yarn_detail' => $prog['yarn_detail'],
                'tot_finishingDia' => $prog['tot_finishingDia'],
                'tot_pieceWt' => $prog['tot_pieceWt'],
                'tot_reqWt' => $prog['tot_reqWt'],
                'created_by' => $logUser,
                'created_unit' => $logUnit,
            );

            $insert = Insert('sales_order_fabric_program', $ins);
            $inid = mysqli_insert_id($mysqli);

            $compont = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_fabric_components WHERE fabric_program_id = '" . $prog['id'] . "'"));

            $sell = mysqli_query($mysqli, "SELECT * FROM sod_size a LEFT JOIN sod_part b on a.sod_combo = b.sod_combo WHERE b.id = " . $prog['sod_part']);

            while ($szz = mysqli_fetch_array($sell)) {
                // foreach(json_decode($sell['size_detail']) as $vval) {
                // print_r($vval);

                // $exp = explode(',,', $vval);
                // $size = explode('=', $exp[0]);
                // $oQty = explode('=', $exp[1]);
                // $exss = explode('=', $exp[2]);
                
                $exess = ($szz['size_qty'] + (($szz['excess_per'] / 100) * $szz['size_qty']));
                
                $narr = array(
                    'sales_order_id' => $prog['sales_order_id'],
                    'sales_order_detalis_id' => $_REQUEST['style'],
                    
                    'sod_size' => $szz['id'],
                    'sod_part' => $to_partt['id'],
                    'sod_combo' => $to_partt['sod_combo'],
                    'combo_id' => $to_partt['combo_id'],
                    'part_id' => $to_partt['part_id'],
                    'part_color' => $to_partt['color_id'],
                    
                    'fabric_program_id' => $inid,
                    'fabric' => $compont['fabric'],
                    'variation_value' => $szz['variation_value'],
                    'order_qty' => $szz['size_qty'],
                    'excess_qty' => ($szz['size_qty'] + (($szz['excess_per'] / 100) * $szz['size_qty'])),
                    'excess' => $szz['excess_per'],
                    'finishing_dia' => $compont['finishing_dia'],
                    'piece_wt' => $compont['piece_wt'],
                    'req_wt' => ($exess * ($compont['piece_wt']/1000)),
                );
                // round($qtty[1] + ($exPer[1]/100)*$qtty[1])

                $insert = Insert('sales_order_fabric_components', $narr);
            }

            $prss = mysqli_query($mysqli, "SELECT * FROM sales_order_fabric_components_process WHERE fabric_program_id = '" . $prog['id'] . "'");
            while ($process = mysqli_fetch_array($prss)) {
                $parr = array(
                    'sales_order_id' => $prog['sales_order_id'],
                    'sales_order_detalis_id' => $_REQUEST['style'],

                    'sod_part' => $to_partt['id'],
                    'sod_combo' => $to_partt['sod_combo'],
                    'combo_id' => $to_partt['combo_id'],
                    'part_id' => $to_partt['part_id'],
                    'part_color' => $to_partt['color_id'],

                    'fabric_program_id' => $inid,

                    'fabric_component_id' => $process['fabric_component_id'],
                    'component' => $process['component'],
                    'process_id' => $process['process_id'],
                    'process_order' => $process['process_order'],
                    'lossPer' => $process['lossPer'],
                );

                $insert = Insert('sales_order_fabric_components_process', $parr);
            }

            $prss = mysqli_query($mysqli, "SELECT * FROM sales_order_fabric_components_yarn WHERE fabric_program_id = '" . $prog['id'] . "'");
            while ($yarnn = mysqli_fetch_array($prss)) {
                $yar = array(
                    'sales_order_id' => $prog['sales_order_id'],
                    'sales_order_detalis_id' => $_REQUEST['style'],

                    'sod_part' => $to_partt['id'],
                    'sod_combo' => $to_partt['sod_combo'],
                    'combo_id' => $to_partt['combo_id'],
                    'part_id' => $to_partt['part_id'],
                    'part_color' => $to_partt['color_id'],

                    'fabric_program_id' => $inid,

                    'fabric' => $yarnn['fabric'],
                    'fabric_component_id' => $yarnn['fabric_component_id'],
                    'component' => $yarnn['component'],
                    'yarn_id' => $yarnn['yarn_id'],
                    'yarn_color' => $yarnn['yarn_color'],
                    'mixed' => $yarnn['mixed'],
                );

                $insert = Insert('sales_order_fabric_components_yarn', $yar);
            }
        }
    } else {
        $data['notFound'][] = 1;
    }
    if ($insert) {
        timeline_history('Insert', 'sales_order_fabric_program', $_REQUEST['id'], 'Fabric Program Copied. Ref Style: ' . sales_order_style($_REQUEST['style']) . '');
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['opdcsaveorder'])) {

    $data = array(
        'podc_entry' => $_REQUEST['podc_entry'],
        'podc_date' => $_REQUEST['podc_date'],
        'opdc_canceltype' => $_REQUEST['opdc_canceltype'],
        'podc_entryno' => $_REQUEST['podc_entryno'],
        'opdc_cancelto' => $_REQUEST['opdc_cancelto'],
        'created_date' => date('Y-m-d H:i:s')
    );

    if ($_REQUEST['podc_cancel_id'] == "") {
        $qry = Insert('podc_cancel', $data);
        $inid = mysqli_insert_id($mysqli);
    } else {
        $qry = Update('podc_cancel', $data, 'WHERE id = ' . $_REQUEST['podc_cancel_id']);
        $inid = $_REQUEST['podc_cancel_id'];
    }


    for ($mm = 0; $mm < count($_REQUEST['cancel_id']); $mm++) {

        $Ar = array(
            'podc_cancel_id' => $inid,
            'cancel_type' => $_REQUEST['opdc_canceltype'],
            'cancel_from' => $_REQUEST['opdc_cancelto'],
            'cancel_id' => $_REQUEST['cancel_id'][$mm],
            'cancel_qty' => $_REQUEST['cancel_qty'][$mm],
        );

        if ($_REQUEST['podc_cancel_det_id'][$mm] == "") {
            $qry = Insert('podc_cancel_det', $Ar);
        } else {
            $qry = Update('podc_cancel_det', $Ar, 'WHERE id = ' . $_REQUEST['podc_cancel_det_id'][$mm]);
        }
    }

    //   timeline_history('Insert', 'podc_cancel', mysqli_insert_id($mysqli), 'Line Added. Ref: '. $_REQUEST['podc_entry']);

    if ($qry) {
        $data = array('result' => 0);
    } else {
        $data = array('result' => 1);
    }

    echo json_encode($data);

} else if (isset($_REQUEST['newPermission'])) {

    $b = mysqli_query($mysqli, "SELECT * FROM user_group");

    while ($row = mysqli_fetch_array($b)) {

        $sel = mysqli_fetch_array(mysqli_query($mysqli, "SELECT id FROM user_permissions WHERE user_group = '" . $row['id'] . "' AND permission_name = '" . $_REQUEST['perm'] . "'"));

        $arr = array(
            'user_group' => $row['id'],
            'permission_name' => $_REQUEST['perm'],
            'value' => 0,
            'created_user' => $logUser,
        );

        if ($sel['id'] == "") {
            $ins = Insert('user_permissions', $arr);
        } else {
            $ins = Update('user_permissions', $arr, ' WHERE id = ' . $sel['id']);
        }
    }

    if ($ins) {
        $ret['result'][] = 0;
    } else {
        $ret['result'][] = 1;
    }

    echo json_encode($ret);

} else if (isset($_REQUEST['employee_check_in'])) {

    $arr = array(
        'date' => date('Y-m-d'),
        'employee_id' => $logUser,
        'in_time' => time(),
        'in_latitude' => $_REQUEST['inout_latitude'],
        'in_longitude' => $_REQUEST['inout_longitude'],
    );

    $ins = Insert('attendance', $arr);

    if ($ins) {
        timeline_history('Insert', 'attendance', mysqli_insert_id($mysqli), 'Check IN');
        $ret['result'][] = 0;
    } else {
        $ret['result'][] = 1;
    }

    echo json_encode($ret);

} else if (isset($_REQUEST['employee_check_out'])) {

    $arr = array(
        'out_time' => time(),
        'out_latitude' => $_REQUEST['inout_latitude'],
        'out_longitude' => $_REQUEST['inout_longitude'],
    );

    $ins = Update('attendance', $arr, ' WHERE id = "' . $_REQUEST['out_id'] . '"');

    if ($ins) {
        // timeline_history('Insert', 'attendance', mysqli_insert_id($mysqli), 'Check Out : at '. date('Y-m-d H:i:s') .', '. employee_name($logUser));
        $ret['result'][] = 0;
    } else {
        $ret['result'][] = 1;
    }

    echo json_encode($ret);

} else if (isset($_REQUEST['delete_podc_cancel'])) {

    $del = Delete('podc_cancel', 'WHERE id = ' . $_REQUEST['id']);
    $del = Delete('podc_cancel_det', 'WHERE podc_cancel_id = ' . $_REQUEST['id']);


    if ($del) {
        timeline_history('Delete', 'podc_cancel', $_REQUEST['id'], 'Fabric PO/ DC Deleted.');
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['stockgroup'])) {

    $data = array(
        'groupname' => $_REQUEST['groupname'],
        'assigneduser' => implode(',', $_REQUEST['assigneduser']),
    );

    $qry = Insert('stockgroup', $data);


    if ($qry) {
        timeline_history('Insert', 'stockgroup', mysqli_insert_id($mysqli), 'Stock Group Added' . $_REQUEST['groupname']);
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['saveInward'])) {

    $H = mysqli_fetch_array(mysqli_query($mysqli, "SELECT processing_code, boundle_id, id FROM processing_list WHERE id = '" . $_REQUEST['processing_list'] . "'"));
    // print_r($H['boundle_id']);

    $bId = explode(',', $H['boundle_id']);

    for ($i = 0; $i < count($bId); $i++) {
        $dta = array(
            'processing_id' => $_REQUEST['processing_list'],
            'bundle_id' => $bId[$i],
            'bundle_qr' => bundle_qr($bId[$i]),
            // 'pieces_qr' => implode(',', bundle_qr($bId[$i])),
        );

        // if (empty($_REQUEST['saved_id'][$i])) {
        // $qry = Insert('inwarded_bundle', $dta);
        //     $_SESSION['msg'] = "added";
        // } else {
        //     Update('inwarded_bundle', $dta, " WHERE id = '" . $_REQUEST['saved_id'][$i] . "'");
        //     $_SESSION['msg'] = "updated";
        // }

        $ndta = array(
            'complete_processing' => 'yes',
            'complete_processing_date' => date('Y-m-d H:i:s')
        );
        $qry = Update('bundle_details', $ndta, " WHERE id = '" . $bId[$i] . "'");
    }

    $dta2 = array(
        'is_inwarded' => 1,
        'dc_num' => $_REQUEST['dc_num'],
        'dc_date' => $_REQUEST['dc_date'],
    );

    $qry = Update('processing_list', $dta2, " WHERE id = '" . $_REQUEST['processing_list'] . "'");

    if ($qry) {
        timeline_history('Update', 'processing_list', $_REQUEST['processing_list'], 'Production process Inwarded! Ref: ' . $H['processing_code']);
        $data = array('result' => 0);
    } else {
        $data = array('result' => 1);
    }

    echo json_encode($data);

} else if (isset($_REQUEST['stockgroup_add'])) {

    $data = array(
        'groupname' => $_REQUEST['groupname'],
        'assigneduser' => implode(',', $_REQUEST['assigneduser']),
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    if ($_REQUEST['stockgroup_id'] == "") {

        $qry = Insert('stockgroup', $data);
        timeline_history('Insert', 'stockgroup', mysqli_insert_id($mysqli), 'Stock Group Added. Ref: ' . $_REQUEST['groupname']);
    } else {

        timeline_history('Update', 'stockgroup', $_REQUEST['stockgroup_id'], 'Stock Group Updated. Ref: ' . $_REQUEST['groupname']);
        $qry = Update('stockgroup', $data, 'WHERE id = ' . $_REQUEST['stockgroup_id']);
    }

    if ($qry) {
        $data = array('result' => 0);
    } else {
        $data = array('result' => 1);
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_stock_item'])) {

    $yarn = array();
    for ($m = 0; $m < count($_REQUEST['yarn_name']); $m++) {
        if ($_REQUEST['yarn_name'][$m] != "" && $_REQUEST['mixing_percentage'][$m] != "") {
            $yarn[] = $_REQUEST['yarn_name'][$m] . '==' . $_REQUEST['mixing_percentage'][$m];
        }
    }

    $data_arr = array(
        'fabric_type' => $_REQUEST['fabric_type'],
        'fabric_name' => $_REQUEST['fabric_name'],
        'gsm' => $_REQUEST['gsm'],
        'dying_color' => $_REQUEST['dying_color'],
        'aop_name' => $_REQUEST['aop_name'],
        'yarn_mixing' => json_encode($yarn),
    );

    $query = "SELECT * FROM mas_stockitem WHERE ";

    foreach ($data_arr as $key => $value) {
        if (!empty($value)) {
            if ($key === 'yarn_mixing') {
                $conditions[] = "JSON_CONTAINS(yarn_mixing, '$value')";
            } else {
                $conditions[] = "$key = '$value'";
            }
        }
    }

    $query .= implode(" AND ", $conditions);

    $num_count = mysqli_num_rows(mysqli_query($mysqli, $query));

    if ($num_count > 0) {
        $data['error'][] = 1;
        $data['message'][] = 'This Set Already Added!';
    } else {
        $data['error'][] = 0;
        $data['message'][] = '';

        $merge = array_merge($data_arr, array('created_by' => $logUser, 'created_unit' => $logUnit));
        $qry = Insert('mas_stockitem', $merge);
        timeline_history('Insert', 'mas_stockitem', mysqli_insert_id($mysqli), 'Stock Item Added. Ref: ' . $_REQUEST['fabric_name']);
    }


    if ($qry) {
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['delete_qrimage'])) {

    foreach (explode(',', $_REQUEST['value']) as $image) {
        if (is_file($image)) {
            unlink($image);
        }
    }

} else if (isset($_REQUEST['delete_barcodeimage'])) {

    foreach (explode(',', $_REQUEST['value']) as $image) {
        if (is_file($image)) {
            unlink($image);
        }
    }

} else if (isset($_REQUEST['save_linePlanning'])) {

    for ($m = 0; $m < count($_REQUEST['sod_part']); $m++) {

        $sod_part = $_REQUEST['sod_part'][$m];
        $typ = $_REQUEST['planningtype' . $sod_part];
        $prt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sod_part WHERE id = " . $sod_part));

        if ($typ == 'Full') {

            $ary = array(
                'order_id' => $_REQUEST['order_id'],
                'style_id' => $_REQUEST['style_id'],
                'sod_part' => $sod_part,
                'combo_id' => $prt['combo_id'],
                'part_id' => $prt['part_id'],
                'color_id' => $prt['color_id'],
                'planning_type' => $typ,
                'order_qty' => $_REQUEST['sod_part_qty' . $sod_part],
                'plan_qty' => $_REQUEST['sod_part_qty' . $sod_part],
                'assign_type' => $_REQUEST['assign_type' . $sod_part],
                'assign_to' => $_REQUEST['assign_to' . $sod_part],
                'created_by' => $logUser,
                'created_unit' => $logUnit,
            );

            if ($_REQUEST['line_planned'][$m] == "") {
                $ins = Insert('line_planning', $ary);
            } else {
                $ins = Update('line_planning', $ary, 'WHERE id = ' . $_REQUEST['line_planned'][$m]);
            }
        } else if ($typ == 'Partial') {

            $ary = array(
                'order_id' => $_REQUEST['order_id'],
                'style_id' => $_REQUEST['style_id'],
                'sod_part' => $sod_part,
                'combo_id' => $prt['combo_id'],
                'part_id' => $prt['part_id'],
                'color_id' => $prt['color_id'],
                'planning_type' => $typ,
                'created_by' => $logUser,
                'created_unit' => $logUnit,
            );

            if ($_REQUEST['line_planned'][$m] == "") {
                $ins = Insert('line_planning', $ary);
                $inidd = mysqli_insert_id($mysqli);
            } else {
                $ins = Update('line_planning', $ary, 'WHERE id = ' . $_REQUEST['line_planned'][$m]);
                $inidd = $_REQUEST['line_planned'][$m];
            }

            $v_value = $_REQUEST['variation_value' . $sod_part];

            for ($k = 0; $k < count($v_value); $k++) {

                $ary = array(
                    'line_planning_id' => $inidd,
                    'sod_size' => $_REQUEST['sod_size' . $sod_part][$k],
                    'variation_value' => $_REQUEST['variation_value' . $sod_part][$k],
                    'order_qty' => $_REQUEST['order_qty' . $sod_part][$k],
                    'plan_qty' => $_REQUEST['plan_qty' . $sod_part][$k],
                    'assign_type' => $_REQUEST['assign_type_sub' . $sod_part][$k],
                    'assign_to' => $_REQUEST['assign_to_sub' . $sod_part][$k],
                    'created_by' => $logUser,
                    'created_unit' => $logUnit,
                );

                if ($_REQUEST['plan_qty' . $sod_part][$k] > 0) {
                    if ($_REQUEST['size_planned' . $sod_part][$k] == "") {
                        $ins = Insert('line_planning_size', $ary);
                        // $inidd = mysqli_insert_id($mysqli);
                    } else {
                        $ins = Update('line_planning_size', $ary, 'WHERE id = ' . $_REQUEST['size_planned' . $sod_part][$k]);
                        // $inidd = $_REQUEST['size_planned'. $sod_part][$k];
                    }
                }
            }
        }
    }

    if ($ins) {
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_process_outward'])) {

    if ($_REQUEST['processing_id'] == "") {

        $data = array(
            'processing_code' => $_REQUEST['processing_code'],
            'order_id' => $_REQUEST['order_id'],
            'scanning_type' => 'bundle',
            'scanning_using' => $_REQUEST['scanning_using'],
            'entry_date' => date('Y-m-d'),
            'input_type' => $_REQUEST['input_type'],
            'process_id' => $_REQUEST['process_id'],
            'assigned_emp' => $_REQUEST['line'],
            'boundle_id' => $_REQUEST['already_scanned'],
            'bundle_track' => NULL,
            'type' => 'process_outward',
            'created_by' => $logUser,
            'created_unit' => $logUnit,
        );

        $inss = Insert('processing_list', $data);
        $inid = mysqli_insert_id($mysqli);
        timeline_history('Insert', 'processing_list', $inid, 'Process Outward DC Created. Ref: ' . $_REQUEST['processing_code']);
    } else {

        if (!empty($_REQUEST['already_scanned'])) {
            $kb = mysqli_fetch_array(mysqli_query($mysqli, "SELECT boundle_id FROM processing_list WHERE id = '" . $_REQUEST['processing_id'] . "'"));

            $data = array(
                'entry_date' => $_REQUEST['entry_date'],
                'input_type' => $_REQUEST['input_type'],
                'assigned_emp' => $_REQUEST['line'],
                'boundle_id' => implode(',', array_merge(explode(',', $kb['boundle_id']), explode(',', $_REQUEST['already_scanned']))),
            );

            $inss = Update('processing_list', $data, " WHERE id = '" . $_REQUEST['processing_id'] . "'");
            timeline_history('Update', 'processing_list', $_REQUEST['processing_id'], 'Sewing Input DC Updated. Ref: ' . $_REQUEST['processing_code']);
            $inid = $_REQUEST['processing_id'];
        } else {
            $inss = 1;
        }
    }

    if (isset($_REQUEST['boundle_id'])) {
        for ($m = 0; $m < count($_REQUEST['boundle_id']); $m++) {

            $bnl = $_REQUEST['boundle_id'][$m];
            $nnvb = mysqli_fetch_array(mysqli_query($mysqli, "SELECT pcs_per_bundle FROM bundle_details WHERE id = " . $bnl));

            $rr = range(1, $nnvb['pcs_per_bundle']);

            $nar = array(
                'in_proseccing' => 'yes',
                'in_proseccing_id' => $inid,
                'in_proseccing_date' => date('Y-m-d'),
            );

            Update('bundle_details', $nar, " WHERE id = '" . $bnl . "'");
        }
    }

    if ($inss) {
        $resp['result'][] = 0;
    } else {
        $resp['result'][] = 1;
    }

    echo json_encode($resp);


} else if (isset($_REQUEST['save_sewing_input'])) {

    if ($_REQUEST['processing_id'] == "") {

        $data = array(
            'processing_code' => $_REQUEST['processing_code'],
            'order_id' => $_REQUEST['order_id'],
            'scanning_type' => 'bundle',
            'scanning_using' => $_REQUEST['scanning_using'],
            'entry_date' => $_REQUEST['entry_date'],
            'input_type' => $_REQUEST['input_type'],
            'assigned_emp' => $_REQUEST['line'],
            'boundle_id' => $_REQUEST['already_scanned'],
            'bundle_track' => NULL,
            'type' => 'sewing_input',
            'created_by' => $logUser,
            'created_unit' => $logUnit,
        );

        $inss = Insert('processing_list', $data);
        $inid = mysqli_insert_id($mysqli);
        timeline_history('Insert', 'processing_list', $inid, 'Sewing Input DC Created. Ref: ' . $_REQUEST['processing_code']);
    } else {

        if (!empty($_REQUEST['already_scanned'])) {
            $kb = mysqli_fetch_array(mysqli_query($mysqli, "SELECT boundle_id FROM processing_list WHERE id = '" . $_REQUEST['processing_id'] . "'"));

            $data = array(
                'entry_date' => $_REQUEST['entry_date'],
                'input_type' => $_REQUEST['input_type'],
                'assigned_emp' => $_REQUEST['line'],
                'boundle_id' => implode(',', array_merge(explode(',', $kb['boundle_id']), explode(',', $_REQUEST['already_scanned']))),
            );

            $inss = Update('processing_list', $data, " WHERE id = '" . $_REQUEST['processing_id'] . "'");
            timeline_history('Update', 'processing_list', $_REQUEST['processing_id'], 'Sewing Input DC Updated. Ref: ' . $_REQUEST['processing_code']);
            $inid = $_REQUEST['processing_id'];
        } else {
            $inss = 1;
        }
    }

    if (isset($_REQUEST['boundle_id'])) {
        for ($m = 0; $m < count($_REQUEST['boundle_id']); $m++) {

            $bnl = $_REQUEST['boundle_id'][$m];
            // print "SELECT pcs_per_bundle FROM bundle_details WHERE id = ". $bnl;
            $nnvb = mysqli_fetch_array(mysqli_query($mysqli, "SELECT pcs_per_bundle FROM bundle_details WHERE id = " . $bnl));

            $rr = range(1, $nnvb['pcs_per_bundle']);

            $nar = array(
                'in_sewing' => 'yes',
                'input_type' => $_REQUEST['input_type'],
                'line' => $_REQUEST['line'],
                'in_sewing_id' => $inid,
                'in_sewing_date' => date('Y-m-d H:i:s'),
                'ch_missing_pcs' => implode(',', $rr),
                'tot_ironing' => implode(',', $rr),
                'tot_packing' => implode(',', $rr),
            );

            Update('bundle_details', $nar, " WHERE id = '" . $bnl . "'");
        }
    }

    if ($inss) {
        $resp['result'][] = 0;
    } else {
        $resp['result'][] = 1;
    }

    echo json_encode($resp);

} else if (isset($_REQUEST['remove_bundle_process_outward'])) {

    $pid = $_REQUEST['processing_id'];
    $bid = $_REQUEST['id'];

    $p_list = mysqli_fetch_array(mysqli_query($mysqli, "SELECT boundle_id FROM processing_list WHERE id=" . $pid));

    $sv = explode(',', $p_list['boundle_id']);

    $array = array_diff($sv, array($_REQUEST['id']));

    $array = array_values($array);

    $upp = Update('processing_list', array('boundle_id' => implode(',', $array)), 'WHERE id = ' . $pid);

    if ($upp) {

        $upp = mysqli_query($mysqli, "UPDATE bundle_details SET in_proseccing_date = NULL, in_proseccing_id = NULL, in_proseccing = NULL WHERE id = '" . $bid . "' ");

        if ($upp) {
            $resp['result'][] = 0;
        } else {
            $resp['result'][] = 1;
        }
    }

    echo json_encode($resp);


} else if (isset($_REQUEST['remove_bundle'])) {

    $pid = $_REQUEST['processing_id'];
    $bid = $_REQUEST['id'];

    $p_list = mysqli_fetch_array(mysqli_query($mysqli, "SELECT boundle_id FROM processing_list WHERE id=" . $pid));

    $sv = explode(',', $p_list['boundle_id']);

    $array = array_diff($sv, array($_REQUEST['id']));

    $array = array_values($array);

    $upp = Update('processing_list', array('boundle_id' => implode(',', $array)), 'WHERE id = ' . $pid);

    if ($upp) {
        $nar = array(
            'in_sewing' => NULL,
            'input_type' => NULL,
            'line' => NULL,
            'in_sewing_id' => NULL,
            'in_sewing_date' => NULL,
        );

        $upp = mysqli_query($mysqli, "UPDATE bundle_details SET in_sewing = NULL, input_type = NULL, line = NULL, in_sewing_id = NULL, in_sewing_date = NULL WHERE id = '" . $bid . "' ");

        if ($upp) {
            $resp['result'][] = 0;
        } else {
            $resp['result'][] = 1;
        }
    }

    echo json_encode($resp);

} else if (isset($_REQUEST['save_linePlanning'])) {

    for ($m = 0; $m < count($_REQUEST['sod_part']); $m++) {

        $sod_part = $_REQUEST['sod_part'][$m];
        $typ = $_REQUEST['planningtype' . $sod_part];
        $prt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sod_part WHERE id = " . $sod_part));

        if ($typ == 'Full') {
            $ary = array(
                'order_id' => $_REQUEST['order_id'],
                'style_id' => $_REQUEST['style_id'],
                'sod_part' => $sod_part,
                'combo_id' => $prt['combo_id'],
                'part_id' => $prt['part_id'],
                'color_id' => $prt['color_id'],
                'planning_type' => $typ,
                'order_qty' => $_REQUEST['sod_part_qty' . $sod_part],
                'plan_qty' => $_REQUEST['sod_part_qty' . $sod_part],
                'assign_type' => $_REQUEST['assign_type' . $sod_part],
                'assign_to' => $_REQUEST['assign_to' . $sod_part],
                'created_by' => $logUser,
                'created_unit' => $logUnit,
            );
            if ($_REQUEST['line_planned'][$m] == "") {
                $ins = Insert('line_planning', $ary);
            } else {
                $ins = Update('line_planning', $ary, 'WHERE id = ' . $_REQUEST['line_planned'][$m]);
            }
        } else if ($typ == 'Partial') {

            $ary = array(
                'order_id' => $_REQUEST['order_id'],
                'style_id' => $_REQUEST['style_id'],
                'sod_part' => $sod_part,
                'combo_id' => $prt['combo_id'],
                'part_id' => $prt['part_id'],
                'color_id' => $prt['color_id'],
                'planning_type' => $typ,
                'created_by' => $logUser,
                'created_unit' => $logUnit,
            );
            if ($_REQUEST['line_planned'][$m] == "") {
                $ins = Insert('line_planning', $ary);
                $inidd = mysqli_insert_id($mysqli);
            } else {
                $ins = Update('line_planning', $ary, 'WHERE id = ' . $_REQUEST['line_planned'][$m]);
                $inidd = $_REQUEST['line_planned'][$m];
            }
            $v_value = $_REQUEST['variation_value' . $sod_part];

            for ($k = 0; $k < count($v_value); $k++) {
                $ary = array(
                    'line_planning_id' => $inidd,
                    'sod_size' => $_REQUEST['sod_size' . $sod_part][$k],
                    'variation_value' => $_REQUEST['variation_value' . $sod_part][$k],
                    'order_qty' => $_REQUEST['order_qty' . $sod_part][$k],
                    'plan_qty' => $_REQUEST['plan_qty' . $sod_part][$k],
                    'assign_type' => $_REQUEST['assign_type_sub' . $sod_part][$k],
                    'assign_to' => $_REQUEST['assign_to_sub' . $sod_part][$k],
                    'created_by' => $logUser,
                    'created_unit' => $logUnit,
                );

                if ($_REQUEST['plan_qty' . $sod_part][$k] > 0) {
                    if ($_REQUEST['size_planned' . $sod_part][$k] == "") {
                        $ins = Insert('line_planning_size', $ary);
                        // $inidd = mysqli_insert_id($mysqli);
                    } else {
                        $ins = Update('line_planning_size', $ary, 'WHERE id = ' . $_REQUEST['size_planned' . $sod_part][$k]);
                        // $inidd = $_REQUEST['size_planned'. $sod_part][$k];
                    }
                }
            }
        }
    }


    if ($ins) {
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_fab_opening'])) {

    
    $so = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_detalis WHERE id = '". $_REQUEST['order_id'] ."'"));

    $arr = array(
        'entry_number' => $_REQUEST['entry_number'],
        'entry_date' => $_REQUEST['entry_date'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );
 
    if (empty($_REQUEST['opening_id'])) {
        $ins = Insert('fabric_opening', $arr);
        $opening_id = mysqli_insert_id($mysqli);
    } else {
        $ins = Update('fabric_opening', $arr, 'WHERE id = ' . $_REQUEST['opening_id']);
        $opening_id = $_REQUEST['opening_id'];
    }

    $fld = ($_REQUEST['stock_bo'] == 'bo') ? 'po_balance' : 'stock_dia';
    $val = ($_REQUEST['stock_bo'] == 'bo') ? $_REQUEST['po_balance'] : $_REQUEST['stock_dia'];

    $det = array(
        'fabric_opening_id' => $opening_id,
        'stock_bo' => $_REQUEST['stock_bo'],
        'order_id' => $so['sales_order_id'],
        'style_id' => $_REQUEST['order_id'],
        'po_stage' => $_REQUEST['po_stage'],
        'material_name' => $_REQUEST['material_name'],
        $fld => $val,
        'bag_roll' => $_REQUEST['bag_roll'],
        'opening_qty' => $_REQUEST['opening_qty'],
    );

    $ins = Insert('fabric_opening_det', $det);
    $detId = mysqli_insert_id($mysqli);


    // fabric stock opening
    if($_REQUEST['stock_bo']=='bo') {
        $reqq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_requirements WHERE id = " . $_REQUEST['material_name']));
        $yfab = ($reqq['yarn_id'] != NULL) ? 'yarn_id' : 'fabric_id';
        $yfab_val = ($reqq['yarn_id'] != NULL) ? $reqq['yarn_id'] : $reqq['fabric_id'];
    } else {
        
        $reqq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM mas_stockitem WHERE id = " . $_REQUEST['material_name']));
        $yfab = 'fabric_id';
        $yfab_val = $reqq['fabric_name'];
    }

    $stok = array(
        'fabric_requirements' => $reqq['id'],
        'order_id' => $so['sales_order_id'],
        'style_id' => $_REQUEST['order_id'],
        'process_id' => $_REQUEST['po_stage'],
        // 'process_order' => '',
        $yfab => $yfab_val,

        'stock_bo' => $_REQUEST['stock_bo'],
        'stock_from' => 'fabric_opening_det',
        'stock_id' => $detId,
        'entry_grn_number' => $_REQUEST['entry_number'],
        // 'req_bag_roll' => $_REQUEST['bag_roll'],
        // 'req_qty' => $_REQUEST['po_qty_wt'],
        'received_bag' => $_REQUEST['bag_roll'],
        'received_qty' => $_REQUEST['opening_qty'],
        'stock_qty' => $_REQUEST['opening_qty'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );
    
    $ins = Insert('fabric_stock', $stok);

    if ($ins) {
        $data['result'][] = 0;
        $data['opening_id'][] = $opening_id;
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if(isset($_REQUEST['new_del'])) {

    $s = range(1, 10);

    foreach($s as $idd) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://benso.app/ajax_action.php?delete_data='. $idd .'&table=timeline_history");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://example.com");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {

            echo $response;
        }

        // Close the cURL session
        curl_close($ch);
    }
}


// timeline_history('Insert', 'employee_detail_temp', $_REQUEST['id'], 'Employee Request Rejected.');
?>