<?php
include("includes/connection.php");
include("includes/function.php");

$data = array();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.*, b.order_code FROM cutting_barcode a LEFT JOIN sales_order b ON a.order_id=b.id WHERE a.id=" . $id));
} else {
    $id = '';
}



if (isset($_POST['saveBarcode'])) {

    $myql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order WHERE id='" . $_REQUEST['order_id'] . "'"));

    $exp = explode('--', $_REQUEST['part']);

    $data = array(
        'order_id' => $_REQUEST['order_id'],
        'order_code' => $myql['order_code'],
        'date' => $_REQUEST['date'],
        'style' => $_REQUEST['styleList'],
        'part' => $exp[0],
        'color' => $_REQUEST['color'],
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
        'created_date' => date('Y-m-d H:i:s'),
    );

    if ($_REQUEST['cutting_barcode_id'] == "") {
        $qry = Insert('cutting_barcode', $data);
        $inid = mysqli_insert_id($mysqli);
    } else {
        $qry = Update('cutting_barcode', $data, " WHERE id = '" . $_REQUEST['cutting_barcode_id'] . "'");
        $inid = $_REQUEST['cutting_barcode_id'];
    }

    if ($sql['style'] != $_REQUEST['styleList']) {
        mysqli_query($mysqli, "DELETE FROM bundle_details WHERE cutting_barcode_id=" . $_REQUEST['cutting_barcode_id']);
    }

    $mys = mysqli_fetch_array(mysqli_query($mysqli, "SELECT max(lay_length) as lay_length FROM bundle_details WHERE cutting_barcode_id='" . $inid . "' ORDER BY id DESC"));
    $llth = $mys['lay_length'] + 1;

    for ($m = 0; $m < count($_REQUEST['variation_value']); $m++) {

        foreach (range($_REQUEST['frombundleno'][$m], $_REQUEST['tobundleno'][$m]) as $number) {
            // print_r($number);

            $mdata = array(
                'cutting_barcode_id' => $inid,
                'lay_length' => $llth,
                'variation_value' => $_REQUEST['variation_value'][$m],
                'order_qty' => $_REQUEST['order_qty'][$m],
                'cutting_qty' => $_REQUEST['cutting_qty'][$m],
                'pcs_per_bundle' => $_REQUEST['pcs_per_bundle'][$m],
                'total_bundle' => $_REQUEST['noOfbundle'][$m],
                'bundle_number' => $number,



                // 'from_bundle' => $_REQUEST['frombundleno'][$m],
                // 'to_bundle' => $_REQUEST['tobundleno'][$m],
                // 'lay_qty' => $_REQUEST['qtyTodayInp'][$m],
                // 'completed' => $_REQUEST['qtytotalInp'][$m],
                // 'balance' => $_REQUEST['qtybalanceInp'][$m],
                'created_date' => date('Y-m-d H:i:s'),
            );
            // if (!empty($_REQUEST['bundle_details'][$m])) {
            //     $qry = Update('bundle_details', $mdata, " WHERE id = '" . $_REQUEST['bundle_details'][$m] . "'");
            // } else {
            if ($_REQUEST['cutting_qty'][$m] > 0 && $_REQUEST['pcs_per_bundle'][$m] > 0) {
                $qry = Insert('bundle_details', $mdata);
            }
            // }
        }
    }
    // exit;
    // $qry = Update('budget_process', $data, " WHERE id = '" . $_REQUEST['budget_process'][$k] . "'");


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
                            <?php if (isset($_GET['id'])) { ?>
                                <div class="col-md-12">
                                    <h5>Reference Code <span style="color: #f22;text-decoration: underline;">
                                            <?= $sql['ref_id'] ?>
                                        </span></h5>
                                </div>
                            <?php } ?>

                            <div class="col-md-12">
                                <br>
                                <h5 style="text-decoration: underline;">Style Detail</h5>
                            </div>

                            <div class="col-md-2">
                                <label for="date" class="fieldrequired">Date :</label>
                                <input type="text" value="<?= $sql['date'] ? $sql['date'] : date('Y-m-d'); ?>"
                                    name="date" id="date" class="form-control date-picker">
                            </div>

                            <div class="col-md-2">
                                <label for="so_id" class="fieldrequired">BO No :</label> <br>
                                <select name="order_id" id="order_id" class="custom-select2 form-control">
                                    <?= select_dropdown('sales_order', array('id', 'order_code'), 'order_code DESC', $sql['order_id'], '', ''); ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="styleList" class="fieldrequired">Style No :</label>
                                <select name="styleList" id="styleList" class="custom-select2 form-control"></select>
                                <input type="hidden" name="oldstyle" id="oldstyle"
                                    value="<?= $sql['style'] ? $sql['style'] : ''; ?>">
                            </div>

                            <div class="col-md-2">
                                <label for="part">Part :</label>
                                <select name="part" id="part" class="custom-select2 form-control"></select>
                            </div>

                            <div class="col-md-2">
                                <label for="color">Color :</label>
                                <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal"
                                    data-target="#color-add-modal" title="Add Color"></i>
                                <select name="color" id="color" class="custom-select2 form-control">
                                    <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', $sql['color'], ' where id=' . $sql['color'], ''); ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="employee">Employee :</label>
                                <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal"
                                    data-target="#employee-add-modal" title="Add Employee"></i>
                                <select name="employee" id="employee" class="custom-select2 form-control">
                                    <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $sql['employee'], '', ''); ?>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <br>
                                <h5 style="text-decoration: underline;">Fabric Detail</h5>
                            </div>

                            <div class="col-md-2">
                                <label for="fabric">Fabric :</label>
                                <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal"
                                    data-target="#fabric-add-modal" title="Add Fabric"></i>
                                <select name="fabric" id="fabric" class="custom-select2 form-control">
                                    <?= select_dropdown('fabric', array('id', 'fabric_name'), 'fabric_name ASC', $sql['fabric'], '', ''); ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="fabric_color">Fabric Color :</label>
                                <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal"
                                    data-target="#color-add-modal" title="Add Color"></i>
                                <select name="fabric_color" id="fabric_color" class="custom-select2 form-control">
                                    <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', $sql['fabric_color'], '', ''); ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="fabric_lot">Fabric Lot :</label>
                                <input type="text" name="fabric_lot" id="fabric_lot" class="form-control"
                                    value="<?= $sql['fabric_lot'] ? $sql['fabric_lot'] : ''; ?>">
                            </div>

                            <div class="col-md-2">
                                <label for="gsm">GSM :</label>
                                <input type="text" name="gsm" id="gsm" class="form-control"
                                    value="<?= $sql['gsm'] ? $sql['gsm'] : ''; ?>">
                            </div>

                            <div class="col-md-2">
                                <label for="dia">Dia :</label>
                                <input type="text" name="dia" id="dia" class="form-control"
                                    value="<?= $sql['dia'] ? $sql['dia'] : ''; ?>">
                            </div>

                            <div class="col-md-2">
                                <label for="wt">Wt :</label>
                                <input type="text" name="wt" id="wt" class="form-control"
                                    value="<?= $sql['wt'] ? $sql['wt'] : ''; ?>">
                            </div>

                            <div class="col-md-12">
                                <br>
                                <h5 style="text-decoration: underline;">Lay Detail</h5>
                            </div>

                            <div class="col-md-2">
                                <label for="lay_length">Lay Length :</label>
                                <input type="text" name="lay_length" id="lay_length" class="form-control"
                                    value="<?= $sql['lay_length'] ? $sql['lay_length'] : ''; ?>">
                            </div>

                            <div class="col-md-2">
                                <label for="per_lay_no">Per Lay No of Pc :</label>
                                <input type="text" name="per_lay_no" id="per_lay_no" class="form-control"
                                    value="<?= $sql['per_lay_no'] ? $sql['per_lay_no'] : ''; ?>">
                            </div>

                            <div class="col-md-2">
                                <label for="per_day_wt">Per Lay Wt :</label>
                                <input type="text" name="per_day_wt" id="per_day_wt" class="form-control"
                                    value="<?= $sql['per_day_wt'] ? $sql['per_day_wt'] : ''; ?>">
                            </div>

                            <div class="col-md-2">
                                <label for="no_of_lay">No of Lay :</label>
                                <input type="text" name="no_of_lay" id="no_of_lay" class="form-control"
                                    value="<?= $sql['no_of_lay'] ? $sql['no_of_lay'] : ''; ?>">
                            </div>

                            <div class="col-md-2">
                                <label for="total_taken_wt">Total Taken Wt :</label>
                                <input type="text" name="total_taken_wt" id="total_taken_wt" class="form-control"
                                    value="<?= $sql['total_taken_wt'] ? $sql['total_taken_wt'] : ''; ?>">
                            </div>

                            <div class="col-md-2">
                                <label for="reject_wt">Reject Wt :</label>
                                <input type="text" name="reject_wt" id="reject_wt" class="form-control"
                                    value="<?= $sql['reject_wt'] ? $sql['reject_wt'] : ''; ?>">
                            </div>

                        </div>

                        <hr>
                        <div class="tab">
                            <ul class="nav nav-tabs customtab" role="tablist">

                                <?php
                                $kn = mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE cutting_barcode_id='" . $_GET['id'] . "' GROUP BY lay_length ASC");

                                while ($opl = mysqli_fetch_assoc($kn)) {
                                    $lay_det[] = $opl;
                                }

                                $x = 1;
                                foreach ($lay_det as $k => $v) {
                                    ?>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#lay_tab<?= $x; ?>" role="tab"
                                            aria-selected="true">Lay
                                            <?= $x; ?>
                                        </a>
                                    </li>
                                    <?php $x++;
                                } ?>
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#laytab_New" role="tab"
                                        aria-selected="false">Lay
                                        <?= $x; ?>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <?php
                                $y = 1;
                                foreach ($lay_det as $k => $v) { ?>
                                    <div class="tab-pane fade " id="lay_tab<?= $y; ?>" role="tabpanel">
                                        <div class="pd-20" style="overflow-y: scroll;">

                                            <h5 style="text-decoration: underline;">Bundle Detail - Lay
                                                <?= $y; ?>
                                            </h5>

                                            <table class="table">
                                                <thead>
                                                    <tr style="background-color: #d7d7d7;">
                                                        <th class="table-plus datatable-nosort"><label for="">Size</label>
                                                        </th>
                                                        <th><label for="">Order Qty</label></th>
                                                        <th><label for="">Cutting Qty</label></th>
                                                        <th><label for="">Pcs Per Bundle</label></th>
                                                        <th><label for="">No Of Bundle</label></th>
                                                        <th><label for="">From Bundle No</label></th>
                                                        <th><label for="">To Bundle No</label></th>
                                                        <th><label for="">Created Date</label></th>
                                                    </tr>
                                                </thead>
                                                <tbody style="">
                                                    <?php

                                                    $toB = $formB = $pcsB = $lay_qty = $completed = $balance = 0;

                                                    $sqql = mysqli_query($mysqli, "SELECT a.*, min(a.bundle_number) as bundle_start, max(a.bundle_number) as bundle_end, b.type FROM bundle_details a LEFT JOIN variation_value b ON a.variation_value=b.id WHERE a.cutting_barcode_id='" . $_GET['id'] . "' AND a.lay_length = '" . $y . "' GROUP BY a.variation_value ASC");
                                                    $m = 1;
                                                    while ($result = mysqli_fetch_array($sqql)) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?= $result['type']; ?>
                                                            </td>
                                                            <td>
                                                                <?= $result['order_qty']; ?>
                                                            </td>
                                                            <td>
                                                                <?= $result['cutting_qty']; ?>
                                                            </td>
                                                            <td>
                                                                <?= $result['pcs_per_bundle']; ?>
                                                            </td>
                                                            <td>
                                                                <?= $result['total_bundle']; ?>
                                                            </td>
                                                            <td>
                                                                <?= $result['bundle_start']; ?>
                                                            </td>
                                                            <td>
                                                                <?= $result['bundle_end']; ?>
                                                            </td>
                                                            <td>
                                                                <?= $result['created_date']; ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $toB += $result['to_bundle'];
                                                        $formB += $result['from_bundle'];
                                                        $pcsB += $result['cutting_qty'];
                                                        $lay_qty += $result['lay_qty'];
                                                        $completed += $result['completed'];
                                                        $balance += $result['balance'];
                                                        $m++;
                                                    } ?>
                                                </tbody>
                                                <tfoot style="background-color: #f9f9f9;display:none">
                                                    <tr>
                                                        <td><label for="">Summary</label></td>
                                                        <td></td>
                                                        <td>
                                                            <?= $pcsB; ?>
                                                        </td>
                                                        <td>
                                                            <? //= $formB; ?>
                                                        </td>
                                                        <td>
                                                            <? //= $toB; ?>
                                                        </td>
                                                        <td>
                                                            <?= $lay_qty; ?>
                                                        </td>
                                                        <td>
                                                            <?= $completed; ?>
                                                        </td>
                                                        <td>
                                                            <?= $balance; ?>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <?php $y++;
                                } ?>
                                <div class="tab-pane fade show active" id="laytab_New" role="tabpanel">
                                    <div class="pd-20" style="overflow-y: scroll;">

                                        <h5 style="text-decoration: underline;">Bundle Detail - Lay
                                            <?= $y; ?>
                                        </h5>

                                        <table class="table">
                                            <thead>
                                                <tr style="background-color: #d7d7d7;">
                                                    <th class="table-plus datatable-nosort"><label for="">Size</label>
                                                    </th>
                                                    <th><label for="">Order Qty</label></th>
                                                    <th><label for="">Cutting Qty</label></th>
                                                    <th><label for="">Pcs Per Bundle</label></th>
                                                    <th><label for="">No Of Bundle</label></th>
                                                    <th><label for="">From Bundle No</label></th>
                                                    <th><label for="">To Bundle No</label></th>
                                                </tr>
                                            </thead>
                                            <tbody id="bundleDiv"></tbody>
                                            <tfoot style="background-color: #f9f9f9;display:none">
                                                <tr>
                                                    <td><label for="">Summary</label></td>
                                                    <td id="noOfbundletd"></td>
                                                    <td id="pcstd">0</td>
                                                    <td id="frombundlenotd">0</td>
                                                    <td id="tobundlenotd">0</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div style="text-align:center;">
                            <a href="view-barcode.php" type="button" class="btn btn-secondary"
                                data-dismiss="modal">Cancel</a>
                            <button type="submit" id="saveBarcode" name="saveBarcode"
                                class="btn btn-primary">save</button>
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
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        $('#color-add-modal').on('shown.bs.modal', function () {
            $('#merchand_name').focus();
        })
    </script>

    <script>
        function pcsss(id) {

            var max = $("#maxQtty" + id).val();
            var min = $("#cutting_qty" + id).val();
            var oq = $("#order_qty" + id).val();

            if (parseInt(oq) < (parseInt(max) + parseInt(min))) {
                message_noload('warning', 'Order Quantity Exceeded!', 3000);

                sa = parseInt(oq) - parseInt(max);
                $("#cutting_qty" + id).val(sa);
                $("#cutting_qty" + id).select();
            }

            checkQtyvalidation(id);
            var a = 0;
            $(".cutting_qty").each(function () {
                a += +$(this).val();
            });
            $("#pcstd").html(a);

            // var b = 0;
            // $(".noOfbundle").each(function () {
            //     b += +$(this).val();
            // });
            // $("#noOfbundletd").html(b);

        };

        // function verify_max(id) {
        //     var max = $("#noOfbundle" + id).val();
        //     var to = $("#tobundleno" + id).val();

        //     if (parseInt(to) > parseInt(max)) {
        //         message_noload('warning', 'Max Bundle num is "' + max + '" You entered "' + to + '" by mistake!', 3000);

        //         $("#tobundleno" + id).val(max);
        //         $("#tobundleno" + id).select();
        //         var to = cutting_qty;
        //     } else {
        //         var to = to;
        //     }
        // }

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

            // 06-1-23 changes end


            // if (parseInt(to) > parseInt(pcs)) {
            //     message_noload('warning', 'Entered Qty is Greater Than a Pice!', 3000);
            //     $("#tobundleno" + id).val(pcs);
            //     $("#tobundleno" + id).select();
            //     var to = pcs;
            // } else {
            //     var to = to;
            // }

            // diff = (to - from) + 1;

            // $("#qtyToday" + id).text(diff);
            // $("#qtytotal" + id).text(to);
            // $("#qtybalance" + id).text(pcs - to);

            // $("#qtyTodayInp" + id).val(diff);
            // $("#qtytotalInp" + id).val(to);
            // $("#qtybalanceInp" + id).val(pcs - to);


        }
    </script>

    <script>
        $("#styleList").change(function () {

            var id = $(this).val();
            boundlelist(id);
        })

        function boundlelist(id) {
            var a = $("#cutting_barcode_id").val();
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getBundledet=1&id=' + id + '&cutting_barcode_id=' + a,
                success: function (msg) {
                    var json = $.parseJSON(msg);

                    // alert(json.part);
                    $("#part").html(json.part);
                    $("#bundleDiv").html(json.html);
                    $("#color_name").val(json.color_name);
                    // $("#color").val(json.color_id);
                    // $("#styleList").focus();
                    pcsss();

                    var part = $("#part").val();
                    // getPartColor(part)
                }
            })
        }
    </script>

    <script>
        $("#part").change(function () {
            var part = $(this).val();
            getPartColor(part);
        });

        function getPartColor(part) {
            var style = $("#styleList").val();

            var a = $("#saveBarcodeForm").serialize();
            // alert(a);

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getPartColor=1&id=' + style + '&part=' + part,
                data: a,
                success: function (msg) {
                    var json = $.parseJSON(msg);

                    window.location.href = "barcode.php?id=" + json.inid;
                }
            })
        }
    </script>

    <script>
        $(document).ready(function () {
            var a = $("#order_id").val();
            var stylelist = $("#styleList").val();
            okao(a);
        });
    </script>

    <script>
        $(function () {
            $("#so_id").autocomplete({
                source: "fetchData2.php",
                select: function (event, ui) {
                    event.preventDefault();
                    $("#so_id").val(ui.item.value);
                    $("#order_id").val(ui.item.id);
                    okao(ui.item.id);
                }
            });
        });

        $("#order_id").change(function () {
            var id = $(this).val();
            okao(id);
        })

        function okao(id) {
            var a = $("#oldstyle").val();

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getStyleNo=1&id=' + id + '&oldstyle=' + a,
                success: function (msg) {

                    $("#styleList").html(msg);
                    // $("#styleList").focus();


                    var stylelist = $("#styleList").val();
                    boundlelist(stylelist);
                }
            })
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



</body>

</html>