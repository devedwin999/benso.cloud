<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_POST['saveBtn'])) {
    
    $_SESSION['msg'] = "added";

    header("Location:fab_opening_list.php");

    exit;
    
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comp = 'Edit Processing';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_opening WHERE id=" . $id));
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
    <title>BENSO GARMENTING - Fabric Stock Opening</title>

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
                    <?php page_spinner(); if(FAB_STOCK_OPENING_ADD!=1 || FAB_STOCK_OPENING_EDIT !=1) { action_denied(); exit; } ?>
                        
                    <div class="pd-20">
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <a class="btn btn-outline-primary" href="<?= $base_url.'fab_opening_list.php'; ?>" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Stock Opening List</a>
                            <a class="btn btn-outline-info" href="mod_fabric.php"><i class="fa fa-home" aria-hidden="true"></i> Fabric</a>
						</div>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">New Fabric Stock Opening</h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="add-poForm" method="post" autocomplete="off">
                        <input type="hidden" name="opening_id" id="opening_id" value="<?= $sql['id']; ?>">
                        <div class="row">
                                
                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['entry_number'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM fabric_opening WHERE entry_number LIKE '%OP-FAB-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'OP-FAB-1';
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
                        </div>
                        
                            
                        <div style="overflow-y: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>BO</th>
                                        <th>Purchase Stage</th>
                                        <th>Material Name</th>
                                        <th>PO BALANCE</th>
                                        <th>Bag / Roll</th>
                                        <th>Opening QTY/Wt</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM fabric_opening_det WHERE fabric_opening_id = '". $_GET['id'] ."'"));
                                    
                                    if(isset($_GET['id']) && $count>0) {
                                        
                                        $qry1 = "SELECT a.*, b.yarn_mixing, b.fabric_id, b.yarn_id, b.dia_size ";
                                        $qry1 .= " FROM fabric_opening_det a ";
                                        $qry1 .= " LEFT JOIN fabric_requirements b ON b.id = a.material_name ";
                                        $qry1 .= " WHERE a.fabric_opening_id = '". $_GET['id'] ."' ";
                                        
                                        $temp1 = mysqli_query($mysqli, $qry1); 
                                        
                                        $i=0;
                                        while($row = mysqli_fetch_array($temp1)) {
                                            
                                            if($row['stock_bo']=='bo') {
                                                if($row['yarn_id'] != NULL) {
                                                    
                                                    $td3 = mas_yarn_name($row['yarn_id']);
                                                } else {
                                                    $opp = '';
                                                    foreach(json_decode($row['yarn_mixing']) as $expp) {
                                                        $exp = explode('=', $expp);
                                                        
                                                        $opp .= " | ". mas_yarn_name($exp[0]) .' - '. color_name($exp[1]) .' - '. $exp[2] .'%';
                                                    }
                                                    
                                                    $td3 = fabric_name($row['fabric_id']) .' '. $opp .' | Dia: '. $row['dia_size'] .'.';
                                                }
                                                $order_code = sales_order_code($row['order_id']).' | '.sales_order_style($row['style_id']);
                                                $po_bal = $row['po_balance'];
                                            } else {
                                                $order_code = stockgroup_name($row['order_id']);
                                                
                                                if(in_array($row['po_stage'], array('19', '20', '21'))) {
                                                    $td3 = mas_yarn_name($row['material_name']);
                                                } else {
                                                    $stk = mysqli_fetch_array(mysqli_query($mysqli, "SELECT fabric_name, yarn_mixing FROM mas_stockitem WHERE id = '". $row['material_name'] ."'"));
                                                        
                                                    $opp = '';
                                                    foreach(json_decode($stk['yarn_mixing']) as $expp) {
                                                        $exp = explode('=', $expp);
                                                        
                                                        $opp .= " | ". mas_yarn_name($exp[0]) .' - '. color_name($exp[1]) .' - '. $exp[2] .'%';
                                                    }
                                                    
                                                    $diaa = ($row['stock_dia']>0) ? ' Dia : '. $row['stock_dia'] : '';
                                                    
                                                    $td3 = fabric_name($stk['fabric_name']) . $opp . $diaa;
                                                    
                                                }
                                                
                                                $po_bal = '-';
                                            }
                                            
                                            print '<tr class="td_edDl"><td>'. $order_code .'</td><td>'. process_name($row['po_stage']) .'</td><td>'. $td3 .'</td><td>'. $po_bal .'</td>';
                                            
                                            if($_GET['rowEd']==$row['id']) {
                                                print '
                                                <td><input type="hidden" class="form-control" name="" id="e_id" value="'. $row['id'] .'"><input type="text" class="form-control" name="" id="e_bag_roll" value="'. $row['bag_roll'] .'"></td>
                                                <td><input type="text" class="form-control" name="" id="e_opening_qty" value="'. $row['opening_qty'] .'"></td>
                                                <td class="d-flex">
                                                    <a class="border border-success rounded text-success text-center saveEdit" data-id="'. $row['id'] .'" title="Update">Update</a> &nbsp;
                                                    <a class="border border-secondary rounded text-secondary text-center cancelEdit" title="Cancel"><i class="icon-copy ion-close-round"></i></a>
                                                </td>
                                                </tr>';
                                            } else {
                                                $gf = $row['id'];
                                                $unity = $row['full_name'] ? $row['full_name'] : '-';
                                                
                                                print'
                                                <td>'. $row['bag_roll'] .'</td>
                                                <td>'. $row['opening_qty'] .'</td>
                                                <td class="d-flex">';
                                                    if(FAB_STOCK_OPENING_EDIT==1) {
                                                        print '<a class="border border-info rounded text-info text-center hov_show editRw" data-id="'. $row['id'] .'" title="Edit"><i class="fa fa-pencil"></i></a> &nbsp;';
                                                    } if(FAB_STOCK_OPENING_DELETE==1) {
                                                        print '<a class="border border-danger rounded text-danger text-center hov_show" onclick="delete_da('. $gf .')" title="Delete"><i class="fa fa-trash"></i></a>';
                                                    }
                                                print '</td>
                                                </tr>';
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
                                            <input type="number" class="form-control d-none" name="stock_dia" id="stock_dia" placeholder="Dia">
                                            <input type="text" class="form-control" name="po_balance" id="po_balance" placeholder="PO BALANCE" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="bag_roll" id="bag_roll" placeholder="Bag / Roll" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="opening_qty" id="opening_qty" placeholder="Opening QTY/Wt" required>
                                        </td>
                                        
                                        <td style="text-align:right;min-width:130px"><a class="btn btn-outline-primary addBtn"><i class="fa fa-plus"></i> Add</a></td>
                                    </tr>
                                    
                                    <?php if(isset($_GET['id']) && $count>0) { ?>
                                    
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <a class="btn btn-outline-secondary" onclick="window.location.href='<?= $base_url.'fab_opening_list.php'; ?>'">Go Back</a>
                                                <a class="btn btn-outline-primary saveBtn">Save Stock Opening</a>
                                                <input type="hidden" name="saveBtn">
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
            <?php
                include('includes/footer.php');
                include('modals.php');
            ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>
    
<script>

    $(document).ready(function() {
        
        $("#opening_qty").keyup(function(){
            var po_balance = $("#po_balance").val();
            var opening_qty = $(this).val();

            if(po_balance != "") {
                if(parseFloat(opening_qty)>parseFloat(po_balance)) {
                    $("#opening_qty").val(po_balance).select();
                    message_noload('error', 'Qty/Wt Exceed!', 1500);
                    return false;
                }
            } else {
                $("#opening_qty").val('').select();
            }
        });


        $(".saveBtn").click(function() {
            $("#add-poForm").submit();
        });
    });
</script>

<script>
    $(".editRw").click(function() {
        var val = $(this).data('id');
        
        var idd = $("#opening_id").val();
        
        window.location.href="fab_opening.php?id=" + idd + "&rowEd=" + val;
    });
    
    $(".cancelEdit").click(function() {
        
        var idd = $("#opening_id").val();
        
        window.location.href="fab_opening.php?id=" + idd;
    });
    
    $(".saveEdit").click(function() {
        
        var e_opening_qty = $("#e_opening_qty").val();
        var e_bag_roll = $("#e_bag_roll").val();
        var e_id = $("#e_id").val();
        var entry_number = $("#entry_number").val();
        
        var idd = $("#opening_id").val();
        
        var data = 'e_opening_qty=' + e_opening_qty + '&e_bag_roll=' + e_bag_roll + '&e_id=' + e_id + '&entry_number=' + entry_number;
        
        
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
                    url:'ajax_action2.php?fab_opening_edit=1',
                    data: data,
                    
                    success:function(msg){
                        var json = $.parseJSON(msg);
                        
                        if(json.result==0) {
                            
                            message_noload('success', 'Updated!', 1500);
                            setTimeout(function() {
                                window.location.href="fab_opening.php?id=" + idd;
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
    $(document).ready(function () {
            
        $(".addBtn").click(function() {
            
            var form = $("#add-poForm").serialize();
            
            var err = required_validation('add-poForm');
            
            if(err==0) {
                $("#overlay").fadeIn(100);
                $(this).prop('disabled', true).html('<i class="fa fa-spinner"></i> Adding..');
                var id = $("#opening_id").val();
                    
                $.ajax({
                    type:'POST',
                    url:'ajax_action2.php?save_fab_opening',
                    data: form,
                    
                    success:function(msg){
                        var json = $.parseJSON(msg);
                        
                        if(json.result==0) {
                            setTimeout(function() {
                                window.location.href="fab_opening.php?id=" + json.opening_id;
                            }, 1000);
                        } else {
                            message_error();
                        }
                    }
                });
            }
        });
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
                            $("#material_name").html(json.material_name);
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

        if(nvl>0) {
            $("#po_balance").val(nvl);
            $(".addBtn").attr('disabled', false).html('<i class="fa fa-plus"></i> Add');
        } else {
            $("#po_balance").val(nvl);
            $(".addBtn").attr('disabled', true).html('<i class="dw dw-cancel"></i> Out of Stock');
        }
        
        // (nvl > 0) ? $(".addBtn").removeClass('d-none') : $(".addBtn").addClass('d-none');
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
    function delete_da(id) {

        var data = {
            id: id,
        };
        
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
                                url:'ajax_action2.php?delete_fab_opening',
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

</body>

</html>