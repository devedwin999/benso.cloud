<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_POST['SaveBtn'])) {
    
    // $bval = $_REQUEST['box_value'];
    
    // for($u=0; $u<count($bval); $u++)
    // {
        
    //     for($o=0; $o<count($_REQUEST[$bval[$u]]); $o++) {
    //         $mkl[] = $_REQUEST[$bval[$u]][$o];
    //     }
        
    //     $sqql = mysqli_query($mysqli, "UPDATE bundle_details SET s_out_complete = '".implode(',', $mkl)."' WHERE id='".$_REQUEST['bundle_id'][$u]."'");
    // }
    
    // for($t=0; $t<count($_REQUEST['bndl_iid']); $t++)
    // {
    //     mysqli_query($mysqli, "UPDATE bundle_details SET ch_rework_stage = '". $_REQUEST['rework_stage'][$t] ."' WHERE id='".$_REQUEST['bndl_iid'][$t]."'");
    // }
    
    $_SESSION['msg'] = "added";

    header("Location:checking-list.php");

    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $id));
} else {
    $id = '';
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Checking Entry</title>

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
    
    .rounded {
        padding: 1px !important;
    }
    
    .tmp-hide {
        display:none;
        border-top: none;
        border-right: none;
        border-left: none;
        width:50%;
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
            <div class="min-height-200px">
                
                <div class="alert alert-warning alert-dismissible1 fade show" role="alert">
					<strong>Important!</strong> Scanned bundle details are saved automatically, To skip saving, click the <b>Cancel Scanning</b> button.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>


                <!-- Default Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    <?php if(CHECKING_ADD!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <a class="btn btn-outline-primary" href="checking-list.php" style="float: right;"><i
                                class="fa fa-list" aria-hidden="true"></i> Checking List</a> 
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">
                                Checking Entry
                            </h4>
                        </div>
                    </div>
                    <form id="add-process" method="post" autocomplete="off">

                        <input type="hidden" name="process_type" id="process_type" value="checking_list">

                        <div class="row">

                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['processing_code'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM processing_list WHERE processing_code LIKE '%CH-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'CH-1';
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
                                <label class="col-form-label">Employee <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="employee" id="employee" class="custom-select2 form-control" style="width:100%">
                                        <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $sql['assigned_emp'], 'WHERE type="employee"', ''); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="">BO <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    
                                    <select class="custom-select2 form-control" <?= $_GET['id'] ? 'disabled' : 'name="order_id" id="order_id"'; ?>>
                                        <?= select_dropdown('sales_order', array('id', 'order_code'), 'id DESC', $sql['order_id'], '', ''); ?>
                                    </select>
                                    
                                    <input type="hidden" value="<?= $sql['order_id']; ?>" <?= $_GET['id'] ? 'name="order_id" id="order_id"' : ''; ?>>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">Scanning For <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="scanning_for" id="scanning_for" class="custom-select2 form-control" style="width:100%">
                                            <option value="Sewing">Sewing Out Pcs</option>
                                            <option value="Rework">Rework Pcs</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3 d-none">
                                <label class="col-form-label">Scan Type <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="scanType" id="scanType" class="custom-select2 form-control" style="width:100%">
                                        <!--<option value="bundle">Bundle</option>-->
                                        <?php //if(!isset($_GET['id'])) { ?>
                                            <option value="piece">Piece</option>
                                        <?php //} ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">Scan Using <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <?php if(!isset($_GET['id'])) { ?>
                                        <!--<input type="radio" value="Mobile" id="sc_Type_mobile" name="scanning_using" > <label for="sc_Type_mobile">Mobile</label>-->
                                        <input type="radio" value="Device" id="sc_Type_device" name="scanning_using" checked> <label for="sc_Type_device">Device</label>
                                        <!--<input type="radio" value="Manual" id="sc_Type_manual" name="scanning_using"> <label for="sc_Type_manual">Manual</label>-->
                                    <?php } else { ?>
                                        <input type="radio" value="<?= $sql['scanning_using'] ?>" id="sc_Type_" name="scanning_using" checked> <label for="sc_Type_"><?= $sql['scanning_using'] ?></label><br>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row d-none">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <label class="col-form-label">Checking Type <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="pieces_type" id="pieces_type" onchange="pieces_typewe()" class="custom-select2 form-control" style="width:100%">
                                        <option value="Good">Good Pcs</option>
                                        <option value="Rework">Rework Pcs</option>
                                        <option value="Rejection">Rejection Pcs</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                            function pieces_typewe() {
                                var a = $("#pieces_type").val();
                                
                                // message_noload('info', 'Scanning bundles are considered '+a+' bundles!', 2000);
                            }
                        </script>
                        
                        <script>
                            var scanCount = 0;
                        </script>

                        <script src="src/html5-qrcode.min.js"></script>

                        <div class="row" align="center" style="display:<?= isset($_GET['id']) ? 'block' : 'none'; ?>">
                            <div class="col">
                                <div style="width:100% !important;" id="reader"></div>
                            </div>
                        </div>

                        <script type="text/javascript">

                            function onScanSuccess(qrCodeMessage) {
                                // alert(qrCodeMessage);

                                $.ajax({
                                    type: 'POST',
                                    url: 'ajax_search.php?validateQR=1&qr=' + qrCodeMessage +'&type=checking_list',
                                    success: function (msg) {
                                        var json = $.parseJSON(msg);
                                        
                                        if(parseInt(scanCount) == 0){

                                            if (json.result == 'notFound') {
    
                                                scanCount++;

                                                var popConfirm = confirm(json.message);
                                                if(popConfirm){
                                                    setTimeout(function () {
                                                        scanCount = 0;
                                                    }, 2000);
                                                } else {
                                                    setTimeout(function () {
                                                        scanCount = 0;
                                                    }, 2000);
                                                }
                                                return false;
                                            } else {

                                                scanCount++;
                                                
                                                swal({
                                                    title: 'Are you sure?',
                                                    text: "Confirm Add Bundle!",
                                                    type: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Yes, Proceed!',
                                                    cancelButtonText: 'No, cancel!',
                                                    confirmButtonClass: 'btn btn-success margin-5',
                                                    cancelButtonClass: 'btn btn-danger margin-5',
                                                    buttonsStyling: false
                                                }).then(function (dd) {
                                                    if (dd['value'] == true) {
                                                        addrow_multi(json.result, '');
                                                        
                                                        setTimeout(function () {
                                                            scanCount = 0;
                                                        }, 2000);
                                                    } else {
                                                        swal(
                                                            'Cancelled',
                                                            '',
                                                            'error'
                                                        )
                                                        
                                                        setTimeout(function () {
                                                            scanCount = 0;
                                                        }, 2000);
                                                    }
                                                })
                                            }
                                        }
                                    }
                                })
                            }

                            function onScanError(errorMessage) {
                                //handle scan error
                            }

                            var html5QrcodeScanner = new Html5QrcodeScanner(
                                "reader", { fps: 10, qrbox: 250 });
                            html5QrcodeScanner.render(onScanSuccess, onScanError);

                        </script>

                        <div class="row">
                            <div class="col-md-5"></div>
                            <div class="col-md-2" style="display:<?= isset($_GET['id']) ? 'none' : 'block'; ?>">
                                <a class="btn btn-outline-secondary" onclick="addrow_multi('', 'head')">Start Scanning</a>
                            </div>
                        </div>

                        <script>
                            function saveHeadprocess() {
                                if ($("#employee").val() == "") {
                                    message_noload('warning', 'Select Employee!', 1000);
                                    return false;
                                } else {
                                    addrow_multi('', 'head');
                                }
                            }
                        </script>

                        <div style="padding: 40px;display:<?= isset($_GET['id']) ? 'block' : 'none'; ?>">

                            <div class="row">

                                <!--<div class="col-md-2">-->
                                <!--    <select class="custom-select2 form-control" name="order_id" id="order_id1">-->
                                <!--        <?= select_dropdown('sales_order', array('id', 'order_code'), 'id DESC', '', '', ''); ?>-->
                                <!--    </select>-->
                                <!--</div>-->

                                <div class="col-md-2">
                                    <select class="custom-select2 form-control" name="style_num" id="style_num">
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select class="custom-select2 form-control" name="partNum" id="partNum">
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    <div class="input-group mb-3">

                                        <input type="hidden" name="processing_id" id="processing_id"
                                            value="<?= $_GET['id']; ?>">
                                        <input type="hidden" name="bundle_in" id="bundle_in"
                                            value="<?= $sql['boundle_id']; ?>">

                                        <select class="custom-select2 form-control" name="multibundle[]"
                                            id="multibundle" multiple>

                                        </select>

                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" onclick="getCheckingDetail('', '')"
                                                type="button">Add</button>
                                            <!--<button class="btn btn-outline-secondary" onclick="addrow_multi('', '')"-->
                                            <!--    type="button">Add</button>-->
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" style="text-align:center">
                                    Scanned Bundle Count : <span id="scanedCnt">
                                        <?= ($sql['boundle_id'] != "") ? count(explode(',', $sql['boundle_id'])) : '0'; ?>
                                    </span>
                                </div>


                                <div class="col-md-12" style="overflow-y: scroll;">

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Bo.No</th>
                                                <th>Style No</th>
                                                <th>Part</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Bundle No & QR</th>
                                                <th>Pcs In Bundle</th>
                                                <th>Good Pcs</th>
                                                <th>Rejection Pcs</th>
                                                <th>Rework Pcs</th>
                                                <th>Rework Stage</th>
                                                <th class="text-danger">Missing Pcs</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <?php
                                            // foreach (explode(',', $sql['boundle_id']) as $qrr) {
                                            //     if ($qrr != "") {
                                            //         $qry = "SELECT a.*, b.order_code, b.ref_id, c.style_no, d.color_name, e.type ";
                                            //         $qry .= "FROM bundle_details a ";
                                            //         $qry .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
                                            //         $qry .= "LEFT JOIN sales_order_detalis c ON b.style=c.id ";
                                            //         $qry .= "LEFT JOIN color d ON b.color=d.id ";
                                            //         $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
                                            //         $qry .= "WHERE a.id='" . $qrr . "' ";

                                            //         $res = mysqli_query($mysqli, $qry);

                                            //         $sql = mysqli_fetch_array($res);

                                            //         print '<tr id="tbTr' . $qrr . '"><td> <input type="hidden" id="" name="boundle_id[]" value="' . $sql['id'] . '"> ' . $sql['order_code'] . '</td> <td>' . $sql['ref_id'] . '</td> <td>' . $sql['style_no'] . '</td> <td>' . $sql['color_name'] . '</td> <td>' . $sql['type'] . '</td> <td>' . $sql['bundle_number'] . '</td> <td>' . $sql['pcs_per_bundle'] . '</td> <td>' . $sql['boundle_qr'] . '</td> <td><i class="icon-copy fa fa-trash-o" aria-hidden="true" onclick="removeRow(' . $qrr . ')" title="Remove"></i></td></tr>';
                                            //     }
                                            // }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <hr>

                            <div class=" row">
                                <div class="col-md-12">
                                    <div class="form-group" style="text-align: center;">
                                        <!--<a class="btn btn-secondary" href="view-processing.php">Cancel</a>-->
                                        <a class="btn btn-warning" onclick="delete_entry(<?= $_GET['id']; ?>)">Cancel Scanning</a>
                                        <a class="btn btn-secondary" href="checking-list.php">Go Back</a>
                                        <input type="submit" class="btn btn-success" name="SaveBtn" value="Submit">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal fade bs-example-modal-lg" id="piecesFindModal" tabindex="-1" role="dialog"
                            aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-top ">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myLargeModalLabel">Scanned Bundles</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Bundle No</th>
                                                    <th>Pcs In Bundle</th>
                                                    <th>Good Pcs</th>
                                                    <th>Rejection Pcs</th>
                                                    <th>Rework Pcs</th>
                                                    <th>Oil</th>
                                                </tr>
                                            </tbody>
                                            <tbody id="mtbody"></tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <input id="idQr" type="hidden">
                                        <button type="button" class="btn btn-secondary" onclick="addrow_multi('', '')" data-dismiss="modal">Add</button>
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
        function getCheckingDetail(qr, head) {
            
            $("#idQr").val(qr);
            if (head == "") {
                if ($("#multibundle").val() == "" && qr == "") {
                    message_noload('warning', 'Select QR!', 1000);
                    return false; 
                }
            }
            
            var bundleId = $("#multibundle").val();
            $.ajax({
                type:'POST',
                url:'ajax_search.php?getBundle_checking_details=1&bundleId='+ bundleId,
                success:function(msg){
                    // var json = $.parseJSON(msg);
                    // var json = $.parseJSON(msg);
                    // alert(msg);
                    
                    $("#mtbody").html(msg);
                }
            })
            $("#piecesFindModal").modal('show');
            
            return false;
        }
    </script>
    
    <script>
        function delete_entry(id)
        {
            swal({
                title: 'Are you sure?',
                text: "Confirm Delete This entry?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    $.ajax({
                        type:'POST',
                        url:'ajax_action.php?delete_processing_list=1&id='+id +'&type=checking_list',
                        success:function(msg){
                            swal(
                                {
                                    position: 'center',
                                    type: 'success',
                                    title: 'Scanning cancelled!', 
                                    showConfirmButton: true,
                                    timer: 2000
                                }
                            ).then(
                                function () {
                                    window.location.href="checking-list.php";
                                })
                        }
                    })
                } else {
                    swal(
                        'Cancelled',
                        '',
                        'error'
                    )
                    
                    setTimeout(function () {
                        scanCount = 0;
                    }, 2000);
                }
            })
        }
    </script>

    <script>
        $("#order_id").change(function () {
            var a = $(this).val();
            if (a == "") {
                $("#style_num").html('').trigger('change');
                $("#partNum").html('').trigger('change');
                $("#multibundle").html('').trigger('change');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getStyleNoforProcess=1&id=' + a,
                success: function (msg) {
                    $("#style_num").html(msg);
                }
            })
        })
    </script>

    <script>
        $("#style_num").change(function () {
            var a = $(this).val();

            if (a == "") {
                $("#partNum").html('').trigger('change');
                $("#multibundle").html('').trigger('change');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getPartNoforProcess=1&id=' + a,
                success: function (msg) {

                    // alert(msg);
                    $("#partNum").html(msg);
                }
            })
        })
    </script>

    <script>
        $("#partNum").change(function () {

            var prtno = $(this).val();
            var ordno = $("#order_id").val();
            var stylno = $("#style_num").val();

            if (prtno == "") {
                $("#multibundle").html('').trigger('change');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getbundleNoProcess=1&prtno=' + prtno + '&ordno=' + ordno + '&stylno=' + stylno +'&type=checking_list&selected=',
                success: function (msg) {
                    $("#multibundle").html(msg);
                }
            })
        })
    </script>

    <script>
        $("#multibundle").change(function () {
            
            var all = $(this).val();
            
            if(all[0] == 'select_all') {
                
                var prtno = $("#partNum").val();
                var ordno = $("#order_id").val();
                var stylno = $("#style_num").val();
    
                if (prtno == "") {
                    $("#multibundle").html('').trigger('change');
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    url: 'ajax_search.php?getbundleNoProcess=1&prtno=' + prtno + '&ordno=' + ordno + '&stylno=' + stylno +'&type=checking_list&selected=selected',
                    success: function (msg) {

                        $("#multibundle").html(msg);
                    }
                })
            }
        })
    </script>
    
    <script>
        function removeRow(id) {
            // $("#tbTr" + id).remove();

            var pid = $("#processing_id").val();
            var cid = $("#scanedCnt").text();

            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?UpdBundlereturn=1&id=' + id + '&pid=' + pid +'&type=checking_list',
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
        function addrow_multi(qr, head) {

            // if ($("#line").val() == "") {
            //     message_noload('warning', 'Select Line!', 1000);
            //     return false;
            // } else
            if (head == "") {
                if ($("#multibundle").val() == "" && qr == "") {
                    message_noload('warning', 'Select QR!', 1000);
                    return false; 
                }
            } else {
                if ($("#employee").val() == "") {
                    message_noload('warning', 'Select Employee!', 1000);
                    return false;
                }
            }
            
            var mult = $("#multibundle").val();
            var id = $("#processing_id").val();
            // var qr = $("#idQr").val();
            
            var scanType = $("#scanType").val();
            var form = $("#add-process").serialize();

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getboundle_details=1&scanner=' + qr + '&head=' + head,
                data: form,
                success: function (msg) {
                    var json = $.parseJSON(msg);
                    if (json.ress == 0) {
                        // window.location.href = 'checking-new.php?id=' + json.inid;
                        // if (scanType == "bundle") {
                        //     window.location.href = 'checking-new.php?id=' + json.inid;
                        // } else if (scanType == "piece") {
                            window.location.href = 'checking-Piece.php?id=' + json.inid;
                        // }
                    } else {
                        swal(
                        {
                            position: 'top-end',
                            type: 'success',
                            title: 'Bundle Added',
                            showConfirmButton: false,
                            timer: 1000
                        }
                    ).then(
                        function () {
                            savedBundles(json.inid);
                            $("#bundle_in").val(json.bundle_in);
                        })
                    }
                }
            })

            $("#order_id").val('').trigger('change');

            // $("#scanedCnt").text($('.table tr').length);
        }
    </script>
    
    <script>
        $(document).ready(function() {
            var id = <?= $_GET['id']; ?>;
            savedBundles(id);
            pieces_typewe();
        })
    
    
        function savedBundles(id)
        {
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?checked_pieces_details=1&id='+ id,
                
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

    <script>
        $(".btn-outline-secondary").click(function () {
            addrow();
        })

        $("#qr_number").keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();

                var qr = $(this).val();

                addrow(qr);
            }
        });


        function addrow____(qr) {
            var a = $("#bundle_details").val();
            if (a == "" && qr == "") {
                message_noload('warning', 'Enter correct QR code!', 1000);
                $(".d-cursor").focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getboundle_details=1&id=' + a + '&qr=' + qr,
                success: function (msg) {

                    var json = $.parseJSON(msg);

                    if (json.num == 0) {
                        message_noload('warning', 'Bundle Not Found!', 1000);
                    } else {

                        $("#tableBody").append(json.table);
                        $("#boundle_id").val('');
                        $("#qr_number").val('');

                        swal(
                            {
                                position: 'top-end',
                                type: 'success',
                                title: 'Bundle Added',
                                showConfirmButton: false,
                                timer: 1000
                            }
                        )
                    }
                }
            })
        }
    </script>

    <script>
        $("#qr_number").autocomplete({
            source: "ajax_action.php?auto_complete=1&table=bundle_details&searchField=boundle_qr",
            select: function (event, ui) {
                event.preventDefault();
                console.log(ui);
                $("#qr_number").val(ui.item.value);
                $("#boundle_id").val(ui.item.id);
            }
        });
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
    </script>
    
    
    <script>
        function showPices(id) {
            if ($("#show_pieces" + id).val() == "") {
                $("#trcBoxx" + id).show();
                $("#show_pieces" + id).val('1');
                $("#iconn" + id).removeClass('ion-chevron-right').addClass('ion-chevron-down');
            } else {
                $("#trcBoxx" + id).hide();
                $("#show_pieces" + id).val('');
                $("#iconn" + id).removeClass('ion-chevron-down').addClass('ion-chevron-right');
            }
        }
    </script>
    
    <script>
        function showEdit(id)
        {
            $(".hideoo"+id).hide();
            $(".inpoo"+id).show();
            $(".c"+id).show();
            $(".e"+id).hide();
        }
        function cancelEdit(id)
        {
            $(".hideoo"+id).show();
            $(".inpoo"+id).hide();
            $(".c"+id).hide();
            $(".e"+id).show();
        }
        
        function saveEdit(id)
        {
            var good = $("#g_Qty"+id).val();
            var reject = $("#rej_Qty"+id).val();
            var rework = $("#rwrk_Qty"+id).val();
            var Total = $("#tot_ppce"+id).val();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?update_qtty_check=1&id=' + id + '&good=' + good + '&reject=' + reject + '&rework=' + rework + '&Total=' + Total,
                success: function (msg) {
                    
                    message_noload('success', 'Updated!', 1500);
                    savedBundles(<?= $_GET['id']; ?>)
                }
            })
        }
    </script>
    
    <script>
        function CH(id) {
            var tot = 0;
                $(".MaxCount"+id).each(function(){
                    tot += parseInt($(this).val());
                })
                
            var ToalCount = $(".ToalCount"+id).val();
            
            if(ToalCount<tot) {
                message_noload('warning', 'Qty exceed', 1500);
                $(".MaxCount"+id).val(0);
            }
        }
    </script>

</body>

</html>