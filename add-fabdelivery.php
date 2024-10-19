<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_POST['savebttn'])) {

    $array = array(
        'dc_number' => $_POST['dc_number'],
        'dc_date' => $_POST['dc_date'],
        'delivery_type' => $_POST['delivery_type'],
        'delivery_to' => $_POST['delivery_to'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    if($_POST['delivery_id']=="") {
        $ins = Insert('fabric_delivery', $array);
        $inid = mysqli_insert_id($mysqli);
    } else {
        $ins = Update('fabric_delivery', $array, 'WHERE id = '. $_POST['delivery_id']);
        $inid = $_POST['delivery_id'];
    }

    for($p=0; $p<count($_POST['style_id']); $p++) {

        $dd = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM `fabric_consumption` WHERE id=". $_POST['fabric_id'][$p]));

        $ar = array(
            'fabric_delivery' => $inid,
            'sales_order_id' => $dd['sales_order_id'],
            'sales_order_detail_id' => $dd['sales_order_detail_id'],
            'fabric' => $dd['fabric'],
            'fabric_consumption' => $_POST['fabric_id'][$p],
            'req_wt' => $_POST['req_wt'][$p],
            'del_bal' => $_POST['del_bal'][$p],
            'bag_roll' => $_POST['bag_roll'][$p],
            'del_wt' => $_POST['del_wt'][$p],
        );

        $ins = Insert('fabric_delivery_det', $ar);
    }

    $_SESSION['msg'] = "added";
    header("Location:fabric_delivery.php");
    exit;
    
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comp = 'Edit Processing';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_delivery WHERE id=" . $id));
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
    <title>BENSO - Fabric Delivery</title>

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
                    <?php page_spinner(); if(FAB_UNIT_DELIVERY_ADD!=1 || FAB_UNIT_DELIVERY_EDIT !=1) { action_denied(); exit; } ?>
                        
                    <div class="pd-20">
                        <a class="btn btn-outline-primary" href="fabric_delivery.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Fabric Delivery List</a>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">New Fabric Delivery</h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="add-poForm" method="post" autocomplete="off">
                            
                        <input type="hidden" name="po_idd" id="po_idd" value="<?= $sql['id']; ?>">
                            
                        <div class="row">
                                
                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['dc_number'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM fabric_delivery WHERE dc_number LIKE '%PO-FAB-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'FAB-DEL-1';
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
                            <div class="col-md-2">
                                <label class="col-form-label">DC Number <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="hidden" name="delivery_id" value="<?= isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                                    <input type="text" readonly name="dc_number" class="form-control" value="<?= $code; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">DC Date <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="date" name="dc_date" class="form-control" value="<?= isset($_GET['id']) ? $sql['dc_date'] : date('Y-m-d'); ?>">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="col-form-label">Delivery type <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="delivery_type" id="delivery_type" class="custom-select2 form-control" style="width:100%">
                                        <option value="Unit">Unit</option>
                                        <option value="Supplier" <?= ($sql['delivery_type'] == 'Supplier') ? 'selected' : ''; ?>>Supplier</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label fieldrequired">Delivery To : <span class="delivery_to_label"><?= $sql['input_type'] ? $sql['input_type'] : 'Unit'; ?></span></label>
                                <div class="form-group">
                                    <select name="delivery_to" id="delivery_to" class="custom-select2 form-control" style="width:100%" required>
                                        <option value="">Select <?= $sql['input_type'] ? $sql['input_type'] : 'Unit'; ?></option>
                                        <?php
                                            if($sql['delivery_type']=='Supplier') {
                                                print select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $sql['delivery_to'], ' WHERE is_active="active"', '`');
                                            } else {
                                                print select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $sql['delivery_to'], ' WHERE type = 2', '`');
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                            
                        <div style="overflow-y: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>BO | Style</th>
                                        <th>Fabric Name</th>
                                        <th>Cutting Req Wt</th>
                                        <th>Delivery Bal</th>
                                        <th>Delivery Wt</th>
                                        <th>Bag/ Roll</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="main_tbody">
                                    <?php
                                    if(isset($_GET['id'])) {
                                        $qy = mysqli_query($mysqli, "SELECT * FROM fabric_delivery_det WHERE fabric_delivery=". $id);
                                        while($result = mysqli_fetch_array($qy)) {
                                            $del = $result['id'].", 'fabric_delivery_det'";
                                            print '
                                                <tr>
                                                    <td>'. sales_order_code($result['sales_order_id']) .' | '. sales_order_style($result['sales_order_detail_id']) .'</td>
                                                    <td>'. fabric_name($result['fabric']) .'</td>
                                                    <td>'. ($result['req_wt'] ? $result['req_wt'] : '-') .'</td>
                                                    <td>'. ($result['del_bal'] ? $result['del_bal'] : '-') .'</td>
                                                    <td>'. ($result['bag_roll'] ? $result['bag_roll'] : '-') .'</td>
                                                    <td>'. ($result['del_wt'] ? $result['del_wt'] : '-') .'</td>
                                                    <td><a class="btn text-danger" onclick="delete_data('. $del .')"><i class="fa fa-trash"></i></a></td>
                                                </tr>
                                            ';
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr id="tablefoot">
                                        <td class="mw-150">
                                            <select class="custom-select2 form-control" id="style_id" style="width:100%" required>
                                                <option value="">Select</option>
                                                <?php                                                    
                                                    $ff1 = mysqli_query($mysqli, "SELECT id, sales_order_id, style_no FROM sales_order_detalis WHERE is_dispatch IS NULL ORDER BY id DESC");
                                                    while($row1 = mysqli_fetch_array($ff1)) {
                                                        print '<option value="'. $row1['id'] .'">'. sales_order_code($row1['sales_order_id']) .' | '. $row1['style_no'] .'</option>';
                                                    }  
                                                ?>
                                            </select>
                                        </td>
                                        <td><select class="custom-select2 form-control" id="fabric" style="width:100%"></select></td>
                                        <td><input type="hidden" id="req_wt" value="0"> <p class="req_wt_td">-</p></td>
                                        <td><input type="hidden" id="del_bal" value="0"> <p class="del_bal_td">-</p></td>
                                        <td><input type="text" class="form-control number_input max-width-200" id="bag_roll" placeholder="Bag/ Roll">
                                        <td><input type="text" class="form-control number_input max-width-200" id="del_wt" placeholder="Delivery Weight">
                                        </td>
                                        <td><a class="btn btn-outline-primary addBtn"><i class="fa fa-plus"></i> Add</a></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-center savetr d-none">
                                            <input type="hidden" name="savebttn">
                                            <a class="btn btn-outline-primary saveBtn"><i class="fa-save fa"></i> Save</a>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <hr>
                    </form>
                </div>

            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>


<script>
    $(document).ready(function() {

        $("#style_id").change(function() {
            var style = $(this).val();
            var data = {
                style : style,
            };
            
            if(style != "") {
                $("#overlay").fadeIn(100);
                $.post('ajax_search3.php?type=get_fabricfor_fabdelivery', data, function(resp){
                    $("#overlay").fadeOut(500);
                    var j = $.parseJSON(resp);
                    if(j.count==0) {
                        message_noload('info', 'Fabric Consumption not Created!', 1500);
                        $("#fabric").html('');
                        return false;
                    } else {
                        $("#fabric").html(j.option);
                    }
                });
            }
        });
        
        
        $("#fabric").change(function(){
            $("#overlay").fadeIn(100);
            var req_wt = $("#fabric option:selected").data('req_wt');
            var del_bal = $("#fabric option:selected").data('del_bal');
            
            $("#req_wt").val(req_wt);
            $(".req_wt_td").text(req_wt);
            $("#del_bal").val(del_bal);
            $(".del_bal_td").text(del_bal);
            $("#overlay").fadeOut(500);
        });
        
        
        $("#delivery_type").change(function() {
            
            $("#overlay").fadeIn(100);
            var val = $(this).val();
            var supplier = '<option value="">Select Supplier</option><?= select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $sql['supplier'], ' WHERE is_active="active"', '`'); ?>';
            var unit = '<option value="">Select Unit</option><?= select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $sql['assigned_emp'], ' WHERE type = 2', '`'); ?>';
            if(val=='Unit') {
                $("#delivery_to").html(unit);
                $(".delivery_to_label").text('Unit');
            } else {
                $("#delivery_to").html(supplier);
                $(".delivery_to_label").text('Supplier');
            }
            $("#overlay").fadeOut(500);
        });
    });
    
    $(document).ready(function() {

        $("#del_wt").keyup(function(){
            
            var val = $(this).val();

            var del_bal =  $("#del_bal").val();

            if(parseFloat(del_bal)<parseFloat(val)) {
                $(this).val(del_bal).select();
                message_noload('info', 'Max Qty Reached', 1500);
                return false;
            }
        });

        $(".addBtn").click(function(){
            var style_id = $("#style_id").val();
            var style_id_name = $("#style_id option:selected").text();
            var fabric = $("#fabric").val();
            var fabric_name = $("#fabric option:selected").text();
            var req_wt = $("#req_wt").val();
            var del_bal = $("#del_bal").val();
            var del_wt = $("#del_wt").val();
            var bag_roll = $("#bag_roll").val();
            
            if(style_id=="") {
                message_noload('error', 'BO | Style Required!', 1500);
                return false;
            } else if(fabric=="") {
                message_noload('error', 'Fabric Required!', 1500);
                return false;
            } else if(del_wt=="") {
                message_noload('error', 'Delivery Wt Required!', 1500);
                return false;
            } else if(bag_roll=="") {
                message_noload('error', 'Bag/ Roll Required!', 1500);
                return false;
            } else {
                
                var html = '<tr>';
                    html += '<td><input type="hidden" name="style_id[]" value="'+ style_id +'">'+ style_id_name +'</td>';
                    html += '<td><input type="hidden" name="fabric_id[]" value="'+ fabric +'">'+ fabric_name +'</td>';
                    html += '<td><input type="hidden" name="req_wt[]" value="'+ req_wt +'">'+ req_wt +'</td>';
                    html += '<td><input type="hidden" name="del_bal[]" value="'+ del_bal +'">'+ del_bal +'</td>';
                    html += '<td><input type="hidden" name="bag_roll[]" value="'+ bag_roll +'">'+ bag_roll +'</td>';
                    html += '<td><input type="hidden" name="del_wt[]" value="'+ del_wt +'">'+ del_wt +'</td>';
                    html += '<td><a class="btn" onclick="remove_tr(this)"><i class="fa fa-trash"></i></a></td>';
                    html += '</tr>';
                    
                    $("#main_tbody").append(html);
                    $("#tablefoot").find('input').val('');
                    // $("#tablefoot").find('select').val('').trigger('change');
                    $(".req_wt_td, .del_bal_td").text('-');

                    $('#fabric').find('option[value="'+ fabric +'"]').remove();
                    $('#fabric').trigger('change');
                    $(".savetr").removeClass('d-none');

                    // var ln = $("#main_tbody tr").length;

                    // // alert(ln);
                    // if(ln==1) {
                        // var aa = $("#style_id").find('option[value="'+ style_id +'"]');
                        // $("#style_id").find('option').not(aa).remove();
                        // $("#style_id").trigger('change');
                    // }
            }
        });

        $(".saveBtn").click(function() {
            var ln = $("#main_tbody tr").length;
            if(ln==0) {
                
                $(".savetr").addClass('d-none');
                message_noload('error', 'Add Items!', 1500);
            } else if($("#delivery_to").val()=="") {
                
                message_noload('error', 'Select Delivery To!', 1500);
                return false;
            } else {
                $(this).html('<i class="fa fa-spinner"></i> Saving..');                
                $("#add-poForm").submit();
            }
        });
    });
</script>

</body>

</html>