<?php
include ("includes/connection.php");
include ("includes/function.php");

// if (!isset($_SESSION['login_id'])) {
// 	header('Location:index.php');
// }

if (isset($_REQUEST['delete_salesOrder'])) {
    // echo '<pre>', print_r($_REQUEST, 1);
    $del = Delete('sales_order', 'WHERE id = ' . $_REQUEST['id']);
    $del = Delete('sales_order_detalis', 'WHERE sales_order_id = ' . $_REQUEST['id']);
    $del = Delete('sod_size', 'WHERE sales_order_id = ' . $_REQUEST['id']);
    $del = Delete('sod_combo', 'WHERE sales_order_id = ' . $_REQUEST['id']);

    if ($del) {
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['delete_data'])) {

    if ($_REQUEST['table'] == 'category') {
        $a = "SELECT * FROM category WHERE id='" . $_REQUEST['delete_data'] . "'";
        $aa = mysqli_query($mysqli, $a);
        $aaa = mysqli_fetch_array($aa);
        if (!empty($aaa['image'])) {
            unlink("uploads/category/" . $aaa['image']);
        }
    }

    $qry = Delete($_REQUEST['table'], " WHERE id = '" . $_REQUEST['delete_data'] . "'");

    timeline_history('Delete', $_REQUEST['table'], $_REQUEST['delete_data'], 'Row Deleted. (' . $_REQUEST['table'] . ')');

    if ($qry) {
        print 0;
    } else {
        print 1;
    }
} else if (isset($_REQUEST['save_category'])) {

    if (!empty($_FILES['category_image']['name'])) {
        $uploaddir = 'uploads/category/';
        $uploadfile = $uploaddir . basename($_FILES['category_image']['name']);
        move_uploaded_file($_FILES['category_image']['tmp_name'], $uploadfile);
    }
    $data = array(
        'category_name' => $_REQUEST['category_name'],
        'image' => $_FILES['category_image']['name'],
        'create_date' => date('Y-m-d H:i:s')
    );
    $inss = Insert('category', $data);
    $iid = mysqli_insert_id($mysqli);

    timeline_history('Insert', 'category', $iid, 'Category Master Inserted');

    if ($inss == true) {

        for ($l = 0; $l < count($_REQUEST['process_list']); $l++) {
            $upl = mysqli_query($mysqli, "UPDATE process SET category= '" . $iid . "' WHERE id='" . $_REQUEST['process_list'][$l] . "'");
        }

        for ($k = 0; $k < count($_REQUEST['sub_process_list']); $k++) {
            $upl = mysqli_query($mysqli, "UPDATE sub_process SET category= '" . $iid . "' WHERE id='" . $_REQUEST['sub_process_list'][$k] . "'");
        }

        $_SESSION['msg'] = "saved";

        header("Location:view-category.php");
        exit;
    } else {
        $_SESSION['msg'] = "error";

        header("Location:view-category.php?errdd");
        exit;
    }
} else if (isset($_REQUEST['edit_category'])) {

    $qry = "SELECT * FROM category WHERE id='" . $_REQUEST['category_id'] . "'";
    $qrry = mysqli_query($mysqli, $qry);
    $sqll = mysqli_fetch_array($qrry);

    if (!empty($_FILES['category_image']['name'])) {
        $uploaddir = 'uploads/category/';
        $uploadfile = $uploaddir . basename($_FILES['category_image']['name']);
        move_uploaded_file($_FILES['category_image']['tmp_name'], $uploadfile);

        $pic = $_FILES['category_image']['name'];
    } else {
        $pic = $sqll['image'];
    }

    $data = array(
        'category_name' => $_REQUEST['category_name'],
        'image' => $pic,
        'create_date' => date('Y-m-d H:i:s')
    );

    $inss = Update('category', $data, " WHERE id = '" . $_REQUEST['category_id'] . "'");

    timeline_history('Update', 'category', $_REQUEST['category_id'], 'Category Master Updated');

    if ($inss == true) {

        for ($l = 0; $l < count($_REQUEST['process_list']); $l++) {
            $upl = mysqli_query($mysqli, "UPDATE process SET category= '" . $_REQUEST['category_id'] . "' WHERE id='" . $_REQUEST['process_list'][$l] . "'");
        }

        for ($k = 0; $k < count($_REQUEST['sub_process_list']); $k++) {
            $upl = mysqli_query($mysqli, "UPDATE sub_process SET category= '" . $_REQUEST['category_id'] . "' WHERE id='" . $_REQUEST['sub_process_list'][$k] . "'");
        }

        $_SESSION['msg'] = "updated";

        header("Location:view-category.php");
        exit;
    } else {
        $_SESSION['msg'] = "error";

        header("Location:view-category.php?errdd");
        exit;
    }
} else if (isset($_REQUEST['savetempvariation'])) {

    $ol = mysqli_query($mysqli, "SELECT * FROM size_details ");
    $ko = mysqli_num_rows($ol);
    $kos = mysqli_fetch_array($ol);

    for ($m = 0; $m < count($_REQUEST['variation_value_id']); $m++) {
        $val[] = 'variation_value_id=' . $_REQUEST['variation_value_id'][$m] . ',,quantity=' . $_REQUEST['varvalue'][$m];
    }

    if (mysqli_num_rows($ol) == 0) {
        $data = array(
            'size_detail' => json_encode($val),
            'so_id' => $_REQUEST['so_id'],
        );

        $inss = Update('size_details', $data, " WHERE id = 1");

    } else {
        $data1 = array(
            'size_detail' => json_encode($val),
        );
        $inss = Update('size_details', $data1, " WHERE id = 1");
    }
    if ($inss) {
        echo 0;
    } else {
        echo 1;
    }
} else if (isset($_REQUEST['saveSalesOrder'])) {

    if (!empty($_REQUEST['salesOrderId'])) {
        $sel = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order WHERE id=" . $_REQUEST['salesOrderId']));
    }
    if (!empty($_FILES['imagefile']['name'])) {
        if (!is_dir("uploads/so_img/" . $_REQUEST['so_id'] . "/")) {
            mkdir("uploads/so_img/" . $_REQUEST['so_id'] . "/");
        }

        $uploaddir = 'uploads/so_img/' . $_REQUEST['so_id'] . '/';
        $uploadfile = $uploaddir . basename($_FILES['imagefile']['name']);
        move_uploaded_file($_FILES['imagefile']['tmp_name'], $uploadfile);

        $pics = $_FILES['imagefile']['name'];
    } else {
        $pics = $sel['order_image'];
    }

    $data = array(
        'order_code' => $_REQUEST['so_id'],
        'order_date' => $_REQUEST['order_date'],
        'delivery_date' => $_REQUEST['deliveryDate'],
        'type' => $_REQUEST['type'],
        'brand' => $_REQUEST['brand'],
        'merchandiser' => $_REQUEST['merchand'],
        'pack_type' => $_REQUEST['pack_type'],
        'po_num' => $_REQUEST['po_number'],
        'customer_id' => $_REQUEST['customer_id'],
        'delivery_address' => $_REQUEST['delivery_address'],
        'order_image' => $pics,
        'created_date' => date('Y-m-d H:i:s'),
    );

    if (empty($_REQUEST['salesOrderId'])) {
        $inss = Insert('sales_order', $data);
        $inid = mysqli_insert_id($mysqli);
        $_SESSION['msg'] = "saved";
        $ret = "sales_order_list.php";
    } else {
        $inss = Update('sales_order', $data, " WHERE id = '" . $_REQUEST['salesOrderId'] . "'");
        $inid = $_REQUEST['salesOrderId'];
        $_SESSION['msg'] = "saved";
        $ret = "sales_order_list.php";
    }

    for ($p = 0; $p < count($_REQUEST['editItemId']); $p++) {
        if ($_REQUEST['can_update'][$p] != "") {

            $qryz = mysqli_query($mysqli, "SELECT * FROM size_details_edit WHERE itemlist_id=" . $_REQUEST['editItemId'][$p]);
            $msq = mysqli_fetch_array($qryz);

            $sel = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.*, b.order_code FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id=b.id WHERE a.id=" . $_REQUEST['editItemId'][$p]));

            if (!empty($_FILES['edit_itenPic']['name'][$p])) {
                if (!is_dir("uploads/so_img/" . $sel['order_code'] . "/")) {
                    mkdir("uploads/so_img/" . $sel['order_code'] . "/");
                }

                $uploaddir = 'uploads/so_img/' . $sel['order_code'] . '/';
                $uploadfile = $uploaddir . basename($_FILES['edit_itenPic']['name'][$p]);
                move_uploaded_file($_FILES['edit_itenPic']['tmp_name'][$p], $uploadfile);

                $item_image = $_FILES['edit_itenPic']['name'][$p];
            } else {
                $item_image = $sel['item_image'];
            }

            $ed_data = array(
                'item_id' => $_REQUEST['ed_itm_val'][$p],
                'size_detail' => $msq['size_detail'],
                'total_qty' => $_REQUEST['ed_qtty'][$p],
                'unit_id' => $_REQUEST['ed_unit'][$p],
                'price' => $_REQUEST['ed_price'][$p],
                'total' => $_REQUEST['ed_total'][$p],
                'item_image' => $item_image,
                'delivery_date' => $_REQUEST['editdeliveryDate'][$p],
            );

            $inss = Update('sales_order_detalis', $ed_data, " WHERE id = '" . $_REQUEST['editItemId'][$p] . "'");

            $ret = "sales_order.php?id=" . $inid . "";
        }
    }

    if (!empty($_REQUEST['item_id'])) {
        $sqql = "SELECT * FROM size_details";
        $nmni = mysqli_query($mysqli, $sqql);
        $nmv = mysqli_fetch_array($nmni);

        $so_id = $_REQUEST['so_id'];

        if (!empty($_FILES['item_image']['name'])) {
            if (!is_dir("uploads/so_img/" . $so_id . "/")) {
                mkdir("uploads/so_img/" . $so_id . "/");
            }

            $uploaddir = 'uploads/so_img/' . $so_id . '/';
            $uploadfile = $uploaddir . basename($_FILES['item_image']['name']);
            move_uploaded_file($_FILES['item_image']['tmp_name'], $uploadfile);
        }

        $item_image = $_FILES['item_image']['name'];

        $data1 = array(
            'sales_order_id' => $inid,
            'item_id' => $_REQUEST['item_id'],
            'style_no' => $_REQUEST['styleno'],
            'size_detail' => $nmv['size_detail'],
            'total_qty' => $_REQUEST['quantity'],
            'unit_id' => $_REQUEST['unitt'],
            'price' => $_REQUEST['price'],
            'total' => $_REQUEST['total'],
            'delivery_date' => $_REQUEST['deliv_date'],
            'item_image' => $item_image,
        );
        $inss = Insert('sales_order_detalis', $data1);
        $rttt = mysqli_insert_id($mysqli);

        if ($inss) {
            $odt = array(
                'itemlist_id' => $rttt,
                'size_detail' => $nmv['size_detail'],
            );

            Insert('size_details_edit', $odt);

            $dats = array('size_detail' => '');
            Update('size_details', $dats, ' WHERE id = 1');
            mysqli_query($mysqli, $sqol);
            $ret = "sales_order.php?id=" . $inid . "";
        }
    }

    header("Location:" . $ret . "");
    exit;

} else if (isset($_REQUEST['saveitemlist'])) {

    $sqql = "SELECT * FROM size_details";
    $nmni = mysqli_query($mysqli, $sqql);
    $nmv = mysqli_fetch_array($nmni);

    $data = array(
        'sales_order_id' => $_REQUEST['salesOrderId'],
        'item_id' => $_REQUEST['item_id'],
        'style_no' => $_REQUEST['styleno'],
        'size_detail' => $nmv['size_detail'],
        'total_qty' => $_REQUEST['quantity'],
        'unit_id' => $_REQUEST['unitt'],
        'price' => $_REQUEST['price'],
        'total' => $_REQUEST['total'],
        'delivery_date' => $_REQUEST['deliv_date'],
    );

    $inss = Insert('sales_order_detalis', $data);
    $inid = mysqli_insert_id($mysqli);

    if ($inss) {

        $dats = array('size_detail' => '');
        Update('size_details', $dats, ' WHERE id = 1');
        mysqli_query($mysqli, $sqol);
    }

    if ($inss) {
        $json['result'] = 'saved';
        $json['url'] = 'sales_order.php?id=' . $inid;
    } else {
        $json['result'] = 'error';
    }
    echo json_encode($json);

} else if (isset($_REQUEST['approve_bill'])) {

    $data = array('bill_status' => $_REQUEST['is_approved']);

    $inss = Update('bill_passing', $data, " WHERE id = '" . $_REQUEST['id'] . "'");

    timeline_history('Update', 'bill_passing', $_REQUEST['id'], 'Bill Approved. Ref: "'. $_REQUEST['ref_id'] .'"');

    if ($inss) {
        $json['result'] = 'saved';
    } else {
        $json['result'] = 'error';
    }
    echo json_encode($json);

} else if (isset($_REQUEST['approve_so'])) {

    $data = array('is_approved' => $_REQUEST['is_approved']);

    $inss = Update('sales_order', $data, " WHERE id = '" . $_REQUEST['id'] . "'");

    timeline_history('Update', 'sales_order', $_REQUEST['id'], 'Sales Order Approved. Ref: ' . sales_order_code($_REQUEST['id']));

    if ($inss) {
        $json['result'] = 'saved';
    } else {
        $json['result'] = 'error';
    }
    echo json_encode($json);

} else if (isset($_REQUEST['edititembtn'])) {

    $qryz = mysqli_query($mysqli, "SELECT * FROM size_details_edit WHERE itemlist_id=" . $_REQUEST['size_details_edit']);
    $msq = mysqli_fetch_array($qryz);

    for ($m = 0; $m < count($_REQUEST['ed_var_value']); $m++) {
        $val[] = 'variation_value_id=' . $_REQUEST['ed_var_value'][$m] . ',,quantity=' . $_REQUEST['ed_various'][$m];
    }

    $data = array(
        'size_detail' => json_encode($val),
        'itemlist_id' => $_REQUEST['size_details_edit'],
    );
    $data1 = array(
        'size_detail' => json_encode($val),
    );
    if ($msq == 0) {
        $inss = Insert('size_details_edit', $data);
    } else {
        $inss = Update('size_details_edit', $data1, " WHERE itemlist_id = '" . $_REQUEST['size_details_edit'] . "'");
    }
    if ($inss) {
        $json['result'] = 'saved';
    } else {
        $json['result'] = 'error';
    }
    echo json_encode($json);

} else if (isset($_REQUEST['changeStatus'])) {

    $upd = mysqli_query($mysqli, "UPDATE " . $_REQUEST['table'] . " SET is_active='" . $_REQUEST['status'] . "' WHERE id='" . $_REQUEST['id'] . "'");

    timeline_history('UPDATE', $_REQUEST['table'], $_REQUEST['id'], 'Active Status Updated. (' . $_REQUEST['table'] . ') Status : ' . $_REQUEST['line_name']);

    if ($upd) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['quality_status'])) {

    $upd = mysqli_query($mysqli, "UPDATE processing_list SET quality_approval ='" . $_REQUEST['status'] . "' WHERE id='" . $_REQUEST['id'] . "'");

    if ($upd) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['saveSO'])) {
    // echo '<pre>', print_r($_REQUEST);
// echo '<pre>', print_r($_FILES);
    if (!empty($_REQUEST['salesOrderId'])) {
        $sel = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order WHERE id=" . $_REQUEST['salesOrderId']));
    }
    if (isset($_FILES['imagefile'])) {
        for ($io = 0; $io < count($_FILES['imagefile']['name']); $io++) {
            if (!empty($_FILES['imagefile']['name'][$io])) {
                if (!is_dir("uploads/so_img/" . $_REQUEST['so_id'] . "/")) {
                    mkdir("uploads/so_img/" . $_REQUEST['so_id'] . "/");
                }

                $uploaddir = 'uploads/so_img/' . $_REQUEST['so_id'] . '/';
                $uploadfile = $uploaddir . basename($_FILES['imagefile']['name'][$io]);
                move_uploaded_file($_FILES['imagefile']['tmp_name'][$io], $uploadfile);

                $pics[] = $uploadfile;
            } else {
                $pics[] = $sel['order_image'];
            }
        }
    }
    $data = array(
        'order_date' => $_REQUEST['order_date'],
        'order_code' => $_REQUEST['so_id'],
        'delivery_date' => $_REQUEST['deliveryDate'],
        'type' => $_REQUEST['type'],
        'brand' => $_REQUEST['brand'],
        'merchandiser' => $_REQUEST['merchand'],
        'merch_id' => $_REQUEST['merch_id'],
        'currency' => $_REQUEST['currency'],
        'season' => $_REQUEST['season'],
        'order_image' => implode(',', $pics),
        'approvals' => implode(',', $_REQUEST['buy_approvals']),
        'order_days' => $_REQUEST['del_days'],
        'template_id' => $_REQUEST['template_id'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
        'created_date' => date('Y-m-d H:i:s'),
        'fin_year' => get_setting_val('FIN_YEAR'),
    );

    $data1 = array(
        'order_code' => $_REQUEST['so_id'],
        'delivery_date' => $_REQUEST['deliveryDate'],
        'type' => $_REQUEST['type'],
        'brand' => $_REQUEST['brand'],
        'merchandiser' => $_REQUEST['merchand'],
        'merch_id' => $_REQUEST['merch_id'],
        'currency' => $_REQUEST['currency'],
        'season' => $_REQUEST['season'],
        'order_image' => implode(',', $pics),
        'approvals' => implode(',', $_REQUEST['buy_approvals']),
        'order_days' => $_REQUEST['del_days'],
        'template_id' => $_REQUEST['template_id'],
    );

    if ($_REQUEST['salesOrderId'] == "") {
        $redirect = "sales_order_list.php";
        $qry = Insert('sales_order', $data);

        $sales_order = mysqli_insert_id($mysqli);

        timeline_history('Insert', 'sales_order', $sales_order, 'Sales Order Created. Ref: ' . $_REQUEST['so_id']);
    } else {
        $redirect = "sales_order_list.php";
        $qry = Update('sales_order', $data1, " WHERE id = '" . $_REQUEST['salesOrderId'] . "'");

        timeline_history('Update', 'sales_order', $_REQUEST['salesOrderId'], 'SO Updated.');

        $sales_order = $_REQUEST['salesOrderId'];
    }


    $so_id = $_REQUEST['so_id'];

    if (!empty($_FILES['item_image']['name'])) {
        if (!is_dir("uploads/so_img/" . $so_id . "/")) {
            mkdir("uploads/so_img/" . $so_id . "/");
        }

        $uploaddir = 'uploads/so_img/' . $so_id . '/';
        $uploadfile = $uploaddir . basename($_FILES['item_image']['name']);
        move_uploaded_file($_FILES['item_image']['tmp_name'], $uploadfile);
    }

    $item_image = $uploadfile;

    if ($_REQUEST['quantity'] > 0 && $_REQUEST['styleno'] != "") {

        $data1 = array(
            'sales_order_id' => $sales_order,
            'style_no' => $_REQUEST['styleno'],
            // 'size_detail' => json_encode($val),
            // 'part_detail' => json_encode($pack_det),
            'total_excess' => ($_REQUEST['quantity'] + (($_REQUEST['excess'] / 100) * $_REQUEST['quantity'])),
            'total_qty' => $_REQUEST['quantity'],
            'unit_id' => $_REQUEST['unitt'],
            'price' => $_REQUEST['price'],
            'po_num' => $_REQUEST['poNum'],
            'excess' => $_REQUEST['excess'],
            'delivery_date' => $_REQUEST['deliv_date'],
            'main_fabric' => $_REQUEST['main_fabric'],
            'style_des' => $_REQUEST['style_des'],
            'gsm' => $_REQUEST['gsm'],
            'item_image' => $item_image,
        );

        $qry = Insert('sales_order_detalis', $data1);
        $sl_inid = mysqli_insert_id($mysqli);

        if (isset($_REQUEST['combo_id'])) {

            for ($pp = 0; $pp < count($_REQUEST['combo_id']); $pp++) {
                $cid = $_REQUEST['combo_id'][$pp];

                $comp_arr = array(
                    'sales_order_id' => $sales_order,
                    'sales_order_detail_id' => $sl_inid,
                    'combo_id' => $_REQUEST['combo_name' . $cid],
                    'pack_id' => $_REQUEST['pack_name' . $cid],
                );

                $comp_arr = Insert('sod_combo', $comp_arr);
                $cmid = mysqli_insert_id($mysqli);

                for ($ii = 0; $ii < count($_REQUEST['part_name' . $cid]); $ii++) {

                    $part_array = array(
                        'sales_order_id' => $sales_order,
                        'sales_order_detail_id' => $sl_inid,
                        'sod_combo' => $cmid,
                        'combo_id' => $_REQUEST['combo_name' . $cid],
                        'pack_id' => $_REQUEST['pack_name' . $cid],
                        'part_id' => $_REQUEST['part_name' . $cid][$ii],
                        'color_id' => $_REQUEST['part_color' . $cid][$ii],
                    );

                    $in_part = Insert('sod_part', $part_array);
                }

                for ($oo = 0; $oo < count($_REQUEST['variation_value_id' . $cid]); $oo++) {

                    $siz_arr = array(
                        'sales_order_id' => $sales_order,
                        'sales_order_detail_id' => $sl_inid,
                        'sod_combo' => $cmid,
                        'combo_id' => $_REQUEST['combo_name' . $cid],
                        'pack_id' => $_REQUEST['pack_name' . $cid],
                        'variation_value' => $_REQUEST['variation_value_id' . $cid][$oo],
                        'size_qty' => $_REQUEST['varvalue' . $cid][$oo],
                        'excess_per' => $_REQUEST['excess_per' . $cid][$oo],
                        'excess_qty' => (($_REQUEST['varvalue' . $cid][$oo] * ($_REQUEST['excess_per' . $cid][$oo] / 100)) + $_REQUEST['varvalue' . $cid][$oo]),
                    );

                    $in_size = Insert('sod_size', $siz_arr);
                }
            }
        }


        timeline_history('Insert', 'sales_order_detalis', $sl_inid, $_REQUEST['styleno'] . ' Style Added. BO: ' . $so_id);
        $redirect = "sales_order.php?id=" . $sales_order;
    }

    if (isset($_REQUEST['editItemId'])) {
        for ($p = 0; $p < count($_REQUEST['editItemId']); $p++) {
            if ($_REQUEST['can_update'][$p] != "") {

                $qryz = mysqli_query($mysqli, "SELECT * FROM size_details_edit WHERE itemlist_id=" . $_REQUEST['editItemId'][$p]);
                $msq = mysqli_fetch_array($qryz);

                $sel = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.*, b.order_code FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id=b.id WHERE a.id=" . $_REQUEST['editItemId'][$p]));

                if (!empty($_FILES['edit_itenPic']['name'][$p])) {
                    if (!is_dir("uploads/so_img/" . $sel['order_code'] . "/")) {
                        mkdir("uploads/so_img/" . $sel['order_code'] . "/");
                    }

                    $uploaddir = 'uploads/so_img/' . $sel['order_code'] . '/';
                    $uploadfile = $uploaddir . basename($_FILES['edit_itenPic']['name'][$p]);
                    move_uploaded_file($_FILES['edit_itenPic']['tmp_name'][$p], $uploadfile);

                    $item_image = $uploadfile;
                } else {
                    $item_image = $sel['item_image'];
                }

                for ($sk = 0; $sk < count($_REQUEST['edit_combo_id']); $sk++) {

                    $t = $_REQUEST['edit_combo_tempid'][$sk];

                    $arr = array(
                        'combo_id' => $_REQUEST['edit_combo' . $t],
                        'pack_id' => $_REQUEST['edit_pack' . $t],
                    );

                    Update('sod_combo', $arr, 'WHERE id =' . $_REQUEST['edit_combo_id'][$sk]);
                }

                for ($sk = 0; $sk < count($_REQUEST['edit_part_id']); $sk++) {

                    $t = $_REQUEST['edit_part_tempid'][$sk];

                    $arr = array(
                        'combo_id' => $_REQUEST['edit_combo' . $t],
                        'pack_id' => $_REQUEST['edit_pack' . $t],
                        'part_id' => $_REQUEST['edit_part'][$sk],
                        'color_id' => $_REQUEST['edit_color'][$sk],
                    );

                    Update('sod_part', $arr, 'WHERE id =' . $_REQUEST['edit_part_id'][$sk]);
                }

                for ($sj = 0; $sj < count($_REQUEST['edit_size_id']); $sj++) {

                    $t = $_REQUEST['edit_size_tempid'][$sj];

                    $arr = array(
                        'combo_id' => $_REQUEST['edit_combo' . $t],
                        'pack_id' => $_REQUEST['edit_pack' . $t],
                        'size_qty' => $_REQUEST['edit_qty'][$sj],
                        'excess_per' => $_REQUEST['edit_excess'][$sj],
                        'excess_qty' => (($_REQUEST['edit_qty'][$sj] * ($_REQUEST['edit_excess'][$sj] / 100)) + $_REQUEST['edit_qty'][$sj]),
                    );

                    Update('sod_size', $arr, 'WHERE id =' . $_REQUEST['edit_size_id'][$sj]);
                }

                $ed_data = array(
                    'style_no' => $_REQUEST['ed_style_no'][$p],
                    // 'size_detail' => json_encode($val1),
                    // 'part_detail' => json_encode($pack_det_ed),
                    'total_excess' => ($_REQUEST['ed_qtty'][$p] + (($_REQUEST['ed_excess'][$p] / 100) * $_REQUEST['ed_qtty'][$p])),
                    'total_qty' => $_REQUEST['ed_qtty'][$p],
                    'unit_id' => $_REQUEST['ed_unit'][$p],
                    'price' => $_REQUEST['ed_price'][$p],
                    'po_num' => $_REQUEST['ed_po_num'][$p],
                    'item_image' => $item_image,
                    'delivery_date' => $_REQUEST['editdeliveryDate'][$p],
                    'main_fabric' => $_REQUEST['ed_main_fabric'][$p],
                    'style_des' => $_REQUEST['ed_style_des'][$p],
                    'gsm' => $_REQUEST['ed_gsm'][$p],
                );

                $inss = Update('sales_order_detalis', $ed_data, " WHERE id = '" . $_REQUEST['editItemId'][$p] . "'");

                timeline_history('Update', 'sales_order_detalis', $_REQUEST['editItemId'][$p], 'Style Detail Updated. BO :(' . $_REQUEST['so_id'] . '). Style Ref: ' . $_REQUEST['ed_style_no'][$p]);

                $redirect = "sales_order.php?id=" . $sales_order;
            }
        }
    }

    $sel = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(total_qty) as total_qty FROM sales_order_detalis WHERE sales_order_id='" . $sales_order . "'"));

    $upd = mysqli_query($mysqli, "UPDATE sales_order SET order_qty = '" . $sel['total_qty'] . "' WHERE id='" . $sales_order . "'");

    $_SESSION['msg'] = "added";

    header("Location:" . $redirect);

} else if (isset($_REQUEST['clone_row'])) {

    $mnc = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM " . $_REQUEST['table'] . " WHERE id=" . $_REQUEST['id']));

    $datas = array(
        'sales_order_id' => $mnc['sales_order_id'],
        'style_no' => $mnc['style_no'],
        'size_detail' => $mnc['size_detail'],
        'part_detail' => $mnc['part_detail'],
        'total_qty' => $mnc['total_qty'],
        'unit_id' => $mnc['unit_id'],
        'price' => $mnc['price'],
        'total' => $mnc['total'],
        'pack_type' => $mnc['pack_type'],
        'po_num' => $mnc['po_num'],
        'color' => $mnc['color'],
        'delivery_date' => $mnc['delivery_date'],
        'item_image' => $mnc['item_image'],
        'excess' => $mnc['excess'],
    );

    $inss = Insert($_REQUEST['table'], $datas);

    timeline_history('Insert', $_REQUEST['table'], mysqli_insert_id($mysqli), 'Style Cloned. Ref: ' . sales_order_style($mnc['style_no']));

    if ($inss) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_selection_type'])) {

    $data = array(
        'type_name' => $_REQUEST['type_name'],
        'created_date' => date('Y-m-d H:i:s')
    );

    $qry = Insert('selection_type', $data);

    timeline_history('Insert', 'selection_type', mysqli_insert_id($mysqli), 'Type Updated. Ref: ' . $_REQUEST['type_name']);

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_part'])) {

    $clr = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM part WHERE part_name='" . $_REQUEST['part_name'] . "'"));

    $data = array(
        'part_name' => $_REQUEST['part_name'],
        'created_date' => date('Y-m-d H:i:s')
    );

    if ($clr == 0) {
        $qry = Insert('part', $data);
        timeline_history('Insert', 'part', mysqli_insert_id($mysqli), 'Part Added. Ref: ' . $_REQUEST['part_name']);
        $inid = mysqli_insert_id($mysqli);

        if ($qry) {
            $data = array('result' => 'success', 'inid' => $inid);
        } else {
            $data = array('result' => 'error');
        }
    } else {
        $data = array('result' => 'exists');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_production_unit'])) {

    $data = array(
        'full_name' => $_REQUEST['full_name'],
        'created_date' => date('Y-m-d H:i:s')
    );

    $qry = Insert('production_unit', $data);

    timeline_history('Insert', 'production_unit', mysqli_insert_id($mysqli), 'Production Unit Added. Ref: ' . $_REQUEST['full_name']);

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_brand'])) {

    $app = $_REQUEST['approvals'] ? implode(',', $_REQUEST['approvals']) : '';
    $data = array(
        'brand_name' => $_REQUEST['brand_name'],
        'brand_code' => $_REQUEST['brand_code'],
        'approvals' => $app,
        'username' => $_REQUEST['username'],
        'password' => $_REQUEST['password'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Insert('brand', $data);

    $inid = mysqli_insert_id($mysqli);

    timeline_history('Insert', 'brand', $inid, 'Brand Inserted.');

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_task'])) {

    $data = array(
        'task_name' => $_REQUEST['task_name'],
        'task_type' => $_REQUEST['task_type'],
        'daily_followup_task' => $_REQUEST['daily_followup_task'],
        'daily_followup_duration_task' => $_REQUEST['daily_followup_duration_task'],
        'end_followup_task' => $_REQUEST['end_followup_task'],
        'end_followup_duration_task' => $_REQUEST['end_followup_duration_task'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Insert('mas_task', $data);

    $inid = mysqli_insert_id($mysqli);

    timeline_history('Insert', 'mas_task', $inid, 'Task Master Inserted.');

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_defect'])) {

    $data = array(
        'defect_name' => $_REQUEST['defect_name'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Insert('mas_defect', $data);

    $inid = mysqli_insert_id($mysqli);

    timeline_history('Insert', 'mas_defect', $inid, 'Defect Master Inserted.');

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_checking'])) {

    $data = array(
        'checking_name' => $_REQUEST['checking_name'],
        'is_rework' => $_REQUEST['is_rework'],
        'checking_color' => $_REQUEST['checking_color'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Insert('mas_checking', $data);

    $inid = mysqli_insert_id($mysqli);

    timeline_history('Insert', 'mas_checking', $inid, 'Checking Master Inserted.');

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_currency'])) {

    $data = array(
        'currency_name' => $_REQUEST['currency_name'],
        'currency_value' => $_REQUEST['currency_value'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Insert('mas_currency', $data);

    $inid = mysqli_insert_id($mysqli);

    timeline_history('Insert', 'mas_currency', $inid, 'Currency Master Inserted.');

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_designation'])) {

    $data = array(
        'desig_name' => $_REQUEST['desig_name'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Insert('mas_designation', $data);

    $inid = mysqli_insert_id($mysqli);

    timeline_history('Insert', 'mas_designation', $inid, 'Designation Master Inserted.');

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_accessories'])) {

    $data = array(
        'acc_name' => $_REQUEST['acc_name'],
        'acc_type' => $_REQUEST['acc_type'],
        'excess' => $_REQUEST['excess'],
        'purchase_uom' => $_REQUEST['purchase_uom'],
        'consumption_uom' => $_REQUEST['consumption_uom'],
        'purchase_unit' => $_REQUEST['purchase_unit'],
        'uom_qty' => $_REQUEST['uom_qty'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Insert('mas_accessories', $data);

    timeline_history('Insert', 'mas_accessories', mysqli_insert_id($mysqli), 'Accessories Added.');

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_accessoriesType'])) {

    $data = array(
        'type_name' => $_REQUEST['type_name'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Insert('mas_accessories_type', $data);

    $inId = mysqli_insert_id($qry);

    timeline_history('Insert', 'mas_accessories_type', $inId, 'Accessories Type Added.');

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_component'])) {

    $data = array(
        'component_name' => $_REQUEST['component_name'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Insert('mas_component', $data);

    timeline_history('Insert', 'mas_component', mysqli_insert_id($mysqli), 'Component Added. Ref: ' . $_REQUEST['component_name']);

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_approval_'])) {

    // for($m=0; $m<count($_REQUEST['app_brand']); $m++) {
    $data = array(
        'name' => $_REQUEST['approval_name'],
        'department' => $_REQUEST['app_dpt'],
        'daily_followup' => $_REQUEST['daily_followup'],
        'end_followup' => $_REQUEST['end_followup'],
        'daily_followup_duration' => $_REQUEST['daily_followup_duration'],
        'end_followup_duration' => $_REQUEST['end_followup_duration'],

        'created_by' => $logUser,
        'created_unit' => $logUnit
    );

    $qry = Insert('mas_approval', $data);

    timeline_history('Insert', 'mas_approval', mysqli_insert_id($mysqli), 'Approval Added. Ref: ' . $_REQUEST['approval_name']);
    // }

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_merchand'])) {

    $numm = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM merchand_detail WHERE merchand_code = '" . $_REQUEST['merchand_code'] . "'"));
    $mrchh = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM merchand_detail WHERE merchand_name = '" . $_REQUEST['merchand_name'] . "'"));

    if ($numm > 0) {
        $data['error'][] = 1;
        $data['message'][] = 'Merchand Code Already Exist!';

    } else if ($mrchh > 0) {
        $data['error'][] = 1;
        $data['message'][] = 'Merchandiser Already Added!';
    } else {
        $data['error'][] = 0;

        $data = array(
            'merchand_name' => $_REQUEST['merchand_name'],
            'merchand_code' => $_REQUEST['merchand_code'],
            'merch_brand' => implode(',', $_REQUEST['merch_brand']),
            'mailid' => $_REQUEST['mailid'],
        );

        $qry = Insert('merchand_detail', $data);

        timeline_history('Insert', 'merchand_detail', mysqli_insert_id($mysqli), 'Merchand Added. Ref: ' . $_REQUEST['merchand_name']);

        if ($qry) {
            $data = array('result' => 'success');
        } else {
            $data = array('result' => 'error');
        }
    }
    echo json_encode($data);

} else if (isset($_REQUEST['save_unit'])) {

    $data = array(
        'full_name' => $_REQUEST['full_name'],
        'short_name' => $_REQUEST['short_name'],
        'part_count' => $_REQUEST['part_count'],
        'created_date' => date('Y-m-d H:i:s')
    );

    $qry = Insert('unit', $data);

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_color'])) {

    $clr = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM color WHERE color_name='" . $_REQUEST['color_name'] . "'"));

    $data = array(
        'color_name' => $_REQUEST['color_name'],
        'created_date' => date('Y-m-d H:i:s')
    );

    if ($clr == 0) {
        $qry = Insert('color', $data);

        $inid = mysqli_insert_id($mysqli);
        timeline_history('Insert', 'color', mysqli_insert_id($mysqli), 'Line Added. Ref: ' . $_REQUEST['color_name']);
        // $data['inid'][] = $inid;

        if ($qry) {
            $data = array('result' => 'success', 'inid' => $inid);
        } else {
            $data = array('result' => 'error');
        }
    } else {
        $data = array('result' => 'exists');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_employee'])) {
    // print_r($_FILES['employee_photo']['name']);
    // echo '<pre>', print_r($_FILES, 1); exit;

    $dob = $_REQUEST['dob'];
    $agee = (date('Y') - date('Y', strtotime($dob)));

    $agee = $_REQUEST['age'] ? $_REQUEST['age'] : $agee;

    $data = array(
        'type' => $_REQUEST['type'],
        'employee_name' => $_REQUEST['employee_name'],
        'employee_code' => $_REQUEST['employee_code'],
        'mobile' => $_REQUEST['mobile'],
        'email' => $_REQUEST['email'],
        'gender' => $_REQUEST['gender'],
        'dob' => $_REQUEST['dob'],
        'age' => $agee,
        'department' => $_REQUEST['department'],
        'designation' => $_REQUEST['designation'],
        'company' => $_REQUEST['company'],

        'address1_com' => $_REQUEST['address1_com'],
        'address2_com' => $_REQUEST['address2_com'],
        'area_com' => $_REQUEST['area_com'],
        'pincode_com' => $_REQUEST['pincode_com'],
        'country_com' => $_REQUEST['country_com'],
        'state_com' => $_REQUEST['state_com'],
        'city_com' => $_REQUEST['city_com'],

        'address1_per' => $_REQUEST['address1_per'],
        'address2_per' => $_REQUEST['address2_per'],
        'area_per' => $_REQUEST['area_per'],
        'pincode_per' => $_REQUEST['pincode_per'],
        'country_per' => $_REQUEST['country_per'],
        'state_per' => $_REQUEST['state_per'],
        'city_per' => $_REQUEST['city_per'],

        'acc_holder_name' => $_REQUEST['acc_holder_name'],
        'acc_num' => $_REQUEST['acc_num'],
        'ifsc' => $_REQUEST['ifsc'],
        'bank_name' => $_REQUEST['bank_name'],
        'bank_branch' => $_REQUEST['bank_branch'],

        'basic_salary' => $_REQUEST['basic_salary'],
        'house_rent' => $_REQUEST['house_rent'],
        'pf' => $_REQUEST['pf'],
        'esi' => $_REQUEST['esi'],
        'salary_total' => $_REQUEST['salary_total'],

        'basic_salary_cmpl' => $_REQUEST['basic_salary_cmpl'],
        'house_rent_cmpl' => $_REQUEST['house_rent_cmpl'],
        'pf_cmpl' => $_REQUEST['pf_cmpl'],
        'esi_cmpl' => $_REQUEST['esi_cmpl'],
        'salary_total_cmpl' => $_REQUEST['salary_total_cmpl'],

        'username' => $_REQUEST['username'],
        'password' => $_REQUEST['password'],
        'user_group' => $_REQUEST['user_group'],
        'task_remainder_level' => $_REQUEST['task_remainder_level'],

        'is_cg' => $_REQUEST['cost_generator'],
        'cg_name' => $_REQUEST['cg_name'],

        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    if ($_REQUEST['insType'] == 'edit') {
        Update('employee_detail', $data, ' WHERE id = ' . $_REQUEST['updId']);

        timeline_history('Update', 'employee_detail', $_REQUEST['updId'], 'Employee Detail Updated. Ref: ' . $_REQUEST['employee_name']);

        $_SESSION['msg'] = "updated";

        $ins_idd = $_REQUEST['updId'];
    } else {
        $qry = Insert('employee_detail', $data);

        $ins_idd = mysqli_insert_id($mysqli);

        $_SESSION['msg'] = "saved";

        timeline_history('Insert', 'employee_detail', $ins_idd, 'Employee Created. Ref: ' . $_REQUEST['employee_name']);
    }
    // echo '<pre>', print_r($_REQUEST, 1);
    if ($_REQUEST['insType'] == 'move') {
        $sqlly = mysqli_fetch_array(mysqli_query($mysqli, "SELECT aadhar_card, pan_card, license, employee_photo FROM employee_detail_temp WHERE id = " . $_REQUEST['updId']));
    } else {
        $sqlly = mysqli_fetch_array(mysqli_query($mysqli, "SELECT aadhar_card, pan_card, license, employee_photo FROM employee_detail WHERE id = " . $ins_idd));
    }
    // print 2222;
    $aadhar = $_FILES['aadhar_card']['name'];

    if (!empty($aadhar)) {
        if (!is_dir("uploads/employeeDet/" . $ins_idd . "/")) {
            mkdir("uploads/employeeDet/" . $ins_idd . "/");
        }

        $fille = explode('.', $aadhar);

        $newName = 'aadhar_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/employeeDet/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['aadhar_card']['tmp_name'], $uploadfile);

        $aadhar = $uploadfile;
    } else {
        $aadhar = $sqlly['aadhar_card'];
    }

    $pan = $_FILES['pan_card']['name'];

    if (!empty($pan)) {
        if (!is_dir("uploads/employeeDet/" . $ins_idd . "/")) {
            mkdir("uploads/employeeDet/" . $ins_idd . "/");
        }

        $fille = explode('.', $pan);

        $newName = 'pan_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/employeeDet/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['pan_card']['tmp_name'], $uploadfile);

        $pan = $uploadfile;
    } else {
        $pan = $sqlly['pan_card'];
    }

    $license = $_FILES['license']['name'];

    if (!empty($license)) {
        if (!is_dir("uploads/employeeDet/" . $ins_idd . "/")) {
            mkdir("uploads/employeeDet/" . $ins_idd . "/");
        }

        $fille = explode('.', $license);

        $newName = 'license_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/employeeDet/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['license']['tmp_name'], $uploadfile);

        $license = $uploadfile;
    } else {
        $license = $sqlly['license'];
    }

    $other_ = $_FILES['other_docs']['name'];

    if (!empty($other_)) {
        if (!is_dir("uploads/employeeDet/" . $ins_idd . "/")) {
            mkdir("uploads/employeeDet/" . $ins_idd . "/");
        }

        $fille = explode('.', $other_);

        $newName = 'other_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/employeeDet/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['other_docs']['tmp_name'], $uploadfile);

        $other_ = $uploadfile;
    }

    $empl = $_FILES['employee_photo']['name'];

    if (!empty($empl)) {
        if (!is_dir("uploads/employeeDet/" . $ins_idd . "/")) {
            mkdir("uploads/employeeDet/" . $ins_idd . "/");
        }

        $fille = explode('.', $empl);

        $newName = 'employee_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/employeeDet/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['employee_photo']['tmp_name'], $uploadfile);

        $empl = $uploadfile;
    } else {
        $empl = $sqlly['employee_photo'];
    }


    $narr = array(
        'aadhar_card' => $aadhar,
        'pan_card' => $pan,
        'license' => $license,
        'other_docs' => $other_,
        'employee_photo' => $empl,
    );

    Update('employee_detail', $narr, ' WHERE id = ' . $ins_idd);

    $previousPage = $_SERVER['HTTP_REFERER'];
    header("Location: $previousPage");

    // header("Location:employee.php");

    exit;

    // if ($qry) {
    //     $data = array('result' => 'success');
    // } else {
    //     $data = array('result' => 'error');
    // }

    // echo json_encode($data);

} else if (isset($_REQUEST['register_employee'])) {
    // print_r($_FILES['employee_photo']['name']);
    // echo '<pre>', print_r($_FILES, 1); exit;

    $dob = $_REQUEST['dob'];
    $agee = (date('Y') - date('Y', strtotime($dob)));

    $agee = $_REQUEST['age'] ? $_REQUEST['age'] : $agee;

    $data = array(
        'type' => $_REQUEST['type'],
        'employee_name' => $_REQUEST['employee_name'],
        'employee_code' => $_REQUEST['employee_code'],
        'mobile' => $_REQUEST['mobile'],
        'email' => $_REQUEST['email'],
        'gender' => $_REQUEST['gender'],
        'dob' => $_REQUEST['dob'],
        'age' => $agee,
        'department' => $_REQUEST['department'],
        'designation' => $_REQUEST['designation'],
        'company' => $_REQUEST['company'],

        'address1_com' => $_REQUEST['address1_com'],
        'address2_com' => $_REQUEST['address2_com'],
        'area_com' => $_REQUEST['area_com'],
        'pincode_com' => $_REQUEST['pincode_com'],
        'country_com' => $_REQUEST['country_com'],
        'state_com' => $_REQUEST['state_com'],
        'city_com' => $_REQUEST['city_com'],

        'address1_per' => $_REQUEST['address1_per'],
        'address2_per' => $_REQUEST['address2_per'],
        'area_per' => $_REQUEST['area_per'],
        'pincode_per' => $_REQUEST['pincode_per'],
        'country_per' => $_REQUEST['country_per'],
        'state_per' => $_REQUEST['state_per'],
        'city_per' => $_REQUEST['city_per'],

        'acc_holder_name' => $_REQUEST['acc_holder_name'],
        'acc_num' => $_REQUEST['acc_num'],
        'ifsc' => $_REQUEST['ifsc'],
        'bank_name' => $_REQUEST['bank_name'],
        'bank_branch' => $_REQUEST['bank_branch'],

        'username' => $_REQUEST['username'],
        'password' => $_REQUEST['password'],
    );


    $qry = Insert('employee_detail_temp', $data);

    $ins_idd = mysqli_insert_id($mysqli);

    $_SESSION['msg'] = $ins_idd;

    timeline_history('Insert', 'employee_detail', $ins_idd, 'New Employee Registered. Name: ' . $_REQUEST['employee_name']);

    $aadhar = $_FILES['aadhar_card']['name'];

    if (!is_dir("uploads/temp_employee/" . $ins_idd . "/")) {
        mkdir("uploads/temp_employee/" . $ins_idd . "/");
    }

    if (!empty($aadhar)) {

        $fille = explode('.', $aadhar);

        $newName = 'aadhar_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/temp_employee/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['aadhar_card']['tmp_name'], $uploadfile);

        $aadhar = $uploadfile;
    }

    $pan = $_FILES['pan_card']['name'];

    if (!empty($pan)) {

        $fille = explode('.', $pan);

        $newName = 'pan_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/temp_employee/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['pan_card']['tmp_name'], $uploadfile);

        $pan = $uploadfile;
    }

    $license = $_FILES['license']['name'];

    if (!empty($license)) {

        $fille = explode('.', $license);

        $newName = 'license_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/temp_employee/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['license']['tmp_name'], $uploadfile);

        $license = $uploadfile;
    }

    $other_ = $_FILES['other_docs']['name'];

    if (!empty($other_)) {

        $fille = explode('.', $other_);

        $newName = 'other_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/temp_employee/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['other_docs']['tmp_name'], $uploadfile);

        $other_ = $uploadfile;
    }

    $empl = $_FILES['employee_photo']['name'];

    if (!empty($empl)) {
        $fille = explode('.', $empl);

        $newName = 'employee_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/temp_employee/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['employee_photo']['tmp_name'], $uploadfile);

        $empl = $uploadfile;
    }


    $narr = array(
        'aadhar_card' => $aadhar,
        'pan_card' => $pan,
        'license' => $license,
        'other_docs' => $other_,
        'employee_photo' => $empl,
    );

    Update('employee_detail_temp', $narr, ' WHERE id = ' . $ins_idd);


    header("Location:index.php");

    exit;

    // if ($qry) {
    //     $data = array('result' => 'success');
    // } else {
    //     $data = array('result' => 'error');
    // }

    // echo json_encode($data);

} else if (isset($_REQUEST['moveAs_employee'])) {
    // print_r($_FILES['employee_photo']['name']);
    // echo '<pre>', print_r($_FILES, 1); exit;

    $dob = $_REQUEST['dob'];
    $agee = (date('Y') - date('Y', strtotime($dob)));

    $agee = $_REQUEST['age'] ? $_REQUEST['age'] : $agee;

    $data = array(
        'type' => $_REQUEST['type'],
        'employee_name' => $_REQUEST['employee_name'],
        'employee_code' => $_REQUEST['employee_code'],
        'mobile' => $_REQUEST['mobile'],
        'email' => $_REQUEST['email'],
        'gender' => $_REQUEST['gender'],
        'dob' => $_REQUEST['dob'],
        'age' => $agee,
        'department' => $_REQUEST['department'],
        'designation' => $_REQUEST['designation'],
        'company' => $_REQUEST['company'],

        'address1_com' => $_REQUEST['address1_com'],
        'address2_com' => $_REQUEST['address2_com'],
        'area_com' => $_REQUEST['area_com'],
        'pincode_com' => $_REQUEST['pincode_com'],
        'country_com' => $_REQUEST['country_com'],
        'state_com' => $_REQUEST['state_com'],
        'city_com' => $_REQUEST['city_com'],

        'address1_per' => $_REQUEST['address1_per'],
        'address2_per' => $_REQUEST['address2_per'],
        'area_per' => $_REQUEST['area_per'],
        'pincode_per' => $_REQUEST['pincode_per'],
        'country_per' => $_REQUEST['country_per'],
        'state_per' => $_REQUEST['state_per'],
        'city_per' => $_REQUEST['city_per'],

        'acc_holder_name' => $_REQUEST['acc_holder_name'],
        'acc_num' => $_REQUEST['acc_num'],
        'ifsc' => $_REQUEST['ifsc'],
        'bank_name' => $_REQUEST['bank_name'],
        'bank_branch' => $_REQUEST['bank_branch'],

        'basic_salary' => $_REQUEST['basic_salary'],
        'house_rent' => $_REQUEST['house_rent'],
        'pf' => $_REQUEST['pf'],
        'esi' => $_REQUEST['esi'],
        'salary_total' => $_REQUEST['salary_total'],

        'basic_salary_cmpl' => $_REQUEST['basic_salary_cmpl'],
        'house_rent_cmpl' => $_REQUEST['house_rent_cmpl'],
        'pf_cmpl' => $_REQUEST['pf_cmpl'],
        'esi_cmpl' => $_REQUEST['esi_cmpl'],
        'salary_total_cmpl' => $_REQUEST['salary_total_cmpl'],

        'username' => $_REQUEST['username'],
        'password' => $_REQUEST['password'],
        'user_group' => $_REQUEST['user_group'],
        'task_remainder_level' => $_REQUEST['task_remainder_level'],

        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Insert('employee_detail', $data);

    $ins_idd = mysqli_insert_id($mysqli);

    $_SESSION['msg'] = "saved";

    timeline_history('Insert', 'employee_detail', $ins_idd, 'Employee Request Accepted & Converted as Employee. Name: ' . $_REQUEST['employee_name']);

    $ndt = array(
        'is_approved' => 'yes',
        'approved_by' => $logUser,
        'approved_date' => date('Y-m-d H:i:s'),
    );

    Update('employee_detail_temp', $ndt, ' WHERE id = ' . $_REQUEST['updId']);


    $sqlly = mysqli_fetch_array(mysqli_query($mysqli, "SELECT aadhar_card, pan_card, license, employee_photo FROM employee_detail_temp WHERE id = " . $_REQUEST['updId']));


    $aadhar = $_FILES['aadhar_card']['name'];

    if (!empty($aadhar)) {
        if (!is_dir("uploads/employeeDet/" . $ins_idd . "/")) {
            mkdir("uploads/employeeDet/" . $ins_idd . "/");
        }

        $fille = explode('.', $aadhar);

        $newName = 'aadhar_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/employeeDet/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['aadhar_card']['tmp_name'], $uploadfile);

        $aadhar = $uploadfile;
    } else {
        $aadhar = $sqlly['aadhar_card'];
    }

    $pan = $_FILES['pan_card']['name'];

    if (!empty($pan)) {
        if (!is_dir("uploads/employeeDet/" . $ins_idd . "/")) {
            mkdir("uploads/employeeDet/" . $ins_idd . "/");
        }

        $fille = explode('.', $pan);

        $newName = 'pan_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/employeeDet/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['pan_card']['tmp_name'], $uploadfile);

        $pan = $uploadfile;
    } else {
        $pan = $sqlly['pan_card'];
    }

    $license = $_FILES['license']['name'];

    if (!empty($license)) {
        if (!is_dir("uploads/employeeDet/" . $ins_idd . "/")) {
            mkdir("uploads/employeeDet/" . $ins_idd . "/");
        }

        $fille = explode('.', $license);

        $newName = 'license_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/employeeDet/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['license']['tmp_name'], $uploadfile);

        $license = $uploadfile;
    } else {
        $license = $sqlly['license'];
    }

    $other_ = $_FILES['other_docs']['name'];

    if (!empty($other_)) {
        if (!is_dir("uploads/employeeDet/" . $ins_idd . "/")) {
            mkdir("uploads/employeeDet/" . $ins_idd . "/");
        }

        $fille = explode('.', $other_);

        $newName = 'other_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/employeeDet/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['other_docs']['tmp_name'], $uploadfile);

        $other_ = $uploadfile;
    }

    $empl = $_FILES['employee_photo']['name'];

    if (!empty($empl)) {
        if (!is_dir("uploads/employeeDet/" . $ins_idd . "/")) {
            mkdir("uploads/employeeDet/" . $ins_idd . "/");
        }

        $fille = explode('.', $empl);

        $newName = 'employee_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/employeeDet/' . $ins_idd . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['employee_photo']['tmp_name'], $uploadfile);

        $empl = $uploadfile;
    } else {
        $empl = $sqlly['employee_photo'];
    }


    $narr = array(
        'aadhar_card' => $aadhar,
        'pan_card' => $pan,
        'license' => $license,
        'other_docs' => $other_,
        'employee_photo' => $empl,
    );

    Update('employee_detail', $narr, ' WHERE id = ' . $ins_idd);


    header("Location:employee_reg.php");

    exit;

    // if ($qry) {
    //     $data = array('result' => 'success');
    // } else {
    //     $data = array('result' => 'error');
    // }

    // echo json_encode($data);

} else if (isset($_REQUEST['save_fabric'])) {

    $data = array(
        'fabric_name' => $_REQUEST['fabric_name'],
        'fabric_code' => $_REQUEST['fabric_code'],
        'created_date' => date('Y-m-d H:i:s')
    );

    $qry = Insert('fabric', $data);


    timeline_history('Insert', 'fabric', mysqli_insert_id($mysqli), 'Line Added. Ref: ' . $_REQUEST['fabric_name']);

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_line'])) {

    if (isset($_REQUEST['cost_generator'])) {
        $cg = empty($_REQUEST['cost_generator']) ? '' : implode(',', $_REQUEST['cost_generator']);
    } else {
        $cg = '';
    }

    $data = array(
        'line_name' => $_REQUEST['line_name'],
        'process' => implode(',', $_REQUEST['process']),
        'pay_type' => $_REQUEST['pay_type'],
        'cost_generator' => $cg,
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Insert('mas_line', $data);

    timeline_history('Insert', 'mas_line', mysqli_insert_id($mysqli), 'Line Added. Ref: ' . $_REQUEST['line_name']);

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_pack'])) {

    $data = array(
        'pack_name' => $_REQUEST['pack_name'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Insert('mas_pack', $data);


    timeline_history('Insert', 'mas_pack', mysqli_insert_id($mysqli), 'Line Added. Ref: ' . $_REQUEST['pack_name']);

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_yarn'])) {

    $data = array(
        'yarn_name' => $_REQUEST['yarn_name'],
        'yarn_code' => $_REQUEST['yarn_code'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $num = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM mas_yarn WHERE yarn_name = '" . $_REQUEST['yarn_name'] . "'"));
    if ($num == 0) {
        $qry = Insert('mas_yarn', $data);
        timeline_history('Insert', 'mas_yarn', mysqli_insert_id($mysqli), 'Line Added. Ref: ' . $_REQUEST['yarn_name']);
    }


    if ($num) {
        $data = array('result' => 'duplicate');
    } else if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['save_uom'])) {

    $data = array(
        'uom_name' => $_REQUEST['uom_name'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $num = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM mas_uom WHERE uom_name = '" . $_REQUEST['uom_name'] . "'"));
    if ($num == 0) {
        $qry = Insert('mas_uom', $data);
        timeline_history('Insert', 'mas_uom', mysqli_insert_id($mysqli), 'Line Added. Ref: ' . $_REQUEST['uom_name']);
    }


    if ($num) {
        $data = array('result' => 'duplicate');
    } else if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['auto_complete'])) {

    print auto_complete($_REQUEST['table'], $_REQUEST['searchField'], $_GET['term']);

} else if (isset($_REQUEST['saveprocess'])) {

    $data1 = array(
        'so_id' => $_REQUEST['so_id'],
        'budget_id' => $_REQUEST['budget_id'],
        'budget_type' => $_REQUEST['budget_type'],
        'type' => $_REQUEST['type'],
        'supplier' => $_REQUEST['supplier_name'],
        'process' => $_REQUEST['process_name'],
        'created_date' => date('Y-m-d H:i:s'),
    );

    if (!empty($_REQUEST['order_process_id'])) {
        $qry = Update('order_process', $data1, " WHERE id = '" . $_REQUEST['order_process_id'] . "'");
    } else {
        $qry = Insert('order_process', $data1);
    }

    if ($qry) {
        $data = array('result' => 'success');
    } else {
        $data = array('result' => 'error');
    }

    echo json_encode($data);

} else if (isset($_REQUEST['savePlanning'])) {

    $lists = explode(',', $_REQUEST['process_list']);
    for ($i = 0; $i < count($lists); $i++) {

        if ($_REQUEST['pp_type'] == 'unit') {
            $dta = array(
                'so_id' => $_REQUEST['so_id'],
                'style_id' => $_REQUEST['style_id'],
                'process_id' => $lists[$i],
                'process_type' => $_REQUEST['pp_type'],
                'processing_unit_id' => $_REQUEST['unitt'],
            );
            $qqw = mysqli_query($mysqli, "SELECT * FROM process_planing WHERE style_id='" . $_REQUEST['style_id'] . "' AND process_id='" . $lists[$i] . "'");
            $ffr = mysqli_fetch_array($qqw);
            $sqql = mysqli_num_rows($qqw);

            if ($sqql == 0) {
                $ins = Insert('process_planing', $dta);
            } else {
                $ins = Update('process_planing', $dta, " WHERE id = '" . $ffr['id'] . "'");
            }

        } else if ($_REQUEST['pp_type'] == 'supplier') {
            $dta = array(
                'so_id' => $_REQUEST['so_id'],
                'style_id' => $_REQUEST['style_id'],
                'process_id' => $lists[$i],
                'process_type' => $_REQUEST['pp_type'],
                'supplier_id' => $_REQUEST['supplier'],
            );

            $qqw = mysqli_query($mysqli, "SELECT * FROM process_planing WHERE style_id='" . $_REQUEST['style_id'] . "' AND process_id='" . $lists[$i] . "'");
            $ffr = mysqli_fetch_array($qqw);
            $sqql = mysqli_num_rows($qqw);

            if ($sqql == 0) {
                $ins = Insert('process_planing', $dta);
            } else {
                $ins = Update('process_planing', $dta, " WHERE id = '" . $ffr['id'] . "'");
            }
        }

        // $qry = Update('process_planing', $dta, " WHERE id = '" . $_REQUEST['det_id'][$i] . "'");
    }

    if ($ins) {
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

    echo '1';
} else if (isset($_REQUEST['approve_inHouse'])) {

    $data = array('is_inhouse' => $_REQUEST['is_approved']);

    $inss = Update('processing_list', $data, " WHERE id = '" . $_REQUEST['id'] . "'");

    if ($inss) {
        $json['result'] = 'saved';
    } else {
        $json['result'] = 'error';
    }
    echo json_encode($json);

} else if (isset($_REQUEST['UpdBundlereturn'])) {

    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT boundle_id, piece_scanned FROM processing_list WHERE id=" . $_REQUEST['pid']));
    $bbundles = explode(',', $sql['boundle_id']);
    $piece_scanned = explode(',', $sql['piece_scanned']);

    if (($key = array_search($_REQUEST['id'], $bbundles)) !== false) {
        unset($bbundles[$key]);
    }

    function removePieces($element)
    {
        return strpos($element, $_REQUEST['id'] . "-") === false;
    }

    $newArray = array_filter($piece_scanned, "removePieces");

    function getPieceNum($element)
    {
        return strpos($element, $_REQUEST['id'] . "-") !== false;
    }

    $getPieceNum = array_filter($piece_scanned, "getPieceNum");

    $pcs_ = array_map(function ($element) {
        return str_replace($_REQUEST['id'] . "-", "", $element); }, $getPieceNum);

    $ip = mysqli_query($mysqli, "UPDATE processing_list SET boundle_id = '" . implode(',', $bbundles) . "', piece_scanned = '" . implode(',', $newArray) . "'  WHERE id='" . $_REQUEST['pid'] . "'");

    $sel = mysqli_fetch_array(mysqli_query($mysqli, "SELECT s_out_complete FROM bundle_details WHERE id = " . $_REQUEST['id']));

    $newArray = array_diff(explode(',', $sel['s_out_complete']), $pcs_);

    $uppd = mysqli_query($mysqli, "UPDATE bundle_details SET s_out_complete = '" . implode(',', $newArray) . "' WHERE id='" . $_REQUEST['id'] . "'");


    if ($ip) {
        if ($_REQUEST['type'] == 'sewing_input') {
            $fild = 'in_sewing';
            $nfd = array(
                'in_sewing' => NULL,
                'in_sewing_id' => NULL,
                'line' => NULL,
                'in_sewing_date' => NULL,
            );
        } else if ($_REQUEST['type'] == 'sewing_output') {
            $fild = 'complete_sewing';
            $nfd = array(
                'complete_sewing' => NULL,
                's_out_complete' => NULL,
                's_out_not_complete' => NULL,
                'comp_sewing_date' => NULL,
            );
        } else if ($_REQUEST['type'] == 'process_outward') {
            $fild = 'in_proseccing';
            $nfd = array(
                'in_proseccing' => NULL,
                'complete_processing' => NULL,
            );
        } else if ($_REQUEST['type'] == 'checking_list') {
            $fild = 'checking_complete';
            $nfd = array(
                'checking_complete' => NULL,
                'checking_id' => NULL,
                'checking_employee' => NULL,
                'checking_date' => NULL,
                'ch_good_pcs' => NULL,
                'ch_missing_pcs' => NULL,
                'ch_reject_pcs' => NULL,
                'ch_rework_pcs' => NULL,
                'ch_rework_stage' => NULL,
            );
        }
        // Update('bundle_details', $nfd, " WHERE id = '" . $_REQUEST['id'] . "'");
        mysqli_query($mysqli, "UPDATE bundle_details SET $fild = NULL WHERE id='" . $_REQUEST['id'] . "'");
        
        $asdf = mysqli_fetch_array(mysqli_query($mysqli, "SELECT boundle_id FROM processing_list WHERE id=" . $_REQUEST['pid']));

        $dta['boundle_id'] = $asdf['boundle_id'];
    }
    echo json_encode($dta);
} else if (isset($_REQUEST['completed_mark'])) {

    mysqli_query($mysqli, "UPDATE processing_list SET complete_inhouse = 'completed' WHERE id='" . $_REQUEST['id'] . "'");

    print 1;
} else if (isset($_REQUEST['delete_processing_list'])) {

    $uio = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id= '" . $_REQUEST['id'] . "'"));

    $bnl = explode(',', $uio['boundle_id']);

    for ($m = 0; $m < count($bnl); $m++) {

        if ($_REQUEST['type'] == 'sewing_input') {
            $fild = 'in_sewing';
        } else if ($_REQUEST['type'] == 'sewing_output') {
            $fild = 'complete_sewing';
        } else if ($_REQUEST['type'] == 'process_outward') {
            $fild = 'in_proseccing';

            mysqli_query($mysqli, "UPDATE bundle_details SET `in_proseccing`='yes', `complete_processing`= NULL, in_proseccing_id=  NULL, `in_proseccing_date`= NULL WHERE `id`='" . $bnl[$m] . "'");

        } else if ($_REQUEST['type'] == 'checking_list') {
            $fild = 'checking_complete';
        }

        mysqli_query($mysqli, "UPDATE bundle_details SET $fild = NULL WHERE id='" . $bnl[$m] . "'");
        
    }

    $vhn = mysqli_query($mysqli, "DELETE FROM processing_list WHERE id= '" . $_REQUEST['id'] . "'");

    if ($vhn) {
        echo 'deleted';
    } else {
        echo 'error';
    }
} else if (isset($_REQUEST['is_dispatch'])) {
    $mysq = mysqli_query($mysqli, "UPDATE sales_order SET is_dispatch='yes', dispatch_date='" . date('Y-m-d H:i:s') . "' WHERE id='" . $_REQUEST['id'] . "'");
    $data['res11'] = $mysq;

    timeline_history('Insert', 'sales_order', $_REQUEST['id'], 'Order Dispatched. Ref: ' . sales_order_code($_REQUEST['id']));
    if ($mysq) {
        $data['res'] = 'success';
    } else {
        $data['res'] = 'error';
    }

    echo json_encode($data);

} else if (isset($_REQUEST['is_dispatch_det'])) {

    $mysq33 = mysqli_query($mysqli, "UPDATE sales_order_detalis SET is_dispatch='yes' WHERE id='" . $_REQUEST['id'] . "'");

    timeline_history('Update', 'sales_order', $_REQUEST['id'], 'Order Dispatched. Ref: ' . sales_order_code($_REQUEST['id']));

    // $data['res11'] = $mysq;
    if ($mysq33) {
        $data['res'][] = 'success';
    } else {
        $data['res'][] = 'error';
    }

    echo json_encode($data);

} else if (isset($_REQUEST['revert_dispatch_det'])) {

    $mysq33 = mysqli_query($mysqli, "UPDATE `sales_order` SET `is_dispatch` = NULL WHERE `sales_order`.`id` = '" . $_REQUEST['id'] . "'");

    timeline_history('Update', 'sales_order', $_REQUEST['id'], 'Dispatch Reverted. Ref: ' . sales_order_code($_REQUEST['id']));

    // $data['res11'] = $mysq;
    if ($mysq33) {
        $data['res'][] = 'success';
    } else {
        $data['res'][] = 'error';
    }

    echo json_encode($data);

} else if (isset($_REQUEST['delete_cutting_barcode'])) {

    $mysq = mysqli_query($mysqli, "DELETE FROM cutting_barcode WHERE id = " . $_REQUEST['id']);
    $mysq = mysqli_query($mysqli, "DELETE FROM bundle_details WHERE cutting_barcode_id = " . $_REQUEST['id']);

    if ($mysq) {
        $data['res'] = 'success';
    } else {
        $data['res'] = 'error';
    }

    echo json_encode($data);
} else if (isset($_REQUEST['update_qtty_check'])) {

    for ($q11 = 0; $q11 < $_REQUEST['good']; $q11++) {
        $ch_good_pcs[] = $q11 + 1;
    }

    for ($q12 = 0; $q12 < $_REQUEST['reject']; $q12++) {
        $ch_reject_pcs[] = $_REQUEST['good'] + $q12 + 1;
    }

    for ($q13 = 0; $q13 < $_REQUEST['rework']; $q13++) {
        $ch_rework_pcs[] = $_REQUEST['reject'] + $_REQUEST['good'] + $q13 + 1;
    }

    $balNCe = $_REQUEST['reject'] + $_REQUEST['good'] + $_REQUEST['rework'];
    $rejCNT = $_REQUEST['Total'] - $balNCe;

    for ($q14 = 0; $q14 < $rejCNT; $q14++) {
        $ch_missing_pcs[] = $balNCe + $q14 + 1;
    }


    $mysq = mysqli_query($mysqli, "UPDATE bundle_details SET ch_good_pcs = '" . implode(',', $ch_good_pcs) . "', ch_reject_pcs = '" . implode(',', $ch_reject_pcs) . "', ch_rework_pcs = '" . implode(',', $ch_rework_pcs) . "', ch_missing_pcs = '" . implode(',', $ch_missing_pcs) . "'  WHERE id = " . $_REQUEST['id'] . "");

    return 1;
} else if (isset($_REQUEST['common_comment'])) {

    $arr = array(
        'employee_id' => $logUser,
        'comment_from' => $_REQUEST['comment_from'],
        'table_name' => $_REQUEST['table'],
        'primary_id' => $_REQUEST['id'],
        'comment' => $_REQUEST['comment'],
        'creaed_unit' => $logUnit,
    );

    $ins = Insert('common_comments', $arr);

    if ($ins) {
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['updateBillable'])) {

    $ins = mysqli_query($mysqli, "UPDATE sales_order_detalis SET billable_qty = '" . $_REQUEST['billable_qty'] . "', billable_qty_By = '" . $logUser . "', billable_qty_unit = '" . $logUnit . "', billable_qty_date = '" . date('Y-m-d H:i:s') . "'  WHERE id = " . $_REQUEST['id'] . "");

    if ($ins) {
        echo 1;
    } else {
        echo 0;
    }
} else if (isset($_REQUEST['approveBillable'])) {

    $ins = mysqli_query($mysqli, "UPDATE sales_order_detalis SET billable_qty_approve = 'yes' WHERE id = " . $_REQUEST['id'] . "");

    if ($ins) {
        echo 1;
    } else {
        echo 0;
    }
} else if (isset($_REQUEST['confirmCreate'])) {

    $ins = mysqli_query($mysqli, "UPDATE sales_order_detalis SET create_subBill = 'yes' WHERE id = " . $_REQUEST['id'] . "");

    if ($ins) {
        echo 1;
    } else {
        echo 0;
    }
} else if (isset($_REQUEST['UpdateSetting'])) {

    $data = array(
        'ref' => $_REQUEST['ref'],
        'description' => $_REQUEST['description'],
        'value' => $_REQUEST['value'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qql = mysqli_query($mysqli, "SELECT * FROM settings WHERE ref= '" . $_REQUEST['ref'] . "'");
    $uio = mysqli_fetch_array($qql);
    $num = mysqli_num_rows($qql);

    if ($num == 0) {
        $ins = Insert('settings', $data);

        $inId = mysqli_insert_id($ins);

        timeline_history('Insert', 'settings', $inId, $_REQUEST['description'] . ' Setting configuration Inserted.');

    } else {
        $ins = mysqli_query($mysqli, "UPDATE settings SET value = '" . $_REQUEST['value'] . "' WHERE id = " . $uio['id']);

        $ff = $data['description'] . ' Setting configuration Updated. Value: ' . $data['value'];
        timeline_history('Update', 'settings', $uio['id'], $ff);

    }

    if ($ins) {

        $resp['val'][] = 0;
    } else {
        $resp['val'][] = 1;
    }

    echo json_encode($resp);

} else if (isset($_REQUEST['delete_FabricProgram'])) {
    $del = Delete('sales_order_fabric_program', 'WHERE id="' . $_REQUEST['id'] . '"');
    timeline_history('Delete', 'sales_order_fabric_program', $_REQUEST['id'], 'Fabric Program Delete with components, yarn details, and process details');

    $del = Delete('sales_order_fabric_components', 'WHERE fabric_program_id="' . $_REQUEST['id'] . '"');
    $del = Delete('sales_order_fabric_components_yarn', 'WHERE fabric_program_id="' . $_REQUEST['id'] . '"');
    $del = Delete('sales_order_fabric_components_process', 'WHERE fabric_program_id="' . $_REQUEST['id'] . '"');

    if ($del) {
        print 0;
    } else {
        print 1;
    }
} else if (isset($_REQUEST['delete_accessProgram'])) {
    $del = Delete('sales_order_accessories_program', 'WHERE id="' . $_REQUEST['id'] . '"');
    timeline_history('Delete', 'sales_order_accessories_program', $_REQUEST['id'], 'Accessories Program Delete with details');

    $del = Delete('sales_order_accessories_det', 'WHERE program_id="' . $_REQUEST['id'] . '"');

    if ($del) {
        print 0;
    } else {
        print 1;
    }
} else if (isset($_REQUEST['updateBudgetApproval'])) {

    // echo '<pre>', print_r($_POST, 1); die;

    $rId = explode(',', $_REQUEST['id']);

    for ($m = 0; $m < count($rId); $m++) {
        $va = array('is_approved' => $_REQUEST['value']);

        $ins = Update('budget_process', $va, " WHERE id = '" . $rId[$m] . "'");

        $oidd = $rId[$m];
    }

    $oid = mysqli_fetch_array(mysqli_query($mysqli, "SELECT style_id, so_id, budget_for FROM budget_process WHERE id=" . $oidd));    
    $tott = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE budget_for= '" . $oid['budget_for'] ."' AND style_id = '". $oid['style_id'] ."'"));    
    $approved = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE is_approved = 'true' AND budget_for= '" . $oid['budget_for'] ."' AND style_id = '". $oid['style_id'] ."'"));

    $vvl = ($tott == $approved) ? 3 : (($approved == 0) ? 1 : 2);

    $nm = $_REQUEST['namee'];

    $field = ($nm == 'Production') ? 'prod_bud_status' : (($nm == 'Fabric') ? 'fabric_bud_status' : 'access_bud_status');
    $ins = Update('sales_order_detalis', array($field => $vvl), " WHERE id = '" . $oid['style_id'] . "'");    
    $tval = ($_REQUEST['value'] == 'true') ? 'Approved' : 'Rejected';

    timeline_history('Approve', 'budget_process', $_REQUEST['pid'], 'Budget ' . $tval . '. Ref: BO - ' . sales_order_code($oid['so_id']) .'. Style - '. sales_order_style($oid['style_id']));

    if ($ins) {
        $resp['result'][] = 0;
    } else {
        $resp['result'][] = 1;
        
    }
    echo json_encode($resp);

} else if (isset($_REQUEST['saveTimeTemplate'])) {

    // echo '<pre>', print_r($_POST, 1); exit;
    $data2 = array(
        'temp_name' => $_REQUEST['temp_name'],
        'total_day' => $_REQUEST['total_day'],
        'brand' => implode(',', $_REQUEST['brand']),
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $ins = Insert('time_management_template', $data2);

    $inId = mysqli_insert_id($mysqli);

    timeline_history('Insert', 'time_management_template', $inId, 'Time Management Template Created. Template: "'. $_REQUEST['temp_name'] .'"');

    for ($o = 0; $o < count($_REQUEST['activity']); $o++) {

        $name = $_REQUEST['nameId'][$o];

        $ndta = array(
            'temp_id' => $inId,
            'total_day' => $_REQUEST['total_day'],
            'table_name' => $_REQUEST['table_name'][$o],
            'activity' => $_REQUEST['activity'][$o],
            'calculation_type' => $_REQUEST['calculation_type_' . $name],
            'start_day' => $_REQUEST['start_day_' . $name],
            'end_day' => $_REQUEST['end_day_' . $name],
            'daily_time' => ($_REQUEST['daily_time_' . $name] * 60),
            'endday_time' => ($_REQUEST['endday_time_' . $name] * 60),
            'resp_A' => implode(',', $_REQUEST['resp_A_' . $name]),
            'resp_B' => implode(',', $_REQUEST['resp_B_' . $name]),
            'resp_C' => implode(',', $_REQUEST['resp_C_' . $name]),
            'resp_D' => implode(',', $_REQUEST['resp_D_' . $name]),
        );

        $ins = Insert('time_management_template_det', $ndta);
    }

    if ($ins) {
        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['updateTimeTemplate'])) {

    $data2 = array(
        'temp_name' => $_REQUEST['temp_name'],
        'total_day' => $_REQUEST['total_day'],
        'brand' => implode(',', $_REQUEST['brand']),
    );


    Update('time_management_template', $data2, " WHERE id = '" . $_REQUEST['tempId'] . "'");

    timeline_history('Update', 'time_management_template', $_REQUEST['tempId'], 'Time Management Template details Updated. Template: "'. $_REQUEST['temp_name'] .'"');

    for ($o = 0; $o < count($_REQUEST['insId']); $o++) {

        $name = $_REQUEST['nameId'][$o];

        $ndta = array(
            'calculation_type' => $_REQUEST['calculation_type_' . $name],
            'start_day' => $_REQUEST['start_day_' . $name],
            'end_day' => $_REQUEST['end_day_' . $name],
            'daily_time' => ($_REQUEST['daily_time_' . $name] * 60),
            'endday_time' => ($_REQUEST['endday_time_' . $name] * 60),
            'resp_A' => implode(',', $_REQUEST['resp_A_' . $name]),
            'resp_B' => implode(',', $_REQUEST['resp_B_' . $name]),
            'resp_C' => implode(',', $_REQUEST['resp_C_' . $name]),
            'resp_D' => implode(',', $_REQUEST['resp_D_' . $name]),
        );

        $inss = Update('time_management_template_det', $ndta, " WHERE id = '" . $_REQUEST['insId'][$o] . "'");
    }

    if ($inss) {
        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['UpdateHod'])) {

    $t = array('hod' => $_REQUEST['value']);

    $inss = Update('department', $t, " WHERE id = '" . $_REQUEST['id'] . "'");

    if ($inss) {
        timeline_history('Update', 'department', $_REQUEST['id'], 'HOD Updated From Settings.');
        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['validateMobile'])) {

    $nm = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM employee_detail_temp WHERE is_approved != 'no' AND mobile = '" . $_REQUEST['mobile'] . "'"));
    
    $data['numm'][] = $nm;
    
    echo json_encode($data);
} else if (isset($_REQUEST['createTimeSheet'])) {
    
    for($p=0; $p<count($_POST['activity_id']); $p++) {
        
        $aid = $_POST['activity_id'][$p];

        $ddt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT daily_time, endday_time FROM time_management_template_det WHERE id = '" . $aid . "'"));

        $activity = array(
            'time_management_template_det' => $aid,
            'sales_order_id' => $_POST['sales_order_id'],
            'activity' => $_POST['activity'][$p],
            'start_date' => $_POST['start_date_'. $aid],
            'end_date' => $_POST['end_date_'. $aid],
            'daily_time' => $ddt['daily_time'],
            'endday_time' => $ddt['endday_time'],
            'resp_a' => !empty($_POST['resp_a_' . $aid]) ? implode(',', $_POST['resp_a_' . $aid]) : '',
            'resp_b' => !empty($_POST['resp_b_' . $aid]) ? implode(',', $_POST['resp_b_' . $aid]) : '',
            'created_by' => $logUser,
            'created_unit' => $logUnit
        );

        $ins = Insert('sod_time_sheet', $activity);
    }
    timeline_history('Insert', 'sod_time_sheet', $_POST['sales_order_id'], 'Time Sheet Created. Ref:'. sales_order_code($_POST['sales_order_id']) .'');

    if($ins) {

        $qry = mysqli_query($mysqli, "SELECT * FROM sod_time_sheet WHERE sales_order_id = '". $_POST['sales_order_id'] ."'");
        while($result = mysqli_fetch_array($qry)) {

            $start_date = $result['start_date'];
            $end_date = $result['end_date'];
            
            $start_timestamp = strtotime($start_date);
            $end_timestamp = strtotime($end_date);
            
            $dates = [];
            for ($current = $start_timestamp; $current <= $end_timestamp; $current += 86400) {
                $dates[] = date('d-m-Y', $current);
            }

            $last_date = end($dates);
            
            foreach ($dates as $date) {

                if ($date === $last_date) {
                    $task_timing = $result['endday_time'];
                } else {
                    $task_timing = $result['daily_time'];
                }

                $resp = explode(',', $result['resp_a']);

                foreach($resp as $reA) {
                    $arrray = array(
                        'sales_order_id' => $result['sales_order_id'],
                        'time_management_template_det' => $result['time_management_template_det'],
                        'activity' => $result['activity'],
                        'task_date' => date('Y-m-d', strtotime($date)),
                        'task_timeing' => $task_timing,
                        'task_for' => $reA,
                        'resp_b ' => $result['resp_b'],
                    );
                    $ins = Insert('order_tasks', $arrray);
                }
            }
        }
    }

    if($ins) {
        $data['result'][] = 0;
    } else {
        $data['result'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['saveTeamTask'])) {

    $ndt = array(
        'task_type' => $_REQUEST['task_type'],
        'task_msg' => $_REQUEST['task_msg'],
        'assigned_to' => implode(',', $_REQUEST['assigned_to']),
        'assigned_toB' => implode(',', $_REQUEST['assigned_toB']),
        'task_duration' =>$_REQUEST['task_duration'],
        'allowed_time' => ($_REQUEST['totTime'] * 60),
        'start_date' => date('Y-m-d H:i:s', strtotime($_REQUEST['start_date'] . $_REQUEST['start_time'])),
        'end_date' => date('Y-m-d H:i:s', strtotime($_REQUEST['end_date'] . $_REQUEST['end_time'])),
        'created_by' => $logUser,
        'created_unit' => $logUnit,
        'created_date' => date('Y-m-d H:i:s'),
    );

    $i = Insert('team_tasks', $ndt);

    if ($i) {

        $inid = mysqli_insert_id($mysqli);

        for ($i = 0; $i < count($_REQUEST['assigned_to']); $i++) {
            $ar = array(
                'task_id' => $inid,
                'type' => 'assigned_to',
                'employee_id' => $_REQUEST['assigned_to'][$i],
            );

            Insert('team_tasks_for', $ar);
        }

        for ($i = 0; $i < count($_REQUEST['assigned_toB']); $i++) {
            $ar = array(
                'task_id' => $inid,
                'type' => 'assigned_toB',
                'employee_id' => $_REQUEST['assigned_toB'][$i],
            );

            Insert('team_tasks_for', $ar);
        }

        timeline_history('Insert', 'team_tasks', $inid, 'New Team Task Created.');

        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['get_AddedColorVia_select'])) {

    echo select_dropdown('color', array('id', 'color_name'), 'color_name ASC', $_REQUEST['id'], 'WHERE is_active="active"', '');

} else if (isset($_REQUEST['get_AddedPartVia_select'])) {

    echo select_dropdown('part', array('id', 'part_name'), 'part_name ASC', $_REQUEST['id'], 'WHERE is_active="active"', '');

} else if (isset($_REQUEST['rejectRegisteredEmp'])) {

    $da = array(
        'is_approved' => 'no',
        'approved_by' => $logUser,
        'approved_date' => date('Y-m-d H:i:s'),
        'approved_notes' => $_REQUEST['cmt'],
    );

    $up = Update('employee_detail_temp', $da, ' WHERE id= ' . $_REQUEST['id']);

    timeline_history('Insert', 'employee_detail_temp', $_REQUEST['id'], 'Employee Request Rejected.');

    if ($up) {
        $data['res'][] = 0;
    } else {
        $data['res'][] = 0;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['GenerateFabricPrint'])) {
    // echo '<pre>', print_r($_REQUEST, 1);
    $del = Delete('fabprogram_print', 'WHERE sales_order_detalis_id = ' . $_REQUEST['id']);
    $del = Delete('fabprogram_print_yarn', 'WHERE sales_order_detalis_id = ' . $_REQUEST['id']);
    $del = Delete('fabric_requirements', 'WHERE style_id = ' . $_REQUEST['id']);

    $oiid = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sales_order_id FROM sales_order_detalis WHERE id = " . $_REQUEST['id']));
    $order_id = $oiid['sales_order_id'];

    if ($del) {

        $q1 = "SELECT a.* ";
        $q1 .= " FROM sales_order_fabric_program a ";
        $q1 .= " WHERE a.sales_order_detalis_id = '" . $_REQUEST['id'] . "' ";

        $prog = mysqli_query($mysqli, $q1);

        while ($program = mysqli_fetch_array($prog)) {

            $q2 = "SELECT a.process_id, a.lossPer, a.process_order ";
            $q2 .= " FROM sales_order_fabric_components_process a ";
            $q2 .= " WHERE a.fabric_program_id = '" . $program['id'] . "' ORDER BY a.process_order DESC";

            $procs = mysqli_query($mysqli, $q2);

            while ($process = mysqli_fetch_array($procs)) {

                $q3 = "SELECT a.finishing_dia, sum(a.req_wt) as req_wt ";
                $q3 .= " FROM sales_order_fabric_components a ";
                $q3 .= " WHERE  a.fabric_program_id = '" . $program['id'] . "' GROUP BY a.finishing_dia ";

                $dia = mysqli_query($mysqli, $q3);

                while ($dia_wt = mysqli_fetch_array($dia)) {

                    $last_order = mysqli_fetch_array(mysqli_query($mysqli, "SELECT process_order FROM sales_order_fabric_components_process a WHERE a.fabric_program_id = '" . $program['id'] . "' ORDER BY a.process_order DESC"));

                    if ($last_order['process_order'] == $process['process_order']) {

                        $finWt = (($dia_wt['req_wt'] / 100) * $process['lossPer']) + $dia_wt['req_wt'];
                    } else {

                        $wh = array(
                            'sales_order_detalis_id' => $_REQUEST['id'],
                            'fabric_program_id' => $program['id'],
                            'fabric_type' => $program['fabric_type'],
                            // 'process_id' => $process['process_id'],
                            'process_order' => ($process['process_order'] + 1),
                            'fabric_id' => $program['fabric'],
                            'dyeing_color' => $program['dyeing_color'],
                            'yarn_mixing' => $program['yarn_detail'],
                            // 'loss_per' => $process['lossPer'],
                            'dia_wt' => $dia_wt['finishing_dia'],
                        );

                        $whereClause = "";
                        $first = true;

                        foreach ($wh as $key => $value) {
                            if (!$first) {
                                $whereClause .= " AND ";
                            }
                            $whereClause .= "$key = '$value'";
                            $first = false;
                        }

                        $already = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(req_wtt) as req_wtt FROM fabprogram_print a WHERE $whereClause "));

                        $finWt = (($already['req_wtt'] / 100) * $process['lossPer']) + $already['req_wtt'];
                    }

                    $yarnBuying = array('19', '20', '21');

                    if (in_array($process['process_id'], $yarnBuying)) {

                        $yrn = mysqli_query($mysqli, "SELECT * FROM fabprogram_print WHERE fabric_program_id = '" . $program['id'] . "' AND process_order = '" . ($process['process_order'] + 1) . "'");

                        while ($yarn_Det = mysqli_fetch_array($yrn)) {

                            $yarn = json_decode($yarn_Det['yarn_mixing']);

                            foreach ($yarn as $yarn_d) {

                                $yarn_d = explode('=', $yarn_d);

                                $data_y = array(
                                    'sales_order_detalis_id' => $_REQUEST['id'],
                                    'process_id' => $process['process_id'],
                                    'fabric_id' => $program['fabric'],
                                    'dyeing_color' => $program['dyeing_color'],
                                    'yarn' => $yarn_d[0],
                                    'color' => $yarn_d[1],
                                    'mixing' => $yarn_d[2],
                                    'loss_per' => $process['lossPer'],
                                    'dia_wt' => $dia_wt['finishing_dia'],
                                    'req_yarn_wt' => (($yarn_d[2] / 100) * $finWt),
                                    'req_wtt' => $finWt,
                                    'process_order' => $process['process_order'],
                                );

                                $inns = Insert('fabprogram_print_yarn', $data_y);
                            }
                        }
                    } else {

                        $dataArr = array(
                            'sales_order_detalis_id' => $_REQUEST['id'],
                            'fabric_program_id' => $program['id'],
                            'fabric_type' => $program['fabric_type'],
                            'process_id' => $process['process_id'],
                            'process_order' => $process['process_order'],
                            'fabric_id' => $program['fabric'],
                            'dyeing_color' => $program['dyeing_color'],
                            'yarn_mixing' => $program['yarn_detail'],
                            'loss_per' => $process['lossPer'],
                            'dia_wt' => $dia_wt['finishing_dia'],
                            'req_wtt' => $finWt,
                        );

                        $inns = Insert('fabprogram_print', $dataArr);

                    }
                }
            }
        }
    }

    // gray yarn buying YARN process start
    // $jvb = mysqli_query($mysqli, "SELECT fabric_program_id, process_id FROM sales_order_fabric_components_process WHERE sales_order_detalis_id = '" . $_REQUEST['id'] . "' AND process_id IN (19, 20, 21) GROUP BY process_id ");
    // $nummm = mysqli_num_rows($jvb);

    // if ($nummm > 0) {
    //     while ($rtt = mysqli_fetch_array($jvb)) {
    //         $immmm[] = $rtt['fabric_program_id'];
    //     // }

    //         $qq = "SELECT a.yarn_id, c.color_name, a.yarn_id ";
    //         $qq .= " FROM sales_order_fabric_components_yarn a ";
    //         $qq .= " LEFT JOIN mas_yarn b ON a.yarn_id=b.id ";
    //         $qq .= " LEFT JOIN color c ON a.yarn_color=c.id ";
    //         // $qq .= " WHERE a.sales_order_detalis_id = '" . $_REQUEST['id'] . "' GROUP BY a.yarn_id";
    //         $qq .= " WHERE a.sales_order_detalis_id = '" . $_REQUEST['id'] . "' AND a.fabric_program_id IN (" . implode(',', $immmm) . ") GROUP BY a.yarn_id";
    //         // $qq .= " WHERE a.sales_order_detalis_id = '" . $_REQUEST['id'] . "' AND a.fabric_program_id = '" . $rtt['fabric_program_id'] . "' GROUP BY a.yarn_id";
    //         $ry = mysqli_query($mysqli, $qq);

    //         while ($nql2 = mysqli_fetch_array($ry)) {

    //             $bb = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(req_yarn_wt) as req_yarn_wt FROM fabprogram_print_yarn WHERE sales_order_detalis_id='" . $_REQUEST['id'] . "' AND yarn='" . $nql2['yarn_id'] . "' AND process_order=1 "));

    //             $ar_in = array(
    //                 'order_id' => $order_id,
    //                 'style_id' => $_REQUEST['id'],
    //                 'process_id' => $rtt['process_id'],
    //                 'process_order' => 1,
    //                 'yarn_id' => $nql2['yarn_id'],
    //                 'req_wt' => $bb['req_yarn_wt'],
    //             );

    //             $inns = Insert('fabric_requirements', $ar_in);
    //         }
    //     }
    // }


    // Prepare statement for fetching fabric_program_id and process_id
    $stmt = $mysqli->prepare("SELECT fabric_program_id, process_id FROM sales_order_fabric_components_process WHERE sales_order_detalis_id = ? AND process_id IN (19, 20, 21) GROUP BY process_id");
    $stmt->bind_param('s', $_REQUEST['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $fabricProgramIds = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $fabricProgramIds[] = $row['fabric_program_id'];
            
            // Prepare statement for fetching yarn details
            $fabricPrograms = implode(',', array_fill(0, count($fabricProgramIds), '?'));
            $stmtYarn = $mysqli->prepare("SELECT a.yarn_id, c.color_name FROM sales_order_fabric_components_yarn a LEFT JOIN mas_yarn b ON a.yarn_id=b.id LEFT JOIN color c ON a.yarn_color=c.id WHERE a.sales_order_detalis_id = ? AND a.fabric_program_id IN ($fabricPrograms) GROUP BY a.yarn_id");
            $stmtYarn->bind_param(str_repeat('s', count($fabricProgramIds) + 1), $_REQUEST['id'], ...$fabricProgramIds);
            $stmtYarn->execute();
            $yarnResult = $stmtYarn->get_result();

            while ($yarnRow = $yarnResult->fetch_assoc()) {
                // Prepare statement for fetching required yarn weight
                $stmtWeight = $mysqli->prepare("SELECT SUM(req_yarn_wt) as req_yarn_wt FROM fabprogram_print_yarn WHERE sales_order_detalis_id = ? AND yarn = ? AND process_order = 1");
                $stmtWeight->bind_param('ss', $_REQUEST['id'], $yarnRow['yarn_id']);
                $stmtWeight->execute();
                $weightResult = $stmtWeight->get_result();
                $weightRow = $weightResult->fetch_assoc();

                $insertData = [
                    'order_id' => $order_id,
                    'style_id' => $_REQUEST['id'],
                    'process_id' => $row['process_id'],
                    'process_order' => 1,
                    'yarn_id' => $yarnRow['yarn_id'],
                    'req_wt' => $weightRow['req_yarn_wt'],
                ];

                // Insert into fabric_requirements
                Insert('fabric_requirements', $insertData);
            }
        }
    }

    // Close the prepared statements
    $stmt->close();

    // gray yarn buying YARN process end


    // other YARN process start
    // $iop = mysqli_query($mysqli, "SELECT a.*, b.process_name, b.budget_type FROM sales_order_fabric_components_process a LEFT JOIN process b ON a.process_id = b.id 
    //         WHERE b.budget_type = 'Yarn' AND b.id NOT IN (19, 20, 21) AND a.sales_order_detalis_id = '" . $_REQUEST['id'] . "' GROUP BY a.process_id ORDER BY a.process_order ASC");
    // if (mysqli_num_rows($iop) > 0) {
    //     while ($res = mysqli_fetch_array($iop)) {

    //         $jvb1 = mysqli_query($mysqli, "SELECT fabric_program_id FROM sales_order_fabric_components_process WHERE sales_order_detalis_id = '" . $_REQUEST['id'] . "' AND process_id='" . $res['process_id'] . "' ");
    //         while ($rtt1 = mysqli_fetch_array($jvb1)) {
    //             $immmm1[] = $rtt1['fabric_program_id'];
    //         }

    //         $qq1 = "SELECT b.yarn_name, c.color_name, a.yarn_id ";
    //         $qq1 .= " FROM sales_order_fabric_components_yarn a ";
    //         $qq1 .= " LEFT JOIN mas_yarn b ON a.yarn_id=b.id ";
    //         $qq1 .= " LEFT JOIN color c ON a.yarn_color=c.id ";
    //         $qq1 .= " WHERE a.sales_order_detalis_id = '" . $_REQUEST['id'] . "' AND a.fabric_program_id IN (" . implode(',', $immmm1) . ") GROUP BY a.yarn_id, a.yarn_color";

    //         $ry = mysqli_query($mysqli, $qq1);

    //         $p = 1;
    //         while ($nql2 = mysqli_fetch_array($ry)) {

    //             $bb = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(req_yarn_wt) as req_yarn_wt FROM fabprogram_print_yarn WHERE sales_order_detalis_id='" . $_REQUEST['id'] . "' AND yarn='" . $nql2['yarn_id'] . "' AND process_order=1 "));

    //             $ar_in = array(
    //                 'style_id' => $_REQUEST['id'],
    //                 'order_id' => $order_id,
    //                 'process_id' => $res['process_id'],
    //                 'process_order' => $res['process_order'],
    //                 'yarn_id' => $nql2['yarn_id'],
    //                 'req_wt' => $bb['req_yarn_wt'],
    //             );

    //             $inns = Insert('fabric_requirements', $ar_in);
    //         }
    //     }
    // }

    // Prepare statement to fetch processes related to the sales order
    $stmtProcesses = $mysqli->prepare("
    SELECT a.*, b.process_name, b.budget_type 
    FROM sales_order_fabric_components_process a 
    LEFT JOIN process b ON a.process_id = b.id 
    WHERE b.budget_type = 'Yarn' 
    AND b.id NOT IN (19, 20, 21) 
    AND a.sales_order_detalis_id = ? 
    GROUP BY a.process_id 
    ORDER BY a.process_order ASC
    ");
    $stmtProcesses->bind_param('s', $_REQUEST['id']);
    $stmtProcesses->execute();
    $resultProcesses = $stmtProcesses->get_result();

    if ($resultProcesses->num_rows > 0) {
    while ($process = $resultProcesses->fetch_assoc()) {
        // Prepare statement to fetch fabric_program_ids for the current process
        $stmtFabricPrograms = $mysqli->prepare("
            SELECT fabric_program_id 
            FROM sales_order_fabric_components_process 
            WHERE sales_order_detalis_id = ? AND process_id = ?
        ");
        $stmtFabricPrograms->bind_param('ss', $_REQUEST['id'], $process['process_id']);
        $stmtFabricPrograms->execute();
        $resultFabricPrograms = $stmtFabricPrograms->get_result();

        $fabricProgramIds = [];
        while ($rowFabricProgram = $resultFabricPrograms->fetch_assoc()) {
            $fabricProgramIds[] = $rowFabricProgram['fabric_program_id'];
        }

        // Only proceed if we have fabric program IDs
        if (!empty($fabricProgramIds)) {
            $fabricProgramsList = implode(',', array_fill(0, count($fabricProgramIds), '?'));
            $stmtYarns = $mysqli->prepare("
                SELECT b.yarn_name, c.color_name, a.yarn_id 
                FROM sales_order_fabric_components_yarn a 
                LEFT JOIN mas_yarn b ON a.yarn_id = b.id 
                LEFT JOIN color c ON a.yarn_color = c.id 
                WHERE a.sales_order_detalis_id = ? AND a.fabric_program_id IN ($fabricProgramsList) 
                GROUP BY a.yarn_id, a.yarn_color
            ");

            // Bind parameters for yarn selection
            $stmtYarns->bind_param(str_repeat('s', count($fabricProgramIds) + 1), $_REQUEST['id'], ...$fabricProgramIds);
            $stmtYarns->execute();
            $resultYarns = $stmtYarns->get_result();

            while ($yarn = $resultYarns->fetch_assoc()) {
                // Prepare statement to fetch required yarn weight
                $stmtWeight = $mysqli->prepare("
                    SELECT SUM(req_yarn_wt) AS req_yarn_wt 
                    FROM fabprogram_print_yarn 
                    WHERE sales_order_detalis_id = ? AND yarn = ? AND process_order = 1
                ");
                $stmtWeight->bind_param('ss', $_REQUEST['id'], $yarn['yarn_id']);
                $stmtWeight->execute();
                $resultWeight = $stmtWeight->get_result();
                $weightRow = $resultWeight->fetch_assoc();

                // Prepare data for insertion
                $insertData = [
                    'style_id' => $_REQUEST['id'],
                    'order_id' => $order_id,
                    'process_id' => $process['process_id'],
                    'process_order' => $process['process_order'],
                    'yarn_id' => $yarn['yarn_id'],
                    'req_wt' => $weightRow['req_yarn_wt'],
                ];

                // Insert into fabric_requirements
                Insert('fabric_requirements', $insertData);
            }
        }
    }
    }

    // Close the prepared statements
    $stmtProcesses->close();

    // other YARN process end


    // Fabric, Dyeing Color, AOP Design process start
    $uio9 = "SELECT a.*, b.process_name, b.budget_type, a.process_id ";
    $uio9 .= " FROM sales_order_fabric_components_process a ";
    $uio9 .= " LEFT JOIN process b ON a.process_id = b.id ";
    $uio9 .= " WHERE (b.budget_type = 'Fabric' OR b.budget_type = 'Dyeing Color' OR b.budget_type = 'AOP Design') AND a.sales_order_detalis_id = '" . $_REQUEST['id'] . "' GROUP BY a.process_id ORDER BY a.process_order ASC ";

    $iop9 = mysqli_query($mysqli, $uio9);
    $px = 0;

    if (mysqli_num_rows($iop9) > 0) {
        while ($res9 = mysqli_fetch_array($iop9)) {

            $qq9 = "SELECT a.*, sum(a.req_wtt) as req_wtt_new, b.fabric_name, c.color_name ";
            $qq9 .= " FROM fabprogram_print a ";
            $qq9 .= " LEFT JOIN fabric b ON a.fabric_id = b.id ";
            $qq9 .= " LEFT JOIN color c ON a.dyeing_color = c.id ";
            $qq9 .= " WHERE a.sales_order_detalis_id = '" . $_REQUEST['id'] . "' AND a.process_id = '" . $res9['process_id'] . "' GROUP BY a.fabric_id, a.dyeing_color, a.yarn_mixing, a.dia_wt, a.loss_per ";

            $ry = mysqli_query($mysqli, $qq9);
            $p = 1;
            while ($nql9 = mysqli_fetch_array($ry)) {

                $yn_[$p] = "";
                foreach (json_decode($nql9['yarn_mixing']) as $ynn) {
                    $ynn = explode('=', $ynn);

                    $nsql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT yarn_name FROM mas_yarn WHERE id = '" . $ynn[0] . "'"));

                    $yrClr = mysqli_fetch_array(mysqli_query($mysqli, "SELECT color_name FROM color WHERE id = '" . $ynn[1] . "'"));

                    $ycrrr = $yrClr['color_name'] ? ' - ' . $yrClr['color_name'] : '';

                    $yn_[$p] .= $nsql['yarn_name'] . $ycrrr . ' - ' . $ynn[2] . '%, ';
                }


                $ar_in = array(
                    'order_id' => $order_id,
                    'style_id' => $_REQUEST['id'],
                    'process_id' => $res9['process_id'],
                    'process_order' => $res9['process_order'],
                    'fabric_type' => $nql9['fabric_type'],
                    'fabric_id' => $nql9['fabric_id'],
                    'yarn_mixing' => $nql9['yarn_mixing'],
                    'loss_p' => $nql9['loss_per'],
                    'color' => $nql9['dyeing_color'],
                    'dia_size' => $nql9['dia_wt'],
                    'req_wt' => $nql9['req_wtt_new'],
                );

                $inns = Insert('fabric_requirements', $ar_in);
                $p++;
            }
        }
    }

    

    // end of the function

    if ($inns) {
        timeline_history('Insert', 'fabprogram_print', $_REQUEST['id'], 'Fabric Program Print Generated for the style : ' . sales_order_style($_REQUEST['id']) . '.');
        $res['result'][] = 0;
    } else {
        $res['result'][] = 1;
    }

    echo json_encode($res);

} else if (isset($_REQUEST['removeChecking'])) {

    $bid = $_REQUEST['bid'];
    $pid = $_REQUEST['pid'];

    $cac = mysqli_query($mysqli, "SELECT * FROM checking_output WHERE processing_list_id = '" . $pid . "' AND bundle_id = '" . $bid . "'");
    while ($del = mysqli_fetch_array($cac)) {
        if ($del['checking_type'] == '1') {
            $arr['good'][] = $del['pieces'];
        } else if ($del['checking_type'] == '6') {
            $arr['rejection'][] = $del['pieces'];
        } else {
            $arr['rework'][] = $del['pieces'];
        }
    }

    $newArray = array();
    foreach ($arr as $key => $values) {
        $newArray[$key] = implode(',', $values);
    }

    $bundle = mysqli_fetch_array(mysqli_query($mysqli, "SELECT ch_good_pcs, ch_reject_pcs, ch_rework_pcs, ch_missing_pcs FROM bundle_details WHERE id = " . $bid));

    $mp = implode(',', array_filter(array_merge(explode(',', $bundle['ch_missing_pcs']), explode(',', $newArray['good']), explode(',', $newArray['rejection']), explode(',', $newArray['rework']))));
    $arr_nw = array(
        'ch_good_pcs' => array_diff($bundle['ch_good_pcs'], $newArray['good']),
        'ch_reject_pcs' => array_diff($bundle['ch_reject_pcs'], $newArray['rejection']),
        'ch_rework_pcs' => array_diff($bundle['ch_rework_pcs'], $newArray['rework']),
        'ch_missing_pcs' => ($mp == "") ? NULL : $mp,
    );

    Update('bundle_details', $arr_nw, 'WHERE id= ' . $bid);


    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT boundle_id, piece_scanned FROM processing_list WHERE id=" . $pid));
    $bbundles = explode(',', $sql['boundle_id']);
    $piece_scanned = explode(',', $sql['piece_scanned']);

    if (($key = array_search($bid, $bbundles)) !== false) {
        unset($bbundles[$key]);
    }

    function removePieces($element, $bid = '')
    {
        return strpos($element, $bid . "-") === false;
    }

    $newArray = array_filter($piece_scanned, "removePieces");

    function getPieceNum($element, $bid = '')
    {
        return strpos($element, $bid . "-") !== false;
    }

    $getPieceNum = array_filter($piece_scanned, "getPieceNum");

    $pcs_ = array_map(function ($element, $bid = '') {
        return str_replace($bid . "-", "", $element); }, $getPieceNum);

    $ip = mysqli_query($mysqli, "UPDATE processing_list SET boundle_id = '" . implode(',', $bbundles) . "', piece_scanned = '" . implode(',', $newArray) . "'  WHERE id='" . $pid . "'");

    $delll = Delete('checking_output', 'WHERE processing_list_id = "' . $pid . '" AND bundle_id = "' . $bid . '"');


    if ($delll) {
        $dataP['res'][] = 0;

        timeline_history('Delete', 'bundle_details', $bid, 'Checking bundle details deleted.');
    } else {
        $dataP['res'][] = 1;
    }

    echo json_encode($dataP);
} else if (isset($_REQUEST['saveBill_receipt'])) {

    if (isset($_REQUEST['ed_id'])) {
        $entry_number = $_REQUEST['entry_number'];
    } else {
        $qryz = mysqli_query($mysqli, "SELECT entry_number FROM bill_receipt WHERE entry_number LIKE '%BR-%' ORDER BY id DESC");
        $sqql = mysqli_fetch_array($qryz);
        $numm = mysqli_num_rows($qryz);
        if ($numm == 0) {
            $entry_number = 'BR-1';
        } else {
            $ex = explode('-', $sqql['entry_number']);

            $value = $ex[1];
            $intValue = (int) $value;
            $newValue = $intValue + 1;

            $entry_number = $ex[0] . '-' . $newValue;
        }
    }


    $bill_image = $_FILES['bill_image']['name'];
    if (!empty($bill_image)) {
        if (!is_dir("uploads/bill-receipt/" . $entry_number . "/")) {
            mkdir("uploads/bill-receipt/" . $entry_number . "/");
        }

        $fille = explode('.', $bill_image);

        $newName = 'bill_image_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/bill-receipt/' . $entry_number . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['bill_image']['tmp_name'], $uploadfile);

        $bill_image = $uploadfile;
    } else {
        $bill_image = '';
    }

    $approved_image = $_FILES['approved_image']['name'];
    if (!empty($approved_image)) {
        if (!is_dir("uploads/bill-receipt/" . $entry_number . "/")) {
            mkdir("uploads/bill-receipt/" . $entry_number . "/");
        }

        $fille = explode('.', $approved_image);

        $newName = 'approved_image_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/bill-receipt/' . $entry_number . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['approved_image']['tmp_name'], $uploadfile);

        $approved_image = $uploadfile;
    } else {
        $approved_image = '';
    }

    $arr_n = array(
        'entry_number' => $entry_number,
        'entry_date' => $_REQUEST['entry_date'],
        'bill_type' => $_REQUEST['bill_type'],
        'bill_number' => $_REQUEST['bill_number'],
        'bill_date' => $_REQUEST['bill_date'],
        'supplier' => $_REQUEST['supplier'],
        'bill_amount' => $_REQUEST['bill_amount'],
        'bill_image' => $bill_image,
        'approved_image' => $approved_image,
        'comments' => $_REQUEST['comments'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    if (isset($_REQUEST['ed_id'])) {
        $vb = Update('bill_receipt', $arr_n, ' WHERE id=' . $_REQUEST['ed_id']);

        $iid = $_REQUEST['ed_id'];

        timeline_history('Update', 'bill_receipt', $_REQUEST['ed_id'], $_REQUEST['bill_type'] . ' Bill Receipt Created.');
    } else {
        $vb = Insert('bill_receipt', $arr_n);
        $iid = mysqli_insert_id($mysqli);

        timeline_history('Insert', 'bill_receipt', $iid, $_REQUEST['bill_type'] . ' Bill Receipt Updated.');
    }

    if ($_REQUEST['bill_type'] == 'CostGenerate') {
        Update('bill_receipt', array('cost_id' => $_REQUEST['cost_id']), ' WHERE id=' . $iid);

        Update('cost_generation', array('is_receipted' => 'yes', 'receipt_ref' => $iid), ' WHERE id=' . $_REQUEST['cost_id']);

    }

    if ($vb) {
        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }

    echo json_encode($data);

} else if (isset($_REQUEST['deleteBillReceipt'])) {

    $sel = mysqli_fetch_array(mysqli_query($mysqli, "SELECT cost_id FROM bill_receipt WHERE id =" . $_REQUEST['id']));

    $upd = Update('cost_generation', array('is_receipted' => 'no'), ' WHERE id=' . $sel['cost_id']);

    if ($upd) {
        $del = Delete('bill_receipt', 'WHERE id = ' . $_REQUEST['id']);
    }

    if ($del) {
        timeline_history('Insert', 'bill_receipt', $_REQUEST['id'], 'Bill Receipt Deleted.');
        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['saveQC'])) {

    echo '<pre>', print_r($_FILES, 1);
    exit;

    for ($i = 0; $i < count($_REQUEST['process_qty']); $i++) {
        $array = array(
            'prodessing_list_id' => $_REQUEST['prodessing_list_id'],
            'ref_num' => $_REQUEST['ref_num'],
            'type' => $_REQUEST['s_type'],
            'order_id' => $_REQUEST['order_id'],
            'process_qty' => $_REQUEST['process_qty'][$i],
            'approved' => $_REQUEST['approved'][$i],
            'critical' => $_REQUEST['critical'][$i],
            'major' => $_REQUEST['major'][$i],
            'minor' => $_REQUEST['minor'][$i],
            'defect' => implode(',', $_REQUEST['defect' . $_REQUEST['temp_id'][$i]]),
            'created_by' => $logUser,
            'created_unit' => $logUnit,
        );
        // print_r($array);
        if ($_REQUEST['s_type'] == 'total') {
            $narr = array();
        } else if ($_REQUEST['s_type'] == 'part_color') {
            $narr = array(
                'part' => $_REQUEST['part'][$i],
                'color' => $_REQUEST['color'][$i],
            );
        } else if ($_REQUEST['s_type'] == 'size') {
            $narr = array(
                'part' => $_REQUEST['part'][$i],
                'color' => $_REQUEST['color'][$i],
                'variation_value' => $_REQUEST['variation_value'][$i],
            );
        }

        $del = Insert('qc_production', array_merge($array, $narr));

    }

    // exit;

    if ($del) {

        $hj = mysqli_query($mysqli, "UPDATE processing_list SET qc_approval ='approved' WHERE id=" . $_REQUEST['prodessing_list_id']);

        timeline_history('Insert', 'qc_production', $_REQUEST['prodessing_list_id'], 'Procuction QC Created for ' . $_REQUEST['ref_num'] . '.');
        $data['res'][] = 0;
        $data['inid'][] = $_REQUEST['prodessing_list_id'];
    } else {
        $data['res'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['Save_CostGeneration'])) {

    if ($_REQUEST['typ'] == 'insert') {
        $narr = array(
            'entry_number' => $_REQUEST['entry_number'],
            'entry_date' => date('Y-m-d'),
            'employee' => $logUser,
            'created_by' => $logUser,
            'created_unit' => $logUnit,
        );

        $ins = Insert('cost_generation', $narr);

        $inid = mysqli_insert_id($mysqli);
    } else {
        $inid = $_REQUEST['cost_generation_id'];
    }

    for ($m = 0; $m < count($_REQUEST['order_basic']); $m++) {

        $exp = explode('-', $_REQUEST['order_basic'][$m]);

        $sod_part = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sod_part WHERE id= '" . $exp[0] . "' "));
        $arr = array(
            'entry_number' => $_REQUEST['entry_number'],
            'cost_generation_id' => $inid,
            'employee' => $_REQUEST['employee'][$m],
            'order_id' => $sod_part['sales_order_id'],
            'style' => $sod_part['sales_order_detail_id'],
            'sod_part' => $sod_part['id'],
            'combo' => $sod_part['combo_id'],
            'part' => $sod_part['part_id'],
            'color' => $sod_part['color_id'],
            'process' => $exp[1],
            'max_qty' => $_REQUEST['max_qty'][$m],
            'bill_qty' => $_REQUEST['bill_qty'][$m],
            'max_rate' => $_REQUEST['max_rate'][$m],
            'bill_rate' => $_REQUEST['bill_rate'][$m],
            'bill_amount' => $_REQUEST['bill_amount'][$m],
            'created_by' => $logUser,
            'created_unit' => $logUnit,
        );

        if ($_REQUEST['cg_id'][$m] == "") {
            $ins = Insert('cost_generation_det', $arr);
        } else {
            $ins = Update('cost_generation_det', $arr, 'WHERE id = ' . $_REQUEST['cg_id'][$m]);
        }
    }

    if ($ins) {
        timeline_history('Insert', 'cost_generation', $inid, 'Bill Receipt Inserted. Ref: ' . $_REQUEST['entry_number']);
        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['saveProd_Passing'])) {

    $array = array(
        'bill_type' => 'Cost Generate',
        'entry_number' => $_REQUEST['entry_number'],
        'entry_date' => $_REQUEST['entry_date'],
        'bill_id' => $_REQUEST['bill_number'],
        'bill_from' => 'cost_generation_det',
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $upd = Insert('bill_passing', $array);

    $bill_passing_id = mysqli_insert_id($mysqli);

    for ($m = 0; $m < count($_REQUEST['cost_generation_det']); $m++) {
        $arr = array(
            'bill_passing_id' => $bill_passing_id,
            'cost_generation_det' => $_REQUEST['cost_generation_det'][$m],
            'bill_rate' => $_REQUEST['n_bill_rate'][$m],
            'bill_qty' => $_REQUEST['n_bill_qty'][$m],
            'bill_amount' => $_REQUEST['n_bill_amount'][$m],
            'debit_qty' => $_REQUEST['debit_qty'][$m],
            'debit_amount' => $_REQUEST['debit_amount'][$m],
            'bill_receipt_id' => $_REQUEST['bill_receipt_id'],
        );

        $upd = Insert('bill_passing_det', $arr);

        // $upd = Update('bill_passing_det', $arr, ' WHERE id='. $_REQUEST['id'][$m]);
    }

    if ($upd) {
        // Update('bill_receipt', array('status' => 'passed'), ' WHERE id='. $_REQUEST['bill_receipt_id']);

        // timeline_history('Update', 'cost_generation_det', $_REQUEST['bill_passing_id'], 'Production Bill Passing Updated.');

        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['Approve_BillPassing_status'])) {

    $arr = array(
        'approval_status' => $_REQUEST['typ'],
        'approval_message' => $_REQUEST['msg'],
    );

    $upd = Update('bill_receipt', $arr, ' WHERE id=' . $_REQUEST['id']);

    if ($upd) {
        Update('bill_receipt', array('status' => 'passed'), ' WHERE id=' . $_REQUEST['bill_receipt_id']);

        timeline_history('Update', 'bill_receipt', $_REQUEST['bill_receipt_id'], 'Production Bill Passing Status ' . $_REQUEST['typ']);

        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['process_payment'])) {



    $ref_file = $_FILES['ref_file']['name'];
    $entry_number = $_REQUEST['entry_number'];

    if (!empty($ref_file)) {
        if (!is_dir("uploads/payment_ref/" . $entry_number . "/")) {
            mkdir("uploads/payment_ref/" . $entry_number . "/");
        }

        $fille = explode('.', $ref_file);

        $newName = 'payment_ref_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'uploads/payment_ref/' . $entry_number . '/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['ref_file']['tmp_name'], $uploadfile);

        $ref_file = $uploadfile;
    } else {
        $ref_file = '';
    }

    if ($_REQUEST['payment_type'] == 'bill_against') {
        $pType = $_REQUEST['bill_RefNew'];

    } else {
        $pType = $_REQUEST['bill_ref'];
    }


    $arr = array(
        'entry_number' => $_REQUEST['entry_number'],
        'entry_date' => $_REQUEST['entry_date'],
        'bill_type' => $_REQUEST['bill_type'],
        'supplier' => $_REQUEST['supplier'],
        'payment_type' => $_REQUEST['payment_type'],
        'total_outstanding' => $_REQUEST['tot_outstanding'],
        'bill_ref' => $pType,
        'bill_value' => $_REQUEST['bill_value'],
        'pay_amount' => $_REQUEST['payment_amt'],
        'pay_method' => $_REQUEST['payment_method'],
        'pay_ref_file' => $ref_file,
        'pay_ref_detail' => $_REQUEST['ref_detail'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );


    $upd = Insert('payments', $arr);

    $innid = mysqli_insert_id($mysqli);

    if ($upd) {

        if ($_REQUEST['payment_type'] == 'bill_against') {

            $pAmt = $_REQUEST['payment_amt'];

            $pmts = mysqli_fetch_array(mysqli_query($mysqli, "SELECT bill_ref FROM payments WHERE id=" . $innid));
            $exp = explode(',', $pmts['bill_ref']);

            if ($pAmt > $_REQUEST['bill_value']) {

                Update('payments', array('paid_excess' => ($pAmt - $_REQUEST['bill_value'])), 'WHERE id=' . $innid);
            }

            foreach ($exp as $key => $val) {

                $bbn = mysqli_fetch_array(mysqli_query($mysqli, "SELECT bill_amount FROM bill_receipt WHERE id=" . $val));

                if ($bbn['bill_amount'] <= $pAmt) {
                    $paid_Amt = $bbn['bill_amount'];

                    $pAmt -= $bbn['bill_amount'];

                    $stus = 'paid';

                } else if (($bbn['bill_amount'] > $pAmt) && ($pAmt > 0)) {
                    $paid_Amt = $pAmt;
                    $stus = 'partially paid';

                    $pAmt -= $pAmt;
                } else {
                    $stus = 'not paid';
                    $paid_Amt = 0;
                }

                Update('bill_receipt', array('paid_amt' => $paid_Amt, 'payment_status' => $stus), 'WHERE id=' . $val);
                timeline_history('Update', 'bill_receipt', $val, 'Amount ' . $paid_Amt . ' ' . $stus);
            }

        }

        timeline_history('Insert', 'payments', $innid, 'payments entry Added!');

        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['save_inhouse_Status'])) {

    $arrr = array(
        'processing_id' => $_REQUEST['processing_id'],
        'entry_number' => $_REQUEST['entry_number'],
        'entry_date' => $_REQUEST['entry_date'],
        'scanType' => $_REQUEST['scanType'],
        'scanUsing' => $_REQUEST['scanUsing'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $ins = Insert('inhouse_daily_status', $arrr);
    $inid = mysqli_insert_id($mysqli);

    if ($ins) {
        timeline_history('Insert', 'inhouse_daily_status', $inid, 'In-house Process Status Created.');
        $data['res'][] = 0;
        $data['inid'][] = $inid;
    } else {
        $data['res'][] = 1;
    }
    echo json_encode($data);


} else if (isset($_REQUEST['Save_inhouse_completed_bundles'])) {

    for ($m = 0; $m < count($_REQUEST['multibundle']); $m++) {

        $qry = mysqli_fetch_array(mysqli_query($mysqli, "SELECT pcs_per_bundle FROM bundle_details WHERE id=" . $_REQUEST['multibundle'][$m]));

        $vali = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM inhouse_process WHERE daily_status_id='" . $_REQUEST['daily_status_id'] . "' AND bundle_id=" . $_REQUEST['multibundle'][$m]));

        $h = range(1, $qry['pcs_per_bundle']);

        $arrr = array(
            'processing_id' => $_REQUEST['processing_id'],
            'daily_status_id' => $_REQUEST['daily_status_id'],
            'bundle_id' => $_REQUEST['multibundle'][$m],
            'completed_qty' => count($h),
            'completed_pcs' => implode(',', $h),
        );

        if ($vali['id'] == "") {
            $ins = Insert('inhouse_process', $arrr);
            // } else {
            //     $ins = Update('inhouse_process', $arrr, ' WHERE id='. $vali['id']); 
        }
    }

    if ($ins) {
        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['deleteInhouse_status'])) {

    $del = Delete('inhouse_daily_status', ' WHERE id=' . $_REQUEST['id']);
    $del = Delete('inhouse_process', ' WHERE daily_status_id=' . $_REQUEST['id']);

    if ($del) {

        timeline_history('Delete', 'inhouse_daily_status', $_REQUEST['id'], 'In-house Daily Status Deleted.');
        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }
    echo json_encode($data);

} else if (isset($_REQUEST['addFabric_PO'])) {

    if ($_REQUEST['po_idd'] == "") {

        $arr = array(
            'entry_number' => $_REQUEST['entry_number'],
            'entry_date' => $_REQUEST['entry_date'],
            'supplier' => $_REQUEST['supplier'],
            'created_by' => $logUser,
            'created_unit' => $logUnit,
        );

        $ins = Insert('fabric_po', $arr);
        $po_idd = mysqli_insert_id($mysqli);
        timeline_history('Insert', 'fabric_po', $po_idd, 'Fabric PO Created. Ref: ' . $_REQUEST['entry_number']);
    } else {
        $arr = array(
            'entry_date' => $_REQUEST['entry_date'],
            'supplier' => $_REQUEST['supplier'],
        );

        $ins = Update('fabric_po', $arr, 'WHERE id=' . $_REQUEST['po_idd']);
        $po_idd = $_REQUEST['po_idd'];
    }

    $sos = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sales_order_id FROM sales_order_detalis WHERE id = '". $_REQUEST['order_id'] ."'"));

    $narr = array(

        'fab_po' => $po_idd,
        'stock_bo' => $_REQUEST['stock_bo'],
        'order_id' => $sos['sales_order_id'],
        'style_id' => $_REQUEST['order_id'],
        'po_stage' => $_REQUEST['po_stage'],
        'material_name' => $_REQUEST['material_name'],
        'color_ref' => $_REQUEST['color_ref'],
        'bag_roll' => $_REQUEST['bag_roll'],
        'po_balance' => $_REQUEST['po_balance'],
        'stock_dia' => $_REQUEST['stock_dia'],
        'po_qty_wt' => $_REQUEST['po_qty_wt'],
        'rate' => $_REQUEST['rate'],
        'tax_per' => $_REQUEST['tax_per'],
        'amount' => $_REQUEST['amount'],
        'created_by' => $logUser,
    );

    $ins = Insert('fabric_po_det', $narr);
    $inid = mysqli_insert_id($mysqli);
    timeline_history('Insert', 'fabric_po_det', $inid, 'Fabric PO List Added. Ref: ' . $_REQUEST['entry_number']);

    $ins = Update('sales_order', array('edit_fab_requirement' => 'no'), 'WHERE id=' . $_REQUEST['order_id']);

    if ($ins) {

        $data['po_idd'][] = $po_idd;
        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }
    echo json_encode($data);

} else if(isset($_REQUEST['update_scantype'])) {
    
    $upd = mysqli_query($mysqli, "UPDATE budget_process SET scanning_type = '". $_REQUEST['value'] ."' WHERE id = '". $_REQUEST['id'] ."'");
    
    if($upd) {
        $data['resp'] = 'success';
    } else {
        $data['resp'] = 'success';
    }

    echo json_encode($data);
}








// timeline_history('Insert', 'employee_detail_temp', $_REQUEST['id'], 'Employee Request Rejected.');








