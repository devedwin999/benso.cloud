<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

$data = array();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.*, b.order_code FROM cutting_barcode a LEFT JOIN sales_order b ON a.order_id=b.id WHERE a.id=" . $id));
} else {
    $id = '';
}



if (isset($_POST['saveBarcode'])) {

    $qryz = mysqli_query($mysqli, "SELECT entry_number FROM cutting_barcode ORDER BY id DESC");

    $sqql = mysqli_fetch_array($qryz);
    $numm = mysqli_num_rows($qryz);
    if ($numm == 0) {
        $entry_number = 'REF-1';
    } else {
        $ex = explode('-', $sqql['entry_number']);

        $value = $ex[1];
        $intValue = (int) $value;
        $newValue = $intValue + 1;
        $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);

        $entry_number = $ex[0] . '-' . $nnum;
    }
    
    $ok = '';
    
    $ptt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sod_part WHERE id=". $_REQUEST['sod_part_id']));
    
    $data = array(
        'lay_number' => $_REQUEST['lay_number'],
        'from_bno' => reset($_REQUEST['frombundleno']),
        'to_bno' => end($_REQUEST['tobundleno']),
        'entry_number' => $entry_number,
        'entry_date' => $_REQUEST['date'],
        
        'order_id' => $_REQUEST['order_id'],
        'style' => $_REQUEST['styleList'],
        'sod_combo' => $_REQUEST['combo_id'],
        'sod_part' => $_REQUEST['sod_part_id'],
        'combo_id' => $ptt['combo_id'],
        'part_id' => $ptt['part_id'],
        'color_id' => $ptt['color_id'],
        
        'employee' => $_REQUEST['employee'],
        'fabric' => $_REQUEST['fabric'],
        'fabric_color' => $_REQUEST['fabric_color'],
        'fabric_lot' => $_REQUEST['fabric_lot'],
        'gsm' => $_REQUEST['gsm'],
        'dia' => $_REQUEST['dia'],
        'wt' => $_REQUEST['wt'],
        'lay_length' => $_REQUEST['lay_length'],
        'per_lay_no' => $_REQUEST['per_lay_no'],
        'per_day_wt' => $_REQUEST['per_day_wt'],
        'no_of_lay' => $_REQUEST['no_of_lay'],
        'total_taken_wt' => $_REQUEST['total_taken_wt'],
        'reject_wt' => $_REQUEST['reject_wt'],
        'created_unit' => $logUnit,
        'created_by' => $logUser,
    );

    if ($_REQUEST['cutting_barcode_id'] == "") {
        $qry = Insert('cutting_barcode', $data);
        $inid = mysqli_insert_id($mysqli);
    } else {
        $qry = Update('cutting_barcode', $data, " WHERE id = '" . $_REQUEST['cutting_barcode_id'] . "'");
        $inid = $_REQUEST['cutting_barcode_id'];
    }
    

    for ($m = 0; $m < count($_REQUEST['variation_value']); $m++) {
        
        foreach (range($_REQUEST['frombundleno'][$m], $_REQUEST['tobundleno'][$m]) as $number) {
            
            $mdata = array(
                'cutting_barcode_id' => $inid,
                'order_id' => $_REQUEST['order_id'],
                'style_id' => $_REQUEST['styleList'],
                'sod_combo' => $_REQUEST['combo_id'],
                'sod_part' => $_REQUEST['sod_part_id'],
                'sod_size' => $_REQUEST['sod_size_id'][$m],
                'combo' => $ptt['combo_id'],
                'part' => $ptt['part_id'],
                'color' => $ptt['color_id'],
                'variation_value' => $_REQUEST['variation_value'][$m],
                
                'lay_length' => $_REQUEST['lay_number'],
                'order_qty' => $_REQUEST['order_qty'][$m],
                'cutting_qty' => $_REQUEST['cutting_qty'][$m],
                'pcs_per_bundle' => $_REQUEST['pcs_per_bundle'][$m],
                'total_bundle' => $_REQUEST['noOfbundle'][$m],
                'bundle_number' => $number,
                'entry_date' => date('Y-m-d'),
                'created_unit' => $logUnit,
                'created_by' => $logUser,
                'date' => date('Y-m-d')
            );
            
            if ($_REQUEST['cutting_qty'][$m] > 0 && $_REQUEST['pcs_per_bundle'][$m] > 0) {
                $qry = Insert('bundle_details', $mdata);
            }
            
        }
    }
    
    
    if(isset($_GET['lay']))
    {
        $newLay = $_REQUEST['layNumber'];
        $newCode = $_REQUEST['layReff'];
    } else {
        $newLay = $llth;
        $newCode = $code;
    }
    

    $_SESSION['msg'] = "saved";

    header("Location:view-barcode.php");

    exit;
}

