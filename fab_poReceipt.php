<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_POST['saveBtn'])) {
    
    $_SESSION['msg'] = "added";

    header("Location:fab_poReceipt_list.php");

    exit;
    
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_po_receipt WHERE id=" . $id));
} else {
    $id = '';
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Fabric Purchase Receipt</title>

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
                    <?php page_spinner(); if(FAB_PO_RECEIPT_ADD!=1 || FAB_PO_RECEIPT_EDIT!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <a class="btn btn-outline-primary" href="fab_poReceipt_list.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Purchase Receipt List</a>
                            <a class="btn btn-outline-info" href="mod_fabric.php"><i class="fa fa-home" aria-hidden="true"></i> Fabric</a>
						</div>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">New Fabric Purchase Receipt</h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="add-poForm" method="post" autocomplete="off">

                        <input type="hidden" name="po_receipt" id="po_receipt" value="<?= $sql['id']; ?>">

                        <div class="row">

                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['grn_number'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM fabric_po_receipt WHERE grn_number LIKE '%PR-FAB-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'PR-FAB-1';
                                } else {
                                    $ex = explode('-', $sqql['grn_number']);
    
                                    $value = $ex[2];
                                    $intValue = (int) $value;
                                    $newValue = $intValue + 1;
                                    $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);
    
                                    $code = $ex[0] . '-' . $ex[1] . '-' . $nnum;
                                }
                            }
                            ?>
                            <div class="col-md-2">
                                <label class="col-form-label">GRN Number <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" readonly name="grn_number" class="form-control" value="<?= $code; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">GRN Date <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="date" name="grn_date" class="form-control" value="<?= $sql['grn_date'] ? $sql['grn_date'] : date('Y-m-d'); ?>">
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
                            
                            <div class="col-md-2">
                                <label class="col-form-label" for="sup_dc_number">Supplier DC No <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" name="sup_dc_number" id="sup_dc_number" class="form-control" placeholder="Supplier DC No" required value="<?= $sql['sup_dc_number'] ? $sql['sup_dc_number'] : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">Supplier DC Date <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="date" name="sup_dc_date" class="form-control" value="<?= $sql['sup_dc_date'] ? $sql['sup_dc_date'] : date('Y-m-d'); ?>">
                                </div>
                            </div>
                            
                        </div>
                        
                            
                        <div style="overflow-y: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>PO No</th>
                                        <th>BO</th>
                                        <th>Material Name</th>
                                        <th>PO Bag / Roll</th>
                                        <th>PO QTY/Wt</th>
                                        <th>Received Balance</th>
                                        <th>Received Bag / Roll</th>
                                        <th colspan="2">Received Qty / Wt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cttt[] = 0;
                                    if(isset($_GET['id'])) {
                                        
                                        $qry1 = "SELECT a.*, b.order_code, a.stock_bo, c.process_name, f.full_name, c.budget_type, a.material_name, a.color_ref, a.tax_per, aa.received_bal, aa.received_bag, aa.received_qty, aa.id as nid, g.entry_number ";
                                        $qry1 .= " , d.yarn_id, d.fabric_id, d.color as color_id, d.dia_size, d.yarn_mixing ";
                                        $qry1 .= " FROM fabric_po_receipt_det aa ";
                                        $qry1 .= " LEFT JOIN fabric_po_det a ON aa.fabric_po_det = a.id ";
                                        $qry1 .= " LEFT JOIN sales_order b ON b.id = a.order_id ";
                                        $qry1 .= " LEFT JOIN process c ON c.id = a.po_stage ";
                                        $qry1 .= " LEFT JOIN fabric_requirements d ON d.id = a.material_name ";
                                        $qry1 .= " LEFT JOIN tax_main f ON f.id = a.tax_per ";
                                        $qry1 .= " LEFT JOIN fabric_po g ON g.id = a.fab_po ";
                                        $qry1 .= " WHERE aa.fabric_po_receipt = '". $_GET['id'] ."' ";
                                        
                                        $temp1 = mysqli_query($mysqli, $qry1); 
                                        $cttt[] = mysqli_num_rows($temp1);
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
                                                $order_code = sales_order_code($row['order_id']);
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
                                            
                                            print '<tr class="td_edDl">
                                                    <td>'. $row['entry_number'] .'</td>
                                                    <td>'. $order_code .'</td>
                                                    <td>'. $td3 .'</td>
                                                    <td>'. $row['bag_roll'] .'</td>
                                                    <td>'. $row['po_qty_wt'] .'</td>
                                                    <td>'. $row['received_bal'] .'</td>';
                                            
                                            if($_GET['rowEd']==$row['nid']) {
                                                
                                                print '
                                                    <td><input type="text" class="form-control" name="" id="ed_received_bag" value="'. $row['received_bag'] .'"></td>
                                                    <td style="text-align:center;"><input type="text" class="form-control" name="" id="ed_received_qty" value="'. $row['received_qty'] .'"></td>
                                                    <td class="d-flex">
                                                        <input type="hidden" name="" id="ed_nid" value="'. $row['nid'] .'">
                                                        <input type="hidden" name="" id="entry_number" value="'. $row['entry_number'] .'">
                                                        <a class="border border-success rounded text-success text-center saveEdit" data-id="'. $row['nid'] .'" title="Save"><i class="fa fa-check"></i></a> &nbsp;
                                                        <a class="border border-secondary rounded text-secondary text-center cancelEdit" title="Cancel"><i class="icon-copy ion-close-round"></i></a>
                                                    </td>
                                                </tr>';
                                            } else {
                                                
                                                $gf = $row['nid'];
                                                
                                                $edit = (FAB_PO_RECEIPT_EDIT==1) ? '<a class="border border-info rounded text-info text-center hov_show editRw" data-id="'. $row['nid'] .'" title="Edit"><i class="fa fa-pencil"></i></a>': '';
                                                $delete = (FAB_PO_RECEIPT_DELETE==1) ? '<a class="border border-danger rounded text-danger text-center hov_show" onclick="delete_da('. $gf .')" title="Delete"><i class="fa fa-trash"></i></a>': '';
                                                
                                                print '
                                                    <td>'. $row['received_bag'] .'</td>
                                                    <td style="text-align:center;">'. $row['received_qty'] .'</td>
                                                    <td class="d-flex">'. $edit .'&nbsp;'. $delete .'</td>
                                                </tr>';
                                            }
                                        $i++; }
                                    }
                                        // <a class="border border-secondary rounded text-secondary text-center hov_show editRw" data-id="'. $row['nid'] .'"><i class="fa fa-pencil"></i></a> &nbsp;
                                        // <a class="border border-secondary rounded text-secondary text-center hov_show" onclick="delete_data('. $gf .')"><i class="fa fa-trash"></i></a>
                                    ?>
                                    <tr id="tableBody">
                                        <td class="mw-150">
                                            <select name="po_num" id="po_num" class="custom-select2 form-control" style="width:100%" required></select>
                                        </td>
                                        <td class="mw-150">
                                            <select name="order_id" id="order_id" class="custom-select2 form-control" style="width:100%" required></select>
                                        </td>
                                        <td class="mw-150">
                                            <select class="custom-select2 form-control" name="material_name" id="material_name" style="width:100%" required></select>
                                            <input type="hidden" name="fab_req" id="fab_req">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="bag_roll" id="bag_roll" placeholder="PO Bag / Roll" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="po_qty_wt" id="po_qty_wt" placeholder="PO QTY/Wt" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="received_bal" id="received_bal" placeholder="Received Balance" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control number_input" name="received_bag" id="received_bag" placeholder="Received Bag / Roll" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control number_input" name="received_qty" id="received_qty" placeholder="Received Qty / Wt" required>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="9" style="text-align:right;"><button class="btn btn-outline-primary addBtn"><i class="fa fa-plus"></i> Add</button></td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        
                        <hr>
                        
                        <div class=" row">
                            <?php if(array_sum($cttt)>0) { ?>
                                <div class="col-md-12">
                                    <div class="form-group" style="text-align: center;">
                                        <a class="btn btn-outline-secondary" href="fab_poReceipt_list.php">Go Back</a>
                                        <input type="button" class="btn btn-outline-success saveBtn" name="SaveBtn" value="Save Receipt">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        
                    </form>
                </div>

            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>


<script>
    $(".saveBtn").click(function() {
        message_noload('success', 'Purchase Receipt Saved!');
        
        setTimeout(function() { window.location.href="fab_poReceipt_list.php"; }, 1500);
    });
</script>

<script>
    $(".editRw").click(function() {
        var val = $(this).data('id');
        
        var idd = $("#po_receipt").val();
        $("#overlay").fadeIn(100);
        window.location.href="fab_poReceipt.php?id=" + idd + "&rowEd=" + val;
    });
    
    $(".cancelEdit").click(function() {
        
        var idd = $("#po_receipt").val();
        $("#overlay").fadeIn(100);
        window.location.href="fab_poReceipt.php?id=" + idd;
    });
    
    $(".saveEdit").click(function() {
        
        var ed_nid = $("#ed_nid").val();
        var ed_received_qty = $("#ed_received_qty").val();
        var ed_received_bag = $("#ed_received_bag").val();
        var entry_number = $("#entry_number").val();
        
        var data = 'id=' + ed_nid + '&received_qty=' + ed_received_qty + '&received_bag=' + ed_received_bag + '&entry_number=' + entry_number;
        
        // var data = {
        //     id: $("#ed_nid").val(),
        //     received_qty: $("#ed_received_qty"),
        //     received_bag: $("#ed_received_bag").val(),
        //     entry_number: $("#entry_number").val()
        // };
        
        var idd = $("#po_receipt").val();
        
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
                $("#overlay").fadeIn(100);
                $.ajax({
                    type:'POST',
                    url:'ajax_action2.php?save_PO_receipt_edit=1',
                    data: data,
                    
                    success:function(msg){
                        var json = $.parseJSON(msg);
                        
                        if(json.result==0) {
                            $("#overlay").fadeOut(500);
                            message_noload('success', 'Updated!', 1500);
                            setTimeout(function() {
                                window.location.href="fab_poReceipt.php?id=" + idd;
                            },1500)
                        } else {
                            $("#overlay").fadeOut(500);
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
    function delete_da(id) {

        var data = { id: id, };
        
        var delete_pwd = $("#delete_pwd").val();
        swal({
            title: 'Enter Password to Delete!',
            html: '<input id="password-input" class="swal2-input" type="password" placeholder="Password">',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            showLoaderOnConfirm: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            preConfirm: function (email) {
                var email = document.getElementById('password-input').value;
                return new Promise(function (resolve, reject) {
                    setTimeout(function () {
                        if (email == delete_pwd) {
                            
                            $.ajax({
                                type:'POST',
                                url:'ajax_action2.php?delete_fab_receipt',
                                data: data,
                                
                                success:function(msg){
                                    var json = $.parseJSON(msg);
                                    
                                    if(json.result==0) {
                                        message_reload('success', 'Row Deleted!', 1500);
                                    } else {
                                        message_noload('error', 'Error!', 1500);
                                    }
                                }
                            });
                        } else {
                            message_noload('error', 'Incorrect Password!', 2000);
                        }
                    }, 1000);
                })
            },
            allowOutsideClick: false
        });
    }
</script>
    
<script>
    $(document).ready(function() {
        
        var id = $("#supplier").val();
        
        supp_change(id);
    })
</script>

<script>
    $("#e_po_qty_wt").keyup(function() { e_calAmt();});
    $("#e_rate").keyup(function() { e_calAmt();});
    
    
    function e_calAmt() {
        
        var po_qty_wt = $("#e_po_qty_wt").val();
        var rate = $("#e_rate").val();
        
        var vll = parseInt(po_qty_wt)*parseInt(rate);
        
        $("#e_amount").val(vll);
        $(".e_amtTxt").text(vll);
    }
</script>

<script>
    $("#received_qty").keyup(function() { calAmt();});
    
    
    function calAmt() {
        
        var received_bal = $("#received_bal").val();
        var received_qty = $("#received_qty").val();
        
        if(parseInt(received_bal)<parseInt(received_qty)) {
            
            $("#received_qty").val(received_bal);
        }
    }
</script>
    
<script>
    $(".addBtn").click(function() {
        
        
        var form = $("#add-poForm").serialize();
        
        var err = required_validation('add-poForm');
        
        if(err==0) {
            $(this).prop('disabled', true);
            var id = $("#po_receipt").val();
            $("#overlay").fadeIn(100);
            $.ajax({
                type:'POST',
                url:'ajax_action2.php?addFabric_PO_receipt',
                data: form,
                
                success:function(msg){
                    var json = $.parseJSON(msg);
                    
                    if(json.res==0) {
                        // alert();
                        if(id=="") {
                            // message_noload('success', 'Receipt Created.', 1500);
                            $("#overlay").fadeOut(500);
                            setTimeout(function() {
                                window.location.href="fab_poReceipt.php?id=" + json.po_receipt;
                            }, 1000);
                        } else {
                            // message_reload('success', 'Receipt Added.', 1500);
                            $("#overlay").fadeOut(500);
                            setTimeout(function() {
                                window.location.href="fab_poReceipt.php?id=" + json.po_receipt;
                            }, 1000);
                        }
                        
                    } else {
                        $("#overlay").fadeOut(500);
                        message_noload('error', 'Error!', 1500);
                    }
                }
            })
        }
    });
</script>

<script>
    $("#material_name").change(function() {
        $("#overlay").fadeIn(100);
        var qwt = $('#material_name option:selected').data('qwt');
        $("#po_qty_wt").val(qwt);
        
        var bal = $('#material_name option:selected').data('bal');
        $("#received_bal").val(bal);
        
        var bag = $('#material_name option:selected').data('bag');
        $("#bag_roll").val(bag);
        
        var fab_req = $('#material_name option:selected').data('fab_req');
        $("#fab_req").val(fab_req);
        $("#overlay").fadeOut(500);
    });
</script>

<script>
    $("#order_id").change(function() {
        
        $("#overlay").fadeIn(100);
        var id = $(this).val();
        var supplier = $("#supplier").val();
        var po_num = $("#po_num").val();
        
        var data = 'id=' + id + '&supplier=' + supplier + '&po_num=' + po_num;
        
        $.ajax({
            type:'POST',
            url:'ajax_search2.php?getFab_Preceipt_dett',
            data: data,
            
            success:function(msg){
                var json = $.parseJSON(msg);
                
                $("#material_name").html(json.material_name);
                $("#po_qty_wt").val('');
                $("#received_bal").val('');
                $("#overlay").fadeOut(500);
            }
        })
    });
</script>

<script>
    $("#supplier").change(function() {
        
        var supplier = $(this).val();
        
        supp_change(supplier);
    });
    
    function supp_change(supplier) {
        $("#overlay").fadeIn(100);

        var data = {
            supplier: supplier,
        };

        $.ajax({
            type:'POST',
            url:'ajax_search2.php?getPO_for_fabric_Preceipt',
            data: data,

            success:function(msg){
                var json = $.parseJSON(msg);
                
                $("#po_num").html(json.po_num);
                $("#order_id").html('');
                $("#material_name").html('');
                $("#po_qty_wt").val('');
                $("#received_bal").val('');
                $("#overlay").fadeOut(500);
            }
        });
    }
</script>

<script>
    $("#po_num").change(function() {
        
        var po_num = $(this).val();
        
        ponum_change(po_num);
    });
    
    function ponum_change(po_num) {
        $("#overlay").fadeIn(100);
        var data = {
            po_num: po_num,
        }
        $.ajax({
            type:'POST',
            url:'ajax_search2.php?getBO_for_fabric_Preceipt',
            data: data,

            success:function(msg){
                var json = $.parseJSON(msg);
                
                $("#order_id").html(json.order_id);
                $("#material_name").html('');
                $("#po_qty_wt").val('');
                $("#received_bal").val('');
                $("#overlay").fadeOut(500);
            }
        });
    }
</script>



</body>

</html>