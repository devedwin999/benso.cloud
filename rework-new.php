<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_POST['SaveBtn'])) {
    
    
    // $bval = $_REQUEST['box_value'];
    
    // for($u=0; $u<count($bval); $u++)
    // {
        
    //     for($o=0; $o<count($_REQUEST[$bval[$u]]); $o++) {
            
    //         $mkl[$u][] = $_REQUEST[$bval[$u]][$o];
    //     }
        
    //     // print_r($mkl[$u]);
        
    //     $sqql = mysqli_query($mysqli, "UPDATE bundle_details SET s_out_complete = '".implode(',', $mkl[$u])."' WHERE id='".$_REQUEST['bundle_id'][$u]."'");
    // }
    // echo "<pre>", print_r($_POST, 1); die;
    // exit;
    $_SESSION['msg'] = "added";

    header("Location:view-sewingOutput.php");

    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comp = 'Edit Processing';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $id));
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
    <title>BENSO - Rework Entry</title>

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
    
    /*#reader__status_span {*/
    /*    font-size:25px !important;*/
    /*}*/
</style>

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">
        
        <div class="pd-ltr-20 xs-pd-20-10">
            <?php if(SEW_OUTPUT_ADD!=1) { action_denied(); exit; } ?>
            <div class="min-height-200px">
                
    <!--    		<div class="alert alert-warning alert-dismissible1 fade show" role="alert">-->
				<!--	<strong>Important!</strong> Scanned Piece details are saved automatically, To skip saving, click the <b>Cancel Scanning</b> button.-->
				<!--	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>-->
				<!--</div>-->

                <!-- Default Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="pd-20">
                        <a class="btn btn-outline-primary" href="rework-list.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Rework List</a> 
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">Rework Entry</h4>
                            
                        </div>
                    </div>
                    <form id="add-process" method="post" autocomplete="off">

                        <input type="hidden" name="process_type" id="process_type" value="rework_entry">

                        <div class="row">

                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['processing_code'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM processing_list WHERE processing_code LIKE '%RW-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'RW-1';
                                } else {
                                    $ex = explode('-', $sqql['processing_code']);
    
                                    $value = $ex[1];
                                    $intValue = (int) $value;
                                    $newValue = $intValue + 1;
                                    $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);
    
                                    $code = $ex[0] . '-' . $nnum;
                                }
                            }
                            ?>
                            <div class="col-md-2">
                                <label class="col-form-label">Entry Number <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" readonly name="processing_code" class="form-control"
                                        value="<?= $code; ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">Entry Date <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="date" name="entry_date" class="form-control"
                                        value="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="" class="rew_l">Rework Stage <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" <?= $_GET['id'] ? 'disabled' : 'name="rework_stage" id="rework_stage"'; ?>>
                                        <?= select_dropdown('mas_checking', array('id', 'checking_name'), 'checking_name DESC', $sql['rework_stage'], 'WHERE is_rework="Yes"', ''); ?>
                                    </select>
                                    
                                    <input type="hidden" value="<?= $sql['rework_stage']; ?>" <?= $_GET['id'] ? 'name="rework_stage" id="rework_stage"' : ''; ?>>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="" class="ord_l">BO <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" <?= $_GET['id'] ? 'disabled' : 'name="order_id" id="order_id"'; ?>>
                                        <?= select_dropdown('sales_order', array('id', 'order_code'), 'id DESC', $sql['order_id'], '', ''); ?>
                                    </select>
                                    
                                    <input type="hidden" value="<?= $sql['order_id']; ?>" <?= $_GET['id'] ? 'name="order_id" id="order_id"' : ''; ?>>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label em_l">Employee <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="employee" id="employee" class="custom-select2 form-control" style="width:100%">
                                        <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $sql['assigned_emp'], ' WHERE type="employee"', ''); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">Scan Using <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <?php if(!isset($_GET['id'])) { ?>
                                        <!--<input type="radio" value="Mobile" id="sc_Type_mobile" name="scanning_using"> <label for="sc_Type_mobile">Mobile</label>-->
                                        <input type="radio" value="Device" id="sc_Type_device" name="scanning_using" checked> <label for="sc_Type_device">Device</label>
                                        <!--<input type="radio" value="Manual" id="sc_Type_manual" name="scanning_using"> <label for="sc_Type_manual">Manual</label>-->
                                    <?php } else { ?>
                                        <input type="radio" value="<?= $sql['scanning_using'] ?>" id="sc_Type_" name="scanning_using" checked> <label for="sc_Type_"><?= $sql['scanning_using'] ?></label><br>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                            var scanCount = 0;
                        </script>
                        
                        <div class="row">
                            <div class="col-md-5"></div>
                            <div class="col-md-2" style="display:<?= isset($_GET['id']) ? 'none' : 'block'; ?>">
                                <a class="btn btn-outline-secondary" onclick="saveReworkHead()">Start Scanning</a>
                            </div>
                        </div>
                        
                        <br>
                        <div style="padding: 40px;display:<?= isset($_GET['id']) ? 'block' : 'none'; ?>">

                            <div class="row">
                                
                                <?php //if(P_OUTWARD_MAN_SCAN==1) 
                                if($sql['scanning_using'] == 'Device') { ?>
                                
                                    <div class="col-md-4"></div>
                                    
                                    <div class="col-md-4">
    
                                        <input type="hidden" name="processing_id" id="processing_id" value="<?= $_GET['id']; ?>">
                                        <input type="hidden" name="bundle_in" id="bundle_in" value="<?= $sql['boundle_id']; ?>">
                                        
                                        <input type="text" class="form-control" name="" id="device_bundle" value="" onkeyup="return getDeviceVal()" style="z-index: ;">
                                    </div>
                                    
                                    <div class="col-md-4"></div>
    
                                    <script>
                                        function getDeviceVal() {
                                            
                                            $("#add-process").submit(function(){
                                                return false;
                                            });
                                            
                                            setTimeout(function() {
                                                var val = $("#device_bundle").val();
                                                
                                                onScanSuccess(val);
                                            }, 200);
                                        };
                                        
                                    </script>
                                    
                                    <script type="text/javascript">
                                        
                                        function onScanSuccess(qrCodeMessage) {
                                            
                                            var order_id = $("#order_id").val();
                                            var rework_stage = $("#rework_stage").val();
                                            $.ajax({
                                                type: 'POST',
                                                url: 'ajax_search.php?validateQR_piece=1&qr=' + qrCodeMessage +'&type=rework_entry' + '&order_id=' + order_id + '&rework_stage=' + rework_stage,
                                                success: function (msg) {
                                                    
                                                    var json = $.parseJSON(msg);
                                                    
                                                    // alert(json.result);
                                                    // alert('gfh');
                                                    if(parseInt(scanCount) == 0){
                
                                                        if (json.result == 'notFound') {
                                                            scanCount++;
                                                            
                                                            $("#device_bundle").val('');
                                                            var popConfirm = confirm(json.message);
                                                            if(popConfirm){
                                                                setTimeout(function () {
                                                                    scanCount = 0;
                                                                }, 500);
                                                            } else {
                                                                setTimeout(function () {
                                                                    scanCount = 0;
                                                                }, 500);
                                                            }
                                                            return false;
                                                        } else {
                                                            scanCount++;
                                                            addrow_multi(json.result, 'qr');
                                                            $("#device_bundle").val('');
                                                            setTimeout(function () {
                                                                scanCount = 0;
                                                            }, 500);
                                                        }
                                                    }
                                                }
                                            })
                                        }
            
                                    </script>
                                <?php } ?>
                                

                                <!--<div class="col-md-12" style="text-align:center">-->
                                <!--    Scanned Bundle Count : <span id="scanedCnt">-->
                                        <? //= ($sql['boundle_id'] != "") ? count(explode(',', $sql['boundle_id'])) : '0'; ?>
                                <!--    </span>-->
                                <!--</div>-->
                                
                                
                                
                                <div class="col-md-12" style="margin-top:2%; overflow-y: auto;">

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>So.No</th>
                                                <th>Style No</th>
                                                <th>Part</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Boundle No</th>
                                                <th>Pieces</th>
                                                <th>Boundle QR</th>
                                                <th>Scanned Pieces</th>
                                                <th class="d-none">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody"></tbody>
                                    </table>
                                </div>
                            </div>

                            <hr>

                            <div class=" row">
                                <div class="col-md-12">
                                    <div class="form-group" style="text-align: center;">
                                        <a class="btn btn-secondary" href="rework-list.php" >Go Back</a>
                                        <input type="submit" class="btn btn-success" name="SaveBtn" value="Submit">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>

    <script>
        function removeRow(id) {
            // $("#tbTr" + id).remove();

            var pid = $("#processing_id").val();
            var cid = $("#scanedCnt").text();

            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?UpdBundlereturn=1&id=' + id + '&pid=' + pid +'&type=rework_entry',
                success: function (msg) {

                    var json = $.parseJSON(msg);

                    swal(
                        {
                            position: 'top-end',
                            type: 'success',
                            title: 'Bundle Removed',
                            showConfirmButton: false,
                            timer: 700
                        }
                    ).then(
                        function () {

                            $("#bundle_in").val(json.boundle_id);
                            $("#tbTr" + id).remove();
                            $("#scanedCnt").text((cid - 1));
                        })
                }
            });
        }
    </script>
    
    <script>
        function saveReworkHead() {
            
            if($("#employee").val()=="") {
                $(".em_l").addClass('req');
                message_noload('warning', 'Select Employee!', 1500);
                return false();
            } else if($("#order_id").val()=="") {
                $(".ord_l").addClass('req');
                message_noload('warning', 'Select BO!', 1500);
                return false();
            } else if($("#rework_stage").val()=="") {
                $(".rew_l").addClass('req');
                message_noload('warning', 'Select Rework Stage!', 1500);
                return false();
            }
            
            var form = $("#add-process").serialize();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?saveReworkHead=1',
                data: form,
                success: function (msg) {
                    
                    var json = $.parseJSON(msg);

                    if (json.ress == 0) {
                        window.location.href = 'rework-new.php?id=' + json.inid;
                    } else {
                        message_noload('error', 'Something Went Wrong!', 1500);
                    }
                }
            });
        }
    </script>

    <script>
        function addrow_multi(qr , head) {
            
            var scanType = '';
            // var scanType = $("#scanType").val();
            
            if (head == "") {
                if ($("#multibundle").val() == "" && qr == "") {
                    message_noload('warning', 'Select QR!', 1000);
                    return false; 
                }
            }
            
            var id = $("#processing_id").val();

            var form = $("#add-process").serialize();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?PieceScanning_=1&qr=' + qr + '&scanType=' + scanType + '&head=' + head,
                data: form,
                success: function (msg) {
                    
                    var json = $.parseJSON(msg);

                    if (json.ress == 0) {
                        window.location.href = 's-outputPiece.php?id=' + json.inid;
                    } else {
                        
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Piece Added',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(function () {
                            savedBundles(json.inid);
                            $("#bundle_in").val(json.bundle_in);
                        })
                    }
                }
            })
        }
    </script>
    
    <script>
        $(document).ready(function() {
            var id = <?= $_GET['id']; ?>;
            savedBundles(id);
        })
    
    
        function savedBundles(id)
        {
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?pieces_wise_scanned_rework=1&id='+ id,
                
                success: function (res) {
                    var njs = $.parseJSON(res);
                    
                    // swal(
                    //     {
                    //         position: 'top-end',
                    //         type: 'success',
                    //         title: 'Bundle Added',
                    //         showConfirmButton: false,
                    //         timer: 1000
                    //     }
                    // ).then(
                    //     function () {
                            $("#scanedCnt").text(njs.cntt);
                            $("#tableBody").html(njs.tbl_bdy);
                        // })
                }
            });
        }
    </script>

    <script type="text/javascript">
        $(function () {
            $('#add-process').validate({
                errorClass: "help-block",
                rules: {
                    p_type: {
                        required: true
                    },
                    process_id: {
                        required: true
                    },
                    supplier_id: {
                        required: true
                    }
                },
                errorPlacement: function (label, element) {
                    label.addClass('mt-2 text-danger');
                    label.insertAfter(element);
                },
                highlight: function (element, errorClass) {
                    $(element).parent().addClass('has-danger')
                    $(element).addClass('form-control-danger')
                }
            });
        });
        
        
        $("body").click(function() {
            device_focus()
        });
        
        $(document).ready(function() {
            device_focus()
        });
        
        function device_focus() {
            $("#device_bundle").focus();
        };
    </script>

</body>

</html>