<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_REQUEST['SaveBtnff'])) {
    // print_r($_REQUEST['box_value']);
    // exit;

    for ($i = 0; $i < count($_REQUEST['bundle_id']); $i++) {
        $dta = array(
            'processing_id' => $_REQUEST['processing_list'],
            'bundle_id' => $_REQUEST['bundle_id'][$i],
            'bundle_qr' => bundle_qr($_REQUEST['bundle_id'][$i]),
            'pieces_qr' => implode(',', bundle_qr($_REQUEST['bundle_id'][$i])),
            'created_date' => date('Y-m-d H:i:s')
        );

        if (empty($_REQUEST['saved_id'][$i])) {
            $qry = Insert('inwarded_bundle', $dta);
            $_SESSION['msg'] = "added";
        } else {
            Update('inwarded_bundle', $dta, " WHERE id = '" . $_REQUEST['saved_id'][$i] . "'");
            $_SESSION['msg'] = "updated";
        }
        
        $ndta = array(
            'complete_processing' => 'yes',
            'complete_processing_date' => date('Y-m-d H:i:s')
            );
        Update('bundle_details', $ndta, " WHERE id = '" . $_REQUEST['bundle_id'][$i] . "'");
    }

    $dta2 = array(
        'is_inwarded' => 1,
        'dc_num' => $_REQUEST['dc_num'],
        'dc_date' => $_REQUEST['dc_date'],
    );

    Update('processing_list', $dta2, " WHERE id = '" . $_REQUEST['processing_list'] . "'");



    header("Location:inward-list.php");

    exit;
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comp = 'Edit ';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $id));
} else {
    $comp = 'Add ';
    $id = '';
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Inward List
    </title>

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
</style>

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <!-- Default Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    <?php if(P_INWARD_ADD!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <a class="btn btn-outline-primary" href="inward-list.php" style="float: right;"><i
                                class="fa fa-list" aria-hidden="true"></i> Inward List</a>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">
                                <?= $comp; ?>Inward Process
                            </h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="add-process" method="post" autocomplete="off">

                        <div class="row">
                            
                            <div class="col-md-2">
                                <label class="col-form-label">Process To <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="input_type" id="input_type" class="custom-select2 form-control" style="width:100%">
                                        <option value="">Select</option>
                                        <option value="Supplier">Supplier</option>
                                        <option value="Employee">Employee</option>
                                        <option value="Unit">Unit</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2 to_div d-none">
                                <label class="col-form-label fieldrequired line_label"><?= $sql['input_type'] ? $sql['input_type'] : 'Supplier'; ?></label>
                                <div class="form-group">
                                    <select name="line" id="line" class="custom-select2 form-control get_process_list" style="width:100%">
                                        <option value="">Select <?= $sql['input_type'] ? $sql['input_type'] : 'Supplier'; ?></option>
                                        <?php
                                            if($sql['input_type']=='Employee') {
                                                print select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $sql['assigned_emp'], ' WHERE is_active="active"', '`');
                                            } else if($sql['input_type']=='Unit') {
                                                print select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $sql['assigned_emp'], ' WHERE type = 2', '`');
                                            } else {
                                                print select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $sql['assigned_emp'], '', '1');
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4" style="">
                                <label for="" class="fieldrequired">Processing List</label>
                                <div class="input-group mb-3">
                                    <input type="hidden" name="boundle_id" id="boundle_id">
                                    <select name="processing_list" id="processing_list" class="custom-select2 form-control">
                                        <?php
                                        $opp = "SELECT a.id, a.processing_code, a.order_id ";
                                        $opp .= " FROM processing_list a ";
                                        $opp .= " WHERE ";
                                            if (isset($_GET['id'])) {
                                        $opp .= " a.id= '" . $sql['id'] ."' ";
                                            } else {
                                                if ($_SESSION['login_role'] != 1) {
                                                $wh = ' AND created_unit="' . $logUnit . '"';
                                            } else {
                                                $wh = '';
                                            }
                                        $opp .= ' type="process_outward" AND ((input_type="Unit" AND is_inwarded IS NULL AND complete_inhouse="completed" ' . $wh .' ) OR (is_inwarded IS NULL AND input_type!="Unit" AND created_unit='.$logUnit.')) ';
                                            }
                                        $opp .= " ORDER BY id DESC";
                                            
                                        $qyyy = mysqli_query($mysqli, $opp);
                                        
                                            print '<option value="">Select</option>';
                                        while($pss = mysqli_fetch_array($qyyy)) {
                                            $vss = ($sql['id'] == $pss['id']) ? 'selected' : '';
                                            print '<option value="'. $pss['id'] .'" '. $vss .'>BO : '. sales_order_code($pss['order_id']) .' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DC : '. $pss['processing_code'] .'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        
                            <div class="col-md-2">
                                <label for="" class="fieldrequired">Supplier Dc Number : </label>
                                <input type="text" name="dc_num" id="dc_num" placeholder="DC Number" class="form-control" value="<?= $sql['dc_num'] ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="" class="fieldrequired">Supplier Dc Date : </label>
                                <input type="date" name="dc_date" id="dc_date" class="form-control" value="<?= $sql['dc_date'] ? $sql['dc_date'] : date('Y-m-d'); ?>">
                                
                                <input type="hidden" name="SaveBtnff" class="form-control" >
                            </div>
                        </div>

                        <div style="overflow-y: auto;width:100%">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Boundle QR</th>
                                        <th>Boundle No</th>
                                        <th>Boundle Qty</th>
                                        <th>So.No</th>
                                        <th>Ref Code</th>
                                        <th>Style No</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <tr><td colspan="8" class="text-center">No Data Found</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row mainDiv d-none">
                            <div class="col-md-12">
                                <div class="form-group" style="text-align: center;">
                                    <a class="btn btn-outline-secondary" href="inward-list.php">Cancel</a>
                                    <a class="btn btn-outline-success saveInward">Submit</a>
                                </div>
                            </div>
                        </div>
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
        $(".get_process_list").change(function() {
            
            var type = $("#input_type").val();
            var value = $(this).val();
            
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search2.php?get_processinglist=1&type=' + type + '&value=' + value,
                
                success: function(msg) {
                   var json = $.parseJSON(msg);
                   
                   $("#processing_list").html(json.option);
                }
            });
        });
    </script>
    
    <script>
        $(".saveInward").click(function() {
            
            var processing_list = $("#processing_list").val();
            var dc_num = $("#dc_num").val();
            var dc_date = $("#dc_date").val();
            
            if(processing_list=="") {
                message_noload('error', 'Select Processing List!', 2000);
                return false;
            } else if(dc_num=="") {
                $("#dc_num").focus();
                message_noload('error', 'Enter DC Number!', 2000);
                return false;
            } else if(dc_date=="") {
                message_noload('error', 'Select DC Date!', 2000);
                return false;
            } else {
                $("#overlay").fadeIn(100);
                var data = "dc_date=" + dc_date + "&processing_list=" + processing_list + "&dc_num=" + dc_num;
                
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action2.php?saveInward=1',
                    data: data,
                    
                    success: function(msg) {
                       var json = $.parseJSON(msg);
                       $("#overlay").fadeOut(500);
                       if(json.result==0) {
                           message_redirect('success', 'Success Inwarded!', 2000, 'inward-list.php');
                       } else {
                           message_error();
                       }
                    }
                });
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            var id = $("#processing_list").val();
            processing_list(id);
        })
        $("#processing_list").change(function () {
            var id = $(this).val();
            processing_list(id);
        })
        
        function processing_list(id) {
            // $("#overlay").fadeIn(100);
            
            var id = id ? id : 0;
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getInward=1&id=' + id,
                
                success: function (msg) {
                    // $("#overlay").fadeOut(500);
                    if (id == "") {
                        $(".mainDiv").addClass('d-none');
                    } else {
                        $(".mainDiv").removeClass('d-none');
                    }
                    $("#tableBody").html(msg);
                }
            })

        }
    </script>

    <script>
        function ncbox(id) {
            var a = $("#ncbox" + id).is(":checked");
            if (a == true) {
                $(".ncbox" + id).prop('checked', true);
            } else {
                $(".ncbox" + id).prop('checked', false);
            }
        }
    </script>
    
    
    
    
    
    <script>
        $("#input_type").change(function() {
            
            var val = $(this).val();
            $("#overlay").fadeIn(100);
            if(val == 'Supplier') {
                var to = '<option value="">Select Supplier</option> <?= select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $sql['assigned_emp'], '', '1'); ?>';
                    $(".to_div").removeClass('d-none');
            } else if(val == 'Employee') {
                var to = '<option value="">Select Employee</option> <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $sql['assigned_emp'], ' WHERE is_active="active"', '`'); ?>';
                    $(".to_div").removeClass('d-none');
            } else if(val == 'Unit') {
                var to = '<option value="">Select Unit</option> <?= select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $sql['assigned_emp'], ' WHERE type = 2', '`'); ?>';
                    $(".to_div").removeClass('d-none');
            } else {
                $(".to_div").addClass('d-none');
            }
            $("#overlay").fadeOut(500);
            $(".line_label").text(val);
            $("#line").html(to);
        });
    </script>

</body>

</html>