?>
<!DOCTYPE html>
<html>

<head>

    <style>
        .table td,
        .table th {
            border-top: 0px solid #dee2e6 !important;
        }

        .col-md-4 {
            padding: 15px !important;
        }

        .addicon {
            font-size: 17px !important;
            color: #5e5e5e;
        }

        input.master:focus {
            border-color: #d7c214 !important;
        }

        .nav-link:hover {
            color: #1a8dc6 !important;
            border-bottom: 2px solid #1a8dc6 !important;
        }

        /* .form-control:disabled,
        .form-control[readonly] {
            background-color: #fbfbfb !important;
            opacity: 1;
        } */
    </style>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Add Budget
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

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">


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

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="pd-20 card-box mb-30">
                    <?php page_spinner(); if(CUTTING_QR_ADD!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <h4 class="text-blue h4">Cutting Barcode
                            <a class="btn btn-outline-primary" href="view-barcode.php" style="float: right;"><i
                                    class="fa fa-list" aria-hidden="true"></i> Barcode List</a>
                        </h4>
                        <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                    </div>

                    <form id="saveBarcodeForm" method="POST" enctype="multipart/form-data">

                        <input type="hidden" value="<?= $id; ?>" name="cutting_barcode_id" id="cutting_barcode_id">

                        <div class="row">
                            <?php if (isset($_GET['id'])) {
                                $nql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE cutting_barcode_id='" . $_GET['id'] . "' AND lay_length='" . $_GET['lay'] . "' "));
                                if (isset($_GET['lay'])) {
                                    ?>
                                    <div class="col-md-3">
                                        <h5>Entry Number : <span style="color: #f22;">
                                                <?= $nql['entry_num']; ?>
                                            </span></h5>
                                    </div>
                                    <div class="col-md-3">
                                        <h5>Entry date : <span style="color: #f22;">
                                                <?= $nql['entry_date']; ?>
                                            </span></h5>
                                    </div>
                                <?php }
                            } ?>

                            <div class="col-md-12">
                                <br>
                                <h5 style="text-decoration: underline;">Style Detail</h5>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="date" class="fieldrequired">Date :</label>
                                <input type="date" value="<?= $sql['date'] ? $sql['date'] : date('Y-m-d'); ?>" name="date" id="date" class="form-control" required>
                                <!-- <input type="text" value="<? //= $sql['date'] ? date('d-m-y', strtotime($sql['date'])) : date('d-m-y'); ?>" name="date" id="date" class="form-control date-picker"> -->
                            </div>
                            
                            <div class="col-md-2">
                                <label for="so_id" class="fieldrequired">BO No :</label>
                                <div class="form-group">
                                    <select name="order_id" id="order_id" class="custom-select2" style="width: 100%;">
                                        <?= select_dropdown('sales_order', array('id', 'order_code'), 'id DESC', $sql['order_id'], ' WHERE is_approved = "approved" AND is_dispatch IS NULL ', ''); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="styleList" class="fieldrequired">Style No :</label>
                                <select name="styleList" id="styleList" class="custom-select2" style="width: 100%;"></select>
                                <input type="hidden" name="oldstyle" id="oldstyle" value="<?= $sql['style'] ? $sql['style'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="part" class="fieldrequired">Combo :</label>
                                <select name="combo_id" id="combo_id" class="custom-select2" style="width: 100%;"></select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="part" class="fieldrequired">Part & Color :</label>
                                <select name="sod_part_id" id="sod_part_id" class="custom-select2" style="width: 100%;"></select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="employee">Employee :</label>
                                <i class="icon-copy fa fa-plus-circle addicon d-none" aria-hidden="true"
                                    data-toggle="modal" data-target="#employee-add-modal" title="Add Employee"></i>
                                <select name="employee" id="employee" class="custom-select2" style="width: 100%;">
                                    <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', ($sql['employee'] ? $sql['employee'] : $logUser), ' WHERE is_active="active"', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-12">
                                <!-- <hr> -->
                                <h5 style="text-decoration: underline;">Fabric Detail</h5>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="fabric">Fabric :</label>
                                <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal"
                                    data-target="#fabric-add-modal" title="Add Fabric"></i>
                                <select name="fabric" id="fabric" class="custom-select2" style="width: 100%;">
                                    <?= select_dropdown('fabric', array('id', 'fabric_name'), 'fabric_name ASC', $sql['fabric'], '', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="fabric_color">Fabric Color :</label>
                                <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal"
                                    data-target="#color-add-modal" title="Add Color"></i>
                                <select name="fabric_color" id="fabric_color" class="custom-select2" style="width: 100%;">
                                    <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', $sql['fabric_color'], '', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="fabric_lot">Fabric Lot :</label>
                                <input type="text" name="fabric_lot" id="fabric_lot" class="form-control" value="<?= $sql['fabric_lot'] ? $sql['fabric_lot'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="gsm">GSM :</label>
                                <input type="text" name="gsm" id="gsm" class="form-control" value="<?= $sql['gsm'] ? $sql['gsm'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="dia">Dia :</label>
                                <input type="text" name="dia" id="dia" class="form-control" value="<?= $sql['dia'] ? $sql['dia'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="wt">Wt :</label>
                                <input type="text" name="wt" id="wt" class="form-control" value="<?= $sql['wt'] ? $sql['wt'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-12">
                                <!-- <hr> -->
                                <h5 style="text-decoration: underline;">Lay Detail</h5>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="lay_length">Lay Length :</label>
                                <input type="text" name="lay_length" id="lay_length" class="form-control" value="<?= $sql['lay_length'] ? $sql['lay_length'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="per_lay_no">Per Lay No of Pcs :</label>
                                <input type="text" name="per_lay_no" id="per_lay_no" class="form-control" value="<?= $sql['per_lay_no'] ? $sql['per_lay_no'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="per_day_wt">Per Lay Wt :</label>
                                <input type="text" name="per_day_wt" id="per_day_wt" class="form-control" value="<?= $sql['per_day_wt'] ? $sql['per_day_wt'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="no_of_lay">No of Lay :</label>
                                <input type="text" name="no_of_lay" id="no_of_lay" class="form-control" value="<?= $sql['no_of_lay'] ? $sql['no_of_lay'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="total_taken_wt">Total Taken Wt :</label>
                                <input type="text" name="total_taken_wt" id="total_taken_wt" class="form-control" value="<?= $sql['total_taken_wt'] ? $sql['total_taken_wt'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="reject_wt">Reject Wt :</label>
                                <input type="text" name="reject_wt" id="reject_wt" class="form-control" value="<?= $sql['reject_wt'] ? $sql['reject_wt'] : ''; ?>">
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="tab new_lay d-none">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="laytab_New" role="tabpanel">
                                    <div class="pd-20" style="overflow-y: auto;">
                                        <p style="text-decoration: underline;">Enter Bundle Details for Lay- <span id="lay_ref"></span></p><br>
                                        <input type="hidden" name="lay_number" id="lay_number">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr style="background-color: #d7d7d7;">
                                                    <th><label>Size</label></th>
                                                    <th><label>Planned Qty</label></th>
                                                    <th><label>Already Completed Qty</label></th>
                                                    <th><label>This Lay Qty</label></th>
                                                    <th><label>Pcs Per Bundle</label></th>
                                                    <th><label>No Of Bundle</label></th>
                                                    <th><label>From Bundle No</label></th>
                                                    <th><label>To Bundle No</label><input type="hidden" name="" id="tempClass"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="lay_det_div"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="text-align:center;" class="save_div d-none">
                            <a href="view-barcode.php" type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</a>
                            <button type="submit" id="saveBarcode" name="saveBarcode" class="btn btn-outline-primary">save</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="referenceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cutting Quantity Reference</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped">
                                <tr><td>Lay Qty : <span id="cutbltd"></span></td></tr>
                                <tr><td>Stock Qty : <span id="stocktd"></span></td></tr>
                                <tr><td>Balance Qty : <span id="balatd"></span></td></tr>
                                <tr><td>Cuttable Qty : <span id="cutttd"></span></td></tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>


    <script>
        function referenceModal(stock, total, i) {
            var a = $("#cutting_qty" + i).val();

            b = parseInt(total) - (parseInt(a) + parseInt(stock));

            $("#cutbltd").text(a);
            $("#stocktd").text(stock);
            $("#balatd").text(b);
            $("#cutttd").text(total);
            $("#referenceModal").modal('show');
        }
    </script>

    <script>
        $('#color-add-modal').on('shown.bs.modal', function () {
            $('#merchand_name').focus();
        })
    </script>

    <script>
        function pcsss(id) {

            // var max = $("#maxQtty" + id).val();
            // var min = $("#cutting_qty" + id).val();
            // var oq = $("#order_qty" + id).val();

            // if (parseInt(oq) < (parseInt(max) + parseInt(min))) {
            //     message_noload('warning', 'Order Quantity Exceeded!', 3000);

            //     sa = parseInt(oq) - parseInt(max);
            //     $("#cutting_qty" + id).val(sa);
            //     $("#cutting_qty" + id).select();
            // }

            checkQtyvalidation1(id);
            // checkQtyvalidation(id);

        };
        
        function checkQtyvalidation1(id) {

            var ppb = $("#pcs_per_bundle" + id).val();
            var tbn = $("#noOfbundle" + id).val();

            var cq = ppb * tbn;

            var max = $("#maxQtty" + id).val();
            var oq = $("#order_qty" + id).val();

            if (parseInt(oq) < (parseInt(max) + parseInt(cq))) {
                message_noload('warning', 'Order Quantity Exceeded!', 3000);

                sa = parseInt(oq) - parseInt(max);

                $("#cutting_qty" + id).val(0);
                $("#noOfbundle" + id).val(0);
                $("#pcs_per_bundle" + id).val(0);
                $("#pcs_per_bundle" + id).select();
            } else {
                $("#cutting_qty" + id).val(cq);
            }


            var key = $("#cutting_qty" + id).attr('data-key');

            var smV = 0;
            $(".totcutting" + key).each(function () {
                smV += parseInt($(this).val());
            })

            if (parseInt(oq) < parseInt(smV)) {
                message_noload('warning', 'Order Quantity Exceeded!', 3000);

                sa = parseInt(oq) - parseInt(max);

                $("#cutting_qty" + id).val(0);
                $("#noOfbundle" + id).val(0);
                $("#pcs_per_bundle" + id).val(0);
                $("#pcs_per_bundle" + id).select();
            }



            $(".frombundleno").each(function (index) {
                var a = $(this).attr('data-id');


                var to = $("#tobundleno" + a).val();
                var cutting_qty = $("#cutting_qty" + a).val();
                var from = $("#frombundleno" + a).val();
                var totBun = $("#noOfbundle" + a).val();

                // 06-1-23 changes start
                var ppb = $("#pcs_per_bundle" + a).val();
                dv1 = parseInt(cutting_qty) / ppb;
                if ($.isNumeric(dv1)) {
                    var dv = dv1;
                } else {
                    var dv = 0;
                }
                // var dv = isNaN(dv1) ? 0 : dv1;
                if (index == 0) {
                    ji = a - 1;
                } else {
                    ji = $("#tempClass").val();//a - 1;
                }

                if (ji >= 0) {
                    var nw = $("#tobundleno" + ji).val();

                    df = parseInt(nw) + 1;

                    dt = parseInt(nw) + parseInt(dv);
                } else {
                    df = $("#frombundleno" + a).val();
                    dt = parseInt(df) + parseInt(dv) - 1;
                }
                $("#noOfbundle" + a).val(dv);
                $("#frombundleno" + a).val(df);
                $("#tobundleno" + a).val(dt);

                $("#tempClass").val(a);
            })


        }

        function checkQtyvalidation(id) {
            var to = $("#tobundleno" + id).val();
            var cutting_qty = $("#cutting_qty" + id).val();
            var from = $("#frombundleno" + id).val();
            var totBun = $("#noOfbundle" + id).val();

            // 06-1-23 changes start
            var ppb = $("#pcs_per_bundle" + id).val();
            dv1 = parseInt(cutting_qty) / ppb;
            if ($.isNumeric(dv1)) {
                var dv = dv1;
            } else {
                var dv = 0;
            }
            // var dv = isNaN(dv1) ? 0 : dv1;

            ji = id - 1;
            if (ji >= 0) {
                var nw = $("#tobundleno" + ji).val();

                df = parseInt(nw) + 1;

                dt = parseInt(nw) + parseInt(dv);
            } else {
                df = $("#frombundleno" + id).val();
                dt = parseInt(df) + parseInt(dv) - 1;
            }
            $("#noOfbundle" + id).val(dv);
            $("#frombundleno" + id).val(df);
            $("#tobundleno" + id).val(dt);


        }
    </script>

    <script type="text/javascript">

        $(function () {
            $('#saveBarcodeForm').validate({
                errorClass: "help-block",
                rules: {
                    date: {
                        required: true
                    },
                    order_id: {
                        required: true
                    },
                    styleList: {
                        required: true
                    },
                    part: {
                        required: true
                    },
                    color: {
                        required: true
                    },
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
        function trOf(id, type, qty, variation, order_qty, exs, sod_size_id) {
            
            $("#overlay").fadeIn(100);
            $("#addIcon" + id).hide();
            
            setTimeout(function () {
                $("#addIcon" + id).show();
                var now = new Date();
                var nnid = [
                    now.getHours(),
                    now.getMinutes(),
                    now.getSeconds()
                ].join('');
                
                var a = '<tr id="dell' + nnid + '">';
                a += '<td><input type="hidden" name="sod_size_id[]" id="sod_size_id' + nnid + '" value="' + sod_size_id + '">  <input type="hidden" name="variation_value[]" id="variation_value' + nnid + '" value="' + variation + '">';
                a += '<input type="hidden" name="" id="" value=""> <input type="hidden" name="order_qty[]" id="order_qty' + nnid + '" value="' + exs + '"> ' + type + ' </td>';
                a += '<td> ' + qty + ' </td> <td> ' + exs + ' </td>';
                a += '<td><input type="text" class="form-control totcutting' + id + '" data-key="' + id + '" name="cutting_qty[]" id="cutting_qty' + nnid + '" readonly></td>';
                a += '<td><input type="text" class="form-control" name="pcs_per_bundle[]" id="pcs_per_bundle' + nnid + '" onkeyup="pcsss(' + nnid + ')"></td>';
                a += '<td><input type="text" class="form-control" name="noOfbundle[]" id="noOfbundle' + nnid + '" onkeyup="pcsss(' + nnid + ')"></td>';
                a += '<td><input type="text" data-id="' + nnid + '" class="form-control frombundleno" name="frombundleno[]" id="frombundleno' + nnid + '" readonly></td>';
                a += '<td class="d-flex"><input type="text" class="form-control" name="tobundleno[]" id="tobundleno' + nnid + '" readonly style="width:80%;"> <span class="border-success rounded">';
                a += '<i class="icon-copy fa fa-trash" aria-hidden="true" onclick="removeRow(' + nnid + ')" title="Remove" style="padding: 9px;border: 1px solid #ccc;"></i></span> </td> </tr>';
                
                // var a = $("#trOf" + id).clone().insertAfter("#trOf" + id);
                // $("#bundleDiv").after(a);
                $("#trOf" + id).after(a);
                $("#overlay").fadeOut(500);
            }, 1000);
        }
        
        function removeRow(id) {
            $("#overlay").fadeIn(100);
            $("#dell" +id).remove();
            $("#overlay").fadeOut(500);
        }
    </script>
    
    <script>
        $("#sod_part_id").change(function () {
            
            var id = $(this).val();
            $("#overlay").fadeIn(100);
            
            if(id=="") {
                // $("#sod_part_id").val('').trig;
                
                $(".save_div").addClass('d-none');
                $(".new_lay").addClass('d-none');
                $("#overlay").fadeOut(500);
            } else {
                
                var data = $("#saveBarcodeForm").serialize();
                $("#lay_det_div").html('');
                
                $.ajax({
                    type: 'POST',
                    url: 'ajax_search.php?get_new_lay=1',
                    data: data,
                    success: function (msg) {
                        var json = $.parseJSON(msg);
                        
                        if(json.err==1) {
                            
                            $("#lay_ref").text('');
                            $("#lay_number").val('');
                            $(".save_div").addClass('d-none');
                            $(".new_lay").addClass('d-none');
                            $("#overlay").fadeOut(100);
                            
                            message_noload(''+ json.notif +'', ''+ json.message +'');
                            return false;
                        } else {
                            $("#lay_ref").text(json.lay_ref);
                            $("#lay_number").val(json.lay_ref);
                            $("#lay_det_div").html(json.lay_div);
                            
                            $(".save_div").removeClass('d-none');
                            $(".new_lay").removeClass('d-none');
                            $("#overlay").fadeOut(500);
                            
                        }
                    }
                });
            }
        })
    </script>
    
    <script>
        $("#combo_id").change(function () {
            
            var combo_id = $(this).val();
            var style = $("#styleList").val();
            
            if(combo_id=="") {
                $("#sod_part_id").html('');
            } else {
                var data = 'combo_id='+ combo_id +'&style=' + style;
                
                $.ajax({
                    type: 'POST',
                    url: 'ajax_search.php?get_combo_details=1',
                    data: data,
                    success: function (msg) {
                        var json = $.parseJSON(msg);
                        $("#sod_part_id").html(json.option);
                    }
                });
            }
        });
    </script>
    
    
    <script>
        $("#styleList").change(function () {
            
            var id = $(this).val();
            var data = 'id='+ id;
            if(id=="") {
                $("#combo_id").html('');
                $("#sod_part_id").html('');
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_search.php?get_combo_names=1',
                    data: data,
                    success: function (msg) {
                        var json = $.parseJSON(msg);
                        
                        if(json.err==1) {
                            message_noload(''+ json.notif +'', ''+ json.message +'');
                            return false;
                        } else {
                            $("#combo_id").html(json.option);
                            $("#sod_part_id").html('');
                        }
                    }
                });
            }
        })
    </script>
    
    
    <script>
        $("#order_id").change(function () {
            var id = $(this).val();
            okao(id);
        });
        
        function okao(id) {
            var a = $("#oldstyle").val();
            
            if(id=="") {
                $("#styleList").html('');
                $("#combo_id").html('');
                $("#sod_part_id").html('');
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_search.php?getStyleNo=1&id=' + id + '&oldstyle=' + a,
                    success: function (msg) {
                        var json = $.parseJSON(msg);
                        $("#styleList").html(json.option);
                        $("#combo_id").html('');
                        $("#sod_part_id").html('');
                    }
                });
            }
        }
    </script>



</body>

</html>