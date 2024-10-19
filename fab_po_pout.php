<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_POST['saveBtn'])) {
    
    // $up = Update('fabric_po', array('grand_total' => $_REQUEST['grand_total']), ' WHERE id = '. $_GET['id']);
    
    // timeline_history('Update', 'fabric_po', $_REQUEST['id'], 'Employee Request Rejected.');
    $_SESSION['msg'] = "added";

    header("Location:fab_po_pout_list.php");

    exit;
    
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comp = 'Edit Processing';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_dc WHERE id=" . $id));
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
    <title>BENSO - Fabric Process DC</title>

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
                    <?php page_spinner(); if(FAB_PO_POUT_ADD!=1 | FAB_PO_POUT_EDIT !=1) { action_denied(); exit; } ?>
                        
                    <div class="pd-20">
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <a class="btn btn-outline-primary" href="fab_po_pout_list.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Process DC List</a>
                            <a class="btn btn-outline-info" href="mod_fabric.php"><i class="fa fa-home" aria-hidden="true"></i> Fabric</a>
						</div>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">New Fabric Process DC</h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="add-poForm" method="post" autocomplete="off">
                            
                        <input type="hidden" name="dc_idd" id="dc_idd" value="<?= $sql['id']; ?>">
                            
                        <div class="row">
                                
                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['dc_number'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM fabric_dc WHERE dc_number LIKE '%DC-FAB-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'DC-FAB-1';
                                } else {
                                    $ex = explode('-', $sqql['dc_number']);
                                        
                                    $value = $ex[2];
                                    $intValue = (int) $value;
                                    $newValue = $intValue + 1;
                                    $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);
                                        
                                    $code = $ex[0] . '-' . $ex[1] . '-' . $nnum;
                                }
                            }
                            ?>
                            <div class="col-md-3 pe-none">
                                <label class="col-form-label">DC Number <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" readonly name="dc_number" class="form-control" value="<?= $code; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="col-form-label">DC Date <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="date" name="entry_date" class="form-control" value="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-3 <?= $_GET['id'] ? 'pe-none' : ''; ?>">
                                <label class="col-form-label">Supplier <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="supplier" id="supplier" class="custom-select2 form-control" style="width:100%">
                                        <?= select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $sql['supplier'], '', ''); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3 <?= $_GET['id'] ? 'pe-none' : ''; ?>">
                                <label class="col-form-label">To Process <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="to_process" id="to_process" class="custom-select2 form-control" style="width:100%">
                                        <?= select_dropdown('process', array('id', 'process_name'), 'process_name ASC', $sql['process'], ' WHERE process_type = "Fabric" ', ''); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="col-form-label">BO <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="order_id" id="order_id" style="width:100%"></select>
                                </div>
                            </div>
                            
                            <div class="col-md-5">
                                <label class="col-form-label">Output Material <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="material_name" id="material_name" style="width:100%"></select>
                                </div>
                                
                                <input type="hidden" name="fabric_id" id="fabric_id">
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">Output Wt <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="output_wt" id="output_wt" placeholder="Output Wt">
                                    <input type="hidden" name="temp_dcBal" id="temp_dcBal">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">&nbsp;</label>
                                <div class="form-group">
                                    <a class="btn btn-outline-primary addPlan"><i class="fa fa-plus"></i> Add Delivery Plan</a>
                                </div>
                            </div>
                        </div>
                        
                        <div style="overflow-y: auto;">
                            <?php $nmm[] = 0; if(isset($_GET['id'])) { ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>BO</th>
                                            <th>Yarn | Fabric</th>
                                            <th class="d-none">DC Balance</th>
                                            <th>Stock Qty</th>
                                            <th>Bag / Roll</th>
                                            <th colspan="2">DC QTY/Wt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                
                                            $qry1 = "SELECT a.*, b.yarn_name, c.fabric_name, d.color_name ";
                                            $qry1 .= " FROM fabric_dc_det a ";
                                            $qry1 .= " LEFT JOIN mas_yarn b ON a.yarn_id = b.id ";
                                            $qry1 .= " LEFT JOIN fabric c ON a.fabric_id = c.id ";
                                            $qry1 .= " LEFT JOIN color d ON a.color_id = d.id ";
                                            $qry1 .= " WHERE a.fabric_dc_id = '". $_GET['id'] ."' ";
                                            
                                            $temp1 = mysqli_query($mysqli, $qry1); 
                                            $nmm[] = mysqli_num_rows($temp1);
                                            $i=0;
                                            while($row = mysqli_fetch_array($temp1)) {
                                                
                                                // 22 knitting process
                                                if($row['process_id']==22) {
                                                    $compnt = $row['yarn_name'];
                                                    
                                                } else {
                                                    
                                                    $compnt = $row['fabric_name'].' | '. $row['color_name'];
                                                }
                                                
                                                if($_GET['rowEd']==$row['id']) {
                                                    
                                                    print '<tr class="td_edDl">
                                                        <td>'. sales_order_code($row['order_id']) .'</td>
                                                        <td>'. $compnt .'</td>
                                                        <td><input type="text" class="form-control" name="" id="ed_received_bag" value="'. $row['received_bag'] .'"></td>
                                                        <td style="text-align:center;"><input type="text" class="form-control" name="" id="ed_received_qty" value="'. $row['received_qty'] .'"></td>
                                                        <td class="d-flex">
                                                            <input type="hidden" name="" id="ed_nid" value="'. $row['id'] .'">
                                                            <input type="hidden" name="" id="entry_number" value="'. $row['entry_number'] .'">
                                                            <a class="border border-success rounded text-success text-center saveEdit" data-id="'. $row['id'] .'" title="Save"><i class="fa fa-check"></i></a> &nbsp;
                                                            <a class="border border-secondary rounded text-secondary text-center cancelEdit" title="Cancel"><i class="icon-copy ion-close-round"></i></a>
                                                        </td>
                                                    </tr>';
                                                } else {
                                                    
                                                    $gf = $row['id'].",'fabric_po_receipt_det'";
                                                    
                                                    $edit = (FAB_PO_POUT_EDIT==1) ? '<a class="d-none border border-info rounded text-info text-center hov_show editRw" data-id="'. $row['id'] .'" title="Edit"><i class="fa fa-pencil"></i></a>': '';
                                                    $delete = (FAB_PO_POUT_DELETE==1) ? '<a class="d-none border border-danger rounded text-danger text-center hov_show" onclick="delete_da('. $gf .')" title="Delete"><i class="fa fa-trash"></i></a>': '';
                                                    
                                                    print '<tr class="td_edDl">
                                                        <td>'. sales_order_code($row['order_id']) .' | '. sales_order_style($row['style_id']) .'</td>
                                                        <td>'. $compnt .'</td>
                                                        <td class="d-none">'. $row['dc_balance'] .'</td>
                                                        <td>'. $row['stock'] .'</td>
                                                        <td>'. $row['bag_roll'] .'</td>
                                                        <td>'. $row['dc_qty_wt'] .'</td>
                                                        <td class="d-flex">'. $edit .'&nbsp;'. $delete .'</td>
                                                    </tr>';
                                                }
                                            $i++; }
                                        ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                            
                            
                        <hr>
                            
                        <div class=" row">
                            <?php if(array_sum($nmm)>0) { ?>
                                <div class="col-md-12">
                                    <div class="form-group" style="text-align: center;">
                                        <a class="btn btn-outline-secondary" href="fab_po_pout_list.php">Go Back</a>
                                        <a class="btn btn-outline-primary saveBtn">Save Process DC</a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                            
                        <div class="modal fade bs-example-modal-lg" id="plan_addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-top" style="max-width:1000px;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myLargeModalLabel">Add Delivery Details</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <div class="modal-body" id="modal_body">
                                        <div id="i_spinner" class="text-center"><i class="icon-copy fa fa-spinner" aria-hidden="true"></i> Loading..</div>
                                        
                                        <table class="table table-bordered">
                                            <thead id="mod_thead"></thead>
                                            <tbody id="mod_tbody"></tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-outline-primary addBtn"><i class="fa fa-plus"></i> Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                    </form>
                </div>
                    
            </div>
            <?php include('modals.php'); include('includes/footer.php'); ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>



<script>
    $(".saveBtn").click(function() {
        
        message_noload('success', 'Process DC Saved');
        
        setTimeout(function() {
            window.location.href="fab_po_pout_list.php";
        }, 1500);
    });
    
</script>
<script>
    $(".editRw").click(function() {
        var val = $(this).data('id');
        
        var idd = $("#dc_idd").val();
        
        window.location.href="fab_po_pout.php?id=" + idd + "&rowEd=" + val;
    });
    
    $(".cancelEdit").click(function() {
        
        var idd = $("#dc_idd").val();
        
        window.location.href="fab_po_pout.php?id=" + idd;
    });
    
    $(".saveEdit").click(function() {
        
        var e_po_qty_wt = $("#e_po_qty_wt").val();
        var e_rate = $("#e_rate").val();
        var e_tax_per = $("#e_tax_per").val();
        var e_amount = $("#e_amount").val();
        var e_id = $("#e_id").val();
        var entry_number = $("#dc_number").val();
        
        var idd = $("#dc_idd").val();
        
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
                $("#overlay").fadeIn(100);
                $.ajax({
                    type:'POST',
                    url:'ajax_action2.php?save_PO_expense_edit=1',
                    data: data,
                    
                    success:function(msg){
                        var json = $.parseJSON(msg);
                        
                        $("#overlay").fadeOut(500);
                        if(json.result==0) {
                            
                            message_noload('success', 'Updated!', 1500);
                            setTimeout(function() {
                                window.location.href="fab_po_pout.php?id=" + idd;
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
            var fabric_po = $("#dc_idd").val();
            var entry_number = $("#dc_number").val();
            
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
            
                var data = 'expense_name=' + expense_name +'&expense_amount=' + expense_amount + '&fabric_po=' + fabric_po + '&entry_number=' + entry_number;
                $("#overlay").fadeIn(100);
                $.ajax({
                    type:'POST',
                    url:'ajax_action2.php?save_PO_expense=1',
                    data: data,
                    
                    success:function(msg){
                        var json = $.parseJSON(msg);
                        $("#overlay").fadeOut(500);
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
    $("#po_qty_wt").keyup(function() { calAmt();});
    $("#rate").keyup(function() { calAmt();});
    
    
    function calAmt() {
        
        var po_qty_wt = $("#po_qty_wt").val();
        var rate = $("#rate").val();
        
        var vll = parseInt(po_qty_wt)*parseInt(rate);
        
        $("#amount").val(vll);
    }
</script>
    











<script>
    $(".addBtn").click(function() {
        
        var id = $("#dc_idd").val();
        var form = $("#add-poForm").serialize();
        var err = required_validation('add-poForm');
        
        if(err==0) {
            $(this).prop('disabled', true).html('<i class="fa fa-spinner"></i> Adding..');
            $("#overlay").fadeIn(100);
            $.ajax({
                type:'POST',
                url:'ajax_action2.php?addFabric_DC_list',
                data: form,
                
                success:function(msg){
                    var json = $.parseJSON(msg);
                    $("#overlay").fadeOut(500);
                    if(json.result==0) {
                        
                        if(id=="") {
                            message_noload('success', 'DC Created.', 1500);
                            setTimeout(function() {
                                window.location.href="fab_po_pout.php?id=" + json.dc_idd;
                            }, 1500);
                        } else {
                            
                            message_reload('success', 'DC List Added.', 1500);
                            setTimeout(function() {
                                window.location.href="fab_po_pout.php?id=" + json.dc_idd;
                            }, 1500);
                        }
                        
                    } else {
                        message_noload('error', 'Error!', 1500);
                    }
                }
            });
        }
    });
</script>

<script>
    $(".addPlan").click(function() {
        
        var supplier = $("#supplier").val();
        var po_stage = $("#to_process").val();
        var order_id = $("#order_id").val();
        var material_name = $("#material_name").val();
        var output_wt = $("#output_wt").val();
        
        if(supplier=="") {
            
            message_noload('error', 'Select Supplier to enter Plan detail');
            return false;
        } else if(po_stage=="") {
            
            message_noload('error', 'Select To Process to enter Plan detail');
            return false;
        } else if(order_id=="") {
            
            message_noload('error', 'Select BO to enter Plan detail');
            return false;
        } else if(material_name=="") {
            
            message_noload('error', 'Select Output Material to enter Plan detail');
            return false;
        } else if(output_wt=="") {
            
            message_noload('error', 'Enter Output Weight to enter Plan detail');
            return false;
        } else {
            
            var form = $("#add-poForm").serialize();
            $("#overlay").fadeIn(100);
            $.ajax({
                type:'POST',
                url:'ajax_search2.php?pOut_plan_detail',
                data: form,
                
                success:function(msg){
                    var json = $.parseJSON(msg);
                    $("#overlay").fadeOut(500);
                    setTimeout(function() {
                        $("#i_spinner").addClass('d-none');
                        $("#mod_thead").html(json.mod_thead);
                        $("#mod_tbody").html(json.mod_tbody);
                    }, 500);
                    
                    setTimeout(function() {
                        var m = 0;
                        var stock = $(".stocks");
                        var reqqs = $(".reqqs");
                        for (var i = 0; i < stock.length; i++) {
                            var s1 = $(stock[i]).val();
                            var r1 = $(reqqs[i]).val();
                            if(s1 <= 0) {
                               m++;
                            } else if(parseFloat(s1) < parseFloat(r1)) {
                               m++;
                            }
                            
                        }
                        // alert(m);
                        if(m==0) {
                            $(".addBtn").prop('disabled', false).addClass('btn-outline-primary').removeClass('btn-outline-danger').html('<i class="fa fa-plus"></i> Add');
                        } else {
                            $(".addBtn").prop('disabled', true).removeClass('btn-outline-primary').addClass('btn-outline-danger').html('Out of Stock');
                        }
                    }, 1000)
                }
            });
            
            $('#plan_addModal').modal({
                backdrop: 'static',
                keyboard: false
            })
            $("#plan_addModal").modal('show');
        }
    });
    
    $('#plan_addModal').on('hidden.bs.modal', function (e) {
        $("#i_spinner").removeClass('d-none');
        $("#mod_thead").html('');
        $("#mod_tbody").html('');
    });
</script>

<script>
    $("#order_id").change(function() {
        
        var po_stage = $("#to_process").val();
        var style_id = $("#order_id").val();
        
        var data = 'process_id=' + po_stage + '&style_id=' + style_id;
        $("#overlay").fadeIn(100);
        $.ajax({
            type:'POST',
            url:'ajax_search2.php?MaterialFor_fabDC',
            data: data,
            success:function(msg){
                var json = $.parseJSON(msg);
                $("#overlay").fadeOut(500);
                // $("#po_stage").html(json.po_stage);
                $("#material_name").html(json.material);
            }
        });
    });
</script>

<script>
    $("#material_name").change(function() {
        $("#overlay").fadeIn(100);
        var val = $('#material_name option:selected').data('wtt');
        $("#output_wt").val(val);
        $("#temp_dcBal").val(val);
        
        var fabric = $('#material_name option:selected').data('fabric');
        $("#fabric_id").val(fabric);
        $("#overlay").fadeOut(500);
    });
</script>

<script>
    $("#output_wt").keyup(function() {
        
        var mn = $("#material_name").val();
        
        var max_val = $('#material_name option:selected').data('wtt');
        
        if(mn == "" || mn == null) {
            $(this).val('');
            $(this).focus();
            message_noload('error', 'Select Material to enter Weight!');
            return false;
            
        } else if(max_val<$(this).val()) {
            
            $(this).val(max_val);
        }
    })
</script>

<script>
    $("#to_process").change(function() {
        process_Change();
    });
    
    function process_Change() {
        
        $("#order_id").html('');
        $("#material_name").html('');
        
        var to_process = $("#to_process").val();
        if([19, 20, 21].includes(parseInt(to_process))) {
            message_title('info', 'This is Buying Process!', 'Try Another process to create DC!');
            $("#order_id").html('');
            return false;
        } else {
        
            $("#overlay").fadeIn(100);

            var data = {
                to_process : to_process,
            }
            $.ajax({
                type:'POST',
                url:'ajax_search2.php?to_process_dc',
                data: data,

                success:function(msg){
                    var json = $.parseJSON(msg);
                    $("#overlay").fadeOut(500);
                    $("#order_id").html(json.order_id);
                }
            });
        }
    }
</script>

<script>
    $(document).ready(function() {
        process_Change();
    })
</script>

</body>

</html>