
<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_POST['saveBtn'])) {
    
    $qyy = mysqli_fetch_array(mysqli_query($mysqli, "SELECT process FROM fabric_dc WHERE id = ". $_POST['fabric_dc']));

    $arry = array(
        'entry_number' => $_POST['entry_number'],
        'entry_date' => $_POST['entry_date'],
        'supplier' => $_POST['supplier'],
        'supplier_dc' => $_POST['supplier_dc'],
        'supplier_dc_date' => $_POST['supplier_dc_date'],
        'fabric_dc' => $_POST['fabric_dc'],
        'inward_process' => $qyy['process'],
    );
    $ins = Insert('fabric_dc_inw', $arry);

    $inid = mysqli_insert_id($mysqli);
    timeline_history('Insert', 'fabric_dc_inw', mysqli_insert_id($mysqli), 'Fabric DC Inwarded. Ref: ' . $_POST['entry_number']);


    for ($m = 0; $m < count($_POST['fabric_dc_det_id']); $m++) {


        if($_POST['inw_qty'][$m]>0) {
            $rwqq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT fabric_id, order_id, style_id, fab_req_id FROM fabric_dc_det WHERE id = ". $_POST['fabric_dc_det_id'][$m]));
            
            $reqId = $rwqq['fab_req_id'];
            $arry = array(
                'process_id' => $qyy['process'],
                // 'grn_number' => $_POST['grn_number'],
                // 'supp_dc_number' => $_POST['supp_dc_number'],
                'supplier' => $_POST['supplier'],

                'order_id' => $rwqq['order_id'],
                'style_id' => $rwqq['style_id'],
                'fabric_id' => $rwqq['fabric_id'],
                'fab_req_id' => $reqId,

                'fabric_dc_inw' => $inid,
                'fabric_dc_det_id' => $_POST['fabric_dc_det_id'][$m],
                'dc_qty' => $_POST['dc_qty'][$m],
                'inw_qty' => $_POST['inw_qty'][$m],
                'inw_bag_roll' => $_POST['inw_bag_roll'][$m],
                'balance_qty' => $_POST['balance_qty'][$m],
            );

            // if ($_POST['fabric_dc_inw_det_id'][$m] == "") {
                $ins = Insert('fabric_dc_inw_det', $arry);
                $inid3 = mysqli_insert_id($mysqli);
                // timeline_history('Insert', 'fabric_dc_inw_det', mysqli_insert_id($mysqli), 'Fabric Inwarded DC Detail Inserted. Ref: '. $_POST['dc_number']);
            // } else {
            //     $ins = Update('fabric_dc_inw_det', $arry, 'WHERE id =' . $_POST['fabric_dc_inw_det_id'][$m]);
            //     $inid3 = $_POST['fabric_dc_inw_det_id'][$m];
            //     // timeline_history('Update', 'fabric_dc_inw_det', $_POST['fabric_dc_inw_det_id'][$m], 'Fabric Inwarded DC Detail Inserted. Ref: '. $_POST['dc_number']);
            // }
            
            $inw_qty = $_POST['inw_qty'][$m];

            $hk = mysqli_query($mysqli, "SELECT id, stock_qty FROM fabric_stock WHERE fabric_requirements = '" . $reqId . "' AND stock_status = 'supplier_stock' ORDER BY id ASC");
            while ($rows = mysqli_fetch_array($hk)) {
                
                $stock_qty = $rows['stock_qty'];
                $new_stock_qty = $stock_qty;
                $stock_status = 'supplier_stock';

                
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
            
            $reqq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_requirements WHERE id = " . $reqId));
            $yfab = ($reqq['yarn_id'] != NULL) ? 'yarn_id' : 'fabric_id';
            $yfab_val = ($reqq['yarn_id'] != NULL) ? $reqq['yarn_id'] : $reqq['fabric_id'];

            
            $stockk = array(
                'fabric_requirements' => $reqq['id'],
                'order_id' => $reqq['order_id'],
                'style_id' => $rwqq['style_id'],
                'process_id' => $reqq['process_id'],
                'process_order' => $reqq['process_order'],
                'req_qty' => $reqq['req_wt'],
                $yfab => $yfab_val,
                'stock_status' => 'in_stock',

                'stock_from' => 'fabric_dc_inw_det',
                'stock_id' => $inid3,
                'entry_grn_number' => $_POST['entry_number'],
                'received_bag' => $_POST['inw_bag_roll'][$m],
                'received_qty' => $_POST['inw_qty'][$m],
                'stock_qty' => $_POST['inw_qty'][$m],
                'created_by' => $logUser,
                'created_unit' => $logUnit
            );
            
            $ins = Insert('fabric_stock', $stockk);
        }
    }

// print '';
    $_SESSION['msg'] = "added";
    header("Location:fab_po_inw_list.php");
    exit;
    
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_dc_inw WHERE id=" . $id));
} else {
    $id = '';
}

$ID = $_GET['id'];

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Fabric Process DC Inward</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-119386393-1');
    </script>
</head>

<style>
    .ui-menu-item-wrapper {
        background-color: #eae8e8 !important;
        padding: 10px;
        width: 20% !important;
        border-bottom: 1px solid #c6c5c5;
    }
    
    
    .mw-150 {
        min-width: 150px
    }
    
    .hov_show {
        display: none;
    }
    
    .td_edDl:hover .hov_show {
        display: block;
    }
    
    /*.td_edDl {*/
    /*    display: flex;*/
    /*}*/
    
    
    @media (max-width: 479px) {
        /*.td_edDl {*/
        /*    min-width: 50px;*/
        /*}*/
    }
</style>

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">
        
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                    
                <!-- Default Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    <?php if(FAB_PO_INW_ADD!=1 || FAB_PO_INW_EDIT !=1) { action_denied(); exit; } ?>
                        
                        
                    <div class="pd-20">
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <a class="btn btn-outline-primary" href="fab_po_inw_list.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Process DC List</a>
                            <a class="btn btn-outline-info" href="mod_fabric.php"><i class="fa fa-home" aria-hidden="true"></i> Fabric</a>
						</div>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">New Fabric Process DC Inward</h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="add-poForm" method="post" autocomplete="off">
                            
                        <input type="hidden" name="dc_idd" id="dc_idd" value="<?= $sql['id']; ?>">
                            
                        <div class="row">
                                
                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['entry_number'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM fabric_dc_inw WHERE entry_number LIKE '%DC-GRN-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'DC-GRN-1';
                                } else {
                                    $ex = explode('-', $sqql['entry_number']);
                                        
                                    $value = $ex[2];
                                    $intValue = (int) $value;
                                    $newValue = $intValue + 1;
                                    $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);
                                        
                                    $code = $ex[0] . '-' . $ex[1] . '-' . $nnum;
                                }
                            }
                            ?>
                            
                            <div class="col-md-2 pe-none">
                                <label class="col-form-label">GRN Number <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" readonly name="entry_number" class="form-control" value="<?= $code; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">GRN Date <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="date" name="entry_date" class="form-control" value="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2 <?= $_GET['id'] ? 'pe-none' : ''; ?>">
                                <label class="col-form-label">Supplier <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="supplier" id="supplier" class="custom-select2 form-control" style="width:100%">
                                        <?= select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $sql['supplier'], '', ''); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2 <?= $_GET['id'] ? 'pe-none' : ''; ?>">
                                <label class="col-form-label">Supplier DC Number <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" name="supplier_dc" id="supplier_dc" class="form-control" placeholder="Supplier DC Number" value="<?= $sql['supplier_dc']; ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-2 <?= $_GET['id'] ? 'pe-none' : ''; ?>">
                                <label class="col-form-label">Supplier DC Date <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="date" name="supplier_dc_date" class="form-control" value="<?= $sql['supplier_dc_date'] ? $sql['supplier_dc_date'] :date('Y-m-d'); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2 <?= $_GET['id'] ? 'pe-none' : ''; ?>">
                                <label class="col-form-label">Process DC <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="fabric_dc" id="fabric_dc" class="custom-select2 form-control" style="width:100%">
                                        <?= $_GET['id'] ? select_dropdown('fabric_dc', array('id', 'dc_number'), 'dc_number DESC', $sql['fabric_dc'], '', '') : ''; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row justify-content-end">
                            
                            <div class="col-md-2 inwType <?= ($sql['process'] == 'multi') ? '' : 'd-none'; ?>">
                                <label class="col-form-label">Inward Type <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="inward_type" id="inward_type" class="custom-select2 form-control" style="width:100%">
                                        <option value="direct">Direct Inward</option>
                                        <option value="multi" <?= ($sql['inward_type'] == 'multi') ? 'selected' : ''; ?>>Multi Process Inward</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2 multiInDiv <?= ($sql['inward_type'] == 'multi') ? '' : 'd-none'; ?>">
                                <label class="col-form-label">Inward Process <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="inward_process[]" id="inward_process" class="custom-select2 form-control" style="width:100%" multiple>
                                        <?= select_dropdown_multiple('process', array('id', 'process_name'), 'process_name ASC', $sql['inward_process'], '', '1'); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <?php if(!isset($_GET['id'])) { ?>
                                <!-- <div class="col-md-2 ta-left">
                                    <label class="col-form-label">&nbsp;</label>
                                    <div class="form-group">
                                        <a class="btn btn-outline-primary addInward"><i class="fa fa-plus"></i> Inward</a>
                                    </div>
                                </div> -->
                            <?php } ?>
                        </div>
                        
                        
                        
                        <div style="overflow-y: auto;">
                            <?php //if(isset($_GET['id'])) { ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>BO</th>
                                            <th>Required Component</th>
                                            <th>DC QTY/Wt</th>
                                            <th>Received Bag/ Roll</th>
                                            <th>Inward Qty</th>
                                            <th colspan="2">Balance Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody id="inward_tbody">
                                        <?php
                                        $qryz = mysqli_query($mysqli, "SELECT * FROM fabric_dc_inw_det WHERE grn_number LIKE '%M-GRN-%' ORDER BY id DESC");
                                        $sqql = mysqli_fetch_array($qryz);
                                        $numm = mysqli_num_rows($qryz);
                                        if ($numm == 0) {
                                            $qqw = 0;
                                        } else {
                                            $ex = explode('-', $sqql['grn_number']);
                                                
                                            $value = $ex[2];
                                            $intValue = (int) $value;
                                            $newValue = $intValue + 1;
                                            $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);
                                                
                                            $qqw = $nnum;
                                        }
                                        
                                        // print_r($sql['inward_process']); exit;
                                        $hhn = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_dc WHERE id='". $sql['fabric_dc'] ."' "));
                                        
                                        // 22 Knitting Process
                                        if($hhn['process'] != 22) {
                                            $ppw = $qqw + 1;
                                            foreach(explode(',', $sql['inward_process']) as $inProcess) {
                                                
                                                $mGRN = 'M-GRN-'. $ppw;
                                                
                                                $inw_det = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_dc_inw_det WHERE fabric_dc_inw = '". $ID ."' AND process_id = '". $inProcess ."'"));
                                            ?>
                                                <tr>
                                                    <td><input type="hidden" name="process_id[]" value="<?= $inProcess; ?>"> <b><?= process_name($inProcess); ?></b></td>
                                                    <td>
                                                        <label>GRN Number :</label>
                                                        <input type="text" name="grn_number<?= $inProcess; ?>" id="" class="form-control mw-200" placeholder="GRN Number" value="<?= $inw_det['grn_number'] ? $inw_det['grn_number'] : $mGRN; ?>" readonly>
                                                    </td>
                                                    <td>
                                                        <label>Supplier DC Number :</label>
                                                        <input type="text" name="supp_dc_number<?= $inProcess; ?>" id="" class="form-control mw-200" placeholder="Supplier DC Number" value="<?= $inw_det['supp_dc_number'] ? $inw_det['supp_dc_number'] : ''; ?>">
                                                    </td>
                                                    <td>
                                                        <label>Supplier :</label>
                                                        <select name="supplier<?= $inProcess; ?>" id="" class="custom-select2 form-control" style="width:100%">
                                                            <?= select_dropdown_multiple('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $inw_det['supplier'], '', ''); ?>
                                                        </select>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            <?php
                                                $qry1 = "SELECT a.* ";
                                                $qry1 .= " FROM fabric_dc_det a ";
                                                $qry1 .= " LEFT JOIN fabric_dc b ON a.fabric_dc_id = b.id ";
                                                $qry1 .= " WHERE a.fabric_dc_id = '". $sql['fabric_dc'] ."' ";
                                                  
                                                $temp1 = mysqli_query($mysqli, $qry1); 
                                                
                                                $i=0;
                                                while($row = mysqli_fetch_array($temp1)) {
                                                    
                                                    // 22 Knitting Process
                                                    if($row['process_id']==22) {                                                        
                                                        $compnt = mas_yarn_name($row['yarn_id']);
                                                    } else {                                                        
                                                        $compnt = fabric_name($row['fabric_id']) .' || '. color_name($row['color_id']);
                                                    }
                                                    
                                                    $dc_in = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_dc_inw_det WHERE fabric_dc_inw = '". $ID ."' AND fabric_dc_det_id = '". $row['id'] ."' AND process_id = '". $inProcess ."'"));
                                                    
                                                    $dcq = $dc_in['dc_qty'] ? $dc_in['dc_qty'] : $row['dc_qty_wt'];
                                                    $iwq = $dc_in['inw_qty'] ? $dc_in['inw_qty'] : '';
                                                     
                                                     
                                                    $gf = $row['id'].",'fabric_po_receipt_det'";
                                                     
                                                    $edit = (FAB_PO_INW_EDIT==1) ? '<a class="d-none border border-info rounded text-info text-center hov_show editRw" data-id="'. $row['id'] .'" title="Edit"><i class="fa fa-pencil"></i></a>': '';
                                                    $delete = (FAB_PO_INW_DELETE==1) ? '<a class="d-none border border-danger rounded text-danger text-center hov_show" onclick="delete_da('. $gf .')" title="Delete"><i class="fa fa-trash"></i></a>': '';
                                                    
                                                    ?>
                                                        <tr class="td_edDl">
                                                            <td><?= sales_order_code($row['order_id']).' | '.sales_order_style($row['style_id']); ?></td>
                                                            <td><?= $compnt; ?></td>
                                                            <td>
                                                                <input type="hidden" name="fabric_dc_inw" value="<?= $ID; ?>">
                                                                <input type="hidden" name="fabric_dc_inw_det_id<?= $inProcess; ?>[]" value="<?= $dc_in['id']; ?>">
                                                                <input type="hidden" name="fabric_dc_det_id<?= $inProcess; ?>[]" value="<?= $row['id']; ?>">
                                                                <input type="hidden" name="dc_qty<?= $inProcess; ?>[]" value="<?= $dcq; ?>">
                                                                <input type="hidden" name="balance_qty<?= $inProcess; ?>[]" id="balance_qty<?= $row['id']; ?>" value="<?= $dcq; ?>">
                                                                <?= $dcq; ?>
                                                            </td>
                                                            <td><input type="number" value="<?= $iwq; ?>" name="inw_qty<?= $inProcess; ?>[]" placeholder="Inward Qty" class="form-control mw-200 inw_qty" data-max="<?= $dcq; ?>" data-tid="<?= $row['id']; ?>"> </td>
                                                            <td class="balance<?= $row['id']; ?>"><?= $dcq; ?></td>
                                                            <td class="d-flex"><?= $edit; ?>&nbsp;<?= $delete; ?></td>
                                                        </tr>
                                                    <?php
                                                $i++; }
                                            $ppw++;
                                            }
                                            
                                        } else {
                                            // 22 Knitting Process
                                            $inProcess = 22;
                                            
                                            $qry1 = "SELECT * ";
                                            $qry1 .= " FROM fabric_dc a ";
                                            $qry1 .= " LEFT JOIN fabric_dc_det b ON a.id = b.fabric_dc_id ";
                                            $qry1 .= " WHERE a.id = '". $sql['fabric_dc'] ."' GROUP BY b.process_id, b.order_id ";
                                            
                                            $temp1 = mysqli_query($mysqli, $qry1); 
                                            
                                            $i=0;
                                            while($row = mysqli_fetch_array($temp1)) {
                                                
                                                if($row['process_id']==22) {
                                                    
                                                    $compnt = fabric_name($row['fabric_id']);
                                                } else {
                                                    
                                                    $compnt = fabric_name($row['fabric_id']) .' || '. color_name($row['color_id']);
                                                }
                                                
                                                $dc_in = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_dc_inw_det WHERE fabric_dc_inw = '". $ID ."' AND fabric_dc_det_id = '". $row['id'] ."' AND process_id = '". $inProcess ."'"));
                                                
                                                $dcq = $dc_in['dc_qty'] ? $dc_in['dc_qty'] : $row['dc_qty_wt'];
                                                $iwq = $dc_in['inw_qty'] ? $dc_in['inw_qty'] : '';
                                                 
                                                 
                                                $gf = $row['id'] .", 'fabric_po_receipt_det'";
                                                
                                                $edit = (FAB_PO_INW_EDIT==1) ? '<a class="d-none border border-info rounded text-info text-center hov_show editRw" data-id="'. $row['id'] .'" title="Edit"><i class="fa fa-pencil"></i></a>': '';
                                                $delete = (FAB_PO_INW_DELETE==1) ? '<a class="d-none border border-danger rounded text-danger text-center hov_show" onclick="delete_da('. $gf .')" title="Delete"><i class="fa fa-trash"></i></a>': '';
                                                
                                                ?>
                                                    <tr class="d-none td_edDl">
                                                        <td><?= sales_order_code($row['order_id']).$row['inward_process']; ?></td>
                                                        <td><?= $compnt; ?></td>
                                                        <td>
                                                            <input type="hidden" name="process_id[]" value="<?= $inProcess; ?>"> 
                                                            <input type="hidden" name="fabric_dc_inw" value="<?= $ID; ?>">
                                                            <input type="hidden" name="fabric_dc_inw_det_id<?= $inProcess; ?>[]" value="<?= $dc_in['id']; ?>">
                                                            <input type="hidden" name="fabric_dc_det_id<?= $inProcess; ?>[]" value="<?= $row['id']; ?>">
                                                            <input type="hidden" name="dc_qty<?= $inProcess; ?>[]" value="<?= $dcq; ?>">
                                                            <input type="hidden" name="balance_qty<?= $inProcess; ?>[]" id="balance_qty<?= $row['id']; ?>" value="<?= $dcq; ?>">
                                                            
                                                            <input type="hidden" name="fab_req_id<?= $inProcess; ?>[]" id="fab_req_id<?= $row['id']; ?>" value="<?= $row['fab_req_id']; ?>">
                                                            <?= $dcq; ?>
                                                        </td>
                                                        <td><input type="number" value="<?= $iwq; ?>" name="inw_qty<?= $inProcess; ?>[]" placeholder="Inward Qty" class="form-control mw-200 inw_qty" data-max="<?= $dcq; ?>" data-tid="<?= $row['id']; ?>" required> </td>
                                                        <td class="balance<?= $row['id']; ?>"><?= $dcq; ?></td>
                                                        <td class="d-flex"><?= $edit; ?>&nbsp;<?= $delete; ?></td>
                                                    </tr>
                                                <?php
                                            $i++; }
                                        }
                                        
                                        


                                        // new
                                        if(isset($_GET['id'])) {

                                            $h = mysqli_query($mysqli, "SELECT * FROM fabric_dc_inw_det WHERE fabric_dc_inw = ". $_GET['id']);
                                            while($res = mysqli_fetch_array($h)) {
                                                print '<tr>
                                                        <td>'. sales_order_code($res['order_id']) .' | '. sales_order_style($res['style_id']) .'</td>
                                                        <td>'. fabric_name($res['fabric_id']) .'</td>
                                                        <td>'. $res['dc_qty'] .'</td>
                                                        <td>'. $res['inw_bag_roll'] .'</td>
                                                        <td>'. $res['inw_qty'] .'</td>
                                                        <td>'. $res['balance_qty'] .'</td>
                                                    </tr>';
                                            }
                                        } else {
                                            print '<tr><td colspan="6  " class="text-center">No data found..</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            <?php //} ?>
                        </div>
                            
                            
                        <hr>
                        
                        <?php //if(isset($ID)) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" style="text-align: center;">
                                        <button name="saveBtn" type="submit" class="btn btn-outline-primary saveBtn d-none">Save DC Inward</button>
                                    </div>
                                </div>
                            </div>
                        <?php //} ?>
                            
                        <div class="modal fade bs-example-modal-lg" id="plan_addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-top" style="max-width:1000px;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myLargeModalLabel">Add Delivery Details</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <div class="modal-body" id="modal_body">
                                        <div id="i_spinner" class="text-center"><i class="icon-copy fa fa-spinner" aria-hidden="true"></i> Loading..</div>
                                        
                                        <table class="table">
                                            <thead id="mod_thead"></thead>
                                            <tbody id="mod_tbody"></tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-outline-primary"><i class="fa fa-plus"></i> Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                    </form>
                </div>
                    
            </div>
            <?php include('includes/footer.php'); ?>
            <?php include('modals.php'); ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>
    
    
<script>
    $("#inward_type").change(function() {
        
        if($(this).val() == 'multi') {
            $(".multiInDiv").removeClass('d-none');
        } else {
            $(".multiInDiv").addClass('d-none');
        }
    });
</script>


<script>
    // $(".saveBtn").click(function() {
    //     var err = required_validation('add-poForm');
    //     if(err==0) {
    //         var form = $("#add-poForm").serialize();
                
    //         $.ajax({
    //             type:'POST',
    //             url:'ajax_action2.php?save_dcInward_list',
    //             data: form,
                
    //             success:function(msg){
    //                 var json = $.parseJSON(msg);
                    
    //                 if(json.result==0) {
                        
    //                     // if(id=="") {
    //                         message_noload('success', 'DC Created.', 1500);
    //                         setTimeout(function() {
    //                             window.location.href="fab_po_inw_list.php";
    //                         }, 1500);
    //                     // } else {
                            
    //                     //     message_reload('success', 'DC List Added.', 1500);
    //                     //     setTimeout(function() {
    //                     //         window.location.href="fab_po_inw.php?id=" + json.inid;
    //                     //     }, 1500);
    //                     // }
                        
    //                 } else {
    //                     message_noload('error', 'Error!', 1500);
    //                 }
    //             }
    //         });
    //     }
    // });
</script>

<script>

    function validate_maxqty(element) {
        var val = $(element).val() ? $(element).val() : 0;
        var max = $(element).data('max');
        var tid = $(element).data('tid');

        if(parseFloat(max)<parseFloat(val)) {
            $(element).val(max);
            $(element).select();
            
            $(".balance" + tid).text('0');
            $("#balance_qty" + tid).val('0');
        } else {
            bal = parseFloat(max) - parseFloat(val);
            $("#balance_qty" + tid).val((bal.toFixed(2)));
        }
        
        var ttt = $("#balance_qty" + tid).val();
        
        $("#balance" + tid).text(ttt);
        
    }
</script>

<script>
    $(".inw_qty").keyup(function() {
        alert('ewr';);

        // var max = $(this).data('max');
        // var val = $(this).val();
        
        // if(parseFloat(max)<parseFloat(val)) {
        //     $(this).val(max);
        //     $(this).select();
        // }
        
        // var tid = $(this).data('tid');
        
        // bal = parseFloat(max) - parseFloat(val);
        
        // $(".balance" + tid).text(bal);
        // $("#balance_qty" + tid).val(bal);
        
    })
</script>

<script>
    // $("#fabric_dc").change(function() {
        
    //     var process = $("#fabric_dc option:selected").data('process');
        
    //     if(process == 22) {
    //         $(".inwType").addClass('d-none');
    //         $(".multiInDiv").addClass('d-none');
    //         $("#inward_type").html('<option value="direct">Direct Inward</option><option value="multi">Multi Process Inward</option>');
    //     } else {
    //         $(".inwType").removeClass('d-none');
    //         $("#inward_type").html('<option value="direct">Direct Inward</option><option value="multi">Multi Process Inward</option>');
    //     }
    // });
</script>

<script>
    // $(".addInward").click(function() {
    $("#fabric_dc").change(function() {
        
        var supplier = $("#supplier").val();
        var supplier_dc = $("#supplier_dc").val();
        var fabric_dc = $("#fabric_dc").val();
        var inward_type = $("#inward_type").val();
        var inward_process = $("#inward_process").val();
        
        if(supplier=="") {
            
            message_noload('error', 'Select Supplier!');
            return false;
        // } else if(supplier_dc=="") {
        //     $("#supplier_dc").focus();
        //     message_noload('error', 'Enter Supplier DC Number!', 1500);
        //     return false;
        } else if(fabric_dc=="") {

            $(".saveBtn").addClass('d-none');
            
            $("#inward_tbody").html('<tr><td class="text-center" colspan="6">No data found..</td></tr>');
            
            // message_noload('error', 'Select Process DC!');
            return false;
        } else if(inward_type=="multi" && inward_process == "") {
            
            message_noload('error', 'Select Inward Process!');
            return false;
        } else {
            var form = $("#add-poForm").serialize();
            
            $.ajax({
                type:'POST',
                url:'ajax_action2.php?insert_inwarded_dc',
                data: form,
                
                success:function(msg){
                    var json = $.parseJSON(msg);
                    
                    $("#inward_tbody").html(json.tbody);
                    $(".saveBtn").removeClass('d-none');
                    
                    // if(json.result==0) {
                        
                        // if(id=="") {
                            // message_noload('success', 'DC Created.', 1500);
                            // setTimeout(function() {
                                // window.location.href="fab_po_inw.php?id=" + json.inid;
                            // }, 1500);
                        // } else {
                            
                        //     message_reload('success', 'DC List Added.', 1500);
                        //     setTimeout(function() {
                        //         window.location.href="fab_po_inw.php?id=" + json.inid;
                        //     }, 1500);
                        // }
                        
                    // } else {
                    //     message_noload('error', 'Error!', 1500);
                    // }
                }
            })
        }
    });
</script>


<script>
    $("#supplier").change(function() {
        
        var supplier = $("#supplier").val();
        var data = 'supplier=' + supplier;
        
        $.ajax({
            type:'POST',
            url:'ajax_search2.php?not_Inwarded_fabDc',
            data: data,
            success:function(msg){
                var json = $.parseJSON(msg);
                
                // $("#po_stage").html(json.po_stage);
                $("#fabric_dc").html(json.process);
            }
        });
    });
</script>

</body>

</html>