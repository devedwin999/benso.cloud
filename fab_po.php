<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_POST['saveBtn'])) {
    
    $up = Update('fabric_po', array('grand_total' => $_REQUEST['grand_total']), ' WHERE id = '. $_GET['id']);
    
    // timeline_history('Update', 'fabric_po', $_REQUEST['id'], 'Employee Request Rejected.');
    $_SESSION['msg'] = "added";

    header("Location:fab_po_list.php");

    exit;
    
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comp = 'Edit Processing';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_po WHERE id=" . $id));
} else {
    $comp = 'Add Processing';
    $id = '';
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Fabric Purchase Order</title>

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
                    <?php page_spinner(); if(FAB_PO_ADD!=1 || FAB_PO_EDIT !=1) { action_denied(); exit; } ?>
                    
                    <div class="alert alert-warning fade show d-none" role="alert">
                        <a onclick="showImpt()" class="a_click"><i class="icon-copy ion-chevron-right icc_imp"></i> <strong>Important! <i class="icon-copy fa fa-hand-o-down" aria-hidden="true"></i></strong></a><br>
                        
                        <span class="sub d-none">
                            <i class="icon-copy fa fa-hand-o-right" aria-hidden="true"></i> Scanned bundle details are saved <b>Temporarily</b>. <br>
                            <i class="icon-copy fa fa-hand-o-right" aria-hidden="true"></i> To save, click the <b>Save</b> button .<br>
                            <i class="icon-copy fa fa-hand-o-right" aria-hidden="true"></i> To skip saving, click the <b>Cancel Scanning</b> button.
                        </span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-top: -25px;"><span aria-hidden="true">×</span></button>
                    </div>
                        
                    <script>
                        function showImpt() {
                            var a = $(".icc_imp").hasClass('ion-chevron-right');
                            if(a==true) {
                                $(".icc_imp").removeClass('ion-chevron-right');
                                $(".icc_imp").addClass('ion-chevron-down');
                                $(".sub").fadeIn();
                                $(".sub").removeClass('d-none');
                            } else {
                                $(".icc_imp").addClass('ion-chevron-right');
                                $(".icc_imp").removeClass('ion-chevron-down');
                                $(".sub").fadeOut();
                            }
                        }
                    </script>
                        
                    <div class="pd-20">
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <a class="btn btn-outline-primary" href="fab_po_list.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Fabric PO List</a>
                            <a class="btn btn-outline-info" href="mod_fabric.php"><i class="fa fa-home" aria-hidden="true"></i> Fabric</a>
						</div>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">New Fabric Purchase Order</h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="add-poForm" method="post" autocomplete="off">
                            
                        <input type="hidden" name="po_idd" id="po_idd" value="<?= $sql['id']; ?>">
                            
                        <div class="row">
                                
                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['entry_number'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM fabric_po WHERE entry_number LIKE '%PO-FAB-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'PO-FAB-1';
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
                            <div class="col-md-2">
                                <label class="col-form-label">Entry Number <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" readonly name="entry_number" class="form-control" value="<?= $code; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">Entry Date <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="date" name="entry_date" class="form-control" value="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label" for="supplier">Supplier <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="supplier" id="supplier" class="custom-select2 form-control" style="width:100%" required>
                                        <?= select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $sql['supplier'], '', ''); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                            
                        <div style="overflow-y: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>BO | Style</th>
                                        <th>Purchase Stage</th>
                                        <th>Material Name</th>
                                        <th>PO BALANCE</th>
                                        <th>Bag / Roll</th>
                                        <th>PO QTY/Wt</th>
                                        <th>Rate</th>
                                        <th>Tax %</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM fabric_po_det WHERE fab_po = '". $_GET['id'] ."'"));
                                    
                                    if(isset($_GET['id']) && $count>0) {
                                        
                                        $qry1 = "SELECT a.*, b.order_code, c.process_name, f.full_name, c.budget_type, a.material_name, a.color_ref, a.tax_per, d.yarn_id, d.fabric_id, d.color as color_id, d.dia_size, d.yarn_mixing ";
                                        $qry1 .= " FROM fabric_po_det a ";
                                        $qry1 .= " LEFT JOIN sales_order b ON b.id = a.order_id ";
                                        $qry1 .= " LEFT JOIN process c ON c.id = a.po_stage ";
                                        $qry1 .= " LEFT JOIN fabric_requirements d ON d.id = a.material_name ";
                                        $qry1 .= " LEFT JOIN tax_main f ON f.id = a.tax_per ";
                                        $qry1 .= " WHERE a.fab_po = '". $_GET['id'] ."' ORDER BY id ASC";
                                        
                                        $temp1 = mysqli_query($mysqli, $qry1); 
                                        
                                        $i=0;
                                        while($row = mysqli_fetch_array($temp1)) {
                                            
                                            if($row['stock_bo']=='bo') {
                                                if($row['budget_type'] == 'Yarn') {
                                                    
                                                    $td3 = mas_yarn_name($row['yarn_id']);
                                                    
                                                // } else if($row['budget_type'] == 'Fabric') {
                                                } else {
                                                    
                                                    $opp = '';
                                                    foreach(json_decode($row['yarn_mixing']) as $expp) {
                                                        $exp = explode('=', $expp);
                                                        
                                                        $opp .= " || ". mas_yarn_name($exp[0]) .' - '. color_name($exp[1]) .' - '. $exp[2] .'%';
                                                    }
                                                    
                                                    $td3 = fabric_name($row['fabric_id']) .'|| '. $opp .' || Dia: '. $row['dia_size'] .'.';
                                                // } else {
                                                //     $td3 = '';
                                                }
                                                $order_code = sales_order_code($row['order_id']).' | '. sales_order_style($row['style_id']);
                                                $po_bal = $row['po_balance'];
                                            } else {
                                                $order_code = stockgroup_name($row['order_id']);
                                                
                                                if(in_array($row['po_stage'], array('26', '27', '28'))) {
                                                    $td3 = mas_yarn_name($row['material_name']);
                                                } else {
                                                    $stk = mysqli_fetch_array(mysqli_query($mysqli, "SELECT fabric_name, yarn_mixing FROM mas_stockitem WHERE id = '". $row['material_name'] ."'"));
                                                        
                                                    $opp = '';
                                                    foreach(json_decode($stk['yarn_mixing']) as $expp) {
                                                        $exp = explode('=', $expp);
                                                        
                                                        $opp .= " || ". mas_yarn_name($exp[0]) .' - '. color_name($exp[1]) .' - '. $exp[2] .'%';
                                                    }
                                                    
                                                    $diaa = ($row['stock_dia']>0) ? ' Dia : '. $row['stock_dia'] : '';
                                                    
                                                    $td3 = fabric_name($stk['fabric_name']) . $opp . $diaa;
                                                    
                                                }
                                                
                                                $po_bal = '-';
                                            }
                                            
                                            print '<tr class="td_edDl"><td>'. $order_code .'</td><td>'. $row['process_name'] .'</td><td>'. $td3 .'</td><td>'. $po_bal .'</td>';
                                            
                                            if($_GET['rowEd']==$row['id']) {
                                                print '
                                                <td>'. $row['bag_roll'] .'</td>
                                                <td><input type="text" class="form-control" name="" id="e_po_qty_wt" value="'. $poq[] = $row['po_qty_wt'] .'"></td>
                                                <td><input type="text" class="form-control" name="" id="e_rate" value="'. $rat[] = $row['rate'] .'"></td>
                                                <td>
                                                    <select class="custom-select2 form-control" name="e_tax_per" id="e_tax_per" style="width:100%">
                                                    '. select_dropdown('tax_main', array('id', 'full_name'), 'full_name DESC', $row['tax_per'], '', '') .'
                                                    </select>
                                                    
                                                    <input type="hidden" name="e_id" id="e_id" value="'. $row['id'] .'">
                                                    <input type="hidden" name="e_amount" id="e_amount" value="'. $row['amount'] .'">
                                                </td>
                                                <td class="d-flex">
                                                    <a class="border border-success rounded text-success text-center saveEdit" data-id="'. $row['id'] .'" title="Save"><i class="fa fa-check"></i></a> &nbsp;
                                                    <a class="border border-secondary rounded text-secondary text-center cancelEdit" title="Cancel"><i class="icon-copy ion-close-round"></i></a>
                                                </td>
                                                <td style="text-align:right" class="e_amtTxt">'. $amt[] = $row['amount'] .'</td></tr>';
                                            } else {
                                                $gf = $row['id'].",'fabric_po_det'";
                                                $unity = $row['full_name'] ? $row['full_name'] : '-';
                                                
                                                print'
                                                <td>'. $row['bag_roll'] .'</td>
                                                <td>'. $poq[] = $row['po_qty_wt'] .'</td>
                                                <td>'. $rat[] = $row['rate'] .'</td>
                                                <td>'. $unity .'</td>
                                                <td style="text-align:right">'. $amt[] = $row['amount'] .'</td>
                                                <td class="d-flex">';
                                                if($row['complete_receipt']!='Yes') {
                                                    if(FAB_PO_EDIT==1) {
                                                        print '<a class="border border-info rounded text-info text-center hov_show editRw" data-id="'. $row['id'] .'" title="Edit"><i class="fa fa-pencil"></i></a> &nbsp;';
                                                    } if(FAB_PO_DELETE==1) {
                                                        print '<a class="border border-danger rounded text-danger text-center hov_show" onclick="delete_data('. $gf .')" title="Delete"><i class="fa fa-trash"></i></a>';
                                                    }
                                                }
                                                print '</td></tr>';
                                            }
                                        $i++; }
                                    }
                                    ?>
                                    <tr id="tableBody">
                                        <td class="mw-150">
                                            <select class="custom-select2 form-control" name="order_id" id="order_id" style="width:100%" required>
                                                <option value="">Select</option>
                                                <?php
                                                    // $ff = mysqli_query($mysqli, "SELECT * FROM stockgroup ORDER BY id DESC");
                                                    // while($row = mysqli_fetch_array($ff)) {
                                                    //     if(in_array($logUser, explode(',', $row['assigneduser']))) {
                                                    //         print '<option value="'. $row['id'] .'" data-val="stock">'. $row['groupname'] .'</option>';
                                                    //     }
                                                    // }
                                                    
                                                    $ff1 = mysqli_query($mysqli, "SELECT a.id, a.sales_order_id, a.style_no FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id = b.id WHERE b.is_dispatch IS NULL ORDER BY a.id DESC");
                                                    while($row1 = mysqli_fetch_array($ff1)) {
                                                        print '<option value="'. $row1['id'] .'" data-val="bo">'. sales_order_code($row1['sales_order_id']) .' | '. $row1['style_no'] .'</option>';
                                                    }
                                                ?>
                                            </select>
                                            
                                            <input type="hidden" name="stock_bo" id="stock_bo">
                                        </td>
                                        <td class="mw-150">
                                            <select class="custom-select2 form-control" name="po_stage" id="po_stage" style="width:100%" required></select>
                                        </td>
                                        <td class="mw-150">
                                            <select class="custom-select2 form-control" name="material_name" id="material_name" style="width:100%" required></select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control d-none max-width-100px" name="stock_dia" id="stock_dia" placeholder="Dia">
                                            <input type="text" class="form-control max-width-100px" name="po_balance" id="po_balance" placeholder="PO BALANCE" readonly>
                                        </td>
                                        <td><input type="number" class="form-control max-width-100px" name="bag_roll" id="bag_roll" placeholder="Bag / Roll"></td>
                                        <td><input type="number" class="form-control max-width-100px" name="po_qty_wt" id="po_qty_wt" placeholder="PO QTY/Wt" required></td>
                                        <td><input type="number" class="form-control max-width-100px" name="rate" id="rate" placeholder="Rate" required></td>
                                        <td>
                                            <select class="custom-select2 form-control" name="tax_per" id="tax_per" style="width:100%">
                                                <?= select_dropdown('tax_main', array('id', 'full_name'), 'full_name DESC', '', '', ''); ?>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control max-width-100px" name="amount" id="amount" placeholder="Amount" readonly></td>
                                        <td><button class="btn btn-outline-primary addBtn"><i class="fa fa-plus"></i> Add</button></td>
                                    </tr>
                                    
                                    <?php if(isset($_GET['id']) && $count>0) { ?>
                                        <tr>
                                            <td colspan="5" style="text-align:right">Total:</td>
                                            <td style=""><?= array_sum($poq); ?></td>
                                            <td colspan="3"></td>
                                            <td style="text-align:right"><?= number_format($amtt = array_sum($amt),2); ?></td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="6">
                                                
                                            </td>
                                            <td colspan="4">
                                                <table class="table">
                                                    <?php

                                                        $gt = [];
                                                        $rew = "SELECT sum((a.percentage/100)*b.amount) as pamt, a.full_name, a.percentage ";
                                                        $rew .= " FROM tax_sub a ";
                                                        $rew .= " LEFT JOIN fabric_po_det b ON a.tax_main=b.tax_per ";
                                                        $rew .= " WHERE b.fab_po = '". $_GET['id'] ."' GROUP BY a.id ASC";
                                                        // print $rew;
                                                        $ff = mysqli_query($mysqli, $rew);
                                                        if(mysqli_num_rows($ff)>0) {
                                                            while($po = mysqli_fetch_array($ff)) {
                                                                print '<tr><td>'. $po['full_name'] .' - '. $po['percentage'] .' %</td><td style="text-align:right">'. number_format($po['pamt'],2) .'</td></tr>';
                                                                $gt[] = $po['pamt'];
                                                            }
                                                        }
                                                        
                                                        
                                                        $rew = "SELECT b.expense_name, a.expense_amount ";
                                                        $rew .= " FROM fabric_po_expense a ";
                                                        $rew .= " LEFT JOIN expense_main b ON a.expense_name=b.id ";
                                                        $rew .= " WHERE a.fabric_po = '". $_GET['id'] ."' ";
                                                        // print $rew;
                                                        $ff = mysqli_query($mysqli, $rew);
                                                        if(mysqli_num_rows($ff)>0){
                                                            while($po = mysqli_fetch_array($ff)) {
                                                                print '<tr><td>'. $po['expense_name'] .'</td><td style="text-align:right">'. number_format($po['expense_amount'],2) .'</td></tr>';
                                                                $gt[] = $po['expense_amount'];
                                                            }
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td style="font-size:20px;">Grand Total :</td>
                                                        <td style="text-align:right;font-size:20px;">
                                                            <?= number_format((array_sum($gt) + $amtt), 2); ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                                
                                                <table class="table">
                                                    <tr class="addexp d-none">
                                                        <td>
                                                            <select class="custom-select2 form-control" name="expense_name" id="expense_name" style="width:100%">
                                                                <?= select_dropdown('expense_main', array('id', 'expense_name'), 'expense_name ASC', $sql['supplier'], '', ''); ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="expense_amount" id="expense_amount" placeholder="Amount">
                                                        </td>
                                                    </tr>
                                                </table>
                                                
                                                <a style="float:right;color:blue;" onclick="AddCost_new()" class="a_save"><i class="fa fa-plus tmpIcon"></i> <span class="IconText">Add Cost</span></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-right">
                                                <input type="hidden" value="<?= (array_sum($gt) + array_sum($amt)); ?>" id="grand_total" name="grand_total">
                                                <a class="btn btn-outline-secondary" onclick="window.location.href='fab_po_list.php'">Go Back</a>
                                                <a class="btn btn-outline-primary <?= ((array_sum($gt) + array_sum($amt))>0) ? 'saveGrandTotal' : 'd-none'; ?>">Save Purchase Order</a>
                                            </td>
                                            <td colspan="3"></td>
                                            <td colspan="2">
                                                <div class="<?= ($sql['ship_to']>0) ? '' : 'd-none'; ?> shipdiv">
                                                    <label>Shipping To :</label>
                                                    <select class="custom-select2 form-control" name="ship_to" id="ship_to" style="width:100%">
                                                        <?= select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $sql['ship_to'], '', ''); ?>
                                                    </select>
                                                    &nbsp;&nbsp;
                                                </div>
                                                
                                                <a style="float:right;color:blue;" class="<?= ($sql['ship_to']>0) ? 'd-none' : ''; ?> addshipping"><i class="icon-copy dw dw-delivery-truck-2" style="font-size:20px;"></i> <span class="IconText">Add Shipping</span></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                    </form>
                </div>

            </div>
            <?php include('includes/footer.php'); include('modals.php'); ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>


<script>
    $(".addshipping").click(function() {
        $(this).addClass('d-none');
        $(".shipdiv").removeClass('d-none');
    })
</script>

<script>
    $(".editRw").click(function() {
        var val = $(this).data('id');
        
        var idd = $("#po_idd").val();
        
        window.location.href="fab_po.php?id=" + idd + "&rowEd=" + val;
    });
    
    $(".cancelEdit").click(function() {
        
        var idd = $("#po_idd").val();
        
        window.location.href="fab_po.php?id=" + idd;
    });
    
    $(".saveEdit").click(function() {
        
        var e_po_qty_wt = $("#e_po_qty_wt").val();
        var e_rate = $("#e_rate").val();
        var e_tax_per = $("#e_tax_per").val();
        var e_amount = $("#e_amount").val();
        var e_id = $("#e_id").val();
        var entry_number = $("#entry_number").val();
        
        var idd = $("#po_idd").val();
        
        var data = 'e_po_qty_wt=' + e_po_qty_wt + '&e_rate=' + e_rate + '&e_tax_per=' + e_tax_per + '&e_amount=' + e_amount + '&e_id=' + e_id + '&entry_number=' + entry_number;
        
        
        swal({
            title: 'Are you sure?',
            text: "Do you want to save this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, change it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-success margin-5',
            cancelButtonClass: 'btn btn-danger margin-5',
            buttonsStyling: false
        }).then(function (dd) {
            if (dd['value'] == true) {
                $.ajax({
                    type:'POST',
                    url:'ajax_action2.php?save_PO_expense_edit=1',
                    data: data,
                    
                    success:function(msg){
                        var json = $.parseJSON(msg);
                        
                        if(json.result==0) {
                            
                            message_noload('success', 'Updated!', 1500);
                            setTimeout(function() {
                                window.location.href="fab_po.php?id=" + idd;
                            },1500)
                        } else {
                            message_noload('error', 'Error!', 1500);
                        }
                    }
                });
            } else {
                swal(
                    'Cancelled',
                    '',
                    'error'
                )
            }
        });
    });
</script>

<script>
    $(".saveGrandTotal").click(function() {
        
        var grand_total = $("#grand_total").val();
        var fabric_po = $("#po_idd").val();
        var ship_to = $("#ship_to").val();
        
        var data = 'grand_total=' + grand_total + '&fabric_po=' + fabric_po + '&ship_to=' + ship_to;
                
        $.ajax({
            type:'POST',
            url:'ajax_action2.php?saveGrandTotal=1',
            data: data,
            
            success:function(msg){
                var json = $.parseJSON(msg);
                
                if(json.result==0) {
                    
                    message_noload('success', 'Purchase Order Saved!', 1500);
                    setTimeout(function() {
                        window.location.href="fab_po_list.php";
                    }, 1500);
                } else {
                    message_noload('error', 'Error!', 1500);
                }
            }
        });
    });
</script>

<script>
    function AddCost_new() {
        
        var a = $(".tmpIcon").hasClass('fa-save');
        
        if(a==false) {
            $(".addexp").removeClass('d-none');
            
            $(".tmpIcon").addClass('fa-save');
            $(".tmpIcon").removeClass('fa-plus');
            $(".IconText").text('Save Cost');
            
        } else if(a==true) {
            
            var expense_amount = $("#expense_amount").val();
            var expense_name = $("#expense_name").val();
            var fabric_po = $("#po_idd").val();
            var entry_number = $("#entry_number").val();
            var grand_total = $("#grand_total").val();
            
            if(expense_name=="") {
                $("#expense_name").focus()
                message_noload('error', 'Selece Expense!', 1500);
                return false;
            } else if(expense_amount=="") {
                $("#expense_amount").focus()
                message_noload('error', 'Enter Amount!', 1500);
                return false;
            } else {
                
                $(".a_save").addClass('blockAll');
            
                var data = 'expense_name=' + expense_name +'&expense_amount=' + expense_amount + '&fabric_po=' + fabric_po + '&entry_number=' + entry_number + '&grand_total=' + grand_total;
                
                $.ajax({
                    type:'POST',
                    url:'ajax_action2.php?save_PO_expense=1',
                    data: data,
                    
                    success:function(msg){
                        var json = $.parseJSON(msg);
                        
                        if(json.result==0) {
                            // setTimeout()
                            location.reload();
                        } else {
                            message_noload('error', 'Error!', 1500);
                        }
                    }
                });
            }
        }
        
    }
</script>
    
<script>
//     $(document).ready(function() {
        
//         var id = <? //= $_GET['id']; ?>;
        
//         // getAdded_fabricPO(id);
//     })
</script>

<script>
    $("#e_po_qty_wt").keyup(function() { e_calAmt();});
    $("#e_rate").keyup(function() { e_calAmt();});
    
    
    function e_calAmt() {
        
        var po_qty_wt = $("#e_po_qty_wt").val();
        var rate = $("#e_rate").val();
        
        var vll = parseFloat(po_qty_wt)*parseFloat(rate);
        
        $("#e_amount").val(vll);
        $(".e_amtTxt").text(vll);
    }
</script>

<script>
    $("#po_qty_wt").keyup(function() { calAmt();});
    $("#rate").keyup(function() { calAmt();});
    
    
    function calAmt() {
        
        var po_qty_wt = $("#po_qty_wt").val();
        
        var bal = $("#po_balance").val();
        if(parseFloat(bal) < parseFloat(po_qty_wt)) {
            
            $("#po_qty_wt").val(parseFloat(bal));
            
            var nval = parseFloat(bal);
        } else {
            var nval = po_qty_wt;
        }
        
        var rate = $("#rate").val();
        var vll = parseFloat(nval)*parseFloat(rate);
        
        $("#amount").val(vll);
    }
</script>
    
<script>
    // function getAdded_fabricPO(id) {
        
    //     $.ajax({
    //         type:'POST',
    //         url:'ajax_search.php?getAdded_fabricPO=1&id=' + id,
            
    //         success:function(msg){
    //             var json = $.parseJSON(msg);
                
    //             $("#tableBody").before(json.tbody)
    //         }
    //     })
    // }
</script>
    
<script>
    $(".addBtn").click(function() {
        
        calAmt();
        
        var form = $("#add-poForm").serialize();
        
        var err = required_validation('add-poForm');
        
        if(err==0) {
            $(this).prop('disabled', true);
            var id = $("#po_idd").val();
            
            $.ajax({
                type:'POST',
                url:'ajax_action.php?addFabric_PO',
                data: form,
                
                success:function(msg){
                    var json = $.parseJSON(msg);
                    
                    if(json.res==0) {
                        // alert();
                        if(id=="") {
                            // message_noload('success', 'PO Created.', 1500);
                            $("#overlay").fadeIn(100);
                            setTimeout(function() {
                                window.location.href="fab_po.php?id=" + json.po_idd;
                            }, 1000);
                        } else {
                            // getAdded_fabricPO(json.po_idd);
                            // message_reload('success', 'PO List Added.', 1500);
                            $("#overlay").fadeIn(100);
                            setTimeout(function() {
                                window.location.href="fab_po.php?id=" + json.po_idd;
                            }, 1500);
                        }
                        // $("#po_idd").val(json.po_idd);
                    } else {
                        message_noload('error', 'Error!', 1500);
                    }
                }
            })
        }
    });
</script>

<script>
    $("#order_id").change(function() {
        
        var data = {
            id : $(this).val(),
        };
        
        var type = $("#order_id option:selected").data('val');
        
        $("#stock_bo").val(type);
        
        var i = 0;
        
        if(type=='bo') {
            
            $("#overlay").fadeIn(100);
            $("#stock_dia").addClass('d-none');
            $("#po_balance").removeClass('d-none');
            $.ajax({
                type:'POST',
                url:'ajax_search.php?validate_budgetApprove',
                data: data,
                
                success:function(msg){
                    var json = $.parseJSON(msg);
                    
                    $("#overlay").fadeOut(500);
                    if(json.approve!='true') {
                        
                        message_noload('error', 'Budget Not Approved for this BO!');
                        $("#po_stage").html('');
                        $("#material_name").html('');
                        return false;
                    } else {
                        
                        $.ajax({
                        type:'POST',
                        url:'ajax_search.php?getFab_puechase_stage',
                        data: data,
                
                        success:function(msg){
                            var json = $.parseJSON(msg);
                            
                            $("#po_stage").html(json.po_stage);
                            // $("#material_name").html(json.material_name);
                        }
                    });
                    }
                }
            });
        } else if(type=='stock') {
            $("#overlay").fadeIn(100);
            $("#stock_dia").removeClass('d-none');
            $("#po_balance").addClass('d-none');
            $.ajax({
                type: 'POST',
                url: 'ajax_search2.php?get_all_process',
                success: function(msg) {
                    var json = $.parseJSON(msg);
                    $("#overlay").fadeOut(500);
                    $("#po_stage").html(json.process_list);
                }
            })
        }
    });
</script>

<script>
    $("#po_stage").change(function() {
        
        var id = $(this).val();
        var style_id = $("#order_id").val();
        var stock_bo = $("#stock_bo").val();
        
        var data = {
            process_id : id,
            style_id: style_id,
            stock_bo: stock_bo,
        }


        $("#overlay").fadeIn(100);
        $.ajax({
            type:'POST',
            url:'ajax_search2.php?getFab_puechase_material_Name',
            data:data,
            
            success:function(msg){
                var json = $.parseJSON(msg);
                $("#overlay").fadeOut(500);
                $("#material_name").html(json.material_name);
                $("#po_balance").val('');
            }
        })
    });
</script>

<script>
    $("#material_name").change(function() {
        $("#overlay").fadeIn(100);
        var req = $('#material_name option:selected').data('req');
        
        var nvl = ($(this).val() == "") ? '' : req; 
        $("#po_balance").val(nvl);
        
        (nvl > 0) ? $(".addBtn").removeClass('d-none') : $(".addBtn").addClass('d-none');
        $("#overlay").fadeOut(500);
    });
    
    
    $("#rate").keyup(function() {
        var budamt = $('#material_name option:selected').data('budamt');
        var rate = $(this).val();
        
        if(parseFloat(rate)>parseFloat(budamt)) {
            $(this).val(budamt);
            $(this).focus();
            $(this).select();
            message_noload('error', 'Budget Rate Exceed!', 1500);
            return false;
        }
    });
    
</script>

<script>
    $("#color_ref").change(function() {
        
        var color = $(this).val();
        var yarn = $("#material_name").val();
        var order_id = $("#order_id").val();
        
        $.ajax({
            type:'POST',
            url:'ajax_search.php?getFab_PO_material_Value=1&yarn=' + yarn + '&order_id=' + order_id + '&color=' + color,
            success:function(msg){
                var json = $.parseJSON(msg);
                
                $("#po_balance").val(json.value);
            }
        })
    });
</script> 

</body>

</html>